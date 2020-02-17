<section class="block vhfull" id="subscribe">
	<?php 
	if(is_page(33) || is_page(61)){
		$id = get_the_ID(); 
	}else{
		$id = 33;
	}
	?>
	<?php 
	$background_image = get_field('subscribe_image',$id);
	$background_image = $background_image['sizes']['xl'];
	$background_text = get_field('subscribe_text',$id); 
	?>
	<div class="block-background subscribe-image" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 subscribe-content">
		<div class="row subscribe-row">
			<div class="col-lg-8 col-xl-6">
				<h2 class="white mb1 font-black">
					<?php echo $background_text; ?>
				</h2>
				<?php if( get_field('join_global_youth_movement_subheading')): ?>
				<h3 class="white mb1 font-black">
					<?php the_field('join_global_youth_movement_subheading') ?>
				</h3>
				<?php endif; ?>
				<?php 
				$form_id = get_field('form_to_display', $id); 
				?>
				<div id="subscribe-form-target" class="subscribe-form-target subscribe-form-youth" data-form-id="<?php echo $form_id; ?>" data-page="<?php echo basename(get_permalink()); ?>">
				</div>
			</div>
		</div>
	</div>
</section>