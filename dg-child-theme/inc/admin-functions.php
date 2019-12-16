<?php
/*
 * Create a column. And maybe remove some of the default ones
 * @param array $columns Array of all user table columns {column ID} => {column Name} 
 */
add_filter( 'manage_users_columns', 'rudr_modify_user_table' );
 
function rudr_modify_user_table( $columns ) {
 
	unset( $columns['posts'] ); // maybe you would like to remove default columns
	$columns['registration_date'] = 'Registration date'; // add new
 
	return $columns;
 
}
 
/*
 * Fill our new column with the registration dates of the users
 * @param string $row_output text/HTML output of a table cell
 * @param string $column_id_attr column ID
 * @param int $user user ID (in fact - table row ID)
 */
add_filter( 'manage_users_custom_column', 'rudr_modify_user_table_row', 10, 3 );
 
function rudr_modify_user_table_row( $row_output, $column_id_attr, $user ) {
 
	$date_format = 'j M, Y H:i';
 
	switch ( $column_id_attr ) {
		case 'registration_date' :
			return date( $date_format, strtotime( get_the_author_meta( 'registered', $user ) ) );
			break;
		default:
	}
 
	return $row_output;
 
}
 
/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param} 
 */
add_filter( 'manage_users_sortable_columns', 'rudr_make_registered_column_sortable' );
 
function rudr_make_registered_column_sortable( $columns ) {
	return wp_parse_args( array( 'registration_date' => 'registered' ), $columns );
}

// Remove menu items I dont want
add_action('admin_menu', 'remove_menus' );
function remove_menus(){
	remove_menu_page( 'edit.php?post_type=thegem_gallery' );       //Gallery
	remove_menu_page( 'edit.php?post_type=thegem_pf_item' );       //Portfolio
	remove_menu_page( 'edit.php?post_type=thegem_qf_item' );       //Quickfinders
	remove_menu_page( 'edit.php?post_type=thegem_team_person' );   //Teams
	remove_menu_page( 'edit.php?post_type=thegem_client' );        //Clients
	remove_menu_page( 'edit.php?post_type=thegem_testimonial' );   //Testimonials
	remove_menu_page( 'edit.php?post_type=thegem_news' );          //News
	remove_menu_page( 'edit.php?post_type=thegem_slide' );         //Slide
	remove_menu_page( 'edit.php?post_type=thegem_footer' );        //Footer
	remove_menu_page( 'admin.php?page=thegem-import-submenu-page' );//Import Theme Elements
}
// Create custom dashboard widget
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('chris_dashboard', 'Chris\' Dashboard', 'chris_dashboard');
}

function chris_dashboard() {
	$available_space 	= round(disk_total_space( '/' ) / 1073741824,2);
	// $available_space 	= formatSizeUnits(disk_total_space( '/' ));
	$free_space 		= round(disk_free_space( '/' ) / 1073741824,2);
	$used_space			= $available_space - $free_space;
	$php_memory_limit	= ini_get('memory_limit');
	$php_mem_usage 		= formatSizeUnits(memory_get_usage(true));
	$php_peak_mem_usage = formatSizeUnits(memory_get_peak_usage(true));
	$users_object		= count_users();

	echo '<p>Total HDD space: <strong>'.$available_space.' GB</strong></p>';
	echo '<p>Space Available: <strong>'.$free_space.' GB</strong></p>'; 
	echo '<p>Space Used: <strong>'.$used_space.' GB</strong></p>'; 
	echo '<p>PHP Memory Allocation: <strong>'.$php_memory_limit.'</strong></p>'; 
	echo '<p>PHP Memory Used: <strong>'.$php_peak_mem_usage.'</strong></p>'; 
	echo '<p>Number of Users: <strong>'.$users_object['total_users'].'</strong></p>';
}
// Adds custom post types to admin dashboard
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
    	}
    }
}