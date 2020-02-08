<section class="block padded-top pb3 bg-ultra-light" id="submit-event-intro">
	<div class="container-fluid">
		<div class="row">
			<div class="col col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
				<h2 class="font-black brand centered">
					List your World Oceans Day event in our global database
				</h2>
				<h3 class="font-bold mb2 centered brand-tint mt1">
					World Oceans Day is 8 June, 2020
				</h3>
			</div>
			<?php if( is_user_logged_in()): ?>
				<div class="col-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2 submit-event-intro-logged-in">
					<h3 class="font-black brand-tint centered mb2">Welcome back!</h3>
					<div class="submit-event-intro-logged-in-buttons submit-event-buttons">
						<?php $redirect = get_the_permalink() . '?user-logged-out=true'; ?>
						<a href="<?php bloginfo('url'); ?>/my-account" class="button">Manage Events</a>
						<a href="<?php echo wp_logout_url($redirect) ?>" class="button">Logout</a> 
					</div>
					<div class="submit-event-intro-logged-in-buttons submit-event-buttons mt2 hidden">
						<a href="#submit-event" class="button jump-submit">List an Event</a>
					</div>
				</div>
				<?php else: ?>
					<div class="col-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2  submit-event-intro-logged-out">
						<div class="submit-event-buttons centered mb3">
							<a href="<?php bloginfo('url'); ?>/my-account?referer=<?php the_permalink(); ?>" class="button">Login</a>
							<a href="#submit-event" class="button jump-submit submit-event-button-create">Create an Account</a>
							<a href="#submit-event" class="button jump-submit submit-event-button-continue">Continue as a Guest</a>
						</div>
						<div class="submit-event-instructions submit-event-instructions-login">
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="block padded bg-ultra-light" id="submit-event-thanks">
		<div class="container-fluid">
			<div class="row mb3">
				<div class="col col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
					<h2 class="font-black brand">
						Thank You!
					</h2>
					<h3 class="font-bold brand-tint mb2">
						Your event was submitted.						
					</h3>
					<h4 class="font-bold brand-tint">
						Our team will review your event for publication. We will contact you with any questions. You will receive an email when your event is approved and published. 
					</h4>
				</div>
			</div>
			<div class="row">
				<div class="col col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
					<h2 class="font-black">Badges go here</h2>
				</div>
			</div>
		</div>
	</section>

	<section class="block padded padded-bottom" id="submit-event">
		<div class="container-fluid container-fluid-submit-event">
			<div class="row">
<!-- 				<div class="col col-md-10 offset-md-1 col-lg-6 offset-lg-3">
					<?php //echo do_shortcode( '[submit_event_form]'); ?>
				</div> -->
				<div class="col-12 submit-event-form-col">
					<?php echo do_shortcode( '[submit_event_form]'); ?>
				</div>
			</div>
		</div>
	</section>

