 <?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cli_themes_popup_overlay">
</div>
<div class="cli_themes_popup">
	<div class="cli_themes_popup_left">
		<ul class="cli_sub_tab">
		    <li data-target="theme-banner">
		    	<a><?php _e('Banner', 'cookie-law-info'); ?></a></li>
		    <li data-target="theme-popup"><a><?php _e('Popup', 'cookie-law-info'); ?></a></li>
		    <li data-target="theme-widget"><a><?php _e('Widget', 'cookie-law-info'); ?></a></li>			    
		</ul>
	</div>		
	<div class="cli_sub_tab_container cli_themes_popup_right" style="background:#efefef;">
		<div class="cli_themes_popup_head"><?php _e('Templates','cookie-law-info');?> 
			<div class="cli_theme_popup_close"><span class="dashicons dashicons-no-alt" style="line-height:20px;"></span></div>

			<ul class="cli_sub_tab">
			    <li style="border-left:solid 1px #ccc;" data-target="theme-banner">
			    	<a><?php _e('Banner', 'cookie-law-info'); ?></a></li>
			    <li data-target="theme-popup"><a><?php _e('Popup', 'cookie-law-info'); ?></a></li>
			    <li data-target="theme-widget"><a><?php _e('Widget', 'cookie-law-info'); ?></a></li>			    
			</ul>
		</div>
		<div class="cli_sub_tab_content" data-id="theme-banner" style="display:block;">
			<div class="cli_themeboxbar">
				<?php
				foreach($themes_banner as $theme_bannerK=>$theme_bannr)
				{
				?>
					<div class="cli_themeboxbar_main cli_themebox_main">
						<div class="cli_themeboxbar_sub_left">
							<input type="radio" name="cli_theme_radio" value="<?php echo $theme_bannerK;?>">
						</div>
						<div class="cli_themeboxbar_sub_right cli_themebox_sub_right">
							<?php 
							Cookie_Law_Info_Cli_Themes::cli_themeblock($theme_bannr);
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="cli_sub_tab_content" data-id="theme-popup">
			<div class="cli_themeboxbar">
				<?php
				foreach($themes_popup as $theme_barK=>$theme_bar)
				{
					
					?>
					<div class="cli_themeboxwidget_main cli_themebox_main">
						<div class="cli_themeboxwidget_sub_left">
							<input type="radio" name="cli_theme_radio" value="<?php echo $theme_barK;?>">
						</div>
						<div class="cli_themeboxwidget_sub_right cli_themebox_sub_right">
							<?php 
							Cookie_Law_Info_Cli_Themes::cli_themeblock($theme_bar);
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="cli_sub_tab_content" data-id="theme-widget">
			<div class="cli_themeboxbar" style="padding-top:25px;">				
				<?php
				foreach($themes_widget as $themes_widgetK=>$themes_widget)
				{
					
					?>
					<div class="cli_themeboxwidget_main cli_themebox_main">
						<div class="cli_themeboxwidget_sub_left">
							<input type="radio" name="cli_theme_radio" value="<?php echo $themes_widgetK;?>">
						</div>
						<div class="cli_themeboxwidget_sub_right cli_themebox_sub_right">
							<?php 
							Cookie_Law_Info_Cli_Themes::cli_themeblock($themes_widget);
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>

		<div class="cli_themes_popup_footer">
			<button type="button" name="cli_theme_customize" class="button-primary" style="height: 28px; float: right;">
				<span class="dashicons dashicons-admin-customizer" style="line-height: 28px;"></span> <?php _e('Customize','cookie-law-info');?>
			</button>
			<a name="cli_theme_live_preview" class="button-secondary" style="height: 28px; float: right; margin-right:15px;" target="_blank" href="">
				<span class="dashicons dashicons-external" style="line-height: 28px;"></span> <?php _e('Live preview','cookie-law-info');?>
			</a>
			<button type="button" name="" class="button-secondary cli_theme_popup_cancel" style="float: right;  margin-right:15px;">
				<span class="dashicons dashicons-no-alt" style="line-height: 28px;"></span> 
				<?php _e('Cancel','cookie-law-info');?>
			</button>	
		</div>

	</div>
</div>