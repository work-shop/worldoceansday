<div class="subscribe-form">
	<?php 
	if(is_page(33) || is_page(61)):
		$id = get_the_ID(); 
else: 
	$id = 33;
endif; ?>
<?php $form_id = get_field('form_to_display', $id); ?>
<?php gravity_form( $form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, 1, $echo = true ); ?>
</div>