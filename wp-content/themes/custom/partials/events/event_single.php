<div id="event-single-wrapper">
	<section class="block" id="single-event-hero">	
		<div class="row">
			<div class="col-lg-7 event-single-hero-image">
				<?php $banner_url = get_event_banner(); ?>
				<div class="block-background" style="background-image: url('<?php echo $banner_url; ?>');">
				</div>
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
						if( $location[0] ):  ?>
							<?php $online_event = false; ?>
							<h3 class="event-single-meta-heading event-single-meta-heading-location">
								<?php echo get_event_location(); ?>
							</h3>
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
								<?php echo get_event_start_date(); ?>
							</h3>
							<h4 class="event-single-meta-secondary">
								<?php echo get_event_start_time(); ?> - <?php echo get_event_end_time(); ?>
							</h4>
							<h4 class="event-single-meta-link">
								<a href="#event-single-map" class="jump-submit">Add to Calendar</a>
							</h4>
						</div>
					</div>
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



