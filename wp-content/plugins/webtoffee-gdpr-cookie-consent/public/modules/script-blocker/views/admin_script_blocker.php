<?php
if (!defined('ABSPATH')) {
    exit;
}
$cli_img_path=CLI_PLUGIN_URL . 'images/';
$cli_sb_status=get_option('cli_script_blocker_status');
$cli_icon = ($cli_sb_status === "enabled"  ? '<span class="dashicons dashicons-yes cli-enabled ">' : '<span class="dashicons dashicons-no-alt cli-disabled"></span>');
$action_text = ($cli_sb_status === "enabled"  ? __('disable','cookie-law-info') : __('enable','cookie-law-info'));
$action_value = ($cli_sb_status === "enabled"  ? 'disabled' : 'enabled');
$cli_sb_status_text = sprintf( __('Script blocker is currently %s','cookie-law-info'),$cli_sb_status);
$cli_notice_text = sprintf( __('<a href="javascript: submitform()">click here</a> to %s ','cookie-law-info'), $action_text );
?>
<div class=" cliscript-container">
    <h2><?php _e( 'Manage Script Blocking' , 'cookie-law-info'); ?></h2>
    <div class="notice-info notice"><p><label><?php echo $cli_icon;?></label>
        <?php echo $cli_sb_status_text; ?> <?php echo $cli_notice_text;?></p>
    </div>
    <form method="post" name="script_blocker_form" >
        <?php 
            if (function_exists('wp_nonce_field'))
            {
                wp_nonce_field('cookielawinfo-update-' . CLI_SETTINGS_FIELD);
            }                    
        ?>
        <input type="hidden" id="cli_script_blocker_state" name="cli_script_blocker_state" class="styled" value="<?php echo $action_value;?>" />
        <input type="hidden" id="cli_update_script_blocker" name="cli_update_script_blocker" />
    </form>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <td class="cli_script_wrapper" colspan="2">
                    <table class="cli_script_items widefat" cellspacing="0">
                        <thead>
                            <tr>
                                <?php
                                $cats = array('necessary', 'non-necessary');
                                $default_columns = array(
                                    'cliscript_id' => __('No.', 'woocommerce'),
                                    'cliscript_title' => __('Name', 'woocommerce'),
                                    'cliscript_status' => __('Enabled', 'woocommerce'),
                                    'cliscript_description' => __('Description', 'woocommerce'),
                                    'cliscript_category' => __('Category', 'woocommerce'),
                                    'cliscript_key' => __('Key', 'woocommerce'),
                                );
                                foreach ($default_columns as $key => $column) {
                                    echo '<th class="' . esc_attr($key) . '">' . esc_html($column) . '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $args = array(
                                'taxonomy' => 'cookielawinfo-category',
                                'hide_empty' => false,
                            );

                            $terms = get_terms($args);
                            $script_data = self::cli_script_table_data();
                            // Use Defined Scripts Through Filter "cli_extend_script_blocker"
                            $scripts='';
                            $cli_user_scripts = apply_filters('cli_extend_script_blocker',$scripts);
                            if($cli_user_scripts && is_array($cli_user_scripts))
                            {   
                                $cli_start_id = count($script_data);
                                foreach($cli_user_scripts as $k => $v)
                                {   
                                    $script_term = $category = get_term_by('slug', $v['category'], 'cookielawinfo-category');
                                    $cli_scripts = new stdClass();
                                    $cli_scripts->id = $cli_start_id;
                                    $cli_scripts->cliscript_title = $cli_scripts->cliscript_description = $v['label'];
                                    $cli_scripts->cliscript_category = $script_term->term_id;
                                    $cli_scripts->cliscript_status = $v['status'];
                                    $cli_scripts->cliscript_key = $v['id'];
                                    $cli_scripts->cliscript_custom = true;
                                    array_push($script_data, $cli_scripts);
                                    $cli_start_id++;
                                }
                            }
                            $sn=0;
                            foreach ($script_data as $data) {

                                $sn++;
                                echo '<tr data-script_id="' . esc_attr($data->id) . '">';
                                foreach ($default_columns as $key => $column) {

                                    $width = '';
                                    if (in_array($key, array('cliscript_id', 'cliscript_status',), true)) {
                                        $width = '1%';
                                    }

                                    echo '<td class="' . esc_attr($key) . '" width="' . esc_attr($width) . '">';

                                    switch ($key) {
                                        case 'cliscript_id':
                                            echo '<input type="hidden" name="cliscript_id[]" value="' . esc_attr($data->id) . '" />'.$sn;
                                            break;
                                        case 'cliscript_title':
                                            echo $data->cliscript_title;
                                            break;
                                        case 'cliscript_description':
                                            echo $data->cliscript_description;
                                            break;
                                        case 'cliscript_category':
                                            echo '<select name="cliscript_category" id="cliscript_category">';
                                            echo '<option value="0">--Select Category--</option>';
                                            foreach ($terms as $key => $term) {
                                                echo '<option value="'.$term->term_id.'"'. selected($data->cliscript_category, $term->term_id).'>'.$term->name.'</option>';   
                                            }
                                            echo '</select>';
                                            
                                            break;
                                        case 'cliscript_status':
                                            // Disable Toggle Option For Custom Scripts
                                            $cl_toggle_class = 'cli-script-items-toggle-enabled';
                                            if($cli_user_scripts && is_array($cli_user_scripts))
                                            {
                                            
                                                if(isset($data->cliscript_custom))
                                                {
                                                    $cl_toggle_class = 'cli-script-items-toggle-disabled';
                                                }
                                            }
                                            
                                            echo '<a class='.$cl_toggle_class.' href="#">';
                                            if (self::cli_string_to_bool($data->cliscript_status)) {
                                                echo '<span class="cli-input-toggle cli-input-toggle--enabled">' . esc_attr__('Yes', 'woocommerce') . '</span>';
                                            } else {
                                                echo '<span class="cli-input-toggle cli-input-toggle--disabled">' . esc_attr__('No', 'woocommerce') . '</span>';
                                            }
                                            echo '</a>';
                                            break;
                                        case 'cliscript_key':
                                           
                                            echo $data->cliscript_key;
                                            break;
                                    }

                                    echo '</td>';
                                }

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
     function submitform() {   document.script_blocker_form.submit(); } 
</script>
<?php
