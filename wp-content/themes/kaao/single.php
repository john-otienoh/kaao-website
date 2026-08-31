<?php
/**
 * Single news article.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_category = kaao_first_term( 'category' );
	$kaao_permalink = (string) get_permalink();
	$kaao_title     = wp_strip_all_tags( (string) get_the_title() );
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container container--narrow">

			<header class="entry-header">
				<div class="cluster" style="margin-bottom:var(--sp-4)">
					<?php if ( $kaao_category && 'uncategorized' !== $kaao_category->slug ) : ?>
						<?php kaao_term_pill( $kaao_category, 'pill--gold' ); ?>
					<?php endif; ?>
					<span class="meta">
						<time datetime="<?php echo esc_attr( (string) get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( (string) get_the_date() ); ?>
						</time>
					</span>
				</div>

				<h1 class="balance"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="lede pretty"><?php echo esc_html( (string) get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-figure">
					<?php
					echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
						'post-thumbnail',
						array(
							'alt'           => esc_attr( $kaao_title ),
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'sizes'         => '(min-width: 48rem) 46rem, 92vw',
						)
					);
					?>
					<?php if ( wp_get_attachment_caption( (int) get_post_thumbnail_id() ) ) : ?>
						<figcaption><?php echo esc_html( (string) wp_get_attachment_caption( (int) get_post_thumbnail_id() ) ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<div class="prose">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="pagination"><div class="nav-links">',
						'after'  => '</div></nav>',
					)
				);
				?>
			</div>

			<footer class="mt-8" style="padding-top:var(--sp-6);border-top:1px solid var(--kaao-border)">
				<div class="cluster" style="justify-content:space-between">
					<div class="entry-share">
						<span class="muted" style="font-size:var(--fs-sm);font-weight:650">
							<?php esc_html_e( 'Share', 'kaao' ); ?>
						</span>
						<a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $kaao_permalink ) ); ?>"
							target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text"><?php esc_html_e( 'Share on LinkedIn (opens in a new tab)', 'kaao' ); ?></span>
							<?php echo kaao_icon( 'linkedin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
						<a href="<?php echo esc_url( 'https://x.com/intent/tweet?url=' . rawurlencode( $kaao_permalink ) . '&text=' . rawurlencode( $kaao_title ) ); ?>"
							target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text"><?php esc_html_e( 'Share on X (opens in a new tab)', 'kaao' ); ?></span>
							<?php echo kaao_icon( 'x-twitter' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
						<a href="<?php echo esc_url( 'mailto:?subject=' . rawurlencode( $kaao_title ) . '&body=' . rawurlencode( $kaao_permalink ) ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Share by email', 'kaao' ); ?></span>
							<?php echo kaao_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</div>

					<a class="link-arrow" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">
						<?php esc_html_e( 'Back to news', 'kaao' ); ?>
					</a>
				</div>
			</footer>
		</div>
	</article>

	<?php
	// Related articles from the same category.
	$kaao_related = kaao_get_posts(
		'post',
		3,
		array(
			'post__not_in' => array( get_the_ID() ),
			'category__in' => $kaao_category ? array( $kaao_category->term_id ) : array(),
		)
	);

	if ( $kaao_related->have_posts() ) :
		?>
		<section class="section section--surface" aria-labelledby="related-title">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow'   => __( 'Keep reading', 'kaao' ),
						'title'     => __( 'Related articles', 'kaao' ),
						'link'      => home_url( '/news/' ),
						'link_text' => __( 'All news', 'kaao' ),
						'id'        => 'related-title',
					)
				);
				?>
				<div class="grid grid--3">
					<?php
					while ( $kaao_related->have_posts() ) :
						$kaao_related->the_post();
						get_template_part( 'template-parts/cards/news' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
