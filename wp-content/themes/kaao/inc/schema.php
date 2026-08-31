<?php
/**
 * JSON-LD structured data.
 *
 * Only types that accurately describe the page are emitted. An Event node is
 * never produced without a real date; a FAQPage never without real Q&A pairs.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * The Organization node, referenced by @id from every other graph.
 *
 * @return array
 */
function kaao_schema_organization(): array {
	$org = kaao_org();

	$node = array(
		'@type'         => array( 'Organization', 'NGO' ),
		'@id'           => home_url( '/#organization' ),
		'name'          => $org['legal_name'],
		'alternateName' => $org['short_name'],
		'url'           => home_url( '/' ),
		'description'   => $org['description'],
		'foundingDate'  => $org['founded'],
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $org['address'],
			'postOfficeBoxNumber' => $org['po_box'],
			'addressLocality' => $org['locality'],
			'addressCountry'  => $org['country'],
		),
	);

	if ( $org['phone'] ) {
		$node['telephone'] = $org['phone'];
	}
	if ( $org['email'] ) {
		$node['email'] = $org['email'];
	}

	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$node['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
			);
		}
	}

	$same_as = array_values( array_filter( array_map( static fn( $s ) => $s['url'], kaao_social_links() ) ) );
	if ( $same_as ) {
		$node['sameAs'] = $same_as;
	}

	return $node;
}

/**
 * The WebSite node, carrying the site search action.
 *
 * @return array
 */
function kaao_schema_website(): array {
	return array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => (string) get_bloginfo( 'name' ),
		'description'     => (string) get_bloginfo( 'description' ),
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'      => (string) get_bloginfo( 'language' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * BreadcrumbList built from the same trail the visible breadcrumbs use.
 *
 * @return array|null
 */
function kaao_schema_breadcrumbs(): ?array {
	$crumbs = kaao_breadcrumb_trail();
	if ( count( $crumbs ) < 2 ) {
		return null;
	}

	$items = array();
	foreach ( $crumbs as $i => $crumb ) {
		$item = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb['label'],
		);
		if ( $crumb['url'] ) {
			$item['item'] = $crumb['url'];
		}
		$items[] = $item;
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => kaao_canonical_url() . '#breadcrumb',
		'itemListElement' => $items,
	);
}

/**
 * Article node for a news post.
 *
 * @return array|null
 */
function kaao_schema_article(): ?array {
	if ( ! is_singular( 'post' ) ) {
		return null;
	}

	$node = array(
		'@type'            => 'Article',
		'@id'              => get_permalink() . '#article',
		'headline'         => wp_strip_all_tags( (string) get_the_title() ),
		'datePublished'    => (string) get_the_date( 'c' ),
		'dateModified'     => (string) get_the_modified_date( 'c' ),
		'mainEntityOfPage' => array( '@id' => (string) get_permalink() ),
		'publisher'        => array( '@id' => home_url( '/#organization' ) ),
		'author'           => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'       => (string) get_bloginfo( 'language' ),
	);

	$excerpt = kaao_excerpt( null, 40 );
	if ( $excerpt ) {
		$node['description'] = $excerpt;
	}

	$image = kaao_social_image();
	if ( $image ) {
		$node['image'] = array(
			'@type'  => 'ImageObject',
			'url'    => $image['url'],
			'width'  => $image['width'],
			'height' => $image['height'],
		);
	}

	$cat = kaao_first_term( 'category' );
	if ( $cat ) {
		$node['articleSection'] = $cat->name;
	}

	return $node;
}

/**
 * Event node — emitted only when a real start date exists.
 *
 * @return array|null
 */
function kaao_schema_event(): ?array {
	if ( ! is_singular( KAAO_CPT_EVENT ) ) {
		return null;
	}
	$start = kaao_field( 'kaao_event_start' );
	if ( ! $start ) {
		return null;
	}

	$end      = kaao_field( 'kaao_event_end' );
	$location = kaao_field( 'kaao_event_location' );

	$node = array(
		'@type'               => 'Event',
		'@id'                 => get_permalink() . '#event',
		'name'                => wp_strip_all_tags( (string) get_the_title() ),
		'startDate'           => $start,
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'organizer'           => array( '@id' => home_url( '/#organization' ) ),
	);

	if ( $end ) {
		$node['endDate'] = $end;
	}

	$description = kaao_excerpt( null, 45 );
	if ( $description ) {
		$node['description'] = $description;
	}

	if ( $location ) {
		$node['location'] = array(
			'@type'   => 'Place',
			'name'    => $location,
			'address' => array(
				'@type'          => 'PostalAddress',
				'addressCountry' => kaao_org_get( 'country' ),
			),
		);
	}

	$image = kaao_social_image();
	if ( $image ) {
		$node['image'] = $image['url'];
	}

	$register = kaao_field( 'kaao_event_register' );
	if ( $register ) {
		$node['url'] = $register;
	}

	return $node;
}

/**
 * Person node for a leadership profile.
 *
 * @return array|null
 */
function kaao_schema_person(): ?array {
	if ( ! is_singular( KAAO_CPT_LEADER ) ) {
		return null;
	}

	$node = array(
		'@type'          => 'Person',
		'@id'            => get_permalink() . '#person',
		'name'           => wp_strip_all_tags( (string) get_the_title() ),
		'memberOf'       => array( '@id' => home_url( '/#organization' ) ),
	);

	$position = kaao_field( 'kaao_leader_position' );
	if ( $position ) {
		$node['jobTitle'] = $position;
	}
	$organisation = kaao_field( 'kaao_leader_organisation' );
	if ( $organisation ) {
		$node['worksFor'] = array(
			'@type' => 'Organization',
			'name'  => $organisation,
		);
	}
	if ( has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( (int) get_post_thumbnail_id(), 'kaao-portrait' );
		if ( $src ) {
			$node['image'] = $src[0];
		}
	}
	$linkedin = kaao_field( 'kaao_leader_linkedin' );
	if ( $linkedin ) {
		$node['sameAs'] = array( $linkedin );
	}

	return $node;
}

/**
 * FAQPage node, built from the FAQs actually rendered on the page.
 *
 * @return array|null
 */
function kaao_schema_faq(): ?array {
	$faqs = kaao_faq_schema_buffer();
	if ( ! $faqs ) {
		return null;
	}

	$entities = array();
	foreach ( $faqs as $faq ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $faq['q'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $faq['a'],
			),
		);
	}

	return array(
		'@type'      => 'FAQPage',
		'@id'        => kaao_canonical_url() . '#faq',
		'mainEntity' => $entities,
	);
}

/**
 * Collect the FAQs rendered during this request.
 *
 * The accordion template part registers each pair here, so the structured data
 * describes exactly what a visitor can see — never more.
 *
 * @param array|null $add Optional pair to record: array{q:string,a:string}.
 * @return array
 */
function kaao_faq_schema_buffer( ?array $add = null ): array {
	static $buffer = array();
	if ( null !== $add && ! empty( $add['q'] ) && ! empty( $add['a'] ) ) {
		$buffer[] = $add;
	}

	return $buffer;
}

/**
 * ItemList node for the member directory, so the listing is machine-readable.
 *
 * @return array|null
 */
function kaao_schema_member_list(): ?array {
	if ( ! is_post_type_archive( KAAO_CPT_MEMBER ) ) {
		return null;
	}
	global $wp_query;
	if ( empty( $wp_query->posts ) ) {
		return null;
	}

	$items = array();
	foreach ( $wp_query->posts as $i => $post ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'url'      => (string) get_permalink( $post ),
			'name'     => wp_strip_all_tags( (string) get_the_title( $post ) ),
		);
	}

	return array(
		'@type'           => 'ItemList',
		'@id'             => kaao_canonical_url() . '#members',
		'name'            => __( 'KAAO Member Directory', 'kaao' ),
		'numberOfItems'   => count( $items ),
		'itemListElement' => $items,
	);
}

/**
 * Organization node for a member profile.
 *
 * @return array|null
 */
function kaao_schema_member(): ?array {
	if ( ! is_singular( KAAO_CPT_MEMBER ) ) {
		return null;
	}

	$node = array(
		'@type'    => 'Organization',
		'@id'      => get_permalink() . '#member',
		'name'     => wp_strip_all_tags( (string) get_the_title() ),
		'memberOf' => array( '@id' => home_url( '/#organization' ) ),
	);

	$description = kaao_excerpt( null, 45 );
	if ( $description ) {
		$node['description'] = $description;
	}
	foreach (
		array(
			'kaao_member_website' => 'url',
			'kaao_member_email'   => 'email',
			'kaao_member_phone'   => 'telephone',
		) as $meta_key => $property
	) {
		$value = kaao_field( $meta_key );
		if ( $value ) {
			$node[ $property ] = $value;
		}
	}
	$location = kaao_field( 'kaao_member_location' );
	if ( $location ) {
		$node['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $location,
			'addressCountry'  => kaao_org_get( 'country' ),
		);
	}
	if ( has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( (int) get_post_thumbnail_id(), 'kaao-logo' );
		if ( $src ) {
			$node['logo'] = $src[0];
		}
	}

	return $node;
}

/**
 * ContactPage / WebPage node.
 *
 * @return array
 */
function kaao_schema_webpage(): array {
	$node = array(
		'@type'      => is_page_template( 'page-templates/contact.php' ) ? 'ContactPage' : 'WebPage',
		'@id'        => kaao_canonical_url() . '#webpage',
		'url'        => kaao_canonical_url(),
		'name'       => wp_get_document_title(),
		'description'=> kaao_meta_description(),
		'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
		'about'      => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage' => (string) get_bloginfo( 'language' ),
	);

	if ( is_singular() ) {
		$node['datePublished'] = (string) get_the_date( 'c' );
		$node['dateModified']  = (string) get_the_modified_date( 'c' );
	}

	return $node;
}

/**
 * Print the JSON-LD graph in the footer, once every template part that
 * contributes to it (FAQs in particular) has run.
 */
function kaao_print_schema(): void {
	if ( is_404() || is_search() ) {
		return;
	}

	$graph = array_values(
		array_filter(
			array(
				kaao_schema_organization(),
				kaao_schema_website(),
				kaao_schema_webpage(),
				kaao_schema_breadcrumbs(),
				kaao_schema_article(),
				kaao_schema_event(),
				kaao_schema_person(),
				kaao_schema_member(),
				kaao_schema_member_list(),
				kaao_schema_faq(),
			)
		)
	);

	$payload = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	);

	if ( ! $payload ) {
		return;
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		$payload // phpcs:ignore WordPress.Security.EscapeOutput -- wp_json_encode output inside a JSON-LD script block.
	);
}
add_action( 'wp_footer', 'kaao_print_schema', 30 );
