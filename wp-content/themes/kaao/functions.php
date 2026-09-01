<?php
/**
 * KAAO theme bootstrap.
 *
 * Each concern lives in its own file under inc/. Nothing is loaded that the
 * request does not need, and no third-party framework is used anywhere.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

define( 'KAAO_VERSION', '1.0.0' );
define( 'KAAO_DIR', get_template_directory() );
define( 'KAAO_URI', get_template_directory_uri() );

/**
 * Load a theme module from inc/.
 *
 * @param string $slug File name without extension.
 */
function kaao_require( string $slug ): void {
	$path = KAAO_DIR . '/inc/' . $slug . '.php';
	if ( is_readable( $path ) ) {
		require_once $path;
	}
}

foreach (
	array(
		'setup',          // Theme supports, menus, image sizes.
		'assets',         // Stylesheet + script enqueueing.
		'post-types',     // Members, Events, Advocacy, Leadership, Resources, FAQs, Vacancies.
		'taxonomies',     // Categories that classify the above.
		'meta',           // Structured fields + meta boxes.
		'options',        // Organisation details & hero configuration.
		'template-tags',  // Reusable output helpers.
		'nav-walker',     // Information architecture + accessible drawer menu.
		'breadcrumbs',    // Accessible breadcrumb trail.
		'queries',        // Archive query shaping and directory filtering.
		'seo',            // Titles, meta descriptions, canonicals, sitemap, robots.
		'schema',         // JSON-LD structured data.
		'routes',         // Nested URLs the page hierarchy cannot express.
		'redirects',      // 301 map protecting the legacy kaao.co.ke URLs.
		'contact',        // Core-only contact form handler.
		'member-access',  // Authentication and authorisation for member-only pages.
		'admin',          // Editor experience for KAAO staff.
	) as $kaao_module
) {
	kaao_require( $kaao_module );
}
unset( $kaao_module );
