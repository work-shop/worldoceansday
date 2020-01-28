<section class="block" id="home-hero">
	<?php if( get_field('hero_video') === false): ?>
		<?php 
		$background_image = get_field('hero_image');
		$background_image = $background_image['sizes']['xl'];
		$background_text = get_field('hero_tagline'); 
		?>
	<?php endif; ?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
		<video muted autoplay playsinline loop class="" id="home-intro-video">
			<source src="<?php bloginfo('template_directory');?>/images/video2.mp4" type="video/mp4">
			</video>
		</div>
		<div class="container-fluid vhfull flex-center-vertical">
			<div class="row home-hero-row">
				<div class="col-lg-10 offset-lg-1">
					<h1 class="pink font-black tagline">
						<?php echo $background_text; ?>
					</h1>
					<h2 class="white tagline-date font-black">
						<?php the_field('tagline_date'); ?>
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

	<section class="block padded bg-light-blue" id="home-mission">
		<div class="fish-strip">
			<img src="<?php bloginfo('template_directory'); ?>/images/fish-strip.png">
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 offset-lg-2">
					<h3 class="font-black mission-heading centered mb1">
						<?php the_field('mission_heading'); ?>
					</h3>
					<p class="mission-subheading centered">
						<?php the_field('mission_subheading'); ?>
					</p>
				</div>
			</div>
		</div>
	</section>


	<section class="block padded" id="home-supporters">
		<div class="container-fluid">
			<?php get_template_part('partials/supporters/supporters_stories'); ?>
			<div class="home-supporters mt6">
				<div class="section-heading-container mb4">
					<h3 class="brand font-bold centered">
						<?php the_field('sustaining_partners_heading'); ?>
					</h3>
				</div>
				<?php $count = 1; ?>
				<div class="row section-content-row partners-list">
					<div class="col">
						<div class="slick-home-supporters">
							<?php while ( have_rows('sustaining_partners',105) ) : the_row(); ?>
								<?php  for ($i=0; $i < 5; $i++) { ?>
									<div class="">

										<?php $image = get_sub_field('supporter_image'); ?>
										<div class="">
											<img src="<?php echo $image['sizes']['sm']; ?>" alt="<?php echo $image['alt']; ?>" class="">
										</div>

									</div>
									<?php $count++; ?>
								<?php  } ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="block padded bg-ultra-light" id="home-take-action">
		<div class="container-fluid">
			<div class="row mb3">
				<div class="col">
					<h3 class="font-bold brand">
						<?php the_field('take_action_heading'); ?>
					</h3>
				</div>
			</div>
			<div class="cards-container">

				<div class="col-md-4 card card-style-image card-style-image-brand take-action-card">
					<div class="card-inner">
						<?php $link = get_field('action_card_1_button'); ?>
						<?php if( $link ): ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
							<?php endif; ?>
							<?php 
							$card_image = get_field('action_card_1_image');
							$card_image = $card_image['sizes']['sm'];
							?>
							<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
							</div>
							<div class="card-text">
								<h3 class="brand card-title">
									<?php the_field('action_card_1_text'); ?>
								</h3>
								<?php $link = get_field('action_card_1_button'); ?>
								<?php if( $link ): ?>
									<div class="card-button">
										<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
											<?php echo $link['title']; ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
							<?php if( $link ): ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-4 card card-style-image card-style-image-interactive take-action-card">
					<div class="card-inner">
						<?php 
						$card_image = get_field('donate_card_image');
						$card_image = $card_image['sizes']['sm'];
						?>
						<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
						</div>
						<div class="card-text">
							<h3 class="white card-title">
								No matter where you live,
								we can help you find a way
								to help protect the sea.
							</h3>
							<div class="card-button">
								<a href="/" class="button">Find An Event</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 card card-style-image card-style-image-pink take-action-card">
					<div class="card-inner">
						<?php 
						$card_image = get_field('supporters_card_image');
						$card_image = $card_image['sizes']['sm'];
						?>
						<div class="block-background" style="background-image: url('<?php echo $card_image; ?>');">
						</div>
						<div class="card-text">
							<h3 class="brand card-title">
								Beach cleanup? Remote
								lecture series? Promote your
								own community event here.
							</h3>
							<div class="card-button">
								<a href="/" class="button">List Your Event</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>