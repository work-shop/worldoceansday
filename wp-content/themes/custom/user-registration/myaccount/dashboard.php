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

<?php 
$action = false; 
if ( isset($_GET['action']) ): 
	$action = $_GET['action'];
endif; 
?>
<?php if( $action != 'edit' ): ?>
<div class="container-fluid padded-top pb3 bg-ultra-light">
	<div class="row mb1">
		<div class="col">
			<h3 class="my-account-dashboard-heading font-black brand-tint">
				Welcome Back!
			</h3>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<a class="button" href="<?php bloginfo('url'); ?>/take-action/list-your-event">Add New Event</a>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="container-fluid padded bg-white">
	<div class="row">
		<div class="col">
			<?php if( $action == 'edit' ): ?>
				<div class="back-to-manage-events-button mb3">
					<a href="<?php bloginfo('url'); ?>/my-account" class="button">Back to All Events</a>
				</div>
			<?php else: ?>
				<h3 class="brand-tint font-black manage-your-events-heading">Manage Your Events</h3>
			<?php endif; ?>
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
