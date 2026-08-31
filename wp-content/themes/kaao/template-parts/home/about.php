<?php
/**
 * About KAAO — two-column introduction.
 *
 * Copy is sourced verbatim from the About Us page on kaao.co.ke.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_image = kaao_attachment_id_by_slug( 'kaao-about-agm-delegates-outside-venue' );
?>
<section class="section" aria-labelledby="about-title">
	<div class="container">
		<div class="split split--wide-text">

			<div>
				<p class="eyebrow"><?php esc_html_e( 'About KAAO', 'kaao' ); ?></p>

				<h2 id="about-title" class="balance">
					<?php esc_html_e( 'A registered national umbrella body for Kenya’s aviation industry', 'kaao' ); ?>
				</h2>

				<p class="lede pretty">
					<?php
					esc_html_e(
						'KAAO is mandated to promote, foster, enhance and protect the interests of those engaged in the aviation industry and allied businesses in Kenya.',
						'kaao'
					);
					?>
				</p>

				<p>
					<?php
					esc_html_e(
						'The Association brings together commercial, private and recreational air operators alongside approved training organisations, approved maintenance organisations, hot air balloon operators and Remotely Piloted Aircraft Systems operators. Associate members include tourism companies, financial institutions, insurance firms, fuel marketers, tax and audit firms, and business associations.',
						'kaao'
					);
					?>
				</p>

				<p>
					<?php
					esc_html_e(
						'We provide a platform that allows members and industry experts to speak with one voice when engaging government, legislators, regulatory bodies, financial institutions, development partners and other stakeholders on policy issues and development plans geared towards the long-term, sustainable growth of the air transport industry.',
						'kaao'
					);
					?>
				</p>

				<div class="cluster mt-6">
					<a class="btn btn--navy" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">
						<?php esc_html_e( 'Discover our story', 'kaao' ); ?>
					</a>
					<a class="link-arrow" href="<?php echo esc_url( home_url( '/leadership/' ) ); ?>">
						<?php esc_html_e( 'Meet our leadership', 'kaao' ); ?>
					</a>
				</div>
			</div>

			<?php if ( $kaao_image ) : ?>
				<figure class="framed" style="margin:0">
					<?php
					echo kaao_image( // phpcs:ignore WordPress.Security.EscapeOutput
						$kaao_image,
						'post-thumbnail',
						array(
							'alt'   => esc_attr__( 'KAAO members and aviation stakeholders at an industry engagement', 'kaao' ),
							'sizes' => '(min-width: 62rem) 34rem, 92vw',
						)
					);
					?>
					<figcaption class="framed__caption">
						<?php esc_html_e( 'KAAO convenes operators, regulators and government around the issues that shape Kenyan aviation.', 'kaao' ); ?>
					</figcaption>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
