<section class="block padded" id="alumni">
	<div class="container-fluid">
		<?php if( get_field('alumni_heading')): ?>
			<div class="section-heading-container">
				<h3 class="section-heading">
					<?php the_field('alumni_heading'); ?>
				</h3>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-lg-6">
				<?php if( have_rows('alumni_lists') ): ?>
					<div class="alumni-lists" data-accordion-group>
						<?php  while ( have_rows('alumni_lists') ) : the_row(); ?>
							<div class="accordion multi-collapse" data-accordion>
								<div class="accordion-label" data-control>
									<h4 class="fc-collapsible-list-accordian-label-text">
										<?php the_sub_field('year_label'); ?>
									</h4>
									<span class="icon" data-icon="”"></span>
								</div>
								<div class="fc-collapsible-list-accordion-body" data-content>
									<div class="accordion-content-inner">
										<div class="wysiwyg">
											<?php the_sub_field('list'); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
				<?php if(get_field('alumni_map_image')): ?>
					<?php $image = get_field('alumni_map_image');
					$image = $image['sizes']['lg']; ?>
					<img src="<?php echo $image; ?>">
				<?php endif; ?>
			</div>
		</div>
	</section>