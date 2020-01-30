<?php
/**
 *Google Maps function - Geocode address
 * @return $location
 * @since 1.0
 */
function google_maps_geocoder( $address, $force_refresh=false ) {
	
	$address_hash = md5( $address );

	$coordinates = get_transient( $address_hash );
	if ( $force_refresh || $coordinates === false ) {
		$api_key = get_option('event_manager_google_maps_api_key');
		$args       = array( 'address' => urlencode( $address ), 'sensor' => 'false' ,'key' => $api_key);
		
		$language  = get_option('event_manager_google_maps_api_language');
		$args['language'] = $language ;
				
		$url        = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
		$response 	= wp_remote_get( $url );

		if( is_wp_error( $response ) )
			return;

		$data = wp_remote_retrieve_body( $response );

		if( is_wp_error( $data ) )
			return;

		if ( $response['response']['code'] == 200 ) {

			$data = json_decode( $data );

			if ( $data->status === 'OK' ) {

				$location['street']        = false;
				$location['apt']           = false;
				$location['city']          = false;
				$location['state_short']   = false;
				$location['state_long']    = false;
				$location['zipcode']       = false;
				$location['country_short'] = false;
				$location['country_long']  = false;

				$coordinates = $data->results[0]->geometry->location;

				$location['lat']               = $coordinates->lat;
				$location['lng']               = $coordinates->lng;
				$location['formatted_address'] = (string) $data->results[0]->formatted_address;

				$address_componenets = $data->results[0]->address_components;

				foreach ($address_componenets as $ac) :

				if ($ac->types[0] == 'street_number') :
				$street_number = esc_attr($ac->long_name);
				endif;

				if ($ac->types[0] == 'route') :
				$street_f = esc_attr($ac->long_name);

				if (isset($street_number) && !empty($street_number))
					$location['street'] = $street_number . ' ' . $street_f;
				else
					$location['street'] = $street_f;
				endif;

				if ($ac->types[0] == 'subpremise')
					$location['apt'] = esc_attr($ac->long_name);

				if ($ac->types[0] == 'locality')
					$location['city'] = esc_attr($ac->long_name);

				if ($ac->types[0] == 'administrative_area_level_1') :

				$location['state_short'] = esc_attr($ac->short_name);
				$location['state_long']  = esc_attr($ac->long_name);

				endif;

				if ($ac->types[0] == 'postal_code') {
					$location['zipcode'] = esc_attr($ac->long_name);
				}
					
				if ($ac->types[0] == 'country') :

				$location['country_short'] = esc_attr($ac->short_name);
				$location['country_long']  = esc_attr($ac->long_name);

				endif;

				endforeach;

				do_action( 'google_map_geocoded_location', $location );

			} elseif ( $data->status === 'ZERO_RESULTS' ) {
				return array( 'error' => __( 'No location found for the entered address.', 'wp-event-manager-google-maps' ) );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
				return array( 'error' => __( 'Invalid request. Did you enter an address?', 'wp-event-manager-google-maps' ) );
			} elseif ( $data->status === 'OVER_QUERY_LIMIT' ) { 
    			return array( 'error' => __( 'Something went wrong while retrieving your location.', 'wp-event-manager-google-maps' ) . '<span style="display:none">OVER_QUERY_LIMIT</span>' );
    		} else {
				return array( 'error' => __( 'Something went wrong while retrieving your location.', 'wp-event-manager-google-maps' ) );
			}

		} else {
			return array( 'error' => __( 'Unable to contact Google API service.', 'wp-event-manager-google-maps' ) );
		}

	} else {
		// return cached results
		$location = $coordinates;
	}

	return $location;

}


