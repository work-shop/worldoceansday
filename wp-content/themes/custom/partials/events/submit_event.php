<section class="block padded-top pb3 bg-ultra-light" id="submit-event-intro">
	<div class="container-fluid">
		<div class="row">
			<div class="col col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
				<h2 class="font-black brand centered">
					<?php the_field('page_heading', 11); ?>
				</h2>
				<h3 class="font-bold mb2 centered brand-tint mt1">
					<?php the_field('page_subheading', 11); ?>
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
					<div class="col-12 col-lg-10 offset-lg-1 col-xl-8 offset-xl-2 submit-event-intro-logged-out">
						<div class="submit-event-buttons centered mb3">
							<a href="<?php bloginfo('url'); ?>/my-account?referer=<?php the_permalink(); ?>" class="button">Login</a>
							<a href="#submit-event" class="button jump-submit submit-event-button-create">Create an Account</a>
							<a href="#submit-event" class="button jump-submit submit-event-button-continue">Continue as a Guest</a>
						</div>
						<div class="wod-alert account-creation-message hidden" id="create-account-message-placeholder">
							<h4 class="font-medium">
								<?php the_field('create_account_helper_message'); ?>
							</h4>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="block padded bg-ultra-light" id="submit-event-thanks">
		<div class="container-fluid">
			<div class="row">
				<div class="col col-lg-8 offset-lg-2">
					<h2 class="font-black brand submit-event-success-heading centered">
						<?php the_field('success_heading'); ?>
					</h2>
					<h3 class="font-bold brand-tint mb2 submit-event-success-subheading centered">
						<?php the_field('success_subheading'); ?>
					</h3>
					<h4 class="font-bold brand-tint centered">
						<?php the_field('success_message'); ?>
					</h4>
					<?php if(is_user_logged_in()): ?>
						<div class="submit-event-intro-logged-in-buttons submit-event-buttons mt3">
							<?php $redirect = get_the_permalink() . '?user-logged-out=true'; ?>
							<a href="<?php bloginfo('url'); ?>/my-account" class="button">Manage Events</a>
							<a href="<?php echo wp_logout_url($redirect) ?>" class="button">Logout</a> 
						</div>
						<?php else: ?>
							<div class="submit-event-intro-logged-in-buttons submit-event-buttons mt3">
								<a href="<?php get_the_permalink(); ?>" class="button jump-submit">List another Event</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<section class="block padded" id="submit-event-badges">
			<div class="container">
				<div class="row">
					<div class="col col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
						<h3 class="font-black brand centered submit-event-badges-heading">
							<?php the_field('badges_heading'); ?>
						</h3>
						<?php //if(get_field('badges_subheading')): ?>
							<h4 class="font-black brand-tint centered submit-event-badges-subheading">
								<?php the_field('badges_subheading'); ?>Pick an image from below and share on social media with #togetherwecan
							</h4>
						<?php// endif; ?>
					</div>
				</div>
				<div class="row mt4 submit-event-badges">
					<?php if( have_rows('badges') ): ?>
						<?php  while ( have_rows('badges') ) : the_row(); ?>
							<div class="col-4 submit-event-badge">
								<?php $image = get_sub_field('image');
								$image = $image['sizes']['md']; ?>
								<img src="<?php echo $image; ?>">
								<div class="submit-event-badge-link centered">
									<a href="<?php echo $image; ?>" target="_blank" download class="button button-small">Download</a>
								</div>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section class="block padded padded-bottom" id="submit-event">
			<div class="container-fluid container-fluid-submit-event">
				<div class="row">
					<div class="col-12 submit-event-form-col">
						<?php echo do_shortcode( '[submit_event_form]'); ?>
					</div>
				</div>
			</div>
		</section>

