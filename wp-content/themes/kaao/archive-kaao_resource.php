<?php
/**
 * Resource Centre.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_archive = get_post_type_archive_link( KAAO_CPT_RESOURCE ) ?: home_url( '/resources/' );
$kaao_year    = isset( $_GET['year'] ) ? (int) $_GET['year'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$kaao_found   = (int) $GLOBALS['wp_query']->found_posts;

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'Resource centre', 'kaao' ),
		'title'      => is_tax() ? wp_strip_all_tags( get_the_archive_title() ) : __( 'Resources', 'kaao' ),
		'intro'      => __( 'Reports, publications, position papers, membership forms and regulatory references — in one place.', 'kaao' ),
		'image_slug' => 'kaao-banner-resources-industry-affairs-meeting',
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container">
		<div class="with-sidebar">

			<aside class="with-sidebar__aside">
				<nav class="filters" aria-labelledby="resource-filter-title">
					<h2 id="resource-filter-title"><?php esc_html_e( 'Browse by type', 'kaao' ); ?></h2>

					<ul style="list-style:none;margin:0 0 var(--sp-5);padding:0;display:grid;gap:var(--sp-1)">
						<li style="margin:0">
							<a href="<?php echo esc_url( $kaao_archive ); ?>"
								style="display:block;padding:.5rem .625rem;border-radius:var(--radius-sm);text-decoration:none;font-size:var(--fs-sm);<?php echo is_post_type_archive() ? 'background:var(--kaao-primary-050);font-weight:650' : ''; ?>">
								<?php esc_html_e( 'All resources', 'kaao' ); ?>
							</a>
						</li>
						<?php
						$kaao_types = get_terms(
							array(
								'taxonomy'   => 'kaao_resource_type',
								'hide_empty' => true,
							)
						);
						if ( ! is_wp_error( $kaao_types ) ) :
							foreach ( $kaao_types as $kaao_type ) :
								$kaao_current = is_tax( 'kaao_resource_type', $kaao_type->term_id );
								?>
								<li style="margin:0">
									<a href="<?php echo esc_url( (string) get_term_link( $kaao_type ) ); ?>"
										<?php echo $kaao_current ? ' aria-current="page"' : ''; ?>
										style="display:flex;justify-content:space-between;gap:.5rem;padding:.5rem .625rem;border-radius:var(--radius-sm);text-decoration:none;font-size:var(--fs-sm);<?php echo $kaao_current ? 'background:var(--kaao-primary-050);font-weight:650' : ''; ?>">
										<span><?php echo esc_html( $kaao_type->name ); ?></span>
										<span class="muted"><?php echo (int) $kaao_type->count; ?></span>
									</a>
								</li>
								<?php
							endforeach;
						endif;
						?>
					</ul>

					<?php
					// Year facet, built from the values actually in use.
					global $wpdb;
					$kaao_years = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$wpdb->prepare(
							"SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
							 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
							 WHERE pm.meta_key = %s AND p.post_type = %s AND p.post_status = 'publish'
							   AND pm.meta_value <> ''
							 ORDER BY meta_value DESC",
							'kaao_resource_year',
							KAAO_CPT_RESOURCE
						)
					);
					if ( $kaao_years ) :
						?>
						<form method="get" action="<?php echo esc_url( $kaao_archive ); ?>" data-filter-form>
							<div class="filters__group">
								<label for="resource-year"><?php esc_html_e( 'Year', 'kaao' ); ?></label>
								<select id="resource-year" name="year">
									<option value=""><?php esc_html_e( 'All years', 'kaao' ); ?></option>
									<?php foreach ( $kaao_years as $kaao_y ) : ?>
										<option value="<?php echo esc_attr( $kaao_y ); ?>" <?php selected( (string) $kaao_year, (string) $kaao_y ); ?>>
											<?php echo esc_html( $kaao_y ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="filters__actions">
								<button type="submit" class="btn btn--navy btn--block btn--sm"><?php esc_html_e( 'Filter', 'kaao' ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				</nav>
			</aside>

			<div>
				<p class="result-count" role="status">
					<?php
					printf(
						/* translators: %s: number of resources. */
						esc_html( _n( '%s resource', '%s resources', $kaao_found, 'kaao' ) ),
						'<strong>' . esc_html( number_format_i18n( $kaao_found ) ) . '</strong>'
					);
					?>
				</p>

				<?php if ( have_posts() ) : ?>
					<div class="grid" style="gap:var(--sp-3)">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/cards/resource' );
						endwhile;
						?>
					</div>

					<?php kaao_pagination(); ?>
				<?php else : ?>
					<div class="empty-state">
						<h2><?php esc_html_e( 'No resources in this view', 'kaao' ); ?></h2>
						<p><?php esc_html_e( 'Documents are added to the Resource Centre as they are published.', 'kaao' ); ?></p>
						<p><a class="btn btn--navy" href="<?php echo esc_url( $kaao_archive ); ?>"><?php esc_html_e( 'Show all resources', 'kaao' ); ?></a></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
