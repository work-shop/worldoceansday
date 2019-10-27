<section class="block padded related" id="project-related">
	<div class="container-fluid">
		<div class="row">
			<div class="col-left">
				<h3 class="related-title">
					More Projects
				</h3>
			</div>
			<div class="col-right">
				<div class="row" id="related-posts">
					<?php 
					$count = 0;
					$customTaxonomyTerms = wp_get_object_terms( $post->ID, 'project-categories', array('fields' => 'ids') );
					$project_query = new WP_Query( array(
						'post_type' => 'projects',
						'posts_per_page' => '3',
						'tax_query' => array(
							array(
								'taxonomy' => 'project-categories',
								'field' => 'id',
								'terms' => $customTaxonomyTerms
							)
						),
						'post__not_in' => array ($post->ID),
					) );
					while ( $project_query->have_posts() ) : $project_query->the_post(); ?>
						<article class="col-4 card card-project card-project-related project-loop-<?php echo $count; ?>">
							<a href="<?php the_permalink(); ?>" class="project-link">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail('sm_cropped'); ?>
									<?php else: ?>
										<img src="<?php echo get_bloginfo( 'stylesheet_directory' ); ?>'/images/wireframe.jpg" />
									<?php endif; ?>
									<h3 class="project-title">
										<?php the_title(); ?>
									</h3>
								</a>
							</article>
							<?php $count++; ?>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>