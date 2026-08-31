<?php
/**
 * 404 — page not found.
 *
 * Routes the visitor somewhere useful rather than apologising at them.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/hero/page',
	null,
	array(
		'eyebrow' => __( 'Error 404', 'kaao' ),
		'title'   => __( 'We could not find that page', 'kaao' ),
		'intro'   => __( 'The page may have moved during the site redesign, or the address may be slightly off. Everything below is one click away.', 'kaao' ),
	)
);
?>

<div class="section">
	<div class="container container--narrow">

		<div style="margin-bottom:clamp(2rem,4vw,3rem)">
			<?php get_search_form(); ?>
		</div>

		<h2 class="h3"><?php esc_html_e( 'Popular destinations', 'kaao' ); ?></h2>

		<div class="grid grid--2 mt-6">
			<?php
			$kaao_destinations = array(
				array( __( 'Member directory', 'kaao' ), __( 'Every organisation in KAAO membership.', 'kaao' ), '/members/' ),
				array( __( 'Membership', 'kaao' ), __( 'Categories, benefits and how to join.', 'kaao' ), '/membership/' ),
				array( __( 'News & insights', 'kaao' ), __( 'Press releases and industry updates.', 'kaao' ), '/news/' ),
				array( __( 'Events', 'kaao' ), __( 'Upcoming and past engagements.', 'kaao' ), '/events/' ),
				array( __( 'Advocacy', 'kaao' ), __( 'The policy work KAAO is engaged on.', 'kaao' ), '/advocacy/' ),
				array( __( 'Contact us', 'kaao' ), __( 'Reach the Secretariat directly.', 'kaao' ), '/contact-us/' ),
			);
			foreach ( $kaao_destinations as [$kaao_label, $kaao_text, $kaao_url] ) :
				?>
				<div class="card card--flat" style="padding:var(--sp-5);position:relative">
					<h3 class="card__title" style="font-size:var(--fs-lg)">
						<a href="<?php echo esc_url( home_url( $kaao_url ) ); ?>"><?php echo esc_html( $kaao_label ); ?></a>
					</h3>
					<p class="card__excerpt" style="margin-top:.5rem"><?php echo esc_html( $kaao_text ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="mt-8">
			<a class="btn btn--navy" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to the homepage', 'kaao' ); ?>
			</a>
		</p>
	</div>
</div>

<?php
get_footer();
