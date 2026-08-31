<?php
/**
 * Leadership — Board of Directors and Secretariat.
 *
 * Renders one section per leadership group from a single reusable card, so no
 * individual profile is hard-coded into the theme.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'About KAAO', 'kaao' ),
		'title'      => is_tax() ? wp_strip_all_tags( get_the_archive_title() ) : __( 'Our leadership', 'kaao' ),
		'intro'      => __( 'Meet the team guiding KAAO’s vision and operations. From strategic direction by the Board of Directors to day-to-day execution by the Secretariat, our leadership is dedicated to advancing Kenya’s aviation industry.', 'kaao' ),
		'image_slug' => 'kaao-banner-leadership-board-meeting-session',
	)
);

kaao_breadcrumbs();

$kaao_groups = is_tax( 'kaao_leader_group' )
	? array( get_queried_object() )
	: get_terms(
		array(
			'taxonomy'   => 'kaao_leader_group',
			'hide_empty' => true,
			'orderby'    => 'term_id',
		)
	);

if ( is_wp_error( $kaao_groups ) ) {
	$kaao_groups = array();
}
?>

<?php if ( $kaao_groups ) : ?>
	<?php foreach ( $kaao_groups as $kaao_index => $kaao_group ) : ?>
		<?php
		$kaao_people = new WP_Query(
			array(
				'post_type'      => KAAO_CPT_LEADER,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'kaao_leader_group',
						'field'    => 'term_id',
						'terms'    => $kaao_group->term_id,
					),
				),
			)
		);

		if ( ! $kaao_people->have_posts() ) {
			continue;
		}
		?>
		<section class="section<?php echo ( $kaao_index % 2 ) ? ' section--surface' : ''; ?>" aria-labelledby="group-<?php echo esc_attr( $kaao_group->slug ); ?>">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow' => 'board-of-directors' === $kaao_group->slug
							? __( 'Governance', 'kaao' )
							: __( 'Day-to-day operations', 'kaao' ),
						'title'   => $kaao_group->name,
						'intro'   => $kaao_group->description,
						'id'      => 'group-' . $kaao_group->slug,
					)
				);
				?>

				<div class="grid grid--4">
					<?php
					while ( $kaao_people->have_posts() ) :
						$kaao_people->the_post();
						get_template_part( 'template-parts/cards/leader' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endforeach; ?>

<?php elseif ( have_posts() ) : ?>
	<div class="section">
		<div class="container">
			<div class="grid grid--4">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/leader' );
				endwhile;
				?>
			</div>
		</div>
	</div>

<?php else : ?>
	<div class="section">
		<div class="container">
			<div class="empty-state">
				<h2><?php esc_html_e( 'Leadership profiles are being prepared', 'kaao' ); ?></h2>
				<p><?php esc_html_e( 'Board of Directors and Secretariat profiles will appear here once published.', 'kaao' ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
get_footer();
