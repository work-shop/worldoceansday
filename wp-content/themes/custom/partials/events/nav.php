<nav class="filter-nav" id="events-nav">
	<div class="page-nav-inner">
		<div class="container-fluid container-fluid-tight">
			<div class="row">
				<div class="col-12">
					<div class="filter" id="filter-category">
						<button class="filter-menu-button off" id="filter-menu-button-category" data-menu="#filter-menu-category">
							Category<span class="filter-menu-button-label" id="filter-menu-button-label-category"></span>
						</button>
						<menu class="filter-menu" id="filter-menu-category">
							<ul>
								<li>
									<a href="#" id="filter-button-all-category" class="filter-button filter-button-category filter-button-all" data-filter-type="category" data-slug="all">
										All
									</a>
								</li>
								<?php 
								$terms = get_terms( array(
									'taxonomy' => 'event_listing_category',
									'hide_empty' => true,
								) ); 
								foreach ($terms as $term){ ?>
									<li>
										<a href="#" class="filter-button filter-button-category" data-filter-type="category" data-slug="<?php echo $term->slug; ?>" data-name="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</menu>
					</div>
					<div class="filter" id="filter-country">
						<button class="filter-menu-button" id="filter-menu-button-country" data-menu="#filter-menu-country">
							Country<span class="filter-menu-button-label" id="filter-menu-button-label-country"></span>
						</button>
						<menu class="filter-menu" id="filter-menu-country">
							<ul>
								<li>
									<a href="#" id="filter-button-all-country" class="filter-button filter-button-country filter-button-all" data-filter-type="country" data-slug="all">
										All
									</a>
								</li>
								<?php 
								$terms = get_terms( array(
									'taxonomy' => 'event_listing_country',
									'hide_empty' => true,
								) ); 
								foreach ($terms as $term){ ?>
									<li>
										<a href="#" class="filter-button filter-button-country" data-filter-type="country" data-slug="<?php echo $term->slug; ?>" data-name="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</menu>
					</div>
					<?php // echo date("Y-m-d"); ?>
					<?php if(true): ?>
						<div class="filter" id="filter-date">
							<input id="litepicker" class="filter-input filter-input-date" value="Date" autocomplete="off">
							<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js" async defer></script>
						</div>
					<?php endif; ?>
					<div class="filter" id="filter-clear">
						<button class="filter-clear-button filter-clear"><span class="icon" data-icon="x"></span> Clear Filters</button>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="filter-screen" id="filter-screen">
	</div>
</nav>


