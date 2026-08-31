<?php
/**
 * Mobile navigation drawer.
 *
 * Hidden until opened, focus-trapped while open, and closable with Escape.
 * With JavaScript unavailable the drawer stays hidden and the fallback
 * navigation below the fold remains reachable via the footer sitemap links.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="kaao-scrim" hidden></div>

<div class="kaao-drawer" id="kaao-drawer" hidden>
	<div class="kaao-drawer__head">
		<span style="font-weight:750;letter-spacing:.04em">
			<?php esc_html_e( 'Menu', 'kaao' ); ?>
		</span>
		<button type="button" class="kaao-iconbtn kaao-drawer__close">
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'kaao' ); ?></span>
			<?php echo kaao_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
	</div>

	<div class="kaao-drawer__body">
		<nav class="kaao-drawer__nav" aria-label="<?php esc_attr_e( 'Mobile', 'kaao' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => '',
						'depth'          => 2,
						'items_wrap'     => '<ul>%3$s</ul>',
						'walker'         => new KAAO_Drawer_Walker(),
						'fallback_cb'    => false,
					)
				);
			} else {
				kaao_drawer_fallback_nav();
			}
			?>
		</nav>
	</div>

	<div class="kaao-drawer__foot">
		<a class="btn btn--primary btn--block" href="<?php echo esc_url( home_url( '/membership/how-to-join/' ) ); ?>">
			<?php esc_html_e( 'Join Us', 'kaao' ); ?>
		</a>
		<p style="margin:1rem 0 0;font-size:var(--fs-xs);color:var(--kaao-muted)">
			<?php if ( kaao_org_get( 'phone' ) ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', kaao_org_get( 'phone' ) ) ); ?>"><?php echo esc_html( kaao_org_get( 'phone' ) ); ?></a><br>
			<?php endif; ?>
			<?php if ( kaao_org_get( 'email' ) ) : ?>
				<a href="mailto:<?php echo esc_attr( kaao_org_get( 'email' ) ); ?>"><?php echo esc_html( kaao_org_get( 'email' ) ); ?></a>
			<?php endif; ?>
		</p>
	</div>
</div>
