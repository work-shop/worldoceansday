<section class="block padded-less">
	<div class="container-fluid">
		<div class="row">
			<div class="col news-post-metadata">
				<h4 class="news-post-meta">
					<?php if( get_field('publish_date')): ?>
						<?php the_field('publish_date'); ?>
					<?php endif; ?>
					<?php if( get_field('author')): ?>
						<?php if( get_field('publish_date')): ?> | <?php endif; ?>By <?php the_field('author'); ?>
					<?php endif; ?>
				</h4>
			</div>
		</div>
	</div>
</section>