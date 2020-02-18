<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div>
	<div class="cli_current_theme" data-cli-bartype="<?php echo $current_theme_type;?>">
	<?php 
	Cookie_Law_Info_Cli_Themes::cli_themeblock($current_theme);
	?>
	</div>
	<div class="cli_theme_hidden_vals">
		<?php
		for($e=1; $e<=5; $e++)
		{
			$btn_name='button_'.$e;
			$vl=$current_theme['config'][$btn_name]['text'];
			?>
			<input type="hidden" value="<?php echo $vl;?>" name="cli_theme_hid_<?php echo $btn_name;?>">
			<?php
		}
		?>
		<input type="hidden" value="<?php echo $current_theme['config']['heading']['text'];?>" name="cli_theme_hid_heading">
	</div>
	<?php
	include plugin_dir_path( __FILE__ ).'theme_property_box.php';
	?>
	<b style="width:auto; float: left; margin-bottom:5px; height:37px; line-height:57px;" class="cli_theme_hd_dv"><?php echo _e('Cookie bar - Design editor','cookie-law-info');?></b>
	<?php
	include plugin_dir_path( __FILE__ ).'theme_buttons.php';
	?>
	<div class="cli_theme_customizebox">
		<div class="cli_theme_customizecontent"></div>
		<div class="cli_theme_customizemsg" style="float:left; width:100%; display: none;">
			<?php _e('Click on the elements to customize.','cookie-law-info'); ?>
		</div>
	</div>

	<b style="width: 100%; float: left; margin-bottom:5px;" class="cli_theme_hd_tv"><?php echo _e('Cookie message - Text editor','cookie-law-info');?></b>
	<div style="width: 100%; float: left; margin-top:0px; height:auto; padding-bottom:0px;">
		<textarea class="cli_theme_box_txt"></textarea>
	</div>
	<div class="cli_theme_customizemsg cli_theme_customizewrnmsg" style="float:left; width: 100%; display: none; color: red;">
		<?php _e('Text on the selected theme may be different. Please update the cookie message accordingly in the above text view.','cookie-law-info'); ?>
	</div>

	<?php
	include plugin_dir_path( __FILE__ ).'theme_buttons.php';
	include plugin_dir_path( __FILE__ ).'themes_popup.php';
	?>
</div>