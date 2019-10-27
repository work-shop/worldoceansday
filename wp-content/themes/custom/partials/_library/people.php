<section class="block padded" id="people">
	<div class="container-fluid">
		<div class="row">
			<div class="col-right offset">
				<h3 class="section-header mb3">
					Our People
				</h3>
				<?php if( have_rows('team_people') ): ?>
					<?php $count = 0; ?>
					<div class="row people-list">
						<?php while ( have_rows('team_people') ) : the_row(); ?>
							<div class="col-6 col-sm-6 col-md-6 mb3 person person-loop-<?php echo $count; ?>">
								<div class="person-image">
									<a href="#" class="modal-toggle" data-modal-target="modal-person-<?php echo $count; ?>">
										<?php $image = get_sub_field('person_image');
										$image = $image['sizes']['md_square']; ?>
										<img src="<?php echo $image; ?>" >
									</div>
									<div class="person-text">
										<h4 class="bold font-main person-name mb0 mt1">
											<?php the_sub_field('person_name'); ?>
										</h4>
										<h4 class="font-main person-title">
											<?php the_sub_field('person_title'); ?>
										</h4>
										<?php if( get_sub_field('person_email') ): ?>
											<h4 class="font-main person-email">
												<a href="mailto:<?php the_sub_field('person_email'); ?>">
													<?php $first_name = explode(' ', get_sub_field('person_name'), 2); ?>
													Email <?php echo $first_name[0]; ?>
												</a>
											</h4>
										<?php endif; ?>
									</div>
								</div>
								<div class="person-modal modal modal-person" id="modal-person-<?php echo $count; ?>">
									<div class="container-fluid">
										<div class="row">
											<div class="col-right offset">
												<h4 class="bold font-main person-name mb0 mt1">
													<?php the_sub_field('person_name'); ?>
												</h4>
												<h4 class="font-main person-title mb2">
													<?php the_sub_field('person_title'); ?>
												</h4>
												<p class="person-bio">
													<?php the_sub_field('person_bio'); ?>
												</p>
											</div>
										</div>
									</div>
								</div>
								<?php $count++; ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>