<section class="block vh100" id="events-test">
	<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
	</script>
	<div class="map" id="alumni-map">
		<?php
		$count = 0;
		$mapOptions = array( 'data' => array() );
		$my_query = new WP_Query( array(
			'post_type' => 'event-test',
			'posts_per_page' => '-1',
		) );
		while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
			<?php 
			$id = 'marker-' . $count;
			//$lat = get_field('lat');
			//$lng = get_field('lng');
			$lat = rand(-180,180);
			$lng = rand(-180,180);
			if ( $lat && $lng ) {
				$location = array(
					'marker' => array(
						'position' => array(
							'lat' => $lat,
							'lng' => $lng
						),
						'popup' => false
					)
				);
				$mapOptions['data'][] = $location;
			}
			?>
			<?php $count++; ?>
		<?php endwhile; ?>
		<script>
			var mapOptions = <?php echo json_encode( $mapOptions, JSON_UNESCAPED_SLASHES ); ?>;
	        // Okay, we got the data. Now we just need to build the html, and parse
	        // the latitude and longitude as integers.
	        mapOptions.data.forEach( function( location ) {
	        	//console.log(location.marker);
	        	location.marker.position.lat = parseFloat(location.marker.position.lat);
	        	location.marker.position.lng = parseFloat(location.marker.position.lng);
	        });

	        var locations = mapOptions.data; 

	        function initMap() {

	        	var center = {lat: 0, lng: 0};

	        	var map = new google.maps.Map( document.getElementById('events-map'), {zoom: 2, center: center} );

		        // Add some markers to the map.
		        // Note: The code uses the JavaScript Array.prototype.map() method to
		        // create an array of markers based on a given "locations" array.
		        // The map() method here has nothing to do with the Google Maps API.
		        var markers = locations.map(function(location, i) {
		        	return new google.maps.Marker({
		        		position: location.marker.position
		        	});
		        });

		        var clusterStyles = [
		        {
		        	textColor: 'white',
		        	url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m1.svg',
		        	height: 50,
		        	width: 50
		        },
		        {
		        	textColor: 'white',
		        	url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m1.svg',
		        	height: 50,
		        	width: 50
		        },
		        {
		        	textColor: 'white',
		        	url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
		        	height: 50,
		        	width: 50
		        },
		        {
		        	textColor: 'white',
		        	url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
		        	height: 50,
		        	width: 50
		        },
		        {
		        	textColor: 'white',
		        	url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
		        	height: 50,
		        	width: 50
		        }
		        ];

		        var mcOptions = {
		        	gridSize: 100,
		        	styles: clusterStyles
		        };

		        // Add a marker clusterer to manage the markers.
		        var markerCluster = new MarkerClusterer(map, markers, mcOptions);

		    }

		</script>

		<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8&callback=initMap"" async defer></script>
		<div id="events-map" class="ws-map-broken" data-options="mapOptions-broken"></div>
	</div>
</section>