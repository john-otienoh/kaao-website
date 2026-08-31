<?php
/**
 * Reusable output helpers.
 *
 * Anything that would otherwise be copied into two templates lives here.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon set.
 *
 * Inline beats an icon font on every axis that matters here: no extra request,
 * no FOIT, correct colour inheritance and no fake text for screen readers.
 *
 * @param string $name  Icon name.
 * @param array  $attrs Extra attributes (class, width, height).
 * @return string SVG markup, already escaped.
 */
function kaao_icon( string $name, array $attrs = array() ): string {
	$paths = array(
		'menu'        => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'close'       => '<path d="M6 6l12 12M18 6L6 18"/>',
		'search'      => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'chevron'     => '<path d="M6 9l6 6 6-6"/>',
		'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'pin'         => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/>',
		'phone'       => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
		'mail'        => '<rect x="2.5" y="4.5" width="19" height="15" rx="2"/><path d="M3 6l9 6.5L21 6"/>',
		'calendar'    => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
		'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.5l3.5 2"/>',
		'download'    => '<path d="M12 3v12M7.5 10.5 12 15l4.5-4.5"/><path d="M4 20h16"/>',
		'external'    => '<path d="M14 4h6v6"/><path d="M20 4 10 14"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
		'globe'       => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18z"/>',
		'shield'      => '<path d="M12 3l7.5 3v6c0 4.6-3.2 8.3-7.5 9.5C7.7 20.3 4.5 16.6 4.5 12V6z"/><path d="m9 12 2.2 2.2L15.5 10"/>',
		'megaphone'   => '<path d="M3 11v2a1 1 0 0 0 1 1h2l6 4V6L6 10H4a1 1 0 0 0-1 1z"/><path d="M16.5 8.5a5 5 0 0 1 0 7"/><path d="M19.5 6a9 9 0 0 1 0 12"/>',
		'handshake'   => '<path d="m10 13 2 2 2-2"/><path d="M3 9.5 7 6l4 2.5L15 6l6 3.5"/><path d="M21 9.5v5l-5 4-4-3-4 3-5-4v-5"/>',
		'users'       => '<circle cx="9" cy="8" r="3.2"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 5.2a3.2 3.2 0 0 1 0 5.6"/><path d="M18 20a6.4 6.4 0 0 0-2.6-5.2"/>',
		'book'        => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v14H6.5A2.5 2.5 0 0 0 4 19.5z"/><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v4H6.5A2.5 2.5 0 0 1 4 19.5z"/>',
		'chart'       => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
		'plane'       => '<path d="M10.2 3.3a1.6 1.6 0 0 1 3.1 0L15 11l6 3v2l-6.4-1.6-1 4.4 2.4 1.7V22l-3.5-1-3.5 1v-1.5l2.4-1.7-1-4.4L3 16v-2l6-3z"/>',
		'facebook'    => '<path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5h1.65V4.6A22 22 0 0 0 14.3 4.5c-2.4 0-4 1.45-4 4.1v2.3H7.6V14h2.7v8z"/>',
		'x-twitter'   => '<path d="M17.5 3h3.1l-6.8 7.8L21.8 21h-6.2l-4.9-6.4L5.1 21H2l7.3-8.3L2.5 3h6.4l4.4 5.8zm-1.1 16.1h1.7L7.7 4.8H5.9z"/>',
		'instagram'   => '<path d="M12 2.2c3.2 0 3.6 0 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9s.68.82.9 1.38c.16.42.36 1.06.41 2.23.06 1.25.07 1.63.07 4.81s0 3.56-.07 4.81c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38s-.82.68-1.38.9c-.42.16-1.06.36-2.23.41-1.25.06-1.63.07-4.85.07s-3.6 0-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9s-.68-.82-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.56 2.2 15.18 2.2 12s0-3.56.07-4.81c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38s.82-.68 1.38-.9c.42-.16 1.06-.36 2.23-.41C8.44 2.21 8.82 2.2 12 2.2zm0 1.8c-3.13 0-3.5.01-4.73.07-.9.04-1.39.19-1.71.32-.43.17-.74.37-1.06.69s-.52.63-.69 1.06c-.13.32-.28.81-.32 1.71-.06 1.23-.07 1.6-.07 4.15s.01 2.92.07 4.15c.04.9.19 1.39.32 1.71.17.43.37.74.69 1.06s.63.52 1.06.69c.32.13.81.28 1.71.32 1.23.06 1.6.07 4.73.07s3.5-.01 4.73-.07c.9-.04 1.39-.19 1.71-.32.43-.17.74-.37 1.06-.69s.52-.63.69-1.06c.13-.32.28-.81.32-1.71.06-1.23.07-1.6.07-4.15s-.01-2.92-.07-4.15c-.04-.9-.19-1.39-.32-1.71a2.9 2.9 0 0 0-.69-1.06 2.9 2.9 0 0 0-1.06-.69c-.32-.13-.81-.28-1.71-.32C15.5 4.01 15.13 4 12 4zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.08a3.18 3.18 0 1 0 0-6.36 3.18 3.18 0 0 0 0 6.36zM18.3 6.9a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0z"/>',
		'linkedin'    => '<path d="M6.94 8.5v11.3H3.2V8.5zM5.07 3a2.17 2.17 0 1 1 0 4.34 2.17 2.17 0 0 1 0-4.34zM9.3 8.5h3.58v1.55h.05c.5-.94 1.72-1.93 3.53-1.93 3.78 0 4.48 2.48 4.48 5.71v5.97h-3.73v-5.29c0-1.26-.02-2.89-1.76-2.89-1.76 0-2.03 1.37-2.03 2.79v5.39H9.3z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$brand_icons = array( 'facebook', 'x-twitter', 'instagram', 'linkedin' );
	$is_brand    = in_array( $name, $brand_icons, true );

	$defaults = array(
		'class'       => 'kaao-icon',
		'width'       => 24,
		'height'      => 24,
		'aria-hidden' => 'true',
		'focusable'   => 'false',
	);
	$attrs = array_merge( $defaults, $attrs );

	$out = '';
	foreach ( $attrs as $key => $value ) {
		$out .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( (string) $value ) );
	}

	return sprintf(
		'<svg viewBox="0 0 24 24"%s%s>%s</svg>',
		$out,
		$is_brand
			? ' fill="currentColor"'
			: ' fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"',
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput -- static, hand-authored SVG path data.
	);
}

/**
 * Section heading block: eyebrow + title + optional intro and action.
 *
 * @param array $args {
 *     @type string $eyebrow  Small caps label.
 *     @type string $title    Heading text.
 *     @type string $intro    Supporting paragraph.
 *     @type string $level    Heading level, default h2.
 *     @type string $align    'left' (default) or 'center'.
 *     @type string $link     Optional action URL.
 *     @type string $link_text Optional action label.
 *     @type string $id       Optional heading id.
 * }
 */
function kaao_section_head( array $args = array() ): void {
	$a = wp_parse_args(
		$args,
		array(
			'eyebrow'   => '',
			'title'     => '',
			'intro'     => '',
			'level'     => 'h2',
			'align'     => 'left',
			'link'      => '',
			'link_text' => '',
			'id'        => '',
		)
	);

	$level   = in_array( $a['level'], array( 'h1', 'h2', 'h3' ), true ) ? $a['level'] : 'h2';
	$classes = 'section-head';
	if ( 'center' === $a['align'] ) {
		$classes .= ' section-head--center';
	}
	if ( $a['link'] ) {
		$classes .= ' section-head--split';
	}
	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<div>
			<?php if ( $a['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo esc_html( $a['eyebrow'] ); ?></p>
			<?php endif; ?>

			<<?php echo esc_attr( $level ); ?> class="balance"<?php echo $a['id'] ? ' id="' . esc_attr( $a['id'] ) . '"' : ''; ?>>
				<?php echo esc_html( $a['title'] ); ?>
			</<?php echo esc_attr( $level ); ?>>

			<?php if ( $a['intro'] ) : ?>
				<p class="pretty"><?php echo esc_html( $a['intro'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $a['link'] && $a['link_text'] ) : ?>
			<a class="link-arrow" href="<?php echo esc_url( $a['link'] ); ?>"><?php echo esc_html( $a['link_text'] ); ?></a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Responsive image for an attachment with sensible fallbacks.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Registered size.
 * @param array  $attrs         Extra <img> attributes.
 * @return string
 */
function kaao_image( int $attachment_id, string $size = 'kaao-card', array $attrs = array() ): string {
	if ( ! $attachment_id ) {
		return '';
	}
	$defaults = array(
		'loading'  => 'lazy',
		'decoding' => 'async',
	);

	return wp_get_attachment_image( $attachment_id, $size, false, array_merge( $defaults, $attrs ) );
}

/**
 * Post thumbnail, or nothing. Keeps templates free of has_post_thumbnail
 * ladders.
 *
 * @param string $size  Image size.
 * @param array  $attrs Attributes.
 * @return string
 */
function kaao_thumbnail( string $size = 'kaao-card', array $attrs = array() ): string {
	return kaao_image( (int) get_post_thumbnail_id(), $size, $attrs );
}

/**
 * Initials fallback used when a member has no logo or a leader has no photo.
 *
 * @param string $name Source string.
 * @return string Up to two uppercase letters.
 */
function kaao_initials( string $name ): string {
	$words = preg_split( '/[\s\-–—]+/u', trim( wp_strip_all_tags( $name ) ), -1, PREG_SPLIT_NO_EMPTY ) ?: array();
	$out   = '';
	foreach ( array_slice( $words, 0, 2 ) as $word ) {
		$out .= mb_strtoupper( mb_substr( $word, 0, 1 ) );
	}

	return $out ?: '·';
}

/**
 * Event date range in a readable form.
 *
 * @param int|null $post_id Event ID.
 * @return string
 */
function kaao_event_date_range( ?int $post_id = null ): string {
	$post_id = $post_id ?: get_the_ID();
	$start   = kaao_field( 'kaao_event_start', $post_id );
	$end     = kaao_field( 'kaao_event_end', $post_id );

	if ( ! $start ) {
		return '';
	}

	$fmt   = (string) get_option( 'date_format', 'j F Y' );
	$s_ts  = strtotime( $start );
	$label = wp_date( $fmt, $s_ts );

	if ( $end && $end !== $start ) {
		$e_ts = strtotime( $end );
		// Same month and year — "12–14 June 2025".
		if ( wp_date( 'F Y', $s_ts ) === wp_date( 'F Y', $e_ts ) ) {
			$label = wp_date( 'j', $s_ts ) . '–' . wp_date( 'j F Y', $e_ts );
		} else {
			$label .= ' – ' . wp_date( $fmt, $e_ts );
		}
	}

	return $label;
}

/**
 * Is this event in the future (or running today)?
 *
 * @param int|null $post_id Event ID.
 * @return bool
 */
function kaao_event_is_upcoming( ?int $post_id = null ): bool {
	$post_id = $post_id ?: get_the_ID();
	$end     = kaao_field( 'kaao_event_end', $post_id ) ?: kaao_field( 'kaao_event_start', $post_id );

	return $end ? ( strtotime( $end . ' 23:59:59' ) >= current_time( 'timestamp' ) ) : false; // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp
}

/**
 * Is this vacancy still open for applications?
 *
 * A vacancy with no closing date is a rolling advertisement and stays open
 * until KAAO unpublishes it.
 *
 * @param int|null $post_id Vacancy ID.
 * @return bool
 */
function kaao_vacancy_is_open( ?int $post_id = null ): bool {
	$deadline = kaao_field( 'kaao_vacancy_deadline', $post_id ?: get_the_ID() );

	return $deadline ? ( strtotime( $deadline . ' 23:59:59' ) >= current_time( 'timestamp' ) ) : true; // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp
}

/**
 * The human label a select field gives to a stored value.
 *
 * Keeps the wording in the meta schema rather than repeating it in templates.
 *
 * @param string $post_type Post type the field belongs to.
 * @param string $key       Field key.
 * @param string $value     Stored value.
 * @return string
 */
function kaao_field_choice( string $post_type, string $key, string $value ): string {
	if ( '' === $value ) {
		return '';
	}
	$options = kaao_fields_for( $post_type )[ $key ]['options'] ?? array();

	return (string) ( $options[ $value ] ?? $value );
}

/**
 * First term name for a post, or an empty string.
 *
 * @param string   $taxonomy Taxonomy.
 * @param int|null $post_id  Post ID.
 * @return WP_Term|null
 */
function kaao_first_term( string $taxonomy, ?int $post_id = null ): ?WP_Term {
	$terms = get_the_terms( $post_id ?: get_the_ID(), $taxonomy );

	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
}

/**
 * Render a term as a pill, linked to its archive.
 *
 * @param WP_Term|null $term     Term.
 * @param string       $modifier Extra pill class, e.g. 'pill--gold'.
 */
function kaao_term_pill( ?WP_Term $term, string $modifier = '' ): void {
	if ( ! $term ) {
		return;
	}
	$link = get_term_link( $term );
	$cls  = trim( 'pill ' . $modifier );

	if ( is_wp_error( $link ) ) {
		printf( '<span class="%s">%s</span>', esc_attr( $cls ), esc_html( $term->name ) );
		return;
	}
	printf(
		'<a class="%s" href="%s">%s</a>',
		esc_attr( $cls ),
		esc_url( $link ),
		esc_html( $term->name )
	);
}

/**
 * A trimmed excerpt that never falls back to the raw post body.
 *
 * @param int|null $post_id Post ID.
 * @param int      $words   Word budget.
 * @return string
 */
function kaao_excerpt( ?int $post_id = null, int $words = 26 ): string {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$text = $post->post_excerpt ?: wp_strip_all_tags( strip_shortcodes( $post->post_content ) );

	return wp_trim_words( $text, $words, '…' );
}

/**
 * Escaped, human-readable host name for an external URL.
 *
 * @param string $url URL.
 * @return string
 */
function kaao_pretty_url( string $url ): string {
	$host = wp_parse_url( $url, PHP_URL_HOST );

	return $host ? preg_replace( '/^www\./', '', $host ) : $url;
}

/**
 * Marker for content KAAO still has to confirm. Keeps unverified copy visibly
 * flagged rather than silently presented as fact.
 *
 * @param string $what Description of what is missing.
 */
function kaao_to_confirm( string $what ): void {
	printf(
		'<p class="needs-confirmation">%s</p>',
		esc_html( sprintf( /* translators: %s: description of the missing content. */ __( '[%s — to be confirmed by KAAO]', 'kaao' ), $what ) )
	);
}

/**
 * The social channels that are actually configured.
 *
 * @return array<string, array{url:string,label:string,icon:string}>
 */
function kaao_social_links(): array {
	$map = array(
		'facebook'  => array( 'Facebook', 'facebook' ),
		'twitter'   => array( 'X (Twitter)', 'x-twitter' ),
		'instagram' => array( 'Instagram', 'instagram' ),
		'linkedin'  => array( 'LinkedIn', 'linkedin' ),
	);

	$out = array();
	foreach ( $map as $key => [$label, $icon] ) {
		$url = kaao_org_get( $key );
		if ( $url ) {
			$out[ $key ] = array(
				'url'   => $url,
				'label' => $label,
				'icon'  => $icon,
			);
		}
	}

	return $out;
}

/**
 * Numbered pagination shared by every archive.
 *
 * Defaults paginate the main query; a template running its own query passes
 * base/current/total so both look and behave identically.
 *
 * @param array $args Overrides passed straight to paginate_links().
 */
function kaao_pagination( array $args = array() ): void {
	$links = paginate_links(
		array_merge(
			array(
				'mid_size'  => 1,
				'end_size'  => 1,
				'prev_text' => __( '← Previous', 'kaao' ),
				'next_text' => __( 'Next →', 'kaao' ),
				'type'      => 'list',
			),
			$args
		)
	);
	if ( ! $links ) {
		return;
	}
	printf(
		'<nav class="pagination" aria-label="%s"><div class="nav-links">%s</div></nav>',
		esc_attr__( 'Pagination', 'kaao' ),
		wp_kses_post( str_replace( array( '<ul class=\'page-numbers\'>', '</ul>', '<li>', '</li>' ), '', $links ) )
	);
}

/**
 * Query a handful of posts for a homepage section.
 *
 * @param string $post_type    Post type.
 * @param int    $count        How many.
 * @param array  $extra        Extra WP_Query args.
 * @return WP_Query
 */
function kaao_get_posts( string $post_type, int $count = 3, array $extra = array() ): WP_Query {
	return new WP_Query(
		array_merge(
			array(
				'post_type'              => $post_type,
				'posts_per_page'         => $count,
				'post_status'            => 'publish',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => true,
			),
			$extra
		)
	);
}
