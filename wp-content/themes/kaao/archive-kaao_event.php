<?php
/**
 * Events archive — upcoming and past.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_when    = isset( $_GET['when'] ) ? sanitize_key( wp_unslash( $_GET['when'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$kaao_archive = get_post_type_archive_link( KAAO_CPT_EVENT ) ?: home_url( '/events/' );
$kaao_is_tax  = is_tax();

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'Events', 'kaao' ),
		'title'      => $kaao_is_tax
			? wp_strip_all_tags( get_the_archive_title() )
			: __( 'Events & industry engagements', 'kaao' ),
		'intro'      => __( 'Board meetings, annual general meetings, industry affairs meetings, stakeholder forums and the sector events KAAO takes part in.', 'kaao' ),
		'image_slug' => 'kaao-banner-events-industry-forum-audience',
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container">

		<ul class="tabs">
			<?php
			$kaao_tabs = array(
				''         => __( 'All events', 'kaao' ),
				'upcoming' => __( 'Upcoming', 'kaao' ),
				// 'past'     => __( 'Past', 'kaao' ),
			);
			foreach ( $kaao_tabs as $kaao_key => $kaao_label ) :
				$kaao_url     = $kaao_key ? add_query_arg( 'when', $kaao_key, $kaao_archive ) : $kaao_archive;
				$kaao_current = ( $kaao_when === $kaao_key ) && ! $kaao_is_tax;
				?>
				<li<?php echo $kaao_current ? ' class="is-current"' : ''; ?>>
					<a href="<?php echo esc_url( $kaao_url ); ?>"<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $kaao_label ); ?>
					</a>
				</li>
			<?php endforeach; ?>

			<?php
			$kaao_types = get_terms(
				array(
					'taxonomy'   => 'kaao_event_type',
					'hide_empty' => true,
					'number'     => 6,
					'orderby'    => 'count',
					'order'      => 'DESC',
				)
			);
			if ( ! is_wp_error( $kaao_types ) ) :
				foreach ( $kaao_types as $kaao_type ) :
					$kaao_current = is_tax( 'kaao_event_type', $kaao_type->term_id );
					?>
					<li<?php echo $kaao_current ? ' class="is-current"' : ''; ?>>
						<a href="<?php echo esc_url( (string) get_term_link( $kaao_type ) ); ?>"<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $kaao_type->name ); ?>
						</a>
					</li>
					<?php
				endforeach;
			endif;
			?>
		</ul>

		<?php if ( have_posts() ) : ?>
			<?php // Names the results region for assistive technology and keeps the heading order unbroken. ?>
			<h2 class="screen-reader-text">
				<?php
				echo esc_html(
					match ( true ) {
						$kaao_is_tax          => wp_strip_all_tags( get_the_archive_title() ),
						'upcoming' === $kaao_when => __( 'Upcoming events', 'kaao' ),
						// 'past' === $kaao_when     => __( 'Past events', 'kaao' ),
						default                   => __( 'All events', 'kaao' ),
					}
				);
				?>
			</h2>

			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/event' );
				endwhile;
				?>
			</div>

			<?php kaao_pagination(); ?>

		<?php else : ?>
			<div class="empty-state">
				<h2>
					<?php
					'upcoming' === $kaao_when
						? esc_html_e( 'No upcoming events are listed', 'kaao' )
						: esc_html_e( 'No events in this view', 'kaao' );
					?>
				</h2>
				<p><?php esc_html_e( 'Events are published here as they are confirmed. In the meantime you can browse past engagements.', 'kaao' ); ?></p>
				<p><a class="btn btn--navy" href="<?php echo esc_url( $kaao_archive ); ?>"><?php esc_html_e( 'Show all events', 'kaao' ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
