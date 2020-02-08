<section class="block" id="home-hero">
	<?php if( get_field('hero_video') === false): ?>
		<?php 
		$background_image = get_field('hero_image');
		$background_image = $background_image['sizes']['xl'];
		?>
	<?php endif; ?>
	<div class="block-background" <?php if( get_field('hero_video') === false): ?> style="background-image: url('<?php echo $background_image; ?>');"<?php endif; ?> >
		<?php if( get_field('hero_video') ): ?>
			<video muted autoplay playsinline loop class="" id="home-intro-video">
				<source src="<?php the_field('hero_video'); ?>" type="video/mp4">
				</video>
			<?php endif; ?>
		</div>
		<div class="container-fluid container-fluid-home vhfull flex-center-vertical">
			<div class="row home-hero-row">
				<div class="col">
					<h1 class="brand-secondary font-black tagline">
						<?php the_field('hero_tagline'); ?>
					</h1>
					<?php if( get_field('hero_date') ): ?>
						<h2 class="white tagline-date font-black">
							<?php the_field('hero_date'); ?>
						</h2>
					<?php endif; ?>
					<?php if( get_field('hero_tertiary_text') ): ?>
						<h3 class="white tertiary-text font-black">
							<?php the_field('hero_tertiary_text'); ?>
						</h3>
					<?php endif; ?>
					<?php if( have_rows('hero_links') ): ?>
						<div class="background-links">
							<?php  while ( have_rows('hero_links') ) : the_row(); ?>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<div class="background-link mb2">
										<a href="$link<?php echo ['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-interactive">
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

	<section class="block padded bg-brand" id="home-social">
		<div class="container-fluid container-fluid-home">
			<div class="row">
				<div class="col-md-7 social-left">
					<h4 class="white font-black social-text mb2">
						<?php the_field('social_media_text'); ?>
					</h4>
					<h4 class="white font-black uppercase home-social-follow">
						<?php the_field('social_media_follow_text'); ?>
						<a href="<?php the_field('facebook_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/fb.png" class="social-icon fb">
						</a> 
						<a href="<?php the_field('twitter_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/tw.png" class="social-icon tw">
						</a>
						<a href="<?php the_field('instagram_link','option'); ?>" target="_blank">
							<img src="<?php bloginfo( 'template_directory' );?>/images/ig.png" class="social-icon ig">
						</a>
					</h4>
				</div>
				<div class="col-md-5 social-right">
					<?php $link = get_field('social_media_link'); ?>
					<?php if($link  ): ?>
						<div class="link-container">
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
								<?php echo $link['title']; ?>
							</a>
						</div>	
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="block bg-tan" id="home-take-action">
		<div class="container-fluid container-fluid-home flex-center-vertical vhfull">
			<div class="">
				<div class="row mb2">
					<div class="col">
						<h1 class="home-section-heading brand">
							<?php the_field('take_action_heading'); ?>
						</h1>
					</div>
				</div>
				<div class="row mb4">
					<div class="col-lg-9 col-md-10 brand">
						<h3 class="font-black take-action-text">
							<?php the_field('take_action_text'); ?>
						</h3>
					</div>
				</div>
				<?php $link = get_field('take_action_link'); ?>
				<?php if( $link ): ?>
					<div class="link-container">
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
							<?php echo $link['title']; ?>
						</a>
					</div>	
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="block" id="home-youth">
		<?php 
		$background_image = get_field('youth_background_image');
		$background_image = $background_image['sizes']['xl'];
		?>
		<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
		</div>
		<div class="container-fluid container-fluid-home flex-center-vertical vhfull">
			<div>
				<div class="row">
					<div class="col-lg-10 offset-lg-1">
						<h3 class="font-black white youth-text centered mb2">
							<?php the_field('youth_text'); ?>
						</h3>
						<?php if( get_field('youth_link') ): ?>
							<?php $link = get_field('youth_link'); ?>
							<div class="link-container centered">
								<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
									<?php echo $link['title']; ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="block padded-top" id="home-stories">
		<div class="container-fluid container-fluid-home flex-center-vertical">
			<div>
				<div class="row mb4">
					<div class="col">
						<h1 class="home-section-heading brand">
							<?php the_field('stories_heading'); ?>
						</h1>
					</div>
				</div>
				<?php get_template_part('partials/supporters/supporters_stories'); ?>
			</div>
		</div>
	</section>

	<section class="block pt3 pb7 home-supporters-section" id="home-supporters">
		<div class="container-fluid container-fluid-home">
			<div class="row section-content-row partners-list mb3">
				<div class="col" id="supporters-slideshow-target">
					<?php if(false): ?>
						<div class="slick-home-supporters">
							<?php $count = 1; ?>
							<?php while ( have_rows('sustaining_partners',105) ) : the_row(); ?>
								<div class="home-supporters-slide">
									<?php $link = get_field('link'); ?>
									<?php if( $link ): ?>
										<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="">
										<?php endif; ?>
										<?php $image = get_sub_field('supporter_image'); ?>
										<img src="<?php echo $image['sizes']['sm']; ?>" alt="<?php echo $image['alt']; ?>" class="">
										<?php if( $link ): ?>
										</a>
									<?php endif; ?>
								</div>
								<?php $count++; ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if( get_field('sustaining_partners_link') ): ?>
				<?php $link = get_field('sustaining_partners_link'); ?>
				<div class="link-container centered">
					<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
						<?php echo $link['title']; ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if(false): ?>
		<section class="block padded bg-ultra-light" id="home-take-action">
			<div class="container-fluid container-fluid-home">
				<div class="row mb3">
					<div class="col">
						<h1 class="home-section-heading brand">
							<?php the_field('take_action_heading'); ?>
						</h1>
					</div>
				</div>
				<div class="cards-container">
					<div class="col-md-4 card card-style-image card-style-image-<?php the_field('action_card_1_overlay_color'); ?> take-action-card">						
						<?php $link = get_field('action_card_1_button'); ?>
						<?php if( $link ): ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="card-inner-link">
							<?php endif; ?>
							<div class="card-inner">
								<?php 
								$card_image = get_field('action_card_1_image');
								$card_image = $card_image['sizes']['md'];
								?>
								<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
								</div>
								<div class="card-text">
									<h3 class="brand card-title">
										<?php the_field('action_card_1_text'); ?>
									</h3>
									<?php if( $link ): ?>
										<div class="card-button">
											<span class="button">
												<?php echo $link['title']; ?>
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<?php if( $link ): ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="col-md-4 card card-style-image card-style-image-<?php the_field('action_card_2_overlay_color'); ?> take-action-card">						
						<?php $link = get_field('action_card_2_button'); ?>
						<?php if( $link ): ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="card-inner-link">
							<?php endif; ?>
							<div class="card-inner">
								<?php 
								$card_image = get_field('action_card_2_image');
								$card_image = $card_image['sizes']['sm'];
								?>
								<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
								</div>
								<div class="card-text">
									<h3 class="brand card-title">
										<?php the_field('action_card_2_text'); ?>
									</h3>
									<?php if( $link ): ?>
										<div class="card-button">
											<span class="button">
												<?php echo $link['title']; ?>
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<?php if( $link ): ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="col-md-4 card card-style-image card-style-image-<?php the_field('action_card_3_overlay_color'); ?> take-action-card">						
						<?php $link = get_field('action_card_3_button'); ?>
						<?php if( $link ): ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="card-inner-link">
							<?php endif; ?>
							<div class="card-inner">
								<?php 
								$card_image = get_field('action_card_3_image');
								$card_image = $card_image['sizes']['sm'];
								?>
								<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
								</div>
								<div class="card-text">
									<h3 class="brand card-title">
										<?php the_field('action_card_3_text'); ?>
									</h3>
									<?php if( $link ): ?>
										<div class="card-button">
											<span class="button">
												<?php echo $link['title']; ?>
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<?php if( $link ): ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>