<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<!-- <p><?php
	/* translators: 1: user display name 2: logout url */
	printf(
		__( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
	);

?></p>

<p><?php
	printf(
		__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
?></p> -->

<?php

$user 				= wp_get_current_user();
$user_id 			= $user->ID;
$first_name 		= $user->first_name;
$user_name 			= $user->user_login;
$user_url 			= get_author_posts_url( $user_id );

function dg_generate_icon_anchor_navigation( $icon, $title, $href, $text = null ) {

	?>

		<a class="dg-icon-anchor" href="<?php echo $href; ?>">
			<div class="vertical-wrapper">
				<div class="dg-icon"><?php echo $icon; ?></div>
				<div class="dg-title"><p class=""><?php echo $title; ?></p></div>
				<?php if ($text) { ?>
					<div class="dg-text"><p><?php echo $text; ?></p></div>
				<?php } ?>
				
			</div>
		</a>

	<?php
}

?>

<div class="title-h4" style="text-align: center;">G'day <?php echo $first_name ?>, welcome to your dashboard</div>
<p class="subtitle" style="text-align: center;">From here you can manage all of your accounts details & preferences.</p>
<div class="row">
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-id-badge"></i>', 
			'Visit Profile',
			$user_url
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-user-cog"></i>', 
			'Edit Profile & Settings',
			'/my-account/edit-account/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-comment"></i>', 
			'Notifications & Preferences',
			'/my-account/notification-preferences/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-file-download"></i>', 
			'My Downloads',
			'/my-account/downloads/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-shopping-bag"></i>', 
			'My Orders',
			'/my-account/orders/'
		) ?>
	</div>

</div>

<div class="vc_empty_space" style="height: 100px"><span class="vc_empty_space_inner"></span></div>

<div class="title-h2" style="text-align: center;">Guitar Mastery</div>
<p class="subtitle" style="text-align: center;">Amplify your technical abilities & check up on your progress / stats.</p>
<div class="row">
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-chalkboard-teacher"></i>', 
			'Learning Dashboard',
			'/learning-dashboard/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-star"></i>', 
			'Stats, Achievements & Accolades',
			'/my-account/stats-achievements-accolades/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-trophy"></i>', 
			'The Leaderboard',
			'/the-leaderboard/'
		) ?>
	</div>
		<div class="col-md-4 ">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-file-audio"></i>', 
			'Saved Tones',
			'/my-account/saved-guitar-tones/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="far fa-file-alt"></i>', 
			'Saved Tabs',
			'/my-account/saved-guitar-tabs/'
		) ?>
	</div>
	<div class="col-md-4">
		<?php dg_generate_icon_anchor_navigation(
			'<i class="fas fa-ellipsis-h"></i>', 
			'Tabs In Progress',
			'/my-account/saved-guitar-tabs/#tabs-in-progress'
		) ?>
	</div>
</div>

<div class="vc_empty_space" style="height: 100px"><span class="vc_empty_space_inner"></span></div>

<?php if (rcp_user_has_active_membership($user_id)) : ?>
	<!-- Guitar god membership -->
	<div class="row">
		<div class="title-h2" style="text-align: center;">Guitar God Membership</div>
		<p class="subtitle" style="text-align: center;">Manage your Guitar God membership using the links below.</p>
		<div class="col-md-4">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="fas fa-crown"></i>', 
				'Welcome',
				'/become-guitar-god/welcome/'
			) ?>
		</div>
<!-- 		<div class="col-md-3">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="fas fa-user-tag"></i>', 
				'Discounts & Promotions',
				'/my-account/discount-codes/'
			) ?>
		</div> -->
		<div class="col-md-4">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="fas fa-user-edit"></i>', 
				'Change My Membership',
				'/my-account/your-membership/'
			) ?>
		</div>
		<div class="col-md-4">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="fas fa-file-invoice-dollar"></i>', 
				'Invoices',
				'/my-account/your-membership/'
			) ?>
		</div>
	</div>
<?php else : ?>
	<!-- Not a guitar god -->
	<div class="row">
		<div class="title-h2" style="text-align: center;">Guitar God Membership</div>
		<p class="subtitle" style="text-align: center;">Gain full access to our exclusive tools & resources with a Guitar God membership.</p>
		<div class="col-md-6">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="fas fa-crown"></i>', 
				'Become A Guitar God',
				'/become-guitar-god/'
			) ?>
		</div>
		<div class="col-md-6">
			<?php dg_generate_icon_anchor_navigation(
				'<i class="far fa-question-circle"></i>', 
				'What\'s A Guitar God?',
				'/become-guitar-god/'
			) ?>
		</div>
	</div>
<?php endif ?>

<!-- Support -->
<!-- Contact -->
<!-- FAQ -->
<!-- Whatever else -->
<!-- Activities -->
<!-- Exercises -->
<!-- Tabs -->
<!-- Tone Explorer -->
<!-- Tools -->
<!-- Online Amp -->
<!-- Online Tuner -->
<!-- Metronome -->

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );
	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );
	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */