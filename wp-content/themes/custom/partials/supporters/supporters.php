<section class="block padded bg-ultra-light" id="supporters-stories">
	<div class="container-fluid">
		<?php get_template_part('partials/supporters/supporters_stories'); ?>
	</div>
</section>
<section class="block padded-top pb4" id="sustaining-partners">
	<div class="container-fluid">
		<div class="section-heading-container">
			<h3 class="section-heading">
				<?php the_field('sustaining_partners_heading'); ?>
			</h3>
		</div>
		<?php $count = 1; ?>
		<div class="row section-content-row partners-list">
			<?php while ( have_rows('sustaining_partners') ) : the_row(); ?>
				<div class="col-3 col-xl-2 col-sm-3 mb3 partner">
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
<?php if( have_rows('supporters') ): ?>
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
					<div class="col-3 col-xl-2 col-sm-3 mb3 partner">
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
<?php endif; ?>
<section class="block padded bg-ultra-light" id="partner-network">
	<div class="container-fluid">
		<div class="section-heading-container">
			<h3 class="section-heading">
				<?php the_field('partner_network_heading'); ?>
			</h3>
		</div>
		<?php if( get_field('partner_network_short_description') || get_field('join_partner_network_link') ): ?>
		<div class="row mb3">
			<div class="col">
				<?php if( get_field('partner_network_short_description') ): ?>
					<h4 class="font-black brand mb2">
						<?php the_field('partner_network_short_description'); ?>
					</h4>
				<?php endif; ?>
				<?php if( get_field('join_partner_network_link') ): ?>
					<?php $link = get_field('join_partner_network_link'); ?>
					<div class="link-container">
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button">
							<?php echo $link['title']; ?>
						</a>
					</div>	
				<?php endif; ?>
			</h4>
		</div>
	</div>
<?php endif; ?>
<div class="partner-network-list mb4">
	<div class="col">
		<div class="wysiwyg">
			<?php the_field('partner_network_list'); ?>
		</div>
	</div>
</div>
</div>
</section>
