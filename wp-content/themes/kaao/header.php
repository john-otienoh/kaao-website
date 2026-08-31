<?php
/**
 * The global header.
 *
 * Rendered by every template through get_header(). There is exactly one copy
 * of this markup in the project.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_org      = kaao_org();
$kaao_has_menu = has_nav_menu( 'primary' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'kaao' ); ?></a>

<header class="kaao-header" role="banner">

	<div class="kaao-topbar">
		<div class="container kaao-topbar__inner">
			<p class="kaao-topbar__contact" style="margin:0">
				<?php if ( $kaao_org['address'] ) : ?>
					<span><?php echo esc_html( $kaao_org['address'] ); ?></span>
				<?php endif; ?>
				<?php if ( $kaao_org['phone'] ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $kaao_org['phone'] ) ); ?>"><?php echo esc_html( $kaao_org['phone'] ); ?></a>
				<?php endif; ?>
				<?php if ( $kaao_org['email'] ) : ?>
					<a href="mailto:<?php echo esc_attr( $kaao_org['email'] ); ?>"><?php echo esc_html( $kaao_org['email'] ); ?></a>
				<?php endif; ?>
			</p>

			<?php if ( kaao_social_links() ) : ?>
				<p style="margin:0;display:flex;gap:14px;align-items:center">
					<?php foreach ( kaao_social_links() as $kaao_key => $kaao_social ) : ?>
						<a href="<?php echo esc_url( $kaao_social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text">
								<?php
								printf(
									/* translators: %s: network name. */
									esc_html__( 'KAAO on %s (opens in a new tab)', 'kaao' ),
									esc_html( $kaao_social['label'] )
								);
								?>
							</span>
							<?php echo kaao_icon( $kaao_social['icon'], array( 'width' => 15, 'height' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="container kaao-header__inner">

		<?php get_template_part( 'template-parts/header/brand' ); ?>

		<nav class="kaao-nav" aria-label="<?php esc_attr_e( 'Primary', 'kaao' ); ?>">
			<?php
			if ( $kaao_has_menu ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'kaao-nav__list',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
			} else {
				get_template_part( 'template-parts/header/fallback-menu' );
			}
			?>
		</nav>

		<div class="kaao-header__actions">
			<a class="btn btn--primary btn--sm kaao-header__cta" href="<?php echo esc_url( home_url( '/membership/how-to-join/' ) ); ?>">
				<?php esc_html_e( 'Become a member', 'kaao' ); ?>
			</a>

			<button
				type="button"
				class="kaao-iconbtn kaao-burger"
				aria-expanded="false"
				aria-controls="kaao-drawer">
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'kaao' ); ?></span>
				<?php echo kaao_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/header/drawer' ); ?>

<main id="main" class="kaao-main" tabindex="-1">
