<nav id="nav" class="fixed before">
	<div class="nav-left">
		<div id="logo" class="logo">
			<a href="<?php echo bloginfo('url'); ?>" title="Home">
				<?php get_template_part('partials/logo'); ?>
			</a>
		</div>
	</div>
	<div class="nav-right">
		<div id="nav-menus">
			<div class="nav-menu-upper">
				<?php if( have_rows('secondary_navigation_buttons','option') ): ?>
					<ul class="nav-menu-upper-links">
						<?php  while ( have_rows('secondary_navigation_buttons','option') ) : the_row(); ?>
							<li>
								<?php $link = get_sub_field('link'); ?>
								<?php if( $link ): ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="menu-link">
										<?php echo $link['title']; ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
						<li class="nav-account-item">
							<a href="<?php echo bloginfo('url'); ?>/my-account" class="menu-link account-link">
								<?php if( is_user_logged_in() ): ?>My Account<?php else: ?>Login<?php endif; ?>
							</a>
						</li>
					</ul>
				<?php endif; ?>
			</div>
			<div class="nav-menu-lower">
				<ul class="nav-menus-list">
					<?php if( have_rows('menus','option') ): ?>
						<?php  while ( have_rows('menus','option') ) : the_row(); ?>
							<?php if(get_sub_field('active')) : ?>
								<?php $slug = get_sub_field('menu_slug'); ?>
								<li class="has-sub-menu closed nav-menu-primary-item nav-<?php echo $slug; ?>-item">
									<?php $link = get_sub_field('menu_title'); ?>
									<?php 
									if( Helpers::is_tree(14) && $slug === 'take-action'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( Helpers::is_tree(69) || is_single('post') ) && $slug === 'blog'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( Helpers::is_tree(13) || is_singular('event_listing') ) && $slug === 'events'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( is_archive('resources') || is_singular('resources') ) && $slug === 'resources'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( Helpers::is_tree(53) ) && $slug === 'youth'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( Helpers::is_tree(97) ) && $slug === 'support'){
										$nav_current_classes = ' nav-current ';
									} elseif( ( Helpers::is_tree(73) ) && $slug === 'about'){
										$nav_current_classes = ' nav-current ';
									} else{
										$nav_current_classes = ' ';
									}
									?>
									<a href="<?php echo $link['url']; ?>" class="nav-primary-link dropdown-link closed mobile-closed <?php echo $nav_current_classes; ?> " id="nav-link-<?php the_sub_field('menu_slug'); ?>" data-dropdown-target="<?php the_sub_field('menu_slug'); ?>">
										<?php echo $link['title']; ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
					<li class="nav-menu-primary-item nav-menu-search-item">
						<a href="#search" class="search-toggle search-button search-trigger" id="search-trigger-button">
							<span class="icon search-icon" data-icon="s"></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
<div class="hamburger menu-toggle">
	<span class="hamburger-line hl-1"></span>
	<span class="hamburger-line hl-2"></span>
	<span class="hamburger-line hl-3"></span>
</div>

