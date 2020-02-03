<section class="block padded" id="mission">
	<?php 
	$background_image = get_field('mission_background_image');
	$background_image = $background_image['sizes']['xl'];
	$background_text = get_field('mission'); 
	?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 flex-center-vertical">
		<div class="row mt2 mb2">
			<div class="col-lg-8 offset-lg-1">
				<h4 class="mission-heading white font-black mb2">
					<?php the_field('mission_heading'); ?>
				</h4>
				<h3 class="white font-black">
					<?php echo $background_text; ?>
				</h3>
			</div>
		</div>
	</div>
</section>