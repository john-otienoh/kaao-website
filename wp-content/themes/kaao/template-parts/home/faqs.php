<?php
/**
 * Membership FAQs on the homepage.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_has_faqs = (int) wp_count_posts( KAAO_CPT_FAQ )->publish > 0;

if ( ! $kaao_has_faqs ) {
	return;
}
?>
<section class="section section--tint" aria-labelledby="faq-title">
	<div class="container">
		<div class="split">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Frequently asked', 'kaao' ); ?></p>
				<h2 id="faq-title" class="balance"><?php esc_html_e( 'Questions about joining KAAO', 'kaao' ); ?></h2>
				<p class="pretty">
					<?php esc_html_e( 'The questions we are asked most often about eligibility, membership categories and what the Association does for its members.', 'kaao' ); ?>
				</p>
				<div class="cluster mt-6">
					<a class="btn btn--navy" href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>">
						<?php esc_html_e( 'All FAQs', 'kaao' ); ?>
					</a>
					<a class="link-arrow" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						<?php esc_html_e( 'Ask us directly', 'kaao' ); ?>
					</a>
				</div>
			</div>

			<div>
				<?php
				get_template_part(
					'template-parts/sections/faq-accordion',
					null,
					array(
						'topic' => 'membership',
						'limit' => 5,
						'open'  => 1,
						'name'  => __( 'Membership questions', 'kaao' ),
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
