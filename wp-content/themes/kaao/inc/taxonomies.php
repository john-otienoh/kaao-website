<?php
/**
 * Taxonomies that classify the KAAO content model.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shared label builder for taxonomies.
 *
 * @param string $singular Singular label.
 * @param string $plural   Plural label.
 * @return array
 */
function kaao_tax_labels( string $singular, string $plural ): array {
	return array(
		'name'              => $plural,
		'singular_name'     => $singular,
		'menu_name'         => $plural,
		'all_items'         => sprintf( /* translators: %s: plural name. */ __( 'All %s', 'kaao' ), $plural ),
		'edit_item'         => sprintf( /* translators: %s: singular name. */ __( 'Edit %s', 'kaao' ), $singular ),
		'view_item'         => sprintf( /* translators: %s: singular name. */ __( 'View %s', 'kaao' ), $singular ),
		'update_item'       => sprintf( /* translators: %s: singular name. */ __( 'Update %s', 'kaao' ), $singular ),
		'add_new_item'      => sprintf( /* translators: %s: singular name. */ __( 'Add %s', 'kaao' ), $singular ),
		'new_item_name'     => sprintf( /* translators: %s: singular name. */ __( 'New %s', 'kaao' ), $singular ),
		'search_items'      => sprintf( /* translators: %s: plural name. */ __( 'Search %s', 'kaao' ), $plural ),
		'parent_item'       => sprintf( /* translators: %s: singular name. */ __( 'Parent %s', 'kaao' ), $singular ),
		'parent_item_colon' => sprintf( /* translators: %s: singular name. */ __( 'Parent %s:', 'kaao' ), $singular ),
		'not_found'         => sprintf( /* translators: %s: lowercase plural. */ __( 'No %s found.', 'kaao' ), strtolower( $plural ) ),
		'back_to_items'     => sprintf( /* translators: %s: plural name. */ __( '← Back to %s', 'kaao' ), $plural ),
	);
}

/**
 * Register KAAO taxonomies.
 */
function kaao_register_taxonomies(): void {

	register_taxonomy(
		'kaao_member_category',
		array( KAAO_CPT_MEMBER ),
		array(
			'labels'            => kaao_tax_labels( __( 'Membership Category', 'kaao' ), __( 'Membership Categories', 'kaao' ) ),
			'description'       => __( 'Ordinary, Associate or Honorary — as defined in the KAAO constitution.', 'kaao' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'members/category',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_sector',
		array( KAAO_CPT_MEMBER ),
		array(
			'labels'            => kaao_tax_labels( __( 'Aviation Sector', 'kaao' ), __( 'Aviation Sectors', 'kaao' ) ),
			'description'       => __( 'What the organisation does — e.g. AOC holder, AMO, ATO, RPAS, insurance.', 'kaao' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'members/sector',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_location',
		array( KAAO_CPT_MEMBER ),
		array(
			'labels'            => kaao_tax_labels( __( 'Location', 'kaao' ), __( 'Locations', 'kaao' ) ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => false,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'members/location',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_event_type',
		array( KAAO_CPT_EVENT ),
		array(
			'labels'            => kaao_tax_labels( __( 'Event Type', 'kaao' ), __( 'Event Types', 'kaao' ) ),
			'description'       => __( 'AGM, Board Meeting, Industry Forum, Stakeholder Engagement, Workshop, Training, Conference, Industry Affairs Meeting, Ceremony & Celebration, Award & Recognition, Launch & Milestone, Awareness Day.', 'kaao' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'events/type',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_advocacy_theme',
		array( KAAO_CPT_ADVOCACY ),
		array(
			'labels'            => kaao_tax_labels( __( 'Advocacy Theme', 'kaao' ), __( 'Advocacy Themes', 'kaao' ) ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'advocacy/theme',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_resource_type',
		array( KAAO_CPT_RESOURCE ),
		array(
			'labels'            => kaao_tax_labels( __( 'Resource Type', 'kaao' ), __( 'Resource Types', 'kaao' ) ),
			'description'       => __( 'Reports, Publications, Position Papers, Forms, Downloads, Regulatory Resources.', 'kaao' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'resources/type',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_leader_group',
		array( KAAO_CPT_LEADER ),
		array(
			'labels'            => kaao_tax_labels( __( 'Leadership Group', 'kaao' ), __( 'Leadership Groups', 'kaao' ) ),
			'description'       => __( 'Board of Directors or Secretariat.', 'kaao' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'leadership/group',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'kaao_faq_topic',
		array( KAAO_CPT_FAQ ),
		array(
			'labels'            => kaao_tax_labels( __( 'FAQ Topic', 'kaao' ), __( 'FAQ Topics', 'kaao' ) ),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'kaao_register_taxonomies', 4 );

/**
 * Terms KAAO staff should find already present on a fresh install.
 *
 * Only structural vocabulary lives here — the constitution's three membership
 * categories, the licence classes KCAA issues, and the document types the
 * Resource Centre is organised around. No organisational facts are invented.
 *
 * @return array<string, array<string, string>> Taxonomy => slug => name.
 */
function kaao_default_terms(): array {
	return array(
		'kaao_member_category' => array(
			'ordinary'  => 'Ordinary',
			'associate' => 'Associate',
			'honorary'  => 'Honorary',
		),
		'kaao_sector'          => array(
			'aoc'                 => 'Air Operator Certificate (AOC)',
			'amo'                 => 'Approved Maintenance Organisation (AMO)',
			'ato'                 => 'Approved Training Organisation (ATO)',
			'roc'                 => 'Remote Operator Certificate (ROC)',
			'balloon-operations'  => 'Balloon Operations',
			'ground-handling'     => 'Ground Handling & Cargo',
			'aviation-services'   => 'Aviation Services & Suppliers',
			'insurance-risk'      => 'Insurance & Risk',
			'legal-advisory'      => 'Legal & Advisory',
			'tourism'             => 'Tourism & Travel',
			'aerodromes'          => 'Aerodromes & Facilities',
		),
		'kaao_event_type'      => array(
			'agm'                    => 'AGM',
			'board-meeting'          => 'Board Meeting',
			'industry-forum'         => 'Industry Forum',
			'stakeholder-engagement' => 'Stakeholder Engagement',
			'workshop'               => 'Workshop',
			'training'               => 'Training',
			'conference'             => 'Conference',
			'industry-affairs'       => 'Industry Affairs Meeting',
			'ceremony-celebration'   => 'Ceremony & Celebration',
			'award-recognition'      => 'Award & Recognition',
			'launch-milestone'       => 'Launch & Milestone',
			'awareness-day'          => 'Awareness Day',
		),
		'kaao_resource_type'   => array(
			'reports'              => 'Reports',
			'publications'         => 'Publications',
			'position-papers'      => 'Position Papers',
			'forms'                => 'Forms',
			'downloads'            => 'Downloads',
			'regulatory-resources' => 'Regulatory Resources',
		),
		'kaao_leader_group'    => array(
			'board-of-directors' => 'Board of Directors',
			'secretariat'        => 'Secretariat',
		),
		'kaao_advocacy_theme'  => array(
			'policy-regulatory'      => 'Policy & Regulatory Affairs',
			'taxation'               => 'Taxation & Fiscal Policy',
			'safety-security'        => 'Safety & Security',
			'infrastructure'         => 'Airports & Infrastructure',
			'industry-engagement'    => 'Industry Engagement',
			'sustainability'         => 'Sustainable Aviation',
		),
		'kaao_faq_topic'       => array(
			'membership' => 'Membership',
			'kaao'       => 'About KAAO',
			'events'     => 'Events',
			'general'    => 'General',
		),
	);
}

/**
 * Insert the default terms once, on theme activation.
 */
function kaao_seed_default_terms(): void {
	foreach ( kaao_default_terms() as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		foreach ( $terms as $slug => $name ) {
			if ( ! term_exists( $slug, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
		}
	}
}

/**
 * Seed terms and flush rewrites when the theme is switched on.
 */
function kaao_after_switch_theme(): void {
	kaao_register_taxonomies();
	kaao_register_post_types();
	kaao_seed_default_terms();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kaao_after_switch_theme' );
