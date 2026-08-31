<?php
/**
 * Event card.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_start    = kaao_field( 'kaao_event_start' );
$kaao_start_ts = $kaao_start ? strtotime( $kaao_start ) : 0;
$kaao_type     = kaao_first_term( 'kaao_event_type' );
$kaao_location = kaao_field( 'kaao_event_location' );
$kaao_upcoming = kaao_event_is_upcoming();
?>
<article <?php post_class( 'card event-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="card__media">
			<?php
			echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
				'kaao-card',
				array(
					'alt'   => esc_attr( (string) get_the_title() ),
					'sizes' => '(min-width: 64rem) 22rem, (min-width: 40rem) 45vw, 92vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="card__body">
		<div class="cluster" style="gap:.5rem">
			<?php if ( $kaao_upcoming ) : ?>
				<span class="pill pill--gold"><?php esc_html_e( 'Upcoming', 'kaao' ); ?></span>
			<?php endif; ?>
			<?php kaao_term_pill( $kaao_type ); ?>
		</div>

		<h3 class="card__title">
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
		</div>

		<?php if ( kaao_excerpt( null, 18 ) ) : ?>
			<p class="card__excerpt"><?php echo esc_html( kaao_excerpt( null, 18 ) ); ?></p>
		<?php endif; ?>
	</div>
</article>
