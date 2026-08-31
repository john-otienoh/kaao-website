<?php
/**
 * Single vacancy.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_careers     = get_page_by_path( 'careers' );
$kaao_careers_url = $kaao_careers ? (string) get_permalink( $kaao_careers ) : home_url( '/careers/' );

while ( have_posts() ) :
	the_post();

	$kaao_location = kaao_field( 'kaao_vacancy_location' );
	$kaao_deadline = kaao_field( 'kaao_vacancy_deadline' );
	$kaao_type     = kaao_field_choice( KAAO_CPT_VACANCY, 'kaao_vacancy_type', kaao_field( 'kaao_vacancy_type' ) );
	$kaao_link     = kaao_field( 'kaao_vacancy_link' );
	$kaao_email    = kaao_field( 'kaao_vacancy_email' ) ?: kaao_org_get( 'email' );
	$kaao_doc_id   = (int) kaao_field( 'kaao_vacancy_document' );
	$kaao_open     = kaao_vacancy_is_open();
	$kaao_date_fmt = (string) get_option( 'date_format', 'j F Y' );
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container">
			<div class="with-sidebar" style="grid-template-columns:1fr 20rem">

				<div>
					<header class="entry-header">
						<div class="cluster" style="margin-bottom:var(--sp-4)">
							<?php if ( $kaao_open ) : ?>
								<span class="pill pill--gold"><?php esc_html_e( 'Open', 'kaao' ); ?></span>
							<?php else : ?>
								<span class="pill pill--muted"><?php esc_html_e( 'Closed', 'kaao' ); ?></span>
							<?php endif; ?>

							<?php if ( $kaao_type ) : ?>
								<span class="pill"><?php echo esc_html( $kaao_type ); ?></span>
							<?php endif; ?>
						</div>

						<h1 class="balance"><?php the_title(); ?></h1>

						<?php if ( has_excerpt() ) : ?>
							<p class="lede pretty"><?php echo esc_html( (string) get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</header>

					<?php if ( ! $kaao_open ) : ?>
						<p class="notice">
							<?php esc_html_e( 'This advertisement has closed and applications are no longer being accepted.', 'kaao' ); ?>
						</p>
					<?php endif; ?>

					<div class="prose" style="font-size:var(--fs-base)">
						<?php the_content(); ?>
					</div>

					<p class="mt-8">
						<a class="link-arrow" href="<?php echo esc_url( $kaao_careers_url ); ?>">
							<?php esc_html_e( 'Back to careers', 'kaao' ); ?>
						</a>
					</p>
				</div>

				<aside>
					<h2 class="h4"><?php esc_html_e( 'Position details', 'kaao' ); ?></h2>
					<dl class="factlist">
						<?php if ( $kaao_location ) : ?>
							<div>
								<dt><?php esc_html_e( 'Location', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_location ); ?></dd>
							</div>
						<?php endif; ?>

						<?php if ( $kaao_type ) : ?>
							<div>
								<dt><?php esc_html_e( 'Engagement', 'kaao' ); ?></dt>
								<dd><?php echo esc_html( $kaao_type ); ?></dd>
							</div>
						<?php endif; ?>

						<div>
							<dt><?php esc_html_e( 'Closing date', 'kaao' ); ?></dt>
							<dd>
								<?php if ( $kaao_deadline ) : ?>
									<time datetime="<?php echo esc_attr( $kaao_deadline ); ?>">
										<?php echo esc_html( wp_date( $kaao_date_fmt, strtotime( $kaao_deadline ) ) ); ?>
									</time>
								<?php else : ?>
									<?php esc_html_e( 'Open until filled', 'kaao' ); ?>
								<?php endif; ?>
							</dd>
						</div>

						<div>
							<dt><?php esc_html_e( 'Advertised by', 'kaao' ); ?></dt>
							<dd><?php echo esc_html( kaao_org_get( 'legal_name' ) ); ?></dd>
						</div>
					</dl>

					<?php if ( $kaao_open && $kaao_link ) : ?>
						<p class="mt-6">
							<a class="btn btn--primary btn--block" href="<?php echo esc_url( $kaao_link ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Apply online', 'kaao' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'kaao' ); ?></span>
							</a>
						</p>
					<?php endif; ?>

					<?php if ( $kaao_open && $kaao_email ) : ?>
						<p class="<?php echo $kaao_link ? 'mt-4' : 'mt-6'; ?>">
							<a class="btn <?php echo $kaao_link ? 'btn--outline' : 'btn--primary'; ?> btn--block" href="mailto:<?php echo esc_attr( $kaao_email ); ?>?subject=<?php echo esc_attr( rawurlencode( wp_strip_all_tags( (string) get_the_title() ) ) ); ?>">
								<?php echo kaao_icon( 'mail', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Apply by email', 'kaao' ); ?>
							</a>
						</p>
					<?php endif; ?>

					<?php if ( $kaao_doc_id ) : ?>
						<p class="mt-4">
							<a class="btn btn--outline btn--block" href="<?php echo esc_url( (string) wp_get_attachment_url( $kaao_doc_id ) ); ?>" download>
								<?php echo kaao_icon( 'download', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php esc_html_e( 'Download job description', 'kaao' ); ?>
							</a>
						</p>
					<?php endif; ?>

					<?php if ( ! $kaao_open ) : ?>
						<p class="mt-6">
							<a class="btn btn--navy btn--block" href="<?php echo esc_url( $kaao_careers_url ); ?>">
								<?php esc_html_e( 'See current vacancies', 'kaao' ); ?>
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
