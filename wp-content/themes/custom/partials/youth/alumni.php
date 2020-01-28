<section class="block padded" id="alumni">
	<div class="container-fluid">
		<?php if( get_field('alumni_heading')): ?>
			<div class="section-heading-container">
				<h3 class="section-heading">
					<?php the_field('alumni_heading'); ?>
				</h3>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-lg-6">
				<?php if( have_rows('alumni_lists') ): ?>
					<div class="alumni-lists" data-accordion-group>
						<?php  while ( have_rows('alumni_lists') ) : the_row(); ?>
							<div class="accordion multi-collapse" data-accordion>
								<div class="accordion-label" data-control>
									<h4 class="fc-collapsible-list-accordian-label-text">
										<?php the_sub_field('year_label'); ?>
									</h4>
									<span class="icon" data-icon="”"></span>
								</div>
								<div class="fc-collapsible-list-accordion-body" data-content>
									<div class="accordion-content-inner">
										<div class="wysiwyg">
											<?php the_sub_field('list'); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
				<div class="map" id="alumni-map">
					<?php
					$count = 0;
					$mapOptions = array( 'data' => array() );
					?>
					<?php if( have_rows('alumni_map') ){ ?>
						<?php  while ( have_rows('alumni_map') ) : the_row(); ?>
							<?php 
							$location = get_sub_field('country');
							$id = 'marker-' . $count;
							if ( $location && ($location['lat'] && $location['lng']) ) {
								$location = array(
									'marker' => array(
										'position' => $location,
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
				        // Okay, we got the data. Now we just need to build the html, and parse
				        // the latitude and longitude as integers.
				        mapOptions.data.forEach( function( location ) {
				        	console.log(location);
				        	location.marker.position.lat = parseFloat(location.marker.position.lat);
				        	location.marker.position.lng = parseFloat(location.marker.position.lng);
				        });
        				mapOptions.render = { zoom: 2 };
        			</script>
        			<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBBurwCtrQ2a4q-CrpB-Wa6cdLO-sR1Zxw" async defer></script>
        			<div class="ws-map" data-options="mapOptions"></div>
        		</div>
        	</div>
        </div>
    </section>