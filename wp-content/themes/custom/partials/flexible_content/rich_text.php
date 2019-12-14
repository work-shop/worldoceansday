<?php
$fc = get_field('page_flexible_content');
$fc_row = $fc[$GLOBALS['fc_index']]; 

$section_id = $fc_row['section_settings']['section_id'];
$section_heading = $fc_row['section_settings']['section_heading'];

$rich_text = $fc_row['section_content']['rich_text'];

if($fc_row['section_content']['include_sidebar']){
	$include_sidebar = $fc_row['section_content']['include_sidebar'];
}else{
	$include_sidebar = false;
}
if( $fc_row['section_content']['sidebar_heading'] ){
	$sidebar_heading = $fc_row['section_content']['sidebar_heading'];
} else{
	$sidebar_heading = false;
}
if( $fc_row['section_content']['sidebar_text'] ){
	$sidebar_text = $fc_row['section_content']['sidebar_text'];
} else{
	$sidebar_text = false;
}
if( $fc_row['section_content']['sidebar_button'] ){
	$sidebar_button = $fc_row['section_content']['sidebar_button'];
} else{
	$sidebar_button = false;
}



if( $section_id == NULL || $section_id == false ){
	$section_id = $GLOBALS['fc_index']; 
} 

//echo '<pre>' , var_dump($fc_row) , '</pre>';

?>
<section class="block flexible-content fc fc-rich-text" id="<?php echo $section_id; ?>">
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
		<div class="row">
			<div class="col-xl-7 col-lg-9 col-md-9 col-sm-12 fc-col-primary fc-rich-text-with-sidebar-main mb3">
				<div class="rich-text fc-rich-text-container wysiwyg">
					<?php echo $rich_text; ?>
				</div>
			</div>
			<div class="col-xl-3 offset-xl-1 col-lg-3 col-md-3 col-sm-12 fc-rich-text-with-sidebar-sidebar">
				<?php if( $sidebar_heading || $sidebar_text ): ?>
					<div class="fc-sidebar">
						<div class="fc-sidebar-inner">
							<div class="fc-sidebar-content">
								<?php if($sidebar_heading): ?>
									<h4 class="bold fc-sidebar-heading">
										<?php echo $sidebar_heading; ?>
									</h4>
								<?php endif; ?>
								<?php if($sidebar_text): ?>
									<h4 class="fc-sidebar-text">
										<?php echo $sidebar_text; ?>
									</h4>
								<?php endif; ?>
							</div>
						</div>
						<?php if( $sidebar_button ): ?>
							<a href="<?php echo $sidebar_button['url']; ?>" target="<?php echo $sidebar_button['target']; ?>" class="fc-sidebar-button">
								<?php echo $sidebar_button['title']; ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</section>