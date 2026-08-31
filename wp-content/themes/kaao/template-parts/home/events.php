<?php
/**
 * Events — upcoming first, then the most recent engagements.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_today = current_time( 'Y-m-d' );

$kaao_upcoming = kaao_get_posts(
	KAAO_CPT_EVENT,
	4,
	array(
		'meta_key'   => 'kaao_event_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'    => array( 'meta_value' => 'ASC' ),
		'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'kaao_event_start',
				'value'   => $kaao_today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

$kaao_recent = kaao_get_posts(
	KAAO_CPT_EVENT,
	3,
	array(
		'meta_key'   => 'kaao_event_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'    => array( 'meta_value' => 'DESC' ),
		'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'kaao_event_start',
				'value'   => $kaao_today,
				'compare' => '<',
				'type'    => 'DATE',
			),
		),
	)
);

if ( ! $kaao_upcoming->have_posts() && ! $kaao_recent->have_posts() ) {
	return;
}
?>
<section class="section section--surface" aria-labelledby="events-title">
	<div class="container">
		<?php
		kaao_section_head(
			array(
				'eyebrow'   => __( 'Events', 'kaao' ),
				'title'     => __( 'Events & industry engagements', 'kaao' ),
				'intro'     => __( 'Board meetings, industry affairs meetings, stakeholder forums and the sector events KAAO takes part in.', 'kaao' ),
				'link'      => home_url( '/events/' ),
				'link_text' => __( 'All events', 'kaao' ),
				'id'        => 'events-title',
			)
		);
		?>

		<div class="with-sidebar" style="grid-template-columns:none">
			<?php if ( $kaao_upcoming->have_posts() ) : ?>
				<div>
					<h3 class="h4" style="margin-bottom:var(--sp-4)"><?php esc_html_e( 'Upcoming', 'kaao' ); ?></h3>
					<div class="grid" style="gap:var(--sp-3)">
						<?php
						while ( $kaao_upcoming->have_posts() ) :
							$kaao_upcoming->the_post();
							get_template_part( 'template-parts/cards/event-row' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<p class="mt-6" style="margin-bottom:0">
						<a class="link-arrow" href="<?php echo esc_url( add_query_arg( 'when', 'upcoming', home_url( '/events/' ) ) ); ?>">
							<?php esc_html_e( 'All upcoming events', 'kaao' ); ?>
						</a>
					</p>
				</div>
			<?php endif; ?>

			<?php if ( $kaao_recent->have_posts() ) : ?>
				<div class="mt-8">
					<h3 class="h4" style="margin-bottom:var(--sp-4)"><?php esc_html_e( 'Latest engagements', 'kaao' ); ?></h3>
					<div class="grid grid--3">
						<?php
						while ( $kaao_recent->have_posts() ) :
							$kaao_recent->the_post();
							get_template_part( 'template-parts/cards/event' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
