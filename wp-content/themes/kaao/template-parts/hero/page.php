<?php
/**
 * Interior page banner.
 *
 * @package KAAO
 *
 * Expected $args:
 *   title      string  Heading text (required).
 *   intro      string  Supporting line.
 *   eyebrow    string  Small caps label.
 *   image_id   int     Background image attachment ID.
 *   image_slug string  Attachment slug, used when image_id is absent.
 */

defined( 'ABSPATH' ) || exit;

$kaao_a = wp_parse_args(
	$args ?? array(),
	array(
		'title'      => '',
		'intro'      => '',
		'eyebrow'    => '',
		'image_id'   => 0,
		'image_slug' => '',
	)
);

if ( ! $kaao_a['image_id'] && $kaao_a['image_slug'] ) {
	$kaao_a['image_id'] = kaao_attachment_id_by_slug( $kaao_a['image_slug'] );
}
?>
<section class="page-hero<?php echo $kaao_a['image_id'] ? '' : ' page-hero--plain'; ?>">
	<?php if ( $kaao_a['image_id'] ) : ?>
		<div class="page-hero__media">
			<?php
			echo kaao_image( // phpcs:ignore WordPress.Security.EscapeOutput
				(int) $kaao_a['image_id'],
				'kaao-banner',
				array(
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'decoding'      => 'sync',
					'sizes'         => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="container">
		<?php if ( $kaao_a['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $kaao_a['eyebrow'] ); ?></p>
		<?php endif; ?>

		<h1 class="balance"><?php echo esc_html( $kaao_a['title'] ); ?></h1>

		<?php if ( $kaao_a['intro'] ) : ?>
			<p class="pretty"><?php echo esc_html( $kaao_a['intro'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
