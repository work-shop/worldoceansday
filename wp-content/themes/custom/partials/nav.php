<nav id="nav" class="fixed before">
	<div class="nav-left">
		<div id="logo" class="logo">
			<a href="<?php echo bloginfo('url'); ?>" title="Home">
				World Oceans Day
				<?php //get_template_part('partials/logo'); ?>
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
					</ul>
				<?php endif; ?>
			</div>
			<div class="nav-menu-lower">
				<ul class="nav-menus-list">
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/take-action" class="nav-primary-link dropdown-link closed mobile-closed <?php if( Helpers::is_tree(14) ): echo ' nav-current '; endif; ?>" id="nav-link-take-action" data-dropdown-target="take-action">
							Take Action
						</a>
					</li>
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/events" class="dropdown-link closed mobile-closed <?php if( Helpers::is_tree(13) ): echo ' nav-current '; endif; ?>" id="nav-link-events" data-dropdown-target="events">
							Events
						</a>
					</li>
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/resources" class="dropdown-link closed mobile-closed <?php if( Helpers::is_tree(161) ): echo ' nav-current '; endif; ?>" id="nav-link-resources" data-dropdown-target="resources">
							Resources
						</a>
					</li>
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/youth" class="dropdown-link closed mobile-closed <?php if( Helpers::is_tree(161) ): echo ' nav-current '; endif; ?>" id="nav-link-youth" data-dropdown-target="youth">
							Youth
						</a>
					</li>
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/support" class="dropdown-link closed mobile-closed <?php if( Helpers::is_tree(161) ): echo ' nav-current '; endif; ?>" id="nav-link-support" data-dropdown-target="support">
							Support
						</a>
					</li>
					<li class="has-sub-menu closed nav-menu-primary-item">
						<a href="<?php echo bloginfo('url'); ?>/about" class="dropdown-link closed mobile-closed <?php if( Helpers::is_tree(161) ): echo ' nav-current '; endif; ?>" id="nav-link-about" data-dropdown-target="about">
							About
						</a>
					</li>
					<li class="nav-menu-primary-item nav-menu-search-item">
						<a href="#search" class="search-toggle" id="nav-link-search">
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

