<?php
global $post;
$start_date = get_post_meta(get_the_ID(),'_event_start_date',true);
$end_date =get_post_meta(get_the_ID(),'_event_end_date',true);
?>

<div class="wpem-main wpem-google-tooltip-event-wrapper">
	<div class="wpem-google-tooltip-event-title"><a href='<?php  echo esc_url( get_permalink( get_the_ID() ));?>'><?php the_title();?></a></div>
	<div class="wpem-google-tooltip-event-start-date"><?php  _e('Start date: ','wp-event-manager-google-maps');?><?php _e($start_date,'wp-event-manager-google-maps');?></div>
	<div class="wpem-google-tooltip-event-end-date"><?php  _e('End date: ','wp-event-manager-google-maps');?><?php _e($end_date,'wp-event-manager-google-maps');?></div>
	<div class="wpem-google-tooltip-event-event-type"><?php  _e('Event Type: ','wp-event-manager-google-maps');?><?php _e(display_event_type( $post ),'wp-event-manager-google-maps');?></div>
</div>