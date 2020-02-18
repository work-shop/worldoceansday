<?php 
$banner = get_post_meta($post->ID,'_event_banner');
$banner_fallback = get_field('event_banner_fallback');
if( !$banner || !$banner[0] ){
	$banner_url = get_field('event_banner_fallback_image');
} else{
	$banner = get_event_banner();
	$banner_url = event_manager_get_resized_image( $banner, 'xl_landscape' );
	if($banner_url){
		$banner_fallback = false;
	}
}

$start_date = get_post_meta($post->ID,'_event_start_date');
$end_date = get_post_meta($post->ID,'_event_end_date');
if( is_page(11) || get_post_status() == 'pending' || get_post_status() == 'pending_approval' ){
	$start_time = strtotime($start_date[0]);
	$start_date = date('Y-m-d', $start_time);
	$end_time = strtotime($end_date[0]);
	$end_date = date('Y-m-d', $end_time);
	$start_date = DateTime::createFromFormat('Y-m-d', $start_date);
	$end_date = DateTime::createFromFormat('Y-m-d', $end_date);
} else{
	$start_date = DateTime::createFromFormat('Y-m-d', $start_date[0]);
	$end_date = DateTime::createFromFormat('Y-m-d', $end_date[0]);
}
if( $end_date > $start_date){
	$multi_day = true;
} else{
	$multi_day = false;
}
?>
<div id="event-single-wrapper">
	<section class="block" id="single-event-hero">	
		<div class="row">
			<div class="col-lg-7 event-single-hero-image">
				<div class="block-background" style="background-image: url('<?php echo $banner_url; ?>');">
				</div>

				<?php if( is_page(11) && $banner_fallback ): ?>
					<div class="event-single-preview-fallback-image-note wod-alert wod-alert-error">
						An image has been automatically added to your event from our image library. To provide your own image, click "Edit Listing" to return to the event form, then upload an image.
					</div>
					<?php else: ?>
					<?php endif; ?>
				</div>
				<div class="col-lg-5 event-single-hero-meta">
					<h2 class="event-single-title">
						<?php the_title(); ?>
					</h2>
					<div class="row event-meta-row">
						<div class="col-2 col-xl-1 event-single-meta-icon">
							<span class="icon" data-icon=","></span>
						</div>
						<div class="col-10 col-xl-11 event-single-meta-text">
							<?php 
							$location = get_post_meta($post->ID,'_event_location');
							$venue = get_post_meta($post->ID,'_event_venue');
							$online = get_post_meta($post->ID,'_event_online');
							if( $online[0] == 'no' ):  ?>
								<?php $online_event = false; ?>
								<?php if( isset($venue[0] ) ): ?>
									<?php if( $venue[0] ): ?>
										<h3 class="event-single-meta-heading event-single-meta-heading-location">
											<?php echo $venue[0]; ?>
										</h3>
										<h4 class="event-single-meta-secondary">
											<?php echo get_event_location(); ?>
										</h4>
										<?php else: ?>
											<h3 class="event-single-meta-heading event-single-meta-heading-location">
												<?php echo get_event_location(); ?>
											</h3>
										<?php endif; ?>
										<?php else: ?>
											<h3 class="event-single-meta-heading event-single-meta-heading-location">
												<?php echo get_event_location(); ?>
											</h3>
										<?php endif; ?>
										<h4 class="event-single-meta-link">
											<a href="#single-event-map" class="jump-submit">View Map</a>
										</h4>
										<?php else: ?>
											<?php $online_event = true; ?>
											<h3 class="event-single-meta-heading event-single-meta-heading-location">
												Virtual Event
											</h3>
										<?php endif; ?>
									</div>
								</div>
								<div class="row event-meta-row">
									<div class="col-2 col-xl-1 event-single-meta-icon">
										<span class="icon" data-icon="å"></span>
									</div>
									<div class="col-10 col-xl-11 event-single-meta-text">
										<h3 class="event-single-meta-heading event-single-meta-heading-date">
											<?php 
											if( $multi_day ){
												echo 'Starts &nbsp;&nbsp;' . $start_date->format("Y-m-d") . ', ' . get_event_start_time() . ' <br>Ends &nbsp;&nbsp;&nbsp;&nbsp;' . $end_date->format("Y-m-d") . ', ' . get_event_end_time();
											} else{
												echo $start_date->format("Y-m-d");
											}
											?>
										</h3>
										<?php if( !$multi_day ): ?>
											<h4 class="event-single-meta-secondary">
												<?php echo get_event_start_time(); ?> - <?php echo get_event_end_time(); ?>
											</h4>
										<?php endif; ?>
										<h4 class="event-single-meta-link">
											<a href="#event-single-map" class="addeventatc" data-styling="none">Add to Calendar
												<span class="start"><?php echo get_event_start_date(); ?> <?php echo get_event_start_time(); ?></span>
												<span class="end"><?php echo get_event_end_date(); ?> <?php echo get_event_end_time(); ?></span>
												<span class="timezone">
													<?php 
													$timezone = get_post_meta($post->ID,'_event_timezone');
													echo $timezone[0];
													?>
												</span>
												<span class="title"><?php the_title(); ?></span>
												<span class="location">			
													<?php
													if( !$online_event ): 					
														echo get_event_location();
													else:
														echo 'Virtual Event';
													endif; 
													?>
												</span>
											</a>
										</h4>
									</div>
								</div>
								<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
								<div class="row event-meta-row">								
									<div class="col-2 col-xl-1 event-single-meta-icon">
										<span class="icon" data-icon="♁"></span>
									</div>
									<div class="col-10 col-xl-11 event-single-meta-text">
										<h3 class="event-single-meta-heading event-single-meta-heading-date">
											Organized by <?php echo get_organizer_name(); ?>
										</h3>
										<h4 class="event-single-meta-link">
											<a href="#event-single-organizer" class="jump-submit">Learn More about this Organizer</a>
										</h4>
									</div>
								</div>
								<?php if( !is_page(11) && !$banner_fallback ): ?>
									<div class="row mt2">
										<div class="col-10 col-xl-11">
											<div class="event-single-back-button">
												<a class="button button-small" href="<?php bloginfo('url');?>/events"><span class="icon mr1" data-icon="‰"></span>Back to Events</a>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</section>
						<section class="block padded-top" id="single-event-description">
							<div class="container">

								<div class="row">
									<div class="col-lg-7 single-event-description">
										<div class="wysiwyg">
											<?php
											$description = get_post_meta($post->ID,'_event_description');
											if($description[0]){
												echo $description[0];
											} else{ }
											?>
										</div>
									</div>
									<div class="col-lg-4 offset-lg-1 single-event-description-sidebar">
										<div class="single-event-categories">
											<?php 
											$terms = get_the_terms( get_the_ID(), 'event_listing_category' );
											if ( $terms && ! is_wp_error( $terms ) ) : 
												$categories = array();
												foreach ( $terms as $term ) {
													$categories[] = '<a href="' . get_bloginfo('url') . '/events?category=' . $term->slug . '" class="single-event-category-button button button-small button-bordered">' . $term->name . '</a>';
												}
												$categories = join( "", $categories );
												echo $categories;
											endif; ?>
										</div>
										<div class="single-event-website">
											<?php $event_website = get_post_meta($post->ID,'_event_registration_link'); ?>
											<?php if( $event_website ): ?>
												<a href="<?php echo $event_website; ?>" target="_blank">
													Event Website
												</a>
											<?php endif; ?>
										</div>
									</div>
									<div class="col-12 single-event-separator-col">
										<div class="single-event-separator"></div>
									</div>
								</div>
							</div>
						</section>
						<section class="block padded-top" id="single-event-organizer">
							<div class="container">
								<div class="row">
									<div class="col-lg-4 single-event-organized-by">
										<?php if( get_organizer_logo() ): ?>
											<div class="single-event-organizer-logo">
												<?php display_organizer_logo('xs'); ?>
											</div>
										<?php endif; ?>
										<h5 class="single-event-organizer-label">
											Organized By
										</h5>
										<?php 
										$organizer_website = get_post_meta($post->ID,'_organizer_website');
										$organizer_name = get_post_meta($post->ID,'_organizer_name');
										if($organizer_website[0]): ?>
											<a href="<?php echo $organizer_website[0]; ?>" target="_blank">
											<?php endif; ?>
											<h3 class="event-single-meta-heading">
												<?php echo $organizer_name[0]; ?>
											</h3>
											<?php if($organizer_website): ?>
												<h4 class="event-single-meta-link">
													<?php echo $organizer_website[0]; ?>
												</h4>
											</a>
										<?php endif; ?>
									</div>
									<div class="col-lg-7 offset-lg-1 single-event-organization-description">
										<?php 
										$organizer_description = get_post_meta($post->ID,'_organizer_description');
										if($organizer_description[0]): ?>
											<div class="wysiwyg">
												<?php echo $organizer_description[0]; ?>
											</div>
										<?php endif; ?>
									</div>
									<div class="col-12 single-event-separator-col">
										<div class="single-event-separator"></div>
									</div>
								</div>
							</div>
						</section>
						<?php if( $online_event === false): ?>
							<section class="block padded-top" id="single-event-map">
								<div class="container">
									<div class="row">
										<div class="col-12 single-event-map-heading mb1">
											<h3 class="event-single-meta-heading">
												<?php echo get_event_location(); ?>
											</h3>
										</div>
										<div class="col-12 single-event-map-container">
											<div id="single-event-map-map">
												<?php $google_maps_api_key = 'AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8'; ?>
												<iframe class="" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo $location[0]; ?>&key=<?php echo $google_maps_api_key; ?>" allowfullscreen></iframe>
											</div>
										</div>
										<div class="col-12 single-event-separator-col">
											<div class="single-event-separator"></div>
										</div>
									</div>
								</div>
							</section>
						<?php endif; ?>
						<section class="block padded" id="single-event-share">
							<div class="container">
								<div class="row">
									<div class="col-12 centered">
										<h3 class="event-single-meta-heading">
											Share this Event
										</h3>
									</div>
									<div class="col-12 event-single-share-container">
										<!-- Go to www.addthis.com/dashboard to customize your tools --> 
										<div class="addthis_inline_share_toolbox_84zx"></div>
									</div>
								</div>
							</div>
						</section>
					</div>



