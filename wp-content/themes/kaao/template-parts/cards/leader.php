<?php
/**
 * Leadership profile card.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_position     = kaao_field( 'kaao_leader_position' );
$kaao_organisation = kaao_field( 'kaao_leader_organisation' );
?>
<article <?php post_class( 'leader-card' ); ?>>
	<div class="leader-card__photo<?php echo has_post_thumbnail() ? '' : ' leader-card__photo--empty'; ?>">
		<?php
		if ( has_post_thumbnail() ) {
			echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
				'kaao-portrait',
				array(
					'alt'   => esc_attr(
						sprintf(
							/* translators: 1: person's name, 2: position. */
							__( '%1$s, %2$s', 'kaao' ),
							wp_strip_all_tags( (string) get_the_title() ),
							$kaao_position ?: __( 'KAAO leadership', 'kaao' )
						)
					),
					'sizes' => '(min-width: 64rem) 18rem, 45vw',
				)
			);
		} else {
			echo '<span aria-hidden="true">' . esc_html( kaao_initials( (string) get_the_title() ) ) . '</span>';
		}
		?>
	</div>

	<div class="leader-card__body">
		<h3 class="leader-card__name">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $kaao_position ) : ?>
			<p class="leader-card__role"><?php echo esc_html( $kaao_position ); ?></p>
		<?php endif; ?>

		<?php if ( $kaao_organisation ) : ?>
			<p class="leader-card__org"><?php echo esc_html( $kaao_organisation ); ?></p>
		<?php endif; ?>
	</div>
</article>
