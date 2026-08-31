<?php
/**
 * Advocacy card.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_issue        = kaao_field( 'kaao_advocacy_issue' );
$kaao_stakeholders = kaao_field( 'kaao_advocacy_stakeholders' );
$kaao_outcome      = kaao_field( 'kaao_advocacy_outcome' );
$kaao_status       = kaao_field( 'kaao_advocacy_status' );
$kaao_date         = kaao_field( 'kaao_advocacy_date' );
$kaao_theme        = kaao_first_term( 'kaao_advocacy_theme' );
?>
<article <?php post_class( 'advocacy-card' ); ?>>
	<div class="cluster" style="gap:.5rem">
		<?php kaao_term_pill( $kaao_theme, 'pill--gold' ); ?>
		<?php if ( $kaao_status ) : ?>
			<span class="pill pill--muted"><?php echo esc_html( ucfirst( $kaao_status ) ); ?></span>
		<?php endif; ?>
	</div>

	<h3 class="advocacy-card__title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

	<?php if ( kaao_excerpt( null, 26 ) ) : ?>
		<p class="muted" style="font-size:var(--fs-sm);margin:0"><?php echo esc_html( kaao_excerpt( null, 26 ) ); ?></p>
	<?php endif; ?>

	<dl>
		<?php if ( $kaao_issue ) : ?>
			<div>
				<dt><?php esc_html_e( 'Issue', 'kaao' ); ?></dt>
				<dd><?php echo esc_html( $kaao_issue ); ?></dd>
			</div>
		<?php endif; ?>

		<?php if ( $kaao_stakeholders ) : ?>
			<div>
				<dt><?php esc_html_e( 'Stakeholders', 'kaao' ); ?></dt>
				<dd><?php echo esc_html( $kaao_stakeholders ); ?></dd>
			</div>
		<?php endif; ?>

		<?php if ( $kaao_outcome ) : ?>
			<div>
				<dt><?php esc_html_e( 'Outcome', 'kaao' ); ?></dt>
				<dd><?php echo esc_html( wp_trim_words( $kaao_outcome, 24, '…' ) ); ?></dd>
			</div>
		<?php endif; ?>
	</dl>

	<?php if ( $kaao_date ) : ?>
		<p class="meta" style="margin:0">
			<time datetime="<?php echo esc_attr( $kaao_date ); ?>">
				<?php echo esc_html( wp_date( (string) get_option( 'date_format' ), strtotime( $kaao_date ) ) ); ?>
			</time>
		</p>
	<?php endif; ?>
</article>
