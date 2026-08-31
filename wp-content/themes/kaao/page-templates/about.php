<?php
/**
 * Template Name: About
 * Template Post Type: page
 *
 * Page content, followed by mission/vision/objectives and a leadership preview
 * drawn from live WordPress data.
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
			'intro'      => get_the_excerpt() ?: __( 'An authoritative and unified voice of advocacy for the Kenyan aviation industry.', 'kaao' ),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-banner-about-industry-panel-discussion',
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
	/* ------------------------------------------- Mission, vision, objectives */
	$kaao_objectives = array(
		__( 'Collective representation', 'kaao' ),
		__( 'Desensitize difficult issues faced by individual members', 'kaao' ),
		__( 'One voice for the industry', 'kaao' ),
		__( 'Influence policy', 'kaao' ),
		__( 'Easy access to professional advice', 'kaao' ),
		__( 'Direct access to Government and Government Agencies', 'kaao' ),
		__( 'Self regulation through code of conduct/ethics', 'kaao' ),
		__( 'Provision of fora to discuss and shape the industry', 'kaao' ),
		__( 'Liaison with other organizations', 'kaao' ),
	);
	?>
	<section class="section section--navy" aria-labelledby="mvo-title">
		<div class="container">
			<?php
			kaao_section_head(
				array(
					'eyebrow' => __( 'What guides us', 'kaao' ),
					'title'   => __( 'Mission, vision & objectives', 'kaao' ),
					'align'   => 'center',
					'id'      => 'mvo-title',
				)
			);
			?>

			<div class="grid grid--2" style="margin-bottom:var(--sp-10)">
				<div style="padding:var(--sp-6);border-left:3px solid var(--kaao-gold-600);background:rgba(255,255,255,.04);border-radius:0 var(--radius) var(--radius) 0">
					<p class="eyebrow"><?php esc_html_e( 'Mission', 'kaao' ); ?></p>
					<p style="font-size:var(--fs-xl);color:var(--kaao-white);margin:0">
						<?php esc_html_e( 'To promote and enhance operations of a safe, efficient and sustainable national aviation industry.', 'kaao' ); ?>
					</p>
				</div>

				<div style="padding:var(--sp-6);border-left:3px solid var(--kaao-gold-600);background:rgba(255,255,255,.04);border-radius:0 var(--radius) var(--radius) 0">
					<p class="eyebrow"><?php esc_html_e( 'Vision', 'kaao' ); ?></p>
					<p style="font-size:var(--fs-xl);color:var(--kaao-white);margin:0">
						<?php esc_html_e( 'To be an authoritative and unified voice of advocacy for the Kenyan aviation industry.', 'kaao' ); ?>
					</p>
				</div>
			</div>

			<h3 style="color:var(--kaao-white)"><?php esc_html_e( 'Our objectives', 'kaao' ); ?></h3>
			<div class="benefits">
				<?php foreach ( $kaao_objectives as $kaao_i => $kaao_objective ) : ?>
					<div class="benefit">
						<span class="benefit__mark" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $kaao_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<div>
							<h4 style="margin:0;color:var(--kaao-white);font-size:var(--fs-base)"><?php echo esc_html( $kaao_objective ); ?></h4>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	/* ----------------------------------------------------- Leadership preview */
	$kaao_board = new WP_Query(
		array(
			'post_type'      => KAAO_CPT_LEADER,
			'posts_per_page' => 4,
			'post_status'    => 'publish',
			'orderby'        => array( 'menu_order' => 'ASC' ),
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	if ( $kaao_board->have_posts() ) :
		?>
		<section class="section" aria-labelledby="leadership-preview">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow'   => __( 'Leadership', 'kaao' ),
						'title'     => __( 'Guiding KAAO’s vision and operations', 'kaao' ),
						'intro'     => __( 'Strategic direction from the Board of Directors, day-to-day execution by the Secretariat.', 'kaao' ),
						'link'      => (string) get_post_type_archive_link( KAAO_CPT_LEADER ),
						'link_text' => __( 'Meet the full team', 'kaao' ),
						'id'        => 'leadership-preview',
					)
				);
				?>
				<div class="grid grid--4">
					<?php
					while ( $kaao_board->have_posts() ) :
						$kaao_board->the_post();
						get_template_part( 'template-parts/cards/leader' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

	get_template_part( 'template-parts/home/cta' );

endwhile;

get_footer();
