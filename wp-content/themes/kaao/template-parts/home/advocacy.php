<?php
/**
 * Advocacy & industry impact preview.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_advocacy = kaao_get_posts( KAAO_CPT_ADVOCACY, 3 );

if ( $kaao_advocacy->have_posts() ) :
	?>
	<section class="section" aria-labelledby="advocacy-title">
		<div class="container">
			<?php
			kaao_section_head(
				array(
					'eyebrow'   => __( 'Advocacy', 'kaao' ),
					'title'     => __( 'Advocacy & industry impact', 'kaao' ),
					'intro'     => __( 'What KAAO is doing for the industry — the policy and regulatory matters the Association is engaged on, and where they stand.', 'kaao' ),
					'link'      => home_url( '/advocacy/' ),
					'link_text' => __( 'All advocacy work', 'kaao' ),
					'id'        => 'advocacy-title',
				)
			);
			?>

			<div class="grid grid--3">
				<?php
				while ( $kaao_advocacy->have_posts() ) :
					$kaao_advocacy->the_post();
					get_template_part( 'template-parts/cards/advocacy' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<p class="mt-8" style="margin-bottom:0">
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/advocacy/impact/' ) ); ?>">
					<?php esc_html_e( 'See our advocacy impact', 'kaao' ); ?>
				</a>
			</p>
		</div>
	</section>
	<?php
endif;
