<section class="block padded-more" id="contact-intro">
	<?php 
	$background_image = get_field('hero_image',748);
	$background_image = $background_image['sizes']['xl'];
	?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 contact-left">
				<div class="wysiwyg contact-left-text">
					<?php the_field('contact_text_left'); ?>
				</div>
			</div>
			<div class="col-md-6 contact-right">
				<div class="wysiwyg contact-right-text mb3">
					<?php the_field('contact_text_right'); ?>
				</div>
				<div class="contact-right-social-media">
					<a href="<?php the_field('facebook_link','option'); ?>" target="_blank">
						<img src="<?php bloginfo( 'template_directory' );?>/images/fb.png" class="social-icon fb">
					</a> 
					<a href="<?php the_field('twitter_link','option'); ?>" target="_blank">
						<img src="<?php bloginfo( 'template_directory' );?>/images/tw.png" class="social-icon tw">
					</a>
					<a href="<?php the_field('instagram_link','option'); ?>" target="_blank">
						<img src="<?php bloginfo( 'template_directory' );?>/images/ig.png" class="social-icon ig">
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="block padded" id="contact-form">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4">
				<h3 class="section-heading">
					Send Us a Message
				</h3>
			</div>
			<div class="col contact-form-container">
				<?php $form_id = get_field('contact_form_to_display'); ?>
				<?php gravity_form( $form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, 1, $echo = true ); ?>
			</div>
		</div>
	</div>
</section>