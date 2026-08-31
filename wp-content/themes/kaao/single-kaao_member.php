<?php
/**
 * Member profile.
 *
 * One template serves every member — no member page is designed by hand.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_name     = wp_strip_all_tags( (string) get_the_title() );
	$kaao_category = kaao_first_term( 'kaao_member_category' );
	$kaao_sector   = kaao_first_term( 'kaao_sector' );
	$kaao_website  = kaao_field( 'kaao_member_website' );
	$kaao_email    = kaao_field( 'kaao_member_email' );
	$kaao_phone    = kaao_field( 'kaao_member_phone' );
	// Some members list more than one contact; the link only ever dials/emails the first.
	$kaao_email_href = trim( preg_split( '/[;,\s]+/', trim( $kaao_email ) )[0] ?? '' );
	$kaao_phone_href = preg_replace( '/\s+/', '', trim( explode( ';', $kaao_phone )[0] ) );
	$kaao_location = kaao_field( 'kaao_member_location' );
	$kaao_since    = kaao_field( 'kaao_member_since' );
	$kaao_fleet    = kaao_field( 'kaao_member_fleet' );
	$kaao_routes   = kaao_field( 'kaao_member_routes' );
	$kaao_evidence = kaao_field( 'kaao_member_evidence' );
	$kaao_sources  = array_filter( array_map( 'trim', preg_split( '/\R/', kaao_field( 'kaao_member_sources' ) ) ?: array() ) );
	$kaao_address  = kaao_field( 'kaao_member_address' );
	$kaao_is_aoc   = $kaao_sector && 'kaao_sector' === $kaao_sector->taxonomy && 'aoc' === $kaao_sector->slug;
	$kaao_services = array_values(
		array_filter(
			array_map( 'trim', preg_split( '/\R/', kaao_field( 'kaao_member_services' ) ) ?: array() )
		)
	);
	$kaao_fleet_items = array_values( array_filter( array_map( 'trim', preg_split( '/\s*;\s*/', $kaao_fleet ) ?: array() ) ) );
	$kaao_route_items = array_values( array_filter( array_map( 'trim', preg_split( '/\s*;\s*/', $kaao_routes ) ?: array() ) ) );
	$kaao_service_items = array_values( array_filter( array_map( 'trim', preg_split( '/\s*;\s*/', implode( '; ', $kaao_services ) ) ?: array() ) ) );
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container">

			<header class="profile-head profile-hero">
				<div class="profile-head__logo">
					<?php
					if ( has_post_thumbnail() ) {
						echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
							'kaao-logo',
							array(
								'alt'           => esc_attr(
									sprintf(
										/* translators: %s: organisation name. */
										__( '%s logo', 'kaao' ),
										$kaao_name
									)
								),
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '200px',
							)
						);
					} else {
						printf(
							'<span aria-hidden="true" style="font-size:var(--fs-4xl);font-weight:750;color:var(--kaao-primary-500)">%s</span>',
							esc_html( kaao_initials( $kaao_name ) )
						);
					}
					?>
				</div>

				<div>
					<div class="cluster profile-hero__badges" style="margin-bottom:var(--sp-3)">
						<?php
						kaao_term_pill( $kaao_category, 'pill--gold' );
						kaao_term_pill( $kaao_sector, 'pill--muted' );
						?>
					</div>

					<h1 class="balance" style="margin-bottom:var(--sp-3)"><?php the_title(); ?></h1>

					<?php if ( has_excerpt() ) : ?>
						<p class="lede pretty" style="margin-bottom:var(--sp-5)"><?php echo esc_html( (string) get_the_excerpt() ); ?></p>
					<?php endif; ?>

					<?php if ( $kaao_location ) : ?>
						<p class="profile-hero__location"><?php echo kaao_icon( 'pin', array( 'width' => 17, 'height' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $kaao_location ); ?></p>
					<?php endif; ?>

					<div class="cluster profile-hero__actions">
						<?php if ( $kaao_website ) : ?>
							<a class="btn btn--navy btn--sm" href="<?php echo esc_url( $kaao_website ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo kaao_icon( 'external', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Visit website', 'kaao' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'kaao' ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $kaao_email ) : ?>
							<a class="btn btn--outline btn--sm" href="mailto:<?php echo esc_attr( $kaao_email_href ); ?>">
								<?php echo kaao_icon( 'mail', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Email', 'kaao' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $kaao_phone ) : ?>
							<a class="btn btn--outline btn--sm" href="tel:<?php echo esc_attr( $kaao_phone_href ); ?>">
								<?php echo kaao_icon( 'phone', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Contact member', 'kaao' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</header>

			<?php if ( $kaao_is_aoc ) : ?>
				<div class="profile-facts" aria-label="<?php esc_attr_e( 'Aviation profile facts', 'kaao' ); ?>">
					<?php foreach ( array( 'pin' => array( __( 'Primary base', 'kaao' ), $kaao_location ), 'shield' => array( __( 'Certificate status', 'kaao' ), $kaao_evidence ? __( 'Verified operator evidence supplied', 'kaao' ) : '' ), 'globe' => array( __( 'Operating region', 'kaao' ), $kaao_routes ? __( 'Regional and international operations', 'kaao' ) : '' ) ) as $kaao_fact_icon => $kaao_fact ) : ?>
						<?php $kaao_fact_label = $kaao_fact[0]; $kaao_fact_value = $kaao_fact[1]; ?>
						<?php if ( $kaao_fact_value ) : ?>
							<div class="profile-fact">
								<span class="profile-fact__icon"><?php echo kaao_icon( $kaao_fact_icon, array( 'width' => 17, 'height' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="profile-fact__label"><?php echo esc_html( $kaao_fact_label ); ?></span>
								<strong><?php echo esc_html( $kaao_fact_value ); ?></strong>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="with-sidebar profile-layout" style="grid-template-columns:1fr 20rem">
				<div>
					<?php if ( get_the_content() ) : ?>
						<section class="profile-panel" aria-labelledby="about-title">
						<h2 id="about-title" class="h3"><?php echo esc_html( $kaao_is_aoc ? __( 'About the operator', 'kaao' ) : __( 'About', 'kaao' ) ); ?></h2>
						<div class="prose" style="font-size:var(--fs-base)">
							<?php the_content(); ?>
						</div>
						</section>
					<?php elseif ( ! has_excerpt() ) : ?>
						<?php kaao_to_confirm( __( 'Organisation profile', 'kaao' ) ); ?>
					<?php endif; ?>

					<?php if ( $kaao_is_aoc && ( $kaao_fleet || $kaao_routes || $kaao_services ) ) : ?>
						<section class="profile-panel mt-8" aria-labelledby="operations-title">
							<h2 id="operations-title" class="h3"><?php esc_html_e( 'Services & operations', 'kaao' ); ?></h2>
							<div class="profile-detail-grid">
								<?php foreach ( array( 'plane' => array( __( 'Fleet & aircraft', 'kaao' ), $kaao_fleet_items ), 'globe' => array( __( 'Routes & destinations', 'kaao' ), $kaao_route_items ), 'shield' => array( __( 'Core services & missions', 'kaao' ), $kaao_service_items ) ) as $kaao_detail_icon => $kaao_detail ) : ?>
									<?php if ( $kaao_detail[1] ) : ?>
										<div class="profile-detail-card">
											<div class="profile-detail-card__icon"><?php echo kaao_icon( $kaao_detail_icon, array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
											<h3><?php echo esc_html( $kaao_detail[0] ); ?></h3>
											<ul class="profile-item-list">
												<?php foreach ( $kaao_detail[1] as $kaao_detail_item ) : ?>
													<li><span class="profile-item-list__mark" aria-hidden="true"></span><?php echo esc_html( $kaao_detail_item ); ?></li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php elseif ( $kaao_services ) : ?>
						<section class="profile-panel mt-8" aria-labelledby="services-title">
							<h2 id="services-title" class="h3"><?php esc_html_e( 'Services & activities', 'kaao' ); ?></h2>
							<ul><?php foreach ( $kaao_services as $kaao_service ) : ?><li><?php echo esc_html( $kaao_service ); ?></li><?php endforeach; ?></ul>
						</section>
					<?php endif; ?>

					<?php if ( $kaao_is_aoc && $kaao_evidence ) : ?>
						<section class="profile-section mt-8" aria-labelledby="verification-title">
							<h2 id="verification-title" class="h3"><?php esc_html_e( 'Operator verification', 'kaao' ); ?></h2>
							<p><?php echo esc_html( $kaao_evidence ); ?></p>
						</section>
					<?php endif; ?>

					<p class="mt-8">
						<a class="link-arrow" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_MEMBER ) ); ?>">
							<?php esc_html_e( 'Back to member directory', 'kaao' ); ?>
						</a>
					</p>
				</div>

				<aside class="profile-aside">
					<h2 class="h4"><?php esc_html_e( 'Key facts', 'kaao' ); ?></h2>
					<dl class="factlist">
						<?php if ( $kaao_category ) : ?>
							<div>
								<dt><?php esc_html_e( 'Membership category', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_category->name ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_sector ) : ?>
							<div>
								<dt><?php esc_html_e( 'Aviation sector', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_sector->name ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_location ) : ?>
							<div>
								<dt><?php esc_html_e( 'Location', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_location ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_phone ) : ?>
							<div>
								<dt><?php esc_html_e( 'Phone', 'kaao' ); ?></dt>
								<dd><a href="tel:<?php echo esc_attr( $kaao_phone_href ); ?>"><?php echo esc_html( $kaao_phone ); ?></a></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_email ) : ?>
							<div>
								<dt><?php esc_html_e( 'Email', 'kaao' ); ?></dt>
								<dd><a href="mailto:<?php echo esc_attr( $kaao_email_href ); ?>"><?php echo esc_html( $kaao_email ); ?></a></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_website ) : ?>
							<div>
								<dt><?php esc_html_e( 'Website', 'kaao' ); ?></dt>
								<dd>
									<a href="<?php echo esc_url( $kaao_website ); ?>" target="_blank" rel="noopener noreferrer">
										<?php echo esc_html( kaao_pretty_url( $kaao_website ) ); ?>
									</a>
								</dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_since ) : ?>
							<div>
								<dt><?php esc_html_e( 'Member since', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_since ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_address ) : ?>
							<div>
								<dt><?php esc_html_e( 'Head office', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_address ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>

				</aside>
			</div>
		</div>
	</article>

	<?php
	// Other members in the same category.
	$kaao_related = kaao_get_posts(
		KAAO_CPT_MEMBER,
		4,
		array(
			'post__not_in' => array( get_the_ID() ),
			'orderby'      => 'rand',
			'tax_query'    => ( $kaao_is_aoc && $kaao_sector )
				? array(
					array(
						'taxonomy' => 'kaao_sector',
						'field'    => 'term_id',
						'terms'    => $kaao_sector->term_id,
					),
				)
				: ( $kaao_category // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				? array(
					array(
						'taxonomy' => 'kaao_member_category',
						'field'    => 'term_id',
						'terms'    => $kaao_category->term_id,
					),
				)
				: array() ),
		)
	);

	if ( $kaao_related->have_posts() ) :
		?>
		<section class="section section--surface" aria-labelledby="related-members">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow'   => __( 'Also in membership', 'kaao' ),
						'title'     => $kaao_category
							? sprintf(
								/* translators: %s: membership category. */
								__( 'Other %s members', 'kaao' ),
								strtolower( $kaao_category->name )
							)
							: __( 'Other members', 'kaao' ),
						'link'      => (string) get_post_type_archive_link( KAAO_CPT_MEMBER ),
						'link_text' => __( 'View all', 'kaao' ),
						'id'        => 'related-members',
					)
				);
				?>
				<div class="grid grid--4">
					<?php
					while ( $kaao_related->have_posts() ) :
						$kaao_related->the_post();
						get_template_part( 'template-parts/cards/member' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
