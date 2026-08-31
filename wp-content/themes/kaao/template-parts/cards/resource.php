<?php
/**
 * Resource card.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_file_id = (int) kaao_field( 'kaao_resource_file' );
$kaao_link    = kaao_field( 'kaao_resource_link' );
$kaao_year    = kaao_field( 'kaao_resource_year' );
$kaao_type    = kaao_first_term( 'kaao_resource_type' );

$kaao_url    = $kaao_file_id ? (string) wp_get_attachment_url( $kaao_file_id ) : $kaao_link;
$kaao_ext    = $kaao_url ? strtoupper( (string) pathinfo( (string) wp_parse_url( $kaao_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) ) : '';
$kaao_size   = $kaao_file_id ? (string) get_attached_file( $kaao_file_id ) : '';
$kaao_bytes  = ( $kaao_size && file_exists( $kaao_size ) ) ? filesize( $kaao_size ) : 0;
$kaao_is_ext = ! $kaao_file_id && $kaao_link;
?>
<article <?php post_class( 'resource-card' ); ?>>
	<div class="resource-card__icon" aria-hidden="true">
		<?php echo esc_html( $kaao_ext ?: 'DOC' ); ?>
	</div>

	<div class="resource-card__body">
		<h3 class="resource-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( kaao_excerpt( null, 22 ) ) : ?>
			<p><?php echo esc_html( kaao_excerpt( null, 22 ) ); ?></p>
		<?php endif; ?>

		<div class="cluster" style="gap:.5rem">
			<?php kaao_term_pill( $kaao_type, 'pill--muted' ); ?>

			<?php if ( $kaao_year ) : ?>
				<span class="pill pill--muted"><?php echo esc_html( $kaao_year ); ?></span>
			<?php endif; ?>

			<?php if ( $kaao_url ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $kaao_url ); ?>"
					<?php echo $kaao_is_ext ? ' target="_blank" rel="noopener noreferrer"' : ' download'; ?>>
					<?php $kaao_is_ext ? esc_html_e( 'Open', 'kaao' ) : esc_html_e( 'Download', 'kaao' ); ?>
					<span class="screen-reader-text">
						<?php
						printf(
							/* translators: 1: document title, 2: file type and size. */
							esc_html__( '%1$s (%2$s)', 'kaao' ),
							esc_html( wp_strip_all_tags( (string) get_the_title() ) ),
							esc_html( trim( $kaao_ext . ( $kaao_bytes ? ', ' . size_format( $kaao_bytes, 1 ) : '' ), ', ' ) )
						);
						?>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( $kaao_bytes ) : ?>
				<span class="muted" style="font-size:var(--fs-xs)"><?php echo esc_html( size_format( $kaao_bytes, 1 ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</article>
