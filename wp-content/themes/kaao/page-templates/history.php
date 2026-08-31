<?php
/**
 * Template Name: History
 * Template Post Type: page
 *
 * The founding narrative, followed by a timeline assembled from KAAO's own
 * dated press releases. Nothing on the timeline is authored here — every entry
 * is a published record, so no milestone can be invented.
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
			'eyebrow'    => __( 'About KAAO', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: sprintf(
				/* translators: %d: number of years. */
				__( 'For over %d years KAAO has been the unified voice of Kenya’s aviation sector.', 'kaao' ),
				kaao_years_active()
			),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-banner-history-kaa-consultative-forum',
		)
	);

	kaao_breadcrumbs();
	?>

	<article class="section">
		<div class="container container--narrow">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php
	// Documented milestones: KAAO press releases, newest first.
	$kaao_press = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 20,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'category_name'  => 'press-releases',
		)
	);

	if ( $kaao_press->have_posts() ) :
		?>
		<section class="section section--surface" aria-labelledby="milestones-title">
			<div class="container container--narrow">
				<?php
				kaao_section_head(
					array(
						'eyebrow' => __( 'On the record', 'kaao' ),
						'title'   => __( 'Documented milestones', 'kaao' ),
						'intro'   => __( 'Drawn from KAAO’s published statements and press releases. Earlier milestones from the Association’s archive will be added as they are verified.', 'kaao' ),
						'id'      => 'milestones-title',
					)
				);
				?>

				<ol class="timeline">
					<?php
					while ( $kaao_press->have_posts() ) :
						$kaao_press->the_post();
						?>
						<li>
							<span class="timeline__year"><?php echo esc_html( (string) get_the_date( 'F Y' ) ); ?></span>
							<h3>
								<a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a>
							</h3>
							<?php if ( kaao_excerpt( null, 28 ) ) : ?>
								<p><?php echo esc_html( kaao_excerpt( null, 28 ) ); ?></p>
							<?php endif; ?>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>

					<li>
						<span class="timeline__year"><?php echo esc_html( wp_date( 'Y', strtotime( kaao_org_get( 'founded' ) ) ) ); ?></span>
						<h3><?php esc_html_e( 'KAAO is founded', 'kaao' ); ?></h3>
						<p>
							<?php
							printf(
								/* translators: %s: founding date. */
								esc_html__( 'Founded on %s to represent the collective interests of air operators in Kenya, advocating for an enabling regulatory environment, promoting safety, and supporting the sustainable growth of the industry.', 'kaao' ),
								esc_html( wp_date( 'j F Y', strtotime( kaao_org_get( 'founded' ) ) ) )
							);
							?>
						</p>
					</li>
				</ol>
			</div>
		</section>
		<?php
	endif;

	get_template_part( 'template-parts/home/cta' );

endwhile;

get_footer();
