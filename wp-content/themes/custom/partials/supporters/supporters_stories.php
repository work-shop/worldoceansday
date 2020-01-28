<?php if( get_field('supporters_stories', 105)): ?>
	<div class="row mb4">
		<div class="col-lg-4 col-xl-3 supporters-stories-text mb3">
			<?php if( get_field('supporters_stories_heading', 105 )): ?>
				<h2 class="brand font-black">
					<?php the_field('supporters_stories_heading', 105); ?>
				</h2>
			<?php endif; ?>
		</div>
		<div class="col-lg-8 col-xl-8 offset-xl-1 supporters-stories">
			<div class="<?php if( is_front_page() === false ){ ?> slick-supporters slick <?php  } else{ ?> supporters-stories-home <?php } ?> ">
				<?php $stories = get_field('supporters_stories', 105); ?>
				<?php $count = 0; ?>
				<?php foreach( $stories as $story): ?>
					<?php if( is_front_page() && $count === 0): ?>
						<?php 
						$post = $story;
						setup_postdata( $post ); 
						?>
						<div class="slide">
							<div class="supporter-story card-supporter">
								<?php $thumb = get_the_post_thumbnail_url($post->ID,'md'); ?>
								<div class="card-supporter-image" style="background-image: url('<?php echo $thumb; ?>');">
								</div>
								<div class="card-supporter-text">
									<div class="card-supporter-text-inner">
										<?php $logo = get_field('sponsor_story_logo'); ?>
										<?php if($logo): ?>
											<div class="card-supporter-logo">
												<img src="<?php echo $logo['sizes']['xs']; ?>" alt="sponsor logo" class="card-supporter-logo">
											</div>
										<?php endif; ?>
										<h3 class="brand-tint card-supporter-title">
											<?php the_title(); ?>
										</h3>
										<div class="card-supporter-button">
											<a href="<?php the_permalink(); ?>" class="button">
												Read The Story
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php wp_reset_postdata(); ?>
						<?php $count++; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>