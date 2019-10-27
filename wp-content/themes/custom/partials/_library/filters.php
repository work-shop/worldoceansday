<section class="block filters padded" id="filters">
	<div class="container-fluid">
		<div class="row">
			<div class="col filter-primary filter-category mb2" id="filters-primary">
				<div class="row">
					<div class="col">
						<h4 class="medium filter-title">
							Type:
							<span class="icon ml1" data-icon="”"></span>
						</h4>
					</div>
				</div>
				<div class="row filter-content-row">
					<div class="col">
						<?php
						$terms = get_terms( array(
							'taxonomy' => 'project-categories',
							'hide_empty' => true,
						) );
						?>
						<?php if( $terms ){ ?>
							<div class="filter-categories" id="filter-buttons-categories">
								<a  href="#" class="filter-button filter-button-category filter-button-all filter-button-reset" data-target="all">
									All
								</a>
								<?php 
								foreach ( $terms as $term ) { ?>
									<a  href="#" class="filter-button filter-button-category" data-target="filter-category-<?php echo $term->slug; ?>">
										<?php echo $term->name; ?>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col filter-secondary filter-region mb2" id="filters-secondary">
				<div class="row">
					<div class="col">
						<h4 class="medium filter-title">
							Region:
							<span class="icon ml1" data-icon="”"></span>
						</h4>
					</div>
				</div>
				<div class="row filter-content-row">
					<div class="col">
						<?php
						$terms = get_terms( array(
							'taxonomy' => 'project-regions',
							'hide_empty' => true,
						) );
						?>
						<?php if( $terms ){ ?>
							<div class="filter-regions" id="filter-buttons-regions">
								<a  href="#" class="filter-button filter-button-category filter-button-all filter-button-reset" data-target="all">
									All
								</a>
								<?php 
								foreach ( $terms as $term ) { ?>
									<a href="#" class="filter-button filter-button-region" data-target="filter-region-<?php echo $term->slug; ?>">
										<?php echo $term->name; ?>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<?php if(false){ ?>
			<div class="row" id="filter-messages">
				<div class="col">
					<div class="bg-error filter-message">
						<h4 class="filter-messages-text error centered">
							Sorry, we couldn't find any results that match your selection.
						</h4>
					</div>
				</div>
			</div>
		<?php } ?>

	</div>
</section>