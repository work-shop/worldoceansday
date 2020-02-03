
<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];
$section_heading = $fc_row['section_settings']['section_heading'];

$section_type = $fc_row['section_style']['section_type'];
$section_background_color = $fc_row['section_style']['section_background_color'];
$section_text_color = $fc_row['section_style']['section_text_color'];
$section_text_color_string = 'style="color: ' . $section_text_color . ';"';
$section_text_color_background_string = 'style="background-color: #222222;"';

if($section_text_color){
	$section_text_color_background_string = 'style="background-color: ' . $section_text_color . ';"';
} 

$list_items = $fc_row['section_content']['list_items'];

if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

if( $section_type === 'collapsible'){
	$section_class = 'fc-collapsible-list';
} else if( $section_type === 'one'){
	$section_class = 'fc-repeating-paragraphs';
} else if( $section_type === 'two'){
	$section_class = 'fc-labelled-list';
}

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>

<section class="block flexible-content fc <?php echo $section_class; ?>" style="background-color: <?php echo $section_background_color; ?>;" id="<?php echo $section_id; ?>">
	<div class="container-fc">
		
		<?php if( $section_heading ): ?>
			<div class="row fc-section-heading fc-row-primary">
				<div class="col-sm-12 fc-col-primary">
					<h2 class="serif fc-section-heading-text" <?php echo $section_text_color_string; ?>>
						<?php echo $section_heading; ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $section_type === 'collapsible'){ ?>

			<?php if( $list_items ): ?>
				<div class="row fc-row-primary">
					<div class="col-xl-8 col-lg-9 col-md-10 fc-col-primary">
						<div data-accordion-group>
							<?php foreach ($list_items as $list_item): ?> 
								<div class="accordion fc-collapsible-list-accordion multi-collapse" data-accordion>
									<div class="fc-collapsible-list-accordion-label" data-control>
										<?php if( $list_item['list_item_label'] ): ?>
											<h4 class="fc-collapsible-list-accordian-label-text">
												<?php echo $list_item['list_item_label']; ?>
											</h4>
											<span class="icon" data-icon="”"></span>
										<?php endif; ?>
									</div>
									<div class="fc-collapsible-list-accordion-body" data-content>
										<div class="accordion-content-inner">
											<?php if( $list_item['list_item_body'] ): ?>
												<div class="wysiwyg">
													<?php echo $list_item['list_item_body']; ?>
												</div>
											<?php endif; ?>
											<?php if( $list_item['link']): ?>
												<div class="fc-collapsible-list-link fc-button">
													<a href="<?php echo $list_item['link']['url']; ?>" target="<?php echo $list_item['link']['target']; ?>">
														<?php echo $list_item['link']['title']; ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>	
					</div>
				</div>		
			<?php endif; ?>

		<?php } else if( $section_type === 'one'){ ?>

			<?php foreach ($list_items as $list_item): ?> 
				<div class="row fc-repeating-paragraphs-row fc-row-primary">
					<div class="col-xl-8 col-lg-9 col-md-10 fc-col-primary">
						<?php if( $list_item['list_item_label'] ): ?>
							<h3 <?php echo $section_text_color_string; ?>>
								<?php echo $list_item['list_item_label']; ?>
							</h3>
						<?php endif; ?>
						<?php if( $list_item['list_item_body'] ): ?>
							<div class="wysiwyg" <?php echo $section_text_color_string; ?>>
								<?php echo $list_item['list_item_body']; ?>
							</div>
						<?php endif; ?>
						<?php if( $list_item['link']): ?>
							<div class="fc-repeating-paragraphs-link fc-button">
								<a href="<?php echo $list_item['link']['url']; ?>" target="<?php echo $list_item['link']['target']; ?>" <?php echo $section_text_color_string; ?>>
									<?php echo $list_item['link']['title']; ?>
								</a>
							</div>
						<?php endif; ?>
						<?php if ( $list_item !== end($list_item) ): ?>
							<div class="fc-repeating-paragraphs-separator" <?php echo $section_text_color_background_string; ?>></div>				
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>

		<?php } else if( $section_type === 'two'){ ?>
			<div class="row fc-row-primary">
				<div class="col-xl-8 col-lg-9 col-md-10 fc-col-primary">
					<?php foreach ($list_items as $list_item): ?> 
						<div class="row fc-labelled-list-item">
							<div class="fc-labelled-list-label cl-md-4 col-lg-3">
								<?php if( $list_item['list_item_label'] ): ?>
									<h4 <?php echo $section_text_color_string; ?>>
										<?php echo $list_item['list_item_label']; ?>
									</h4>
								<?php endif; ?>
							</div>
							<div class="fc-labelled-list-body cl-md-8 col-lg-9">
								<?php if( $list_item['list_item_body'] ): ?>
									<div class="wysiwyg" <?php echo $section_text_color_string; ?>>
										<?php echo $list_item['list_item_body']; ?>
									</div>
								<?php endif; ?>
								<?php if( $list_item['link']): ?>
									<div class="fc-labelled-list-link fc-button">
										<a href="<?php echo $list_item['link']['url']; ?>" target="<?php echo $list_item['link']['target']; ?>" <?php echo $section_text_color_string; ?>>
											<?php echo $list_item['link']['title']; ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>	
		<?php } ?>

	</div>
</section>