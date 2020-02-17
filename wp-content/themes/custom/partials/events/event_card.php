<article id="<?php echo get_post_field( 'post_name', get_post() ); ?>" class="event card-event">
	<a href="<?php the_permalink(); ?>">
		<div class="card-inner event-card-inner">
			<?php 
			$banner = get_post_meta($post->ID,'_event_banner');
			$banner_fallback = get_field('event_banner_fallback');
			if( !$banner || !$banner[0] ){
				$banner_url = get_field('event_banner_fallback_image');
			} else{
				$banner = get_event_banner();
				$banner_url = event_manager_get_resized_image( $banner, 'xl_landscape' );
			}
			?>
			<div class="card-image" style="background-image: url('<?php echo $banner_url; ?>');">
			</div>
			<div class="card-text">
				<small class="event-categories">
					<?php 
					$terms = get_the_terms( get_the_ID(), 'event_listing_category' );
					if ( $terms && ! is_wp_error( $terms ) ) : 
						$categories = array();
						foreach ( $terms as $term ) {
							$categories[] = $term->name;
						}
						$categories = join( " • ", $categories );
						echo $categories;
					endif; ?>
				</small>
				<h3 class="event-title brand font-black">
					<?php the_title(); ?>
				</h3>
				<h4 class="event-date">
					<?php echo get_event_start_date(); ?>, <?php echo get_event_start_time(); ?>
				</h4>
				<?php 
				$location = get_post_meta($post->ID,'_event_location');
				$venue = get_post_meta($post->ID,'_event_venue');
				$online = get_post_meta($post->ID,'_event_online');
				if( $online[0] == 'no' ):  ?>
					<?php $online_event = false; ?>
					<?php if( isset($venue[0] ) ): ?>
						<?php if( $venue[0] ): ?>
							<h4 class="event-location event-location-not-online">
								<?php echo $venue[0]; ?>
							</h4>
							<?php else: ?>
								<h4 class="event-location event-location-not-online">
									<?php echo get_event_location(); ?>
								</h4>
							<?php endif; ?>
							<?php else: ?>
								<h4 class="event-location event-location-not-online">
									<?php echo get_event_location(); ?>
								</h4>
							<?php endif; ?>
							<?php else: ?>
								<?php $online_event = true; ?>
								<h4 class="event-location event-location-online">
									Virtual Event
								</h4>
							<?php endif; ?>
						</div>
					</div>
				</a>
			</article>