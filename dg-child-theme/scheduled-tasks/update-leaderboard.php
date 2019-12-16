<?php
/*
**	Daily cron schedule: 
*/
// Add this to the schedule

add_action('init', 'dg_update_leaderboard_cron_task');
function dg_update_leaderboard_cron_task() {
	if (! wp_next_scheduled ( 'dg_update_leaderboard' )) {
	    wp_schedule_event( strtotime('16:20:00'), 'daily', 'dg_update_leaderboard' );
	}
}

add_action('dg_update_leaderboard', 'dg_update_leaderboard_function');
function dg_update_leaderboard_function() {

	$users 					= get_users();
	$i 						= 0;
	$ii 					= 0;
	$tr 					= '';
	$results 				= array();

	foreach($users as $user) {

		$user_id 				= $user->ID;

		// Skip user if it's my id
		if ($user_id == 2 ) {
			continue;
		}

		$i++;
		$stats 							= dg_check_user_stats($user);
		$experience_levels 				= dg_calculate_experience($user_id);
		$stats['level']					= $experience_levels['level'];
		$stats['total_experience'] 		= $experience_levels['total_experience'];
		$stats['username'] 				= $user->user_login;
		$stats['profile_url'] 			= get_author_posts_url( $user_id );
		// put everything into one array
		$results[$i] 					= $stats;

		// Irrelevant?
		$lastSeenRaw 					= $stats['last_seen_raw'];
		$longDurationRaw 				= $stats['longest_duration_raw'];
		$totalDuratioRaw 				= $stats['total_duration_raw'];
		$lastSeenHuman 					= $stats['last_seen_human'];
		$longestDurationHuman 			= $stats['longest_duration_human'];
		$totalDurationHuman 			= $stats['total_duration_human'];
		$totalSessions 					= $stats['total_number_sessions'];

	}

	// Sort by price ascending
	foreach ($results as $key => $row) {
	    $total_duration[$key] = $row['total_duration_raw'];
	}

	array_multisort($total_duration, SORT_DESC, $results);

	// Build rows
	foreach ($results as $result) {

		$ii++;
		$lastSeenRaw 			= $result['last_seen_raw'];
		$longDurationRaw 		= $result['longest_duration_raw'];
		$totalDuratioRaw 		= $result['total_duration_raw'];
		$lastSeenHuman 			= $result['last_seen_human'];
		$longestDurationHuman 	= $result['longest_duration_human'];
		$totalDurationHuman 	= $result['total_duration_human'];
		$time 					= $result['current'];
		$totalSessions 			= $result['total_number_sessions'];
		$username 				= $result['username'];
		$profile_url 			= $result['profile_url'];
		$total_experience 		= number_format($result['total_experience']);
		$level 					= $result['level'];

		// Version with total session:
		//$tr .= '<tr><td>'.$ii.'</td><td><a href="'.$profile_url.'">'.$username.'</a></td><td>'.$totalSessions.'</td><td>'.$level.'</td><td>'.$total_experience.'</td></tr>';

		// Version without sessions:
		$tr .= '<tr><td>'.$ii.'</td><td><a href="'.$profile_url.'">'.$username.'</a></td><td>'.$level.'</td><td>'.$total_experience.'</td></tr>';

		// Only show top 50
		if ($ii == 10) {

			break;

		}

	}

	// Version with sessions
	// $table = '<table class="leaderboard"><thead><tr><td>Position</td><td>Username</td><td>Total no. Sessions</td><td>Level</td><td>Total Experience</td></tr></thead>';

	// Version without sessions
	$table = '<table class="leaderboard"><thead><tr><td>Position</td><td>Username</td><td>Level</td><td>Total Experience</td></tr></thead>';
	$table .= '<tbody>'.$tr.'</tbody></table>';

	file_put_contents( get_stylesheet_directory() . '/html/leaderboard.html', print_r($table, TRUE));

	return $table;

}

/*
**	Print HTML file generated daily
*/
add_shortcode('leaderboard', 'dg_print_static_leaderboard');
function dg_print_static_leaderboard() {

	$leaderboard = file_get_contents( get_stylesheet_directory() . '/html/leaderboard.html');
	return $leaderboard;
	
}
