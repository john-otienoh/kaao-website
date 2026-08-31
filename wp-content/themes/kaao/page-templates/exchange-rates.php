<?php
/**
 * Template Name: Exchange Rates
 * Template Post Type: page
 *
 * Weekly KES reference rates for USD/GBP/EUR relevant to Kenyan aviation
 * trade, rendered from the Sheet to Table Live Sync from Google Sheet plugin.
 * Kept at its original production URL so indexed and bookmarked links
 * keep working.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/hero/page',
		null,
		array(
			'eyebrow' => __( 'Resources', 'kaao' ),
			'title'   => (string) get_the_title(),
			'intro'   => get_the_excerpt() ?: __( 'Indicative USD reference rates for currencies used across KAAO membership and regional aviation trade.', 'kaao' ),
		)
	);

	kaao_breadcrumbs();
	?>

	<div class="section">
		<div class="container container--narrow">

			<?php if ( get_the_content() ) : ?>
				<div class="prose" style="margin-bottom:var(--sp-8)">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<div class="kaao-sheet-table"
				style="padding:var(--sp-6);background:var(--kaao-surface);border:1px solid var(--kaao-border);border-radius:var(--card-radius)">
				<?php if ( shortcode_exists( 'STWT_Sheet_Table' ) ) : ?>
					<?php echo do_shortcode( '[STWT_Sheet_Table id="2261" name="Weekly reference rates (KES)"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<p class="muted"><?php esc_html_e( 'Exchange rates are temporarily unavailable. Please check that Sheet to Table Live Sync from Google Sheet is active.', 'kaao' ); ?></p>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<?php
endwhile;

get_footer();
