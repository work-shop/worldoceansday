<section class="block padded bg-ultra-light" id="join-world-oceans-day-network-intro">
	<div class="container-fluid">
		<div class="row mb4">
			<div class="col-lg-7">
				<div class="wysiwyg">
					<?php the_field('introduction'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="join-world-oceans-day-network-form">
					<?php $form_id = get_field('form_to_display'); ?>
					<?php gravity_form( $form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, 1, $echo = true ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="block padded" id="world-oceans-day-network-map">
	<div class="container-fluid">
		<div class="map" id="alumni-map">
			<?php
			$count = 0;
			$mapOptions = array( 'data' => array() );
			?>
			<?php if( have_rows('partners_list') ){ ?>
				<?php  while ( have_rows('partners_list') ) : the_row(); ?>
					<?php 
					$lat = get_sub_field('latitude');
					$lng = get_sub_field('longitude');
					$title = get_sub_field('organization_name');
					$link = get_sub_field('organization_website');
					$position['lat'] = $lat;
					$position['lng'] = $lng;
					$id = 'marker-' . $count;
					if ( $lat && $lng ) {
						$location = array(
							'marker' => array(
								'position' => $position,
								'title' => $title,
								'link' => $link,
								'popup' => array(
									'id' => $id
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
				mapOptions.data.forEach( function( location ) {
					console.log(location);
					location.marker.position.lat = parseFloat(location.marker.position.lat);
					location.marker.position.lng = parseFloat(location.marker.position.lng);
					location.marker.popup.content = '<div class="marker-card"><a href="' + location.marker.link + '" target="_blank"><h4 class="marker-card-title font-black mt1">' + location.marker.title + '</h4></a></div>';
				});
				mapOptions.render = { zoom: 2 };
			</script>
			<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBBurwCtrQ2a4q-CrpB-Wa6cdLO-sR1Zxw" async defer></script>
			<div class="ws-map" data-options="mapOptions"></div>
		</div>
		<div class="row network-partner-list mt3">
			<?php
			$count = 0;
			?>
			<?php if( have_rows('partners_list') ){ ?>
				<?php  while ( have_rows('partners_list') ) : the_row(); ?>
					<?php 
					$title = get_sub_field('organization_name');
					$link = get_sub_field('organization_website');
					?>
					<div class="partner-item col-lg-4 col-md-3 col-sm-6 col-6">
						<?php if($link): ?>
							<a href="<?php echo $link; ?>" target="_blank">
							<?php endif;?>
							<h4 class="partner-name">
								<?php echo $title; ?>
							</h4>
							<?php if($link): ?>
							</a>
						<?php endif;?>
					</div>
					<?php $count++; ?>
				<?php endwhile; ?>
			<?php } ?>
		</div>
	</div>
</section>