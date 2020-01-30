<?php
/*
* Main Admin functions class which responsible for the entire amdin functionality and scripts loaded and files.
*
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WP_Event_Manager_Admin class.
 */
class WP_Event_Manager_Google_Maps_Admin {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() 
	{
		add_action( 'admin_enqueue_scripts', 	array( $this, 'admin_autocomplete'   ));
	}
	
	
	/**
	 * Trigger autocomplete on the location field in backend
	 */
	function admin_autocomplete() {
		global $post_type;
		
		if ( $post_type != 'event_listing' || get_option('event_manager_google_maps_google_address_autocomplete_backend') == false ) 
			return;
		
		$language = get_option('event_manager_google_maps_api_language');
		$region   = get_option('event_manager_google_maps_api_default_region');
		$api_key   = get_option('event_manager_google_maps_api_key');
		
		//register google maps api
		if ( !wp_script_is( 'google-maps', 'registered' ) ) {
			wp_register_script( 'google-maps', ( is_ssl() ? 'https' : 'http' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language='.$language.'&region='.$region.'&key='.$api_key, array( 'jquery' ), false );
		}
		//register google maps api
		if ( !wp_script_is( 'google-maps', 'enqueued' ) ) {
			wp_enqueue_script( 'google-maps' );
		}
		
		$country= array( 'country' => get_option('event_manager_google_maps_autocomplete_country_restriction'));
		
		$autocomplete_options = array(
				'input_address'	=> '_event_address',
				'input_pincode'	=> '_event_pincode',
				'input_location'   => '_event_location',
				'options' 		=> $country
		);
		wp_enqueue_script( 'wp-event-manager-google-maps-autocomplete-backend', EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL .'/assets/js/google-maps-autocomplete.min.js', array( 'jquery' ), EVENT_MANAGER_GOOGLE_MAPS_VERSION, true );
		wp_localize_script( 'wp-event-manager-google-maps-autocomplete-backend', 'AutoCompOptionsLocation', $autocomplete_options );
		
	}
	
}

new WP_Event_Manager_Google_Maps_Admin();