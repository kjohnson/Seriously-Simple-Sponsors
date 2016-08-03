<?php
/*
 * Plugin Name: Seriously Simple Sponsors
 * Version: 1.0
 * Plugin URI: https://wordpress.org/plugins/
 * Description: Add sponsors to your Seriously Simple Podcasting episodes.
 * Author: Kyle B. Johnson
 * Author URI: https://kylebjohnson.me/
 * Requires at least: 4.4
 * Tested up to: 4.5.3
 *
 * Text Domain: seriously-simple-sponsors
 * Domain Path: /languages
 *
 * @package WordPress
 * @author Kyle B. Johnson
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'is_ssp_active' ) ) {
	require_once( 'ssp-includes/ssp-functions.php' );
}

if( is_ssp_active( '1.14' ) ) {

	// Load plugin class files
	require_once( 'includes/class-ssp-sponsors.php' );

	/**
	 * Returns the main instance of SSP_Sponsors to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object SSP_Sponsors
	 */
	function SSP_Sponsors () {
		$instance = SSP_Sponsors::instance( __FILE__, '1.0.0' );
		return $instance;
	}

    SSP_Sponsors();
}
