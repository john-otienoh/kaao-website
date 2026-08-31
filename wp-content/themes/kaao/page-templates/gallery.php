<?php
/**
 * Template Name: Gallery
 * Template Post Type: page
 *
 * A photographic record of the Association's work, built from the images
 * already attached to events and news articles. Photographs are grouped into
 * sliders of ten and shown on their own — they are not links back to the
 * entry they came from, so this page reads as a gallery rather than a list
 * of events. Entries still carrying the shared placeholder image (posts
 * awaiting a real photograph) are left out until one is set.
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
			'eyebrow'    => __( 'Gallery', 'kaao' ),
			'title'      => (string) get_the_title(),
			'intro'      => get_the_excerpt() ?: __( 'Photographs from KAAO events, engagements and industry forums.', 'kaao' ),
			'image_id'   => (int) get_post_thumbnail_id(),
			'image_slug' => 'kaao-hero-kaao-delegates-group-photo',
		)
	);

	kaao_breadcrumbs();

	$kaao_gallery_url  = (string) get_permalink();
	$kaao_per_slide    = 10;
	$kaao_placeholder  = kaao_attachment_id_by_slug( 'kaao-about-agm-delegates-outside-venue' );

	// Read-only public filter: which source to show.
	$kaao_in = isset( $_GET['in'] ) ? sanitize_key( wp_unslash( $_GET['in'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$kaao_sources = array(
		''       => __( 'All photographs', 'kaao' ),
		'events' => __( 'Events & engagements', 'kaao' ),
		'news'   => __( 'News & insights', 'kaao' ),
	);
	if ( ! isset( $kaao_sources[ $kaao_in ] ) ) {
		$kaao_in = '';
	}

	$kaao_types = match ( $kaao_in ) {
		'events' => array( KAAO_CPT_EVENT ),
		'news'   => array( 'post' ),
		default  => array( KAAO_CPT_EVENT, 'post' ),
	};

	$kaao_meta_query = array( 'relation' => 'AND', array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	if ( $kaao_placeholder ) {
		$kaao_meta_query[] = array(
			'key'     => '_thumbnail_id',
			'value'   => (string) $kaao_placeholder,
			'compare' => '!=',
		);
	}

	$kaao_photos = new WP_Query(
		array(
			'post_type'           => $kaao_types,
			'post_status'         => 'publish',
			'posts_per_page'      => 60,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'meta_query'          => $kaao_meta_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		)
	);

	$kaao_date_fmt = (string) get_option( 'date_format', 'j F Y' );
	$kaao_slides   = array_chunk( $kaao_photos->posts, $kaao_per_slide );
	?>

	<div class="section">
		<div class="container">

			<?php if ( get_the_content() ) : ?>
				<div class="prose" style="margin-bottom:var(--sp-8)">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<ul class="tabs">
				<?php foreach ( $kaao_sources as $kaao_key => $kaao_label ) : ?>
					<?php
					$kaao_url     = $kaao_key ? add_query_arg( 'in', $kaao_key, $kaao_gallery_url ) : $kaao_gallery_url;
					$kaao_current = $kaao_in === $kaao_key;
					?>
					<li<?php echo $kaao_current ? ' class="is-current"' : ''; ?>>
						<a href="<?php echo esc_url( $kaao_url ); ?>"<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $kaao_label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $kaao_slides ) : ?>
				<h2 class="screen-reader-text"><?php echo esc_html( $kaao_sources[ $kaao_in ] ); ?></h2>

				<p class="result-count">
					<?php
					printf(
						/* translators: %s: number of photographs. */
						esc_html( _n( '%s photograph', '%s photographs', count( $kaao_photos->posts ), 'kaao' ) ),
						'<strong>' . esc_html( number_format_i18n( count( $kaao_photos->posts ) ) ) . '</strong>'
					);
					?>
				</p>

				<div class="photo-slider" data-photo-slider>
					<div class="photo-slider__viewport">
						<?php foreach ( $kaao_slides as $kaao_i => $kaao_slide ) : ?>
							<div class="photo-slider__slide<?php echo 0 === $kaao_i ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $kaao_i ? 'false' : 'true'; ?>">
								<div class="photo-grid">
									<?php foreach ( $kaao_slide as $kaao_post ) : ?>
										<?php
										$kaao_source_label = KAAO_CPT_EVENT === $kaao_post->post_type
											? __( 'Event', 'kaao' )
											: __( 'News', 'kaao' );
										?>
										<figure class="photo">
											<span class="photo__media">
												<?php
												echo kaao_image( // phpcs:ignore WordPress.Security.EscapeOutput
													(int) get_post_thumbnail_id( $kaao_post ),
													'kaao-card',
													array(
														'alt'   => '',
														'sizes' => '(min-width: 64rem) 20rem, (min-width: 40rem) 45vw, 92vw',
													)
												);
												?>
											</span>
											<figcaption class="photo__caption">
												<span class="photo__title"><?php echo esc_html( get_the_title( $kaao_post ) ); ?></span>
												<span class="photo__meta">
													<?php echo esc_html( $kaao_source_label ); ?>
													<span aria-hidden="true">·</span>
													<?php echo esc_html( get_the_date( $kaao_date_fmt, $kaao_post ) ); ?>
												</span>
											</figcaption>
										</figure>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $kaao_slides ) > 1 ) : ?>
						<div class="photo-slider__controls">
							<button type="button" class="kaao-iconbtn photo-slider__prev">
								<span class="screen-reader-text"><?php esc_html_e( 'Previous photographs', 'kaao' ); ?></span>
								<?php echo kaao_icon( 'arrow-right', array( 'style' => 'transform:rotate(180deg)' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</button>

							<ul class="photo-slider__dots">
								<?php foreach ( $kaao_slides as $kaao_i => $kaao_slide ) : ?>
									<li>
										<button type="button" class="photo-slider__dot" aria-current="<?php echo 0 === $kaao_i ? 'true' : 'false'; ?>">
											<span class="screen-reader-text">
												<?php
												printf(
													/* translators: %s: slide number. */
													esc_html__( 'Photographs, page %s', 'kaao' ),
													esc_html( (string) ( $kaao_i + 1 ) )
												);
												?>
											</span>
										</button>
									</li>
								<?php endforeach; ?>
							</ul>

							<button type="button" class="kaao-iconbtn photo-slider__next">
								<span class="screen-reader-text"><?php esc_html_e( 'More photographs', 'kaao' ); ?></span>
								<?php echo kaao_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</button>
						</div>
					<?php endif; ?>
				</div>

			<?php else : ?>
				<div class="empty-state">
					<h2><?php esc_html_e( 'No photographs in this view', 'kaao' ); ?></h2>
					<p><?php esc_html_e( 'Photographs appear here as events and articles are published with a featured image.', 'kaao' ); ?></p>
					<p>
						<a class="btn btn--navy" href="<?php echo esc_url( $kaao_gallery_url ); ?>">
							<?php esc_html_e( 'Show all photographs', 'kaao' ); ?>
						</a>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	get_template_part( 'template-parts/home/cta' );

endwhile;

get_footer();
