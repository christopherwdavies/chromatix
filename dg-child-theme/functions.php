<?php
/**
 *	Functions File
 */
//Functions
include_once( get_stylesheet_directory(). '/inc/core-functions.php' );

//Shortcodes
include_once( get_stylesheet_directory(). '/inc/shortcodes.php' );

// GA/FB events
include_once( get_stylesheet_directory(). '/inc/event-tracking.php' );

// Internal User Tracking
include_once( get_stylesheet_directory(). '/inc/user-tracking.php' );

// User submitted data
include_once( get_stylesheet_directory(). '/inc/user-submitted-data.php' );

// Modules to create learning dashboard
include_once( get_stylesheet_directory(). '/inc/learning-dashboard.php' );

// Admin dashboard functions
include_once( get_stylesheet_directory(). '/inc/admin-functions.php' );

// Yoast seo filters
include_once( get_stylesheet_directory(). '/inc/seo.php' );

// Redirections and login / register actions
include_once( get_stylesheet_directory(). '/inc/redirections-login-register-actions.php' );

// Redirections and login / register actions
include_once( get_stylesheet_directory(). '/inc/scripts-styles-manager.php' );

// Emails
include_once( get_stylesheet_directory(). '/emails/practice-reminders.php' );
// include_once( get_stylesheet_directory(). '/emails/practice-summary.php' );

// File responsible for sendign mc api requests
include_once( get_stylesheet_directory(). '/emails/mc-api.php' );

// Scheduled Tasks
include_once( get_stylesheet_directory(). '/scheduled-tasks/update-leaderboard.php' );

// Load menus, sidebars widgets etc
include_once( get_stylesheet_directory(). '/templates/sidebars-widgets-menus.php' );

// Woocom hooks
if( class_exists( 'WooCommerce' ) ) {

	include_once( get_stylesheet_directory(). '/woocommerce/woocommerce_hooks.php' );

	add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

	function mytheme_add_woocommerce_support() {

		add_theme_support( 'woocommerce' );

	}
	
}

// Dokan hooks
if( class_exists( 'WeDevs_Dokan' ) ) {

	include_once( get_stylesheet_directory(). '/dokan/functions.php' );

}

// Dokan hooks
if( class_exists( 'BuddyPress' ) ) {

	include_once( get_stylesheet_directory(). '/buddypress/functions.php' );

}

/**
 *
 *	Hide admin bar for all others except admin
 *
 */
add_action('init', 'dg_remove_admin_bar');

function dg_remove_admin_bar() {

	if ( ! current_user_can( 'administrator' ) ) {
	
		add_filter( 'show_admin_bar', '__return_false' );

	}

}

/**
 *
 *	Allow users to upload
 *	
 */
add_action('admin_init', 'allow_contributor_uploads');

function allow_contributor_uploads() {

	// Customer
	$customer = get_role('customer');
	$customer->add_cap( 'upload_files' );
	$customer->add_cap( 'delete_posts' );	
	$customer->add_cap( 'edit_published_posts' );	
	$customer->add_cap( 'edit_others_posts' );	


	// Subscriber
	$subscriber = get_role('subscriber');
	$subscriber->add_cap( 'upload_files' );
	$subscriber->add_cap( 'delete_posts' );
	$subscriber->add_cap( 'edit_published_posts' );	
	$subscriber->add_cap( 'edit_others_posts' );	

}

/**
 *
 *	Limit media in library to users //
 *
 */  
add_filter( 'ajax_query_attachments_args', 'wpb_show_current_user_attachments' );
 
function wpb_show_current_user_attachments( $query ) {

    $user_id = get_current_user_id();

    // restrict media to current user if they are not administrator
    if ( $user_id && ! current_user_can('administrator') ) {

        $query['author'] = $user_id;

    }

    return $query;

} 

/**
 *
 *	Filter avatar to include profile picture if set
 *
 */
// add_filter( 'get_avatar_url' , 'dg_custom_avatar_url' , 10000 , 3 );

function dg_custom_avatar_url( $url, $id_or_email, $args ) {

    $user = false;

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {

            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );

        }

    } else {

        $user = get_user_by( 'email', $id_or_email );

    }

    if ( $user && is_object( $user ) ) {

    	$attachment_id = $user->dg_profile_picture;

    	if ( ! empty( $attachment_id ) ) {

 			$profile_picture 	= wp_get_attachment_image_src( $attachment_id, $size = 'large');
			$profile_picture 	= $profile_picture[0];
			$url 				= $profile_picture;

			return $url;

    	}
        
    }

    return $url;
}
// Apply filter
// add_filter( 'get_avatar' , 'dg_custom_avatar' , 1000 , 5 );

function dg_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

    $user = false;

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {

        $user = get_user_by( 'email', $id_or_email );

    }

    if ( $user && is_object( $user ) ) {

    	$attachment_id = $user->dg_profile_picture;

    	if ( ! empty( $attachment_id ) ) {

 			$profile_picture 	= wp_get_attachment_image_src( $attachment_id, 'medium');
			$profile_picture 	= $profile_picture[0];

			// $avatar = "<img alt='{$alt}' src='{$profile_picture}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			$avatar = 
				"<div style='background-image: url({$profile_picture}); height: {$size}px; width: {$size}px; background-size: cover; background-position: center; display: inline-block; vertical-align: middle;' class='avatar avatar-{$size} photo' /></div>";

			return $avatar;

    	}

    }

    return $avatar;
}

/**
 *
 *	@todo set profile slug to be a var so that if I change it it stays relevant
 *	Try create custom vars for profile
 */
add_action( 'init', 'dg_rewrite_rules' );

function dg_rewrite_rules() {

    add_rewrite_rule( 'profile/([^/]+)/videos/?$', 'index.php?author_name=$matches[1]&author_content=videos', 'top' );

}
/**
 *
 *	Add my own query vars
 *
 */
add_filter('query_vars', 'dg_query_vars');

function dg_query_vars($vars) {

    $vars[] = 'author_content';   
    return $vars;

}

/**
 *	Disable cache for logged in users, doesnt really work
 */
add_action('init', 'dg_disable_cache_for_logged_in_users');

function dg_disable_cache_for_logged_in_users() {

	if ( is_user_logged_in() && isset( $_SERVER['COMET_CACHE_ALLOWED'] ) ) {

		$_SERVER['COMET_CACHE_ALLOWED'] = FALSE;

	}

}

/**
 *
 *	Allow custom mime types for GP files
 *	.gp5 = application/octet-stream
 *
 */
add_filter('upload_mimes', 'my_myme_types', 1, 1);

function my_myme_types($mime_types){

    $mime_types['gp'] = 'application/octet-stream'; //Adding Guitar Pro
    return $mime_types; //daviesguitars.io

}

/**
 *	Custom mailchimp sync
 */
add_filter( 'mailchimp_sync_user_data', function( $data, $user ) {

	// YYYY/MM/DD = DATE FORMAT

	$interested_in_learning = $user->interested_in_learning;
    $registered = get_the_author_meta( 'user_registered', $user->ID );
   	$registered = date( "Y/m/d", strtotime( $registered ) );
   	$guitar_god_member = rcp_user_has_active_membership($user->ID);

   	if ($guitar_god_member == TRUE) {

   		$data['PAIDMEMBER'] = 'TRUE';

   	} else {

   		$data['PAIDMEMBER'] = 'FALSE';
  		
   	}

    $data['USERNAME'] = $user->user_login;
    $data['ACNTCREATE'] = $registered;
    $data['SIGNUPFORM'] = 'MC User Sync';
    $data['LEARNING'] = $interested_in_learning;

    return $data;

}, 10, 2 );

/**
 * Change the data that is sent to MailChimp.
 *
 * @param MC4WP_MailChimp_Subscriber $subscriber
 * @return MC4WP_MailChimp_Subscriber
 */
add_filter( 'mc4wp_subscriber_data', 'myprefix_subscriber_data' );

function myprefix_subscriber_data( MC4WP_MailChimp_Subscriber $subscriber ) {


	if ( isset( $_POST['email'] ) && isset( $_POST['username'] ) ) {

		$email 	= $_POST['email'];
		$user 	= get_user_by( 'email', $email );
		$interested_in_learning = $user->interested_in_learning;
		
   		$subscriber->merge_fields['LEARNING'] 	= $interested_in_learning;
		$subscriber->merge_fields['USERNAME'] 	= $user->user_login;
	    $subscriber->merge_fields['SIGNUPFORM'] = 'MC User Sync';

	    $guitar_god_member = rcp_user_has_active_membership($user->ID);

	   	if ($guitar_god_member == TRUE) {

	   		$subscriber->merge_fields['PAIDMEMBER'] = 'TRUE';

	   	} else {

	   		$subscriber->merge_fields['PAIDMEMBER'] = 'FALSE';
	  		
	   	}

	   	$registered = get_the_author_meta( 'user_registered', $user->ID );
   		$registered = date( "Y/m/d", strtotime( $registered ) );

    	$subscriber->merge_fields['ACNTCREATE'] = $registered;

	}

    return $subscriber;

}

/**
 *	Change author slug to /profile/
 */
add_action('init', 'cng_author_base');

function cng_author_base() {

    global $wp_rewrite;
    $author_slug = 'profile'; // change slug name
    $wp_rewrite->author_base = $author_slug;
    $wp_rewrite->flush_rules();

}

/**
 *	Add to header
 */
add_action('wp_head', 'cd_insert_into_head');

function cd_insert_into_head() {

	echo '<meta name="theme-color" content="#111111">';

}

/**
 *	Filter the title as I please
 */
add_filter('the_title', 'dg_filter_page_title', 10, 2);

function dg_filter_page_title($title, $id) {

	// Filter welcome guitar god page to show name
	if ( $id == 23189 ) {

		$user 		= wp_get_current_user();
		$fname 		= $user->first_name;
		$title 		= 'Welcome ' . $fname;

	}

	return $title;

}

/**
 *	Set email template to blank for RCP
 */
add_filter( 'rcp_email_templates', 'ag_rcp_email_templates' );

function ag_rcp_email_templates( $templates ) {

    $templates['chris-template'] = __( 'Blank Template' );
    return $templates;

}

// User Avatar
add_filter( 'avatar_defaults', 'wpb_new_gravatar' );

function wpb_new_gravatar ($avatar_defaults) {

	$myavatar = 'https://daviesguitars.io/wp-content/uploads/2019/01/Davies-Guitars-Logo-2-Favicon.png';
	$avatar_defaults[$myavatar] = "Default Gravatar";
	return $avatar_defaults;

}

//Final render of popup search form
add_action('chris_after_header', 'header_search_form');

function header_search_form() { ?>

	<div class="search-overlay">
		<div class="container">
			<div class="wpb_column vc_column_container vc_col-sm-12">
				<div class="vc_column-inner">
					<div class="wpb_wrapper" style="position: relative;">
						<div class="search-forms">
							<span class="header-exit-button">
								<i class="fas fa-times"></i>
							</span>
							<div class="title-h1 title-xlarge" style="text-align:center; color:white;">
								<span>Search</span>
							</div>
							<div class="gradient-border" style="max-width: 75px; margin: 20px auto;"></div>
							<h2 class="title-h1 subtitle">
								<p style="color: white;">Find Your Sound</p>
							</h2>
							<?php echo do_shortcode('[global-search]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php

}

/**
 *	Hide logged in css if user is logged in
 */
add_action('wp_footer', 'dg_hide_class_if_logged_in');

function dg_hide_class_if_logged_in() {

	if ( is_user_logged_in() ) : ?>
		<style type="text/css">
			.hide-logged-in {
				display: none !important;
			}
			.show-if-guitar-god {
				display: none;
			}
			<?php if ( dg_is_user_guitar_god() ) : ?>
			.hide-if-guitar-god {
				display: none !important;
			}
			.show-if-guitar-god {
				display: initial;
			}
			li.show-if-guitar-god {
				display: list-item;
			}
			<?php endif; ?>
		</style>
	<?php endif;

}

/**
 *	Points system, account menu, modal
 */
add_action('wp_footer', 'dg_footer_html');

function dg_footer_html() {
	?>

	<div class="account-menu-popup">
		<span class="account-popup-exit">
			<i class="fas fa-times"></i>
		</span>
		<?php echo do_shortcode('[account-menu-content]'); ?>
	</div>
	<div id="dialog-modal" title="Basic modal dialog" style="display: none;"></div>
	<div id="points-popup-div"></div>
	<div id="registration-loading" style="display: none;">
		<div class="wrapper">
			<div class="vertical-align">
				<?php echo do_shortcode('[dg-loading]'); ?>
				<span class="loading-text">Passing your details onto the underworld...</span>
			</div>
		</div>
	</div>
	<style type="text/css">
		div#registration-loading {
		    position: fixed;
		    width: 100%;
		    height: 100%;
		    top: 0px;
		    left: 0px;
		    z-index: 999999999999999999999;
		    background: #000000e8;
		}
		span.loading-text {
		    text-align: center;
		    font-size: 24px;
		    color: white;
		    display: block;
		    margin-top: 10px;
		}
		div#registration-loading .wrapper {
		    display: table !important;
		    width: 100% !important;
		    height: 100% !important;
		}
		#registration-loading .pentagram {
		    position: initial;
		    margin: auto;
		}
		.vertical-align {
		    display: table-cell;
		    vertical-align: middle;
		}
	</style>

	<?php 
}
/**
 *
 *	Add Banner Image Size
 *
 */
add_action( 'init', 'dg_image_sizes', 11 );

function dg_image_sizes() {

    add_image_size( 'Banner Size', 1920, 1920 );
    add_image_size( 'Max Size', 9999, 9999 );

}
