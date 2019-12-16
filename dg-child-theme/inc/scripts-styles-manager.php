<?php
/**
 *
 *	Managing Scripts and Styles
 *
 *
 **/
/**
 *	Load and manage new scripts
 */
add_action('wp_enqueue_scripts', 'load_new_scripts', 100);

function load_new_scripts() { 

    // Jquery ui
    wp_register_script( 'jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js', array( 'jquery') );
    wp_enqueue_script( 'jquery-ui' );
    // Jquery css
    wp_register_style( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css' );
    wp_enqueue_style('jquery-ui');

    // My global JS functions file - loaded on all pages
    wp_register_script( 'davies-guitars', get_stylesheet_directory_uri() . '/js/davies-guitars.js', array( 'jquery', 'jquery-ui' ), FALSE, TRUE );
    wp_enqueue_script( 'davies-guitars' );

	// Load Kiona font
	wp_register_style( 'kiona', get_stylesheet_directory_uri() . '/css/kiona/kiona-fonts.css' );
	wp_enqueue_style('kiona');

	// Load css for progress bar
	wp_enqueue_style( 'loading-bar', get_stylesheet_directory_uri() . '/css/progress-bar.css' );

	// Load JS for progress bar
	wp_enqueue_script( 'loading-bar', get_stylesheet_directory_uri() . '/js/progress-bar.js');

    // Font awesome
    wp_register_style( 'font-awesome-5', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' );
    wp_enqueue_style('font-awesome-5');

    /**
     *
     *  JS file to update profile pictures / cover images
     *  Passing php vars into js file
     *  @link https://stackoverflow.com/questions/6808221/php-within-js-file-wordpress
     *  @param admin, user
     *
     */
    $variables = array(
        'admin' => admin_url( 'admin-ajax.php' ),
        'user'  => get_current_user_id(),
         );
    wp_register_script( 'update-profile-picture', get_stylesheet_directory_uri() . '/js/profile-picture.js', array( 'jquery', 'jquery-ui'), FALSE, TRUE );
    wp_localize_script( 'update-profile-picture', 'variables', $variables );


	/**
     *
     *  Dequeue unnecessary files
     *
     */
	wp_dequeue_style( 'thegem-woocommerce-custom-css' );
    wp_deregister_style( 'thegem-woocommerce-custom-css' );

    wp_dequeue_style( 'thegem-woocommerce-custom' );
    wp_deregister_style( 'thegem-woocommerce-custom' );

    wp_dequeue_style( 'thegem-new-css' );
    wp_deregister_style( 'thegem-new-css' );

    wp_dequeue_style( 'perevazka-css-css' );
    wp_deregister_style( 'perevazka-css-css' );

    wp_dequeue_style( 'thegem-additional-blog-1' );
    wp_deregister_style( 'thegem-additional-blog-1' );
}

/**
 *	Admin side scripts
 */
// Load login styles
add_action('login_enqueue_scripts', 'login_page_styles',  10 );

function login_page_styles() {

	wp_enqueue_style( 'login-theme-css', get_stylesheet_directory_uri() . '/css/login_theme.css');

}

// Load admins styles
// add_action('admin_enqueue_scripts', 'admin_page_styles', 10);

function admin_page_styles() {

	wp_enqueue_style( 'admin-theme-css', get_stylesheet_directory_uri() . '/css/admin_theme.css');

}