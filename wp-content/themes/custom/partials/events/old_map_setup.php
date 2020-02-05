<script src="<?php bloginfo('template_directory'); ?>/js/markerclusterer.js"></script>
	<div class="map-setup">
		<?php if(false): ?>
			<?php
			$count = 0;
			$mapOptions = array( 'data' => array() );
			$my_query = new WP_Query( array(
				'post_type' => 'event_listing',
				'posts_per_page' => '-1',
			) );
			while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
				<?php 
				$post_id = $post->ID;
				$location = get_post_meta($post_id,'location');
				//print_r($location);
				$id = 'marker-' . $count;
				$lat = $location[0]['lat'];
				$lng = $location[0]['lng'];
				// $lat = rand(-180,180);
				// $lng = rand(-180,180);
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
		<?php endif; ?>
		<script>
			var mapOptions = <?php echo json_encode( $mapOptions, JSON_UNESCAPED_SLASHES ); ?>;
		</script>
		<script src="<?php bloginfo('template_directory'); ?>/js/events-map.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8&callback=initMap"" async defer></script>
	</div>