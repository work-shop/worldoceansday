<section class="block vhmin60 padded" id="subscribe">
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
				<?php if(true): ?>
					<?php 
					if(is_page(33) || is_page(61)):
						$id = get_the_ID(); 
				else: 
					$id = 33;
				endif;
				$form_id = get_field('form_to_display', $id); 
				global $wp;
				$home_url = home_url( $wp->request ); 
				$site_url = get_bloginfo('url'); 
				$currentPage = str_replace($site_url, '', $home_url);
				?>
				<div id="subscribe-form-target" class="subscribe-form-target subscribe-form" data-form-id="<?php echo $form_id; ?>" data-page="<?php echo $currentPage; ?>/">
				</div>
				<?php else: ?>
					<?php get_template_part('partials/subscribe_form'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>