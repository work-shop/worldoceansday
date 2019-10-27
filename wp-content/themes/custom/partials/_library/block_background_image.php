<section class="block vh60" id="home-thinking">
	<?php 
	$background_image = get_field('background_image');
	$background_image = $background_image['sizes']['page_hero'];
	$background_image = get_bloginfo( 'stylesheet_directory' ) . '/images/wireframe2.jpg';
	$background_text = get_field('background_text'); 
	$background_text = 'Exercitation esse velit elit ut pariatur ad in ex culpa aliqua aute eiusmod laborum magna culpa cupidatat anim in.'; 
	?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 flex-center-vertical">
		<div class="row">
			<div class="col-8 offset-4">
				<h2 class="white mb1">
					<?php echo $background_text; ?>
				</h2>
				<div class="background-link">
					<a href="/thinking" class="button white">Read Our Thinking</a>
				</div>
			</div>
		</div>
	</div>
</section>