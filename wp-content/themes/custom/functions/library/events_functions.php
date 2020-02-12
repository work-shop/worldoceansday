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
	register_rest_route( 'wod-events/v1', '/search', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_event_search',
	) );
} );


function get_event_list( $request ){

	$html = '';
	$results = array();

	$category = $request['category'];
	$country = $request['country'];
	$per_page = $request['per_page'];
	$page = $request['page'];

	if($category === 'all' || $category === 'All' || $category == false){
		$terms = get_terms( array(
			'taxonomy' => 'event_listing_category',
			'hide_empty' => true,
		) ); 
		$category = array();
		foreach ($terms as $term){
			$category[] = $term->slug;
		}
	}

	if($country === 'all' || $country === 'All' || $country == false){
		$terms = get_terms( array(
			'taxonomy' => 'event_listing_country',
			'hide_empty' => true,
		) ); 
		$country = array();
		foreach ($terms as $term){
			$country[] = $term->slug;
		}
	}

	$my_query = new WP_Query( array(
		'post_type' => 'event_listing',
		'posts_per_page' => $per_page,
		'paged' => $page,
		'status' => 'active',
		'meta_key' => '_event_start_date',
		'order_by' => 'meta_key',
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array (
				'taxonomy' => 'event_listing_category',
				'field' => 'slug',
				'terms' => $category,
			),
			array (
				'taxonomy' => 'event_listing_country',
				'field' => 'slug',
				'terms' => $country,
			)
		),
	) );

	$results['found_posts'] = $my_query->found_posts;
	$results['post_count'] = $my_query->post_count;

	if( $my_query->have_posts() ){
		while ( $my_query->have_posts() ) { $my_query->the_post();
			ob_start();
			get_template_part('partials/events/event_card');
			$event_html = ob_get_clean();
			$html .= $event_html;
		}
		$results['html'] = $html;
		return $results;
	} else{
		return false;
	}

}


function get_event_map_locations( $request ){

	$category = $request['category'];
	$country = $request['country'];

	$mapOptions = array( 'data' => array() );

	if($category === 'all' || $category === 'All' || $category == false){
		$terms = get_terms( array(
			'taxonomy' => 'event_listing_category',
			'hide_empty' => true,
		) ); 
		$category = array();
		foreach ($terms as $term){
			$category[] = $term->slug;
		}
	}

	if($country === 'all' || $country === 'All' || $country == false){
		$terms = get_terms( array(
			'taxonomy' => 'event_listing_country',
			'hide_empty' => true,
		) ); 
		$country = array();
		foreach ($terms as $term){
			$country[] = $term->slug;
		}
	}

	$my_query = new WP_Query( array(
		'post_type' => 'event_listing',
		'posts_per_page' => -1,
		'status' => 'active',
		'meta_key' => '_event_start_date',
		'order_by' => 'meta_key',
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array (
				'taxonomy' => 'event_listing_category',
				'field' => 'slug',
				'terms' => $category,
			),
			array (
				'taxonomy' => 'event_listing_country',
				'field' => 'slug',
				'terms' => $country,
			)
		),
	) );

	while ( $my_query->have_posts() ) : $my_query->the_post();
		$post_id = get_the_ID();
		$location = get_post_meta($post_id,'location');
		$title = get_the_title();
		$id = 'marker-' . get_post_field( 'post_name', get_post() );
		ob_start();
		echo '<div class="marker-card" id=". $id .">';
		get_template_part('partials/events/event_card');
		echo '</div>';
		$marker_card = ob_get_clean();
		$lat = $location[0]['lat'];
		$lng = $location[0]['lng'];
		if ( $lat && $lng ) {
			$location = array(
				'marker' => array(
					'title' => $title,
					'position' => array(
						'lat' => $lat,
						'lng' => $lng
					),
					'popup' => array(
						'id' => $id,
						'marker_card' => $marker_card
					)
				)
			);
			$mapOptions['data'][] = $location;
		}
	endwhile;

	//$mapOptions = json_encode( $mapOptions, JSON_UNESCAPED_SLASHES ); 

	return $mapOptions;

}



function get_event_search( $request ){

	$html = '';
	$results = array();

	$my_query = new WP_Query( array(
		'post_type' => 'event_listing',
		'status' => 'active',
		's' => 'carara',
		'meta_key' => '_event_start_date',
		'order_by' => 'meta_key',
		'order' => 'ASC'
	) );

	$results['found_posts'] = $my_query->found_posts;
	$results['post_count'] = $my_query->post_count;

	if( $my_query->have_posts() ){
		while ( $my_query->have_posts() ) { $my_query->the_post();
			ob_start();
			get_template_part('partials/events/event_card');
			$event_html = ob_get_clean();
			$html .= $event_html;
		}
		$results['html'] = $html;
		return $results;
	} else{
		return false;
	}

}



//update map location field from organizer entered address on post save
// acf/update_value/name={$field_name} - filter for a specific field based on it's name
//add_filter('acf/update_value/name=location', 'my_acf_update_value', 10, 3);
//add_action('acf/save_post', 'my_acf_update_value');
add_action('save_post', 'my_acf_update_value',10,3);
function my_acf_update_value($post_id, $post, $update ) {


	//update the banner with a fallback image if the organizer did not provide an image
	$banner = get_post_meta($post->ID,'_event_banner');
	if( !$banner || !$banner[0] ){
		$fallback_images = get_field('event_fallback_images', 13);
		$length = count($fallback_images);
		$rand = rand(0, ($length - 1) );
		$banner_url = $fallback_images[$rand]['image']['sizes']['xl_landscape'];
		$bannerArray = array();
		$bannerArray[] = $banner_url;
		update_post_meta($post_id, '_event_banner', $bannerArray);
		update_field('field_5e43f31f9b260', 1, $post_id); //fallback check
		update_field('field_5e4403ee81804', $banner_url, $post_id); //fallback image
	} else{
		update_field('field_5e43f31f9b260', 0, $post_id);
		update_field('field_5e4403ee81804', '', $post_id);
	}


	$online = get_post_meta($post_id,'_event_online');
	$online = $online[0];
	$original_value = get_field('location');


	if( get_field('override_organizer_input_address', $post_id) == false || $update == false ){

		$address;

		if( $online == 'yes' || $online == 'Yes' || $online == 'YES' ){
			$terms = get_the_terms( $post->ID , 'event_listing_country' ); 
			foreach( $terms as $term ) { 
				$address = $term->name;
				break;
				unset($term);
			}
		} else{
			$location = get_post_meta($post_id,'_event_location');
			$provided_location = $location;
			$address = $location[0];
		}

		if($address){

			//print_r($address);
			$base = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
			$end = '&key=AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8';
			$url = $base . $address . $end;
			$response = wp_remote_get( $url );
			$message = '';

			if( is_wp_error( $response ) ){
				$error = 'Somethign went wrong while processing the request for the location. wp_error on $response';
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_field('location_messages_admin', $message, $post_id);
				return;
			}

			$body = wp_remote_retrieve_body( $response );

			if( is_wp_error( $body ) ){
				$error = 'Somethign went wrong while processing the request for the location. wp_error on $body';
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_field('location_messages_admin', $message, $post_id);
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

					$success = 'Map location successfully determined by Google Maps. ';
					if( $online == 'yes' || $online == 'Yes' || $online == 'YES' ){
						$success .= ' This is an Online Event, so the Event Country setting has been used for the map location.';
					}
					$message = '<div class="wod-alert wod-alert-success success">' . $success . '</div>';

					update_field('location', $value, $post_id);
					update_field('location_messages_admin', $message, $post_id);

					// ob_start();
					// var_dump($location);
					// $value = ob_get_clean();

					return $value;

				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$error = 'No location found for the provided address.' . $data->status;
					$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
					update_field('location_messages_admin', $message, $post_id);
				} elseif( $data->status === 'INVALID_REQUEST' ) {
					$error = 'Invalid request, it is likely a proper address was not entered.' . $data->status;
					$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
					update_field('location_messages_admin', $message, $post_id);
				} elseif ( $data->status === 'OVER_QUERY_LIMIT' ) { 
					$error = 'Over Google API Query Limit.' . $data->status;
					$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
					update_field('location_messages_admin', $message, $post_id);
				} else {
					$error = 'Something went wrong while retrieving the location.' . $data->status;
					$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
					update_field('location_messages_admin', $message, $post_id);
				} //end body status okay

			} else{
				$body = json_decode($body);
				$error = 'Error ' . $response['response']['code'] . '. ' . $body->error_message . ' Location provided: ' . var_dump($provided_location);
				$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
				update_field('location_messages_admin', $message, $post_id);
			} //close response code 200

		} else{
			$error = 'Something went wrong while retrieving the location, likely the location is empty.';
			$message = '<div class="wod-alert wod-alert-error">' . $error . '</div>';
			update_field('location_messages_admin', $message, $post_id);
		} //close if address

	//overriding organizer input address
	} else{
		$success = 'Map location manually set, overriding organizer input address.';
		$message = '<div class="wod-alert">' . $success . '</div>';
		update_field('location_messages_admin', $message, $post_id);
		return $original_value;
	} //close if overriding organizer input address

}



?>
