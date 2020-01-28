<section class="block padded bg-ultra-light" id="supporters-stories">
	<div class="container-fluid">
		<?php get_template_part('partials/supporters/supporters_stories'); ?>
	</div>
</section>
<section class="block padded" id="sustaining-partners">
	<div class="container-fluid">
		<div class="section-heading-container">
			<h3 class="section-heading">
				<?php the_field('sustaining_partners_heading'); ?>
			</h3>
		</div>
		<?php $count = 1; ?>
		<div class="row section-content-row partners-list">
			<?php while ( have_rows('sustaining_partners') ) : the_row(); ?>
				<div class="col-6 col-xl-2 col-sm-3 mb3 partner">
					<?php if( get_sub_field('supporter_link') ): ?>
						<a href="<?php the_sub_field('supporter_link'); ?>" class="partner-link">
						<?php endif; ?>
						<?php $image = get_sub_field('supporter_image'); ?>
						<div class="partner-image">
							<img src="<?php echo $image['sizes']['sm']; ?>" alt="<?php echo $image['alt']; ?>" class="partner-logo">
						</div>
						<?php if( get_sub_field('supporter_link') ): ?>
						</a>
					<?php endif; ?>
				</div>
				<?php $count++; ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<section class="block padded" id="supporters">
	<div class="container-fluid">
		<div class="section-heading-container">
			<h3 class="section-heading">
				<?php the_field('supporters_heading'); ?>
			</h3>
		</div>
		<?php $count = 1; ?>
		<div class="row section-content-row partners-list mb4">
			<?php while ( have_rows('supporters') ) : the_row(); ?>
				<div class="col-6 col-xl-2 col-sm-3 mb3 partner">
					<?php if( get_sub_field('supporter_link') ): ?>
						<a href="<?php the_sub_field('supporter_link'); ?>" class="partner-link">
						<?php endif; ?>
						<?php $image = get_sub_field('supporter_image'); ?>
						<div class="partner-image">
							<img src="<?php echo $image['sizes']['sm']; ?>" alt="<?php echo $image['alt']; ?>" class="partner-logo">
						</div>
						<?php if( get_sub_field('supporter_link') ): ?>
						</a>
					<?php endif; ?>
				</div>
				<?php $count++; ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
