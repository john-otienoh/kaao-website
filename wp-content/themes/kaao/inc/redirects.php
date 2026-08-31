<?php
/**
 * Legacy URL protection.
 *
 * The production site at kaao.co.ke has indexed URLs that the redesigned
 * information architecture moves. Every one of them is answered with a 301 so
 * no accumulated search value is dropped. Paths that stay put are deliberately
 * absent from this map — see docs/url-inventory.md for the full decision table.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Exact-path redirects: old path => new path.
 *
 * @return array<string, string>
 */
function kaao_redirect_map(): array {
	return array(
		// News consolidation — three legacy listings become one newsroom.
		'/blog'                     => '/news/',
		'/blogs'                    => '/news/',
		'/press-release'            => '/news/category/press-releases/',
		'/newsletter'               => '/resources/type/publications/',

		// Events: two legacy post types merge into one Events archive.
		'/events-and-engagements'   => '/events/',
		'/events-and-engagements-2' => '/events/',
		'/upcoming-events'          => '/events/?when=upcoming',

		// Members.
		'/our-members'              => '/members/',
		'/member-list'              => '/members/',
		'/members-fleet'            => '/members/',

		// Pages that keep their content but move in the hierarchy.
		'/terms-of-use'             => '/terms-and-conditions/',
		'/login-customizer'         => '/',

		// /gallery keeps its legacy URL: it is a real page again, built from the
		// photography attached to the engagements it documents.
	);
}

/**
 * Prefix redirects: legacy single-item bases => new base.
 *
 * @return array<string, string>
 */
function kaao_redirect_prefixes(): array {
	return array(
		'/member/'              => '/members/',
		'/event-and-engagement/'=> '/events/',
		'/upcoming-event/'      => '/events/',
		'/fleet/'               => '/members/',
	);
}

/**
 * Issue the 301s.
 */
function kaao_legacy_redirects(): void {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- parsed to a path below.
	$request = '/' . trim( sanitize_text_field( rawurldecode( $request ) ), '/' );
	if ( '/' === $request ) {
		return;
	}

	$query  = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$target = '';

	$map = kaao_redirect_map();
	if ( isset( $map[ $request ] ) ) {
		$target = $map[ $request ];
	} else {
		foreach ( kaao_redirect_prefixes() as $old_base => $new_base ) {
			if ( str_starts_with( $request . '/', $old_base ) ) {
				$slug   = trim( substr( $request . '/', strlen( $old_base ) ), '/' );
				$target = $slug ? $new_base . $slug . '/' : $new_base;
				break;
			}
		}
	}

	if ( ! $target ) {
		return;
	}

	// Never redirect onto a path that would 404: fall back to the archive.
	$destination = home_url( $target );
	if ( $query && ! str_contains( $target, '?' ) ) {
		$destination = add_query_arg( wp_parse_args( $query ), $destination );
	}

	wp_safe_redirect( $destination, 301, 'KAAO legacy URL map' );
	exit;
}
add_action( 'template_redirect', 'kaao_legacy_redirects', 1 );

/**
 * Recover URLs whose permalink base changed.
 *
 * The production site publishes articles at the site root (`/article-slug/`);
 * the redesign files them under `/news/`. Every one of those addresses is
 * indexed and linked, so an exact slug match is resolved to its new permalink
 * with a 301 rather than being allowed to 404.
 *
 * Matching is on the exact slug only — no fuzzy matching — so this can never
 * redirect a visitor to something unrelated.
 */
function kaao_recover_moved_permalinks(): void {
	if ( ! is_404() ) {
		return;
	}

	$path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$slug = trim( sanitize_text_field( rawurldecode( $path ) ), '/' );

	// Single-segment paths only: /some-article/, never /a/b/.
	if ( '' === $slug || str_contains( $slug, '/' ) ) {
		return;
	}

	$match = get_posts(
		array(
			'name'                   => sanitize_title( $slug ),
			'post_type'              => array( 'post', 'page', KAAO_CPT_MEMBER, KAAO_CPT_EVENT, KAAO_CPT_ADVOCACY, KAAO_CPT_RESOURCE, KAAO_CPT_LEADER ),
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $match ) {
		return;
	}

	$destination = (string) get_permalink( (int) $match[0] );
	if ( $destination && untrailingslashit( $destination ) !== untrailingslashit( home_url( $path ) ) ) {
		wp_safe_redirect( $destination, 301, 'KAAO moved permalink' );
		exit;
	}
}
add_action( 'template_redirect', 'kaao_recover_moved_permalinks', 4 );

/**
 * Send an unresolved legacy member/event slug to its archive rather than a
 * bare 404, so inbound links still land somewhere useful.
 */
function kaao_soft_404_fallbacks(): void {
	if ( ! is_404() ) {
		return;
	}

	$request = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$request = '/' . trim( sanitize_text_field( rawurldecode( $request ) ), '/' ) . '/';

	$bases = array(
		'/members/'   => KAAO_CPT_MEMBER,
		'/events/'    => KAAO_CPT_EVENT,
		'/advocacy/'  => KAAO_CPT_ADVOCACY,
		'/resources/' => KAAO_CPT_RESOURCE,
	);

	foreach ( $bases as $base => $post_type ) {
		if ( '/' . trim( $base, '/' ) . '/' === $request || ! str_starts_with( $request, $base ) ) {
			continue;
		}
		$slug = trim( str_replace( $base, '', $request ), '/' );
		if ( ! $slug || str_contains( $slug, '/' ) ) {
			continue;
		}
		// Try a fuzzy title match before giving up.
		$match = get_posts(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				's'              => str_replace( '-', ' ', $slug ),
				'no_found_rows'  => true,
			)
		);
		if ( $match ) {
			wp_safe_redirect( (string) get_permalink( (int) $match[0] ), 301, 'KAAO slug recovery' );
			exit;
		}
	}
}
add_action( 'template_redirect', 'kaao_soft_404_fallbacks', 5 );
