<?php
/*
Plugin Name: WP Event Manager - Google Maps
Plugin URI: https://wp-eventmanager.com/
Description: Using Google maps in Event Listing.
Author: WP Event Manager
Author URI: https://wp-eventmanager.com/
Text Domain: wp-event-manager-google-maps
Domain Path: /languages
Version: 1.8.1
Since: 1.0
Requires WordPress Version at least: 4.1

Copyright: 2017 WP Event Manager
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GAM_Updater' ) ) 
     include( 'autoupdater/gam-plugin-updater.php' );
 
 function pre_check_before_installing_google_map() 
{   
    /*
	* Check weather WP Event Manager is installed or not. If WP Event Manger is not installed or active then it will give notification to admin panel
	*/
	if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
	{
			global $pagenow;
			if( $pagenow == 'plugins.php' )
			{
			   echo '<div id="error" class="error notice is-dismissible"><p>';
			   echo __('WP Event Manager is require to use WP Event Manager Google Map','wp-event-manager-google-maps');
			   echo '</p></div>';			
			}
					
	}
}
add_action( 'admin_notices', 'pre_check_before_installing_google_map' );

/**
 * WP_Event_Manager_Google_Maps class.
 */
class WP_Event_Manager_Google_Maps  extends GAM_Updater{

	/**
	 * Constructor
	 */
	public function __construct() {
		
		/** Update restriction removed.
		$plugin_slug = str_replace( '.php', '', basename( __FILE__ ) );
		$activation_key = get_option( $plugin_slug . '_licence_key' );
		if(!$activation_key) return;
		****/
		// Define constants
		define( 'EVENT_MANAGER_GOOGLE_MAPS_VERSION', '1.8.1' );
		define( 'EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		include('wp-event-manager-google-maps-functions.php');
		include('core/wp-event-manager-google-maps-fillters.php');
		include( 'shortcodes/wp-event-manager-google-maps-shortcodes.php' );
		//external
		include('external/external.php');
		 if ( is_admin() ) {
			include( 'admin/wp-event-manager-google-maps-settings.php' );
			include( 'admin/wp-event-manager-google-maps-admin.php' );
		}
		
		// Add actions
		 add_action( 'init', array( $this, 'load_plugin_textdomain' ), 12 );
		 add_action( 'wp_enqueue_scripts', array( $this, 'google_maps_frontend_scripts' ) );
		 
		 // Init updates
		 $this->init_updates( __FILE__ );
	}
	
	/**
	 * Localisation
	 */
	public function load_plugin_textdomain() {
		$domain = 'wp-event-manager-google-maps';       
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain( $domain, WP_LANG_DIR . "/wp-event-manager-google-maps/".$domain."-" .$locale. ".mo" );
		load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * frontend_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function google_maps_frontend_scripts() {
		
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
		wp_register_script('wp-event-manager-google-maps-single-event',EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL . '/assets/js/google-maps-single-event.min.js', array('jquery'), EVENT_MANAGER_GOOGLE_MAPS_VERSION, true ); 
		
		/** this will used in future to load events with filter map**/ 
		//wp_register_script('wp-event-manager-google-maps-search-form',EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL . '/assets/js/google-maps-search-form.js', array('jquery'), EVENT_MANAGER_GOOGLE_MAPS_VERSION, false ); 
		
		wp_register_script( 'wp-event-manager-google-maps-autocomplete', EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL .'/assets/js/google-maps-autocomplete.min.js', array( 'jquery' ), EVENT_MANAGER_GOOGLE_MAPS_VERSION, true );
		wp_register_script( 'wp-event-manager-google-maps-search-location-autocomplete', EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL .'/assets/js/google-maps-search-location-autocomplete.min.js', array( 'jquery' ), EVENT_MANAGER_GOOGLE_MAPS_VERSION, true );
		
		wp_register_script( 'wp-event-manager-google-maps-cluster',  'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js', array( 'jquery','google-maps' , 'wp-event-manager-content-event-listing' ), EVENT_MANAGER_GOOGLE_MAPS_VERSION, true );	
		wp_enqueue_script( 'wp-event-manager-google-maps-cluster' );
		
		wp_enqueue_style( 'wp-event-manager-google-maps-style',EVENT_MANAGER_GOOGLE_MAPS_PLUGIN_URL .'/assets/css/frontend.css' );
	}
	
}

$GLOBALS['WP_Event_Manager_Google_Maps'] = new WP_Event_Manager_Google_Maps();