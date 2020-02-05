<nav class="resources-nav filter-nav" id="resources-nav">
	<div class="page-nav-inner">
		<div class="container-fluid container-fluid-tight">
			<div class="row">
				<div class="col">
					<div class="filter" id="filter-topic">
						<button class="filter-menu-button off" id="filter-menu-button-topic" data-menu="#filter-menu-topic">
							Topic<span class="filter-menu-button-label" id="filter-menu-button-label-topic"></span>
						</button>
						<menu class="filter-menu" id="filter-menu-topic">
							<ul>
								<li>
									<a href="#" id="filter-button-all-topic" class="filter-button filter-button-topic filter-button-all" data-filter-type="topic" data-slug="all">
										All
									</a>
								</li>
								<?php 
								$terms = get_terms( array(
									'taxonomy' => 'resources-topics',
									'hide_empty' => true,
								) ); 
								foreach ($terms as $term){ ?>
									<li>
										<a href="#" class="filter-button filter-button-topic" data-filter-type="topic" data-slug="<?php echo $term->slug; ?>" data-name="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</menu>
					</div>
					<div class="filter" id="filter-type">
						<button class="filter-menu-button" id="filter-menu-button-type" data-menu="#filter-menu-type">
							Type<span class="filter-menu-button-label" id="filter-menu-button-label-type"></span>
						</button>
						<menu class="filter-menu" id="filter-menu-type">
							<ul>
								<li>
									<a href="#" id="filter-button-all-type" class="filter-button filter-button-type filter-button-all" data-filter-type="type" data-slug="all">
										All
									</a>
								</li>
								<?php 
								$terms = get_terms( array(
									'taxonomy' => 'resources-type',
									'hide_empty' => true,
								) ); 
								foreach ($terms as $term){ ?>
									<li>
										<a href="#" class="filter-button filter-button-type" data-filter-type="type" data-slug="<?php echo $term->slug; ?>" data-name="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</menu>
					</div>
					<div class="filter" id="filter-language">
						<button class="filter-menu-button off" id="filter-menu-button-language" data-menu="#filter-menu-language">
							Language<span class="filter-menu-button-label" id="filter-menu-button-label-language"></span>
						</button>
						<menu class="filter-menu" id="filter-menu-language">
							<ul>
								<li>
									<a href="#" id="filter-button-all-language" class="filter-button filter-button-language filter-button-all" data-filter-type="language" data-slug="all">
										All
									</a>
								</li>
								<?php 
								$terms = get_terms( array(
									'taxonomy' => 'resources-language',
									'hide_empty' => true,
								) ); 
								foreach ($terms as $term){ ?>
									<li>
										<a href="#" class="filter-button filter-button-language" data-filter-type="language" data-slug="<?php echo $term->slug; ?>" data-name="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</menu>
					</div>
					<div class="filter" id="filter-clear">
						<button class="filter-clear-button filter-clear"><span class="icon" data-icon="x"></span> Clear Filters</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>


