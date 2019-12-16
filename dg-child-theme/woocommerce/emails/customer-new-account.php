<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

// do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php

	// vars added by Chris.
	$current_user = get_user_by('login', $user_login ); 
	$user_first_name = $current_user->first_name;
	$account_link = wc_get_page_permalink( 'myaccount' );
	$user_email = $current_user->user_email;

?>

<h3>Welcome <?php echo $user_first_name ?>,</h3>

<p>It's truly our pleasure to welcome you to Davies Guitars.</p>

<p>On our website you will find a huge range of tools and resources needed to help you find your sound as a heavy metal guitarist.</p>
<p>You can learn more about all the tools and resources you now have at your disposal as well as update your details and build your profile in your <a href="<?php echo $account_link ?>">account area</a>.</p>

<p>To <a href="<?php echo $account_link ?>">log into your account</a> you can start by visiting the <a href="<?php echo $account_link ?>">account area</a>.</p>

<p>You can login using either your username <strong><?php echo $user_login ?></strong> or email address <strong><?php echo $user_email ?></strong> and the password you set whilst creating your account.</p>

<p>For any questions or queries don't hesiate to <a href="https://daviesguitars.io/contact/">reach out to our team</a>.</p>

<strong>Stay Heavy,</strong>
<p>The Team At Davies Guitars</p>


<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>
	<?php /* translators: %s Auto generated password */ ?>
	<p><?php printf( esc_html__( 'Your password has been automatically generated: %s', 'woocommerce' ), '<strong>' . esc_html( $user_pass ) . '</strong>' ); ?></p>
<?php endif; ?>

<?php
/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

// do_action( 'woocommerce_email_footer', $email );
