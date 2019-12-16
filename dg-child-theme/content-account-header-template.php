<?php

// echo 'user header template';
$user 				= wp_get_current_user();
$user_id 			= $user->ID;
$first_name 		= $user->first_name;
$user_name 			= $user->user_login;
$user_url 			= get_author_posts_url( $user_id );

// 
$profile_picture 	= get_avatar_url( $user_id, array('size' => 200) );

// Make page title compatible with BP


$page_title = bp_current_component();

if ( empty($page_title) ) {

	$page_title = get_the_title();

}

?>
<style>
	div#page-title {
	    padding: 20px 0px !important;
	}
	.profile-nav a:hover {
	    color: #b30909;
	}
</style>
<div class="page-title-block page-title-alignment-center page-title-style-1" id="page-title">
	<div class="container">
		<div class="col-md-3 col-xs-12">
			<div style="background-image: url('<?php echo $profile_picture ?>');" class="user-profile-picture" id="profile-picture">
				<span class="edit-icon profile-picture"><i class="fas fa-pencil-alt"></i></span>
			</div>
			<div class="user-first-name">G'day <?php echo $first_name ?></div>
			<a class="user-username" href="<?php echo $user_url?>">@<?php echo $user_name; ?></a>
			<div class="profile-nav" style="text-align:center;">
				<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ) ?>">Dashboard</a> | 
				<a href="<?php echo $user_url?>">Visit Profile</a> | 
				<a href="/my-account/edit-account/">Edit Details</a>
			</div>
		</div>
		<div class="col-md-9 col-xs-12 account-title">
			<h1><?php echo $page_title ?></h1>
		</div>
	</div>
</div>
<?php 

/**
 *
 *	Currently hooked:
 *	Buddypress navigation
 *
 */
do_action( 'under_account_header' );