<?php
/**
 * Header brand lock-up.
 *
 * The KAAO logo artwork already contains the wordmark and the full
 * organisation name, so the text lock-up is rendered only as a fallback when
 * no logo has been set. Either way the link carries an accessible name.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_logo_id = (int) get_theme_mod( 'custom_logo' );
$kaao_is_home = is_front_page();
?>
<a class="kaao-brand<?php echo $kaao_logo_id ? ' kaao-brand--mark' : ''; ?>"
	href="<?php echo esc_url( home_url( '/' ) ); ?>"
	<?php echo $kaao_is_home ? ' aria-current="page"' : ''; ?>>

	<?php if ( $kaao_logo_id ) : ?>
		<?php
		echo wp_get_attachment_image(
			$kaao_logo_id,
			'full',
			false,
			array(
				'alt'           => '',
				'fetchpriority' => 'high',
				'decoding'      => 'sync',
			)
		);
		?>
		<span class="screen-reader-text">
			<?php esc_html_e( 'Kenya Association of Air Operators — home', 'kaao' ); ?>
		</span>
	<?php else : ?>
		<span class="kaao-brand__text">
			<span class="kaao-brand__name">KAAO</span>
			<span class="kaao-brand__tag"><?php esc_html_e( 'Kenya Association of Air Operators', 'kaao' ); ?></span>
		</span>
	<?php endif; ?>
</a>
