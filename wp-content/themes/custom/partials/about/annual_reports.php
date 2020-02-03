<section class="block padded bg-ultra-light" id="annual-reports">
	<div class="container-fluid">

		<?php if( have_rows('reports') ): ?>
			<div class="row annual-reports">
				<?php  while ( have_rows('reports') ) : the_row(); ?>
					<div class="annual-report">
						<a href="<?php the_sub_field('file'); ?>" target="_blank">
							<h4 class="annual-report-title font-black white mb0">
								<?php the_sub_field('label'); ?>
							</h4>
						</a>
					</div>
				<?php endwhile; ?>
			</div class="row annual-reports">
		<?php endif; ?>
	</div>
</section>