<?php
/*
Plugin Name: Perfmatters
Plugin URI: https://perfmatters.io/
Description: This plugin adds an assortment of performance and speed improvements to your WordPress installation.
Version: 1.4.7
Author: forgemedia
Author URI: https://forgemedia.io/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: perfmatters
Domain Path: /languages
*/

/*****************************************************************************************
* EDD License
*****************************************************************************************/
define('PERFMATTERS_STORE_URL', 'https://perfmatters.io/');
define('PERFMATTERS_ITEM_NAME', 'perfmatters');
define('PERFMATTERS_VERSION', '1.4.7');

//load translations
function perfmatters_load_textdomain() {
	load_plugin_textdomain('perfmatters', false, dirname(plugin_basename( __FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'perfmatters_load_textdomain');

//load EDD custom updater class
if(!class_exists('EDD_SL_Plugin_Updater')) {
	include(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

//EDD updater function
function perfmatters_edd_plugin_updater() {

	//retrieve our license key from the DB
	if(is_network_admin()) {
		$license_key = trim(get_site_option('perfmatters_edd_license_key'));
	}
	else {
		$license_key = trim(get_option('perfmatters_edd_license_key'));
	}
	
	//setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(PERFMATTERS_STORE_URL, __FILE__, array(
			'version' 	=> PERFMATTERS_VERSION,
			'license' 	=> $license_key,
			'item_name' => PERFMATTERS_ITEM_NAME,
			'author' 	=> 'forgemedia'
		)
	);
}
add_action('admin_init', 'perfmatters_edd_plugin_updater', 0);

//add our admin menus
if(is_admin()) {
	add_action('admin_menu', 'perfmatters_menu', 9);
}

global $perfmatters_settings_page;

//admin menu
function perfmatters_menu() {
	if(perfmatters_network_access()) {
		
		global $perfmatters_settings_page;
		$perfmatters_settings_page = add_options_page('perfmatters', 'Perfmatters', 'manage_options', 'perfmatters', 'perfmatters_admin');
		add_action('load-' . $perfmatters_settings_page, 'perfmatters_settings_load');
	}
}

//admin settings page
function perfmatters_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//admin settings page load hook
function perfmatters_settings_load() {
	add_action('admin_enqueue_scripts', 'perfmatters_admin_scripts');
}

//plugin admin scripts
function perfmatters_admin_scripts() {
	if(perfmatters_network_access()) {
		wp_register_style('perfmatters-styles', plugins_url('/css/style.css', __FILE__), array(), PERFMATTERS_VERSION);
		wp_enqueue_style('perfmatters-styles');

		wp_register_script('perfmatters-js', plugins_url('/js/perfmatters.js', __FILE__), array(), PERFMATTERS_VERSION);
		wp_enqueue_script('perfmatters-js');
	}
}

//check multisite and verify access
function perfmatters_network_access() {
	if(is_multisite()) {
		$perfmatters_network = get_site_option('perfmatters_network');
		if((!empty($perfmatters_network['access']) && $perfmatters_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}

//license messages in plugins table
function perfmatters_meta_links($links, $file) {
	if(strpos($file, 'perfmatters.php' ) !== false) {

		if(is_network_admin()) {
			$license_info = perfmatters_edd_check_network_license();
			$settings_url = network_admin_url('settings.php?page=perfmatters');
		}
		else {
			$license_info = perfmatters_edd_check_license();
			$settings_url = admin_url('options-general.php?page=perfmatters');
		}

		$perfmatters_links = array();

		//support link
		$perfmatters_links[] = '<a href="https://perfmatters.io/docs/" target="_blank">' . __('Support', 'perfmatters') . '</a>';

		//activation link
		if(!is_plugin_active_for_network('perfmatters/perfmatters.php') || is_network_admin()) {

			if(!empty($license_info->license) && $license_info->license == "valid") {
				$perfmatters_links[] = '<a href="' . $settings_url . '&tab=license" style="color: green;">' . __('License is Activated', 'perfmatters') . '</a>';
			}
			elseif(!empty($license_info->license) && $license_info->license == "expired") {
				$perfmatters_links[] = '<a href="' . $settings_url . '&tab=license" style="color: orange;">' . __('Renew License', 'perfmatters') . '</a>';
			}
			else {
				$perfmatters_links[] = '<a href="' . $settings_url . '&tab=license" style="color: red;">' . __('Activate License', 'perfmatters') . '</a>';
			}

		}

		$links = array_merge($links, $perfmatters_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'perfmatters_meta_links', 10, 2);

//settings link in plugins table
function perfmatters_action_links($actions, $plugin_file) 
{
	if(plugin_basename(__FILE__) == $plugin_file) {

		if(is_network_admin()) {
			$settings_url = network_admin_url('settings.php?page=perfmatters');
		}
		else {
			$settings_url = admin_url('options-general.php?page=perfmatters');
		}

		$settings_link = array('settings' => '<a href="' . $settings_url . '">' . __('Settings', 'perfmatters') . '</a>');
		$actions = array_merge($settings_link, $actions);
	}
	return $actions;
}
add_filter('plugin_action_links', 'perfmatters_action_links', 10, 5);

//Optimization Guide Notice
function perfmatters_guide_notice() {
    if(get_current_screen()->base == 'settings_page_perfmatters') {
        echo "<div class='notice notice-info'>";
        	echo "<p>";
        		_e("Check out our <a href='https://woorkup.com/speed-up-wordpress/' title='WordPress Optimization Guide' target='_blank'>complete optimization guide</a> for more ways to speed up WordPress.", 'perfmatters');
        	echo "</p>";
        echo "</div>";
    }
}
add_action('admin_notices', 'perfmatters_guide_notice');

function perfmatters_activate() {
	$perfmatters_ga = get_option('perfmatters_ga');

	//enable local analytics scheduled event
	if(!empty($perfmatters_ga['enable_local_ga']) && $perfmatters_ga['enable_local_ga'] == "1") {
		if(!wp_next_scheduled('perfmatters_update_ga')) {
			wp_schedule_event(time(), 'daily', 'perfmatters_update_ga');
		}
	}
}
register_activation_hook(__FILE__, 'perfmatters_activate');

//register a license deactivation
function perfmatters_deactivate() {

	//remove local analytics scheduled event
	if(wp_next_scheduled('perfmatters_update_ga')) {
		wp_clear_scheduled_hook('perfmatters_update_ga');
	}
	
	//retrieve the license from the database
	$license = trim(get_option('perfmatters_edd_license_key'));
	$license_status = get_option('perfmatters_edd_license_status');

	if(!empty($license) && (!empty($license_status) && $license_status == "valid")) {

		//data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode(PERFMATTERS_ITEM_NAME),
			'url'       => home_url()
		);

		//call the custom API
		$response = wp_remote_post(PERFMATTERS_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

		//make sure the response came back okay
		if(is_wp_error($response)) {
			return false;
		}

		//decode the license data
		$license_data = json_decode(wp_remote_retrieve_body($response));

		//$license_data->license will be either "deactivated" or "failed"
		if($license_data->license == 'deactivated') {
			delete_option('perfmatters_edd_license_status');
		}
	}	
}
register_deactivation_hook(__FILE__, 'perfmatters_deactivate');

//uninstall plugin + delete options
function perfmatters_uninstall() {

	//plugin options
	$perfmatters_options = array(
		'perfmatters_options',
		'perfmatters_cdn',
		'perfmatters_ga',
		'perfmatters_extras',
		'perfmatters_script_manager',
		'perfmatters_script_manager_settings',
		'perfmatters_edd_license_key',
		'perfmatters_edd_license_status'
	);

	if(is_multisite()) {
		$perfmatters_network = get_site_option('perfmatters_network');
		if(!empty($perfmatters_network['clean_uninstall']) && $perfmatters_network['clean_uninstall'] == 1) {
			delete_site_option('perfmatters_network');

			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
			if(is_array($sites) && $sites !== array()) {
				foreach($sites as $site) {
					foreach($perfmatters_options as $option) {
						delete_blog_option($site['blog_id'], $option);
					}
				}
			}
		}
	}
	else {
		$perfmatters_extras = get_option('perfmatters_extras');
		if(!empty($perfmatters_extras['clean_uninstall']) && $perfmatters_extras['clean_uninstall'] == 1) {
			foreach($perfmatters_options as $option) {
				delete_option($option);
			}
		}
	}
}
register_uninstall_hook(__FILE__, 'perfmatters_uninstall');

//all plugin file includes
include plugin_dir_path(__FILE__) . '/inc/settings.php';
include plugin_dir_path(__FILE__) . '/inc/functions.php';
include plugin_dir_path(__FILE__) . '/inc/network.php';