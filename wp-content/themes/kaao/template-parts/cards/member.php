<?php
/**
 * Member card, used in the directory and in homepage sections.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_category = kaao_first_term( 'kaao_member_category' );
$kaao_sector   = kaao_first_term( 'kaao_sector' );
$kaao_excerpt  = kaao_excerpt( null, 20 );
?>
<article <?php post_class( 'member-card' ); ?>>
	<div class="member-card__logo<?php echo has_post_thumbnail() ? '' : ' member-card__logo--empty'; ?>">
		<?php
		if ( has_post_thumbnail() ) {
			echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
				'kaao-logo',
				array(
					'alt'   => esc_attr(
						sprintf(
							/* translators: %s: organisation name. */
							__( '%s logo', 'kaao' ),
							wp_strip_all_tags( (string) get_the_title() )
						)
					),
					'sizes' => '200px',
				)
			);
		} else {
			echo '<span aria-hidden="true">' . esc_html( kaao_initials( (string) get_the_title() ) ) . '</span>';
		}
		?>
	</div>

	<div>
		<h3 class="member-card__name">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $kaao_category || $kaao_sector ) : ?>
			<div class="cluster" style="margin-top:.5rem;gap:.375rem">
				<?php
				kaao_term_pill( $kaao_category, 'pill--gold' );
				kaao_term_pill( $kaao_sector, 'pill--muted' );
				?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $kaao_excerpt ) : ?>
		<p class="member-card__excerpt"><?php echo esc_html( $kaao_excerpt ); ?></p>
	<?php endif; ?>
</article>
