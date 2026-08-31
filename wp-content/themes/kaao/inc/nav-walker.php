<?php
/**
 * Navigation: the default information architecture and the drawer walker.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

/**
 * The approved information architecture.
 *
 * Used as the navigation fallback before a menu is assigned, and as the source
 * the seeder builds the real WordPress menu from — so the two can never drift.
 *
 * @return array<int, array{label:string,url:string,children?:array<int,array{label:string,url:string}>}>
 */
function kaao_default_nav(): array {
	return array(
		array(
			'label'    => __( 'About', 'kaao' ),
			'url'      => '/about-us/',
			'children' => array(
				array( 'label' => __( 'Who We Are', 'kaao' ), 'url' => '/about-us/' ),
				array( 'label' => __( 'Our History', 'kaao' ), 'url' => '/about-us/history/' ),
				array( 'label' => __( 'Mission, Vision & Objectives', 'kaao' ), 'url' => '/about-us/mission-vision-objectives/' ),
				array( 'label' => __( 'Leadership', 'kaao' ), 'url' => '/leadership/' ),
				array( 'label' => __( 'Partners & Stakeholders', 'kaao' ), 'url' => '/about-us/partners-and-stakeholders/' ),
			),
		),
		array(
			'label'    => __( 'Membership', 'kaao' ),
			'url'      => '/membership/',
			'children' => array(
				array( 'label' => __( 'Why Join KAAO', 'kaao' ), 'url' => '/membership/' ),
				array( 'label' => __( 'Membership Categories', 'kaao' ), 'url' => '/membership/categories/' ),
				array( 'label' => __( 'Membership Benefits', 'kaao' ), 'url' => '/membership/benefits/' ),
				array( 'label' => __( 'How to Join', 'kaao' ), 'url' => '/membership/how-to-join/' ),
				array( 'label' => __( 'Member Registration', 'kaao' ), 'url' => '/member-registration/' ),
				array( 'label' => __( 'FAQs', 'kaao' ), 'url' => '/faqs/' ),
			),
		),
		array(
			'label' => __( 'Members', 'kaao' ),
			'url'   => '/members/',
		),
		array(
			'label'    => __( 'Advocacy', 'kaao' ),
			'url'      => '/advocacy/',
			'children' => array(
				array( 'label' => __( 'Our Advocacy', 'kaao' ), 'url' => '/advocacy/' ),
				array( 'label' => __( 'Advocacy Impact', 'kaao' ), 'url' => '/advocacy/impact/' ),
			),
		),
		array(
			'label'    => __( 'News & Insights', 'kaao' ),
			'url'      => '/news/',
			'children' => array(
				array( 'label' => __( 'All News', 'kaao' ), 'url' => '/news/' ),
				array( 'label' => __( 'Press Releases', 'kaao' ), 'url' => '/news/category/press-releases/' ),
				// Disabled until KAAO has content in these categories — restore
				// by uncommenting once each category is publishing regularly.
				// array( 'label' => __( 'KAAO News', 'kaao' ), 'url' => '/news/category/kaao-news/' ),
				// array( 'label' => __( 'Industry Updates', 'kaao' ), 'url' => '/news/category/industry-updates/' ),
				// array( 'label' => __( 'Advocacy Updates', 'kaao' ), 'url' => '/news/category/advocacy-updates/' ),
				// array( 'label' => __( 'Regulatory Updates', 'kaao' ), 'url' => '/news/category/regulatory-updates/' ),
				array( 'label' => __( 'Articles', 'kaao' ), 'url' => '/news/category/articles/' ),
				array( 'label' => __( 'Newsletter', 'kaao' ), 'url' => '/resources/type/publications/' ),
			),
		),
		array(
			'label' => __( 'Events', 'kaao' ),
			'url'   => '/events/',
		),
		array(
			'label'    => __( 'Resources', 'kaao' ),
			'url'      => '/resources/',
			'children' => array(
				array( 'label' => __( 'All Resources', 'kaao' ), 'url' => '/resources/' ),
				array( 'label' => __( 'Gallery', 'kaao' ), 'url' => '/gallery/' ),
				array( 'label' => __( 'Careers', 'kaao' ), 'url' => '/careers/' ),
				array( 'label' => __( 'Exchange Rates', 'kaao' ), 'url' => '/exchange-rates/' ),
			),
		),
		array(
			'label' => __( 'Contact', 'kaao' ),
			'url'   => '/contact-us/',
		),
	);
}

/**
 * Accessible mobile-drawer menu walker.
 *
 * A parent item renders as a link plus a separate disclosure button, so the
 * parent page stays reachable and the submenu gets a real toggle.
 */
class KAAO_Drawer_Walker extends Walker_Nav_Menu {

	/**
	 * The item whose submenu is currently being opened.
	 *
	 * @var int
	 */
	private int $current_parent = 0;

	/**
	 * Open a submenu list.
	 *
	 * @param string   $output Markup, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= sprintf( '<ul class="sub-menu" id="kaao-sub-%d" hidden>', $this->current_parent );
	}

	/**
	 * Close a submenu list.
	 *
	 * @param string   $output Markup, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	/**
	 * Render one item.
	 *
	 * @param string   $output Markup, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Item id.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes    = array_filter( (array) $item->classes );
		$has_kids   = in_array( 'menu-item-has-children', $classes, true );
		$is_current = in_array( 'current-menu-item', $classes, true );

		if ( $has_kids && 0 === $depth ) {
			$this->current_parent = (int) $item->ID;
		}

		$li_class = trim( implode( ' ', array_intersect( $classes, array( 'current-menu-item', 'current-menu-ancestor' ) ) ) );
		$output  .= '<li' . ( $li_class ? ' class="' . esc_attr( $li_class ) . '"' : '' ) . '>';

		$link = sprintf(
			'<a href="%s"%s>%s</a>',
			esc_url( $item->url ?: '#' ),
			$is_current ? ' aria-current="page"' : '',
			esc_html( $item->title )
		);

		if ( $has_kids && 0 === $depth ) {
			$output .= '<div class="kaao-drawer__row">' . $link . kaao_drawer_toggle( (int) $item->ID, wp_strip_all_tags( $item->title ), 'kaao-sub' ) . '</div>';
		} else {
			$output .= $link;
		}
	}

	/**
	 * Close an item.
	 *
	 * @param string   $output Markup, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * The submenu disclosure button used in the drawer.
 *
 * @param int    $id     Identifier used to build the aria-controls target.
 * @param string $label  Parent item title.
 * @param string $prefix Id prefix.
 * @return string Escaped markup.
 */
function kaao_drawer_toggle( int $id, string $label, string $prefix ): string {
	return sprintf(
		'<button type="button" class="kaao-subtoggle" aria-expanded="false" aria-controls="%1$s-%2$d">'
			. '<span class="screen-reader-text">%3$s</span>%4$s</button>',
		esc_attr( $prefix ),
		$id,
		esc_html(
			sprintf(
				/* translators: %s: menu item title. */
				__( 'Show %s submenu', 'kaao' ),
				$label
			)
		),
		kaao_icon( 'chevron', array( 'width' => 16, 'height' => 16 ) )
	);
}

/**
 * Drawer navigation rendered from kaao_default_nav() when no menu is assigned.
 */
function kaao_drawer_fallback_nav(): void {
	echo '<ul>';
	foreach ( kaao_default_nav() as $index => $item ) {
		echo '<li>';

		$link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( home_url( $item['url'] ) ),
			esc_html( $item['label'] )
		);

		if ( ! empty( $item['children'] ) ) {
			echo '<div class="kaao-drawer__row">'
				. $link // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
				. kaao_drawer_toggle( (int) $index, $item['label'], 'kaao-fbsub' ) // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper.
				. '</div>';

			printf( '<ul class="sub-menu" id="kaao-fbsub-%d" hidden>', (int) $index );
			foreach ( $item['children'] as $child ) {
				printf(
					'<li><a href="%s">%s</a></li>',
					esc_url( home_url( $child['url'] ) ),
					esc_html( $child['label'] )
				);
			}
			echo '</ul>';
		} else {
			echo $link; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
		}

		echo '</li>';
	}
	echo '</ul>';
}
