<?php
/**
 *
 *  Woocom Registration Redirect
 *
 */
add_filter('woocommerce_registration_redirect', 'custom_registration_redirect', 50, 1);

function custom_registration_redirect( $redirect ) {

    $redirect = get_site_url() . '/learning-dashboard/';
	// $redirect = get_author_posts_url( get_current_user_id() );

	dg_activity('Registration redirect url: ' . $redirect .'.', 'Register' );

	return $redirect;

}

/**
 *
 *  Woocom login form redirect
 *
 */
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 50, 2 );

function wc_custom_user_redirect( $redirect, $user ) {

	global $post;
	$url = $_SERVER['REQUEST_URI'];
	// $redirect = get_author_posts_url( $user->ID );
    $redirect = get_site_url() . '/learning-dashboard/';

    // If logging in on guitar god page then redirect to same page
    if ( strpos( $url, 'become-guitar-god/confirm') !== FALSE ) {
    	
        $redirect = get_site_url() . $url;
        
    }

    dg_activity('Login redirect url: ' . $redirect .'.', 'Login' );

	return $redirect;

}

/**
 *
 *	Redirect admin to admin dashboard
 *	Redirect to guitar god confirm page if you log in there
 *	@todo Redirect to query param if set
 *	All others go to /my-account/
 *
 */
add_filter( 'login_redirect', 'dg_login_redirect', 100, 3 );
function dg_login_redirect( $redirect_to, $request, $user ) {

    if ( is_array( $user->roles ) ) {

        if ( in_array( 'administrator', $user->roles ) ) {

            $redirect_to = admin_url( 'admin.php?page=dg-dashboard' );
            return $redirect_to;

        } else {

        	// $redirect_to = '/my-account/';
        	// $redirect_to = get_author_posts_url( $user->ID );
            $redirect = get_site_url() . '/learning-dashboard/';
        	return $redirect_to;

        }

    } else {

        // If logging in on guitar god page then redirect to same page
        if ( is_page( 24136 ) ) {

            $redirect_to = get_permalink( 24136 );

        } else {

        	$redirect_to = get_site_url() . '/learning-dashboard/';
            // $redirect_to = get_author_posts_url( $user->ID );

        }

    	return $redirect_to;

    }
}

/**
 *	Redirect logged in pages to my account
 */
add_action('template_redirect', 'dg_must_be_logged_in');
function dg_must_be_logged_in() {

	//	23939 = Stats, achievements & accolades
	//	23460 = Learning dashboard
	$page_ids = [23939,23460];

    if ( ! is_user_logged_in() && is_page($page_ids) ) {
        wp_redirect( '/my-account/' );
        exit;
    }
}

/**
 *	Bypass logout confirmation on woocom
 *	And redirect to my account page
 */
add_action( 'template_redirect', 'iconic_bypass_logout_confirmation' );
function iconic_bypass_logout_confirmation() {

    global $wp;
 
    if ( isset( $wp->query_vars['customer-logout'] ) ) {
    	logoutUser();
    	wp_redirect( wc_get_page_permalink( 'myaccount' ) );
        exit;

    } elseif ( is_user_logged_in() && is_front_page() && 1 == 2 ) {

    	// redirect to profile page if they visit home page - DISABLED
    	$redirect_to = get_author_posts_url( get_current_user_id() );
    	wp_redirect( $redirect_to );
    	exit;

    }
}

/*
 *	Maybe logout properly
 *
 */
add_action('wp_logout', 'logoutUser', 10);
function logoutUser() {

   delete_user_meta( get_current_user_id(), 'session_tokens' );

}

/**
 *
 *	Redirect anyone who logs out to /my-account/
 *
 */
add_filter( 'logout_redirect', 'dg_logout_redirect', 100, 3 );
function dg_logout_redirect( $redirect_to, $request, $user ) {

    $redirect_to = '/my-account/';
	return $redirect_to;

}

/**
 *  Check / set logged in cookie
 *  Used in pop ups
 */
add_action('init', 'dg_set_logged_in_cookie');

function dg_set_logged_in_cookie() { 
    if ( is_user_logged_in() ) {

        // Check if cookie is already set
        if ( ! isset($_COOKIE['guitar-tabs-registration-popup']) || ! isset($_COOKIE['guitar-tones-registration-popup']) ) {
         
            // Don't worry about it all good
            setcookie('guitar-tabs-registration-popup',  'true', time() + 31556926);
            setcookie('guitar-tones-registration-popup',  'true', time() + 31556926);

        }
    }
}

/**
 *  Redirect old tones to new tones (301, permanent)
 */
add_action('template_redirect', 'check_guitar_tone_url');

function check_guitar_tone_url() {

    global $post;

    // All good
    if ( is_tax( 'developer' ) ) {

        $term = get_queried_object();

        dg_debug( 'term: ' . $term->slug);

        $url = get_site_url(null,'/explore/brand/' . $term->slug);

        wp_redirect($url, 301);
        die;

    }

    // All good
    if ( is_singular( 'guitar_tone' ) ) {

        $post_slug = $post->post_name;
        $url = get_site_url(null,'/explore/guitar-tone/'.$post_slug);
        wp_redirect($url, 301);
        die;

    }
}
/**
 *
 *  Sets redirect after user updated profile info
 *
 */
add_action( 'woocommerce_save_account_details', 'custom_profile_redirect', 100 );
function custom_profile_redirect() {

    wp_redirect( wc_get_account_endpoint_url( 'edit-account' ) );

    exit;

}
