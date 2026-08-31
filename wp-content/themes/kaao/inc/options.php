<?php
/**
 * Organisation details and hero configuration.
 *
 * Everything an editor might reasonably need to change — contact details,
 * social channels, the hero mode — is a Customizer setting, never a constant
 * buried in a template. Defaults are the verified values published on
 * kaao.co.ke at the time of the content audit.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Verified organisation facts, overridable in the Customizer.
 *
 * Source: https://kaao.co.ke/contact-us/ and https://kaao.co.ke/about-us/
 *
 * @return array
 */
function kaao_org(): array {
	static $org = null;
	if ( null !== $org ) {
		return $org;
	}

	$defaults = array(
		'legal_name'  => 'Kenya Association of Air Operators',
		'short_name'  => 'KAAO',
		'founded'     => '1967-05-05',
		'address'     => 'Aero Club of East Africa, Wilson Airport, Nairobi',
		'po_box'      => 'P.O. Box 27592-00506, Nairobi',
		'locality'    => 'Nairobi',
		'country'     => 'KE',
		'phone'       => '+254 742 667 856',
		'email'       => 'admin@kaao.co.ke',
		'description' => 'KAAO is a registered national umbrella body mandated to promote, foster, enhance and protect the interests of those engaged in the aviation industry and allied businesses in Kenya.',
		'facebook'    => 'https://www.facebook.com/people/Kenya-Association-of-Air-Operators/100086069874635/',
		'twitter'     => 'https://x.com/kenyaoperators',
		'instagram'   => 'https://www.instagram.com/kenyaairoperators',
		'linkedin'    => 'https://www.linkedin.com/in/kenya-association-of-air-operators-kaao/',
	);

	$org = array();
	foreach ( $defaults as $key => $default ) {
		$org[ $key ] = (string) get_theme_mod( 'kaao_' . $key, $default );
	}

	return $org;
}

/**
 * One organisation value.
 *
 * @param string $key Key from kaao_org().
 * @return string
 */
function kaao_org_get( string $key ): string {
	return kaao_org()[ $key ] ?? '';
}

/**
 * Years since founding, computed rather than hard-coded so the homepage stat
 * never goes stale.
 *
 * @return int
 */
function kaao_years_active(): int {
	$founded = kaao_org_get( 'founded' );
	$ts      = $founded ? strtotime( $founded ) : false;
	if ( ! $ts ) {
		return 0;
	}

	return max( 0, (int) ( ( time() - $ts ) / YEAR_IN_SECONDS ) );
}

/**
 * Hero configuration for the homepage.
 *
 * Mode A (video), Mode B (static image) and Mode C (slider) are all supported
 * so the Board demonstration and the production build can differ without a
 * code change.
 *
 * @return array
 */
function kaao_hero_config(): array {
	$mode = (string) get_theme_mod( 'kaao_hero_mode', 'image' );
	if ( ! in_array( $mode, array( 'video', 'image', 'slider' ), true ) ) {
		$mode = 'image';
	}

	$poster_id = (int) get_theme_mod( 'kaao_hero_poster', 0 );
	if ( ! $poster_id ) {
		$poster_id = (int) kaao_attachment_id_by_slug( 'kaao-hero-business-jet-at-sunset' );
	}

	$slides = array_values(
		array_filter(
			array_map(
				'absint',
				array(
					get_theme_mod( 'kaao_hero_slide_1', $poster_id ),
					get_theme_mod( 'kaao_hero_slide_2', kaao_attachment_id_by_slug( 'kaao-hero-wilson-airport-control-tower' ) ),
					get_theme_mod( 'kaao_hero_slide_3', kaao_attachment_id_by_slug( 'kaao-hero-kaao-delegates-group-photo' ) ),
				)
			)
		)
	);

	return array(
		'mode'      => $mode,
		'poster_id' => $poster_id,
		'video_mp4' => (string) get_theme_mod( 'kaao_hero_video_mp4', '' ),
		'video_web' => (string) get_theme_mod( 'kaao_hero_video_webm', '' ),
		'slides'    => $slides,
		'headline'  => (string) get_theme_mod( 'kaao_hero_headline', __( 'The unified voice of Kenya’s aviation industry', 'kaao' ) ),
		'subhead'   => (string) get_theme_mod( 'kaao_hero_subhead', __( 'Representing, connecting and advancing the interests of Kenya’s aviation industry.', 'kaao' ) ),
	);
}

/**
 * Resolve an attachment by its slug, so seeded imagery can be referenced by a
 * stable, human-readable name instead of a numeric ID.
 *
 * @param string $slug Attachment post slug.
 * @return int Attachment ID, or 0.
 */
function kaao_attachment_id_by_slug( string $slug ): int {
	$cache_key = 'kaao_att_' . md5( $slug );
	$cached    = wp_cache_get( $cache_key, 'kaao' );
	if ( false !== $cached ) {
		return (int) $cached;
	}

	$found = get_posts(
		array(
			'name'                   => $slug,
			'post_type'              => 'attachment',
			'post_status'            => 'inherit',
			'numberposts'            => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	$id = $found ? (int) $found[0] : 0;
	wp_cache_set( $cache_key, $id, 'kaao', HOUR_IN_SECONDS );

	return $id;
}

/**
 * Register Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function kaao_customize_register( WP_Customize_Manager $wp_customize ): void {

	/* ---------------------------------------------------- Organisation ---- */
	$wp_customize->add_panel(
		'kaao_panel',
		array(
			'title'       => __( 'KAAO settings', 'kaao' ),
			'description' => __( 'Organisation details and homepage hero. These feed the header, footer, contact page and structured data.', 'kaao' ),
			'priority'    => 20,
		)
	);

	$wp_customize->add_section(
		'kaao_contact',
		array(
			'title' => __( 'Contact details', 'kaao' ),
			'panel' => 'kaao_panel',
		)
	);

	$contact_fields = array(
		'address'  => array( __( 'Physical address', 'kaao' ), 'text' ),
		'po_box'   => array( __( 'Postal address', 'kaao' ), 'text' ),
		'phone'    => array( __( 'Phone', 'kaao' ), 'text' ),
		'email'    => array( __( 'Email', 'kaao' ), 'email' ),
	);
	foreach ( $contact_fields as $key => [$label, $type] ) {
		$wp_customize->add_setting(
			'kaao_' . $key,
			array(
				'default'           => kaao_org()[ $key ],
				'sanitize_callback' => 'email' === $type ? 'sanitize_email' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'kaao_' . $key,
			array(
				'label'   => $label,
				'section' => 'kaao_contact',
				'type'    => 'email' === $type ? 'email' : 'text',
			)
		);
	}

	/* --------------------------------------------------------- Social ----- */
	$wp_customize->add_section(
		'kaao_social',
		array(
			'title' => __( 'Social channels', 'kaao' ),
			'panel' => 'kaao_panel',
		)
	);
	foreach (
		array(
			'facebook'  => __( 'Facebook URL', 'kaao' ),
			'twitter'   => __( 'X (Twitter) URL', 'kaao' ),
			'instagram' => __( 'Instagram URL', 'kaao' ),
			'linkedin'  => __( 'LinkedIn URL', 'kaao' ),
		) as $key => $label
	) {
		$wp_customize->add_setting(
			'kaao_' . $key,
			array(
				'default'           => kaao_org()[ $key ],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'kaao_' . $key,
			array(
				'label'   => $label,
				'section' => 'kaao_social',
				'type'    => 'url',
			)
		);
	}

	/* ----------------------------------------------------------- Hero ----- */
	$wp_customize->add_section(
		'kaao_hero',
		array(
			'title'       => __( 'Homepage hero', 'kaao' ),
			'panel'       => 'kaao_panel',
			'description' => __( 'Switch between a video background, a single static image or a slider. The static image is always rendered first and stands in on mobile, on slow connections and when reduced motion is requested.', 'kaao' ),
		)
	);

	$wp_customize->add_setting(
		'kaao_hero_mode',
		array(
			'default'           => 'image',
			'sanitize_callback' => static fn( $v ) => in_array( $v, array( 'video', 'image', 'slider' ), true ) ? $v : 'image',
		)
	);
	$wp_customize->add_control(
		'kaao_hero_mode',
		array(
			'label'   => __( 'Hero mode', 'kaao' ),
			'section' => 'kaao_hero',
			'type'    => 'select',
			'choices' => array(
				'image'  => __( 'Static image (fastest)', 'kaao' ),
				'slider' => __( 'Image slider', 'kaao' ),
				'video'  => __( 'Video background', 'kaao' ),
			),
		)
	);

	$wp_customize->add_setting(
		'kaao_hero_poster',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'kaao_hero_poster',
			array(
				'label'       => __( 'Hero image / video poster', 'kaao' ),
				'description' => __( 'Also used as the preloaded LCP image.', 'kaao' ),
				'section'     => 'kaao_hero',
				'mime_type'   => 'image',
			)
		)
	);

	foreach ( array( 1, 2, 3 ) as $n ) {
		$wp_customize->add_setting(
			'kaao_hero_slide_' . $n,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'kaao_hero_slide_' . $n,
				array(
					/* translators: %d: slide number. */
					'label'     => sprintf( __( 'Slider image %d', 'kaao' ), $n ),
					'section'   => 'kaao_hero',
					'mime_type' => 'image',
				)
			)
		);
	}

	foreach (
		array(
			'kaao_hero_video_mp4'  => __( 'Video URL (MP4/H.264)', 'kaao' ),
			'kaao_hero_video_webm' => __( 'Video URL (WebM)', 'kaao' ),
		) as $key => $label
	) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $label,
				'section'     => 'kaao_hero',
				'type'        => 'url',
				'description' => __( 'Keep under 3 MB and 15 seconds. Never loaded on mobile.', 'kaao' ),
			)
		);
	}

	$wp_customize->add_setting(
		'kaao_hero_headline',
		array(
			'default'           => __( 'The unified voice of Kenya’s aviation industry', 'kaao' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'kaao_hero_headline',
		array(
			'label'   => __( 'Headline', 'kaao' ),
			'section' => 'kaao_hero',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'kaao_hero_subhead',
		array(
			'default'           => __( 'Representing, connecting and advancing the interests of Kenya’s aviation industry.', 'kaao' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'kaao_hero_subhead',
		array(
			'label'   => __( 'Supporting statement', 'kaao' ),
			'section' => 'kaao_hero',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'kaao_customize_register' );
