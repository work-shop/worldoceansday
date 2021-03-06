<article id="<?php echo get_post_field( 'post_name', get_post() ); ?>" class="resource card-resource col-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
	<div class="card-inner resource-card-inner">
		<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'lg'); ?>
		<div class="card-image" style="background-image: url('<?php echo $featured_img_url; ?>');">
		</div>
		<div class="card-text">
			<?php if( !is_singular() ): ?>
				<a href="<?php the_permalink(); ?>">
				<?php endif; ?>
				<h3 class="resource-title brand-tint font-black">
					<?php the_title(); ?>
				</h3>
				<?php if( !is_singular() ): ?>
				</a>
			<?php endif; ?>
			<h4 class="resource-description">
				<?php the_field('resource_description'); ?>
			</h4>
			<div class="card-links">
				<?php $link = get_field('resource_link'); ?> 
				<?php if( $link ): ?>
					<a class="resource-download resource-download-link resource-link" href="<?php echo $link['url']; ?>" target="_blank" onclick="
						gtag('event', 'Link Click', {
							'event_category' : 'Resources Tracking',
							'event_label' : '<?php the_title(); ?>'
						});
						">
						<?php echo $link['title']; ?>
					</a>
					<?php else: ?>
						<?php $file = get_field('resource_file'); ?>
						<?php $file_url = $file['url']; ?>
						<a href="#" class="resource-preview modal-toggle" data-file-url="<?php echo $file_url; ?>?download=0" data-modal-target="resource-modal" onclick="
						gtag('event', 'Preview', {
							'event_category' : 'Resources Tracking',
							'event_label' : '<?php the_title(); ?>'
						});
						">
						Preview
					</a>
					<a class="resource-download" href="<?php echo $file_url; ?>?download=1" download="<?php the_title(); ?>" target="_blank" onclick="
						gtag('event', 'Download', {
							'event_category' : 'Resources Tracking',
							'event_label' : '<?php the_title(); ?>'
						});
						">
					Download
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>