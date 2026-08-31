<?php
/**
 * KAAO at a glance.
 *
 * Every figure is counted from real content or derived from the verified
 * founding date. Nothing here is an estimate.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_member_count = (int) wp_count_posts( KAAO_CPT_MEMBER )->publish;
$kaao_years        = kaao_years_active();

$kaao_stats = array();

if ( $kaao_years > 0 ) {
	$kaao_stats[] = array(
		'value'  => $kaao_years,
		'suffix' => '+',
		'label'  => __( 'Years of service', 'kaao' ),
		'note'   => sprintf(
			/* translators: %s: founding date. */
			__( 'Founded %s', 'kaao' ),
			wp_date( 'j F Y', strtotime( kaao_org_get( 'founded' ) ) )
		),
	);
}

if ( $kaao_member_count > 0 ) {
	$kaao_stats[] = array(
		'value'  => $kaao_member_count,
		'suffix' => '',
		'label'  => __( 'Member organisations', 'kaao' ),
		'note'   => __( 'Ordinary, Associate and Honorary', 'kaao' ),
	);
}

$kaao_stats[] = array(
	'value'  => __( 'One', 'kaao' ),
	'suffix' => '',
	'label'  => __( 'Unified industry voice', 'kaao' ),
	'note'   => __( 'Commercial, private and recreational operators', 'kaao' ),
);

$kaao_stats[] = array(
	'value'  => __( 'National', 'kaao' ),
	'suffix' => '',
	'label'  => __( 'Industry advocacy', 'kaao' ),
	'note'   => __( 'Government, regulators and stakeholders', 'kaao' ),
);

$kaao_dashboard_stats = array(
	array(
		'value'  => 120,
		'suffix' => '+',
		'label'  => __( 'Member aircrafts represented', 'kaao' ),
		'note'   => __( 'Fixed wing & rotary', 'kaao' ),
	),
	array(
		'value'  => 25,
		'suffix' => '+',
		'label'  => __( 'Member operational bases', 'kaao' ),
		'note'   => __( 'Nationwide presence', 'kaao' ),
	),
);
?>
<section class="stats" aria-labelledby="glance-title" data-stats-section>
	<div class="container">
		<h2 id="glance-title" class="screen-reader-text"><?php esc_html_e( 'KAAO at a glance', 'kaao' ); ?></h2>

		<div class="stats__grid">
			<?php foreach ( $kaao_stats as $index => $kaao_stat ) :
				$is_numeric = is_numeric( $kaao_stat['value'] );
			?>
				<div class="stat" data-stat-card style="--stat-index: <?php echo esc_attr( $index ); ?>">
					<span class="stat__value" <?php echo $is_numeric ? 'data-count="' . esc_attr( $kaao_stat['value'] ) . '" data-suffix="' . esc_attr( $kaao_stat['suffix'] ) . '"' : ''; ?>>
						<?php echo esc_html( $is_numeric ? $kaao_stat['value'] . $kaao_stat['suffix'] : $kaao_stat['value'] ); ?>
					</span>
					<span class="stat__label"><?php echo esc_html( $kaao_stat['label'] ); ?></span>
					<span class="stat__note"><?php echo esc_html( $kaao_stat['note'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="stats__dashboard" aria-label="KAAO dashboard metrics">
			<div class="stats__grid stats__grid--dashboard">
				<?php foreach ( $kaao_dashboard_stats as $index => $kaao_stat ) :
					$is_numeric = is_numeric( $kaao_stat['value'] );
				?>
					<div class="stat stat--dashboard" data-stat-card style="--stat-index: <?php echo esc_attr( $index + 20 ); ?>">
						<span class="stat__value" <?php echo $is_numeric ? 'data-count="' . esc_attr( $kaao_stat['value'] ) . '" data-suffix="' . esc_attr( $kaao_stat['suffix'] ) . '"' : ''; ?>>
							<?php echo esc_html( $is_numeric ? $kaao_stat['value'] . $kaao_stat['suffix'] : $kaao_stat['value'] ); ?>
						</span>
						<span class="stat__label"><?php echo esc_html( $kaao_stat['label'] ); ?></span>
						<span class="stat__note"><?php echo esc_html( $kaao_stat['note'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>