<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * Contact details plus the enquiry form handled in inc/contact.php.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_org    = kaao_org();
$kaao_result = kaao_contact_result();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/hero/page',
		null,
		array(
			'eyebrow'    => __( 'Contact', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Reach the KAAO Secretariat with a membership enquiry, a media request or an industry matter.', 'kaao' ),
			'image_slug' => 'kaao-banner-contact-nairobi-aerial-view',
		)
	);

	kaao_breadcrumbs();
	?>

	<div class="section">
		<div class="container">
			<div class="split split--wide-text" style="align-items:start">

				<div>
					<h2 class="h3"><?php esc_html_e( 'Send us a message', 'kaao' ); ?></h2>

					<?php if ( $kaao_result ) : ?>
						<div class="form-status form-status--<?php echo esc_attr( 'ok' === $kaao_result['status'] ? 'ok' : 'error' ); ?>"
							role="<?php echo 'ok' === $kaao_result['status'] ? 'status' : 'alert'; ?>">
							<?php echo esc_html( $kaao_result['message'] ); ?>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( (string) get_permalink() ); ?>#contact-form" id="contact-form" novalidate>
						<?php wp_nonce_field( 'kaao_contact', 'kaao_contact_nonce' ); ?>
						<input type="hidden" name="kaao_started" value="<?php echo esc_attr( (string) time() ); ?>">

						<?php // Honeypot — visually and programmatically hidden from real users. ?>
						<div class="visually-hidden" aria-hidden="true">
							<label for="kaao_website"><?php esc_html_e( 'Leave this field empty', 'kaao' ); ?></label>
							<input type="text" id="kaao_website" name="kaao_website" tabindex="-1" autocomplete="off">
						</div>

						<div class="grid grid--2" style="gap:0 var(--sp-5)">
							<div class="field">
								<label for="kaao_name">
									<?php esc_html_e( 'Your name', 'kaao' ); ?>
									<span class="required" aria-hidden="true">*</span>
								</label>
								<input type="text" id="kaao_name" name="kaao_name" required autocomplete="name"
									value="<?php echo esc_attr( isset( $_POST['kaao_name'] ) ? sanitize_text_field( wp_unslash( $_POST['kaao_name'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>">
							</div>

							<div class="field">
								<label for="kaao_email">
									<?php esc_html_e( 'Email address', 'kaao' ); ?>
									<span class="required" aria-hidden="true">*</span>
								</label>
								<input type="email" id="kaao_email" name="kaao_email" required autocomplete="email"
									value="<?php echo esc_attr( isset( $_POST['kaao_email'] ) ? sanitize_email( wp_unslash( $_POST['kaao_email'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>">
							</div>
						</div>

						<div class="field">
							<label for="kaao_organisation"><?php esc_html_e( 'Organisation', 'kaao' ); ?></label>
							<input type="text" id="kaao_organisation" name="kaao_organisation" autocomplete="organization"
								value="<?php echo esc_attr( isset( $_POST['kaao_organisation'] ) ? sanitize_text_field( wp_unslash( $_POST['kaao_organisation'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>">
						</div>

						<div class="field">
							<label for="kaao_subject"><?php esc_html_e( 'Subject', 'kaao' ); ?></label>
							<select id="kaao_subject" name="kaao_subject">
								<option value=""><?php esc_html_e( 'Select a subject', 'kaao' ); ?></option>
								<option value="<?php esc_attr_e( 'Membership enquiry', 'kaao' ); ?>"><?php esc_html_e( 'Membership enquiry', 'kaao' ); ?></option>
								<option value="<?php esc_attr_e( 'Advocacy or policy matter', 'kaao' ); ?>"><?php esc_html_e( 'Advocacy or policy matter', 'kaao' ); ?></option>
								<option value="<?php esc_attr_e( 'Events and engagements', 'kaao' ); ?>"><?php esc_html_e( 'Events and engagements', 'kaao' ); ?></option>
								<option value="<?php esc_attr_e( 'Media and press', 'kaao' ); ?>"><?php esc_html_e( 'Media and press', 'kaao' ); ?></option>
								<option value="<?php esc_attr_e( 'General enquiry', 'kaao' ); ?>"><?php esc_html_e( 'General enquiry', 'kaao' ); ?></option>
							</select>
						</div>

						<div class="field">
							<label for="kaao_message">
								<?php esc_html_e( 'Message', 'kaao' ); ?>
								<span class="required" aria-hidden="true">*</span>
							</label>
							<textarea id="kaao_message" name="kaao_message" required minlength="10" maxlength="5000"></textarea>
							<span class="hint"><?php esc_html_e( 'Please include any detail that will help us route your message to the right person.', 'kaao' ); ?></span>
						</div>

						<p style="font-size:var(--fs-xs);color:var(--kaao-muted)">
							<?php
							printf(
								/* translators: %s: privacy policy link. */
								esc_html__( 'We use the details you provide only to respond to your enquiry. See our %s.', 'kaao' ),
								'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'privacy policy', 'kaao' ) . '</a>'
							);
							?>
						</p>

						<button type="submit" name="kaao_contact_submit" value="1" class="btn btn--primary btn--lg">
							<?php esc_html_e( 'Send message', 'kaao' ); ?>
						</button>
					</form>
				</div>

				<aside>
					<h2 class="h3"><?php esc_html_e( 'Our offices', 'kaao' ); ?></h2>

					<dl class="factlist">
						<?php if ( $kaao_org['address'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Physical address', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_org['address'] ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_org['po_box'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Postal address', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_org['po_box'] ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_org['phone'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Phone', 'kaao' ); ?></dt>
								<dd><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $kaao_org['phone'] ) ); ?>"><?php echo esc_html( $kaao_org['phone'] ); ?></a></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_org['email'] ) : ?>
							<div>
								<dt><?php esc_html_e( 'Email', 'kaao' ); ?></dt>
								<dd><a href="mailto:<?php echo esc_attr( $kaao_org['email'] ); ?>"><?php echo esc_html( $kaao_org['email'] ); ?></a></dd>
							</div>
						<?php endif; ?>
					</dl>

					<?php if ( kaao_social_links() ) : ?>
						<h3 class="h4 mt-8"><?php esc_html_e( 'Follow KAAO', 'kaao' ); ?></h3>
						<div class="cluster">
							<?php foreach ( kaao_social_links() as $kaao_social ) : ?>
								<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $kaao_social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo kaao_icon( $kaao_social['icon'], array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<?php echo esc_html( $kaao_social['label'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="mt-8" style="padding:var(--sp-5);background:var(--kaao-surface);border-radius:var(--card-radius);border:1px solid var(--kaao-border)">
						<h3 class="h4"><?php esc_html_e( 'Looking to join?', 'kaao' ); ?></h3>
						<p class="muted" style="font-size:var(--fs-sm)">
							<?php esc_html_e( 'Membership enquiries are handled through the registration page, where you can download and submit the application form.', 'kaao' ); ?>
						</p>
						<a class="btn btn--navy btn--sm" href="<?php echo esc_url( home_url( '/member-registration/' ) ); ?>">
							<?php esc_html_e( 'Apply for membership', 'kaao' ); ?>
						</a>
					</div>
				</aside>
			</div>

			<?php if ( get_the_content() ) : ?>
				<div class="prose mt-8" style="max-width:none">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
endwhile;

get_footer();
