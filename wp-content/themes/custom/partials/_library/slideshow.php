<?php
$slides = get_field('collaboration_slideshow');
?>
<section class="block slideshow" id="slideshow">
	<?php if( $slides ): ?>
		<div class="slick slick-default">
			<?php foreach ($slides as $image): ?> 
				<div class="slick-slide">
					<div class="slide-image-container">
						<div class="slide-image vh70" style="background-image: url('<?php echo $image['sizes']['page_hero']; ?>');">
						</div>
					</div>
					<div class="slide-caption-container">
						<?php if( $image['caption'] ): ?>
							<p class="slide-caption"><?php echo $image['caption']; ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>