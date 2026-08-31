<?php
/**
 * Single leadership profile.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_position     = kaao_field( 'kaao_leader_position' );
	$kaao_organisation = kaao_field( 'kaao_leader_organisation' );
	$kaao_linkedin     = kaao_field( 'kaao_leader_linkedin' );
	$kaao_group        = kaao_first_term( 'kaao_leader_group' );
	$kaao_name         = wp_strip_all_tags( (string) get_the_title() );
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container">
			<div class="split" style="align-items:start">

				<div>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure style="margin:0;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md);max-width:26rem">
							<?php
							echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
								'kaao-portrait',
								array(
									'alt'           => esc_attr(
										sprintf(
											/* translators: 1: name, 2: position. */
											__( '%1$s, %2$s', 'kaao' ),
											$kaao_name,
											$kaao_position ?: __( 'KAAO leadership', 'kaao' )
										)
									),
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'sizes'         => '(min-width: 62rem) 26rem, 92vw',
								)
							);
							?>
						</figure>
					<?php endif; ?>
				</div>

				<div>
					<?php if ( $kaao_group ) : ?>
						<p class="eyebrow"><?php echo esc_html( $kaao_group->name ); ?></p>
					<?php endif; ?>

					<h1 class="balance" style="margin-bottom:var(--sp-2)"><?php the_title(); ?></h1>

					<?php if ( $kaao_position ) : ?>
						<p style="margin:0 0 var(--sp-2);font-size:var(--fs-lg);font-weight:650;color:var(--kaao-gold-700)">
							<?php echo esc_html( $kaao_position ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $kaao_organisation ) : ?>
						<p class="muted" style="margin-bottom:var(--sp-6)"><?php echo esc_html( $kaao_organisation ); ?></p>
					<?php endif; ?>

					<?php if ( get_the_content() ) : ?>
						<div class="prose" style="font-size:var(--fs-base);max-width:none">
							<?php the_content(); ?>
						</div>
					<?php else : ?>
						<?php kaao_to_confirm( __( 'Biography', 'kaao' ) ); ?>
					<?php endif; ?>

					<div class="cluster mt-8">
						<?php if ( $kaao_linkedin ) : ?>
							<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $kaao_linkedin ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo kaao_icon( 'linkedin', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'LinkedIn profile', 'kaao' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'kaao' ); ?></span>
							</a>
						<?php endif; ?>

						<a class="link-arrow" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_LEADER ) ); ?>">
							<?php esc_html_e( 'Back to leadership', 'kaao' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</article>

<?php
endwhile;

get_footer();
