<?php

define( '__ROOT__', dirname( __FILE__ ) );

require_once( __ROOT__ . '/functions/library/class-ws-cdn-url.php');

require_once( __ROOT__ . '/functions/class-ws-site-admin.php' );
require_once( __ROOT__ . '/functions/class-ws-site-init.php' );

require_once( __ROOT__ . '/functions/library/class-ws-flexible-content.php' );
require_once( __ROOT__ . '/functions/library/class-helpers.php' );

new WS_Site();
new WS_Site_Admin();

/* Do something with the data entered */
// add_action( 'save_post', 'update_event_location_field' );

// /* When the post is saved, saves our custom data */
// function update_event_location_field( $post_id ) {

//   $string = $post->_event_address; // Do something with $string 

//   update_post_meta( $post_id, 'location_test', $string );

// }

function my_acf_update_value( $value, $post_id, $field  ) {
	
	// override value
	$value = get_post_meta($post_id,'_event_address');
	//print_r($post->_event_address); 
	
	// return
	return $value[0];

}


// acf/update_value/name={$field_name} - filter for a specific field based on it's name
add_filter('acf/update_value/name=location_test', 'my_acf_update_value', 10, 3);

?>
