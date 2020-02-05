<article id="<?php echo get_post_field( 'post_name', get_post() ); ?>" class="event card-event">
	<a href="<?php the_permalink(); ?>">
		<div class="card-inner event-card-inner">
			<?php 
			//$banner = get_post_meta($post->ID,'_event_banner'); 
			//print_r($banner);

			//$start_date = get_post_meta($post->ID,'_event_start_date'); 
			//print_r($start_date);
			?>
			<?php $banner_url = get_event_banner(); ?>
			<div class="card-image" style="background-image: url('<?php echo $banner_url; ?>');">
				<?php //var_dump(get_event_banner()[0]); ?>
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
				if( $location[0] ):  ?>
					<h4 class="event-location event-location-not-online">
						<?php echo get_event_location(); ?>
					</h4>
					<?php else: ?>
						<h4 class="event-location event-location-online">
							Virtual Event
						</h4>
					<?php endif; ?>
				</div>
			</div>
		</a>
	</article>