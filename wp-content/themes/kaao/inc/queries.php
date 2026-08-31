<?php
/**
 * Archive query shaping and the member directory filter.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shape the main query for KAAO archives.
 *
 * @param WP_Query $q Query.
 */
function kaao_pre_get_posts( WP_Query $q ): void {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}

	/* ------------------------------------------------- Member directory --- */
	if ( $q->is_post_type_archive( KAAO_CPT_MEMBER ) || $q->is_tax( array( 'kaao_member_category', 'kaao_sector', 'kaao_location' ) ) ) {
		$q->set( 'posts_per_page', 24 );
		$q->set( 'orderby', array( 'title' => 'ASC' ) );

		$tax_query = array( 'relation' => 'AND' );
		foreach (
			array(
				'member_category' => 'kaao_member_category',
				'sector'          => 'kaao_sector',
				'location'        => 'kaao_location',
			) as $param => $taxonomy
		) {
			$slug = isset( $_GET[ $param ] ) ? sanitize_title( wp_unslash( $_GET[ $param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public read-only filter.
			if ( $slug ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $slug,
				);
			}
		}
		if ( count( $tax_query ) > 1 ) {
			$existing = (array) $q->get( 'tax_query' );
			$q->set( 'tax_query', array_merge( $existing, $tax_query ) );
		}
		return;
	}

	/* -------------------------------------------------------- Leadership -- */
	if ( $q->is_post_type_archive( KAAO_CPT_LEADER ) || $q->is_tax( 'kaao_leader_group' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		$q->set( 'order', 'ASC' );
		return;
	}

	/* ------------------------------------------------------------ Events -- */
	if ( $q->is_post_type_archive( KAAO_CPT_EVENT ) || $q->is_tax( 'kaao_event_type' ) ) {
		$q->set( 'posts_per_page', 12 );
		$q->set( 'meta_key', 'kaao_event_start' );
		$q->set( 'orderby', array( 'meta_value' => 'DESC', 'date' => 'DESC' ) );

		$when = isset( $_GET['when'] ) ? sanitize_key( wp_unslash( $_GET['when'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$today = current_time( 'Y-m-d' );

		if ( 'upcoming' === $when ) {
			$q->set(
				'meta_query',
				array(
					array(
						'key'     => 'kaao_event_start',
						'value'   => $today,
						'compare' => '>=',
						'type'    => 'DATE',
					),
				)
			);
			$q->set( 'orderby', array( 'meta_value' => 'ASC' ) );
		} elseif ( 'past' === $when ) {
			$q->set(
				'meta_query',
				array(
					array(
						'key'     => 'kaao_event_start',
						'value'   => $today,
						'compare' => '<',
						'type'    => 'DATE',
					),
				)
			);
		}
		return;
	}

	/* --------------------------------------------------------- Resources -- */
	if ( $q->is_post_type_archive( KAAO_CPT_RESOURCE ) || $q->is_tax( 'kaao_resource_type' ) ) {
		$q->set( 'posts_per_page', 20 );
		$q->set( 'orderby', array( 'date' => 'DESC' ) );

		$year = isset( $_GET['year'] ) ? (int) $_GET['year'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $year ) {
			$q->set(
				'meta_query',
				array(
					array(
						'key'     => 'kaao_resource_year',
						'value'   => (string) $year,
						'compare' => '=',
					),
				)
			);
		}
		return;
	}

	/* ---------------------------------------------------------- Advocacy -- */
	if ( $q->is_post_type_archive( KAAO_CPT_ADVOCACY ) || $q->is_tax( 'kaao_advocacy_theme' ) ) {
		$q->set( 'posts_per_page', 12 );
		return;
	}

	/* ------------------------------------------------------------- Search - */
	if ( $q->is_search() ) {
		$q->set(
			'post_type',
			array( 'post', 'page', KAAO_CPT_MEMBER, KAAO_CPT_EVENT, KAAO_CPT_ADVOCACY, KAAO_CPT_RESOURCE, KAAO_CPT_LEADER )
		);
		$q->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'kaao_pre_get_posts' );

/**
 * The member filters currently applied, for the "active filters" chips.
 *
 * @return array<string, array{taxonomy:string,term:WP_Term}>
 */
function kaao_active_member_filters(): array {
	$out = array();
	foreach (
		array(
			'member_category' => 'kaao_member_category',
			'sector'          => 'kaao_sector',
			'location'        => 'kaao_location',
		) as $param => $taxonomy
	) {
		$slug = isset( $_GET[ $param ] ) ? sanitize_title( wp_unslash( $_GET[ $param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! $slug ) {
			continue;
		}
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( $term instanceof WP_Term ) {
			$out[ $param ] = array(
				'taxonomy' => $taxonomy,
				'term'     => $term,
			);
		}
	}

	return $out;
}

/**
 * Current URL minus one filter parameter — used by the chip "remove" links.
 *
 * @param string $param Parameter to drop.
 * @return string
 */
function kaao_url_without( string $param ): string {
	$base = get_post_type_archive_link( KAAO_CPT_MEMBER ) ?: home_url( '/members/' );
	$args = array();
	foreach ( array( 's', 'member_category', 'sector', 'location' ) as $key ) {
		if ( $key === $param ) {
			continue;
		}
		$value = isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( '' !== $value ) {
			$args[ $key ] = $value;
		}
	}

	return $args ? add_query_arg( $args, $base ) : $base;
}

/**
 * Restrict a member-directory search to the member post type.
 *
 * WordPress routes ?s= to the search template; when the query also carries a
 * member filter we keep the visitor on the directory instead.
 *
 * @param WP_Query $q Query.
 */
function kaao_member_search_scope( WP_Query $q ): void {
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) {
		return;
	}
	$scope = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( KAAO_CPT_MEMBER !== $scope ) {
		return;
	}

	$q->set( 'post_type', KAAO_CPT_MEMBER );
	$q->set( 'posts_per_page', 24 );
	$q->set( 'orderby', array( 'title' => 'ASC' ) );

	$tax_query = array();
	foreach (
		array(
			'member_category' => 'kaao_member_category',
			'sector'          => 'kaao_sector',
			'location'        => 'kaao_location',
		) as $param => $taxonomy
	) {
		$slug = isset( $_GET[ $param ] ) ? sanitize_title( wp_unslash( $_GET[ $param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $slug ) {
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $slug,
			);
		}
	}
	if ( $tax_query ) {
		$q->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', 'kaao_member_search_scope', 20 );

/**
 * Use the directory template for a member-scoped search.
 *
 * @param string $template Resolved template.
 * @return string
 */
function kaao_member_search_template( string $template ): string {
	$scope = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( is_search() && KAAO_CPT_MEMBER === $scope ) {
		$archive = locate_template( 'archive-' . KAAO_CPT_MEMBER . '.php' );
		if ( $archive ) {
			return $archive;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kaao_member_search_template', 20 );
