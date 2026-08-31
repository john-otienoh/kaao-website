<?php
/**
 * KAAO content model — custom post types.
 *
 * Each type here exists because KAAO staff need to add that thing repeatedly
 * and it needs its own fields and template. Anything that is genuinely just a
 * page stays a page.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * The post type keys, kept in one place so templates and queries agree.
 */
const KAAO_CPT_MEMBER   = 'kaao_member';
const KAAO_CPT_EVENT    = 'kaao_event';
const KAAO_CPT_ADVOCACY = 'kaao_advocacy';
const KAAO_CPT_LEADER   = 'kaao_leader';
const KAAO_CPT_RESOURCE = 'kaao_resource';
const KAAO_CPT_FAQ      = 'kaao_faq';
const KAAO_CPT_VACANCY  = 'kaao_vacancy';

/**
 * Build a labels array from a singular/plural pair.
 *
 * @param string $singular Singular label.
 * @param string $plural   Plural label.
 * @param string $add_new  Wording for the "Add New" action.
 * @return array
 */
function kaao_labels( string $singular, string $plural, string $add_new = '' ): array {
	$add_new = $add_new ?: sprintf( /* translators: %s: singular post type name. */ __( 'Add %s', 'kaao' ), $singular );

	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'name_admin_bar'        => $singular,
		'add_new'               => $add_new,
		'add_new_item'          => $add_new,
		'edit_item'             => sprintf( /* translators: %s: singular name. */ __( 'Edit %s', 'kaao' ), $singular ),
		'new_item'              => sprintf( /* translators: %s: singular name. */ __( 'New %s', 'kaao' ), $singular ),
		'view_item'             => sprintf( /* translators: %s: singular name. */ __( 'View %s', 'kaao' ), $singular ),
		'view_items'            => sprintf( /* translators: %s: plural name. */ __( 'View %s', 'kaao' ), $plural ),
		'search_items'          => sprintf( /* translators: %s: plural name. */ __( 'Search %s', 'kaao' ), $plural ),
		'not_found'             => sprintf( /* translators: %s: lowercase plural name. */ __( 'No %s yet.', 'kaao' ), strtolower( $plural ) ),
		'not_found_in_trash'    => sprintf( /* translators: %s: lowercase plural name. */ __( 'No %s in the bin.', 'kaao' ), strtolower( $plural ) ),
		'all_items'             => sprintf( /* translators: %s: plural name. */ __( 'All %s', 'kaao' ), $plural ),
		'archives'              => sprintf( /* translators: %s: singular name. */ __( '%s archive', 'kaao' ), $singular ),
		'featured_image'        => __( 'Image', 'kaao' ),
		'set_featured_image'    => __( 'Set image', 'kaao' ),
		'remove_featured_image' => __( 'Remove image', 'kaao' ),
		'use_featured_image'    => __( 'Use as image', 'kaao' ),
		'item_published'        => sprintf( /* translators: %s: singular name. */ __( '%s published.', 'kaao' ), $singular ),
		'item_updated'          => sprintf( /* translators: %s: singular name. */ __( '%s updated.', 'kaao' ), $singular ),
	);
}

/**
 * Register the KAAO custom post types.
 */
function kaao_register_post_types(): void {

	/* ------------------------------------------------------------ Members */
	register_post_type(
		KAAO_CPT_MEMBER,
		array(
			'labels'             => kaao_labels( __( 'Member', 'kaao' ), __( 'Members', 'kaao' ), __( 'Add Member', 'kaao' ) ),
			'description'        => __( 'Organisations in KAAO membership.', 'kaao' ),
			'public'             => true,
			'has_archive'        => 'members',
			'menu_icon'          => 'dashicons-groups',
			'menu_position'      => 21,
			'rewrite'            => array(
				'slug'       => 'members',
				'with_front' => false,
			),
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest'       => true,
			'rest_base'          => 'members',
			'taxonomies'         => array( 'kaao_member_category', 'kaao_sector', 'kaao_location' ),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
		)
	);

	/* ------------------------------------------------------------- Events */
	register_post_type(
		KAAO_CPT_EVENT,
		array(
			'labels'          => kaao_labels( __( 'Event', 'kaao' ), __( 'Events', 'kaao' ), __( 'Add Event', 'kaao' ) ),
			'description'     => __( 'KAAO events, engagements and industry forums.', 'kaao' ),
			'public'          => true,
			'has_archive'     => 'events',
			'menu_icon'       => 'dashicons-calendar-alt',
			'menu_position'   => 22,
			'rewrite'         => array(
				'slug'       => 'events',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'show_in_rest'    => true,
			'rest_base'       => 'events',
			'taxonomies'      => array( 'kaao_event_type' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	/* ----------------------------------------------------------- Advocacy */
	register_post_type(
		KAAO_CPT_ADVOCACY,
		array(
			'labels'          => kaao_labels( __( 'Advocacy Update', 'kaao' ), __( 'Advocacy', 'kaao' ), __( 'Add Advocacy Update', 'kaao' ) ),
			'description'     => __( 'Policy, regulatory and industry representation work.', 'kaao' ),
			'public'          => true,
			'has_archive'     => 'advocacy',
			'menu_icon'       => 'dashicons-megaphone',
			'menu_position'   => 23,
			'rewrite'         => array(
				'slug'       => 'advocacy',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'show_in_rest'    => true,
			'rest_base'       => 'advocacy',
			'taxonomies'      => array( 'kaao_advocacy_theme' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	/* --------------------------------------------------------- Leadership */
	register_post_type(
		KAAO_CPT_LEADER,
		array(
			'labels'          => kaao_labels( __( 'Leadership Profile', 'kaao' ), __( 'Leadership', 'kaao' ), __( 'Add Leadership Profile', 'kaao' ) ),
			'description'     => __( 'Board of Directors and Secretariat profiles.', 'kaao' ),
			'public'          => true,
			'has_archive'     => 'leadership',
			'menu_icon'       => 'dashicons-businessperson',
			'menu_position'   => 24,
			'rewrite'         => array(
				'slug'       => 'leadership',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest'    => true,
			'rest_base'       => 'leadership',
			'taxonomies'      => array( 'kaao_leader_group' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	/* ---------------------------------------------------------- Resources */
	register_post_type(
		KAAO_CPT_RESOURCE,
		array(
			'labels'          => kaao_labels( __( 'Resource', 'kaao' ), __( 'Resources', 'kaao' ), __( 'Add Resource', 'kaao' ) ),
			'description'     => __( 'Reports, publications, position papers, forms and downloads.', 'kaao' ),
			'public'          => true,
			'has_archive'     => 'resources',
			'menu_icon'       => 'dashicons-media-document',
			'menu_position'   => 25,
			'rewrite'         => array(
				'slug'       => 'resources',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'show_in_rest'    => true,
			'rest_base'       => 'resources',
			'taxonomies'      => array( 'kaao_resource_type' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	/* ---------------------------------------------------------- Vacancies */
	// No archive of its own: /careers/ is the listing page, so a vacancy is
	// addressable at /careers/<slug>/ and the page keeps its legacy URL.
	register_post_type(
		KAAO_CPT_VACANCY,
		array(
			'labels'          => kaao_labels( __( 'Vacancy', 'kaao' ), __( 'Vacancies', 'kaao' ), __( 'Add Vacancy', 'kaao' ) ),
			'description'     => __( 'Positions open at the KAAO Secretariat.', 'kaao' ),
			'public'          => true,
			'has_archive'     => false,
			'menu_icon'       => 'dashicons-clipboard',
			'menu_position'   => 27,
			'rewrite'         => array(
				'slug'       => 'careers',
				'with_front' => false,
			),
			'supports'        => array( 'title', 'editor', 'excerpt', 'revisions' ),
			'show_in_rest'    => true,
			'rest_base'       => 'vacancies',
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	/* --------------------------------------------------------------- FAQs */
	// Not individually addressable: FAQs only ever appear grouped on /faqs/
	// and in the membership pages, so a single-item URL would be a thin page.
	register_post_type(
		KAAO_CPT_FAQ,
		array(
			'labels'              => kaao_labels( __( 'FAQ', 'kaao' ), __( 'FAQs', 'kaao' ), __( 'Add FAQ', 'kaao' ) ),
			'description'         => __( 'Questions shown in the FAQ accordions.', 'kaao' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-editor-help',
			'menu_position'       => 26,
			'supports'            => array( 'title', 'editor', 'page-attributes' ),
			'show_in_rest'        => true,
			'rest_base'           => 'faqs',
			'taxonomies'          => array( 'kaao_faq_topic' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'kaao_register_post_types', 5 );

/**
 * Placeholder prompts in the title field, so an empty screen tells staff what
 * belongs there.
 *
 * @param string  $title Default text.
 * @param WP_Post $post  Current post.
 * @return string
 */
function kaao_title_placeholder( $title, $post ) {
	return match ( $post->post_type ) {
		KAAO_CPT_MEMBER   => __( 'Organisation name', 'kaao' ),
		KAAO_CPT_EVENT    => __( 'Event title', 'kaao' ),
		KAAO_CPT_ADVOCACY => __( 'What was the issue?', 'kaao' ),
		KAAO_CPT_LEADER   => __( 'Full name', 'kaao' ),
		KAAO_CPT_RESOURCE => __( 'Document title', 'kaao' ),
		KAAO_CPT_VACANCY  => __( 'Position title', 'kaao' ),
		KAAO_CPT_FAQ      => __( 'The question, as a visitor would ask it', 'kaao' ),
		default           => $title,
	};
}
add_filter( 'enter_title_here', 'kaao_title_placeholder', 10, 2 );
