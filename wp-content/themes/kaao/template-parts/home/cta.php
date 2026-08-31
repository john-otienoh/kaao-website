<?php
/**
 * Closing call to action.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_image = kaao_attachment_id_by_slug( 'kaao-cta-kenya-airways-787-in-flight' );
?>
<section class="cta-band" aria-labelledby="cta-title">
	<?php if ( $kaao_image ) : ?>
		<div class="cta-band__media">
			<?php
			echo kaao_image( // phpcs:ignore WordPress.Security.EscapeOutput
				$kaao_image,
				'kaao-banner',
				array(
					'alt'   => '',
					'sizes' => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="container cta-band__inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Join the association', 'kaao' ); ?></p>
			<h2 id="cta-title"><?php esc_html_e( 'A stronger digital KAAO for a stronger aviation industry', 'kaao' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Join the Association and reap the benefits of being part of an aviation community that gets your concerns addressed.',
					'kaao'
				);
				?>
			</p>
		</div>

		<div class="cta-band__actions">
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/member-registration/' ) ); ?>">
				<?php esc_html_e( 'Apply for membership', 'kaao' ); ?>
			</a>
			<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
				<?php esc_html_e( 'Talk to us', 'kaao' ); ?>
			</a>
		</div>
	</div>
</section>
