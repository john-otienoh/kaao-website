<?php
/**
 * Single event.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_start    = kaao_field( 'kaao_event_start' );
	$kaao_time     = kaao_field( 'kaao_event_time' );
	$kaao_location = kaao_field( 'kaao_event_location' );
	$kaao_host     = kaao_field( 'kaao_event_host' );
	$kaao_register = kaao_field( 'kaao_event_register' );
	$kaao_doc_id   = (int) kaao_field( 'kaao_event_document' );
	$kaao_type     = kaao_first_term( 'kaao_event_type' );
	$kaao_upcoming = kaao_event_is_upcoming();
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container">
			<div class="with-sidebar" style="grid-template-columns:1fr 20rem">

				<div>
					<header class="entry-header">
						<div class="cluster" style="margin-bottom:var(--sp-4)">
							<?php if ( $kaao_upcoming ) : ?>
								<span class="pill pill--gold"><?php esc_html_e( 'Upcoming', 'kaao' ); ?></span>
							<?php endif; ?>
							<?php kaao_term_pill( $kaao_type ); ?>
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

					<div class="prose" style="font-size:var(--fs-base)">
						<?php the_content(); ?>
					</div>

					<p class="mt-8">
						<a class="link-arrow" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_EVENT ) ); ?>">
							<?php esc_html_e( 'Back to all events', 'kaao' ); ?>
						</a>
					</p>
				</div>

				<aside>
					<h2 class="h4"><?php esc_html_e( 'Event details', 'kaao' ); ?></h2>
					<dl class="factlist">
						<?php if ( $kaao_start ) : ?>
							<div>
								<dt><?php esc_html_e( 'Date', 'kaao' ); ?></dt>
								<dd>
									<time datetime="<?php echo esc_attr( $kaao_start ); ?>">
										<?php echo esc_html( kaao_event_date_range() ); ?>
									</time>
								</dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_time ) : ?>
							<div>
								<dt><?php esc_html_e( 'Time', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_time ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_location ) : ?>
							<div>
								<dt><?php esc_html_e( 'Location', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_location ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_type ) : ?>
							<div>
								<dt><?php esc_html_e( 'Event type', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_type->name ); ?></dd>
							</div>
						<?php endif; ?>

						<div>
							<dt><?php esc_html_e( 'Hosted by', 'kaao' ); ?></dt>
							<dd><?php echo esc_html( $kaao_host ?: kaao_org_get( 'legal_name' ) ); ?></dd>
						</div>
					</dl>

					<?php if ( $kaao_register ) : ?>
						<p class="mt-6">
							<a class="btn btn--primary btn--block" href="<?php echo esc_url( $kaao_register ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Register', 'kaao' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'kaao' ); ?></span>
							</a>
						</p>
					<?php endif; ?>

					<?php if ( $kaao_doc_id ) : ?>
						<p class="mt-4">
							<a class="btn btn--outline btn--block" href="<?php echo esc_url( (string) wp_get_attachment_url( $kaao_doc_id ) ); ?>" download>
								<?php echo kaao_icon( 'download', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Download programme', 'kaao' ); ?>
							</a>
						</p>
					<?php endif; ?>

					<?php if ( ! $kaao_start ) : ?>
						<?php kaao_to_confirm( __( 'Event date', 'kaao' ) ); ?>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</article>

	<?php
	$kaao_related = kaao_get_posts(
		KAAO_CPT_EVENT,
		3,
		array(
			'post__not_in' => array( get_the_ID() ),
			'meta_key'     => 'kaao_event_start', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'      => array( 'meta_value' => 'DESC' ),
		)
	);

	if ( $kaao_related->have_posts() ) :
		?>
		<section class="section section--surface" aria-labelledby="related-events">
			<div class="container">
				<?php
				kaao_section_head(
					array(
						'eyebrow'   => __( 'More engagements', 'kaao' ),
						'title'     => __( 'Recent events', 'kaao' ),
						'link'      => (string) get_post_type_archive_link( KAAO_CPT_EVENT ),
						'link_text' => __( 'All events', 'kaao' ),
						'id'        => 'related-events',
					)
				);
				?>
				<div class="grid grid--3">
					<?php
					while ( $kaao_related->have_posts() ) :
						$kaao_related->the_post();
						get_template_part( 'template-parts/cards/event' );
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
