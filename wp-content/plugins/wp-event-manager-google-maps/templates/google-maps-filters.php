<?php
$within_dropdown=WP_Event_Manager_Google_Maps_Filters::get_within_filter();
$distance_dropdown=WP_Event_Manager_Google_Maps_Filters::get_distance_filter();
$order_by_dropdown=WP_Event_Manager_Google_Maps_Filters::get_order_by_filter();
?>
<div class="wpem-row">
    <!-- Search by map section section start -->	
    <div class="wpem-col">
        <div class="wpem-form-group">
            <label for="search_within_radius" class="wpem-form-label"><?php _e( 'Within', 'wp-event-manager' ); ?></label>
            <select name="search_within_radius[]" id="search_within_radius" class="event-manager-category-dropdown" data-placeholder="Within" data-no_results_text="<?php _e('No results match','event-manager-google-map');?>" data-multiple_text="<?php _e('Select Some Options','event-manager-google-map');?>" >
                    <option value="100"><?php _e('Within','event-manager-google-map');?></option>
    				<?php foreach ( $within_dropdown as $key => $value ) : ?>
                        <option  value="<?php echo $value; ?>" ><?php printf(__('%s','wp-event-manager-google-maps'),$value); ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="wpem-col">
        <div class="wpem-form-group">
            <label for="search_distance_units" class="wpem-form-label"><?php _e( 'Miles', 'wp-event-manager' ); ?></label>
            <select name="search_distance_units[]" id="search_distance_units" class="event-manager-category-dropdown" data-placeholder="Miles" data-no_results_text="<?php _e('No results match','event-manager-google-map');?>" data-multiple_text="<?php _e('Select Some Options','event-manager-google-map');?>" >
    			    <?php foreach ( $distance_dropdown as $key => $value ) : ?>
                        <option  value="<?php echo $key ; ?>" ><?php echo  $value; ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="wpem-col">
        <div class="wpem-form-group">
            <label for="search_orderby" class="wpem-form-label"><?php _e( 'Sort By', 'wp-event-manager' ); ?></label>
            <select name="search_orderby[]" id="search_orderby" class="event-manager-category-dropdown" data-placeholder="Sort By" data-no_results_text="<?php _e('No results match','event-manager-google-map');?>" data-multiple_text="<?php _e('Select Some Options','event-manager-google-map');?>" >
                    <option value=""><?php _e('Order by','event-manager-google-map');?></option>
        			<?php foreach ( $order_by_dropdown as $key => $value ) : ?>
                        <option  value="<?php echo $value ; ?>" ><?php printf(__('%s','wp-event-manager-google-maps'),$value); ?></option>
                    <?php endforeach; ?>
            </select>
        </div>
    </div>
    
</div>

<input type="hidden" id="google_map_lat" name="google_map_lat" value="" />
<input type="hidden" id="google_map_lng" name="google_map_lng" value="" />	


 
