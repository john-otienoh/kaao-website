<?php
/**
 * Contact form handling.
 *
 * A small, dependency-free handler rather than a forms plugin: the site needs
 * one form, and the plugin the production site currently loads (WPForms) costs
 * several requests on every page including ones with no form at all.
 *
 * Protections: nonce, capability-free but rate-limited, honeypot, timing check,
 * strict validation, escaped output, no unsanitised header values.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle a submission. Runs before any output so a redirect is still possible.
 */
function kaao_handle_contact_form(): void {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}
	if ( ! isset( $_POST['kaao_contact_submit'] ) ) {
		return;
	}

	$nonce = isset( $_POST['kaao_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kaao_contact_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'kaao_contact' ) ) {
		kaao_contact_result( 'error', __( 'Your session expired. Please try sending the message again.', 'kaao' ) );
		return;
	}

	// Honeypot: a real visitor never fills a field they cannot see.
	$trap = isset( $_POST['kaao_website'] ) ? trim( (string) wp_unslash( $_POST['kaao_website'] ) ) : '';
	if ( '' !== $trap ) {
		kaao_contact_result( 'ok', __( 'Thank you — your message has been sent.', 'kaao' ) );
		return;
	}

	// Timing: submissions faster than three seconds are automated.
	$started = isset( $_POST['kaao_started'] ) ? (int) $_POST['kaao_started'] : 0;
	if ( $started && ( time() - $started ) < 3 ) {
		kaao_contact_result( 'error', __( 'That was submitted a little too quickly. Please try again.', 'kaao' ) );
		return;
	}

	// Rate limit per IP: five messages an hour is generous for a real enquiry.
	$bucket = 'kaao_cf_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$sent   = (int) get_transient( $bucket );
	if ( $sent >= 5 ) {
		kaao_contact_result( 'error', __( 'Too many messages have been sent from this connection. Please try again later, or email us directly.', 'kaao' ) );
		return;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['kaao_name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['kaao_email'] ?? '' ) );
	$org     = sanitize_text_field( wp_unslash( $_POST['kaao_organisation'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['kaao_subject'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['kaao_message'] ?? '' ) );

	$errors = array();
	if ( '' === $name ) {
		$errors[] = __( 'Please tell us your name.', 'kaao' );
	}
	if ( ! is_email( $email ) ) {
		$errors[] = __( 'Please enter a valid email address so we can reply.', 'kaao' );
	}
	if ( mb_strlen( $message ) < 10 ) {
		$errors[] = __( 'Please include a message of at least ten characters.', 'kaao' );
	}
	if ( mb_strlen( $message ) > 5000 ) {
		$errors[] = __( 'Please keep your message under 5,000 characters.', 'kaao' );
	}

	if ( $errors ) {
		kaao_contact_result( 'error', implode( ' ', $errors ) );
		return;
	}

	$to      = kaao_org_get( 'email' ) ?: get_option( 'admin_email' );
	$heading = $subject ?: __( 'Website enquiry', 'kaao' );

	$body = array(
		sprintf( /* translators: %s: sender name. */ __( 'Name: %s', 'kaao' ), $name ),
		sprintf( /* translators: %s: sender email. */ __( 'Email: %s', 'kaao' ), $email ),
	);
	if ( $org ) {
		$body[] = sprintf( /* translators: %s: organisation. */ __( 'Organisation: %s', 'kaao' ), $org );
	}
	$body[] = '';
	$body[] = $message;
	$body[] = '';
	$body[] = sprintf(
		/* translators: 1: site name, 2: page URL. */
		__( 'Sent from the %1$s website: %2$s', 'kaao' ),
		(string) get_bloginfo( 'name' ),
		home_url( add_query_arg( array() ) )
	);

	// From: our own domain — never the visitor's, which fails SPF/DKIM.
	$host    = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'From: %s <no-reply@%s>', (string) get_bloginfo( 'name' ), preg_replace( '/^www\./', '', $host ) ),
		sprintf( 'Reply-To: %s <%s>', $name, $email ),
	);

	$sent_ok = wp_mail(
		$to,
		sprintf( '[%s] %s', (string) get_bloginfo( 'name' ), $heading ),
		implode( "\n", $body ),
		$headers
	);

	set_transient( $bucket, $sent + 1, HOUR_IN_SECONDS );

	if ( $sent_ok ) {
		kaao_contact_result( 'ok', __( 'Thank you — your message has been sent. We will be in touch.', 'kaao' ) );
	} else {
		kaao_contact_result(
			'error',
			sprintf(
				/* translators: %s: email address. */
				__( 'The message could not be sent from the website. Please email us directly at %s.', 'kaao' ),
				kaao_org_get( 'email' )
			)
		);
	}
}
add_action( 'template_redirect', 'kaao_handle_contact_form' );

/**
 * Stash or read the submission result for this request.
 *
 * @param string|null $status  'ok' or 'error' when setting.
 * @param string      $message Message when setting.
 * @return array{status:string,message:string}|null
 */
function kaao_contact_result( ?string $status = null, string $message = '' ): ?array {
	static $result = null;
	if ( null !== $status ) {
		$result = array(
			'status'  => $status,
			'message' => $message,
		);
	}

	return $result;
}

/**
 * On a local install without a mail transport, log the message instead of
 * failing silently so the form can still be demonstrated.
 *
 * @param WP_Error $error Mail error.
 */
function kaao_log_failed_mail( WP_Error $error ): void {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( '[KAAO contact form] wp_mail failed: ' . $error->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
add_action( 'wp_mail_failed', 'kaao_log_failed_mail' );
