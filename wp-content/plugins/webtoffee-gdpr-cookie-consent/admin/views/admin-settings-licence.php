<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo $target_id;?>">
	<?php
	$plugin_name = 'wtgdprcookieconsent';   
	require_once(plugin_dir_path( dirname( __FILE__ ) ).'wf_api_manager/html/html-wf-activation-window.php' );
	?>
</div>