<?php
/**
 *	Buddypress hooks
 **/


/**
 *
 *	Under account nav
 *	
 **/
add_action('under_account_header', 'dg_under_account_header_nav');

function dg_under_account_header_nav() {

    ?>

    <div class="under-account-header-nav">
        <div class="account-nav-wrap">
            <?php 
                 $args = array(
                    'menu'            => 'under-account-header-menu',
                    'container'       => 'div',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => 'menu',
                    'menu_id'         => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'item_spacing'    => 'preserve',
                    'depth'           => 0,
                    'walker'          => '',
                    'theme_location'  => '',
                );
                wp_nav_menu( $args );
            ?>
        </div>
    </div>

    <?php

}

/**
 *	This filters the document title
 *	@todo need to filter page title
 **/
add_filter( 'bp_modify_document_title_parts', 'add_single_activity_to_title', 100, 1 );

function add_single_activity_to_title( $title ) {

	    global $bp;

	    if ( ! bp_is_blog_page() ) {

	    	$page = ucfirst( bp_current_component() );

	    	// 'title' is the title page for header
	    	$title['title'] = 'My Account - ' . $page;

	    }

    return $title;

}

/**
 *	Filter the title only buddypress pages
 */
// add_filter('the_title', 'dg_filter_bp_page_title', 10, 2);

function dg_filter_bp_page_title( $title, $id ) {


	global $post;

	$post_type = get_post_type( $post );

    if ( ! bp_is_blog_page() && ! is_admin() && $post_type == 'page' && in_the_loop() ) {

    	$title = bp_current_component();

        return $title;

    }

    return $title;
    
}

/**
 *
 *	Link to assist with creating nav links
 *
 **/
add_shortcode('bp-user-permalink', 'dg_bp_user_permalink'); 
function dg_bp_user_permalink() {

	$current_user = wp_get_current_user();
	$result = get_permalink( 24004 ) . $current_user->user_login;

	return $result;

}

add_shortcode('dg-settings-nav', 'dg_bp_settings_subnav');
add_action('woocommerce_before_edit_account_form', 'dg_bp_settings_subnav', 20);
add_action('dg_before_settings', 'dg_bp_settings_subnav', 20);
function dg_bp_settings_subnav() {

	$active = 'class="current selected"'; 

	?>

	<div id="buddypress" class="custom-subnav">
		<div class="item-list-tabs no-ajax" id="subnav" aria-label="Member secondary navigation" role="navigation">
			<ul>
				<li id="edit-personal-li" <?php if ( dg_check_active_link_bp_subnav('/my-account/edit-account/') ) { echo $active; } ?>>
					<a id="edit-account" href="https://daviesguitars.io/my-account/edit-account/">Edit Account</a>
				</li>
				<li id="notifications-personal-li" <?php if ( dg_check_active_link_bp_subnav('/settings/notifications/') ) {echo $active;} ?>>
					<a id="notifications" href="<?php echo dg_bp_user_permalink(); ?>/settings/notifications/">Email Notifications</a>
				</li>
				<li id="reminders-li" <?php if ( dg_check_active_link_bp_subnav('/my-account/notification-preferences/') ) {echo $active;} ?>>
					<a id="reminders" href="https://daviesguitars.io/my-account/notification-preferences/">Reminders</a>
				</li>
				<li id="general-personal-li" style="display: none;">
					<a id="general" href="<?php echo dg_bp_user_permalink(); ?>/settings/">General</a>
				</li>
				<li id="data-personal-li" style="display: none;">
					<a id="data" href="<?php echo dg_bp_user_permalink(); ?>/settings/data/">Export Data</a>
				</li>
			</ul>
		</div>
	</div>

	<?php

}

add_shortcode('dg-learning-nav', 'dg_bp_learning_subnav');
function dg_bp_learning_subnav() {

	$active = 'class="current selected"'; 

	?>

	<div id="buddypress" class="custom-subnav">

		<div class="item-list-tabs no-ajax" id="subnav" aria-label="Member secondary navigation" role="navigation">
			<ul>
				<!-- Learning dashboard https://daviesguitars.io/learning-dashboard/ --><!-- daviesguitars.io/wp-admin -->
				<!-- Stats & achievements https://daviesguitars.io/my-account/stats-achievements-accolades/ -->
				<!-- Leaderboard https://daviesguitars.io/the-leaderboard/ -->

				<li id="learning-dashboard-li" <?php if ( dg_check_active_link_bp_subnav('/learning-dashboard/') ) { echo $active; } ?>>
					<a id="learning-dashboard" href="/learning-dashboard/">Learning Dashboard</a>
				</li>
				<li id="stats-achievements-accolades-li" <?php if ( dg_check_active_link_bp_subnav('/stats-achievements-accolades/') ) {echo $active;} ?>>
					<a id="stats-achievements-accolades" href="/my-account/stats-achievements-accolades/">Stats & Achievements</a>
				</li>
				<li id="the-leaderboard-li" <?php if ( dg_check_active_link_bp_subnav('/the-leaderboard/') ) {echo $active;} ?>>
					<a id="the-leaderboard" href="/the-leaderboard/">Leaderboard</a>
				</li>
			</ul>
		</div>
	</div>

<?php

}

/**
 *	daviesguitars.io/my-account/
 */
add_shortcode('dg-saved-content-nav', 'dg_bp_saved_content_subnav');
function dg_bp_saved_content_subnav() {

	$active = 'class="current selected"'; 

	?>

	<div id="buddypress" class="custom-subnav">

		<div class="item-list-tabs no-ajax" id="subnav" aria-label="Member secondary navigation" role="navigation">
			<ul>

				<li id="saved-tabs-li" <?php if ( dg_check_active_link_bp_subnav('/saved-guitar-tabs/') ) { echo $active; } ?>>
					<a id="saved-tabs" href="/my-account/saved-guitar-tabs/">Saved Tabs</a>
				</li>
				<li id="saved-tones-li" <?php if ( dg_check_active_link_bp_subnav('/saved-guitar-tones/') ) {echo $active;} ?>>
					<a id="saved-tones" href="/my-account/saved-guitar-tones/">Saved Tones</a>
				</li>
			</ul>
		</div>
	</div>

<?php

}

/**
 *	daviesguitars.io/my-account/
 */
// \buddypress\activity\index
add_action('bp_before_directory_activity_content', 'dg_profile_activity_subnav');
add_shortcode('dg-profile-activity-links', 'dg_profile_activity_subnav');
function dg_profile_activity_subnav() {

	$active = 'class="current selected"'; 
	$user_id = get_current_user_id();
	$profile_url = get_author_posts_url( $user_id );

	?>

	<div id="buddypress" class="custom-subnav">

		<div class="item-list-tabs no-ajax" id="subnav" aria-label="Member secondary navigation" role="navigation">
			<ul>

				<li id="saved-tabs-li" <?php if ( dg_check_active_link_bp_subnav('/profile/') ) { echo $active; } ?>>
					<a id="profile" href="<?php echo $profile_url ?>">Profile</a>
				</li>
				<li id="saved-tones-li" <?php if ( dg_check_active_link_bp_subnav('/activity/') ) {echo $active;} ?>>
					<a id="activity-feed" href="<?php echo bp_get_activity_root_slug(); ?>">Activity Feed</a>
				</li>         
			</ul>
		</div>
	</div>

<?php

}

/**
 *	Checks what the active link is
 */
function dg_check_active_link_bp_subnav( $slug ) {

	$uri = $_SERVER['REQUEST_URI'];

	if ( strpos( $uri, $slug ) !== FALSE ) {

		return TRUE;

	} else {

		return FALSE;

	}

}

/**
 *
 *	Add active menu class to current menu item	
 *
 */
add_filter( 'nav_menu_css_class', 'dg_active_menu_item_user_menu', 10, 3 );
function dg_active_menu_item_user_menu( $classes, $item, $args ) {

    // check if the item is in the primary menu
    if ( $args->menu == 'under-account-header-menu' ) {

    	$user_bp_url = bp_core_get_userlink( bp_loggedin_user_id(), FALSE, TRUE );
    	$menu_item_url = $item->url;
    	$menu_item_title = $item->title;
    	$current_url = get_site_url() . $_SERVER['REQUEST_URI'];

    	// Learning 
    	if ( $menu_item_title == 'Learning' ) {

	    	if ( $current_url == 'https://daviesguitars.io/my-account/stats-achievements-accolades/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

	    	} elseif ( $current_url == 'https://daviesguitars.io/the-leaderboard/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

	    	}

    	}

    	// Activity Feed
    	if ( $menu_item_title == 'Activity Feed' ) {

			if ( $current_url == $user_bp_url ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			} elseif ( $current_url == bp_get_activity_root_slug() ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			}

    	}

    	// Settings
    	if ( $menu_item_title == 'Settings' ) {

			if ( $current_url == $user_bp_url . 'settings/notifications/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			} elseif ( $current_url == 'https://daviesguitars.io/my-account/notification-preferences/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			}

    	}

    	// Saved Content
    	if ( $menu_item_title == 'My Saved Content' ) {

			if ( $current_url == 'https://daviesguitars.io/my-account/saved-guitar-tones/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			} elseif ( $current_url == 'https://daviesguitars.io/my-account/saved-guitar-tabs/' ) {

				$classes[] = 'current-menu-item';
				$classes[] = 'current_page_item';

			}

    	}


    }

    return $classes;
}

/**
 *
 *	Add notifications to menu items
 *
 */
add_filter( 'nav_menu_item_args', 'dg_args_menu_user_items', 10, 3 );

function dg_args_menu_user_items( $args, $item, $depth ) {

    // check if the item is in the primary menu
    if ( $args->menu == 'under-account-header-menu' ) {

    	$user_id = get_current_user_id();

    	$notifications = bp_notifications_get_unread_notification_count( $user_id );
    	$unread_messages = bp_get_total_unread_messages_count( $user_id ); // echo
    	$friends_count = friends_get_friend_count_for_user( $user_id );

    	// Notifications
    	if ( $item->title == 'Notifications' ) {

        	$args->link_after  = '<span class="bp-user-notifications li-menu-count">' . $notifications . '</span>';

    	} elseif ( $item->title == 'Messages' ) {

        	$args->link_after  = '<span class="bp-message-count li-menu-count">' . $unread_messages . '</span>';

    	} elseif ( $item->title == 'Friends' ) {

        	$args->link_after  = '<span class="bp-friend-count li-menu-count">' . $friends_count . '</span>';

    	} elseif ( $item->title == 'Settings' ) {

        	$args->link_after  = NULL;

    	} elseif ( $item->title == 'My Saved Content' ) {

        	$args->link_after  = NULL;

    	}

    	return $args;

    }

    return $args;

}

/**
 *
 *
 *	Print list of members forcefully
 *	review https://codex.buddypress.org/developer/using-bp_parse_args-to-filter-buddypress-template-loops/
 *	@param X is number of users to show in loop
 *
 */
function dg_bp_user_query( $x = 10 ) {

	// Get all user ids
	$users = get_users( array( 'fields' => array( 'ID' ) ) );
	$user_ids = array();
	foreach ( $users as $user ) {
		$user_ids[] = $user->ID;
	}

	/**
	 *
	 *	Args - sets up query
	 *	@link https://codex.buddypress.org/developer/bp_user_query/
	 *
	 */
	$args = array(
		'type'		=> 'active',
		'include' 	=> FALSE,

	);

	$query = new BP_User_Query( $args );
	$count = 0;

	?>

	<ul id="members-list" class="item-list" aria-live="assertive" aria-relevant="all">

		<?php foreach ( $query->results as $user ) : ?>

		<?php

		$user_id = $user->ID;

		// add_filter( 'bp_displayed_user_id', 'dg_filter_displayed_user_id', 10, 1 );
		add_filter( 'bp_displayed_user_id', function( $original_id ) use ( $user_id ) {

				$original_id = $user_id;
		        return $original_id;

		    }, 12
		);

		$follow_button = bp_follow_get_add_follow_button ( 
			array (
		    	'leader_id' => $user->ID, 
		    	'follower_id' => get_current_user_id(),
		    	'link_class' => 'gem-button micro', 
		    	'wrapper' => ''
			)
		);

		$view_profile_button = '<a href="' . get_author_posts_url( $user_id ) . '" class="gem-button micro grey">View Profile</a>';

		?>

			<li <?php bp_member_class(); ?>>

				<div class="item-avatar">
					<a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo get_avatar($user->ID); ?></a>
				</div>

				<div class="item">

					<div class="item-title">
						<a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo $user->user_nicename; ?></a>
						<?php if ( bp_get_member_latest_update($user->ID) ) : ?>
							<span class="update"> <?php bp_member_latest_update($user->ID); ?></span>
						<?php endif; ?>
					</div>

					<div class="item-meta">
						<span class="activity">
							<?php 	
								$country = dg_get_user_country( $user_id, 16 );
								echo $country['flag']; 
							?>
							<?php echo 'Last active: ' . bp_core_time_since( $user->last_activity ) . '<br>'; ?>
							<?php // echo $friendship = bp_is_friend( $user->ID ); ?>

						</span>
					</div>
					<?php do_action( 'bp_directory_members_item' ); ?>
				</div>

				<div class="action">

					<?php // echo dg_add_friend_button( $user->ID ); ?>
					<?php echo $view_profile_button; ?>
					<?php echo $follow_button; ?>
					<?php // echo 'User ID: ' . bp_get_member_user_id() ?>
					<?php // do_action( 'bp_directory_members_actions' ); ?>

				</div>

				<div class="clear"></div>

			</li>

			<?php $count++; if ($count >= $x) : break; endif; ?>

		<?php endforeach; ?>

		<script type="text/javascript">

				function dg_friend_request( potential_friend, update_action ) { 

					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
					var user_id = <?php echo get_current_user_id(); ?>;

				    if ( ! event) {
				    	event = window.event;
				    };

				    var el = (event.target || event.srcElement);

				    jQuery(el).text('Processing');

				    event.preventDefault();

				    var data = {
				        action: 'dg_friend_request',
				        user_id: user_id,
				        potential_friend: potential_friend,
				        update_action: update_action
				    };

				    jQuery.post(ajaxurl, data, function( response ) {

				    	if ( response == 1 ) {

				    		jQuery(el).text('Done');

				    	} else {

				    		jQuery(el).text('Failed');

				    	}

				        console.log('Ajax response: ' + response);

				    });				
				}

		</script>

		</ul>

	<?php

}

/**
 *
 *	Ajax friend request
 *	@link http://hookr.io/plugins/buddypress/2.8.2/files/bp-friends-bp-friends-functions/
 **/
add_action( 'wp_ajax_dg_friend_request', 'dg_friend_request' );
add_action( 'wp_ajax_nopriv_dg_friend_request', 'dg_friend_request' );

function dg_friend_request(  ) {

	$user_id 			= $_POST['user_id'];
	$potential_friend 	= $_POST['potential_friend'];
	$update_action 		= $_POST['update_action'];

	if ( $update_action == 'add' ) {

		// Add Friend
		$result = friends_add_friend( $user_id, $potential_friend, $force_accept = false );

	} elseif ( $update_action == 'remove' ) {

		// Remove friend
		$result = friends_remove_friend( $user_id, $potential_friend );

	} elseif ( $update_action == 'withdraw' ) {

		// Withdraw request
		$result = friends_withdraw_friendship( $user_id, $potential_friend );

	} elseif ( $update_action == 'accept' ) {

		// Accept Request
		$friendship_id = friends_get_friendship_id( $user_id, $potential_friend );
		$result = friends_accept_friendship( $friendship_id );

	} elseif ( $update_action == 'reject' ) {

		// Reject Request
		$friendship_id = friends_get_friendship_id( $user_id, $potential_friend );
		$result = friends_reject_friendship( $friendship_id );

	}

	// Return results
	if ( $result == TRUE ) {

		echo 1;

	} else {

		echo 0;

	}

	// Exit
	wp_die();

}

/**
 *
 *	Status: pending | awaiting_response | is_friend | default
 *
 */
function dg_add_friend_button( $potential_friend ) {

	// Do nothing if your looking at yourself
	if ( get_current_user_id() == $potential_friend ) {

		return FALSE;

	}

	$friendship = bp_is_friend( $potential_friend );

	if ( $friendship == 'pending' ) {

		$href 	= '#disabled';
		$class 	= 'gem-button micro pending grey';
		$js 	= 'dg_friend_request(' . $potential_friend . ',\'remove\')';
		$text 	= 'Cancel Request';

	} elseif ( $friendship == 'awaiting_response' ) {

		$href 	= '#disabled';
		$class 	= 'gem-button micro awaiting-response';
		$js 	= 'dg_friend_request(' . $potential_friend . ',\'accept\')';
		$text 	= 'Accept Request';

	} elseif ( $friendship == 'is_friend' ) {

		$href 	= '#disabled';
		$class 	= 'gem-button micro is-friend';
		$js 	= 'dg_friend_request(' . $potential_friend . ',\'remove\')';
		$text 	= 'Remove Friend';

	} else { // not friends

		$href 	= '#disabled';
		$class 	= 'gem-button micro add-friend';
		$js 	= 'dg_friend_request(' . $potential_friend . ',\'add\')';
		$text 	= 'Add Friend';

	}

	$result = '<a href="' . $href . '" class="' . $class . '" onclick="' . $js . '">' . $text . '</a>';

	return $result;

}

/**
 *
 *	Filter core add friend buttons
 *
 */
add_filter( 'bp_get_add_friend_button','dg_bp_button_styles', 50, 1);

function dg_bp_button_styles( $button ) {

	if ( is_array( $button ) && isset( $button['id'] ) ) {

		if ($button['id'] == 'pending') {

			$button['link_class'] .= ' gem-button micro grey';

		} elseif ($button['id'] == 'awaiting_response') {

			$button['link_class'] .= ' gem-button micro grey';

		} elseif ($button['id'] == 'is_friend') {

			$button['link_class'] .= ' gem-button micro';

		} elseif ($button['id'] == 'not_friends') {

			$button['link_class'] .= ' gem-button micro';

		}

	}

  return $button;

}

/**
 * 	Build the "Notifications" dropdown. Custom
 *	https://wordpress.stackexchange.com/questions/121517/how-to-add-nav-menu-items-to-a-specific-position-within-a-menu
 *	https://github.com/buddypress/BuddyPress/blob/master/src/bp-notifications/bp-notifications-adminbar.php
 * 	@since 1.9.0
 *
 * 	@return bool
 */
add_action('wp_nav_menu_objects', 'dg_bp_notifications_menu_item', 10, 2);
function dg_bp_notifications_menu_item( $items, $args ) {

	// Dont do anything if not logged in
	if ( ! is_user_logged_in() ) {

		return $items;

	}

	if ( $args->theme_location == 'primary' ) {

		// Setup variables
		$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
		$count         = ! empty( $notifications ) ? count( $notifications ) : 0;
		$alert_class   = (int) $count > 0 ? 'pending-count alert' : 'count no-alert';
		$alert_icon	   = '<i class="_mi fa fa-bell" aria-hidden="true"></i>';
		$menu_title    = '<span class="notifications-wrapper">' . $alert_icon . '<span id="ab-pending-notifications" class="' . $alert_class . '">' . number_format_i18n( $count ) . '</span></span>';
		$menu_link     = trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() );

		// active notifications class
		if ( ! empty( $notifications ) ) {

			$active = 'active-notifications';

		} else {

			$active = 'no-notifications';

		}

		// Add the top-level Notifications button.
	    $item = array(
	        'title'            => $menu_title,
	        'menu_item_parent' => 0,
	        'ID'               => 'bp-primary-notifications',
	        'db_id'            => 'bp-primary-notifications',
	        'url'              => $menu_link,
	        'classes'          => array( 'menu-item', $active )
	    );

		$new_links[] = (object) $item; // Add the new menu item to our array
	    $index = count( $items ) - 2;  // Insert before the last two items

		// Secondary menu items - to be 
		if ( ! empty( $notifications ) ) {

			foreach ( (array) $notifications as $notification ) {

				// Add secondary menyu item
				$new_links[] = (object) array(

					'menu_item_parent' => 'bp-primary-notifications',
					'ID'     => 'notification-' . $notification->id,
					'title'  => $notification->content,
					'url'   => $notification->href,
					'db_id'  => '',

				);

			}

		} else {

			// Add secondary menu item
			$new_links[] = (object) array(

				'menu_item_parent' => 'bp-primary-notifications',
				'ID'     => 'no-notifications',
				'title'  => __( 'No new notifications', 'buddypress' ),
				'url'   => $menu_link,
				'db_id'  => '',

			);

		}

	    // Insert the new links at the appropriate place.
	    array_splice( $items, $index, 0, $new_links );

	}

	return $items;

}

/**
 *
 *	Notifications bell for mobile
 *	@todo make it so that it triggers a slide out menu showing notifications
 *
 */
add_shortcode( 'notifications-bell', 'dg_bp_notifications_bell' );
function dg_bp_notifications_bell() {

	ob_start();

	if ( is_user_logged_in() ) :

		$menu_link 		= trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() );
		$notifications 	= bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
		$count         	= ! empty( $notifications ) ? count( $notifications ) : 0;

		if ( ! empty( $notifications ) ) {
			$active = 'active-notifications';
		} else {
			$active = 'no-notifications';
		}

		?>
			<div class="shiftnav-menu-icon menu-item no-notifications menu-item-bp-primary-notifications mobile-notifications <?php echo $active ?>">
				<a href="<?php echo $menu_link ?>">
					<span class="notifications-wrapper">
						<i class="_mi fa fa-bell" aria-hidden="true"></i>
						<span id="ab-pending-notifications" class="count no-alert"><?php echo $count ?></span>
					</span>
				</a>
			</div>
		<?php

	endif;

	return ob_get_clean();

}

/**
 * Get a link to send PM to the given User.
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function dg_get_send_private_message_url( $user_id ) {

	return wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );

}

/** 
 *
 *	Try and force set the displayed author id to help with BP functions on author page
 *
 */ 
add_filter( 'bp_displayed_user_id', 'dg_filter_displayed_user_id', 10, 1 );
function dg_filter_displayed_user_id( $id ) {

	if ( is_author() ) {

	    $author 	= get_queried_object();
	    $author_id 	= $author->ID;		
	    $id 		= $author_id;

	}

	return $id;

}

/*function dg_get_follow_button( $leader_id, $follower_id = null ) {

	if ( $follower_id == null && is_user_logged_in() ) {

		$follower_id = get_current_user_id();

	}

    if ( bp_follow_is_following( array('leader_id' => $leader_id, 'follower_id' => $follower_id ) ) ) {

        $link_text = __('Unfollow', 'artgorae');

    } else {

        $link_text = __('Follow', 'artgorae');

    }

    $args = array ( 

    	'leader_id' => $leader_id, 
    	'follower_id' => $follower_id,
    	'link_text' => $link_text, 
    	'link_class' => 'gem-button micro', 
    	'wrapper' => ''

    );

    return bp_follow_get_add_follow_button($args);
}*/