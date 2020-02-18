<?php 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
echo '<div class="wt-cli-cookie-bar-container">';
echo $notify_html;
$pop_content_html_file=plugin_dir_path(CLI_PLUGIN_FILENAME).'public/views/cookie-law-info_popup_content.php';
if(file_exists($pop_content_html_file))
{
    include $pop_content_html_file;
} 
?>
<?php if($the_options['cookie_setting_popup'] == true ) { ?>
<div class="cli-modal" id="cliSettingsPopup" tabindex="-1" role="dialog" aria-labelledby="cliSettingsPopup" aria-hidden="true">
  <div class="cli-modal-dialog" role="document">
    <div class="cli-modal-content cli-bar-popup">
      <button type="button" class="cli-modal-close" id="cliModalClose">
      <svg class="" viewBox="0 0 24 24"><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"></path><path d="M0 0h24v24h-24z" fill="none"></path></svg>
      <span class="wt-cli-sr-only">Close</span>
      </button>
      <div class="cli-modal-body">
        <?php 
          echo $pop_out;
        ?>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="cli-modal-backdrop cli-fade cli-settings-overlay"></div>
<div class="cli-modal-backdrop cli-fade cli-popupbar-overlay"></div>
</div>
<script type="text/javascript">
  /* <![CDATA[ */
    cli_cookiebar_settings='<?php echo Cookie_Law_Info::get_json_settings(); ?>';
  /* ]]> */
</script>