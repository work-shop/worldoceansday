<section class="block vh60 bg-light" id="map">
	<?php
	$count = 0;
	$mapOptions = array( 'data' => array() );
	?>
	<?php if( have_rows('locations') ){ ?>
		<?php  while ( have_rows('locations') ) : the_row(); ?>
			<?php 
			$title = get_sub_field('location_title');
			$address = get_sub_field('location_address');
			$phone = get_sub_field('location_phone');
			$fax = get_sub_field('location_fax');
			$summary = $address . $phone . $fax;
			//$summary = 'summary';
			$location = get_sub_field('location_location');
			$link = '';
			$id = 'marker-' . $count;

			if ( $location && ($location['lat'] && $location['lng']) ) {

				$location = array(
					'marker' => array(
						'title' => $title,
						'position' => $location,
						'link' => $link,
						'popup' => array(
							'id' => $id,
							'summary' => $summary
						)
					)
				);

				$mapOptions['data'][] = $location;

			}
			?>
			<?php $count++; ?>
		<?php endwhile; ?>
	<?php } ?>

	<script>
		var mapOptions = <?php echo json_encode( $mapOptions, JSON_UNESCAPED_SLASHES ); ?>;

        // Okay, we got the data. Now we just need to build the html, and parse
        // the latitude and longitude as integers.
        mapOptions.data.forEach( function( location ) {
        	console.log(location);
        	location.marker.position.lat = parseInt(location.marker.position.lat);
        	location.marker.position.lng = parseInt(location.marker.position.lng);
        	location.marker.popup.content = '<div class="marker-card"><h4 class="marker-card-title">' + location.marker.title + '</h4><p class="marker-card-summary">' + location.marker.popup.summary + '</p></div>';
        });

        mapOptions.render = { zoom: 3 };

    </script>

     <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBBurwCtrQ2a4q-CrpB-Wa6cdLO-sR1Zxw" async defer></script>
    <div class="ws-map" data-options="mapOptions"></div>
</section>
