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
					<h1 class="pink font-black tagline">
						<?php the_field('hero_tagline'); ?>
					</h1>
					<h2 class="white tagline-date font-black">
						<?php the_field('hero_date'); ?>
					</h2>
					<?php if( have_rows('hero_links') ): ?>
						<div class="background-links">
							<?php  while ( have_rows('hero_links') ) : the_row(); ?>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<div class="background-link">
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



	<section class="block padded bg-light-blue" id="home-overview">
		<div class="container-fluid container-fluid-home">
			<div class="row mb2">
				<div class="col">
					<h1 class="home-section-heading brand">
						<?php the_field('overview_heading'); ?>
					</h1>
				</div>
			</div>
			<div class="row mb4">
				<div class="col brand">
					<h3 class="font-black mission-heading">
						<?php the_field('overview_text'); ?>
					</h3>
				</div>
			</div>
			<div class="row">
				<?php if( have_rows('overview') ): ?>
					<?php  while ( have_rows('overview') ) : the_row(); ?>
						<div class="col-12 col-sm-6 col-md home-overview-block">
							<?php $image = get_sub_field('icon'); ?>
							<img src="<?php echo $image['sizes']['sm']; ?>">
							<?php $link = get_sub_field('link'); ?>
							<?php if( $link ): ?>
								<div class="home-overview-link-container">
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
										<?php echo $link['title']; ?>
									</a>
								</div>	
							<?php endif; ?>
							<p class="home-overview-block-text font-bold">
								<?php the_sub_field('text'); ?>
							</p>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>



	<section class="block padded bg-brand" id="home-who-we-are">
		<div class="who-we-are-background-image">
			<img src="<?php bloginfo('template_directory');?>/images/fish-strip.png">
		</div>
		<div class="container-fluid container-fluid-home">
			<div class="row mb4">
				<div class="col">
					<h1 class="home-section-heading pink">
						<?php the_field('who_we_are_heading'); ?>
					</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<h3 class="font-black white who-we-are-text-1">
						<?php the_field('who_we_are_text_1'); ?>
					</h3>
					<p class="white who-we-are-text-2">
						<?php the_field('who_we_are_text_2'); ?>
					</p>
					<?php if( get_field('who_we_are_link') ): ?>
						<?php $link = get_field('who_we_are_link'); ?>
						<div class="link-container">
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-pink">
								<?php echo $link['title']; ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<?php $image = get_field('who_we_are_image');
					$image = $image['sizes']['lg']; ?>
					<img class="who-we-are-image" src="<?php echo $image; ?>">
				</div>
			</div>
		</div>
	</section>

	<section class="block vhfull" id="home-stories">
		<?php 
		$background_image = get_field('stories_background_image');
		$background_image = $background_image['sizes']['xl'];
		?>
		<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
		</div>
		<div class="container-fluid container-fluid-home vhfull flex-center-vertical">
			<div>
				<div class="row mb4">
					<div class="col">
						<h1 class="home-section-heading white">
							<?php the_field('stories_heading'); ?>
						</h1>
					</div>
				</div>
				<?php get_template_part('partials/supporters/supporters_stories'); ?>
			</div>
		</div>
	</section>

	<section class="block padded" id="home-supporters">
		<div class="container-fluid container-fluid-home">
			<div class="row mb3">
				<div class="col">
					<h1 class="home-section-heading brand">
						<?php the_field('sustaining_partners_heading'); ?>
					</h1>
				</div>
			</div>
			<div class="row section-content-row partners-list mb3">
				<div class="col">
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
				</div>
			</div>
			<div class="row">
				<div class="col">
					<?php if( get_field('sustaining_partners_link') ): ?>
						<?php $link = get_field('sustaining_partners_link'); ?>
						<div class="link-container">
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-pink">
								<?php echo $link['title']; ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>


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