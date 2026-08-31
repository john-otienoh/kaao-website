<?php
/**
 * Site search form.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_field_id = 'kaao-search-' . wp_unique_id();
?>
<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $kaao_field_id ); ?>">
		<?php esc_html_e( 'Search the KAAO website', 'kaao' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $kaao_field_id ); ?>"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search members, news, events, resources…', 'kaao' ); ?>">
	<button type="submit" class="btn btn--primary">
		<?php echo kaao_icon( 'search', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'kaao' ); ?></span>
	</button>
</form>
