<?php
/**
 * Vacancy card, used on the Careers page.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_location = kaao_field( 'kaao_vacancy_location' );
$kaao_deadline = kaao_field( 'kaao_vacancy_deadline' );
$kaao_type     = kaao_field_choice( KAAO_CPT_VACANCY, 'kaao_vacancy_type', kaao_field( 'kaao_vacancy_type' ) );
$kaao_open     = kaao_vacancy_is_open();
?>
<article <?php post_class( 'card card--flat' ); ?>>
	<div class="card__body">
		<div class="cluster" style="gap:.5rem">
			<?php if ( $kaao_open ) : ?>
				<span class="pill pill--gold"><?php esc_html_e( 'Open', 'kaao' ); ?></span>
			<?php else : ?>
				<span class="pill pill--muted"><?php esc_html_e( 'Closed', 'kaao' ); ?></span>
			<?php endif; ?>

			<?php if ( $kaao_type ) : ?>
				<span class="pill"><?php echo esc_html( $kaao_type ); ?></span>
			<?php endif; ?>
		</div>

		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="event-row__meta">
			<?php if ( $kaao_location ) : ?>
				<span>
					<?php echo kaao_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php echo esc_html( $kaao_location ); ?></span>
				</span>
			<?php endif; ?>

			<span>
				<?php echo kaao_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( $kaao_deadline ) : ?>
					<span>
						<?php esc_html_e( 'Closes', 'kaao' ); ?>
						<time datetime="<?php echo esc_attr( $kaao_deadline ); ?>">
							<?php echo esc_html( wp_date( (string) get_option( 'date_format', 'j F Y' ), strtotime( $kaao_deadline ) ) ); ?>
						</time>
					</span>
				<?php else : ?>
					<span><?php esc_html_e( 'No closing date stated', 'kaao' ); ?></span>
				<?php endif; ?>
			</span>
		</div>

		<?php if ( kaao_excerpt( null, 22 ) ) : ?>
			<p class="card__excerpt"><?php echo esc_html( kaao_excerpt( null, 22 ) ); ?></p>
		<?php endif; ?>
	</div>
</article>
