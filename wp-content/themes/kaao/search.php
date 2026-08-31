<?php
/**
 * Search results, grouped so a visitor can tell a member from an article.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_query = get_search_query();
$kaao_found = (int) $GLOBALS['wp_query']->found_posts;

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow' => __( 'Search', 'kaao' ),
		'title'   => $kaao_query
			/* translators: %s: search term. */
			? sprintf( __( 'Results for “%s”', 'kaao' ), $kaao_query )
			: __( 'Search', 'kaao' ),
		'intro'   => $kaao_found
			? sprintf(
				/* translators: %s: number of results. */
				_n( '%s result found.', '%s results found.', $kaao_found, 'kaao' ),
				number_format_i18n( $kaao_found )
			)
			: __( 'No results found.', 'kaao' ),
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container container--narrow">

		<div style="margin-bottom:clamp(2rem,4vw,3rem)">
			<?php get_search_form(); ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<ol style="list-style:none;margin:0;padding:0;display:grid;gap:var(--sp-4)">
				<?php
				while ( have_posts() ) :
					the_post();

					$kaao_type_obj   = get_post_type_object( (string) get_post_type() );
					$kaao_type_label = $kaao_type_obj->labels->singular_name ?? '';
					?>
					<li style="margin:0">
						<article class="card card--flat" style="padding:var(--sp-5)">
							<div class="cluster" style="margin-bottom:.5rem">
								<?php if ( $kaao_type_label ) : ?>
									<span class="pill pill--muted"><?php echo esc_html( $kaao_type_label ); ?></span>
								<?php endif; ?>
								<?php if ( 'post' === get_post_type() ) : ?>
									<span class="meta">
										<time datetime="<?php echo esc_attr( (string) get_the_date( 'c' ) ); ?>"><?php echo esc_html( (string) get_the_date() ); ?></time>
									</span>
								<?php endif; ?>
							</div>

							<h2 class="card__title" style="font-size:var(--fs-lg)">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<p class="card__excerpt" style="margin-top:.5rem"><?php echo esc_html( kaao_excerpt( null, 28 ) ); ?></p>
						</article>
					</li>
					<?php
				endwhile;
				?>
			</ol>

			<?php kaao_pagination(); ?>

		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nothing matched that search', 'kaao' ); ?></h2>
				<p><?php esc_html_e( 'Try a shorter phrase, or start from one of these sections.', 'kaao' ); ?></p>
				<div class="cluster" style="justify-content:center;margin-top:var(--sp-6)">
					<a class="btn btn--outline btn--sm" href="<?php echo esc_url( home_url( '/members/' ) ); ?>"><?php esc_html_e( 'Member directory', 'kaao' ); ?></a>
					<a class="btn btn--outline btn--sm" href="<?php echo esc_url( home_url( '/news/' ) ); ?>"><?php esc_html_e( 'News', 'kaao' ); ?></a>
					<a class="btn btn--outline btn--sm" href="<?php echo esc_url( home_url( '/events/' ) ); ?>"><?php esc_html_e( 'Events', 'kaao' ); ?></a>
					<a class="btn btn--outline btn--sm" href="<?php echo esc_url( home_url( '/resources/' ) ); ?>"><?php esc_html_e( 'Resources', 'kaao' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
