<?php
/**
 * Structured fields for the KAAO content model.
 *
 * One declarative schema drives registration, the meta boxes, sanitising and
 * saving. Adding a field means adding one array entry — never a new form.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field schema, grouped into meta boxes per post type.
 *
 * Supported types: text, textarea, url, email, tel, date, time, number,
 * select, checkbox, media, file.
 *
 * @return array
 */
function kaao_field_schema(): array {
	static $schema = null;
	if ( null !== $schema ) {
		return $schema;
	}

	$schema = array(
		KAAO_CPT_MEMBER   => array(
			'kaao_member_details' => array(
				'title'  => __( 'Member details', 'kaao' ),
				'fields' => array(
					'kaao_member_website'  => array(
						'label' => __( 'Website', 'kaao' ),
						'type'  => 'url',
						'hint'  => __( 'Include https://', 'kaao' ),
					),
					'kaao_member_email'    => array(
						'label' => __( 'Email', 'kaao' ),
						'type'  => 'email',
					),
					'kaao_member_phone'    => array(
						'label' => __( 'Phone', 'kaao' ),
						'type'  => 'tel',
					),
					'kaao_member_location' => array(
						'label' => __( 'Location', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'For example: Wilson Airport, Nairobi.', 'kaao' ),
					),
					'kaao_member_services' => array(
						'label' => __( 'Services / activities', 'kaao' ),
						'type'  => 'textarea',
						'hint'  => __( 'One per line. Shown as a list on the profile.', 'kaao' ),
					),
					'kaao_member_fleet'    => array(
						'label' => __( 'Fleet / aircraft types', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_member_routes'   => array(
						'label' => __( 'Routes / operating area', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_member_evidence' => array(
						'label' => __( 'Operator / verification evidence', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_member_sources'  => array(
						'label' => __( 'Source URLs', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_member_address'  => array(
						'label' => __( 'Head office address', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_member_since'    => array(
						'label' => __( 'Member since', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'Year only. Leave blank if unconfirmed.', 'kaao' ),
					),
					'kaao_featured'        => array(
						'label' => __( 'Feature on the homepage', 'kaao' ),
						'type'  => 'checkbox',
					),
				),
			),
		),

		KAAO_CPT_EVENT    => array(
			'kaao_event_details' => array(
				'title'  => __( 'Event details', 'kaao' ),
				'fields' => array(
					'kaao_event_start'    => array(
						'label'    => __( 'Start date', 'kaao' ),
						'type'     => 'date',
						'required' => true,
						'hint'     => __( 'Drives the Upcoming / Past split on /events/.', 'kaao' ),
					),
					'kaao_event_end'      => array(
						'label' => __( 'End date', 'kaao' ),
						'type'  => 'date',
						'hint'  => __( 'Leave blank for single-day events.', 'kaao' ),
					),
					'kaao_event_time'     => array(
						'label' => __( 'Time', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'For example: 09:00 – 16:00 EAT.', 'kaao' ),
					),
					'kaao_event_location' => array(
						'label' => __( 'Location', 'kaao' ),
						'type'  => 'text',
					),
					'kaao_event_register' => array(
						'label' => __( 'Registration link', 'kaao' ),
						'type'  => 'url',
					),
					'kaao_event_host'     => array(
						'label' => __( 'Hosted by', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'Leave blank when KAAO is the host.', 'kaao' ),
					),
					'kaao_event_document' => array(
						'label' => __( 'Programme / document', 'kaao' ),
						'type'  => 'file',
					),
					'kaao_featured'       => array(
						'label' => __( 'Feature on the homepage', 'kaao' ),
						'type'  => 'checkbox',
					),
				),
			),
		),

		KAAO_CPT_ADVOCACY => array(
			'kaao_advocacy_details' => array(
				'title'  => __( 'Advocacy record', 'kaao' ),
				'fields' => array(
					'kaao_advocacy_issue'        => array(
						'label' => __( 'Issue', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'The policy or regulatory matter in one line.', 'kaao' ),
					),
					'kaao_advocacy_position'     => array(
						'label' => __( 'KAAO position', 'kaao' ),
						'type'  => 'textarea',
					),
					'kaao_advocacy_stakeholders' => array(
						'label' => __( 'Stakeholders engaged', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'Comma separated. For example: KCAA, KAA, National Treasury.', 'kaao' ),
					),
					'kaao_advocacy_outcome'      => array(
						'label' => __( 'Outcome', 'kaao' ),
						'type'  => 'textarea',
						'hint'  => __( 'Only record outcomes that are documented. Leave blank if ongoing.', 'kaao' ),
					),
					'kaao_advocacy_status'       => array(
						'label'   => __( 'Status', 'kaao' ),
						'type'    => 'select',
						'options' => array(
							''            => __( '— Not stated —', 'kaao' ),
							'ongoing'     => __( 'Ongoing', 'kaao' ),
							'concluded'   => __( 'Concluded', 'kaao' ),
							'monitoring'  => __( 'Monitoring', 'kaao' ),
						),
					),
					'kaao_advocacy_date'         => array(
						'label' => __( 'Date of engagement', 'kaao' ),
						'type'  => 'date',
					),
					'kaao_advocacy_document'     => array(
						'label' => __( 'Supporting document', 'kaao' ),
						'type'  => 'file',
					),
					'kaao_featured'              => array(
						'label' => __( 'Feature on the homepage', 'kaao' ),
						'type'  => 'checkbox',
					),
				),
			),
		),

		KAAO_CPT_LEADER   => array(
			'kaao_leader_details' => array(
				'title'  => __( 'Profile details', 'kaao' ),
				'fields' => array(
					'kaao_leader_position'     => array(
						'label'    => __( 'Position', 'kaao' ),
						'type'     => 'text',
						'required' => true,
						'hint'     => __( 'For example: Chairman, Director, Chief Executive Officer.', 'kaao' ),
					),
					'kaao_leader_organisation' => array(
						'label' => __( 'Organisation', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'The member organisation this person represents, if applicable.', 'kaao' ),
					),
					'kaao_leader_linkedin'     => array(
						'label' => __( 'LinkedIn', 'kaao' ),
						'type'  => 'url',
					),
				),
			),
		),

		KAAO_CPT_RESOURCE => array(
			'kaao_resource_details' => array(
				'title'  => __( 'Document', 'kaao' ),
				'fields' => array(
					'kaao_resource_file' => array(
						'label' => __( 'File', 'kaao' ),
						'type'  => 'file',
						'hint'  => __( 'Upload the PDF or document visitors will download.', 'kaao' ),
					),
					'kaao_resource_link' => array(
						'label' => __( 'External link', 'kaao' ),
						'type'  => 'url',
						'hint'  => __( 'Use instead of a file when the document lives elsewhere.', 'kaao' ),
					),
					'kaao_resource_year' => array(
						'label' => __( 'Year', 'kaao' ),
						'type'  => 'number',
						'attrs' => array(
							'min'  => '1967',
							'max'  => '2100',
							'step' => '1',
						),
					),
					'kaao_featured'      => array(
						'label' => __( 'Feature in the Resource Centre', 'kaao' ),
						'type'  => 'checkbox',
					),
				),
			),
		),

		KAAO_CPT_VACANCY  => array(
			'kaao_vacancy_details' => array(
				'title'  => __( 'Vacancy details', 'kaao' ),
				'fields' => array(
					'kaao_vacancy_location' => array(
						'label' => __( 'Location', 'kaao' ),
						'type'  => 'text',
						'hint'  => __( 'For example: KAAO Secretariat, Wilson Airport, Nairobi.', 'kaao' ),
					),
					'kaao_vacancy_type'     => array(
						'label'   => __( 'Engagement type', 'kaao' ),
						'type'    => 'select',
						'options' => array(
							''            => __( '— Not stated —', 'kaao' ),
							'full-time'   => __( 'Full time', 'kaao' ),
							'part-time'   => __( 'Part time', 'kaao' ),
							'fixed-term'  => __( 'Fixed term contract', 'kaao' ),
							'internship'  => __( 'Internship', 'kaao' ),
							'consultancy' => __( 'Consultancy', 'kaao' ),
							'volunteer'   => __( 'Volunteer', 'kaao' ),
						),
					),
					'kaao_vacancy_deadline' => array(
						'label' => __( 'Closing date', 'kaao' ),
						'type'  => 'date',
						'hint'  => __( 'Drives the Open / Closed split on /careers/. Leave blank for a rolling advertisement.', 'kaao' ),
					),
					'kaao_vacancy_email'    => array(
						'label' => __( 'Applications to (email)', 'kaao' ),
						'type'  => 'email',
						'hint'  => __( 'Leave blank to use the Secretariat address.', 'kaao' ),
					),
					'kaao_vacancy_link'     => array(
						'label' => __( 'Application link', 'kaao' ),
						'type'  => 'url',
						'hint'  => __( 'Use when applications are made through an external portal.', 'kaao' ),
					),
					'kaao_vacancy_document' => array(
						'label' => __( 'Job description', 'kaao' ),
						'type'  => 'file',
						'hint'  => __( 'Upload the full advertisement or terms of reference.', 'kaao' ),
					),
				),
			),
		),
	);

	return $schema;
}

/**
 * Flatten the schema to field key => definition for one post type.
 *
 * @param string $post_type Post type.
 * @return array
 */
function kaao_fields_for( string $post_type ): array {
	$out = array();
	foreach ( kaao_field_schema()[ $post_type ] ?? array() as $box ) {
		foreach ( $box['fields'] as $key => $def ) {
			$out[ $key ] = $def;
		}
	}

	return $out;
}

/**
 * Sanitise a value according to its declared field type.
 *
 * @param mixed  $value Raw value.
 * @param array  $def   Field definition.
 * @return mixed
 */
function kaao_sanitize_field( $value, array $def ) {
	return match ( $def['type'] ?? 'text' ) {
		'url'      => esc_url_raw( (string) $value ),
		'email'    => sanitize_email( (string) $value ),
		'tel'      => preg_replace( '/[^0-9+()\-.\s]/', '', (string) $value ),
		'textarea' => sanitize_textarea_field( (string) $value ),
		'number'   => ( '' === $value ? '' : (string) (int) $value ),
		'date'     => ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', (string) $value ) ? (string) $value : '' ),
		'checkbox' => ( $value ? '1' : '' ),
		'media',
		'file'     => ( (int) $value > 0 ? (string) (int) $value : '' ),
		'select'   => ( array_key_exists( (string) $value, $def['options'] ?? array() ) ? (string) $value : '' ),
		default    => sanitize_text_field( (string) $value ),
	};
}

/**
 * Register every schema field with WordPress so REST, revisions and
 * capability checks all behave.
 */
function kaao_register_meta(): void {
	foreach ( kaao_field_schema() as $post_type => $boxes ) {
		foreach ( $boxes as $box ) {
			foreach ( $box['fields'] as $key => $def ) {
				register_post_meta(
					$post_type,
					$key,
					array(
						'type'              => 'string',
						'single'            => true,
						'default'           => '',
						'show_in_rest'      => true,
						'description'       => (string) ( $def['label'] ?? '' ),
						'sanitize_callback' => static fn( $value ) => kaao_sanitize_field( $value, $def ),
						'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
					)
				);
			}
		}
	}
}
add_action( 'init', 'kaao_register_meta', 6 );

/**
 * Add the meta boxes described by the schema.
 */
function kaao_add_meta_boxes(): void {
	foreach ( kaao_field_schema() as $post_type => $boxes ) {
		foreach ( $boxes as $box_id => $box ) {
			add_meta_box(
				$box_id,
				$box['title'],
				'kaao_render_meta_box',
				$post_type,
				'normal',
				'high',
				array( 'fields' => $box['fields'] )
			);
		}
	}
}
add_action( 'add_meta_boxes', 'kaao_add_meta_boxes' );

/**
 * Render one meta box.
 *
 * @param WP_Post $post Current post.
 * @param array   $args Box args carrying the field definitions.
 */
function kaao_render_meta_box( WP_Post $post, array $args ): void {
	wp_nonce_field( 'kaao_save_meta_' . $post->ID, 'kaao_meta_nonce' );
	$fields = $args['args']['fields'];

	echo '<div class="kaao-fields">';
	foreach ( $fields as $key => $def ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = esc_attr( $key );
		$type  = $def['type'] ?? 'text';

		echo '<p class="kaao-field kaao-field--' . esc_attr( $type ) . '">';

		if ( 'checkbox' === $type ) {
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s> %3$s</label>',
				$id,
				checked( $value, '1', false ),
				esc_html( $def['label'] )
			);
		} else {
			printf(
				'<label for="%s"><strong>%s</strong>%s</label><br>',
				$id,
				esc_html( $def['label'] ),
				! empty( $def['required'] ) ? ' <span aria-hidden="true" style="color:#9A6D1F">*</span>' : ''
			);

			switch ( $type ) {
				case 'textarea':
					printf(
						'<textarea id="%s" name="%s" rows="4" class="large-text">%s</textarea>',
						$id,
						$id,
						esc_textarea( $value )
					);
					break;

				case 'select':
					printf( '<select id="%s" name="%s">', $id, $id );
					foreach ( (array) ( $def['options'] ?? array() ) as $opt_value => $opt_label ) {
						printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( (string) $opt_value ),
							selected( $value, (string) $opt_value, false ),
							esc_html( (string) $opt_label )
						);
					}
					echo '</select>';
					break;

				case 'media':
				case 'file':
					$url = $value ? wp_get_attachment_url( (int) $value ) : '';
					printf(
						'<input type="number" id="%1$s" name="%1$s" value="%2$s" class="small-text" min="0" step="1"> ',
						$id,
						esc_attr( $value )
					);
					printf(
						'<span class="description">%s</span>',
						$url
							? sprintf(
								/* translators: %s: file URL. */
								wp_kses_post( __( 'Attached: <a href="%s" target="_blank" rel="noopener">view file</a>', 'kaao' ) ),
								esc_url( $url )
							)
							: esc_html__( 'Paste the Media Library attachment ID.', 'kaao' )
					);
					break;

				default:
					$attrs = '';
					foreach ( (array) ( $def['attrs'] ?? array() ) as $a_key => $a_val ) {
						$attrs .= sprintf( ' %s="%s"', esc_attr( $a_key ), esc_attr( (string) $a_val ) );
					}
					printf(
						'<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" class="regular-text"%4$s>',
						esc_attr( $type ),
						$id,
						esc_attr( $value ),
						$attrs // phpcs:ignore WordPress.Security.EscapeOutput -- built from esc_attr above.
					);
			}
		}

		if ( ! empty( $def['hint'] ) ) {
			printf( '<br><span class="description">%s</span>', esc_html( $def['hint'] ) );
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Persist meta box values.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function kaao_save_meta( int $post_id, WP_Post $post ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	$fields = kaao_fields_for( $post->post_type );
	if ( ! $fields ) {
		return;
	}
	$nonce = isset( $_POST['kaao_meta_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kaao_meta_nonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'kaao_save_meta_' . $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( $fields as $key => $def ) {
		$raw   = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised on the next line.
		$clean = kaao_sanitize_field( $raw, $def );

		if ( '' === $clean ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $clean );
		}
	}
}
add_action( 'save_post', 'kaao_save_meta', 10, 2 );

/**
 * Read a KAAO field with a safe default.
 *
 * @param string   $key     Meta key.
 * @param int|null $post_id Post ID, defaults to current.
 * @return string
 */
function kaao_field( string $key, ?int $post_id = null ): string {
	return (string) get_post_meta( $post_id ?: get_the_ID(), $key, true );
}
