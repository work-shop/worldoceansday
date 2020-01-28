<section class="block padded history-2 bg-ultra-light vh100" id="history">
		<div class="container-fluid history-top">
			<div class="row">
				<div class="col">
					<?php $events = get_field('history_timeline'); ?>
					<?php if( have_rows('history_timeline') ){ ?>
						<div class="history-line"></div>
						<div class="history-events slick slick-history">
							<?php $count = 1; ?>
							<?php  while ( have_rows('history_timeline') ) : the_row(); ?>
									<div class="history-slide">
										<div class="history-event <?php if( get_sub_field('milestone') ): echo 'milestone'; endif; ?>">
											<h5 class="uppercase brand bold tracked history-event-year mb1">
												<?php the_sub_field('year'); ?>
											</h5>
											<?php $image = get_sub_field('event_image'); ?>
											<?php if( $image ){ ?>
												<div class="history-event-image-container mb1">
													<img class="history-event-image" src="<?php echo $image['sizes']['sm_landscape']; ?>">
												</div>
											<?php  } ?>
											<?php if( get_sub_field('event_description') ){ ?>
												<p class="history-event-description mb0">
													<?php the_sub_field('event_description'); ?>
												</p>
											<?php } ?>
										</div>

									</div>
									<?php $count++; ?>
								<?php endwhile; ?>
							</div>
					<?php } ?>
				</div>
			</div>
		</div>
</section>