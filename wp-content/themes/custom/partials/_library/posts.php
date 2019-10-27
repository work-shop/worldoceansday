<div id="posts">
	<?php 
	$count = 0;
	$project_query = new WP_Query( array(
		'post_type' => 'projects',
		'posts_per_page' => '6',
		'tax_query' => array(
			array (
				'taxonomy' => 'project-categories',
				'field' => 'slug',
				'terms' => 'featured',
			)
		),
	) );
	while ( $project_query->have_posts() ) : $project_query->the_post(); ?>
		<?php 
		$terms = wp_get_post_terms($post->ID, 'project-categories');
		$terms_classes;
		if( $terms ):
		foreach ($terms as $term) :
			$terms_classes .= 'filter-' . $term->slug . ' ';
		endforeach;
		endif;
		?>
		<article class="post post-loop-<?php echo $count; ?> filter-target <?php echo $terms_classes; ?>">
			<a href="<?php the_permalink(); ?>" class="post-link">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail('md'); ?>
					<?php else: ?>
						<img src="<?php echo get_bloginfo( 'stylesheet_directory' ); ?>/images/default.png" />
					<?php endif; ?>
					<h3 class="post-title">
						<?php the_title(); ?>
					</h3>
				</a>
			</article>
			<?php $count++; ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>