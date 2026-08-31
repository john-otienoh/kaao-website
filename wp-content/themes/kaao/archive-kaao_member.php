<?php
/**
 * Member directory.
 *
 * Search and filtering are plain GET parameters against the main query, so the
 * directory works without JavaScript, every filtered view is a shareable URL,
 * and the browser back button behaves. JavaScript only removes the need to
 * press "Search".
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

$kaao_search  = get_search_query();
$kaao_active  = kaao_active_member_filters();
$kaao_total   = (int) wp_count_posts( KAAO_CPT_MEMBER )->publish;
$kaao_found   = (int) $GLOBALS['wp_query']->found_posts;
$kaao_archive = get_post_type_archive_link( KAAO_CPT_MEMBER ) ?: home_url( '/members/' );

/**
 * Filter dropdown definitions.
 */
$kaao_filters = array(
	'member_category' => array(
		'taxonomy' => 'kaao_member_category',
		'label'    => __( 'Membership category', 'kaao' ),
		'all'      => __( 'All categories', 'kaao' ),
	),
	'sector'          => array(
		'taxonomy' => 'kaao_sector',
		'label'    => __( 'Aviation sector', 'kaao' ),
		'all'      => __( 'All sectors', 'kaao' ),
	),
	'location'        => array(
		'taxonomy' => 'kaao_location',
		'label'    => __( 'Location', 'kaao' ),
		'all'      => __( 'All locations', 'kaao' ),
	),
);

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow'    => __( 'Our members', 'kaao' ),
		'title'      => __( 'Member directory', 'kaao' ),
		'intro'      => $kaao_total
			? sprintf(
				/* translators: %d: number of members. */
				_n(
					'%d organisation in KAAO membership — commercial, private and recreational operators, and the businesses that support them.',
					'%d organisations in KAAO membership — commercial, private and recreational operators, and the businesses that support them.',
					$kaao_total,
					'kaao'
				),
				$kaao_total
			)
			: __( 'Organisations in KAAO membership.', 'kaao' ),
		'image_slug' => 'kaao-banner-members-kenya-airways-hangar-team',
	)
);

kaao_breadcrumbs();
?>

<div class="section">
	<div class="container">
		<div class="with-sidebar">

			<aside class="with-sidebar__aside">
				<form class="filters" method="get" action="<?php echo esc_url( $kaao_archive ); ?>" data-filter-form>
					<h2><?php esc_html_e( 'Find a member', 'kaao' ); ?></h2>

					<?php // Keeps a ?s= search scoped to members rather than the whole site. ?>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( KAAO_CPT_MEMBER ); ?>">

					<div class="filters__group">
						<label for="member-search"><?php esc_html_e( 'Search members', 'kaao' ); ?></label>
						<input
							type="search"
							id="member-search"
							name="s"
							value="<?php echo esc_attr( $kaao_search ); ?>"
							placeholder="<?php esc_attr_e( 'Organisation name…', 'kaao' ); ?>">
					</div>

					<?php
					foreach ( $kaao_filters as $kaao_param => $kaao_filter ) :
						$kaao_terms = get_terms(
							array(
								'taxonomy'   => $kaao_filter['taxonomy'],
								'hide_empty' => true,
							)
						);
						if ( is_wp_error( $kaao_terms ) || ! $kaao_terms ) {
							continue;
						}
						$kaao_selected = isset( $_GET[ $kaao_param ] ) ? sanitize_title( wp_unslash( $_GET[ $kaao_param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						?>
						<div class="filters__group">
							<label for="filter-<?php echo esc_attr( $kaao_param ); ?>"><?php echo esc_html( $kaao_filter['label'] ); ?></label>
							<select id="filter-<?php echo esc_attr( $kaao_param ); ?>" name="<?php echo esc_attr( $kaao_param ); ?>">
								<option value=""><?php echo esc_html( $kaao_filter['all'] ); ?></option>
								<?php foreach ( $kaao_terms as $kaao_term ) : ?>
									<option value="<?php echo esc_attr( $kaao_term->slug ); ?>" <?php selected( $kaao_selected, $kaao_term->slug ); ?>>
										<?php echo esc_html( $kaao_term->name ); ?> (<?php echo (int) $kaao_term->count; ?>)
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>

					<div class="filters__actions">
						<button type="submit" class="btn btn--navy btn--block"><?php esc_html_e( 'Search', 'kaao' ); ?></button>
						<?php if ( $kaao_active || $kaao_search ) : ?>
							<a class="btn btn--outline btn--block" href="<?php echo esc_url( $kaao_archive ); ?>">
								<?php esc_html_e( 'Clear filters', 'kaao' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</form>

				<p class="mt-6" style="font-size:var(--fs-sm);color:var(--kaao-muted)">
					<?php esc_html_e( 'Is your organisation missing, or is a detail out of date?', 'kaao' ); ?>
					<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Let us know.', 'kaao' ); ?></a>
				</p>
			</aside>

			<div>
				<p class="result-count" role="status">
					<?php
					printf(
						/* translators: 1: number shown, 2: total members. */
						esc_html( _n( 'Showing %1$s of %2$s member.', 'Showing %1$s of %2$s members.', $kaao_total, 'kaao' ) ),
						'<strong>' . esc_html( number_format_i18n( $kaao_found ) ) . '</strong>',
						'<strong>' . esc_html( number_format_i18n( $kaao_total ) ) . '</strong>'
					);
					?>
				</p>

				<?php if ( $kaao_active || $kaao_search ) : ?>
					<div class="active-filters">
						<?php if ( $kaao_search ) : ?>
							<a class="chip" href="<?php echo esc_url( kaao_url_without( 's' ) ); ?>">
								<?php
								printf(
									/* translators: %s: search term. */
									esc_html__( 'Search: %s', 'kaao' ),
									esc_html( $kaao_search )
								);
								?>
								<span class="chip__x" aria-hidden="true">×</span>
								<span class="screen-reader-text"><?php esc_html_e( 'Remove this filter', 'kaao' ); ?></span>
							</a>
						<?php endif; ?>

						<?php foreach ( $kaao_active as $kaao_param => $kaao_filter ) : ?>
							<a class="chip" href="<?php echo esc_url( kaao_url_without( $kaao_param ) ); ?>">
								<?php echo esc_html( $kaao_filter['term']->name ); ?>
								<span class="chip__x" aria-hidden="true">×</span>
								<span class="screen-reader-text"><?php esc_html_e( 'Remove this filter', 'kaao' ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>
					<div class="grid grid--3">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/cards/member' );
						endwhile;
						?>
					</div>

					<?php kaao_pagination(); ?>

				<?php else : ?>
					<div class="empty-state">
						<h2><?php esc_html_e( 'No members match those filters', 'kaao' ); ?></h2>
						<p><?php esc_html_e( 'Try removing a filter or searching for a shorter part of the organisation name.', 'kaao' ); ?></p>
						<p>
							<a class="btn btn--navy" href="<?php echo esc_url( $kaao_archive ); ?>">
								<?php esc_html_e( 'Show all members', 'kaao' ); ?>
							</a>
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
