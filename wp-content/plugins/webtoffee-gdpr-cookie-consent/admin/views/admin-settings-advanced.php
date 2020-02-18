<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="cookie-law-info-tab-content" data-id="<?php echo $target_id;?>">
    <h3><?php _e('Advanced', 'cookie-law-info'); ?></h3>
    <p><?php _e('Sometimes themes apply settings that clash with plugins. If that happens, try adjusting these settings.', 'cookie-law-info'); ?></p>

    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Renew user consent', 'cookie-law-info'); ?></th>
            <td>
                <input type="submit" name="cli_renew_consent" value="<?php _e('Renew consent', 'cookie-law-info'); ?>" onclick="cli_store_settings_btn_click(this.name);" class="button-secondary" />
                <span class="cli_form_help"><?php _e('Clicking this will renew the existing user consents on your website. The existing users will be prompted with the consent banner irrespective of their previous consent.', 'cookie-law-info'); ?></span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Reset all values', 'cookie-law-info'); ?></th>
            <td>
                <input type="submit" name="delete_all_settings" value="<?php _e('Delete settings and reset', 'cookie-law-info'); ?>" class="button-secondary" onclick="cli_store_settings_btn_click(this.name); if(confirm('<?php _e('Are you sure you want to delete all your settings?', 'cookie-law-info'); ?>')){  }else{ return false;};" />
                <span class="cli_form_help"><?php _e('Warning: this will actually delete your current settings.', 'cookie-law-info'); ?></span>
            </td>
        </tr>
    </table>
    <?php 
        //advanced settings form fields for module
        do_action('cli_module_settings_advanced');
    ?>
    
    <?php
    global $wp_filter;
    //only if anybody hooked to the above action 
    if(isset($wp_filter['cli_module_settings_advanced']))
    {
        include "admin-settings-save-button.php";
    }
    ?>
</div>