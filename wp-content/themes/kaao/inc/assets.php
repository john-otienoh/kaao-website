<?php
/**
 * Asset loading.
 *
 * The whole front end is one stylesheet and one deferred script. There is no
 * jQuery, no framework, no icon font and no webfont request.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * File modification time as a cache-busting version.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function kaao_asset_version( string $relative ): string {
	$path = KAAO_DIR . '/' . ltrim( $relative, '/' );

	return file_exists( $path ) ? (string) filemtime( $path ) : KAAO_VERSION;
}

/**
 * Enqueue front-end assets.
 */
function kaao_enqueue_assets(): void {
	wp_enqueue_style(
		'kaao',
		KAAO_URI . '/assets/css/main.css',
		array(),
		kaao_asset_version( 'assets/css/main.css' )
	);

	// The theme header stylesheet carries no rules; register it only so child
	// themes and plugins that expect the 'style.css' handle keep working.
	wp_register_style( 'kaao-style', get_stylesheet_uri(), array( 'kaao' ), KAAO_VERSION );

	wp_enqueue_script(
		'kaao',
		KAAO_URI . '/assets/js/kaao.js',
		array(),
		kaao_asset_version( 'assets/js/kaao.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kaao_enqueue_assets' );

/**
 * Remove the core block library CSS on the front end.
 *
 * All KAAO templates are hand-built; block styles would ship ~90 KB of rules
 * that never match a selector. If an editor starts using core blocks inside
 * page content, flip KAAO_KEEP_BLOCK_CSS in wp-config.php.
 */
function kaao_dequeue_block_styles(): void {
	if ( defined( 'KAAO_KEEP_BLOCK_CSS' ) && KAAO_KEEP_BLOCK_CSS ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'kaao_dequeue_block_styles', 100 );

/**
 * Preload the hero image so it is discovered in the first byte range.
 *
 * Only ever emitted for the front page, and only for the image that is
 * genuinely the LCP candidate.
 */
function kaao_preload_hero(): void {
	if ( ! is_front_page() ) {
		return;
	}
	$hero = kaao_hero_config();
	if ( empty( $hero['poster_id'] ) ) {
		return;
	}

	$srcset = wp_get_attachment_image_srcset( (int) $hero['poster_id'], 'kaao-hero' );
	$src    = wp_get_attachment_image_url( (int) $hero['poster_id'], 'kaao-hero' );
	if ( ! $src ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s"%s imagesizes="100vw" fetchpriority="high">' . "\n",
		esc_url( $src ),
		$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : ''
	);
}
add_action( 'wp_head', 'kaao_preload_hero', 1 );

/**
 * Encoding quality for generated image sizes.
 *
 * WordPress defaults to 82, which is generous for AVIF — the format holds up
 * far better than JPEG at lower settings. 62 is visually indistinguishable in
 * side-by-side comparison at 1:1 and roughly halves the bytes on every
 * thumbnail the site serves.
 *
 * @param int    $quality   Default quality.
 * @param string $mime_type Output MIME type.
 * @return int
 */
function kaao_image_quality( int $quality, string $mime_type ): int {
	return match ( $mime_type ) {
		'image/avif' => 62,
		'image/webp' => 74,
		default      => $quality,
	};
}
add_filter( 'wp_editor_set_quality', 'kaao_image_quality', 10, 2 );

/**
 * Editor styles so the block editor previews KAAO typography and colours.
 */
function kaao_editor_assets(): void {
	add_editor_style( 'assets/css/main.css' );
}
add_action( 'admin_init', 'kaao_editor_assets' );

/**
 * Lazy-load everything below the fold, but never the hero or the first card.
 *
 * WordPress already adds loading="lazy"; this makes sure the images we mark as
 * priority opt out, which is what actually moves LCP.
 *
 * @param array  $attr       Attributes.
 * @param object $attachment Attachment post.
 * @param string $size       Requested size.
 * @return array
 */
function kaao_image_attributes( $attr, $attachment, $size ) {
	if ( in_array( $size, array( 'kaao-hero', 'full' ), true ) && doing_action( 'kaao_hero' ) ) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
		$attr['decoding']      = 'sync';
	}
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'kaao_image_attributes', 10, 3 );
