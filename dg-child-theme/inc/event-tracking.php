<?php
/**
**
**		@ This doc is for setting up event tracking on PHP hooks fired
**
**
**		@ Google Analytics Event Tracking
**		@ Docs: https://developers.google.com/analytics/devguides/collection/analyticsjs/events
**
**			# ga('send', 'event', [eventCategory], [eventAction], [eventLabel], [eventValue], [fieldsObject]);
**			# ga('send', 'event', 'Videos', 'play', 'Fall Campaign');
**			# Category and Action are required fields

		@ Facebook Event Tracking
		@ Docs: https://developers.facebook.com/docs/facebook-pixel/implementation/conversion-tracking/, https://www.facebook.com/business/help/402791146561655 <--
		@ Extra notes on custom events: https://www.digishuffle.com/blogs/10-facebook-custom-events-to-build-custom-audiences-define-conversions/#m3

			# Custom Events:
			'trackCustom' is the first parameter, the next is the name/label, extra info can be added in a JSON object. Example below.
			fbq('trackCustom', 'ShareDiscount', {promotion: 'share_discount_10%'});
			
			# Standard Events:
			fbq('track', 'AddPaymentInfo');
			fbq('track', 'AddToCart');
			fbq('track', 'AddToWishlist');
			fbq('track', 'CompleteRegistration');
			fbq('track', 'Contact');
			fbq('track', 'CustomizeProduct');
			fbq('track', 'Donate');
			fbq('track', 'FindLocation');
			fbq('track', 'InitiateCheckout');
			fbq('track', 'Lead');
			fbq('track', 'Purchase', {value: 0.00, currency: 'GBP'});
			fbq('track', 'Schedule');
			fbq('track', 'Search');
			fbq('track', 'StartTrial', {value: '0.00', currency: 'USD', predicted_ltv: '0.00'});
			fbq('track', 'SubmitApplication');
			fbq('track', 'Subscribe', {value: '0.00', currency: 'USD', predicted_ltv: '0.00'});
			fbq('track', 'ViewContent');

**
**
*/
add_action('wp_head', 'dg_set_point_variables');
function dg_set_point_variables(){

	?>
	<script type="text/javascript">
		var points_tabFinished 	= 250;
		var points_play			= 50;
	</script>
	<?php

}

add_action('wp_footer', 'global_event_tracking_wp_footer');
function global_event_tracking_wp_footer() {
	?> 
	<script type="text/javascript">
		/*

				@ Category: Menu Activity

		*/

		jQuery(document).ready(function() {

			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
			var post_id = '<?php echo get_the_ID() ?>';

		    jQuery('form').submit( function(event) {

		    	var form_submit = jQuery(this).serializeArray();
		    	var data = {
		    		action: 'dj_ajax_form_submit',
		    		form_submit: form_submit
		    	}

		    	jQuery.post(ajaxurl, data, function(response) {
		    		// Done
		    	});

		    });

			// Main menu clicks
			jQuery('ul#primary-menu a').click(function() {
				var label = jQuery(this).text();
				fbq('trackCustom', 'Menu Click', {'Main Menu': label});
				ga('send', 'event', 'Menu Activity', 'Click', 'Main Menu: '+label);
			});
			// mobile menu clicks
			jQuery('ul#menu-mobile-menu a').click(function() {
				var label = jQuery(this).text();
				fbq('trackCustom', 'Menu Click', {'Mobile Menu': label});
				ga('send', 'event', 'Menu Activity', 'Click', 'Mobile Menu: '+label);
			});

			/*
					@ Category: My Account, Membership
					Form Submissions

			*/
			// Completed heavy metal quiz
	        jQuery("form#gform_1").submit(function() { 
				ga('send', 'event', 'Form', 'Submit', 'Completed Heavy Metal Guitarist Quiz');
				fbq('trackCustom', 'Form', {'Activity': 'Completed Heavy Metal Guitarist Quiz'});	                
	        }); 

			// Register form
/*			
			jQuery('form.register').on('submit', 'form', function() {
				ga('send', 'event', 'My Account', 'Register', 'Created Account On Woocom Form');
				fbq('track', 'CompleteRegistration', {value: 1.00, currency: 'USD'});
			});
*/

			// Track registrations for some reason the other one isnt working
			jQuery('form.register').submit(function() {
				ga('send', 'event', 'My Account', 'Register', 'Created Account On Woocom Form');
				fbq('track', 'CompleteRegistration', {value: '1.00', currency: 'USD'});
			});
			// Logged in
			jQuery('.woocommerce-form.woocommerce-form-login.login').submit(function() {
				ga('send', 'event', 'My Account', 'Register', 'Logged In On Woocom Form');
				fbq('trackCustom', 'Form', {'My Account': 'Logged In'});
			});

			// Created Guitar God Subscription
			jQuery('form#rcp_registration_form').submit(function() {
				ga('send', 'event', 'Membership', 'Register', 'Created Guitar God Subscription', '11.95');
				fbq('track', 'Subscribe', {value: '11.95', currency: 'USD', predicted_ltv: '11.95'});
			});
			

			/*
					@ Category: ?
					Searches

			*/
			// Might be able to set up on global if you check when x has been clicked what the search val is

			// Guitar Tones Searches - not working because there is two form tags.
			jQuery('.archive-search.search-only form#search-filter-form-22491').submit(function() {
				console.log('Tone search submitted');
				var query = jQuery('.archive-search.search-only #search-filter-form-22491 .sf-field-search input.sf-input-text').val();
				console.log('Query is: ' + query);
				ga('send', 'event', 'Submitearch', 'Submit', 'Tones Search: '+query);
				fbq('track', 'Search', {'content_category': 'Guitar Tones', 'search_string': query});
			});

			// Guitar Tabs Searches
			jQuery('form#search-filter-form-1960').submit(function() {
				var query = jQuery('#search-filter-form-1960 input.sf-input-text').val();
				ga('send', 'event', 'Search', 'Submit', 'Tabs Search: '+query);
				fbq('track', 'Search', {'content_category': 'Guitar Tabs', 'search_string': query});
			});


			/*
					@ Category: Guitar Tones
					@ Guitar Tone Tracking

			*/
			// Listened To Audio on catalogue
			jQuery('.products audio.mejs__player').on( "play", function() {
				ga('send', 'event', 'Guitar Tones', 'Play', 'Played Audio On Catalogue Listings');
				fbq('trackCustom', 'Guitar Tones', {'Activity': 'Played Audio On Catalogue Listings'});
			});

			// Listened To Audio on catalogue
			jQuery('.single-product-content audio.mejs__player').on( "play", function() {
				ga('send', 'event', 'Guitar Tones', 'Play', 'Played Audio On Single Page');
				fbq('trackCustom', 'Guitar Tones', {'Activity': 'Played Audio On Single Page'});
			});

			// External product click on visit developer website
			jQuery('.product-type-external .single_add_to_cart_button').click(function(){
				ga('send', 'event', 'Guitar Tones', 'Click', 'Visit External Website');
				fbq('trackCustom', 'Guitar Tones', {'Activity': 'Visit External Website'});				
			});


			

			/*
					@ Category: Guitar Tab Player
					@ Guitar tab player
					** add stuff for guitar tab directory

			*/
			jQuery('#playPause').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Play / Pause');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Play / Pause'});
			});
			jQuery('ul#playbackSpeedSelector li a').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Change Speed');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Change Speed'});
			});
			jQuery('a#looping').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Loop');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Loop'});
			});
			jQuery('a#metronome').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Metronome');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Metronome'});
			});
			jQuery('a#currentTrack').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Tracks');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Tracks'});
			});
			jQuery('a#page').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Clicked Page View');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Clicked Page View'});
			});
			jQuery('a#horizontalOffscreen').click(function() {
				ga('send', 'event', 'Guitar Tab Player', 'Click', 'Guitar Tab Player - Clicked Horizontal View');
				fbq('trackCustom', 'Guitar Tab Player', {'Activity': 'Guitar Tab Player - Clicked Horizontal View'});
			});

			/*
					@ Category: Social Links
					@ Tracking social links

			*/
			jQuery('.facebook-link').click(function() {
				ga('send', 'event', 'Social Links', 'Click', 'Links Page - Facebook Link');
				fbq('trackCustom', 'Social Links', {'Activity': 'Links Page - Facebook Link'});
			});
			jQuery('.youtube-link').click(function() {
				ga('send', 'event', 'Social Links', 'Click', 'Links Page - Youtube Link');
				fbq('trackCustom', 'Social Links', {'Activity': 'Links Page - Youtube Link'});
			});
			jQuery('.website-link').click(function() {
				ga('send', 'event', 'Social Links', 'Click', 'Links Page - Website Link');
				fbq('trackCustom', 'Social Links', {'Activity': 'Links Page - Website Link'});
			});

			/*
					@ Category: Promo Links
					@ Promo Bars
					@ Description: Any activities / actions that are getting people to do what I want them to do.

			*/
			jQuery('.promo-banner').click(function() {
				var data = jQuery('.promo-banner').data('objective');
				ga('send', 'event', 'Promo Banner', 'Click', 'Promo Link (banner) - ' + data);
				fbq('trackCustom', 'Promo Banner', {'Activity': 'Promo Link (banner) - ' + data});
			});
			jQuery('.guitar-god-popup').click(function() {
				ga('send', 'event', 'Promo Links', 'Click', 'Promo Link - Guitar God Popup');
				fbq('trackCustom', 'Promo Links', {'Activity': 'Promo Link - Guitar God Popup'});
			});
			jQuery('.create-account-notification').click(function() {
				ga('send', 'event', 'Promo Links', 'Click', 'Promo Link - Create Account Notification Icon');
				fbq('trackCustom', 'Promo Links', {'Activity': 'Promo Link - Create Account Notification Icon'});
			});
			jQuery('.logged-in-popup').click(function() {
				ga('send', 'event', 'Promo Links', 'Click', 'Promo Link - Must Be Logged In Popup Trigger');
				fbq('trackCustom', 'Promo Links', {'Activity': 'Promo Link - Must Be Logged In Popup Trigger'});
			});
			jQuery('.create-account-popup').click(function() {
				ga('send', 'event', 'Promo Links', 'Click', 'Promo Link - Create A Free Account Popup Trigger');
				fbq('trackCustom', 'Promo Links', {'Activity': 'Promo Link - Create A Free Account Popup Trigger'});
			});
			

			/*
			 *
			 *	@ Category: Website Activity
			 *	
			*/
			// Clicked on save post button
			jQuery('.dg_save_post').click(function(){
				points_popup(25);
				ga('send', 'event', 'Website Activity', 'Save', 'Save Post');
				fbq('trackCustom', 'Website Activity', {'Activity': 'Save Post'});
			});

			/**
			 *
			 *		Contact form events
			 *		ID: 14 = Download Discography
			 *		ID: 263 = General contact
			 *
			 **/
			 document.addEventListener( 'wpcf7mailsent', function( event ) {

			 	var ID = event.detail.contactFormId;

			 	// Download discography
			 	if (ID == 14 ) {

					ga('send', 'event', 'Form', 'Submit', 'Discography Download');
					fbq('trackCustom', 'Form', {'Activity': 'Discography Download'});
			 	}

			 	// General Contact Form
			 	if (ID == 263 ) {

					ga('send', 'event', 'Form', 'Submit', 'Contact Form Submission');
					fbq('trackCustom', 'Form', {'Activity': 'Contact Form Submission'});
			 	}

			 });

		});




	</script> 
	<?php

	if (is_user_logged_in()) : //logged in users ?>
		<script type="text/javascript">

			/*
			**
			**	@ Alpha Tab user tracking
			**
			*/

			var at = jQuery('#alphaTab');

			at.on('alphaTab.finished', function(e, args) {
				console.log('End of tab event fired');
				points_popup(points_tabFinished);
				dg_track_guitar_tabs_script('finished');
			});
			
			at.on('alphaTab.playerStateChanged', function(e, args) {

				// Playing
				if (args.State == 1) {
					console.log('Started playing tab');
					points_popup(points_play);					
					dg_track_guitar_tabs_script('play');
				}

				// Paused
				if (args.State == 0) {
					console.log('Paused tab');
					dg_track_guitar_tabs_script('pause');					
				}
			});

		</script>
	<?php else : // not logged in ?>
		<script type="text/javascript">

			/*
			**
			**	@ Alpha Tab user tracking
			**
			*/

			var at = jQuery('#alphaTab');

			at.on('alphaTab.finished', function(e, args) {
				console.log('End of tab event fired');
				points_popup(points_tabFinished);
			});
			
			at.on('alphaTab.playerStateChanged', function(e, args) {

				// Playing
				if (args.State == 1) {
					console.log('Started playing tab');
					points_popup(points_play);
				}

				// Paused
				if (args.State == 0) {
					console.log('Paused tab');
				}
			});

		</script>
	<?php endif;
}

// Set page view using ajax
add_action( 'wp_ajax_dj_ajax_form_submit', 'dj_ajax_form_submit' );
add_action( 'wp_ajax_nopriv_dj_ajax_form_submit', 'dj_ajax_form_submit' );

function dj_ajax_form_submit() {

	if ( isset($_POST['form_submit']) ) {

		$data = $_POST['form_submit'];
		$actual_link = "https://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];

		$result = 'Form Submission';
		$result .= '<br>URL: ' . $actual_link;

		foreach ($data as $d) {

			$result .= '<br>' . $d['name'] . ': ' . $d['value'];

		}

		dg_activity( $result, 'Form Submission' );

	}

}

/**
 *	Track page view with hook
 */
add_action( 'wp_footer', 'dg_track_page_view_activity' );
function dg_track_page_view_activity() {


	if ( is_admin() || dg_bot_check() == TRUE ) {

		return FALSE;
		
	}

	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$actual_link = "https://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
	$banned_urls = array('wp-config', 'ads.txt', '.php', '/feed/');

	foreach( $banned_urls as $url ) {

		if ( strpos( $actual_link, $url ) !== FALSE ) {

			return FALSE;

		}

	}

    $key = 'post_views_count';
    $post_id = get_the_ID();
    $count = (int) get_post_meta( $post_id, $key, true );
    $count++;

	dg_activity('Setting views to ' . print_r($count, TRUE) . ' for "' . $actual_link . '"', 'Page View' );

	dg_set_post_view($post_id);

}


/*
 *	Track page views
 */

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); 

function dg_get_post_view() {

    $count = get_post_meta( get_the_ID(), 'post_views_count', true );
    
    return $count . " views";

}

function dg_set_post_view($post_id = 0) {

    $key = 'post_views_count';

    if ($post_id == 0) {

    	$post_id = get_the_ID();

    }

    $count = (int) get_post_meta( $post_id, $key, true );
    $count++;

    update_post_meta( $post_id, $key, $count );

}

// Posts
add_filter( 'manage_posts_columns', 'dg_posts_column_views' );
add_action( 'manage_posts_custom_column', 'dg_posts_custom_column_views' );

// Pages
add_filter( 'manage_pages_columns', 'dg_posts_column_views' );
add_action( 'manage_pages_custom_column', 'dg_posts_custom_column_views' );

function dg_posts_column_views( $columns ) {

    $columns['post_views'] = 'Views';
    return $columns;

}

function dg_posts_custom_column_views( $column ) {

    if ( $column === 'post_views') {

        echo dg_get_post_view();

    }

}

/**
 *	Log all woocommerce errors
 */
add_action('template_redirect', 'dg_log_all_woocommerce_errors');

function dg_log_all_woocommerce_errors() {

	if ( is_admin() ) {

		return FALSE;

	}

	$all_notices = WC()->session->get( 'wc_notices', array() );

	if ( ! empty($all_notices) ) {

		foreach( $all_notices as $notice ) {

			foreach ( $notice as $n) {

				$result .= $n . '<br>';

			}

		}

		dg_debug('<br>Woocommerce notice: ' . $result);
		dg_activity( $result, 'Error');

	}
	
}