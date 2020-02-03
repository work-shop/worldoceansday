<div id="menus">
	<?php if( have_rows('menus','option') ): ?>
		<?php  while ( have_rows('menus','option') ) : the_row(); ?>
			<?php if(get_sub_field('active')) : ?>
				<menu id="menu-<?php the_sub_field('menu_slug'); ?>" class="menu menu-dropdown mobile-menu-closed off" data-dropdown="<?php the_sub_field('menu_slug'); ?>" >
					<div class="menu-title">
						<?php $link = get_sub_field('menu_title'); ?>
						<?php if( $link ): ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-title-link">
								<?php echo $link['title']; ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="menu-left">
						<div class="row">
							<div class="menu-col menu-column-1 col-lg-6">
								<div class="menu-heading mb2">
									<h3 class="">
										<?php $link = get_sub_field('menu_column_1_heading'); ?>
										<?php if( $link ): ?>
											<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-heading-link">
												<?php echo $link['title']; ?>
											</a>
										<?php endif; ?>
									</h3>
								</div>
								<div class="menu-links">
									<?php if( have_rows('menu_column_1_links') ): ?>
										<ul>
											<?php while ( have_rows('menu_column_1_links') ) : the_row(); ?>
												<li>
													<?php $link = get_sub_field('link'); ?>
													<?php if( $link ): ?>
														<div class="link-container">
															<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-link">
																<?php echo $link['title']; ?>
															</a>
														</div>	
													<?php endif; ?>
												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
							<div class="menu-col menu-column-2 col-lg-6">
								<div class="menu-heading mb2">
									<h3 class="">
										<?php $link = get_sub_field('menu_column_2_heading'); ?>
										<?php if( $link ): ?>
											<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-heading-link">
												<?php echo $link['title']; ?>
											</a>
										<?php endif; ?>
									</h3>
								</div>
								<div class="menu-links">
									<?php if( have_rows('menu_column_2_links') ): ?>
										<ul>
											<?php  while ( have_rows('menu_column_2_links') ) : the_row(); ?>
												<li>
													<?php $link = get_sub_field('link'); ?>
													<?php if( $link ): ?>
														<div class="link-container">
															<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-link">
																<?php echo $link['title']; ?>
															</a>
														</div>	
													<?php endif; ?>
												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="menu-right">
						<div class="menu-graphic">
							<?php $menu_image_link = get_sub_field('menu_image_link'); ?>
							<a href="<?php echo $link['url']; ?>">
								<?php $image = get_sub_field('menu_image'); ?>
								<img src="<?php echo $image['sizes']['md_landscape']; ?>">
								<?php if( get_sub_field('menu_image_heading') ): ?>
									<h4 class="menu-graphic-heading bold">
										<?php the_sub_field('menu_image_heading'); ?>
									</h4>
								<?php endif; ?>
								<?php if( get_sub_field('menu_image_text') ): ?>
									<p class="menu-graphic-text">
										<?php the_sub_field('menu_image_text'); ?>
									</p>
								<?php endif; ?>
							</a>
						</div>
					</div>
				</menu>	
			<?php endif; ?>			
		<?php endwhile; ?>
		<?php if( have_rows('secondary_navigation_buttons','option') ): ?>
			<div class="mobile-menu-secondary">
				<ul class="mobile-menu-secondary-links">
					<?php  while ( have_rows('secondary_navigation_buttons','option') ) : the_row(); ?>
						<li class="mobile-menu-secondary-link">
							<?php $link = get_sub_field('link'); ?>
							<?php if( $link ): ?>
								<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-link">
									<?php echo $link['title']; ?>
								</a>
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<div id="blanket-dropdown" class="dropdown-close"></div>
</div>
