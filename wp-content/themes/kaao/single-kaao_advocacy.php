<?php
/**
 * Single advocacy record.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_issue        = kaao_field( 'kaao_advocacy_issue' );
	$kaao_position     = kaao_field( 'kaao_advocacy_position' );
	$kaao_stakeholders = kaao_field( 'kaao_advocacy_stakeholders' );
	$kaao_outcome      = kaao_field( 'kaao_advocacy_outcome' );
	$kaao_status       = kaao_field( 'kaao_advocacy_status' );
	$kaao_date         = kaao_field( 'kaao_advocacy_date' );
	$kaao_doc_id       = (int) kaao_field( 'kaao_advocacy_document' );
	$kaao_theme        = kaao_first_term( 'kaao_advocacy_theme' );
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container">
			<div class="with-sidebar" style="grid-template-columns:1fr 20rem">

				<div>
					<header class="entry-header">
						<div class="cluster" style="margin-bottom:var(--sp-4)">
							<?php kaao_term_pill( $kaao_theme, 'pill--gold' ); ?>
							<?php if ( $kaao_status ) : ?>
								<span class="pill pill--muted"><?php echo esc_html( ucfirst( $kaao_status ) ); ?></span>
							<?php endif; ?>
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
									'alt'           => esc_attr( wp_strip_all_tags( (string) get_the_title() ) ),
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'sizes'         => '(min-width: 64rem) 46rem, 92vw',
								)
							);
							?>
						</figure>
					<?php endif; ?>

					<?php if ( $kaao_position ) : ?>
						<blockquote style="margin:0 0 var(--sp-8);padding:var(--sp-5) var(--sp-6);border-left:3px solid var(--kaao-gold-600);background:var(--kaao-surface);border-radius:0 var(--radius) var(--radius) 0">
							<p class="eyebrow" style="margin-bottom:var(--sp-2)"><?php esc_html_e( 'KAAO position', 'kaao' ); ?></p>
							<p style="margin:0;font-size:var(--fs-lg);color:var(--kaao-primary-900)"><?php echo esc_html( $kaao_position ); ?></p>
						</blockquote>
					<?php endif; ?>

					<div class="prose" style="font-size:var(--fs-base)">
						<?php the_content(); ?>
					</div>

					<?php if ( $kaao_outcome ) : ?>
						<h2 class="h3 mt-8"><?php esc_html_e( 'Outcome', 'kaao' ); ?></h2>
						<p><?php echo esc_html( $kaao_outcome ); ?></p>
					<?php else : ?>
						<h2 class="h3 mt-8"><?php esc_html_e( 'Outcome', 'kaao' ); ?></h2>
						<p class="muted"><?php esc_html_e( 'This matter is still in progress. The outcome will be published once it is confirmed.', 'kaao' ); ?></p>
					<?php endif; ?>

					<p class="mt-8">
						<a class="link-arrow" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_ADVOCACY ) ); ?>">
							<?php esc_html_e( 'Back to advocacy', 'kaao' ); ?>
						</a>
					</p>
				</div>

				<aside>
					<h2 class="h4"><?php esc_html_e( 'At a glance', 'kaao' ); ?></h2>
					<dl class="factlist">
						<?php if ( $kaao_issue ) : ?>
							<div>
								<dt><?php esc_html_e( 'Issue', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_issue ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_date ) : ?>
							<div>
								<dt><?php esc_html_e( 'Date', 'kaao' ); ?></dt>
								<dd>
									<time datetime="<?php echo esc_attr( $kaao_date ); ?>">
										<?php echo esc_html( wp_date( (string) get_option( 'date_format' ), strtotime( $kaao_date ) ) ); ?>
									</time>
								</dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_stakeholders ) : ?>
							<div>
								<dt><?php esc_html_e( 'Stakeholders engaged', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_stakeholders ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_status ) : ?>
							<div>
								<dt><?php esc_html_e( 'Status', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( ucfirst( $kaao_status ) ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>

					<?php if ( $kaao_doc_id ) : ?>
						<p class="mt-6">
							<a class="btn btn--outline btn--block" href="<?php echo esc_url( (string) wp_get_attachment_url( $kaao_doc_id ) ); ?>" download>
								<?php echo kaao_icon( 'download', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Download document', 'kaao' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</article>

<?php
endwhile;

get_footer();
