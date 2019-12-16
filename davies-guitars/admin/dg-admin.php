<?php

/**
 *
 *
 *	Load admin css stylesheet
 *
 **/
// Update CSS within in Admin
add_action('admin_enqueue_scripts', 'dg_admin_style_script');

function dg_admin_style_script() {

	wp_enqueue_style('dg-admin-css', plugins_url( 'admin/css/dg-admin.css' , dirname(__FILE__)) );
	wp_enqueue_script('dg-admin-js', plugins_url( 'admin/js/dg-admin.js' , dirname(__FILE__)) );

	//plugins_url( 'js/dg-admin.js' , dirname(__FILE__) )
}


/**
**
** https://scottdeluzio.com/create-tabbed-settings-pages-for-wordpress-plugins/
** Create pages
**
*/
add_action('admin_menu', 'dg_admin_settings_setup');

function dg_admin_settings_setup() {

	/**
	 *
	 *	@param add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
	 *	@param add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
	 *
	 **/

	add_menu_page('Davies Guitars', 'Davies Guitars', 'manage_options', 'dg-dashboard', 'dg_admin_page', 'https://daviesguitars.io/wp-content/uploads/2019/11/dg-16x16.png', 1);
	add_submenu_page( 'dg-dashboard', 'Activity', 'Activity', 'manage_options', 'dg-activity', 'dg_activity_page' );
	add_submenu_page( 'dg-dashboard', 'Settings', 'Settings', 'manage_options', 'dg-settings', 'dg_settings_page' );
	add_submenu_page( 'dg-dashboard', 'Debug', 'Debug', 'manage_options', 'dg-debug', 'dg_debug_page' );

}

/*
**
**	Nav tabs - Global
**
*/
add_action( 'dg_admin_tabs', 'dg_nav_tabs', 1 );
function dg_nav_tabs(){

	if ( isset( $_GET['page'] ) ) { $active_page = $_GET['page']; } ?>

		<a class="nav-tab <?php if ($active_page == 'dg-dashboard') { echo 'nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=dg-dashboard' ); ?>"><?php _e( 'Dashboard', 'dg' ); ?> </a>
		<a class="nav-tab <?php if ($active_page == 'dg-activity') { echo 'nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=dg-activity' ); ?>"><?php _e( 'Activity', 'dg' ); ?> </a>
		<a class="nav-tab <?php if ($active_page == 'dg-settings') { echo 'nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=dg-settings' ); ?>"><?php _e( 'Settings', 'dg' ); ?> </a>
		<a class="nav-tab <?php if ($active_page == 'dg-debug') { echo 'nav-tab-active'; } ?>" href="<?php echo admin_url( 'admin.php?page=dg-debug' ); ?>"><?php _e( 'Debug', 'dg' ); ?> </a>
	<?php
}

/**
 *
 * Admin Dashboard
 *
 **/
// Callback to set template
function dg_admin_page(){ ?>
 
	<h2 class="nav-tab-wrapper"> <?php do_action( 'dg_admin_tabs' ); ?> </h2>

	<?php do_action( 'dg_admin_dashboard_page' );

}

// Callback to add contents to page
add_action( 'dg_admin_dashboard_page', 'dg_dashboard_page_content' );
function dg_dashboard_page_content() {

	$available_space 	= round(disk_total_space( '/' ) / 1073741824,2);
	// $available_space 	= formatSizeUnits(disk_total_space( '/' ));
	$free_space 		= round(disk_free_space( '/' ) / 1073741824,2);
	$used_space			= $available_space - $free_space;
	$php_memory_limit	= ini_get('memory_limit');
	$php_mem_usage 		= formatSizeUnits(memory_get_usage(true));
	$php_peak_mem_usage = formatSizeUnits(memory_get_peak_usage(true));
	$users_object		= count_users();

	$users 				= get_users( );
    $meta 				= get_user_meta( 2, 'interested_in_learning', TRUE );
    $count 				= array();
    $results 			= array();
    $total 				= 0;

    foreach( $users as $user ) {

    	$interest = get_user_meta( $user->ID, 'interested_in_learning', TRUE );

    	if ( empty($interest) ) {
    		continue;
    	}

    	if ( isset($count[$interest]) ) {

    		$results[$interest] = $count[$interest]++;

    	} else {

    		$count[$interest] = 1;

    	}

    	$results['total'] = $total++;
    }

	?>
 
	<h3><?php _e( 'Dashboard', 'dg' ); ?></h3>

	<div class="wrap">

		<table class="widefat fixed" cellspacing="0" style="width: 47%; margin-right: 2%; float: left; clear: none;">
			<thead>
			    <tr>
			        <th>Stat</th>
			        <th>Value</th>
			    </tr>
			</thead>
			<tfoot>
			    <tr>
			        <th>Stat</th>
			        <th>Value</th>  
			    </tr>
			</tfoot>
			<tbody>
			   	<tr>
					<td><?php echo 'Total HDD Space' ?></td>
					<td><?php echo '<strong>'.$available_space.' GB</strong>'; ?></td>
			   	</tr>
			   	<tr>
					<td><?php echo 'Space Available' ?></td>
					<td><?php echo '<strong>'.$free_space.' GB</strong>' ?></td>
			   	</tr>
			   	<tr>
					<td><?php echo 'Space Used' ?></td>
					<td><?php echo '<strong>'.$used_space.' GB</strong>' ?></td>
			   	</tr>
			   	<tr>
					<td><?php echo 'PHP Memory Allocation' ?></td>
					<td><?php echo '<strong>'.$php_memory_limit.'</strong>' ?></td>
			   	</tr>
			   	<tr>
					<td><?php echo 'PHP Memory Used' ?></td>
					<td><?php echo '<strong>'.$php_peak_mem_usage.'</strong>' ?></td>
			   	</tr>
			   	<tr>
					<td><?php echo 'Number Of Users' ?></td>
					<td><?php echo '<strong><a href="/wp-admin/users.php">'.$users_object['total_users'].'</a></strong>' ?></td>
			   </tr>
			</tbody>
		</table>

		<table class="widefat fixed" cellspacing="0" style="width: 47%; margin-right: 2%; float: left; clear:none;">
			<thead>
			    <tr>
			        <th colspan="2">Users are interested in</th>
			    </tr>
			</thead>
			<tbody>
				<?php foreach($results as $key => $val) : ?>
					<?php if ($key == 'total') : continue; endif; ?>
					<?php $percentage = round(($val / $results['total']) * 100, 2); ?>
					<tr>
						<td><?php echo ucfirst(str_replace('-', ' ', $key)); ?></td>
						<td><?php echo $val . '/' . $results['total'] . ' <strong>(' . $percentage . '%)</strong>'; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</div>

	<!-- Put your content here -->
	<?php

}

/**
 *
 * Admin Activity
 *
 **/

// Callback to set template
function dg_activity_page(){ ?>
 
	<h2 class="nav-tab-wrapper"> <?php do_action( 'dg_admin_tabs' ); ?> </h2>

	<?php do_action( 'dg_admin_activity_page' );

}

// Callback to add contents to page
add_action( 'dg_admin_activity_page', 'dg_activity_page_content' );
function dg_activity_page_content() {

	?>
 
	<h3><?php _e( 'Activity', 'dg' ); ?></h3>

	<!-- Put your content here -->
	<?php

	if ( ! class_exists( 'WP_List_Table' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class DG_Activity_Table extends WP_List_Table {

		// https://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
		// https://gist.github.com/Latz/7f923479a4ed135e35b2

		public $activity_data;

		function get_columns(){

		  $columns = array(
		    'date' 			=> 'Date',
		    'ip-address'    => 'IP Address',
		    'user-id'      	=> 'User ID',
		    'category'      => 'Category',
		    'activity'      => 'Activity'
		  );

		  return $columns;

		}

		function prepare_activity_log() {

			$csv_array = array();
			$results = array();

			$file = fopen( get_stylesheet_directory_uri() . '/activity.csv', 'rb' );
			while ( ! feof($file)) {

				$csv_array[] = fgetcsv($file);

			}
			fclose($file);

			foreach($csv_array as $row) {

				foreach($row as $data) {

					if ($row[2] !== 'Guest') {
						$user = get_user_by( 'login', $row[2] );
						if ($user != false) {
							$profile = get_author_posts_url( $user->ID );
							$row[2] = '<a href="' . $profile . '">' . $row[2] . '</a>';
						}
					}

					$result['date'] 		= $row[0];
					$result['ip-address'] 	= $row[1];
					$result['user-id'] 		= $row[2];
					$result['category'] 	= $row[3];
					$result['activity'] 	= $row[4];

				}

				$results[] = $result;
			}

			$results = array_reverse($results);
			return $results;

		}

		function prepare_items() {

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = array();
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->items = $this->prepare_activity_log();

		}

		function column_default( $item, $column_name ) {

		  switch( $column_name ) { 
		    case 'date':
		    case 'ip-address':
		    case 'user-id':
		    case 'category':
		    case 'activity':

		    return $item[ $column_name ];

		    default:

		    	return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes

		  	}

		}

	}

	$myListTable = new DG_Activity_Table();

	echo '<div class="wrap">'; 

	$myListTable->prepare_items(); 
	$myListTable->display(); 
/*	echo '<pre>';
	print_r( $myListTable->prepare_activity_log() );*/

	echo '</div>'; 

}

/**
 *
 * Admin Settings
 *
 **/
// Callback to set template
function dg_settings_page(){ ?>
 
	<h2 class="nav-tab-wrapper"> <?php do_action( 'dg_admin_tabs' ); ?> </h2>

	<?php do_action( 'dg_admin_settings_page' );

}

// Callback to add contents to page
add_action( 'dg_admin_settings_page', 'dg_settings_page_content' );
function dg_settings_page_content() {

	?>
 
	<h3><?php _e( 'Settings', 'dg' ); ?></h3>

	<!-- Put your content here -->
	<div class="wrap">
		<form method="post" action="options.php">

			<?php
				// display settings field on theme-option page
				settings_fields("dg-site-settings");
				// display all sections for theme-options page
				do_settings_sections("dg-settings");
				submit_button();
			?>

		</form>
	</div>
	<?php

}

/**
 *
 * Admin Settings
 *
 **/
// Callback to set template
function dg_debug_page(){ ?>
 
	<h2 class="nav-tab-wrapper"> <?php do_action( 'dg_admin_tabs' ); ?> </h2>

	<?php do_action( 'dg_admin_debug_page' );

}

// Callback to add contents to page
add_action( 'dg_admin_debug_page', 'dg_debug_page_content' );
function dg_debug_page_content() {

	?>
 
	<h3><?php _e( 'Debug', 'dg' ); ?></h3>

	<!-- Put your content here -->
	<div class="wrap">
		<div class="debug-wrap" style="background-color: white; padding: 15px; font-size: 18px; border-radius: 3px;">
		<?php 

			$file = file_get_contents( get_stylesheet_directory_uri() . '/debug.txt' );
			$file = explode("\n", $file);
			$file = array_reverse($file);

			foreach($file as $line) {

				echo '<p>' . $line . '</p>';

			}

		?>
		</div>

	</div>
	<?php

}

add_action('admin_init','test_theme_settings');
function test_theme_settings(){


	add_settings_section ( 
		'first_section', 				// ID
		'Davies Guitars Admin Settings',// Title / heading
		'dg_site_settings_description', 	// Echos content between heading and fields
		'dg-settings' 					// Slug of page to add section to 
	);

	// Lean admin
	add_option('dg_minimal_dashboard',1);// add theme option to database
	add_settings_field (
		'dg_minimal_dashboard', 		// ID
		'Lean Admin Dashboard', 		// Title 
		'minimal_dashboard_callback', 			// Creates the input / html
		'dg-settings', 					// Slug of page to add content to
		'first_section' 				// Slug of the section to add it to
	);

	// Lean admin
	add_option('dg_send_daily_email',1);// add theme option to database
	add_settings_field (
		'dg_daily_report', 		// ID
		'Send Daily Report', 		// Title 
		'daily_report_callback', 			// Creates the input / html
		'dg-settings', 					// Slug of page to add content to
		'first_section' 				// Slug of the section to add it to
	);

	register_setting( 'dg-site-settings', 'dg_minimal_dashboard');
	register_setting( 'dg-site-settings', 'dg_daily_report');

}

function dg_site_settings_description(){

	echo '<p>Various settings for managing the Davies Guitars website.</p>';

}

function minimal_dashboard_callback(){

	$options = get_option( 'dg_minimal_dashboard' );

	echo '<input name="dg_minimal_dashboard" id="dg_minimal_dashboard" type="checkbox" value="1" class="code" ' . checked( 1, $options, false ) . ' /> <label for="dg_minimal_dashboard">Check to hide excessive admin menu items.</label>';
}

function daily_report_callback(){

	$options = get_option( 'dg_daily_report' );

	echo '<input name="dg_daily_report" id="dg_daily_report" type="checkbox" value="1" class="code" ' . checked( 1, $options, false ) . ' /> <label for="dg_daily_report">Check to send daily summary report.</label>';
}

add_action( 'admin_menu', 'wpexplorer_remove_menus', 9999 );
function wpexplorer_remove_menus() {

	// dg_debug( '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>' );
	// Check key 2 to see what the 'ID' is for removing the data

	$options = get_option( 'dg_minimal_dashboard' );

	if ($options == 1) {

	    $array = array(

	    	'edit.php?post_type=guitar_tab',
		    'edit.php',
		    'edit.php?post_type=coupon',
		    'edit.php?post_type=guitar_tone',
		    'edit.php?post_type=popup',
		    'edit.php?post_type=shop_order',
		    'edit.php?post_type=product',
		    'edit.php?post_type=search-filter-widget',
		    'wpseo_dashboard',
		    'wpcf7',
		    'woocommerce',
		    'faulh-admin-listing',
		    'wordpress_adv_bulk_edit',
		    'vc-general',
		    'pixelyoursite',
		    'rate-my-post',
		    'woo-product-feed-pro/woocommerce-sea.php',
		    'mailchimp-for-wp',
		    'gf_edit_forms',
		    'wp-mail-smtp',
		    'wp-user-avatar',
		    'pods',
		    'woodiscuz_options_page'
		    
		);

		foreach ($array as $a) {

	    	remove_menu_page( $a );

		}

	}


}
