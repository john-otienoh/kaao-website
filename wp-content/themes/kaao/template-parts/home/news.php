<?php
/**
 * Latest news and press releases.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_news = kaao_get_posts( 'post', 3 );

if ( $kaao_news->have_posts() ) :
	?>
	<section class="section" aria-labelledby="news-title">
		<div class="container">
			<?php
			kaao_section_head(
				array(
					'eyebrow'   => __( 'News & insights', 'kaao' ),
					'title'     => __( 'Latest from KAAO', 'kaao' ),
					'intro'     => __( 'Press releases, advocacy updates, regulatory news and industry commentary.', 'kaao' ),
					'link'      => home_url( '/news/' ),
					'link_text' => __( 'All news', 'kaao' ),
					'id'        => 'news-title',
				)
			);
			?>

			<div class="grid grid--3">
				<?php
				while ( $kaao_news->have_posts() ) :
					$kaao_news->the_post();
					get_template_part( 'template-parts/cards/news' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
endif;
