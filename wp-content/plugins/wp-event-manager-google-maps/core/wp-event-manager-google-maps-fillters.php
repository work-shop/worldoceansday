<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_Event_Manager_Filters class.
 */

class WP_Event_Manager_Google_Maps_Filters {
    
	/**
	 * Get within filter as array
	 */

	public static function get_within_filter() 
	{	
		$within=array();		
		$radius = get_option('event_manager_radius');
		$within = explode(',',$radius);
		return $within;
	}
	
	/**
	 * Get distance filter as array
	 */

	public static function get_distance_filter() 
	{	
		$distance=array();		

		$distance["mi"]=__("Miles",'wp-event-manager-google-maps');
		$distance["km"]=__("Kilometers",'wp-event-manager-google-maps');		
		return $distance;
	}
	
	/**
	 * Get order by by filter as array
	 */

	public static function get_order_by_filter() 
	{	
		$order_by=array();		
		$order_by = get_option('event_manager_orderby');
		$order_by = explode(',',$order_by);
		
		return $order_by;
	}

	
}

new WP_Event_Manager_Google_Maps_Filters();