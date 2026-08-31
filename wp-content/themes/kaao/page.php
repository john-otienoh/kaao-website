<?php
/**
 * Default page template.
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
			'title'    => (string) get_the_title(),
			'intro'    => (string) get_the_excerpt(),
			'image_id' => (int) get_post_thumbnail_id(),
		)
	);

	kaao_breadcrumbs();
	?>

	<article class="section">
		<div class="container container--narrow">
			<div class="prose">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="pagination" aria-label="' . esc_attr__( 'Page sections', 'kaao' ) . '"><div class="nav-links">',
						'after'  => '</div></nav>',
					)
				);
				?>
			</div>
		</div>
	</article>

	<?php
endwhile;

get_footer();
