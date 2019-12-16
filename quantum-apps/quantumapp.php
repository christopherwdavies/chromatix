<?php 

/**
 * Plugin Name:       Quantum Apps
 * Description:       Creates an applications custom post type
 * Version:           1.0.0
 * Author:            Quantum
 * Author URI:        https://quantumapps.io
 * Text Domain:       quantumapps
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
 * Plugin constants
 */

$directory = plugin_dir_url(__FILE__);

add_action( 'wp_enqueue_scripts', 'my_custom_script_load', 999 );

function my_custom_script_load(){
	wp_register_script('jquery2-1-4', 'https://code.jquery.com/jquery-2.1.4.min.js');
	wp_register_script('submit_push', plugin_dir_url(__FILE__) . 'assets/js/submit_push.js');
	wp_register_style('quantum', plugin_dir_url(__FILE__) . 'assets/css/quantum.css');

	wp_enqueue_script('jquery2-1-4');
	wp_enqueue_script('submit_push');
	wp_enqueue_style('quantum');
}





//Redirect customers to dashboard when they log in
add_filter('woocommerce_login_redirect', 'wc_login_redirect');
 
function wc_login_redirect( $redirect_to ) {
	$redirect_to = home_url() . '/dashboard';
	return $redirect_to;
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );




// Hook to the init action hook
add_action( 'init', 'quantum_custom_apps_type' );
 
// The custom function to register a movie post type
function quantum_custom_apps_type() {
 
	// Set the labels, this variable is used in the $args array
	$labels = array(
	'name'               => __( 'Apps' ),
	'singular_name'      => __( 'App' ),
	'add_new'            => __( 'Add New App' ),
	'add_new_item'       => __( 'Add New App' ),
	'edit_item'          => __( 'Edit App' ),
	'new_item'           => __( 'New App' ),
	'all_items'          => __( 'All Apps' ),
	'view_item'          => __( 'View Apps' ),
	'search_items'       => __( 'Search Apps' ),
	'featured_image'     => 'App Image',
	'set_featured_image' => 'Add App Image'
	);

	// The arguments for our post type, to be entered as parameter 2 of register_post_type()
	$args = array(
	'labels'            => $labels,
	'description'       => 'Holds our apps and app specific data',
	'public'            => true,
	'menu_position'     => 5,
	'supports'          => array( 'title', 'custom-fields', 'thumbnail' ),
	'has_archive'       => true,
	'show_in_admin_bar' => true,
	'show_in_nav_menus' => true,
	'has_archive'       => true,
	'query_var'         => 'film'
	);

	// Call the actual WordPress function
	// Parameter 1 is a name for the post type
	// Parameter 2 is the $args array
	register_post_type( 'apps', $args);


	//Create custom role for app manager
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    $adm = $wp_roles->get_role('administrator');
    //Adding a 'new_role' with all admin caps
    $wp_roles->add_role('app_manager', 'App Manager', $adm->capabilities);
}


function remove_admin_menu_items() {

	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
	$userlogin = $user_info->user_login;

	if ($userlogin == 'appmanager') {
		remove_menu_page( 'edit.php?post_type=thegem_gallery' );
		remove_menu_page( 'edit.php?post_type=thegem_pf_item' );
		remove_menu_page( 'edit.php?post_type=thegem_qf_item' );
		remove_menu_page( 'admin.php?page=wpcf7' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'edit.php?post_type=page' );
		remove_menu_page( 'upload.php' );
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit.php?post_type=thegem_team_person' );
		remove_menu_page( 'edit.php?post_type=thegem_client' );
		remove_menu_page( 'edit.php?post_type=thegem_testimonial' );
		remove_menu_page( 'edit.php?post_type=thegem_news' );
		remove_menu_page( 'themes.php' );
		remove_menu_page( 'index.php' );                  //Dashboard
		remove_menu_page( 'jetpack' );                    //Jetpack* 
		remove_menu_page( 'upload.php' );                 //Media
		remove_menu_page( 'edit.php?post_type=page' );    //Pages
		remove_menu_page( 'edit-comments.php' );          //Comments
		remove_menu_page( 'themes.php' );                 //Appearance
		remove_menu_page( 'plugins.php' );                //Plugins
		remove_menu_page( 'users.php' );                  //Users
		remove_menu_page( 'tools.php' );                  //Tools
		remove_menu_page( 'options-general.php' );        //Settings
		remove_menu_page( 'edit.php?post_type=thegem_footer' );        //Settings
		remove_menu_page( 'edit.php?post_type=thegem_slide' );        //Settings
		remove_menu_page( 'admin.php?page=vc-general' );        //Settings
		remove_menu_page( 'edit.php?post_type=acf-field-group' );        //Settings
		remove_menu_page( 'admin.php?page=thegem-import-submenu-page' );        //Settings
		remove_menu_page( 'admin.php?page=mailchimp-for-wp' );        //Settings
		remove_menu_page( 'admin.php?page=mailchimp-for-wp' );        //Settings
		remove_menu_page( 'admin.php?page=revslider' );        //Settings
		remove_menu_page( 'admin.php?page=mailchimp-woocommerce' );        //Settings
	}

}

add_action( 'admin_menu', 'remove_admin_menu_items', 999 );

	 



/*

MY APPS SHORTCODE

*/

add_shortcode( 'my_apps', 'quantum_apps_loop' );

function quantum_apps_loop() {

	global $post;
	$i = 0;


	$loop = new WP_Query( array(
	    'post_type' => 'apps',
	    'posts_per_page' => -1
	  )
	);

	while ( $loop->have_posts() ) : $loop->the_post(); 


		$firebaseCode = get_post_meta($post->ID, 'server_key', true);
		$appCode = get_post_meta($post->ID, 'app_name', true);
		$appOwner = get_post_meta($post->ID, 'owner', true);
		$appStatus = get_post_meta($post->ID, 'app_status', true);
		$mainColour = get_post_meta($post->ID, 'main_colour', true);

		$post_thumbnail_id = get_post_thumbnail_id( $post );
		$appImage = wp_get_attachment_image_url( $post_thumbnail_id, 'post-thumbnail' );

		$user_id = get_current_user_id();

		if ($user_id == $appOwner) {

			++$i;

			echo '<div class="users-apps action-block col-md-6">';
				echo '<div class="app-image-container header"><div id="app-image" style="background: ' . $mainColour . '"><img src="' . $appImage . '"></div></div>';
				echo '<div class="app-content-container">';
					echo '<div class="app-name-container"><div id="app-name">' . $appCode . '</div></div>';
					echo '<div class="app-status-container">App status: ' . $appStatus . '</div>';
					echo '<div class="server-key-container" style="display:none;">Server Key: <div id="app-server-key">' . $firebaseCode . '</div></div>';
				echo '</div>';
			echo '</div>';

		}

		elseif ( ! $user_id == $appOwner) {
				echo '<div class="woocommerce"><ul class="woocommerce-error" role="alert"><li><strong>ERROR</strong>: You currently don\'t have any apps available. If you have made an order your app will appear when it\'s ready to send push notifications to.</li></ul></div>';
		}

	endwhile;
	wp_reset_query(); 


}

/*

DASHBAORD SHORTCODE

*/

add_shortcode( 'dashboard', 'quantum_dashboard' );

function quantum_dashboard() {

	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);

	echo 'G\'day ' . $user_info->first_name . '!<br>';

	echo 'Username: ' . $user_info->user_login . '<br />';
    echo 'User email: ' . $user_info->user_email . '<br />';
    echo 'User first name: ' . $user_info->user_firstname . '<br />';
    echo 'User last name: ' . $user_info->user_lastname . '<br />';
    echo 'User display name: ' . $user_info->display_name . '<br />';
    echo 'User ID: ' . $user_info->ID . '<br />';

}


// this shortcode will set up sending a message
add_shortcode( 'send_push', 'quantum_send_message' );

function quantum_send_message() {
	
	echo '
			<form id="ajax-push-notification" action="' . plugin_dir_url(__FILE__) . 'send_notification.php" method="post">
			<div class="sending-push-to">Sending to: <div id="selected-app"></div></div>
			<p>Title:</p>
			<input id="title" type="text" name="title">
			<p>Message:</p>
			<input id="message" type="text" name="message">
			<p>URL:</p>
			<input id="url" type="text" name="url">
			<p>Schedule:</p>
			<input id="date" type="date" name="date">
			<div class="button-wrapper"><button class="button submit-push" type="submit">Send</button></div>
			<input id="serverkey" type="hidden" name="serverkey">
			</form>
			<div id="form-messages"></div>
		 ';
}


