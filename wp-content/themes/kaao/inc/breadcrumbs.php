<?php
/**
 * Breadcrumb trail.
 *
 * Emits an accessible <nav> and feeds the same data to the BreadcrumbList
 * JSON-LD in schema.php, so the visible trail and the structured data can
 * never disagree.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build the breadcrumb trail for the current request.
 *
 * @return array<int, array{label:string,url:string}> Ordered crumbs; the last has an empty URL.
 */
function kaao_breadcrumb_trail(): array {
	if ( is_front_page() ) {
		return array();
	}

	$crumbs = array(
		array(
			'label' => __( 'Home', 'kaao' ),
			'url'   => home_url( '/' ),
		),
	);

	$add = static function ( string $label, string $url = '' ) use ( &$crumbs ): void {
		if ( '' !== $label ) {
			$crumbs[] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	};

	/**
	 * Push the archive link for a post type, when it has one.
	 *
	 * @param string $post_type Post type key.
	 */
	$add_archive = static function ( string $post_type ) use ( $add ): void {
		$obj = get_post_type_object( $post_type );
		if ( ! $obj || ! $obj->has_archive ) {
			return;
		}
		$link = get_post_type_archive_link( $post_type );
		$add( $obj->labels->name, $link ?: '' );
	};

	if ( is_singular() ) {
		$post_type = get_post_type();

		if ( 'page' === $post_type ) {
			foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
				$add( get_the_title( $ancestor ), (string) get_permalink( $ancestor ) );
			}
		} elseif ( 'post' === $post_type ) {
			$news = get_option( 'page_for_posts' );
			$add(
				$news ? get_the_title( $news ) : __( 'News & Insights', 'kaao' ),
				$news ? (string) get_permalink( $news ) : home_url( '/news/' )
			);
			$cat = kaao_first_term( 'category' );
			if ( $cat && 'uncategorized' !== $cat->slug ) {
				$link = get_term_link( $cat );
				$add( $cat->name, is_wp_error( $link ) ? '' : $link );
			}
		} elseif ( KAAO_CPT_VACANCY === $post_type ) {
			// Vacancies have no archive: the Careers page is their listing.
			$careers = get_page_by_path( 'careers' );
			$add(
				$careers ? get_the_title( $careers ) : __( 'Careers', 'kaao' ),
				$careers ? (string) get_permalink( $careers ) : home_url( '/careers/' )
			);
		} else {
			$add_archive( $post_type );

			// One classifying term gives the trail useful depth.
			$tax_for = array(
				KAAO_CPT_MEMBER   => 'kaao_member_category',
				KAAO_CPT_EVENT    => 'kaao_event_type',
				KAAO_CPT_ADVOCACY => 'kaao_advocacy_theme',
				KAAO_CPT_RESOURCE => 'kaao_resource_type',
				KAAO_CPT_LEADER   => 'kaao_leader_group',
			);
			if ( isset( $tax_for[ $post_type ] ) ) {
				$term = kaao_first_term( $tax_for[ $post_type ] );
				if ( $term ) {
					$link = get_term_link( $term );
					$add( $term->name, is_wp_error( $link ) ? '' : $link );
				}
			}
		}

		$add( get_the_title() );

	} elseif ( is_post_type_archive() ) {
		$add( post_type_archive_title( '', false ) );

	} elseif ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$tax = get_taxonomy( $term->taxonomy );
			if ( $tax && ! empty( $tax->object_type[0] ) && 'post' !== $tax->object_type[0] ) {
				$add_archive( $tax->object_type[0] );
			} elseif ( is_category() || is_tag() ) {
				$news = get_option( 'page_for_posts' );
				$add(
					$news ? get_the_title( $news ) : __( 'News & Insights', 'kaao' ),
					$news ? (string) get_permalink( $news ) : home_url( '/news/' )
				);
			}
			if ( $term->parent ) {
				$parent = get_term( $term->parent, $term->taxonomy );
				if ( $parent instanceof WP_Term ) {
					$link = get_term_link( $parent );
					$add( $parent->name, is_wp_error( $link ) ? '' : $link );
				}
			}
			$add( $term->name );
		}
	} elseif ( is_home() ) {
		$add( single_post_title( '', false ) ?: __( 'News & Insights', 'kaao' ) );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$add( sprintf( __( 'Search results for “%s”', 'kaao' ), get_search_query() ) );
	} elseif ( is_404() ) {
		$add( __( 'Page not found', 'kaao' ) );
	} elseif ( is_archive() ) {
		$add( wp_strip_all_tags( get_the_archive_title() ) );
	}

	return $crumbs;
}

/**
 * Output the breadcrumb navigation.
 */
function kaao_breadcrumbs(): void {
	$crumbs = kaao_breadcrumb_trail();
	if ( count( $crumbs ) < 2 ) {
		return;
	}
	?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'kaao' ); ?>">
		<div class="container">
			<ol>
				<?php foreach ( $crumbs as $crumb ) : ?>
					<li>
						<?php if ( $crumb['url'] ) : ?>
							<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
						<?php else : ?>
							<span aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</nav>
	<?php
}
