<?php
/**
 *
 *	Buddypress override template
 *
 */

/**
 *	Styles to be used for BP only pages
 **/
add_action('wp_enqueue_scripts', 'dg_load_bp_style', 100);
function dg_load_bp_style() { 

	// Load custom font
	wp_register_style( 'custom-buddypress', get_stylesheet_directory_uri() . '/css/custom-buddypress.css' );
	wp_enqueue_style('custom-buddypress');

}

// Set default page template on BP pages
get_template_part( 'page', 'user-account-template' );