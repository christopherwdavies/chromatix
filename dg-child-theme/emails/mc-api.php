<?php

/**
 *	Sends mailchimp email via api
 *
 *	API located in thegem/mailchimp-api
 *	Using API key for Davies_guitars mailchimp account
 *
 *	@param $email_address, $endpoint
 *	@return results to debug.txt
 *
 *
*/

// Load someones class for dealing with this
require_once( get_template_directory() . '/mailchimp-api/src/MailChimp.php' );
use DrewM\MailChimp\MailChimp;

// Function to send email
function dg_send_mailchimp_email($email_address, $endpoint) {

	$mailchimp = new MailChimp('4436bd814546fc9463ddb5adb70673a0-us7');

	// $endpoint = '/automations/a28f08a9a3/emails/dd235456b6/queue';
	// $email_address = 'davies101@live.com.au';
	$data = array($endpoint, $email_address);

	ob_start();

	echo 'Mailchimp email sending results - ';

	$result = $mailchimp->post("$endpoint", [
			'email_address' => $email_address
		]);

	//Dump info to log
	print_r($result);
	print_r($data);

	if ( $mailchimp->success() ) {

		// Do something on success.
		echo ' - Successfully sent.';

	} else {

		echo $mailchimp->getLastError();
		echo ' - Unsuccessfully sent.';

	}

	return dg_debug( ob_get_clean() );

}

