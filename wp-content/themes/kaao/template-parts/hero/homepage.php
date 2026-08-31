<?php
/**
 * Homepage hero — three interchangeable modes.
 *
 * Mode A  video   — poster image paints first, the video is attached by
 *                   JavaScript only on wide screens, fast connections and when
 *                   motion is not reduced.
 * Mode B  image    — one optimised image. Always the fastest option.
 * Mode C  slider   — cross-fading images, keyboard operable, paused on hover,
 *                   focus, hidden tab and reduced-motion.
 *
 * Whichever mode is chosen, exactly one image is preloaded and eagerly decoded,
 * so the LCP element is identical across all three.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_hero   = kaao_hero_config();
$kaao_mode   = $kaao_hero['mode'];
$kaao_poster = (int) $kaao_hero['poster_id'];
$kaao_slides = $kaao_hero['slides'];

// A slider needs at least two images; otherwise fall back to the static image.
if ( 'slider' === $kaao_mode && count( $kaao_slides ) < 2 ) {
	$kaao_mode = 'image';
}
// A video needs a source; otherwise the poster stands alone.
if ( 'video' === $kaao_mode && ! $kaao_hero['video_mp4'] && ! $kaao_hero['video_web'] ) {
	$kaao_mode = 'image';
}

/**
 * Attributes for the one image that is the LCP candidate.
 *
 * @var array<string,string>
 */
$kaao_lcp_attrs = array(
	'loading'       => 'eager',
	'fetchpriority' => 'high',
	'decoding'      => 'sync',
	'sizes'         => '100vw',
);
?>
<section class="hero" aria-labelledby="hero-title"<?php echo 'slider' === $kaao_mode ? ' data-hero-slider data-interval="3000"' : ''; ?>>

	<?php if ( 'slider' === $kaao_mode ) : ?>

		<div class="hero__slides">
			<?php foreach ( $kaao_slides as $kaao_i => $kaao_slide_id ) : ?>
				<div class="hero__slide<?php echo 0 === $kaao_i ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $kaao_i ? 'false' : 'true'; ?>">
					<?php
					echo kaao_image( // phpcs:ignore WordPress.Security.EscapeOutput -- wp_get_attachment_image output.
						(int) $kaao_slide_id,
						'kaao-hero',
						0 === $kaao_i
							? $kaao_lcp_attrs + array( 'alt' => '' )
							: array( 'loading' => 'lazy', 'alt' => '' )
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>

	<?php else : ?>

		<div class="hero__media">
			<?php
			if ( $kaao_poster ) {
				echo kaao_image( $kaao_poster, 'kaao-hero', $kaao_lcp_attrs + array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}

			if ( 'video' === $kaao_mode ) :
				?>
				<video
					data-hero-video
					muted
					playsinline
					loop
					preload="none"
					aria-hidden="true"
					tabindex="-1"
					<?php echo $kaao_poster ? 'poster="' . esc_url( (string) wp_get_attachment_image_url( $kaao_poster, 'kaao-hero' ) ) . '"' : ''; ?>>
					<?php if ( $kaao_hero['video_web'] ) : ?>
						<source data-src="<?php echo esc_url( $kaao_hero['video_web'] ); ?>" type="video/webm">
					<?php endif; ?>
					<?php if ( $kaao_hero['video_mp4'] ) : ?>
						<source data-src="<?php echo esc_url( $kaao_hero['video_mp4'] ); ?>" type="video/mp4">
					<?php endif; ?>
				</video>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<div class="hero__scrim" aria-hidden="true"></div>

	<div class="container">
		<div class="hero__inner">
			<p class="eyebrow"><?php esc_html_e( 'Kenya Association of Air Operators', 'kaao' ); ?></p>

			<h1 id="hero-title"><?php echo esc_html( $kaao_hero['headline'] ); ?></h1>

			<p class="hero__sub"><?php echo esc_html( $kaao_hero['subhead'] ); ?></p>

			<div class="hero__actions">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/membership/how-to-join/' ) ); ?>">
					<?php esc_html_e( 'Become a member', 'kaao' ); ?>
				</a>
				<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">
					<?php esc_html_e( 'Explore KAAO', 'kaao' ); ?>
				</a>
			</div>
		</div>
	</div>

	<?php if ( 'slider' === $kaao_mode ) : ?>
		<ul class="hero__dots">
			<?php foreach ( $kaao_slides as $kaao_i => $kaao_slide_id ) : ?>
				<li>
					<button
						type="button"
						class="hero__dot"
						aria-current="<?php echo 0 === $kaao_i ? 'true' : 'false'; ?>">
						<span class="screen-reader-text">
							<?php
							printf(
								/* translators: %d: slide number. */
								esc_html__( 'Show slide %d', 'kaao' ),
								(int) $kaao_i + 1
							);
							?>
						</span>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
