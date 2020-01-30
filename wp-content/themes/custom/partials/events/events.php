<section class="block" id="events">
	<div class="">
		<?php //the_content(); ?>
		<?php  //echo do_shortcode( '[events]'); ?>
		<?php  echo do_shortcode( '[events_map maps_type="ROADMAP" height="400px" width="100%" categories="cleanups"]'); ?>
	</div>
	<div class="events-container hidden">
		<div class="events-list">
			
		</div>
		<div class="events-map">
			<?php //echo do_shortcode( '[events_map]'); ?>
			<?php //the_content(); ?>
		</div>
	</div>
</section>