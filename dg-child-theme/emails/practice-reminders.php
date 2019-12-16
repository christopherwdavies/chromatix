<?php

// Add this to the schedule
add_action('init', 'schedule_practice_reminder');
function schedule_practice_reminder() {
	if (! wp_next_scheduled ( 'dg_daily_cron_check' )) {
	    wp_schedule_event( time(), 'daily', 'dg_daily_cron_check' );
	}
}

//add_shortcode('testing-emails', 'send_practice_reminder');

add_action('dg_daily_cron_check', 'send_practice_reminder');
function send_practice_reminder() {

	$users 	= get_users();
	$i 		= 0;

	foreach ($users as $user) {

		// Check if they are a guitar god
		$guitar_god_check = rcp_user_has_active_membership($user->ID);

		if ($guitar_god_check == false) {

			// error_log($user->ID.' isnt a guitar god so we didnt send an email.');
			continue;

		} else {


			$meta_key = 'dg_preferences';
			$user_preferences = get_user_meta( $user->ID, $meta_key, false );
			$stored_practice_notifications = $user_preferences[0]['practice_notifications'];

			// Kill process if they dont want a reminder
			if ($stored_practice_notifications == 'never') {

				continue;

			} else {

				// If we cna't find the setting, set default to never.
				if ( ! $stored_practice_notifications) {

					$stored_practice_notifications = 'never';

				}

				$stats 					= dg_check_user_stats($user);
				$lastSeenRaw 			= $stats['last_seen_raw'];
				$longDurationRaw 		= $stats['longest_duration_raw'];
				$totalDuratioRaw 		= $stats['total_duration_raw'];
				$lastSeenHuman 			= $stats['last_seen_human'];
				$longestDurationHuman 	= $stats['longest_duration_human'];
				$totalDurationHuman 	= $stats['total_duration_human'];
				$time 					= $stats['current'];
				$bool 					= false;


				// If yes, how long since their last practice and how does that align
				if ($stored_practice_notifications == 'daily') {
					// 86400 seconds in a day
					$day = 86400;
					if ($lastSeenRaw > $day) {
						$bool = true;
					}
				} elseif ($stored_practice_notifications == 'weekly') {
					// 86400 * 7 seconds in a week
					$week = 86400 * 7;
					if ($lastSeenRaw > $week) {
						$bool = true;
					}
				} elseif($stored_practice_notifications == 'monthly') {
					// 86400 * 7 * 4 seconds in a month
					$month = 86400 * 7 * 4;
					if ($lastSeenRaw > $month) {
						$bool = true;
					}
				}

				// If condition has been met, shoot email.
				if ($bool == true) {
					// run email function and send relevant user object
					send_practice_reminder_email($user, $stats);
					$i++;

					// error_log($user->ID.' conditions have been met so we sending email.');

				} else {

					// error_log($user->ID.' has practiced recently so an email wasnt needed.');

				}
			}
		}		
	}

	error_log('Davies Guitars practice reminder cron job has been executed.' . $i . ' emails have been sent.');

}

function send_practice_reminder_email($user, $stats) {

	$user_id 			= $user->ID;
	$user_email 		= $user->user_email;
	$user_first_name 	= $user->user_firstname;
	$lastSeenHuman 		= $stats['last_seen_human'];

	$admin_email = 'Davies Guitars';
	$headers = 'From: '. $admin_email . "\r\n" .'Reply-To: ' . $admin_email . "\r\n" . 'Content-Type: text/html; charset=utf-8' . "\r\n";

	$to = $user_email;
	$subject = "Hey ".$user_first_name.", Just a Little Practice Reminder";

	$message = '<br><h2>Hey '.$user_first_name.',</h2><br><p>How\'s it going? Just sending you a little reminder to jump back on the riff stick.</p><p>You havent been on for a while, we\'re trying to help make sure you keep on top of your practice routine.<p>';
	$message .= '<strong>Your last practice was ' . $lastSeenHuman.' ago.</strong>';
	$message .= '<p>You can launch back into your <a href="https://daviesguitars.io/guitar-exercises/">guitar exercises here</a>.</p>';
	$message .= '<p>This email was sent because your preferences are set up to send you notifications when you haven\'t been on in a while. If you want to change those preferences you can do so <a href="https://daviesguitars.io/my-account/notification-preferences/">here</a>.</p>';
	$message .= 'Much love,<br>The team at Davies Guitars<br><br>';
	
	$sent = wp_mail($to, $subject, $message, $headers);

}