<?php
/**
 * The homepage.
 *
 * Composed entirely of reusable parts. Every section pulls live WordPress
 * content — nothing on this page is hard-coded editorial copy except the
 * section headings themselves.
 *
 * @package KAAO
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'kaao_hero' );
get_template_part( 'template-parts/hero/homepage' );

get_template_part( 'template-parts/home/stats' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/what-we-do' );
get_template_part( 'template-parts/home/membership' );
get_template_part( 'template-parts/home/members' );
get_template_part( 'template-parts/home/advocacy' );
get_template_part( 'template-parts/home/events' );
get_template_part( 'template-parts/home/news' );
get_template_part( 'template-parts/home/faqs' );
get_template_part( 'template-parts/home/cta' );

get_footer();
