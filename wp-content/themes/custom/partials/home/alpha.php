<section class="block vh100" id="home-hero">
	<?php 
	$background_image = get_field('background_image');
	$background_image = $background_image['sizes']['xl'];
	$background_text = get_field('hero_tagline'); 
	?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 flex-center-vertical">
		<div class="row">
			<div class="col-8 offset-4">
				<h2 class="white mb1">
					<?php echo $background_text; ?>
				</h2>
				<div class="background-link">
					<a href="" class="button white">Link</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="block padded bg-light-blue" id="home-alpha-2">
	<div class="container-fluid">
		<div class="row">
			<div class="col centered">
				<h3 class="bold overview-heading">
					<?php the_field('overview_heading'); ?>
				</h3>
				<p class="overview-text">
					<?php the_field('overview_text'); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<section class="block padded bg-white" id="home-alpha-3">
	<div class="row" id="sponsor-stories">
		<div class="col-lg-5 col-xl-4 sponsor-stories-1">
			<h2 class="brand bold">
				<?php the_field('sponsor_stories_heading'); ?>
			</h2>
		</div>
		<div class="col-lg-7 col-xl-8 sponsor-stories-2">
			<div class="sponsor-stories-slideshow slick slick-sponsors">

			</div>
		</div>
	</div>
</section>

<section class="block padded bg-white" id="home-alpha-4">
	<div class="row" id="sponsor-logos">
		<div class="col-lg-5 col-xl-4">
			<h2 class="brand bold">
				<?php the_field('sponsor_logos_heading'); ?>
			</h2>
		</div>
		<div class="col-lg-7 col-xl-8 sponsor-logos">
			<?php if( have_rows('sponsor_list') ): ?>
				<div class="row sponsor-list">
					<?php  while ( have_rows('sponsor_list') ) : the_row(); ?>
						<div class="col-6 col-lg-3 sponsor-col">
							<?php if( get_sub_field('sponsor_link')) { ?>
								<a href="<?php echo $sponsor_link; ?>">
								<?php } ?>
								<?php $image = get_sub_field('sponsor_logo'); ?>
								<img src="<?php echo $image['sizes']['md']; ?>">
								<?php if( get_sub_field('sponsor_link')) { ?>
								</a>	
							<?php } ?>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="block padded" id="home-alpha-5">
	<?php 
	$background_image = get_field('youth_image');
	$background_image = $background_image['sizes']['xl'];
	$background_text = get_field('youth_text'); 
	?>
	<div class="block-background" style="background-image: url('<?php echo $background_image; ?>');">
	</div>
	<div class="container-fluid height-100 flex-center-vertical">
		<div class="row">
			<div class="col-8 offset-4">
				<h2 class="white mb1">
					<?php echo $background_text; ?>
				</h2>
				<div class="background-link">
					<a href="/" class="button white">Link</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="block padded bg-light" id="home-alpha-6">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<h3>We're building a new website! The full site will launch in Mid February. For now, if you need further information, you can visit the old site at <a href="http://worldoceansday.com">http://worldoceansday.com</a>
			</div>
		</div>
	</div>
</section>