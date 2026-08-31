<?php
/**
 * SEO: titles, meta descriptions, canonicals, Open Graph, robots, sitemap.
 *
 * Implemented in the theme rather than delegated to an SEO plugin: the site
 * needs perhaps 120 lines of output, and a plugin would cost more in weight
 * and admin surface than it returns. Every value derives from real content —
 * nothing is templated into a keyword string.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Separator and suffix for document titles.
 */
add_filter( 'document_title_separator', static fn(): string => '|' );

/**
 * Give archives and taxonomy pages descriptive titles.
 *
 * @param array $parts Title parts.
 * @return array
 */
function kaao_document_title_parts( array $parts ): array {
	if ( is_post_type_archive( KAAO_CPT_MEMBER ) ) {
		$parts['title'] = __( 'Member Directory', 'kaao' );
	} elseif ( is_post_type_archive( KAAO_CPT_EVENT ) ) {
		$parts['title'] = __( 'Events & Industry Engagements', 'kaao' );
	} elseif ( is_post_type_archive( KAAO_CPT_ADVOCACY ) ) {
		$parts['title'] = __( 'Advocacy & Industry Impact', 'kaao' );
	} elseif ( is_post_type_archive( KAAO_CPT_RESOURCE ) ) {
		$parts['title'] = __( 'Resource Centre', 'kaao' );
	} elseif ( is_post_type_archive( KAAO_CPT_LEADER ) ) {
		$parts['title'] = __( 'Leadership', 'kaao' );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'kaao_document_title_parts' );

/**
 * The best available description for the current view.
 *
 * @return string Plain text, ≤ 160 characters.
 */
function kaao_meta_description(): string {
	$text = '';

	if ( is_front_page() ) {
		$text = sprintf(
			/* translators: %s: organisation description. */
			__( '%s Representing, connecting and advancing the interests of Kenya’s aviation industry.', 'kaao' ),
			kaao_org_get( 'description' )
		);
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$text = $post->post_excerpt ?: wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$text = $term->description ?: sprintf(
				/* translators: 1: term name, 2: taxonomy label. */
				__( '%1$s — %2$s from the Kenya Association of Air Operators.', 'kaao' ),
				$term->name,
				(string) ( get_taxonomy( $term->taxonomy )->labels->singular_name ?? '' )
			);
		}
	} elseif ( is_post_type_archive() ) {
		$obj  = get_queried_object();
		$text = ( $obj instanceof WP_Post_Type ) ? (string) $obj->description : '';
	} elseif ( is_home() ) {
		$text = __( 'News, press releases, advocacy updates and industry insight from the Kenya Association of Air Operators.', 'kaao' );
	} elseif ( is_search() ) {
		/* translators: %s: search term. */
		$text = sprintf( __( 'Search results for “%s” on the Kenya Association of Air Operators website.', 'kaao' ), get_search_query() );
	}

	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( '' === $text ) {
		$text = (string) get_bloginfo( 'description' );
	}

	if ( mb_strlen( $text ) > 160 ) {
		$text = rtrim( mb_substr( $text, 0, 157 ), " \t\n\r\0\x0B.,;:" ) . '…';
	}

	return $text;
}

/**
 * The canonical URL for the current view.
 *
 * @return string
 */
function kaao_canonical_url(): string {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_post_type_archive() ) {
		return (string) get_post_type_archive_link( (string) get_query_var( 'post_type' ) );
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$link = get_term_link( $term );
			return is_wp_error( $link ) ? home_url( add_query_arg( array() ) ) : $link;
		}
	}
	if ( is_home() ) {
		$page = get_option( 'page_for_posts' );
		return $page ? (string) get_permalink( $page ) : home_url( '/' );
	}

	return home_url( add_query_arg( array() ) );
}

/**
 * The image representing the current view, for social previews.
 *
 * @return array{url:string,width:int,height:int}|null
 */
function kaao_social_image(): ?array {
	$id = 0;

	if ( is_singular() && has_post_thumbnail() ) {
		$id = (int) get_post_thumbnail_id();
	}
	// Fall back to the hero image rather than shipping a separate share asset.
	if ( ! $id ) {
		$hero = kaao_hero_config();
		$id   = (int) $hero['poster_id'];
	}
	if ( ! $id ) {
		return null;
	}

	$src = wp_get_attachment_image_src( $id, 'full' );
	if ( ! $src ) {
		return null;
	}

	return array(
		'url'    => (string) $src[0],
		'width'  => (int) $src[1],
		'height' => (int) $src[2],
	);
}

/**
 * Emit the head metadata.
 */
function kaao_head_meta(): void {
	$description = kaao_meta_description();
	$canonical   = kaao_canonical_url();
	$image       = kaao_social_image();
	$site        = (string) get_bloginfo( 'name' );
	$title       = wp_get_document_title();

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}

	// Keep thin or duplicative views out of the index without hiding them.
	if ( is_search() || is_404() || ( is_paged() && ( is_search() || is_author() ) ) ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">' . "\n";
	}

	/* ------------------------------------------------------- Open Graph -- */
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $canonical ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( str_replace( '-', '_', (string) get_bloginfo( 'language' ) ) ) );

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image['url'] ) );
		printf( '<meta property="og:image:width" content="%d">' . "\n", (int) $image['width'] );
		printf( '<meta property="og:image:height" content="%d">' . "\n", (int) $image['height'] );
	}

	if ( is_singular( 'post' ) ) {
		printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( (string) get_the_date( 'c' ) ) );
		printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( (string) get_the_modified_date( 'c' ) ) );
		$cat = kaao_first_term( 'category' );
		if ( $cat ) {
			printf( '<meta property="article:section" content="%s">' . "\n", esc_attr( $cat->name ) );
		}
	}

	/* ----------------------------------------------------------- Twitter -- */
	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	$handle = kaao_org_get( 'twitter' );
	if ( $handle ) {
		$path = trim( (string) wp_parse_url( $handle, PHP_URL_PATH ), '/' );
		if ( $path ) {
			printf( '<meta name="twitter:site" content="@%s">' . "\n", esc_attr( $path ) );
		}
	}

	/* -------------------------------------------------------------- Misc -- */
	printf( '<meta name="theme-color" content="%s">' . "\n", '#26336B' );
}
add_action( 'wp_head', 'kaao_head_meta', 2 );

/**
 * Add the KAAO post types to the core XML sitemap and drop the ones that
 * should not be indexed.
 *
 * @param array $post_types Post types.
 * @return array
 */
function kaao_sitemap_post_types( array $post_types ): array {
	unset( $post_types[ KAAO_CPT_FAQ ] );

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'kaao_sitemap_post_types' );

/**
 * Keep private taxonomies out of the sitemap index.
 *
 * @param array $taxonomies Taxonomies.
 * @return array
 */
function kaao_sitemap_taxonomies( array $taxonomies ): array {
	unset( $taxonomies['post_tag'], $taxonomies['kaao_faq_topic'] );

	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'kaao_sitemap_taxonomies' );

/**
 * Point robots.txt at the sitemap and keep crawlers out of internals.
 *
 * @param string $output Existing robots.txt body.
 * @param string $public Whether the site is public.
 * @return string
 */
function kaao_robots_txt( string $output, string $public ): string {
	if ( '1' !== $public ) {
		return $output;
	}

	$lines = array(
		'User-agent: *',
		'Allow: /',
		'Disallow: /wp-admin/',
		'Allow: /wp-admin/admin-ajax.php',
		'Disallow: /?s=',
		'Disallow: /search/',
		'',
	);

	// Yoast publishes its own, richer sitemap and appends its own Sitemap:
	// line to robots.txt after this filter runs — advertising the core one
	// too would give crawlers two conflicting sitemaps for the same site.
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		$lines[] = 'Sitemap: ' . home_url( '/wp-sitemap.xml' );
		$lines[] = '';
	}

	return implode( "\n", $lines );
}
add_filter( 'robots_txt', 'kaao_robots_txt', 10, 2 );
