<?php
/**
 * Template Name: Membership
 * Template Post Type: page
 *
 * Page content followed by the membership categories, benefits, the how-to-join
 * steps and the membership FAQs — all in one journey.
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
			'eyebrow'    => __( 'Membership', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Join the Kenya Association of Air Operators and be part of a network driving the growth and sustainability of Kenya’s aviation industry.', 'kaao' ),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-banner-membership-join-us',
		)
	);

	kaao_breadcrumbs();
	?>

	<?php if ( get_the_content() ) : ?>
		<article class="section">
			<div class="container container--narrow">
				<div class="prose">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/home/membership' ); ?>

	<section class="section" aria-labelledby="join-title">
		<div class="container container--narrow">
			<?php
			kaao_section_head(
				array(
					'eyebrow' => __( 'How to join', 'kaao' ),
					'title'   => __( 'Two steps to apply', 'kaao' ),
					'intro'   => __( 'The application process is handled by the Secretariat. Download the form, complete it, and submit it through the registration page.', 'kaao' ),
					'id'      => 'join-title',
				)
			);
			?>

			<ol class="timeline">
				<li>
					<span class="timeline__year"><?php esc_html_e( 'Step 1', 'kaao' ); ?></span>
					<h3><?php esc_html_e( 'Download the membership application form', 'kaao' ); ?></h3>
					<p><?php esc_html_e( 'The form sets out the information the Association needs about your organisation and the licences or certificates it holds.', 'kaao' ); ?></p>
				</li>
				<li>
					<span class="timeline__year"><?php esc_html_e( 'Step 2', 'kaao' ); ?></span>
					<h3><?php esc_html_e( 'Upload the completed form', 'kaao' ); ?></h3>
					<p><?php esc_html_e( 'Submit the completed form through the registration page and the Secretariat will be in touch.', 'kaao' ); ?></p>
				</li>
			</ol>

			<div class="cluster mt-8">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/member-registration/' ) ); ?>">
					<?php esc_html_e( 'Go to registration', 'kaao' ); ?>
				</a>
				<a class="btn btn--outline btn--lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					<?php esc_html_e( 'Ask a question first', 'kaao' ); ?>
				</a>
			</div>
		</div>
	</section>

	<?php if ( (int) wp_count_posts( KAAO_CPT_FAQ )->publish > 0 ) : ?>
		<section class="section section--surface" aria-labelledby="membership-faq-title">
			<div class="container container--narrow">
				<?php
				kaao_section_head(
					array(
						'eyebrow' => __( 'Frequently asked', 'kaao' ),
						'title'   => __( 'Membership questions', 'kaao' ),
						'id'      => 'membership-faq-title',
					)
				);

				get_template_part(
					'template-parts/sections/faq-accordion',
					null,
					array(
						'topic' => 'membership',
						'limit' => -1,
						'name'  => __( 'Membership questions', 'kaao' ),
					)
				);
				?>

				<p class="mt-6">
					<a class="link-arrow" href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>">
						<?php esc_html_e( 'See all FAQs', 'kaao' ); ?>
					</a>
				</p>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/home/cta' ); ?>

	<?php
endwhile;

get_footer();
