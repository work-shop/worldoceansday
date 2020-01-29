<?php if( get_field('supporters_stories', 105)): ?>
	<div class="row mb4">
		<div class="col-lg-4 col-xl-4 supporters-stories-text mb3">
			<?php if( get_field('supporters_stories_heading', 105 )): ?>
				<h3 class="brand font-black">
					<?php the_field('supporters_stories_heading', 105); ?>
				</h3>
			<?php endif; ?>
			<?php if( is_front_page() && get_field('stories_link')): ?>
			<?php $link = get_field('stories_link'); ?>
			<div class="link-container mt3">
				<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-pink">
					<?php echo $link['title']; ?>
				</a>
			</div>	
		<?php endif; ?>
	</div>
	<div class="col-lg-8 col-xl-7 offset-xl-1 supporters-stories">
		<div class="<?php if( is_front_page() === false ){ ?> slick-supporters slick <?php  } else{ ?> supporters-stories-home <?php } ?> ">
			<?php $stories = get_field('supporters_stories', 105); ?>
			<?php $count = 0; ?>
			<?php if( is_front_page() ): $stories = array_slice($stories, 0, 1); endif; ?>
				<?php foreach( $stories as $story): ?>
					<?php 
					$post = $story;	
					setup_postdata( $post ); 
					?>
					<div class="slide">
						<a href="<?php the_permalink(); ?>">
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
											<span class="button">
												Read The Story
											</span>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php wp_reset_postdata(); ?>
					<?php $count++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>