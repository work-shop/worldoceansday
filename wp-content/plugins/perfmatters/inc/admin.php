<?php 
//if no tab is set, default to first/options tab
if(empty($_GET['tab'])) {
	$_GET['tab'] = 'options';
} 
?>
<div class="wrap perfmatters-admin">

	<!-- Plugin Admin Page Title -->
	<h2><?php _e('Perfmatters Settings', 'perfmatters'); ?></h2>

    <!-- Tab Navigation -->
	<h2 class="nav-tab-wrapper">
		<a href="?page=perfmatters&tab=options" class="nav-tab <?php echo $_GET['tab'] == 'options' || '' ? 'nav-tab-active' : ''; ?>"><?php _e('Options', 'perfmatters'); ?></a>
		<a href="?page=perfmatters&tab=cdn" class="nav-tab <?php echo $_GET['tab'] == 'cdn' ? 'nav-tab-active' : ''; ?>"><?php _e('CDN', 'perfmatters'); ?></a>
		<a href="?page=perfmatters&tab=ga" class="nav-tab <?php echo $_GET['tab'] == 'ga' ? 'nav-tab-active' : ''; ?>"><?php _e('Google Analytics', 'perfmatters'); ?></a>
		<a href="?page=perfmatters&tab=extras" class="nav-tab <?php echo $_GET['tab'] == 'extras' ? 'nav-tab-active' : ''; ?>"><?php _e('Extras', 'perfmatters'); ?></a>
		<?php if(!is_plugin_active_for_network('perfmatters/perfmatters.php')) { ?>
			<a href="?page=perfmatters&tab=license" class="nav-tab <?php echo $_GET['tab'] == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e('License', 'perfmatters'); ?></a>
		<?php } ?>
		<a href="?page=perfmatters&tab=support" class="nav-tab <?php echo $_GET['tab'] == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'perfmatters'); ?></a>
	</h2>

	<!-- Plugin Options Form -->
	<form method="post" action="options.php">

		<!-- Main Options Tab -->
		<?php if($_GET['tab'] == 'options') { ?>

		    <?php settings_fields('perfmatters_options'); ?>
		    <?php do_settings_sections('perfmatters_options'); ?>
			<?php submit_button(); ?>

		<!-- CDN Tab -->
		<?php } elseif($_GET['tab'] == 'cdn') { ?>

			<?php settings_fields('perfmatters_cdn'); ?>
		    <?php do_settings_sections('perfmatters_cdn'); ?>
			<?php submit_button(); ?>

		<!-- Google Analytics Tab -->
		<?php } elseif($_GET['tab'] == 'ga') { ?>

			<?php settings_fields('perfmatters_ga'); ?>
		    <?php do_settings_sections('perfmatters_ga'); ?>
			<?php submit_button(); ?>

		<!-- Extras Tab -->
		<?php } elseif($_GET['tab'] == 'extras') { ?>

			<?php settings_fields('perfmatters_extras'); ?>
		    <?php do_settings_sections('perfmatters_extras'); ?>
			<?php submit_button(); ?>

		<!-- License and Activation Tab -->
		<?php } elseif($_GET['tab'] == 'license') { ?>

			<?php require_once('license.php'); ?>

		<!-- Support Tab -->
		<?php } elseif($_GET['tab'] == 'support') { ?>

			<h2><?php _e('Support', 'perfmatters'); ?></h2>
			<p><?php _e("For plugin support and documentation, please visit <a href='https://perfmatters.io/' title='perfmatters' target='_blank'>perfmatters.io</a>.", 'perfmatters'); ?></p>

		<?php } ?>
	</form>

	<?php if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') { ?>

		<div id="perfmatters-legend">
			<div id="perfmatters-tooltip-legend">
				<span>?</span><?php _e('Click on tooltip icons to view full documentation.', 'perfmatters'); ?>
			</div>
		</div>

	<?php } ?>

	<script>
		(function ($) {
			$(".perfmatters-tooltip").hover(function(){
			    $(this).closest("tr").find(".perfmatters-tooltip-text-container").show();
			},function(){
			    $(this).closest("tr").find(".perfmatters-tooltip-text-container").hide();
			});
		}(jQuery));
	</script>
	
</div>