<?php
/**
 * Desktop navigation shown when no menu has been assigned to Primary.
 *
 * The item list comes from kaao_default_nav() in inc/nav-walker.php, which is
 * also what the content seeder builds the real menu from.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_path = '/' . trim( (string) wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ), '/' ) . '/';
?>
<ul class="kaao-nav__list">
	<?php foreach ( kaao_default_nav() as $kaao_item ) : ?>
		<?php
		$kaao_has_kids = ! empty( $kaao_item['children'] );
		$kaao_current  = ( $kaao_path === $kaao_item['url'] );
		$kaao_classes  = trim( ( $kaao_has_kids ? 'has-children ' : '' ) . ( $kaao_current ? 'is-current' : '' ) );
		?>
		<li<?php echo $kaao_classes ? ' class="' . esc_attr( $kaao_classes ) . '"' : ''; ?>>
			<a href="<?php echo esc_url( home_url( $kaao_item['url'] ) ); ?>"<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>>
				<?php echo esc_html( $kaao_item['label'] ); ?>
			</a>

			<?php if ( $kaao_has_kids ) : ?>
				<ul class="sub-menu">
					<?php foreach ( $kaao_item['children'] as $kaao_child ) : ?>
						<li<?php echo $kaao_path === $kaao_child['url'] ? ' class="current-menu-item"' : ''; ?>>
							<a href="<?php echo esc_url( home_url( $kaao_child['url'] ) ); ?>"><?php echo esc_html( $kaao_child['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
