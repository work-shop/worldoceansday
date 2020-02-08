<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];
$section_heading = $fc_row['section_settings']['section_heading'];

$blocks = $fc_row['section_content']['blocks'];

if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

$count = 1;

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>
<section class="block flexible-content fc fc-overview-blocks" id="<?php echo $section_id; ?>">
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
		<?php if( $blocks ): ?>
			<div class="fc-overview-blocks overview-blocks">
				<?php foreach ($blocks as $block): ?> 
					<div class="overview-block fc-overview-block">
						<?php $block_text_color_string = 'style="color: ' . $block['block_text_color'] . ';"'; ?>
						<?php $block_background_color_string = 'style="background-color: ' . $block['block_text_background_color'] . ';"'; ?>
						<div class="overview-block-text" >
							<div class="overview-block-text-inner" <?php echo $block_background_color_string; ?>>
								<h3 class="block-heading" <?php echo $block_text_color_string; ?>>
									<?php echo $block['block_heading']; ?>
								</h3>
								<p class="block-text" <?php echo $block_text_color_string; ?>>
									<?php echo $block['block_text']; ?>
								</p>
								<?php if( $block['block_link']): ?>
									<a href="<?php echo $block['block_link']['url']; ?>" target="<?php echo $block['block_link']['target']; ?>" <?php echo $block_text_color_string; ?> class="block-link">
										<?php echo $block['block_link']['title']; ?> <span class="icon" data-icon="‹"></span>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<?php $image = $block['block_image'];
						$image = $image['sizes']['md']; ?>
						<div class="overview-block-image" style="background-image: url('<?php echo $image; ?>');">
							<?php if( $block['block_image'] ): ?>
							<?php endif; ?>
						</div>
					</div>
					<?php $count++; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>