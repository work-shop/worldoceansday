<?php

/**
 * Fired during plugin activation
 *
 * @link       http://cookielawinfo.com/
 * @since      2.1.3
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.1.3
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/includes
 * @author     WebToffee <info@webtoffee.com>
 */
class Cookie_Law_Info_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.1.3
	 */
	public static function activate() 
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );       
        if(is_multisite()) 
        {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach($blog_ids as $blog_id ) 
            {
                switch_to_blog( $blog_id );
                self::install_tables();
                Cookie_Law_Info::cli_patches();
                restore_current_blog();
            }
        }
        else 
        {
            self::install_tables();
            Cookie_Law_Info::cli_patches();
            Cookie_Law_Info::wt_cli_init_consent_version();
        }
	}
	public static function install_tables()
	{
		global $wpdb;
		//install necessary tables
	}

}
