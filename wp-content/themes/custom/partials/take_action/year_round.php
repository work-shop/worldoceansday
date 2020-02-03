<?php if( get_field('get_involved_year_round_heading') || get_field('get_involved_year_round_cards')): ?>
<section class="block padded" id="take-action-year-round">
	<div class="container-fluid">
		<?php if( get_field('get_involved_year_round_heading')): ?>
			<div class="section-heading-container">
				<h3 class="section-heading">
					<?php the_field('get_involved_year_round_heading'); ?>
				</h3>
			</div>
		<?php endif; ?>
		<?php $cards = get_field('get_involved_year_round_cards'); ?>
		<?php if( $cards ): ?>
			<div class="cards-container mb4">
				<?php foreach ($cards as $card): ?> 
					<div class="card fc-card col-md-<?php echo $card['card_width']; ?>">
						<?php if( $card['card_link']): ?>
							<a href="<?php echo $card['card_link']['url']; ?>" target="<?php echo $card['card_link']['target']; ?>" class="card-inner-link  fc-card-inner-link">
							<?php endif; ?>
							<div class="card-inner fc-card-inner">
								<?php if( $card['card_image'] ): ?>
									<?php $image = $card['card_image'];
									$image = $image['sizes']['sm_landscape']; ?>
									<img src="<?php echo $image; ?>" class="card-image fc-card-image">
								<?php endif; ?>
								<?php if( $card['card_title']): ?>
									<h4 class="card-title fc-card-title">
										<?php echo $card['card_title']; ?>
									</h4>
								<?php endif; ?>
								<?php if( $card['card_description']): ?>
									<p class="card-description fc-card-description">
										<?php echo $card['card_description']; ?>
									</p>
								<?php endif; ?>
								<div class="fc-card-button-container card-button-container">
									<?php if( $card['show_card_button'] && $card['card_link']): ?>
										<span class="card-button-text">
											<?php echo $card['card_link']['title']; ?> <span class="icon" data-icon="Ú"></span>
										</span>
									<?php endif; ?>
								</div>
							</div>
							<?php if( $card['card_link']): ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if( get_field('section_callout_image_2') && get_field('section_callout_heading_2') ): ?>
		<div class="callout-container">
			<div class="overview-block">
				<div class="overview-block-text" >
					<div class="overview-block-text-inner">
						<h2 class="block-heading">
							<?php the_field('section_callout_heading_2'); ?>
						</h2>
						<?php if( get_field('section_callout_link_2') ): ?>
							<?php $link = get_field('section_callout_link_2'); ?>
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="block-link">
								<?php echo $link['title']; ?> <span class="icon" data-icon="‹"></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
				<?php $image = get_field('section_callout_image_2');
				$image = $image['sizes']['lg']; ?>
				<div class="overview-block-image" style="background-image: url('<?php echo $image; ?>');">
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
</section>
<?php endif; ?>