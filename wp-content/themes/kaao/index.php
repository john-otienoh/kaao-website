<?php
/**
 * Fallback template.
 *
 * Also serves the news index (the page assigned to Posts).
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_posts_page = (int) get_option( 'page_for_posts' );
$kaao_title      = $kaao_posts_page ? get_the_title( $kaao_posts_page ) : __( 'News & Insights', 'kaao' );
$kaao_intro      = $kaao_posts_page ? get_post_field( 'post_excerpt', $kaao_posts_page ) : '';

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'Newsroom', 'kaao' ),
		'title'      => is_home() ? $kaao_title : wp_strip_all_tags( get_the_archive_title() ),
		'intro'      => $kaao_intro ?: __( 'Press releases, advocacy updates, regulatory news and industry insight from the Kenya Association of Air Operators.', 'kaao' ),
		'image_slug' => 'kaao-banner-newsroom-stakeholder-panel',
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container">

		<?php
		$kaao_categories = get_categories(
			array(
				'hide_empty' => true,
				'exclude'    => array( (int) get_option( 'default_category' ) ),
			)
		);
		if ( $kaao_categories ) :
			?>
			<ul class="tabs">
				<li<?php echo is_home() ? ' class="is-current"' : ''; ?>>
					<a href="<?php echo esc_url( $kaao_posts_page ? (string) get_permalink( $kaao_posts_page ) : home_url( '/news/' ) ); ?>"
						<?php echo is_home() ? ' aria-current="page"' : ''; ?>>
						<?php esc_html_e( 'All', 'kaao' ); ?>
					</a>
				</li>
				<?php foreach ( $kaao_categories as $kaao_category ) : ?>
					<?php $kaao_is_current = is_category( $kaao_category->term_id ); ?>
					<li<?php echo $kaao_is_current ? ' class="is-current"' : ''; ?>>
						<a href="<?php echo esc_url( (string) get_category_link( $kaao_category ) ); ?>"
							<?php echo $kaao_is_current ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $kaao_category->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<?php // Names the results region for assistive technology and keeps the heading order unbroken. ?>
			<h2 class="screen-reader-text">
				<?php
				echo esc_html(
					is_home()
						? __( 'All articles', 'kaao' )
						: wp_strip_all_tags( get_the_archive_title() )
				);
				?>
			</h2>

			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/news' );
				endwhile;
				?>
			</div>

			<?php kaao_pagination(); ?>

		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nothing published here yet', 'kaao' ); ?></h2>
				<p><?php esc_html_e( 'There are no articles in this section at the moment. Please check back soon.', 'kaao' ); ?></p>
				<p><a class="btn btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'kaao' ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
