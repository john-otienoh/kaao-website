<?php
/**
 * Single resource.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$kaao_file_id = (int) kaao_field( 'kaao_resource_file' );
	$kaao_link    = kaao_field( 'kaao_resource_link' );
	$kaao_year    = kaao_field( 'kaao_resource_year' );
	$kaao_type    = kaao_first_term( 'kaao_resource_type' );

	$kaao_url   = $kaao_file_id ? (string) wp_get_attachment_url( $kaao_file_id ) : $kaao_link;
	$kaao_path  = $kaao_file_id ? (string) get_attached_file( $kaao_file_id ) : '';
	$kaao_bytes = ( $kaao_path && file_exists( $kaao_path ) ) ? filesize( $kaao_path ) : 0;
	$kaao_ext   = $kaao_url ? strtoupper( (string) pathinfo( (string) wp_parse_url( $kaao_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) ) : '';
	?>

	<?php kaao_breadcrumbs(); ?>

	<article class="section">
		<div class="container container--narrow">

			<header class="entry-header">
				<div class="cluster" style="margin-bottom:var(--sp-4)">
					<?php kaao_term_pill( $kaao_type, 'pill--gold' ); ?>
					<?php if ( $kaao_year ) : ?>
						<span class="pill pill--muted"><?php echo esc_html( $kaao_year ); ?></span>
					<?php endif; ?>
				</div>

				<h1 class="balance"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="lede pretty"><?php echo esc_html( (string) get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( $kaao_url ) : ?>
				<div class="resource-card" style="margin-bottom:var(--sp-8)">
					<div class="resource-card__icon" aria-hidden="true"><?php echo esc_html( $kaao_ext ?: 'DOC' ); ?></div>
					<div class="resource-card__body">
						<p style="margin:0 0 .5rem;font-weight:650">
							<?php echo esc_html( wp_strip_all_tags( (string) get_the_title() ) ); ?>
						</p>
						<p style="margin:0 0 .75rem" class="muted">
							<?php
							echo esc_html(
								trim(
									implode(
										' · ',
										array_filter(
											array(
												$kaao_ext,
												$kaao_bytes ? size_format( $kaao_bytes, 1 ) : '',
												$kaao_year,
											)
										)
									)
								)
							);
							?>
						</p>
						<a class="btn btn--primary btn--sm" href="<?php echo esc_url( $kaao_url ); ?>"
							<?php echo $kaao_file_id ? ' download' : ' target="_blank" rel="noopener noreferrer"'; ?>>
							<?php echo kaao_icon( 'download', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php $kaao_file_id ? esc_html_e( 'Download', 'kaao' ) : esc_html_e( 'Open resource', 'kaao' ); ?>
						</a>
					</div>
				</div>
			<?php else : ?>
				<?php kaao_to_confirm( __( 'Document file', 'kaao' ) ); ?>
			<?php endif; ?>

			<div class="prose">
				<?php the_content(); ?>
			</div>

			<p class="mt-8">
				<a class="link-arrow" href="<?php echo esc_url( (string) get_post_type_archive_link( KAAO_CPT_RESOURCE ) ); ?>">
					<?php esc_html_e( 'Back to the Resource Centre', 'kaao' ); ?>
				</a>
			</p>
		</div>
	</article>

<?php
endwhile;

get_footer();
