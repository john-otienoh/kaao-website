<?php
/**
 * Membership section — the three categories plus the published benefits.
 *
 * Category descriptions are verbatim from the KAAO Member Registration page;
 * the benefit list is verbatim from the current homepage.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

$kaao_tiers = array(
	array(
		'slug'     => 'ordinary',
		'name'     => __( 'Ordinary', 'kaao' ),
		'badge'    => __( 'Licensed operators', 'kaao' ),
		'text'     => __( 'Ordinary Membership is open to holders of KCAA licences and certificates (AOC, AMO, ATO and ROC).', 'kaao' ),
		'points'   => array(
			__( 'Air Operator Certificate (AOC) holders', 'kaao' ),
			__( 'Approved Maintenance Organisations (AMO)', 'kaao' ),
			__( 'Approved Training Organisations (ATO)', 'kaao' ),
			__( 'Remote Operator Certificate (ROC) holders', 'kaao' ),
		),
		'featured' => true,
	),
	array(
		'slug'     => 'associate',
		'name'     => __( 'Associate', 'kaao' ),
		'badge'    => __( 'Industry partners', 'kaao' ),
		'text'     => __( 'Associate Membership is available to owners, operators, and individuals contributing to civil aviation. Associate Members enrich our community with their varied experiences and insights.', 'kaao' ),
		'points'   => array(
			__( 'Tourism associations and companies', 'kaao' ),
			__( 'Financial institutions and insurance firms', 'kaao' ),
			__( 'Fuel companies and marketers', 'kaao' ),
			__( 'Tax, audit, legal and advisory firms', 'kaao' ),
		),
		'featured' => false,
	),
	array(
		'slug'     => 'honorary',
		'name'     => __( 'Honorary', 'kaao' ),
		'badge'    => __( 'By invitation only', 'kaao' ),
		'text'     => __( 'Honorary Membership may be granted to individuals or organisations whose contributions transcend traditional boundaries. Honorary Members embody the spirit of excellence and innovation.', 'kaao' ),
		'points'   => array(
			__( 'Recognition of outstanding contribution to Kenyan aviation', 'kaao' ),
			__( 'Extended by invitation of the Association', 'kaao' ),
		),
		'featured' => false,
	),
);

$kaao_benefits = array(
	array( __( 'Stronger industry voice', 'kaao' ), __( 'Advocate for aviation policies and regulatory changes.', 'kaao' ) ),
	array( __( 'Exclusive networking', 'kaao' ), __( 'Connect with top aviation professionals and businesses.', 'kaao' ) ),
	array( __( 'Expert support', 'kaao' ), __( 'Access legal, financial and operational guidance.', 'kaao' ) ),
	array( __( 'Training & development', 'kaao' ), __( 'Gain skills through specialised workshops.', 'kaao' ) ),
	array( __( 'Government representation', 'kaao' ), __( 'Your concerns are heard at policymaking levels.', 'kaao' ) ),
	array( __( 'Cost-effective services', 'kaao' ), __( 'Enjoy exclusive benefits and business opportunities.', 'kaao' ) ),
);
?>
<section class="section" aria-labelledby="membership-title">
	<div class="container">
		<?php
		kaao_section_head(
			array(
				'eyebrow' => __( 'Membership', 'kaao' ),
				'title'   => __( 'Be part of Kenya’s aviation voice', 'kaao' ),
				'intro'   => __( 'Join the Kenya Association of Air Operators and be part of a network driving the growth and sustainability of Kenya’s aviation industry.', 'kaao' ),
				'align'   => 'center',
				'id'      => 'membership-title',
			)
		);
		?>

		<div class="grid grid--3">
			<?php foreach ( $kaao_tiers as $kaao_tier ) : ?>
				<div class="tier<?php echo $kaao_tier['featured'] ? ' tier--featured' : ''; ?>">
					<span class="tier__badge"><?php echo esc_html( $kaao_tier['badge'] ); ?></span>
					<h3><?php echo esc_html( $kaao_tier['name'] ); ?></h3>
					<p><?php echo esc_html( $kaao_tier['text'] ); ?></p>

					<ul>
						<?php foreach ( $kaao_tier['points'] as $kaao_point ) : ?>
							<li><?php echo esc_html( $kaao_point ); ?></li>
						<?php endforeach; ?>
					</ul>

					<div class="tier__foot">
						<a class="link-arrow" href="<?php echo esc_url( home_url( '/members/category/' . $kaao_tier['slug'] . '/' ) ); ?>">
							<?php
							printf(
								/* translators: %s: membership category name. */
								esc_html__( 'View %s members', 'kaao' ),
								esc_html( strtolower( $kaao_tier['name'] ) )
							);
							?>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="cluster mt-8" style="justify-content:center">
			<a class="btn btn--navy btn--lg" href="<?php echo esc_url( home_url( '/membership/' ) ); ?>">
				<?php esc_html_e( 'Explore membership', 'kaao' ); ?>
			</a>
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/member-registration/' ) ); ?>">
				<?php esc_html_e( 'Apply for membership', 'kaao' ); ?>
			</a>
		</div>
	</div>
</section>

<section class="section section--navy" aria-labelledby="benefits-title">
	<div class="container">
		<?php
		kaao_section_head(
			array(
				'eyebrow' => __( 'Why join KAAO', 'kaao' ),
				'title'   => __( 'What membership gives your organisation', 'kaao' ),
				'align'   => 'center',
				'id'      => 'benefits-title',
			)
		);
		?>

		<div class="benefits">
			<?php foreach ( $kaao_benefits as $kaao_i => [$kaao_title, $kaao_text] ) : ?>
				<div class="benefit">
					<span class="benefit__mark" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $kaao_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<div>
						<h3><?php echo esc_html( $kaao_title ); ?></h3>
						<p><?php echo esc_html( $kaao_text ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
