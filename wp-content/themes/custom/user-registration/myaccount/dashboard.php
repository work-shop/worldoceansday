<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion UserRegistration will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/user-registration/template-structure/
 * @author  WPEverest
 * @package UserRegistration/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="container-fluid padded-top pb5 bg-ultra-light">
	<div class="row mb2">
		<div class="col">
			<h3 class="my-account-dashboard-heading font-black brand-tint">
				<?php
				printf(
					__( 'Welcome, %1$s', 'user-registration' ),
					esc_html( $current_user->display_name )
				);
				?>
			</h3>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<a class="button" href="<?php bloginfo('url'); ?>/take-action/list-your-event">Add New Event</a>
		</div>
	</div>
</div>
<div class="container-fluid padded bg-white">
	<div class="row">
		<div class="col">
			<h3 class="brand-tint font-black">Your Events</h4>
			<?php echo do_shortcode( '[event_dashboard]'); ?>
		</div>
	</div>
</div>




<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'user_registration_account_dashboard' );

	/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
