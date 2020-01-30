<section class="block padded bg-ultra-light" id="join-world-oceans-day-network-intro">
	<div class="container-fluid">
		<div class="row mb4">
			<div class="col-lg-7">
				<div class="wysiwyg">
					<?php the_field('introduction'); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="join-world-oceans-day-network-form">
					<?php $form_id = get_field('form_to_display'); ?>
					<?php gravity_form( $form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, 1, $echo = true ); ?>
				</div>
			</div>
		</div>
	</div>
</section>