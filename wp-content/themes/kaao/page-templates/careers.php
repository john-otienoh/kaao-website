<?php
/**
 * Template Name: Careers
 * Template Post Type: page
 *
 * Page copy, followed by the vacancies KAAO has published. Open positions come
 * first; positions whose closing date has passed stay visible but are clearly
 * marked, so a visitor arriving from an old link is never misled.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/hero/page',
		null,
		array(
			'eyebrow'    => __( 'Careers', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Opportunities to work with the Kenya Association of Air Operators.', 'kaao' ),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-banner-members-kenya-airways-hangar-team',
		)
	);

	kaao_breadcrumbs();

	// Every published vacancy in one query; the open/closed split is a field,
	// not a separate listing, so it is decided here rather than in the database.
	$kaao_vacancies = new WP_Query(
		array(
			'post_type'      => KAAO_CPT_VACANCY,
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	$kaao_open   = array();
	$kaao_closed = array();
	foreach ( $kaao_vacancies->posts as $kaao_vacancy ) {
		if ( kaao_vacancy_is_open( (int) $kaao_vacancy->ID ) ) {
			$kaao_open[] = $kaao_vacancy;
		} else {
			$kaao_closed[] = $kaao_vacancy;
		}
	}

	$kaao_apply_email = kaao_org_get( 'email' );

	/**
	 * Render a set of vacancies with the shared card.
	 *
	 * @param WP_Post[] $posts Vacancies.
	 */
	$kaao_vacancy_cards = static function ( array $posts ): void {
		global $post;
		foreach ( $posts as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- restored below.
			setup_postdata( $post );
			get_template_part( 'template-parts/cards/vacancy' );
		}
		wp_reset_postdata();
	};
	?>

	<?php if ( get_the_content() ) : ?>
		<article class="section">
			<div class="container container--narrow">
				<div class="prose">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endif; ?>

	<section class="section section--surface" aria-labelledby="vacancies-title">
		<div class="container">
			<?php
			kaao_section_head(
				array(
					'eyebrow' => __( 'Work with us', 'kaao' ),
					'title'   => __( 'Current vacancies', 'kaao' ),
					'intro'   => __( 'Positions at the Secretariat are advertised here for as long as they are open. Each advertisement states where to send an application and by when.', 'kaao' ),
					'id'      => 'vacancies-title',
				)
			);
			?>

			<?php if ( $kaao_open ) : ?>
				<div class="grid grid--3">
					<?php $kaao_vacancy_cards( $kaao_open ); ?>
				</div>
			<?php else : ?>
				<div class="empty-state">
					<h3><?php esc_html_e( 'No positions are open at the moment', 'kaao' ); ?></h3>
					<p><?php esc_html_e( 'Vacancies at the Secretariat are advertised on this page, and through KAAO’s social channels, as they arise.', 'kaao' ); ?></p>
					<?php if ( $kaao_apply_email ) : ?>
						<p>
							<a class="btn btn--navy" href="mailto:<?php echo esc_attr( $kaao_apply_email ); ?>">
								<?php echo kaao_icon( 'mail', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Write to the Secretariat', 'kaao' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $kaao_closed ) : ?>
				<div class="mt-8">
					<h3 class="h4"><?php esc_html_e( 'Closed advertisements', 'kaao' ); ?></h3>
					<p class="muted" style="font-size:var(--fs-sm)">
						<?php esc_html_e( 'Kept online so links to them still resolve. These positions are no longer accepting applications.', 'kaao' ); ?>
					</p>
					<div class="grid grid--3 mt-6">
						<?php $kaao_vacancy_cards( $kaao_closed ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="section" aria-labelledby="speculative-title">
		<div class="container">
			<div class="with-sidebar" style="grid-template-columns:1fr 20rem">
				<div>
					<?php
					kaao_section_head(
						array(
							'eyebrow' => __( 'Applying', 'kaao' ),
							'title'   => __( 'How applications reach us', 'kaao' ),
							'id'      => 'speculative-title',
						)
					);
					?>
					<div class="prose" style="font-size:var(--fs-base)">
						<p>
							<?php esc_html_e( 'Applications are received and acknowledged by the KAAO Secretariat. Where an advertisement names a closing date or a specific address, those instructions take precedence over anything on this page.', 'kaao' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'KAAO also welcomes speculative approaches from aviation professionals, and enquiries about internships and attachments, even when nothing is advertised.', 'kaao' ); ?>
						</p>
					</div>
				</div>

				<aside>
					<div style="padding:var(--sp-5);background:var(--kaao-surface);border-radius:var(--card-radius);border:1px solid var(--kaao-border)">
						<h3 class="h4"><?php esc_html_e( 'Contact the Secretariat', 'kaao' ); ?></h3>
						<p class="muted" style="font-size:var(--fs-sm)">
							<?php esc_html_e( 'For anything to do with recruitment, internships or attachments.', 'kaao' ); ?>
						</p>
						<?php if ( $kaao_apply_email ) : ?>
							<p>
								<a class="btn btn--navy btn--sm btn--block" href="mailto:<?php echo esc_attr( $kaao_apply_email ); ?>">
									<?php echo esc_html( $kaao_apply_email ); ?>
								</a>
							</p>
						<?php endif; ?>
						<p style="margin:0">
							<a class="link-arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
								<?php esc_html_e( 'All contact details', 'kaao' ); ?>
							</a>
						</p>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
