<?php
/**
 * Template Name: Advocacy Impact
 * Template Post Type: page
 *
 * Documented advocacy work grouped by theme, with the concluded matters
 * surfaced first. Records with no confirmed outcome are shown as ongoing
 * rather than presented as achievements.
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
			'eyebrow'    => __( 'Advocacy', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Documented examples of policy advocacy, regulatory engagement, industry representation and stakeholder engagement.', 'kaao' ),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-banner-advocacy-regulatory-forum-address',
		)
	);

	kaao_breadcrumbs();
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

	<?php
	$kaao_themes = get_terms(
		array(
			'taxonomy'   => 'kaao_advocacy_theme',
			'hide_empty' => true,
		)
	);
	if ( is_wp_error( $kaao_themes ) ) {
		$kaao_themes = array();
	}

	$kaao_any = false;

	foreach ( $kaao_themes as $kaao_index => $kaao_theme ) :
		$kaao_records = new WP_Query(
			array(
				'post_type'      => KAAO_CPT_ADVOCACY,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'kaao_advocacy_theme',
						'field'    => 'term_id',
						'terms'    => $kaao_theme->term_id,
					),
				),
			)
		);

		if ( ! $kaao_records->have_posts() ) {
			continue;
		}
		$kaao_any = true;
		?>
		<section class="section<?php echo ( $kaao_index % 2 ) ? ' section--surface' : ''; ?>" aria-labelledby="impact-<?php echo esc_attr( $kaao_theme->slug ); ?>">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow'   => __( 'Advocacy theme', 'kaao' ),
						'title'     => $kaao_theme->name,
						'intro'     => $kaao_theme->description,
						'link'      => (string) get_term_link( $kaao_theme ),
						'link_text' => __( 'All records in this theme', 'kaao' ),
						'id'        => 'impact-' . $kaao_theme->slug,
					)
				);
				?>
				<div class="grid grid--2">
					<?php
					while ( $kaao_records->have_posts() ) :
						$kaao_records->the_post();
						get_template_part( 'template-parts/cards/advocacy' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endforeach;

	if ( ! $kaao_any ) :
		?>
		<div class="section">
			<div class="container container--narrow">
				<div class="empty-state">
					<h2><?php esc_html_e( 'Advocacy records are being compiled', 'kaao' ); ?></h2>
					<p><?php esc_html_e( 'Documented policy and regulatory engagements will be published here.', 'kaao' ); ?></p>
					<p>
						<a class="btn btn--navy" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_ADVOCACY ) ); ?>">
							<?php esc_html_e( 'See our advocacy work', 'kaao' ); ?>
						</a>
					</p>
				</div>
			</div>
		</div>
		<?php
	endif;

	get_template_part( 'template-parts/home/cta' );

endwhile;

get_footer();
