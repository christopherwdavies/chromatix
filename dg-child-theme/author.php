<?php
/**
 * The template for displaying Author archive pages
 *
 * 	@link https://codex.wordpress.org/Template_Hierarchy
 *	@link https://daviesguitars.io/my-account/
 *
 * 	@package WordPress
 * 	@subpackage Davies Guitars
 * 	@since NA
 */

	get_header();

	//$query_var = get_query_var( 'author_content' );
	//echo 'Query var set: ' . $query_var;
	// if query var set use x template... etc

	
	// Set the Current Author Variable $curauth
	$curauth = ( isset( $_GET['author_name']) ) ? get_user_by('slug', $author_name) : get_userdata( intval($author) );

	//Store our variables
	$user_id 			= $curauth->ID;
	$first_name 		= $curauth->first_name;
	$user_name 			= $curauth->user_login;
	$emailAddress 		= $curauth->user_email; 
	$registration_date  = date('d/m/y', strtotime($curauth->user_registered));
	$user_bio 			= $curauth->user_biography;

	// Profile picture
	if ( ! empty( $curauth->dg_profile_picture ) ) {

		$attachment_id	 	= $curauth->dg_profile_picture;
		$profile_picture 	= wp_get_attachment_image_src( $attachment_id, 'large');
		$profile_picture 	= $profile_picture[0];

	} else {

		$profile_picture 	= get_avatar_url( $user_id, array('size' => 200) );

	}

	/** 
	 *
	 *	@todo Create new attachment size for 1920px
	 *
	 **/
	if ( ! empty( $curauth->dg_cover_image ) ) {

		$attachment_id	 	= $curauth->dg_cover_image;
		$cover_image 		= wp_get_attachment_image_src( $attachment_id, 'full');
		$cover_image 		= $cover_image[0];
		$cover_image_css 	= 'background-image: url(\''.$cover_image.'\');';

	} else {

		$cover_image 		= NULL;

	}

	// Do these in the future
	$facebook 			= $curauth->facebook; 
	$instagram 			= $curauth->instagram; 
	$youtube 			= $curauth->youtube;
	$twitter 			= $curauth->twitter;
	$soundcloud			= $curauth->soundcloud;
	$band_name 			= $curauth->band_name;
	$band_website 		= $curauth->band_website;
	$website 			= $curauth->user_website;
	$instruments_played = $curauth->instruments_played;
	$favorite_bands 	= $curauth->favorite_bands;

	// stats
	$stats 				= dg_check_user_stats($curauth);
	$last_seen 			= $stats['last_seen_date'];
	$longest_session 	= $stats['longest_duration_human'];
	$total_duration 	= $stats['total_duration_human'];
	$total_sessions 	= $stats['total_number_sessions'] + 1;

	// Levels & Experience
	$levels 			= dg_calculate_experience($user_id);
	$level 				= $levels['level'];
	$experience 		= $levels['total_experience'];
	$guitar_god 		= dg_is_user_guitar_god($user_id);
	$country 			= dg_get_user_country($user_id);

	// Process vars
	if ($user_bio == '') {
		$user_bio = 'Looks like '.$first_name.' has nothing to say about themself!';
	}

	if ($guitar_god) {
		$status = 'Guitar God';
	} else {
		$status = 'Member';
	}

	// Is this your own profile?
	if ( $user_id == get_current_user_id() ) {
		$own_profile = TRUE;
	} else {
		$own_profile = FALSE;
	}

	// Edit profile link if it's your
	if ($own_profile) {
		$edit_profile = '<a href="/my-account/edit-account/" class="edit-account-link">Edit Details</a>';
	} else {
		$edit_profile = NULL;
	}

	if ( function_exists( 'bp_follow_total_follow_counts' ) ) {

		$count = bp_follow_total_follow_counts( array ( 'user_id' => $user_id) );

	} else {

		$count['following'] 	= 0;
		$count['followers'] 	= 0;

	}

	if ( is_user_logged_in() ) {

		$message_button = '<a class="gem-button micro" href="' . dg_get_send_private_message_url( $user_id ) . '" target="_blank">Message</a>';
		$follow_button = bp_follow_get_add_follow_button ( 
			array (
		    	'leader_id' => $user_id, 
		    	'follower_id' => get_current_user_id(),
		    	'link_class' => 'gem-button micro', 
		    	'wrapper' => ''
			)
		);

	} else {

		$message_button = '<a href="#disabled" class="gem-button micro logged-in-popup">Message</a>';
		$follow_button = '<a href="#disabled" class="gem-button micro logged-in-popup">Follow</a>';

	}

	$edit_profile_button = '<a href="/my-account/edit-account/" class="gem-button micro">Edit Profile</a>';

?>

<style>
.user-meta-container {
	background-color: #f7f7f7;
}
div#page-title {
    padding: 70px 0px 140px !important;
    background-image: url('/wp-content/uploads/2019/09/black-wallpaper.jpg');
    background-image-size: cover;
}
p.subtitle {
    color: black;
    margin-bottom: 0px;
}
li.author-social a i {
	vertical-align: middle;
	margin-right: 10px;
	color: #b30909;
}
li.author-social a {
    color: black;
    padding: 10px 0px;
    display: block;
}
li.author-social {
    list-style: none;
    border-bottom: solid 1px #eaeaea;
}
.user-about-section {
    box-shadow: 0px 5px 5px 0px #0000001f;
    margin-bottom: 25px;
    padding: 10px 20px;
    border: solid 1px #eaeaea;
}
.user-meta-container * {
    font-size: 18px;
    font-family: 'Raleway';
    line-height: initial;
    color: black;
    text-align: center;
}
.user-meta-container {
    padding: 10px 0px;
}
.user-meta-section {
    position: absolute;
    bottom: 0px;
    width: 100%;
    background: #000000ab;
    padding: 10px 0px;
}
span.label {
    font-size: 12px;
    font-family: 'Montserrat';
    text-transform: uppercase;
    display: block;
}
span.stat {
    font-size: 32px;
}
.user-meta-section .item {
    line-height: initial;
    display: inline-block;
    padding: 0px 15px;
}
.user-details {
    line-height: 28px;
}
.edit-account-link {
	font-size: 12px;
    font-family: 'Montserrat';
}
.user-details .button-wrapper a {
	margin-bottom: 0px !important;
}
.button-wrapper a {
    margin-right: 5px;
    margin-left: 5px;
}
span.edit-cover-image {
    float: left;
    background: black;
    border-radius: 100px;
    padding-right: 10px;
    opacity: .7;
    font-size: 14px;
    cursor: pointer;
}
span.edit-cover-image .edit-icon {
    float: none;
    display: inline-block;
    height: 33px;
    padding: 0px;
    width: 33px;
    font-size: 14px;
}
span.edit-cover-image:hover, span.edit-cover-image:hover .edit-icon {
    opacity: 1;
}
span.edit-cover-image {
    float: left;
    background: black;
    border-radius: 100px;
    padding-right: 12px;
    opacity: .7;
    font-size: 12px;
    cursor: pointer;
    font-family: 'Montserrat';
}
</style>

<div id="main-content" class="main-content">
	<div class="page-title-block page-title-alignment-center page-title-style-1 cover-image" id="page-title" style="<?php echo $cover_image_css; ?>">
		<?php if ( $own_profile ) : ?><span class="edit-cover-image"><span class="edit-icon banner"><i class="fas fa-pencil-alt"></i></span>Edit Cover Image</span><?php endif; ?>
		<div class="container">
			<div class="col-md-12 col-xs-12">
				<div class="user-details">
					<div style="background-image: url('<?php echo $profile_picture ?>');" id="profile-picture" class="user-profile-picture">
						<?php if ( $own_profile ) : ?><span class="edit-icon profile-picture"><i class="fas fa-pencil-alt"></i></span><?php endif; ?>
					</div>
					<div class="user-first-name"><?php echo $first_name ?>, @<?php echo $user_name; ?></div>
					<div class="user-location"><?php echo $country['flag'] . ' ' . $country['country'] ?></div>
					<div class="button-wrapper">
						<?php 

							if ( $own_profile ) {
								echo $edit_profile_button;
							} else {
								echo $follow_button;
								echo $message_button;
							}

						?>
					</div>
				</div>
			</div>
		</div>
		<div class="user-meta-section">
			<div class="container">
				<div class="wrapper">
					<!-- Following -->
					<div class="item following">
						<span class="stat"><?php echo $count['following'] ?></span>
						<span class="label">Following</span>
					</div>
					<!-- Followers -->
					<div class="item followers">
						<span class="stat"><?php echo $count['followers'] ?></span>
						<span class="label">Followers</span>
					</div>
					<!-- Level -->
					<div class="item level">
						<span class="stat"><?php echo $level ?></span>
						<span class="label">Level</span>
					</div>
					<!-- Level -->
					<div class="item level" style="display: none;">
						<span class="stat">N/A</span>
						<span class="label">World Rank</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $own_profile ) : dg_under_account_header_nav(); endif; ?>

	<div class="block-content">
		<div class="container">

			<?php if ( $own_profile ) : echo do_shortcode('[dg-profile-activity-links]'); endif; ?>

			<div class="row">

				<div class="col-md-3 col-sm-12">

					<div class="user-about-section">
						<p class="styled-subtitle">Links</p>
						<div class="author-social-wrapper">
							<?php if ($facebook): ?>
					    		<li class="author-social facebook">
					    			<a href="<?php echo $facebook ?>" target="_blank" rel="nofollow"><i class="socials-item-icon facebook"></i>Facebook</a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($instagram): ?>
					    		<li class="author-social instagram">
					    			<a href="<?php echo $instagram ?>" target="_blank" rel="nofollow"><i class="socials-item-icon instagram"></i>Instagram</a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($youtube): ?>
					    		<li class="author-social youtube">
					    			<a href="<?php echo $youtube ?>" target="_blank" rel="nofollow"><i class="socials-item-icon youtube"></i>Youtube</a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($twitter): ?>
					    		<li class="author-social twitter">
					    			<a href="<?php echo $twitter ?>" target="_blank" rel="nofollow"><i class="fab fa-twitter"></i>Twitter</a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($soundcloud): ?>
					    		<li class="author-social soundcloud">
					    			<a href="<?php echo $soundcloud ?>" target="_blank" rel="nofollow"><i class="fab fa-soundcloud"></i>Soundcloud</a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($band_name && $band_website): ?>
					    		<li class="author-social website">
					    			<a href="<?php echo $band_website ?>" target="_blank" rel="nofollow"><i class="fas fa-music"></i><?php echo $band_name ?></a>
					    		</li>
				    		<?php endif; ?>
				    		<?php if ($website): ?>
					    		<li class="author-social website">
					    			<a href="<?php echo $website ?>" target="_blank" rel="nofollow"><i class="fas fa-globe"></i></i>Website</a>
					    		</li>
				    		<?php endif; ?>
				    	</div>
				    	<?php echo $edit_profile ?>
				    </div>

					<div class="user-about-section">
						<p class="styled-subtitle">Bio</p>
						<p><?php echo $user_bio ?></p>
						<?php echo $edit_profile ?>
					</div>

					<div class="user-about-section">
						<p class="styled-subtitle">Instruments played</p>
						<p><?php echo $instruments_played ?></p>
						<?php echo $edit_profile ?>
					</div>

					<div class="user-about-section">
						<p class="styled-subtitle">Favorite Bands</p>
						<p><?php echo $favorite_bands ?></p>
						<?php echo $edit_profile ?>
					</div>

				</div>

				<div class="col-md-9 col-sm-12">

					<div id="buddypress">

						<?php if ( $own_profile ) : ?>

							<!-- Post a status update -->
							<?php bp_get_template_part( 'activity/post-form' ); ?>

						<?php endif; ?>

						<div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

							<!-- Your activity -->
							<?php bp_get_template_part( 'activity/activity-loop' ) ?>

						</div>

						<div class="suggested-friends">

							<!-- Suggested friends -->
							<div class="title-h4">Recently Active Members</div>

							<?php dg_bp_user_query(); ?>

						</div>

					</div>

				</div>

			</div>

		</div><!-- Container -->
	</div><!-- Block Content -->
</div><!-- Main content -->

<?php if ( $own_profile ) : ?>
	
    <?php wp_enqueue_media(); ?>	
    <?php wp_enqueue_script('update-profile-picture'); ?>

<?php endif; ?>

<?php get_footer();