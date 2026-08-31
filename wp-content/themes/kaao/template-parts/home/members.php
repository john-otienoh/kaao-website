<?php
/**
 * Member showcase.
 *
 * Pulls featured members dynamically — no member is hard-coded here. Falls
 * back to the most recently added members if nothing has been flagged.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_members = kaao_get_posts(
	KAAO_CPT_MEMBER,
	12,
	array(
		'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'kaao_featured',
				'value'   => '1',
				'compare' => '=',
			),
		),
		'orderby'     => array( 'title' => 'ASC' ),
		'post_status' => 'publish',
	)
);

if ( ! $kaao_members->have_posts() ) {
	$kaao_members = kaao_get_posts(
		KAAO_CPT_MEMBER,
		12,
		array(
			'orderby'    => array( 'date' => 'DESC' ),
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		)
	);
}

$kaao_total = (int) wp_count_posts( KAAO_CPT_MEMBER )->publish;

if ( $kaao_members->have_posts() ) :
	?>
	<section class="section section--surface" aria-labelledby="members-title">
		<div class="container">
			<?php
			kaao_section_head(
				array(
					'eyebrow'   => __( 'Our members', 'kaao' ),
					'title'     => __( 'The organisations that make up KAAO', 'kaao' ),
					'intro'     => $kaao_total
						? sprintf(
							/* translators: %d: number of member organisations. */
							_n(
								'%d organisation across commercial, private and recreational aviation and the businesses that support them.',
								'%d organisations across commercial, private and recreational aviation and the businesses that support them.',
								$kaao_total,
								'kaao'
							),
							$kaao_total
						)
						: '',
					'link'      => home_url( '/members/' ),
					'link_text' => __( 'View member directory', 'kaao' ),
					'id'        => 'members-title',
				)
			);
			?>

			<ul class="grid grid--logos" style="list-style:none;margin:0;padding:0">
				<?php
				while ( $kaao_members->have_posts() ) :
					$kaao_members->the_post();
					?>
					<li style="margin:0">
						<a class="logo-tile" href="<?php the_permalink(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								echo kaao_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput
									'kaao-logo',
									array(
										'alt'   => esc_attr( wp_strip_all_tags( (string) get_the_title() ) ),
										'sizes' => '200px',
									)
								);
							} else {
								printf(
									'<span style="font-weight:700;color:var(--kaao-primary-600);text-align:center;font-size:var(--fs-sm)">%s</span>',
									esc_html( wp_strip_all_tags( (string) get_the_title() ) )
								);
							}
							?>
						</a>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>

			<p class="mt-8" style="margin-bottom:0">
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/members/' ) ); ?>">
					<?php esc_html_e( 'View member directory', 'kaao' ); ?>
				</a>
			</p>
		</div>
	</section>
	<?php
endif;
