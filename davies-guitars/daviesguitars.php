<?php
/*
Plugin Name: Davies Guitars
Plugin URI: https://daviesguitars.io/
Description: Various functions to facilitiate custom functionality for Davies Guitars
Author: Davies Guitars
Version: 1.0.0
*/

defined( 'ABSPATH' ) || exit;


// Set path constants, always returns trailing / 
define( 'DGINCLUDEPATH', plugin_dir_path( __FILE__ ) . '/' );
define( 'DGURLPATH', plugin_dir_url( __FILE__ ) );

class Davies_Guitars {

	function __construct() {

		$this->includes();

	}

	public function includes() {


		// Core classes
		require_once( DGINCLUDEPATH . 'includes/class-dg-guitar-amp.php');
		require_once( DGINCLUDEPATH . 'includes/class-dg-guitar-tab-player.php');


		// Admin files
		if ( is_admin() ) {

			// Admin files / styles
			require_once( DGINCLUDEPATH . 'admin/dg-admin.php');

		}

	}

}

$dg = new Davies_Guitars();