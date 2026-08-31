<?php
/**
 * FAQ accordion.
 *
 * Built on <details>/<summary>: keyboard operable, screen-reader announced and
 * expandable before any JavaScript loads. Each pair rendered here is also
 * registered for the FAQPage structured data, so the markup and the schema
 * always describe the same questions.
 *
 * @package KAAO
 *
 * Expected $args:
 *   topic  string  FAQ topic slug to show. Empty for all.
 *   limit  int     Maximum questions. -1 for all.
 *   open   int     How many to render already expanded.
 *   name   string  Accessible group name for the region.
 */

defined( 'ABSPATH' ) || exit;

$kaao_a = wp_parse_args(
	$args ?? array(),
	array(
		'topic' => '',
		'limit' => -1,
		'open'  => 0,
		'name'  => '',
	)
);

$kaao_query_args = array(
	'post_type'      => KAAO_CPT_FAQ,
	'post_status'    => 'publish',
	'posts_per_page' => (int) $kaao_a['limit'],
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	'no_found_rows'  => true,
);

if ( $kaao_a['topic'] ) {
	$kaao_query_args['tax_query'] = array(
		array(
			'taxonomy' => 'kaao_faq_topic',
			'field'    => 'slug',
			'terms'    => $kaao_a['topic'],
		),
	);
}

$kaao_faqs = new WP_Query( $kaao_query_args );

if ( $kaao_faqs->have_posts() ) :
	$kaao_index = 0;
	?>
	<div class="accordion"<?php echo $kaao_a['name'] ? ' aria-label="' . esc_attr( $kaao_a['name'] ) . '"' : ''; ?>>
		<?php
		while ( $kaao_faqs->have_posts() ) :
			$kaao_faqs->the_post();

			$kaao_answer = apply_filters( 'the_content', get_the_content() );

			// Record the pair for the FAQPage node built in inc/schema.php.
			kaao_faq_schema_buffer(
				array(
					'q' => wp_strip_all_tags( (string) get_the_title() ),
					'a' => trim( wp_strip_all_tags( $kaao_answer ) ),
				)
			);
			?>
			<details class="accordion__item"<?php echo $kaao_index < (int) $kaao_a['open'] ? ' open' : ''; ?>>
				<summary class="accordion__q"><?php the_title(); ?></summary>
				<div class="accordion__a"><?php echo wp_kses_post( $kaao_answer ); ?></div>
			</details>
			<?php
			++$kaao_index;
		endwhile;
		?>
	</div>
	<?php
	wp_reset_postdata();
endif;
