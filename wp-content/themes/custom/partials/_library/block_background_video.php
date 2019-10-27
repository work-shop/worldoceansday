<section class="block vh100" id="video">
	<?php 
	$video = get_field('video');
	?>
	<video muted autoplay playsinline loop class="" id="video">
		<source src="<?php echo $video; ?>" type="video/mp4">
		</video>
	</section>
