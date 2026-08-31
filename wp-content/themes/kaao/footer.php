<?php
/**
 * The global footer.
 *
 * One copy, inherited by every template through get_footer().
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_org = kaao_org();

/**
 * Footer link columns. Menus override these when assigned, so KAAO staff can
 * edit the footer from Appearance → Menus without touching a template.
 */
$kaao_columns = array(
	'footer_1' => array(
		'title' => __( 'Quick links', 'kaao' ),
		'links' => array(
			array( __( 'About', 'kaao' ), '/about-us/' ),
			array( __( 'Membership', 'kaao' ), '/membership/' ),
			array( __( 'Members', 'kaao' ), '/members/' ),
			array( __( 'Advocacy', 'kaao' ), '/advocacy/' ),
			array( __( 'News', 'kaao' ), '/news/' ),
			array( __( 'Events', 'kaao' ), '/events/' ),
			array( __( 'Gallery', 'kaao' ), '/gallery/' ),
			array( __( 'Resources', 'kaao' ), '/resources/' ),
			array( __( 'Careers', 'kaao' ), '/careers/' ),
			array( __( 'Contact', 'kaao' ), '/contact-us/' ),
		),
	),
	'footer_2' => array(
		'title' => __( 'Membership', 'kaao' ),
		'links' => array(
			array( __( 'Why join KAAO', 'kaao' ), '/membership/' ),
			array( __( 'Membership categories', 'kaao' ), '/membership/categories/' ),
			array( __( 'Benefits', 'kaao' ), '/membership/benefits/' ),
			array( __( 'How to join', 'kaao' ), '/membership/how-to-join/' ),
			array( __( 'Apply', 'kaao' ), '/member-registration/' ),
			array( __( 'FAQs', 'kaao' ), '/faqs/' ),
		),
	),
	'footer_3' => array(
		'title' => __( 'Resources', 'kaao' ),
		'links' => array(
			array( __( 'Publications', 'kaao' ), '/resources/type/publications/' ),
			array( __( 'Reports', 'kaao' ), '/resources/type/reports/' ),
			array( __( 'Position papers', 'kaao' ), '/resources/type/position-papers/' ),
			array( __( 'Forms', 'kaao' ), '/resources/type/forms/' ),
			array( __( 'Downloads', 'kaao' ), '/resources/type/downloads/' ),
			array( __( 'Regulatory resources', 'kaao' ), '/resources/type/regulatory-resources/' ),
		),
	),
);
?>
</main><!-- #main -->

<footer class="kaao-footer" role="contentinfo">
	<div class="container">
		<div class="kaao-footer__main">

			<div class="kaao-footer__brand">
				<?php
				$kaao_footer_logo = kaao_attachment_id_by_slug( 'kaao-logo-reversed' ) ?: (int) get_theme_mod( 'custom_logo' );
				if ( $kaao_footer_logo ) {
					echo wp_get_attachment_image(
						$kaao_footer_logo,
						'medium',
						false,
						array(
							'alt'   => esc_attr__( 'Kenya Association of Air Operators', 'kaao' ),
							'style' => 'height:60px;width:auto',
						)
					);
				}
				?>
				<p><?php echo esc_html( $kaao_org['description'] ); ?></p>

				<?php if ( kaao_social_links() ) : ?>
					<div class="kaao-social">
						<?php foreach ( kaao_social_links() as $kaao_social ) : ?>
							<a href="<?php echo esc_url( $kaao_social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="screen-reader-text">
									<?php
									printf(
										/* translators: %s: network name. */
										esc_html__( 'KAAO on %s (opens in a new tab)', 'kaao' ),
										esc_html( $kaao_social['label'] )
									);
									?>
								</span>
								<?php echo kaao_icon( $kaao_social['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php foreach ( $kaao_columns as $kaao_location => $kaao_column ) : ?>
				<nav aria-labelledby="footer-<?php echo esc_attr( $kaao_location ); ?>">
					<h2 id="footer-<?php echo esc_attr( $kaao_location ); ?>"><?php echo esc_html( $kaao_column['title'] ); ?></h2>
					<?php
					if ( has_nav_menu( $kaao_location ) ) {
						wp_nav_menu(
							array(
								'theme_location' => $kaao_location,
								'container'      => false,
								'depth'          => 1,
								'items_wrap'     => '<ul>%3$s</ul>',
								'fallback_cb'    => false,
							)
						);
					} else {
						echo '<ul>';
						foreach ( $kaao_column['links'] as [$kaao_label, $kaao_url] ) {
							printf(
								'<li><a href="%s">%s</a></li>',
								esc_url( home_url( $kaao_url ) ),
								esc_html( $kaao_label )
							);
						}
						echo '</ul>';
					}
					?>
				</nav>
			<?php endforeach; ?>

			<div>
				<h2><?php esc_html_e( 'Contact', 'kaao' ); ?></h2>
				<address class="kaao-footer__contact" style="font-style:normal">
					<?php if ( $kaao_org['address'] ) : ?>
						<div>
							<?php echo kaao_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span>
								<?php echo esc_html( $kaao_org['address'] ); ?>
								<?php if ( $kaao_org['po_box'] ) : ?>
									<br><?php echo esc_html( $kaao_org['po_box'] ); ?>
								<?php endif; ?>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( $kaao_org['phone'] ) : ?>
						<div>
							<?php echo kaao_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $kaao_org['phone'] ) ); ?>"><?php echo esc_html( $kaao_org['phone'] ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $kaao_org['email'] ) : ?>
						<div>
							<?php echo kaao_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<a href="mailto:<?php echo esc_attr( $kaao_org['email'] ); ?>"><?php echo esc_html( $kaao_org['email'] ); ?></a>
						</div>
					<?php endif; ?>
				</address>

				<p style="margin-top:1.25rem">
					<a class="btn btn--primary btn--sm" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						<?php esc_html_e( 'Get in touch', 'kaao' ); ?>
					</a>
				</p>
			</div>
		</div>

		<div class="kaao-footer__bar">
			<p style="margin:0">
				<?php
				printf(
					/* translators: 1: year, 2: organisation name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'kaao' ),
					esc_html( wp_date( 'Y' ) ),
					esc_html( $kaao_org['legal_name'] )
				);
				?>
			</p>

			<?php
			if ( has_nav_menu( 'legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'legal',
						'container'      => false,
						'depth'          => 1,
						'items_wrap'     => '<ul>%3$s</ul>',
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'kaao' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'kaao' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/accessibility/' ) ); ?>"><?php esc_html_e( 'Accessibility', 'kaao' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>"><?php esc_html_e( 'Sitemap', 'kaao' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
