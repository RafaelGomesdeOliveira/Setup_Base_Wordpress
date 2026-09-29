<?php
/**
 * Template Name: Quem Somos
 * Template Post Type: page
 *
 * About Us page. Select it in Pages > Edit > Template > "Quem Somos".
 * Each section lives in templates-parts/about-us/.
 *
 * @package wp-base
 */

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'templates-parts/about-us/hero' );
	get_template_part( 'templates-parts/about-us/values' );
	// Add more sections here: get_template_part( 'templates-parts/about-us/<section>' );
endwhile;

get_footer();
