
<section class="block padded" id="news">
	<div class="container">
		<div class="row">
			<div class="col-lg-7 news-posts blog-posts-container">
				<?php if(false): ?>

				<?php endif; ?>
			</div>
			<div class="col-lg-4 offset-lg-1 news-filters filters">
				<div class="col filter-primary filter-category mb2" id="filters-primary">
					<div class="row filter-content-row">
						<div class="col">
							<?php
							$terms = get_terms( array(
								'taxonomy' => 'category',
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
										<a  href="#" class="filter-button filter-button-category" data-target="<?php echo $term->slug; ?>">
											<?php echo $term->name; ?>
										</a>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>