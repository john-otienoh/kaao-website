<?php
/**
 * Advocacy archive.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_archive = get_post_type_archive_link( KAAO_CPT_ADVOCACY ) ?: home_url( '/advocacy/' );

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'Advocacy', 'kaao' ),
		'title'      => is_tax() ? wp_strip_all_tags( get_the_archive_title() ) : __( 'Our advocacy', 'kaao' ),
		'intro'      => __( 'KAAO engages government, legislators, regulators and development partners on the policy issues that shape Kenya’s air transport industry. This is the record of that work.', 'kaao' ),
		'image_slug' => 'kaao-banner-advocacy-regulatory-forum-address',
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container">

		<?php
		$kaao_themes = get_terms(
			array(
				'taxonomy'   => 'kaao_advocacy_theme',
				'hide_empty' => true,
			)
		);
		if ( ! is_wp_error( $kaao_themes ) && $kaao_themes ) :
			?>
			<ul class="tabs">
				<li<?php echo is_post_type_archive() ? ' class="is-current"' : ''; ?>>
					<a href="<?php echo esc_url( $kaao_archive ); ?>"<?php echo is_post_type_archive() ? ' aria-current="page"' : ''; ?>>
						<?php esc_html_e( 'All themes', 'kaao' ); ?>
					</a>
				</li>
				<?php foreach ( $kaao_themes as $kaao_theme ) : ?>
					<?php $kaao_current = is_tax( 'kaao_advocacy_theme', $kaao_theme->term_id ); ?>
					<li<?php echo $kaao_current ? ' class="is-current"' : ''; ?>>
						<a href="<?php echo esc_url( (string) get_term_link( $kaao_theme ) ); ?>"<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $kaao_theme->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<?php // Names the results region for assistive technology and keeps the heading order unbroken. ?>
			<h2 class="screen-reader-text">
				<?php
				is_tax()
					? printf(
						/* translators: %s: advocacy theme name. */
						esc_html__( 'Advocacy records in %s', 'kaao' ),
						esc_html( wp_strip_all_tags( get_the_archive_title() ) )
					)
					: esc_html_e( 'All advocacy records', 'kaao' );
				?>
			</h2>

			<div class="grid grid--2">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/advocacy' );
				endwhile;
				?>
			</div>

			<?php kaao_pagination(); ?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'No advocacy records in this view', 'kaao' ); ?></h2>
				<p><?php esc_html_e( 'Advocacy work is published here as engagements are documented.', 'kaao' ); ?></p>
				<p><a class="btn btn--navy" href="<?php echo esc_url( $kaao_archive ); ?>"><?php esc_html_e( 'Show all advocacy work', 'kaao' ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
