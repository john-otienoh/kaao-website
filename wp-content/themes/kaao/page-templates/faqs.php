<?php
/**
 * Template Name: FAQs
 * Template Post Type: page
 *
 * Questions grouped by topic, each group an accessible accordion.
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
			'eyebrow'    => __( 'Help', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Answers to the questions we are asked most often about membership, the Association and its work.', 'kaao' ),
			'image_slug' => 'kaao-banner-faqs-member-consultation',
		)
	);

	kaao_breadcrumbs();

	$kaao_topics = get_terms(
		array(
			'taxonomy'   => 'kaao_faq_topic',
			'hide_empty' => true,
		)
	);
	if ( is_wp_error( $kaao_topics ) ) {
		$kaao_topics = array();
	}
	?>

	<div class="section">
		<div class="container">
			<div class="with-sidebar">

				<aside class="with-sidebar__aside">
					<?php if ( count( $kaao_topics ) > 1 ) : ?>
						<nav class="filters" aria-labelledby="faq-nav-title">
							<h2 id="faq-nav-title"><?php esc_html_e( 'On this page', 'kaao' ); ?></h2>
							<ul style="list-style:none;margin:0;padding:0;display:grid;gap:var(--sp-1)">
								<?php foreach ( $kaao_topics as $kaao_topic ) : ?>
									<li style="margin:0">
										<a href="#faq-<?php echo esc_attr( $kaao_topic->slug ); ?>"
											style="display:flex;justify-content:space-between;gap:.5rem;padding:.5rem .625rem;border-radius:var(--radius-sm);text-decoration:none;font-size:var(--fs-sm)">
											<span><?php echo esc_html( $kaao_topic->name ); ?></span>
											<span class="muted"><?php echo (int) $kaao_topic->count; ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>

					<div class="mt-6" style="padding:var(--sp-5);background:var(--kaao-surface);border-radius:var(--card-radius);border:1px solid var(--kaao-border)">
						<h2 class="h4"><?php esc_html_e( 'Still have a question?', 'kaao' ); ?></h2>
						<p class="muted" style="font-size:var(--fs-sm)">
							<?php esc_html_e( 'The Secretariat is happy to help with anything not covered here.', 'kaao' ); ?>
						</p>
						<a class="btn btn--navy btn--sm" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
							<?php esc_html_e( 'Contact us', 'kaao' ); ?>
						</a>
					</div>
				</aside>

				<div>
					<?php if ( get_the_content() ) : ?>
						<div class="prose" style="margin-bottom:var(--sp-10)">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>

					<?php if ( $kaao_topics ) : ?>
						<?php foreach ( $kaao_topics as $kaao_topic ) : ?>
							<section id="faq-<?php echo esc_attr( $kaao_topic->slug ); ?>" style="margin-bottom:clamp(2.5rem,5vw,4rem);scroll-margin-top:calc(var(--header-h) + 1rem)">
								<h2 class="h3"><?php echo esc_html( $kaao_topic->name ); ?></h2>

								<?php if ( $kaao_topic->description ) : ?>
									<p class="muted"><?php echo esc_html( $kaao_topic->description ); ?></p>
								<?php endif; ?>

								<?php
								get_template_part(
									'template-parts/sections/faq-accordion',
									null,
									array(
										'topic' => $kaao_topic->slug,
										'limit' => -1,
										'name'  => $kaao_topic->name,
									)
								);
								?>
							</section>
						<?php endforeach; ?>
					<?php else : ?>
						<?php
						get_template_part(
							'template-parts/sections/faq-accordion',
							null,
							array( 'limit' => -1 )
						);
						?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();
