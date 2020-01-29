<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];
$section_heading = $fc_row['section_settings']['section_heading'];

$cards = $fc_row['section_content']['cards'];

if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>
<section class="block flexible-content fc fc-cards" id="<?php echo $section_id; ?>">
	<div class="container-fc">
		<?php if( $section_heading ): ?>
			<div class="row fc-section-heading fc-row-primary">
				<div class="col-sm-12 fc-col-primary">
					<h2 class="serif fc-section-heading-text">
						<?php echo $section_heading; ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>
		<?php if( $cards ): ?>
			<div class="fc-row-primary fc-cards-container cards-container">
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
		</div>
	<?php endif; ?>
</section>