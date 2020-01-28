<?php if( get_field('featured_action_image') && get_field('featured_action_heading') ): ?>
<section class="block featured padded-less bg-ultra-light" id="take-action-featured">
	<div class="container-fluid container-fluid-tight">
		<div class="card card-style-featured card-style-image card-style-image-pink mb0">
			<div class="block-background" style="background-image: url('<?php $image = get_field('featured_action_image'); echo $image['sizes']['lg']; ?>);">
			</div>
			<div class="card-featured-inner">
				<div class="row mb2">
					<div class="col-lg-8">
						<h2 class="card-featured-heading take-action-featured-heading brand font-black">
							<?php the_field('featured_action_heading'); ?>
						</h2>
					</div>
				</div>
				<?php if( have_rows('featured_action_links') ): ?>
					<div class="card-featured-links">
						<?php  while ( have_rows('featured_action_links') ) : the_row(); ?>
							<?php $link = get_sub_field('link'); ?>
							<?php if( $link ): ?>
								<div class="card-featured-link mr2">
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-brand button-large">
										<?php echo $link['title']; ?>
									</a>
								</div>	
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>