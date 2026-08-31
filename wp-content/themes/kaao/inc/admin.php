<?php
/**
 * Admin experience.
 *
 * The target reader is a KAAO staff member who is not a developer: they should
 * be able to add a member, an event or a resource without asking anyone what a
 * field means.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * A short "how this content type works" note above each editor list.
 */
function kaao_admin_notices(): void {
	$screen = get_current_screen();
	if ( ! $screen || 'edit' !== $screen->base ) {
		return;
	}

	$notes = array(
		KAAO_CPT_MEMBER   => __( 'Add each member organisation once. The logo is the “Image”, the short description is the “Excerpt”, and the Membership Category controls where it appears in the directory. Tick “Feature on the homepage” for the logos shown on the front page.', 'kaao' ),
		KAAO_CPT_EVENT    => __( 'The Start date decides whether an event shows under Upcoming or Past — it is the one field you must fill in.', 'kaao' ),
		KAAO_CPT_ADVOCACY => __( 'Record what KAAO did, who was engaged and what resulted. Leave the Outcome blank while a matter is still open rather than describing an outcome that has not happened.', 'kaao' ),
		KAAO_CPT_LEADER   => __( 'Board of Directors and Secretariat are set by the Leadership Group. Use “Order” under Page Attributes to control who appears first.', 'kaao' ),
		KAAO_CPT_RESOURCE => __( 'Upload the document to the Media Library first, then paste its attachment ID into the File field. Set a Resource Type so it is filed correctly in the Resource Centre.', 'kaao' ),
		KAAO_CPT_FAQ      => __( 'FAQs appear grouped by Topic on the FAQs page and on the Membership pages. They have no pages of their own.', 'kaao' ),
	);

	if ( empty( $notes[ $screen->post_type ] ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info inline" style="margin:14px 0 0;border-left-color:#C38B29"><p style="max-width:80ch">%s</p></div>',
		esc_html( $notes[ $screen->post_type ] )
	);
}
add_action( 'admin_notices', 'kaao_admin_notices' );

/**
 * Useful columns on the KAAO list tables.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function kaao_member_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['kaao_logo'] = __( 'Logo', 'kaao' );
		}
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kaao_featured'] = __( 'Featured', 'kaao' );
		}
	}

	return $new;
}
add_filter( 'manage_' . KAAO_CPT_MEMBER . '_posts_columns', 'kaao_member_columns' );

/**
 * Event list columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function kaao_event_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kaao_when']  = __( 'Date', 'kaao' );
			$new['kaao_where'] = __( 'Location', 'kaao' );
		}
	}
	unset( $new['date'] );

	return $new;
}
add_filter( 'manage_' . KAAO_CPT_EVENT . '_posts_columns', 'kaao_event_columns' );

/**
 * Leadership list columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function kaao_leader_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kaao_position'] = __( 'Position', 'kaao' );
			$new['kaao_org']      = __( 'Organisation', 'kaao' );
		}
	}

	return $new;
}
add_filter( 'manage_' . KAAO_CPT_LEADER . '_posts_columns', 'kaao_leader_columns' );

/**
 * Resource list columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function kaao_resource_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kaao_year'] = __( 'Year', 'kaao' );
			$new['kaao_doc']  = __( 'File', 'kaao' );
		}
	}

	return $new;
}
add_filter( 'manage_' . KAAO_CPT_RESOURCE . '_posts_columns', 'kaao_resource_columns' );

/**
 * Vacancy list columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function kaao_vacancy_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kaao_closes'] = __( 'Closing date', 'kaao' );
			$new['kaao_status'] = __( 'Status', 'kaao' );
		}
	}

	return $new;
}
add_filter( 'manage_' . KAAO_CPT_VACANCY . '_posts_columns', 'kaao_vacancy_columns' );

/**
 * Render the custom columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function kaao_render_admin_column( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'kaao_logo':
			$thumb = get_the_post_thumbnail( $post_id, array( 48, 48 ), array( 'style' => 'object-fit:contain;width:48px;height:48px' ) );
			echo $thumb ? wp_kses_post( $thumb ) : '<span aria-hidden="true">—</span>';
			break;

		case 'kaao_featured':
			echo kaao_field( 'kaao_featured', $post_id )
				? '<span title="' . esc_attr__( 'Featured', 'kaao' ) . '" style="color:#C38B29;font-size:16px">★</span>'
				: '<span style="color:#c3c8d1">☆</span>';
			break;

		case 'kaao_when':
			$range = kaao_event_date_range( $post_id );
			echo $range ? esc_html( $range ) : '<em>' . esc_html__( 'No date set', 'kaao' ) . '</em>';
			break;

		case 'kaao_where':
			echo esc_html( kaao_field( 'kaao_event_location', $post_id ) ?: '—' );
			break;

		case 'kaao_position':
			echo esc_html( kaao_field( 'kaao_leader_position', $post_id ) ?: '—' );
			break;

		case 'kaao_org':
			echo esc_html( kaao_field( 'kaao_leader_organisation', $post_id ) ?: '—' );
			break;

		case 'kaao_closes':
			$deadline = kaao_field( 'kaao_vacancy_deadline', $post_id );
			echo $deadline
				? esc_html( wp_date( (string) get_option( 'date_format', 'j F Y' ), strtotime( $deadline ) ) )
				: '<em>' . esc_html__( 'Rolling', 'kaao' ) . '</em>';
			break;

		case 'kaao_status':
			echo kaao_vacancy_is_open( $post_id )
				? '<span style="color:#1f7a3d;font-weight:600">' . esc_html__( 'Open', 'kaao' ) . '</span>'
				: '<span style="color:#8a8f98">' . esc_html__( 'Closed', 'kaao' ) . '</span>';
			break;

		case 'kaao_year':
			echo esc_html( kaao_field( 'kaao_resource_year', $post_id ) ?: '—' );
			break;

		case 'kaao_doc':
			$file = (int) kaao_field( 'kaao_resource_file', $post_id );
			$url  = $file ? wp_get_attachment_url( $file ) : kaao_field( 'kaao_resource_link', $post_id );
			if ( $url ) {
				printf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $url ), esc_html__( 'Open', 'kaao' ) );
			} else {
				echo '<em>' . esc_html__( 'None', 'kaao' ) . '</em>';
			}
			break;
	}
}
add_action( 'manage_posts_custom_column', 'kaao_render_admin_column', 10, 2 );

/**
 * Sort the event list by event date, not publish date.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function kaao_sortable_event_columns( array $columns ): array {
	$columns['kaao_when'] = 'kaao_event_start';

	return $columns;
}
add_filter( 'manage_edit-' . KAAO_CPT_EVENT . '_sortable_columns', 'kaao_sortable_event_columns' );

/**
 * Apply that sort.
 *
 * @param WP_Query $q Query.
 */
function kaao_admin_event_order( WP_Query $q ): void {
	if ( ! is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( KAAO_CPT_EVENT !== $q->get( 'post_type' ) ) {
		return;
	}
	if ( 'kaao_event_start' === $q->get( 'orderby' ) ) {
		$q->set( 'meta_key', 'kaao_event_start' );
		$q->set( 'orderby', 'meta_value' );
	} elseif ( ! $q->get( 'orderby' ) ) {
		$q->set( 'meta_key', 'kaao_event_start' );
		$q->set( 'orderby', 'meta_value' );
		$q->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'kaao_admin_event_order' );

/**
 * A KAAO panel on the dashboard pointing staff at the common tasks.
 */
function kaao_dashboard_widget(): void {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	wp_add_dashboard_widget(
		'kaao_quick_actions',
		__( 'KAAO — add content', 'kaao' ),
		static function (): void {
			$actions = array(
				array( KAAO_CPT_MEMBER, __( 'Add Member', 'kaao' ), 'dashicons-groups' ),
				array( KAAO_CPT_EVENT, __( 'Add Event', 'kaao' ), 'dashicons-calendar-alt' ),
				array( 'post', __( 'Add News Article', 'kaao' ), 'dashicons-admin-post' ),
				array( KAAO_CPT_ADVOCACY, __( 'Add Advocacy Update', 'kaao' ), 'dashicons-megaphone' ),
				array( KAAO_CPT_RESOURCE, __( 'Add Resource', 'kaao' ), 'dashicons-media-document' ),
				array( KAAO_CPT_LEADER, __( 'Add Leadership Profile', 'kaao' ), 'dashicons-businessperson' ),
				array( KAAO_CPT_FAQ, __( 'Add FAQ', 'kaao' ), 'dashicons-editor-help' ),
				array( KAAO_CPT_VACANCY, __( 'Add Vacancy', 'kaao' ), 'dashicons-clipboard' ),
			);

			echo '<div style="display:grid;gap:8px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))">';
			foreach ( $actions as [$type, $label, $icon] ) {
				printf(
					'<a class="button button-secondary" style="display:flex;align-items:center;gap:8px;justify-content:flex-start" href="%s"><span class="dashicons %s" aria-hidden="true"></span>%s</a>',
					esc_url( admin_url( 'post-new.php?post_type=' . $type ) ),
					esc_attr( $icon ),
					esc_html( $label )
				);
			}
			echo '</div>';

			printf(
				'<p style="margin-top:14px"><a href="%s">%s</a></p>',
				esc_url( admin_url( 'customize.php?autofocus[panel]=kaao_panel' ) ),
				esc_html__( 'Edit contact details, social links and the homepage hero →', 'kaao' )
			);
		}
	);
}
add_action( 'wp_dashboard_setup', 'kaao_dashboard_widget' );

/**
 * Light styling for the KAAO meta boxes.
 */
function kaao_admin_styles(): void {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array_keys( kaao_field_schema() ), true ) ) {
		return;
	}
	echo '<style>
		.kaao-fields{display:grid;gap:4px 28px;grid-template-columns:repeat(auto-fit,minmax(280px,1fr))}
		.kaao-fields .kaao-field{margin:0 0 14px}
		.kaao-fields .kaao-field--textarea{grid-column:1/-1}
		.kaao-fields label strong{display:inline-block;margin-bottom:4px}
		.kaao-fields .description{color:#5a6472}
	</style>';
}
add_action( 'admin_head', 'kaao_admin_styles' );

/**
 * Group the KAAO post types together in the admin menu with a heading, so the
 * sidebar reads as a content model rather than a pile of items.
 */
function kaao_admin_menu_separator(): void {
	global $menu;
	if ( ! is_array( $menu ) ) {
		return;
	}
	$menu[20.5] = array( '', 'read', 'kaao-separator', '', 'wp-menu-separator' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'admin_menu', 'kaao_admin_menu_separator' );

/**
 * Remind an administrator to finish setup if the front page is not set.
 */
function kaao_setup_notice(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'KAAO: the homepage is not yet set to a static page, so the designed front page will not show.', 'kaao' ),
		esc_url( admin_url( 'options-reading.php' ) ),
		esc_html__( 'Set it in Settings → Reading', 'kaao' )
	);
}
add_action( 'admin_notices', 'kaao_setup_notice' );
