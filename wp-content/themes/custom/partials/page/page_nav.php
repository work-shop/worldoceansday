<?php if( get_field('show_page_navigation_menu')): ?>
	<div class="page-nav">
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<?php if( have_rows('navigation_menu_links') ): ?>
						<ul class="page-nav-list">
							<?php  while ( have_rows('navigation_menu_links') ) : the_row(); ?>
								<li>
									<?php 
									if( get_sub_field('scroll_link') ): 
										$href = '#' . get_sub_field('section_id');
										$text = get_sub_field('link_text');
										$classes = ' jump ';
									else: 
										$link = get_sub_field('link'); 
										$href = $link['url'];
										$text = $link['title'];
										$classes = ' non-jump ';
									endif; 
									?>
									<a href="<?php echo $href; ?>" class="<?php echo $classes; ?>">
										<?php echo $text ?>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>