<?php
$product_name = CLI_ACTIVATION_ID; // name should match with 'Software Title' configured in server, and it should not contains white space
$product_version = CLI_VERSION;
$product_slug = CLI_PLUGIN_BASENAME; //product base_path/file_name
$serve_url = 'https://www.webtoffee.com/';
$plugin_settings_url = admin_url( 'edit.php?post_type='.CLI_POST_TYPE.'&page=cookie-law-info' );
//include api manager
include_once ( 'wf_api_manager.php' );
new WF_API_Manager($product_name, $product_version, $product_slug, $serve_url, $plugin_settings_url);
