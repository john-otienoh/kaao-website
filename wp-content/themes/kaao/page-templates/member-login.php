<?php
/**
 * Template Name: Member Login
 * Template Post Type: page
 *
 * Branded authentication screen for KAAO members. Form submission is handled
 * by kaao_handle_member_login() in inc/member-access.php before this template
 * is rendered.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_redirect_to = isset( $_GET['redirect_to'] ) ? wp_unslash( $_GET['redirect_to'] ) : '';
$kaao_redirect_to = wp_validate_redirect( (string) $kaao_redirect_to, home_url( '/exchange-rates/' ) );
$kaao_login_state = isset( $_GET['login'] ) ? sanitize_key( wp_unslash( $_GET['login'] ) ) : '';

get_header();

?>
<section class="section" aria-labelledby="member-login-title">
	<div class="container container--narrow">
		<div class="kaao-member-login-card">
			<p class="eyebrow"><?php esc_html_e( 'Member area', 'kaao' ); ?></p>
			<h1 id="member-login-title"><?php esc_html_e( 'Member Login', 'kaao' ); ?></h1>
			<p><?php esc_html_e( 'Sign in to access KAAO member exchange rates.', 'kaao' ); ?></p>

			<?php if ( 'failed' === $kaao_login_state ) : ?>
					<div class="form-status form-status--error" role="alert">
						<?php esc_html_e( 'The username or password is incorrect, or this account does not have member access.', 'kaao' ); ?>
					</div>
				<?php elseif ( 'invalid-request' === $kaao_login_state ) : ?>
					<div class="form-status form-status--error" role="alert">
						<?php esc_html_e( 'Your login session expired. Please try again.', 'kaao' ); ?>
					</div>
				<?php endif; ?>

			<?php if ( kaao_can_view_exchange_rates() ) : ?>
					<div class="form-status form-status--ok" role="status">
						<?php esc_html_e( 'You are already signed in.', 'kaao' ); ?>
					</div>
					<p><a class="btn btn--primary" href="<?php echo esc_url( $kaao_redirect_to ); ?>"><?php esc_html_e( 'Continue to exchange rates', 'kaao' ); ?></a></p>
				<p><a href="<?php echo esc_url( wp_logout_url( kaao_member_login_url() ) ); ?>"><?php esc_html_e( 'Sign out', 'kaao' ); ?></a></p>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( kaao_member_login_url() ); ?>" class="kaao-member-login">
						<?php wp_nonce_field( 'kaao_member_login', 'kaao_member_login_nonce' ); ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $kaao_redirect_to ); ?>">

						<div class="field">
							<label for="kaao_member_login_username"><?php esc_html_e( 'Username', 'kaao' ); ?></label>
							<input id="kaao_member_login_username" name="log" type="text" required autocomplete="username" autocapitalize="none" spellcheck="false">
						</div>

						<div class="field">
							<label for="kaao_member_login_password"><?php esc_html_e( 'Password', 'kaao' ); ?></label>
							<input id="kaao_member_login_password" name="pwd" type="password" required autocomplete="current-password">
						</div>

						<label class="check"><input type="checkbox" name="rememberme" value="forever"> <?php esc_html_e( 'Remember me', 'kaao' ); ?></label>
						<p><button type="submit" name="kaao_member_login_submit" value="1" class="btn btn--primary btn--lg"><?php esc_html_e( 'Sign in', 'kaao' ); ?></button></p>
					</form>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
