<?php
add_shortcode('account-preferences', 'account_preferences');
function account_preferences() { 

	ob_start();

	account_preference_processing();

	if ( ! empty($_POST) ) {
		// echo 'You\'ve selected ';
	} else {
		// echo 'Submit below.';
	}

	// Let's see if they've got the meta data stored
	$user_id = get_current_user_id();
	$meta_key = 'dg_preferences';
	$user_preferences = get_user_meta( $user_id, $meta_key, false );

	$stored_practice_notifications 	= $user_preferences[0]['practice_notifications'];
	$stored_monthly_summary 		= $user_preferences[0]['monthly_summary'];
	$stored_charity_setting 		= $user_preferences[0]['charity_setting'];

	// Set defaults.
	if ( ! $stored_practice_notifications) {

		$stored_practice_notifications = 'never';

	}

	if ( ! $stored_monthly_summary) {

		$stored_monthly_summary = 'yes';

	}

	if ( ! $stored_charity_setting) {

		$stored_charity_setting = 'musicians_on_call';

	}

	?>
	<div id="content">

	    <form action="" method="post" class="settings" id="dg_practice_notifications">

	    	<div class="styled-subtitle">Practice Reminders</div>
			<label for="dg_practice_notification">How often would you like to be reminded about practising? This can be useful to maintain consistency with your practice schedule.</label>
			<select name="dg_practice_notification">
				<option value="daily" <?php dg_check_stored_value($stored_practice_notifications, 'daily') ?>>Daily</option>
				<option value="weekly" <?php dg_check_stored_value($stored_practice_notifications, 'weekly') ?>>Weekly</option>
				<option value="monthly" <?php dg_check_stored_value($stored_practice_notifications, 'monthly') ?>>Monthly</option>
				<option value="never" <?php dg_check_stored_value($stored_practice_notifications, 'never') ?>>Never</option>
			</select>

	    	<div class="styled-subtitle">Monthly Performance Report</div>
			<label for="dg_monthly_summary">Would you like to receive summary emails about your performance each month?</label>
			<select name="dg_monthly_summary">
				<option value="yes" <?php dg_check_stored_value($stored_monthly_summary, 'yes') ?>>Yes</option>
				<option value="no" <?php dg_check_stored_value($stored_monthly_summary, 'no') ?>>No</option>
			</select>

			<div class="styled-subtitle" style="display: none;">Charity Preference</div>
			<label for="dg_charity_setting" style="display: none;">We are proud to say that 10% of your monthly fee goes toward a charity of your choice. Select your preference from the list below. <a href="/contact/">Let us know</a> if there is another musician-centric charity you would like to see available.</label>
			<select name="dg_charity_setting" style="display: none;">
				<option value="musicians_on_call" <?php dg_check_stored_value($stored_monthly_summary, 'musicians_on_call') ?>>Musicians On Call</option>
				<option value="musicians_foundation" <?php dg_check_stored_value($stored_monthly_summary, 'musicians_foundation') ?>>Musicians Foundation</option>
				<option value="musicfund" <?php dg_check_stored_value($stored_monthly_summary, 'musicfund') ?>>MusicFund</option>
				<option value="women_in_music" <?php dg_check_stored_value($stored_monthly_summary, 'women_in_music') ?>>Women In Music</option>
				<option value="hungry_for_music" <?php dg_check_stored_value($stored_monthly_summary, 'hungry_for_music') ?>>Hungry For Music</option>
				<option value="musicares" <?php dg_check_stored_value($stored_monthly_summary, 'musicares') ?>>MusiCares</option>
			</select>

	        <input type="submit" value="Update">

	    </form>
	</div>
	<?php

/*	echo '<pre>';
	print_r($user_preferences);
	echo '</pre>';*/

	return ob_get_clean();

}

function dg_check_stored_value($pref, $current) {
	if ($pref == $current) {
		echo 'selected="selected"';
	} else {
		return false;
	}
}

// Process data
function account_preference_processing() {

	if ( ! empty($_POST) ) {

		$user_id 						= get_current_user_id();
		$meta_key 						= 'dg_preferences';

		$practice_notification 			= sanitize_text_field($_POST['dg_practice_notification']);
		$monthly_summary 				= sanitize_text_field($_POST['dg_monthly_summary']);
		$charity_setting 				= sanitize_text_field($_POST['dg_charity_setting']);

		$preferences = array(
			'practice_notifications' 	=> $practice_notification,
			'monthly_summary' 			=> $monthly_summary,
			'charity_setting' 			=> $charity_setting
		);

		update_user_meta( $user_id, $meta_key, $preferences );

		echo '<div class="success-notification">Your preferences have been updated succesfully.</div>';

	} else {

		return false;

	}
}

/*
**
**		Saving posts to account 
**		Guitar tones, Tabs
**
*/

// Add button to guitar tones
add_action('woocommerce_after_shop_loop_item', 'dg_save_post_button', 55);
add_action('thegem_woocommerce_single_product_right', 'dg_save_post_button', 65);

// Add to guitar tab listing
add_action('guitar_tab_extra_content', 'dg_save_post_button', 10); // tab listings
add_action('above_tab_content', 'dg_save_post_button', 10); // tab single page

function dg_save_post_button() {

	global $post;
	$post_id 	= $post->ID;
	$post_type 	= get_post_type( $post_id );

	if ($post_type == 'product') {
		$post_type = 'guitar_tone';
	}

	$save_string 	= ' Save';
	$remove_string 	= ' Remove';
	$save_class 	= '';
	$remove_class	= '';

	// Do things if it's a product page
	if ( is_product() ) {
		
		$save_string = ' Save This Tone';
		$save_class = 'grey';
		$remove_class = 'grey';

	}

	// Do things if it's a guitar tab page
	if ( is_singular( 'guitar_tab' ) ) {
		$save_string = ' Save This Tab';
		$save_class = 'grey small';
		$remove_class = 'grey small';
	}

	// Print fake button if they're not logged in.
	if ( ! is_user_logged_in() ) {

		echo '<div class="save-remove-wrapper"><a class="product-meta-attribute dg_save_post save logged-in-popup gem-button '.$save_class.'"><i class="fas fa-save"></i>'.$save_string.'</a></div>';

	}

	if ( is_user_logged_in() ) {

		$user_id 			= get_current_user_id();
		$meta_key 			= 'dg_saved_' . $post_type;
		$original_data 		= get_user_meta( $user_id, $meta_key, FALSE )[0];
		$bool = FALSE; // false means they haven't got this tone saved.

		// Don't add a duplicate
		foreach ($original_data as $saved_item) {

			if ($post_id == $saved_item) {

				$bool = TRUE; //  they've got this tone saved.

			}

		}	

		// Format post type to suit JS requirements
		$post_type = "'".$post_type."'";

		if ($bool == TRUE) {

			// They'ev already saved this
			echo '<div class="save-remove-wrapper"><a class="product-meta-attribute dg_save_post delete gem-button '.$remove_class.'" onclick="dg_jq_delete_post('.$post_id.','.$post_type.')"><i class="fas fa-times"></i>'.$remove_string.'</a></div>';

		} else {

			// they've not saved this
			echo '<div class="save-remove-wrapper"><a class="product-meta-attribute dg_save_post save gem-button '.$save_class.'" onclick="dg_jq_save_post('.$post_id.','.$post_type.')"><i class="fas fa-save"></i>'.$save_string.'</a></div>';

		}

	}

}

/*
**
**	Add new fields to update user details woocommerce
**
*/
add_action('wp_footer', 'dg_save_post_script');
function dg_save_post_script() {

	if ( is_user_logged_in() ) { 

	?>
		<script type="text/javascript">
				function dg_jq_save_post(post_id, post_type, event) { 

					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
					var user_id = <?php echo get_current_user_id(); ?>;
					var removeElement = '<a class="product-meta-attribute dg_save_post delete gem-button" onclick="dg_jq_delete_post('+post_id+',\''+post_type+'\')"><i class="fas fa-times"></i> Remove</a>';

				    if (!event) {
				    	event = window.event;
				    };
				    var el = (event.target || event.srcElement); 

				    var data = {
				        action: 'dg_save_post',
				        post_id: post_id,
				        user_id: user_id,
				        post_type: post_type
				    };

				    jQuery.post(ajaxurl, data, function(response) {

				        console.log('Got this from the server: ' + response);

				        if (response == 'already exists') {

				        	showDialog('This Has Already Been Saved In Your Account.', 'Success');

				        } else if (response == 'saved succesfully') {

				        	jQuery(el).parents('.save-remove-wrapper').html(removeElement);
				        	showDialog('This Has Been Saved Succesfully To Your Account.', 'Success');

				        }

				    });				
				}

				function dg_jq_delete_post(post_id, post_type, event) {

					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
					var user_id = <?php echo get_current_user_id(); ?>;

					var saveElement = '<a class="product-meta-attribute dg_save_post save gem-button" onclick="dg_jq_save_post('+post_id+',\''+post_type+'\')"><i class="fas fa-save"></i> Save</a>';

				    if (!event) {
				    	event = window.event;
				    }
				    var el = (event.target || event.srcElement); 

				    var data = {
				        action: 'dg_delete_post',
				        post_id: post_id,
				        user_id: user_id,
				        post_type: post_type
				    };

				    jQuery.post(ajaxurl, data, function(response) {

				        console.log('Got this from the server: ' + response);

				        if (response == 'not saved') {

				        	showDialog('This Hasn\'t Been Saved To Your Account Yet.', 'Success');

				        } else if (response == 'deleted succesfully') {

				        	// jQuery('.save-remove-wrapper').html(saveElement);
				        	showDialog('This Has Been Removed Succesfully From Your Account.', 'Success');

				        	jQuery(el).parents('.save-remove-wrapper').html(saveElement);

				        }

				    });

				}

		</script>

	<?php

	}
}

// Save
add_action( 'wp_ajax_dg_save_post', 'dg_save_post_to_account' );
add_action( 'wp_ajax_nopriv_dg_save_post', 'dg_save_post_to_account' );

// Delete
add_action( 'wp_ajax_dg_delete_post', 'dg_delete_post_from_account' );
add_action( 'wp_ajax_nopriv_dg_delete_post', 'dg_delete_post_from_account' );

// Save callback
function dg_save_post_to_account() {

	// Grab variables
	$post_id 			= $_POST['post_id'];
	$user_id 			= $_POST['user_id'];
	$post_type 			= $_POST['post_type'];
	$meta_key 			= 'dg_saved_' . $post_type;
	$original_data 		= get_user_meta( $user_id, $meta_key, FALSE )[0];

	if ( ! array($original_data) ) {

		$original_data = array();
		$original_data[] = $post_id;

	} else {

		// Don't add a duplicate
		foreach ($original_data as $saved_item) {

			if ($post_id == $saved_item) {

				echo 'already exists';
				wp_die();

			}

		}	

		$original_data[] = $post_id;

	}

	$new_data = $original_data;

	// Save data
	update_user_meta( $user_id, $meta_key, $new_data );

	// Post response to server
	echo 'saved succesfully';

	// Check stored vals
	$stored_data = get_user_meta( $user_id, $meta_key, FALSE );

	// Debugging
	// error_log( 'Existing Data: '. print_r($original_data, TRUE) );
	dg_activity( 'Save data to account: '. print_r($stored_data, TRUE), 'Account' );

	// Let's clear it
	// delete_user_meta($user_id, $meta_key);

	// Exit
	wp_die();

}

function dg_delete_post_from_account() {

	// Grab variables
	$post_id 			= $_POST['post_id'];
	$user_id 			= $_POST['user_id'];
	$post_type 			= $_POST['post_type'];
	$meta_key 			= 'dg_saved_' . $post_type;
	$original_data 		= get_user_meta( $user_id, $meta_key, FALSE )[0];
	$i = 0;
	$bool = false;

	// If the user has no saved entries
	if ( empty($original_data) ) {

		echo 'not saved';
		wp_die();

	} else {

		// Delete element
		foreach ($original_data as $saved_item) {

			if ( $post_id == $saved_item ) {

				echo 'deleted succesfully';
				$bool = true;
				continue;
			}

			$new_data[] = $saved_item;

		}	

	}

	if ($bool = false) {
		echo 'not saved';
	}

	// Save data
	update_user_meta( $user_id, $meta_key, $new_data );

	// Check stored vals
	$stored_data = get_user_meta( $user_id, $meta_key, FALSE );

	// Debugging
	// error_log( 'Were going to try remove: ' . $post_id);
	// error_log( 'Old Values: '. print_r($original_data, TRUE) );
	dg_activity( 'Delete Saved Data: '. print_r($stored_data, TRUE), 'Account' );

	// Exit
	wp_die();

}
/*
**
**		@ Create tab status
**
**
*/
// Create button for setting progress
add_action('above_tab_content', 'dg_tab_progress_status_html', 20); // single guitar tab page
// add_action('guitar_tab_extra_content', 'dg_tab_progress_status_html', 20); // tab listings

function dg_tab_progress_status_html() {

	global $post;
	$post_id 	= $post->ID;
	$post_type 	= get_post_type( $post_id );
	$meta_key 	= 'dg_tab_progress_status'; // <-- for checking what the current status is



	// Print fake button if they're not logged in.
	if ( ! is_user_logged_in() ) { 

		?>

		<div class="tab-status">
			<a class="dg_tab_status gem-button not-started small active">Not Started</a>
			<a class="dg_tab_status logged-in-popup gem-button in-progress small">In Progress</a>
			<a class="dg_tab_status logged-in-popup gem-button completed small">Completed</a>
		</div>

		<?php

	}

	if ( is_user_logged_in() ) {

		$user_id 			= get_current_user_id();
		$original_data 		= get_user_meta( $user_id, $meta_key, FALSE )[0];
		$bool 				= FALSE; // false means they haven't got this tone saved.

		$current_status 	= dg_check_tab_status($user_id, $post_id);

		?>

		<div class="tab-status">

			<a class="dg_tab_status gem-button not-started small <?php if ($current_status == 'not-started') { echo 'active';} ?>">Not Started</a>

			<a class="dg_tab_status gem-button in-progress small <?php if ($current_status == 'in-progress') { echo 'active';} ?>" onclick="dg_jq_set_tab_progress_status(<?php echo $post_id  ?>, 'in-progress')">In Progress</a>

			<a class="dg_tab_status gem-button complete small <?php if ($current_status == 'complete') { echo 'active';} ?>" onclick="dg_jq_set_tab_progress_status(<?php echo $post_id  ?>, 'complete')">Complete</a>

		</div>

		<?php

	}

}

function dg_check_tab_status($user_id, $post_id) {

	$meta_key 	= 'dg_tab_progress_status'; // <-- for checking what the current status is
	$data = get_user_meta( $user_id, $meta_key, FALSE )[0];
	$result;
	$bool = false;

	foreach ($data as $tab) {
		if ($post_id == $tab['id']) {
			$result = $tab['status'];
			$bool = true;
		}
	}

	if ($bool == false) {
		$result = 'not-started';
	}

	return $result;

}

// Script to send tab status to ajax
#
#
# Maybe by default, add it to saved tabs as well. <--
#
#
add_action('wp_footer', 'dg_tab_progress_script');
function dg_tab_progress_script() {

	if ( is_user_logged_in() ) {  //only print on tabs pages

	?>
		<script type="text/javascript">

				function dg_jq_set_tab_progress_status(post_id, status) {

					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
					var user_id = <?php echo get_current_user_id(); ?>;

				    if (!event) {
				    	event = window.event;
				    }
				    var el = (event.target || event.srcElement); 

				    var data = {
				        action: 'dg_set_tab_progress_status',
				        post_id: post_id,
				        user_id: user_id,
				        status: status
				    };

				    jQuery.post(ajaxurl, data, function(response) {

				        var string = 'Tab has been ' + response + ' as ' + status + '.';
				        
				        console.log(string);

				        jQuery('.dg_tab_status.active').removeClass('active');
				        jQuery('.dg_tab_status.'+ status +'').addClass('active');

				        showDialog(string, 'Success'); //daviesguitars.io/wp-admin

				    });

				}
		</script>

	<?php

	}
}


/*
**
**	@ Set status of tab
**	@ null | not started, in progress, completed
**
**	@ create a button with in progress or completed and a little tick next to them if its as such
**	
*/

// Set tab prorgess ajax action
add_action( 'wp_ajax_dg_set_tab_progress_status', 'dg_set_tab_progress_status' );
add_action( 'wp_ajax_nopriv_dg_set_tab_progress_status', 'dg_set_tab_progress_status' );

function dg_set_tab_progress_status() {

	// Grab variables
	$post_id 			= $_POST['post_id']; // <--- this is the unique key, for external tabs it will be something other than post 
	$user_id 			= $_POST['user_id'];
	$status 			= $_POST['status'];

	$meta_key 			= 'dg_tab_progress_status';
	$original_data 		= get_user_meta( $user_id, $meta_key, FALSE )[0];
	$bool = false;

	// If they don't have any information
	if ( empty( $original_data ) ) {

		// echo 'Saving first status | ';
		$new_data[0] = array(
			'id' => $post_id,
			'status' => $status
		);

		echo 'saved';

	} else {

		// echo 'Trying to add '. $post_id.'. | ';

		// change var to new format
		$new_data	 	= $original_data;

		$i = 0;
		// Before adding new id, check if it exists already.
		foreach ( $new_data as $nd ) {

			if ($nd['id'] == $post_id) {

				// echo 'Looks like youve already done this tab, we will update it to '.$status.'. |';

				$new_data[$i]['status'] = $status;

				// echo 'Updated status.';
				$bool = true;

				echo 'updated';

			}

			$i++;

		}

		if ($bool == false) {

			// echo 'Found a new tab, adding it to stack.';
			$new_data[] 	= array(

				'id' => $post_id,
				'status' => $status

			);

			echo 'saved';

		}


	}

	// Save data
	update_user_meta( $user_id, $meta_key, $new_data );

	// Post response to server

	// Check stored vals
	$stored_data = get_user_meta( $user_id, $meta_key, FALSE )[0];

	// Debugging
	// error_log( 'Existing Data: '. print_r($original_data, TRUE) );
	// error_log( 'New Data Data: '. print_r($new_data, TRUE) );
	dg_activity( 'Setting tab progress: '. print_r($stored_data, TRUE), 'Guitar Tabs');

	// Let's clear it
	// delete_user_meta($user_id, $meta_key);

	// Exit
	wp_die();

}


/*
**
**	Add new fields to update user details woocommerce
**
*/
add_action( 'dg_add_new_woocommerce_edit_account_fields', 'dg_add_user_fields_to_edit_account_form' );
function dg_add_user_fields_to_edit_account_form() {

    $user = wp_get_current_user();
    $usermeta = get_user_meta($user->ID);   

    ?>	

    	<div class="row">
	    	<div class="col-md-12"><div class="styled-subtitle">Additional Fields</div></div>
	    	<div class="col-md-12">
	        	<label for="user_biography"><?php _e( 'A bit about yourself', 'woocommerce' ); ?>
	        	<textarea rows="7" class="woocommerce-Input woocommerce-Input--text input-text textarea" name="user_biography" id="user_biography"/><?php echo esc_attr( $user->user_biography ); ?></textarea>
	    	</div>
	        <div class="col-md-12">
	        	<label for="instruments_played"><?php _e( 'Instruments Played (comma seperated)', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="instruments_played" id="instruments_played" value="<?php echo esc_attr( $user->instruments_played ); ?>" />
	    	</div>
	    	<div class="col-md-12">
	        	<label for="favorite_bands"><?php _e( 'Favorite Bands (comma seperated)', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="favorite_bands" id="favorite_bands" value="<?php echo esc_attr( $user->favorite_bands ); ?>" />
	    	</div>
    	</div>

    	<div class="row">
	    	<div class="col-md-12">
	    		<div class="styled-subtitle">Socials</div>
	    		<p>Enter the full URL of the relevant page.</p>
	    	</div>
	        <div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="facebook"><?php _e( 'Facebook', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="facebook" id="facebook" value="<?php echo esc_attr( $user->facebook ); ?>" />
	    	</div>
	    	<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="instagram"><?php _e( 'Instagram', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="instagram" id="instagram" value="<?php echo esc_attr( $user->instagram ); ?>" />
	    	</div>
	    	<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="youtube"><?php _e( 'Youtube', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="youtube" id="youtube" value="<?php echo esc_attr( $user->youtube ); ?>" />
	    	</div>
	    	<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="twitter"><?php _e( 'Twitter', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="twitter" id="twitter" value="<?php echo esc_attr( $user->twitter ); ?>" />
	    	</div>
	    	<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="soundcloud"><?php _e( 'Soundcloud', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="soundcloud" id="soundcloud" value="<?php echo esc_attr( $user->soundcloud ); ?>" />
	    	</div>
	    	<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="user_website"><?php _e( 'Website', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="user_website" id="website" value="<?php echo esc_attr( $user->user_website ); ?>" />
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="band_name"><?php _e( 'Band Name', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="band_name" id="band_name" value="<?php echo esc_attr( $user->band_name ); ?>" />
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
	        	<label for="band_website"><?php _e( 'Band Website', 'woocommerce' ); ?>
	        	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="band_website" id="band_website" value="<?php echo esc_attr( $user->band_website ); ?>" />
	    	</div>
    	</div>

    <?php
}

// Save the custom field 'favorite_color' 
add_action( 'woocommerce_save_account_details', 'dg_save_account_details', 12, 1 );

function dg_save_account_details( $user_id ) {

	$array = array(
		'user_biography',
		'instruments_played',
		'favorite_bands',
		'facebook',
		'instagram',
		'youtube',
		'twitter',
		'soundcloud',
		'user_website',
		'band_name',
		'band_website'
	);

	foreach ($array as $a) {

	    // For Favorite color
	    if ( isset($_POST[$a]) ) {

	        update_user_meta( $user_id, $a, sanitize_text_field( $_POST[$a] ) );

	    }

	}

}

/*
**
**	Stops username being required
**
*/
add_filter('woocommerce_save_account_details_required_fields', 'dg_wc_save_account_details_required_fields' );

function dg_wc_save_account_details_required_fields( $required_fields ){

    unset( $required_fields['account_display_name'] );
    unset( $required_fields['account_first_name'] );
    unset( $required_fields['account_last_name'] );

    return $required_fields;

}
/*
**
**		Add new fields to woocom regsitration
**
*/
// Add meta field to reg form
// add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
function wooc_extra_register_fields() {?>

       <p class="form-row form-row-wide">
	       <label for="first_name"><?php _e( 'First Name', 'woocommerce' ); ?> <span class="required">*</span></label>
	       <input type="text" class="input-text" name="first_name" id="first_name" value="<?php if (!empty($_POST['first_name'])) { echo esc_attr( wp_unslash($_POST['first_name']));} else { echo '';} ?>" />
       </p>
       <?php

}
add_action('woocommerce_register_form', 'dg_check_user_interests', 1);
function dg_check_user_interests() { ?>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide col-md-12 registration-question">
		<div>What are you most interested in learning?</div>
		<span class="dg-interests-radio">
			<input type="radio" name="interested_in_learning" value="technical-skills" id="technical-skills">
			<label for="technical-skills">Technical Skills</label>
		</span>
		<span class="dg-interests-radio">
			<input type="radio" name="interested_in_learning" value="writing" id="writing">
			<label for="writing">Writing</label>
		</span>
		<span class="dg-interests-radio">
			<input type="radio" name="interested_in_learning" value="music-theory" id="music-theory">
			<label for="music-theory">Music Theory</label>
		</span>
	</div>

<?php }

// validate data
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

	if ( isset( $_POST['first_name'] ) && empty( $_POST['first_name'] ) ) {

	     $validation_errors->add( 'first_name_error', __( 'First name is required!', 'woocommerce' ) );

	}

    return $validation_errors;

}
// Store Data
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

function wooc_save_extra_register_fields( $customer_id ) {

	dg_debug( print_r($_POST, TRUE) );

	if ( isset( $_POST['first_name'] ) ) {

		//First name field which is by default
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );

		// First name field which is used in WooCommerce
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['first_name'] ) );

	}

	if ( isset( $_POST['interested_in_learning'] ) && ! empty( $_POST['interested_in_learning'] ) ) {

		// First name field which is by default
		update_user_meta( $customer_id, 'interested_in_learning', sanitize_text_field( $_POST['interested_in_learning'] ) );

	}

}


/**
 *
 *	Update profile picture and banner
 *	See Author.php for usage.
 *	@param dg_profile_picture (meta key)
 *	@param dg_cover_image (meta key)
 *
 */

add_action( 'wp_ajax_dg_save_meta_ajax', 'dg_save_meta_to_account' );
add_action( 'wp_ajax_nopriv_dg_save_meta_ajax', 'dg_save_meta_to_account' );

function dg_save_meta_to_account() {

	// Grab variables
	$user_id 			= $_POST['user_id'];
	$data 				= $_POST['data'];
	$meta_key 			= $_POST['meta_key'];

	// Try and hack into user avatars
	if ( $meta_key == 'dg_profile_picture' ) {

		global $wpdb;
		$update = update_user_meta( $user_id, $wpdb->get_blog_prefix() . 'user_avatar', $data );
		
		// Delete profile pic meta to phase it out
		$delete = delete_user_meta( $user_id, 'dg_profile_picture' );

	} else {

		$update = update_user_meta( $user_id, $meta_key, $data );

	}

	// Return response
	if ( $update == FALSE ) {

		echo 0;

	} else {

		echo 1;

		// Track activity if succesful
		dg_activity( 'Saved "' . $data . '" to "' . $meta_key . '"' , 'Save');

	}

	// Shut down function
	wp_die();

}