<?php 

add_action( 'rest_api_init', function () {
	register_rest_route( 'wod-events/v1', '/list', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_event_list',
	) );
	register_rest_route( 'wod-events/v1', '/map-locations', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_event_map_locations',
	) );
} );

function get_event_list( $data ){

	$count = 0;
	$event_list = array( 'data' => array() );
	$my_query = new WP_Query( array(
		'post_type' => 'event_listing',
		'posts_per_page' => '-1',
	) );
	while ( $my_query->have_posts() ) : $my_query->the_post();
		$id = 'marker-' . $count;
		$event_html = '<div class="event-list-card">' . get_the_title() . '</div>';
		$event_list['data'][] = $event_html;
		$count++;
	endwhile;

	$event_list = json_encode($event_list, JSON_UNESCAPED_SLASHES );

	return $event_list;

}

function get_event_map( $data ){
	//return 'event_map';
}



//update map location field from organizer entered address on post save
function my_acf_update_value( $value, $post_id, $field  ) {

	$original_value = $value;

	if(get_field('override_organizer_input_address', $post_id) === false){

		$location = get_post_meta($post_id,'_event_location');
		$address = $location[0];
		//print_r($address);
		$base = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
		$end = '&key=AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8';
		$url = $base . $address . $end;
		$response = wp_remote_get( $url );
		$message = '';

		if( is_wp_error( $response ) ){
			$error = 'Somethign went wrong while processing the request for the location. wp_error on $response';
			$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
			update_message_field('field_5e3442a5e4f45', $message);
			return;
		}

		$body = wp_remote_retrieve_body( $response );

		if( is_wp_error( $body ) ){
			$error = 'Somethign went wrong while processing the request for the location. wp_error on $body';
			$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
			update_message_field('field_5e3442a5e4f45', $message);
			return;
		}

		if ( $response['response']['code'] == 200 ) {

			$body = json_decode($body);

			if ( $body->status === 'OK' ) {

				$results = $body->results[0];

				$address =  $results->address_components[0]->long_name;
				$lat = $results->geometry->location->lat;
				$lng = $results->geometry->location->lng;
				$place_id = $results->place_id;
				//$street_number = $results->address_components[1]->long_name;
				//$street_name = $results->address_components[2]->long_name;
				//$street_name_short = $results->address_components[2]->short_name;
				//$city = $results->address_components[3]->long_name;
				//$state = $results->address_components[5]->long_name;
				//$state_short = $results->address_components[5]->short_name;
				//$post_code = $results->address_components[7]->long_name;
				//$country = $results->address_components[6]->long_name;
				//$country_short = $results->address_components[6]->short_name;

				$location = array(
					'address' => $address,
					'lat' => $lat,
					'lng' => $lng,
					'place_id' => $place_id//,
					// 'street_number' => $street_number,
					// 'street_name' => $street_name,
					// 'street_name_short' => $street_name_short,
					// 'city' => $city,
					// 'state' => $state,
					// 'state_short' => $state_short,
					// 'post_code' => $post_code,
					// 'country' => $country,
					// 'country_short' => $country_short		
				);
				$value = $location;

				$success = 'Map location successfully determined by Google Maps';
				$message = '<div class="wod-alert wod-alert-success success">' . $success . '</div>';
				update_message_field('field_5e3442a5e4f45', $message);

				// ob_start();
				// var_dump($location);
				// $value = ob_get_clean();

				return $value;

			} elseif ( $data->status === 'ZERO_RESULTS' ) {
				$error = 'No location found for the provided address.' . $data->status;
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_message_field('field_5e3442a5e4f45', $message);
			} elseif( $data->status === 'INVALID_REQUEST' ) {
				$error = 'Invalid request, it is likely a proper address was not entered.' . $data->status;
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_message_field('field_5e3442a5e4f45', $message);
			} elseif ( $data->status === 'OVER_QUERY_LIMIT' ) { 
				$error = 'Over Google API Query Limit.' . $data->status;
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_message_field('field_5e3442a5e4f45', $message);
			} else {
				$error = 'Something went wrong while retrieving the location.' . $data->status;
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_message_field('field_5e3442a5e4f45', $message);
			}

		} else{
			$body = json_decode($body);
			$error = 'Error ' . $response['response']['code'] . '. ' . $body->error_message;
			$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
			update_message_field('field_5e3442a5e4f45', $message);
		}

	} else{
		$success = 'Map location manually set, overriding organizer input address.';
		$message = '<div class="wod-alert">' . $success . '</div>';
		update_message_field('field_5e3442a5e4f45', $message);
		return $original_value;
	}

}


// acf/update_value/name={$field_name} - filter for a specific field based on it's name
add_filter('acf/update_value/name=location', 'my_acf_update_value', 10, 3);


function update_message_field($field_key='', $message='') {
	global $wpdb;

	$table = $wpdb->prefix.'posts';
	$field = $wpdb->get_results("SELECT * FROM $table WHERE post_name = '$field_key' AND post_type='acf-field'");
	if($field)
	{
		$meta = unserialize($field[0]->post_content);
		$meta['message'] = $message;
		$wpdb->update(
			$table,
			array(
				'post_content'=>serialize($meta)
			),
			array('post_name'=>$field_key, 'post_type'=>'acf-field'),
			array('%s')
		);
	}
}





?>
