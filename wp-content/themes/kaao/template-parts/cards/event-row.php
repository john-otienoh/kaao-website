<?php
/**
 * Compact event row used in the upcoming-events list.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_start    = kaao_field( 'kaao_event_start' );
$kaao_start_ts = $kaao_start ? strtotime( $kaao_start ) : 0;
$kaao_location = kaao_field( 'kaao_event_location' );
$kaao_host     = kaao_field( 'kaao_event_host' );
?>
<article <?php post_class( 'event-row' ); ?>>
	<?php if ( $kaao_start_ts ) : ?>
		<div class="event-card__date" aria-hidden="true">
			<span class="event-card__day"><?php echo esc_html( wp_date( 'j', $kaao_start_ts ) ); ?></span>
			<span class="event-card__month"><?php echo esc_html( wp_date( 'M', $kaao_start_ts ) ); ?></span>
			<span class="event-card__year"><?php echo esc_html( wp_date( 'Y', $kaao_start_ts ) ); ?></span>
		</div>
	<?php endif; ?>

	<div class="event-row__body">
		<h3 class="event-row__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="event-row__meta">
			<?php if ( $kaao_start_ts ) : ?>
				<span>
					<?php echo kaao_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<time datetime="<?php echo esc_attr( $kaao_start ); ?>"><?php echo esc_html( kaao_event_date_range() ); ?></time>
				</span>
			<?php endif; ?>

			<?php if ( $kaao_location ) : ?>
				<span>
					<?php echo kaao_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php echo esc_html( $kaao_location ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( $kaao_host ) : ?>
				<span>
					<?php echo kaao_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span>
						<?php
						printf(
							/* translators: %s: host organisation. */
							esc_html__( 'Hosted by %s', 'kaao' ),
							esc_html( $kaao_host )
						);
						?>
					</span>
				</span>
			<?php endif; ?>
		</div>
	</div>
</article>
