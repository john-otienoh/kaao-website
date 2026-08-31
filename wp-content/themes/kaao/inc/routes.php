<?php
/**
 * Custom routes.
 *
 * The information architecture puts Advocacy Impact under /advocacy/, but
 * /advocacy/ is a custom post type archive, so it cannot also be a parent
 * page. These two hooks give the page the URL the IA calls for without
 * inventing a conflicting page hierarchy.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pages that live at a nested URL without a nested parent.
 *
 * @return array<string, string> Page slug => public path.
 */
function kaao_virtual_paths(): array {
	return array(
		'advocacy-impact' => 'advocacy/impact',
	);
}

/**
 * Register the rewrite rules for those pages.
 */
function kaao_add_rewrites(): void {
	foreach ( kaao_virtual_paths() as $slug => $path ) {
		add_rewrite_rule(
			'^' . preg_quote( $path, '#' ) . '/?$',
			'index.php?pagename=' . rawurlencode( $slug ),
			'top'
		);
	}
}
add_action( 'init', 'kaao_add_rewrites', 10 );

/**
 * Make get_permalink() return the nested URL for those pages.
 *
 * @param string $link    Permalink.
 * @param int    $post_id Page ID.
 * @return string
 */
function kaao_virtual_permalink( string $link, int $post_id ): string {
	$slug = get_post_field( 'post_name', $post_id );
	$map  = kaao_virtual_paths();

	return isset( $map[ $slug ] ) ? home_url( '/' . $map[ $slug ] . '/' ) : $link;
}
add_filter( 'page_link', 'kaao_virtual_permalink', 10, 2 );

/**
 * Send the flat URL to the nested one so only one address is canonical.
 */
function kaao_virtual_canonical_redirect(): void {
	if ( is_admin() || ! is_page() ) {
		return;
	}
	$slug = (string) get_post_field( 'post_name', (int) get_queried_object_id() );
	$map  = kaao_virtual_paths();
	if ( ! isset( $map[ $slug ] ) ) {
		return;
	}

	$request = '/' . trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ) . '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$target  = '/' . $map[ $slug ] . '/';

	if ( $request !== $target ) {
		wp_safe_redirect( home_url( $target ), 301, 'KAAO virtual path' );
		exit;
	}
}
add_action( 'template_redirect', 'kaao_virtual_canonical_redirect', 2 );
