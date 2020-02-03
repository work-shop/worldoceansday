<section class="block vh60" id="subscribe">
	<?php 
	$background_image = get_field('subscribe_image','33');
	$background_image = $background_image['sizes']['xl'];
	$background_text = get_field('subscribe_text','33'); 
	?>
	<div class="block-background subscribe-image" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 subscribe-content">
		<div class="row subscribe-row">
			<div class="col">
				<h2 class="white mb1 font-black">
					<?php echo $background_text; ?>
				</h2>
				<?php get_template_part('/partials/subscribe_form'); ?>
			</div>
		</div>
	</div>
</section>