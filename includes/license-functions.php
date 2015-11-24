<?php
/**
 * License Functions
 *
 * @package     RCP
 * @subpackage  License
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.5.0
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function rcp_activate_license() {
	if( ! isset( $_POST['rcp_settings']['license_key'] ) )
		return;

	if( ! current_user_can( 'rcp_manage_settings' ) ) {
		return;
	}

	// retrieve the license from the database
	$status  = get_option( 'rcp_license_status' );
	$license = trim( $_POST['rcp_settings']['license_key'] );

	if( 'valid' == $status )
		return; // license already activated

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => 'Restrict Content Pro', // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( 'https://pippinsplugins.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return false;

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	update_option( 'rcp_license_status', $license_data->license );
	delete_transient( 'rcp_license_check' );

	if( 'valid' !== $license_data->license ) {
		wp_die( sprintf( __( 'Your license key could not be activated. Error: %s', 'rcp' ), $license_data->error ), __( 'Error', 'rcp' ), array( 'response' => 401, 'back_link' => true ) );
	}

}

function rcp_deactivate_license() {
	// listen for our activate button to be clicked
	if( isset( $_POST['license_key_deactivate'] ) ) {

		global $rcp_options;

		// run a quick security check
	 	if( ! check_admin_referer( 'rcp_deactivate_license', 'rcp_deactivate_license' ) )
			return; // get out if we didn't click the Activate button

		if( ! current_user_can( 'rcp_manage_settings' ) ) {
			return;
		}

		// retrieve the license from the database
		$license = trim( $rcp_options['license_key'] );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( 'Restrict Content Pro' ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( 'https://pippinsplugins.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'rcp_license_status' );
			delete_transient( 'rcp_license_check' );
		}

	}
}

function rcp_check_license() {

	if( ! empty( $_POST['rcp_settings'] ) ) {
		return; // Don't fire when saving settings
	}

	global $rcp_options;

	$status = get_transient( 'rcp_license_check' );

	// Run the license check a maximum of once per day
	if( false === $status && ! empty( $rcp_options['license_key'] ) ) {

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'check_license',
			'license' 	=> trim( $rcp_options['license_key'] ),
			'item_name' => urlencode( 'Restrict Content Pro' ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( 'https://pippinsplugins.com', array( 'timeout' => 35, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		$rcp_options['license_status'] = $license_data->license;

		update_option( 'rcp_settings', $rcp_options );

		set_transient( 'rcp_license_check', $license_data->license, DAY_IN_SECONDS );

		$status = $license_data->license;

		if( 'valid' !== $status ) {
			delete_option( 'rcp_license_status' );
		}

	}

	return $status;

}
add_action( 'admin_init', 'rcp_check_license' );