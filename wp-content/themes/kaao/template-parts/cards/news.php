<?php
/**
 * News article card.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_cat = kaao_first_term( 'category' );
?>
<article <?php post_class( 'card' ); ?>>
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
		<?php if ( $kaao_cat && 'uncategorized' !== $kaao_cat->slug ) : ?>
			<div><?php kaao_term_pill( $kaao_cat ); ?></div>
		<?php endif; ?>

		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="card__excerpt"><?php echo esc_html( kaao_excerpt( null, 22 ) ); ?></p>

		<div class="card__foot">
			<time datetime="<?php echo esc_attr( (string) get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( (string) get_the_date() ); ?>
			</time>
			<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Read', 'kaao' ); ?></span>
		</div>
	</div>
</article>
