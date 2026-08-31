<?php
/**
 * What KAAO does — six cards.
 *
 * Each card restates one of the Association's published objectives from the
 * About Us page. No capability is claimed that is not on the record.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_pillars = array(
	array(
		'icon'  => 'megaphone',
		'title' => __( 'Advocacy & policy', 'kaao' ),
		'text'  => __( 'Representing industry interests on policies and regulations, and working to influence policy on behalf of members.', 'kaao' ),
		'link'  => '/advocacy/',
	),
	array(
		'icon'  => 'users',
		'title' => __( 'Industry representation', 'kaao' ),
		'text'  => __( 'Providing one unified voice for the industry, and collective representation for members whose individual concerns are shared.', 'kaao' ),
		'link'  => '/advocacy/',
	),
	array(
		'icon'  => 'shield',
		'title' => __( 'Member support', 'kaao' ),
		'text'  => __( 'Easy access to professional advice, and self-regulation through a shared code of conduct and ethics.', 'kaao' ),
		'link'  => '/membership/benefits/',
	),
	array(
		'icon'  => 'handshake',
		'title' => __( 'Industry engagement', 'kaao' ),
		'text'  => __( 'Direct access to Government and Government agencies, and liaison with other organisations across the sector.', 'kaao' ),
		'link'  => '/events/',
	),
	array(
		'icon'  => 'book',
		'title' => __( 'Knowledge sharing', 'kaao' ),
		'text'  => __( 'Events, groups, meetings and forums that let members exchange information and learn from one another.', 'kaao' ),
		'link'  => '/resources/',
	),
	array(
		'icon'  => 'chart',
		'title' => __( 'Industry development', 'kaao' ),
		'text'  => __( 'Provision of fora to discuss and shape the industry, supporting a safe, efficient and sustainable aviation sector.', 'kaao' ),
		'link'  => '/about-us/mission-vision-objectives/',
	),
);
?>
<section class="section section--surface" aria-labelledby="what-title">
	<div class="container">
		<?php
		kaao_section_head(
			array(
				'eyebrow' => __( 'What we do', 'kaao' ),
				'title'   => __( 'Working for a safe, efficient and sustainable aviation industry', 'kaao' ),
				'intro'   => __( 'The objectives the Association was established to pursue, and continues to pursue on behalf of its members.', 'kaao' ),
				'align'   => 'center',
				'id'      => 'what-title',
			)
		);
		?>

		<div class="grid grid--3">
			<?php foreach ( $kaao_pillars as $kaao_pillar ) : ?>
				<div class="feature">
					<span class="feature__icon">
						<?php echo kaao_icon( $kaao_pillar['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<h3><?php echo esc_html( $kaao_pillar['title'] ); ?></h3>
					<p><?php echo esc_html( $kaao_pillar['text'] ); ?></p>
					<p class="mt-4" style="margin-bottom:0">
						<a class="link-arrow" href="<?php echo esc_url( home_url( $kaao_pillar['link'] ) ); ?>">
							<?php esc_html_e( 'Learn more', 'kaao' ); ?>
							<span class="screen-reader-text"><?php echo esc_html( $kaao_pillar['title'] ); ?></span>
						</a>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
