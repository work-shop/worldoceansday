<footer class="block pt5 pb2 bg-brand" id="footer">
	<div class="container-fluid">
		<div class="row mb2 footer-primary">
			<div class="col-lg-3 footer-col">
				<h3 class="footer-heading mb2">
					<?php $link = get_field('footer_column_1_heading','option'); ?>
					<?php if( $link ): ?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<?php echo $link['title']; ?>
						</a>
					<?php endif; ?>
				</h3>
				<ul class="footer-list">
					<?php if( have_rows('footer_column_1_links','option') ): ?>
						<?php  while ( have_rows('footer_column_1_links','option') ) : the_row(); ?>
							<li>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
										<?php echo $link['title']; ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="col-lg-3 footer-col">
				<h3 class="footer-heading mb2">
					<?php $link = get_field('footer_column_2_heading','option'); ?>
					<?php if( $link ): ?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<?php echo $link['title']; ?>
						</a>
					<?php endif; ?>
				</h3>
				<ul class="footer-list">
					<?php if( have_rows('footer_column_2_links','option') ): ?>
						<?php  while ( have_rows('footer_column_2_links','option') ) : the_row(); ?>
							<li>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
										<?php echo $link['title']; ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="col-lg-3 footer-col">
				<h3 class="footer-heading mb2">
					<?php $link = get_field('footer_column_3_heading','option'); ?>
					<?php if( $link ): ?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<?php echo $link['title']; ?>
						</a>
					<?php endif; ?>
				</h3>
				<ul class="footer-list">
					<?php if( have_rows('footer_column_3_links','option') ): ?>
						<?php  while ( have_rows('footer_column_3_links','option') ) : the_row(); ?>
							<li>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
										<?php echo $link['title']; ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="col-lg-3 footer-col">
				<h3 class="footer-heading mb2">
					<?php $link = get_field('footer_column_4_heading','option'); ?>
					<?php if( $link ): ?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<?php echo $link['title']; ?>
						</a>
					<?php endif; ?>
				</h3>			
				<ul>
					<li class="footer-social-items">
						<a href="<?php the_field('facebook_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/fb.png" class="social-icon fb">
						</a> 
						<a href="<?php the_field('twitter_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/tw.png" class="social-icon tw">
						</a>
						<a href="<?php the_field('instagram_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/ig.png" class="social-icon ig">
						</a>
					</li>

				</ul>
			</div>
		</div>
		<div class="row mb2 footer-secondary">
			<div class="col">
				<?php if( have_rows('secondary_footer_buttons','option') ): ?>
					<ul>
						<?php  while ( have_rows('secondary_footer_buttons','option') ) : the_row(); ?>
							<li>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
										<?php echo $link['title']; ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</footer>