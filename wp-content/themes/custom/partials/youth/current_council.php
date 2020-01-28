<section class="block padded" id="current-council">
	<div class="container-fluid">
		<?php if( get_field('current_council_heading')): ?>
			<div class="section-heading-container">
				<h3 class="section-heading">
					<?php the_field('current_council_heading'); ?>
				</h3>
			</div>
		<?php endif; ?>
		<?php if( have_rows('current_council') ): ?>
			<?php $count = 0; ?>
			<div class="row people-list">
				<?php while ( have_rows('current_council') ) : the_row(); ?>
					<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb3 person person-loop-<?php echo $count; ?>">
						<a href="#" class="modal-toggle" data-modal-target="modal-person-<?php echo $count; ?>">
							<div class="person-image">
								<?php $image = get_sub_field('image');
								$image = $image['sizes']['sm_square']; ?>
								<img src="<?php echo $image; ?>" >
							</div>
							<div class="person-text">
								<h4 class="person-name">
									<?php the_sub_field('name'); ?>
								</h4>
								<?php if( get_sub_field('country') ): ?>
									<h4 class="person-country">
										<?php the_sub_field('country'); ?>
									</h4>
								<?php endif; ?>
							</div>
						</a>
						<div class="person-modal modal modal-person" id="modal-person-<?php echo $count; ?>">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-3">
										<div class="person-image">
											<img src="<?php echo $image; ?>" >
										</div>
									</div>
									<div class="col-lg-8 offset-lg-1">
										<h4 class="person-name mb0 mt1">
											<?php the_sub_field('name'); ?>
										</h4>
										<?php if( get_sub_field('country') ): ?>
											<h4 class="person-country">
												<?php the_sub_field('country'); ?>
											</h4>
										<?php endif; ?>
										<?php if( get_sub_field('link') ): ?>
											<?php $link = get_sub_field('link'); ?>
											<div class="person-link-container">
												<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="person-link">
													<?php echo $link['title']; ?>
												</a>
											</div>	
										<?php endif; ?>
										<p class="person-bio">
											<?php the_sub_field('bio'); ?> 
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php $count++; ?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>		
	</div>
</section>