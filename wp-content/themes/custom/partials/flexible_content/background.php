<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];
$section_heading = $fc_row['section_settings']['section_heading'];

$section_type = $fc_row['section_style']['section_type'];
$background_type = $section_type;
$section_background_color = $fc_row['section_style']['section_background_color'];
$section_text_color = $fc_row['section_style']['section_text_color'];
$section_height = $fc_row['section_style']['section_height'];
$section_text_color_string = 'style="color: ' . $section_text_color . ';"';
$section_text_color_background_string = 'style="background-color: ' . $section_text_color . ';"';

$background_image = $fc_row['section_content']['background_image'];
$background_image = $background_image['sizes']['page_hero'];
$background_image_masking = $fc_row['section_content']['background_image_masking'];
$background_image_masking .= ' mask ';

$include_text= $fc_row['section_content']['include_text'];
$text_alignment = $fc_row['section_content']['text_alignment'];
$heading = $fc_row['section_content']['heading'];
$heading_font = $fc_row['section_content']['heading_font'];
$subheading = $fc_row['section_content']['subheading'];
$subheading_font = $fc_row['subheading_font'];
$link_text = $fc_row['section_content']['link']['title'];
$link_url = $fc_row['section_content']['link']['url'];

$fc_background_classes = 'fc-background-' . $section_type . ' ';
$fc_background_classes .= 'vh' . $section_height . ' ';

if( $section_height === 'natural' ){
	$fc_background_classes .= ' fc-background-natural';	
} else{
	$fc_background_classes .= ' fc-background-not-natural';	
}

if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>

<section class="block flexible-content fc fc-background <?php echo $fc_background_classes; ?>" style="background-color: <?php echo $section_background_color; ?>;" id="fc-<?php echo $section_id; ?>">

	<?php if( $background_type === 'image' ){ ?>
		<?php WS_Flexible_Content_Helper::fc_background_image( $background_type, $background_image, $section_height, $background_image_masking ); ?>
	<?php } ?>

	<?php if( $include_text ): ?>
		<div class="container-fc fc-background-text">
			<div class="row">
				<div class="col-sm-12">
					<?php WS_Flexible_Content_Helper::fc_background_text( $background_type, $heading, $text_alignment, $section_text_color_string, $subheading, $link_url, $link_text ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

</section>
