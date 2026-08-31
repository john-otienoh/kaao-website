<?php
/**
 * Theme supports, navigation menus and image sizes.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 */
function kaao_setup(): void {
	load_theme_textdomain( 'kaao', KAAO_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'               => 120,
			'width'                => 400,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => false,
		)
	);

	register_nav_menus(
		array(
			'primary'   => __( 'Primary navigation', 'kaao' ),
			'footer_1'  => __( 'Footer — Quick Links', 'kaao' ),
			'footer_2'  => __( 'Footer — Membership', 'kaao' ),
			'footer_3'  => __( 'Footer — Resources', 'kaao' ),
			'legal'     => __( 'Footer — Legal', 'kaao' ),
		)
	);

	/*
	 * Image sizes are deliberately few. Every extra size multiplies the files
	 * WordPress writes on upload; these four cover every layout in the theme.
	 */
	set_post_thumbnail_size( 1200, 750, true );
	add_image_size( 'kaao-card', 720, 450, true );      // Card media (16:10).
	add_image_size( 'kaao-portrait', 600, 750, true );  // Leadership photos (4:5).
	add_image_size( 'kaao-logo', 400, 400, false );     // Member logos, contained.
	add_image_size( 'kaao-hero', 1920, 1080, true );    // Full-height homepage hero.
	/*
	 * Interior page banners render as a short band, so a 16:9 image would ship
	 * roughly three times the pixels the layout can ever show. This crop
	 * matches what is actually visible.
	 */
	add_image_size( 'kaao-banner', 1920, 560, true );

	// Editors pick from meaningful names rather than raw pixel sizes.
	add_filter(
		'image_size_names_choose',
		static function ( array $sizes ): array {
			return array_merge(
				$sizes,
				array(
					'kaao-card'     => __( 'Card (720×450)', 'kaao' ),
					'kaao-portrait' => __( 'Portrait (600×750)', 'kaao' ),
					'kaao-logo'     => __( 'Logo (max 400)', 'kaao' ),
				)
			);
		}
	);

	/*
	 * Core generates three intermediate sizes no KAAO template requests. On a
	 * media library of a few hundred images that is over a thousand files the
	 * site never serves. Removing them shrinks uploads/ and speeds up imports;
	 * the sizes above already cover every srcset the theme emits.
	 */
	remove_image_size( 'medium_large' );
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
}
add_action( 'after_setup_theme', 'kaao_setup' );

/**
 * Content width used by oEmbed and wide blocks.
 */
function kaao_content_width(): void {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'kaao_content_width', 0 );

/**
 * Widget areas. Only one, and only where it earns its place.
 */
function kaao_widgets_init(): void {
	register_sidebar(
		array(
			'name'          => __( 'News sidebar', 'kaao' ),
			'id'            => 'news-sidebar',
			'description'   => __( 'Shown beside single news articles.', 'kaao' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'kaao_widgets_init' );

/**
 * Trim the WordPress head of markup this site does not use.
 *
 * Each removal is a real request or a real byte saved; nothing here affects
 * editing, the REST API, or the block editor.
 */
function kaao_clean_head(): void {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
}
add_action( 'init', 'kaao_clean_head' );

/**
 * Drop the emoji DNS prefetch that survives the action removals above.
 *
 * @param array  $urls          Resource URLs.
 * @param string $relation_type Link relation.
 * @return array
 */
function kaao_no_emoji_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}
	$emoji = (string) apply_filters( 'emoji_svg_url', 'https://s.w.org/' );

	return array_values(
		array_filter(
			(array) $urls,
			static fn( $url ) => ! is_string( $url ) || false === strpos( $url, $emoji )
		)
	);
}
add_filter( 'wp_resource_hints', 'kaao_no_emoji_prefetch', 10, 2 );

/**
 * Disable XML-RPC. Nothing in this project uses it and it is a standing
 * brute-force target.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Keep the REST API open for reads but require authentication for user
 * enumeration, which is the only endpoint that leaks anything useful.
 *
 * @param mixed $result Current result.
 * @return mixed
 */
function kaao_protect_user_endpoint( $result ) {
	if ( ! empty( $result ) ) {
		return $result;
	}
	$route = isset( $GLOBALS['wp']->query_vars['rest_route'] ) ? (string) $GLOBALS['wp']->query_vars['rest_route'] : '';
	if ( str_starts_with( $route, '/wp/v2/users' ) && ! is_user_logged_in() ) {
		return new WP_Error( 'rest_forbidden', __( 'Authentication required.', 'kaao' ), array( 'status' => 401 ) );
	}

	return $result;
}
add_filter( 'rest_authentication_errors', 'kaao_protect_user_endpoint' );

/**
 * Excerpt length and ellipsis tuned to the card layouts.
 */
add_filter( 'excerpt_length', static fn(): int => 26 );
add_filter( 'excerpt_more', static fn(): string => '…' );

/**
 * Body classes describing the current template, so CSS can respond without
 * inline styles.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function kaao_body_class( array $classes ): array {
	if ( is_front_page() ) {
		$classes[] = 'is-front';
	}
	if ( ! is_active_sidebar( 'news-sidebar' ) ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'kaao_body_class' );
