<div id="my-account-content" class="">
	<div>
		<?php if( is_user_logged_in() === false): ?>
			<div class="registration-heading my-account-heading mb2">
				<h3 class="brand-tint font-black centered">
					Log in to worldoceansday.org
				</h3>
			</div>
		<?php endif; ?>
		<?php echo do_shortcode( '[user_registration_my_account]'); ?>
	</div>
</div>					
