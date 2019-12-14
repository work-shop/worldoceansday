<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];

$section_background_color = $fc_row['section_style']['section_background_color'];
$section_text_color = $fc_row['section_style']['section_text_color'];
$section_text_color_string = 'style="color: ' . $section_text_color . ';"';
$section_text_color_background_string = 'style="background-color: ' . $section_text_color . ';"';
$section_container = $fc_row['section_style']['container'];
$section_stack_sm = $fc_row['section_style']['stack_sm'];
$section_gutters = $fc_row['section_style']['gutters'];
$pt = ' pt-' . $fc_row['section_style']['padding_top']; 
$pb = ' pb-' . $fc_row['section_style']['padding_bottom']; 

$section_columns = $fc_row['section_content']['columns'];

if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>

<section class="block flexible-content fc fc-multi-column <?php echo $pt . $pr . $pb . $pl; ?> <?php if( $section_gutters === false){ echo ' no-gutters'; } ?>" style="background-color: <?php echo $section_background_color; ?>;" id="<?php echo $section_id; ?>">
	
	<?php if( $section_container ) { ?> 
		<div class="container-fc">
		<?php } else{ ?>
			<div class="overflow-hidden">
			<?php } ?>
			<div class="row fc-multi-column-row">
				<?php foreach ($section_columns as $column){ ?> 
					<?php 
					if( $section_stack_sm === true ){
						$section_stack_sm_classes = ' stack';
						$col_prefix = ' col-md-';
						$offset_prefix = ' offset-md-';
					} else{
						$section_stack_sm_classes = ' no-stack';
						$col_prefix = ' col-';
						$offset_prefix = ' offset-';
					}
					$column_width = $col_prefix . $column['column_width'];
					$column_offset = $offset_prefix . $column['column_offset'];
					$mt = ' mt-' . $column['column_margin_top']; 
					$mb = ' mb-' . $column['column_margin_bottom']; 
					?>
					<div class="fc-multi-column-column <?php echo $column_width . $column_offset . $section_stack_sm_classes . $mt . $mb; ?> ">
						<div class="fc-multi-column-body wysiwyg" <?php echo $section_text_color_string; ?>>
							<?php echo $column['column_body']; ?>
						</div>
					</div>
				<?php } ?>
			</div>

		</div>
	</section>
