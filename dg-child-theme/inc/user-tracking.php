<?php
/*
**
**  @ Print analytics relating to guitar tab activity
**
*/
add_shortcode('guitar-tab-analytics', 'dg_print_guitar_tab_analytics');
function dg_print_guitar_tab_analytics() {

    ob_start();

    $user_id        = get_current_user_id();
    $meta_key       = 'dg_track_guitar_exercises';
    $tab_analytics  = get_user_meta( $user_id, $meta_key, FALSE )[0];

    ?>
    <style type="text/css">
        .stat-wrapper {
            text-align: center;
            margin-bottom: 25px !important;
            background: white;
        }
        .stat-wrapper * {
            color: #262626;
        }
        .stat-wrapper .stat {
            display: inline-block;
        }
        .stat-wrapper .stat {
            display: inline-block;
            margin-right: 10px;
            padding-right: 10px;
            border-right: solid 1px #efefef;
        }
        .stat-wrapper .stat .stat-number {
            font-weight: 600;
        }
        .stat-wrapper i {
            color: #b30909;
        }
        .stat-wrapper .subtitle {
            background: black;
            color: white;
            padding: 10px;
            margin-bottom: 0px;
        }
        .stats-wrapper {
            padding: 25px 10px;
        }
    </style>
    <?php

    echo '<div class="row">';
    foreach ($tab_analytics as $tab) {

        // Array data
        $tab_id         = $tab['id'];
        $tab_plays      = $tab['play']['count'];
        $tab_pause      = $tab['pause']['count'];
        $tab_finished   = $tab['finished']['count'];

        // Post data
        $title = get_the_title( $tab_id );
        $url = get_permalink( $tab_id );

        ?>

            <div class="col-md-12">
                <div class="stat-wrapper box-shadow">
                    <a href="<?php echo $url ?>">
                        <p class="subtitle"><?php echo $title ?></p>
                        <div class="stats-wrapper">
                            <div class="stat"><span class="stat-text">Played</span> <i class="fas fa-play"></i> <span class="stat-number"><?php echo $tab_plays ?></span></div>
                            <div class="stat"><span class="stat-text">Paused</span> <i class="fas fa-pause"></i> <span class="stat-number"><?php echo $tab_pause ?></span></div>
                            <div class="stat"><span class="stat-text">Finished</span> <i class="fas fa-flag"></i> <span class="stat-number"><?php echo $tab_finished ?></span></div>
                        </div>
                    </a>
                </div>
            </div>

        <?php
    }
    echo '</div>';

    return ob_get_clean();

}

// Dump user activity
add_shortcode('user-key-stats', 'user_key_stats');
function user_key_stats() {

    ob_start();

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
	$user 		= wp_get_current_user();
	$user_id 	= $user->ID;
    $where_query;
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

    // Clean
    //$lastSeenClean 		= human_time_diff(strtotime($lastSeen));
    //$maxDurationClean 	= human_time_diff(strtotime($maxDuration));
    $lastSeenClean 			= secondsToWords(time() - strtotime($results[1]['time_last_seen']));
    $maxDurationClean 		= secondsToWords($maxDuration);
    $totalDurationClean 	= secondsToWords($totalDuration);

	/*

		@param Start displaying results
		
	*/
	?>
    <style>
        table.sessions-table tbody td {
            background-color: white;
        }
    </style>
	<div class="row session-overview">
		<div class="last-logged-in col-md-3 col-xs-12 col-sm-6">
			<div class="wrapper">
				<span class="hero"><?php echo $lastSeenClean; ?></span>
				<div class="text">Last Session</div>
			</div>
		</div>

	   	<div class="longest-session col-md-3 col-xs-12 col-sm-6">
	   		<div class="wrapper">
		   		<span class="hero"><?php echo $maxDurationClean; ?></span>
				<div class="text">Longest Session</div>
			</div>
	   	</div>

	   	<div class="total-session col-md-3 col-xs-12 col-sm-6">
	   		<div class="wrapper">
		   		<span class="hero"><?php echo $totalDurationClean; ?></span>
				<div class="text">Total Session Time</div>
			</div>
	   	</div>

	    <div class="number-sessions col-md-3 col-xs-12 col-sm-6">
	   		<div class="wrapper">
		   		<span class="hero title-h3"><?php echo $i; ?></span>
				<div class="text">Number of Sessions</div>
			</div>
	   	</div>
	</div>
   	<?php

    return ob_get_clean();

}

// Dump user activity
add_shortcode('activity-history', 'user_activity_history');
function user_activity_history() {

    ob_start();

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
	$user 		= wp_get_current_user();
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

	// Loop through results
    foreach($results as $result) {

    	// Create vars
    	$i++;
    	$duration 				= human_time_diff(strtotime($result['time_login']), strtotime($result['time_last_seen']));
    	$time_last_seen_unix 	= strtotime($result['time_last_seen']);
    	$last_seen 				= human_time_diff($time_last_seen_unix);

    	// build session table body
    	$rows[$i] = '<tr><td>'.$i.'</td><td>'.$duration.'</td><td>'.$last_seen.' ago</td></tr>';

    }

	/*

		@param Start displaying results
		
	*/
	?>

   	<table class="sessions-table">
   		<thead>
   			<td>Session</td>
   			<td>Duration</td>
   			<td>Last Seen</td>
   		</thead>
   		<tbody>
   			<?php 
                $ii = 0;
                foreach($rows as $row) {

                    /* limit results to last 10     
                    if ($ii == 10) {
                        break;
                    }*/
                    echo $row;
                    $ii++;
                } 

            ?>
   		</tbody>
   	</table>
<!--     <p>Only showing last 10 results</p>
 -->
   	<?php

    return ob_get_clean();

}


// Last login
function check_last_login() {
	$last_login = get_the_author_meta('wc_last_active');
    $the_login_date = human_time_diff($last_login);
    return $the_login_date;
}

/*//Retrieve analytic info
function retrieve_exercise_analytics() {

	$user = wp_get_current_user();
	$user_id = $user->ID;
	$meta_key = 'dg_exercises';
	$data = unserialize(get_user_meta( $user_id, $meta_key, false ));

	// print_r($data);

	print_r(get_user_meta($user_id, $meta_key));

}
*/


// Script to update guitar exercises tracking
#
#
#   Maybe by default, add it to saved tabs as well. <--
#   tab_event: 
#
add_action('wp_footer', 'dg_track_guitar_tabs_script');
function dg_track_guitar_tabs_script() {

    if ( is_user_logged_in() ) :  //only print on tabs pages

            $post_id = get_the_ID();
            $user_id = get_current_user_id();
            $admin_url = admin_url( 'admin-ajax.php' );

        ?>

        <script type="text/javascript">

            function dg_track_guitar_tabs_script(tab_event = null) {

                var ajaxurl = '<?php echo $admin_url ?>';
                var user_id = <?php echo $user_id; ?>;
                var post_id = <?php echo $post_id; ?>;

                if (!event) {
                    event = window.event;
                }

                var el = (event.target || event.srcElement); 

                var data = {
                    action: 'dg_track_guitar_tabs_store_meta',
                    post_id: post_id,
                    user_id: user_id,
                    tab_event: tab_event
                };

                jQuery.post(ajaxurl, data, function(response) {

                    // console.log(response);

                    if (tab_event == 'finished') {
                        showDialog(response, 'Congratulations!');
                    }

                });

            }

        </script>

        <?php 

    endif;

}



/*
**
**  @ Track number of times an exercise has been completed
**  
**
**  @ Triggered by completion of tab on exercise page
**  
*/

// Set tab prorgess ajax action
add_action( 'wp_ajax_dg_track_guitar_tabs_store_meta', 'dg_track_guitar_tabs_store_meta' );
add_action( 'wp_ajax_nopriv_dg_track_guitar_tabs_store_meta', 'dg_track_guitar_tabs_store_meta' );

// add_shortcode('exercise-tracking', 'dg_track_guitar_exercises_store_meta');
function dg_track_guitar_tabs_store_meta() {

    if ( isset($_POST['user_id']) ) {
        $user_id = $_POST['user_id'];
    }   
    if ( isset($_POST['post_id']) ) {
        $post_id = $_POST['post_id'];
    }
    if ( isset($_POST['tab_event']) ) {
        $tab_event = $_POST['tab_event'];
    }

    $meta_key = 'dg_track_guitar_exercises';
    $original_data = get_user_meta( $user_id, $meta_key, FALSE )[0];
    $bool = false;
    $count = 1;
    $response;

    // If they don't have any information
    if ( empty( $original_data ) ) {

        $new_data[0] = array(
            'id' => $post_id,
            $tab_event => array('count' => $count),
            'timestamp' => time()
        );

        $response = 'Congratulations on completing your first ever tab! Well done!';

    } else {

        $new_data = $original_data;
        $i = 0;

        // Check if user has done this post id before
        foreach ( $new_data as $nd ) {

            if ($nd['id'] == $post_id) {

                // Add 1 to count
                if ( isset($new_data[$i][$tab_event]['count']) ) {

                    $count = $new_data[$i][$tab_event]['count'];
                    // echo 'Count is set and it is currently: ' . $count . '.';

                } else {
                    //echo 'Count is not found and we are starting from 0.';
                    $count = 0;

                }

                $count++;

                $new_data[$i][$tab_event]['count'] = $count;

                // Update timestamp
                $new_data[$i]['timestamp'] = time();

                // Send response
                $response = 'Congratulations, you\'ve now completed this tab ' . $count . ' times!';

                if ($count == 25) {

                    $response = 'Congratulations!! Now that  you\'ve now completed this tab ' . $count . ' times it will be marked as finished in your account.';

                }

                // Prevent other data manipulation
                $bool = true;

            }

            // Move onto next array element
            $i++;

        }

        if ($bool == false) {

            // change var to new format
            $new_data       = $original_data;

            // echo 'Found a new tab, adding it to stack.';
            $new_data[]     = array(

                'id' => $post_id,
                $tab_event => array('count' => $count),
                'timestamp' => time()
            );

            $response = 'Congratulations! That\'s the first time you\'ve completed this tab. You\'re killin\' it!';

        }

    }

    // Save data
    update_user_meta( $user_id, $meta_key, $new_data );

    // Check stored vals
    $stored_data = get_user_meta( $user_id, $meta_key, FALSE )[0];

    dg_activity( 'Guitar tab activity: '. print_r($stored_data, TRUE), 'Guitar Tabs');

    // Print response
    echo $response;

    // Let's clear it
    // delete_user_meta($user_id, $meta_key);

    // Exit
    wp_die();

}

/*
**
**      Creates graph visualising number of logins / time spent
**      Require the following scripts
**      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
**      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />**
*/
add_shortcode('login-history-graph', 'dg_user_login_history_graph');
function dg_user_login_history_graph() {

    ob_start();

    $login_history = dg_check_user_stats_return_each_session();
    $last_30_days    = dg_return_array_last_x_days(30, "M-d");
    // $last_30_days  = array_reverse($days_array);
    $results       = array();
    $i;

    foreach($last_30_days as $day) {

        $i = 0;

        foreach($login_history as $login) {

            $date = date("M-d", strtotime($login['date']) ) ;
            $duration = $login['duration'];

            if ($date == $day) {

                $results[$day]['duration']              += $duration;
                $results[$day]['count']                 =  $i + 1;
                $results[$day]['data'][$i]['duration']  =  $duration;
                $results[$day]['data'][$i]['date']      =  $date;

                $i++;
            }
        }

        if ( ! isset($results[$day])) {
            $results[$day]['count']     = 0;
            $results[$day]['duration']  = 0;
        }
    }

    ?>

    <div class="login-data-container" style="height: 400px;">
        <canvas id="login-data-line-chart" width="200" height="300"></canvas>
    </div>
    <script type="text/javascript">

        var ctxLoginGraph    = jQuery('#login-data-line-chart');
        ctxLoginGraph.height = 400;

        var myExerciseChart = new Chart(ctxLoginGraph, {
            type: 'line',
            data: {
                labels: [ <?php foreach($results as $key => $value) { echo '"'.$key.'",'; } ?> ],
                datasets: [{
                    label: '# of Sessions',
                    data: [<?php foreach($results as $key => $value) { echo '"'.$value['count'].'",'; } ?>],
                    backgroundColor: 'rgba(0, 191, 255, 0.5)',
                    borderColor: 'rgba(0, 191, 255, 1)',
                    pointBackgroundColor: 'rgba(0, 191, 255, 1)',
                    pointBorderColor: 'rgba(0, 191, 255, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

    </script>
    <?php

    return ob_get_clean();
}