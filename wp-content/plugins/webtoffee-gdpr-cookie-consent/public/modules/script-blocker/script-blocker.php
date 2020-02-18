<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
//`Coming Soon Page & Maintenance Mode by SeedProd` is active then disable script blocker
if(class_exists('SEED_CSP4'))
{
    $seed_csp4_option=get_option('seed_csp4_settings_content');
    if($seed_csp4_option && $seed_csp4_option['status']>0)
    {
        return;
    }
}

class Cookie_Law_Info_Script_Blocker {

    public $version;

    public $parent_obj; //instance of the class that includes this class

    public $plugin_obj;
	
    public function __construct($parent_obj)
	{
		$this->version=$parent_obj->version;
        $this->parent_obj=$parent_obj;
        $this->plugin_obj=$parent_obj->plugin_obj;

        /* creating necessary table for script blocker  */
        register_activation_hook(CLI_PLUGIN_FILENAME,array(__CLASS__,'activator'));

        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
        add_action('wp_ajax_cli_change_script_category',array($this,'cli_change_script_category'));
        add_action('wp_ajax_cli_toggle_script_enabled',array($this,'toggle_cliscript_enabled'));

        //=====Plugin settings page Hooks=====
        if(self::get_buffer_type()==2) //buffer type 2 means old type buffer
        {
            add_action('cli_module_settings_advanced',array($this,'settings_advanced'));
            add_action('cli_module_save_settings',array( $this,'save_settings'));
        }
        
        add_action('cli_module_settings_debug',array($this,'settings_debug'));
        add_action('cli_module_save_debug_settings',array( $this,'save_debug_settings'));
        $this->frontend_module();
	}


    /**
     *  =====Plugin settings page Hook=====
     * save debug settings hook
     **/
    public function save_debug_settings()
    {
        if(isset($_POST['cli_sb_change_buffer_type_btn']))
        {
            $allowed_options=array(1,2);
            if(in_array($_POST['cli_sb_buffer_type'],$allowed_options))
            {
                $buffer_option=$_POST['cli_sb_buffer_type'];
            }else
            {
                $buffer_option=1;
            }
            update_option('cli_sb_buffer_type',$buffer_option);
            wp_redirect($_SERVER['REQUEST_URI']); exit();
        }
    }

    /**
     *  =====Plugin settings page Hook=====
     * Insert content to debug tab
     **/
    public function settings_debug()
    {
        $buffer_type=self::get_buffer_type();
        ?>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Buffer type</th>
                    <td>
                        <input type="radio" name="cli_sb_buffer_type" value="2" <?php echo $buffer_type==2 ? 'checked' :'' ?>> Old
                        <input type="radio" name="cli_sb_buffer_type" value="1" <?php echo $buffer_type==1 ? 'checked' :'' ?>> New
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td>
                        <input type="submit" name="cli_sb_change_buffer_type_btn" value="Save" class="button-primary">
                    </td>
                </tr>
            </table>   
        </form>
        <?php
    }


    /**
     *  =====Plugin settings page Hook=====
     * save settings hook
     **/
    public function save_settings()
    {
        if(isset($_POST['cli_sb_buffer_option']))
        {
            $allowed_options=array(1,2);
            if(in_array($_POST['cli_sb_buffer_option'],$allowed_options))
            {
                $buffer_option=$_POST['cli_sb_buffer_option'];
            }else
            {
                $buffer_option=1;
            }
            update_option('cli_sb_buffer_option',$buffer_option);
        }
    }
    /**
     *  =====Plugin settings page Hook=====
     *  Insert content to advanced tab
     **/
    public function settings_advanced()
    {
        $buffer_option=get_option('cli_sb_buffer_option');
        if(!$buffer_option)
        {
            $buffer_option=1;
        }
        ?>
        <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Output buffer type', 'cookie-law-info'); ?></th>
            <td>
                <input type="radio" id="cli_sb_buffer_type_multi" name="cli_sb_buffer_option" class="styled" value="1" <?php echo ( $buffer_option ==1 ) ? ' checked="checked"' : ''; ?> /><?php _e('Multi', 'cookie-law-info'); ?>
                <input type="radio" id="cli_sb_buffer_type_single" name="cli_sb_buffer_option" class="styled" value="2" <?php echo ( $buffer_option ==2 ) ? ' checked="checked" ' : ''; ?> /><?php _e('Single', 'cookie-law-info'); ?>
                <span class="cli_form_help"><?php _e('Caution: This may break the site.', 'cookie-law-info'); ?></span>
            </td>
            </tr>
        </table>
        <?php
    }



    public static function decideBuffer()
    {
        $buffer_option=1; //multi level
        $level = @ob_get_level();
        if(version_compare(PHP_VERSION,'5.3.0')<0)
        {   
            $buffer_option=1;
        }else
        {
            if($level>1)
            {
                $buffer_option=1;
            }else
            {
                $buffer_option=2;
            } 
        }
        return $buffer_option;
    }

    public static function get_buffer_type()
    {
        if(!get_option('cli_sb_buffer_type'))
        {
           update_option('cli_sb_buffer_type',1);
           return 1;
        }else
        {
            return get_option('cli_sb_buffer_type');
        }
    }
    public static function activator()
    {
        global $wpdb;
        //setting buffer option
        $buffer_option=self::decideBuffer();
        if(!get_option('cli_sb_buffer_option'))
        {
           update_option('cli_sb_buffer_option',$buffer_option);
        }
        //setting buffer option

        //setting buffer type
        if(!get_option('cli_sb_buffer_type'))
        {
           update_option('cli_sb_buffer_type',1);
        }
        //setting buffer type

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');       
        if(is_multisite()) 
        {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach($blog_ids as $blog_id ) 
            {
                switch_to_blog( $blog_id );
                self::install_tables();
                restore_current_blog();
            }
        }
        else 
        {
            self::install_tables();
        }
    }
    public static function install_tables()
    {
        global $wpdb;
        self::create_taxonomy(); //we need non necessary taxonomy id for script blocker data
        //creating table for script blocker================
        $search_query = "SHOW TABLES LIKE %s";
        $charset_collate = $wpdb->get_charset_collate();
        $like = '%' . $wpdb->prefix . 'cli_scripts%';
        $table_name = $wpdb->prefix . 'cli_scripts';
        if(!$wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N)) 
        {

            $sql_settings = "CREATE TABLE $table_name(
                `id` INT NOT NULL AUTO_INCREMENT,
                `cliscript_title` TEXT NOT NULL,
                `cliscript_category` VARCHAR(100) NOT NULL,
                `cliscript_status` VARCHAR(100) NOT NULL,
                `cliscript_description` LONGTEXT NOT NULL,
                `cliscript_key` VARCHAR(100) NOT NULL,
                PRIMARY KEY(`id`)
            );";
            dbDelta($sql_settings);
        }
        //creating table for script blocker================


        //inserting data for script blocker=================
        //$version = '2.1.1';
        //$db_version = get_option('cli_script_version');
        if($wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N)) //check table exists, in some cases it will not created
        {
            $total_data=$wpdb->get_row("SELECT COUNT(id) AS ttnum FROM `$table_name`",ARRAY_A);
            $nonnecessary_category_id=self::get_nonnecessary_category_id();
            if($total_data['ttnum']==0)
            {
                $data = array(
                    0 => array(
                        'cliscript_key' => 'googleanalytics',
                        'cliscript_title' => 'Google Analytics',
                        'cliscript_category' => $nonnecessary_category_id,
                        'cliscript_status' => 'yes',
                        'cliscript_description' => 'Google Analytic Scripts'
                    ),
                    1 => array(
                        'cliscript_key' => 'facebook_pixel',
                        'cliscript_title' => 'Facebook Pixel',
                        'cliscript_category' => $nonnecessary_category_id,
                        'cliscript_status' => 'yes',
                        'cliscript_description' => 'Facebook Pixel Scripts'
                    ),
                    2 => array(
                        'cliscript_key' => 'google_tag_manager',
                        'cliscript_title' => 'Google Tag Manager',
                        'cliscript_category' => $nonnecessary_category_id,
                        'cliscript_status' => 'yes',
                        'cliscript_description' => 'Google Tag Manager Scripts'
                    ),
                );
                foreach ($data as $key => $value) {
                    $result = $wpdb->insert($table_name, $value);
                }
                //update_option('cli_script_version',$version);
            }

            //new blocking scripts added 
            self::new_scripts($table_name,$nonnecessary_category_id);

        }
        //inserting data for script blocker=================
    }

    /*
    * new blocking scripts added after first setup is inserted via below function.
    * @since    2.1.6
    */
    public static function new_scripts($table_name,$nonnecessary_category_id)
    {
        global $wpdb;

        /**
        *
        * @since 2.1.6
        */
        $data=array(
            array(
                'cliscript_key' => 'hotjar',
                'cliscript_title' => 'Hotjar Analytics',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Hotjar Analytic Scripts'
            ),
            array(
                'cliscript_key' => 'google_publisher_tag',
                'cliscript_title' => 'Google Publisher Tag',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Google Publisher Tag (Google Ad Manager)'
            ),
            array(
                'cliscript_key' => 'youtube_embed',
                'cliscript_title' => 'Youtube embed',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Youtube player embed'
            ),
            array(
                'cliscript_key' => 'vimeo_embed',
                'cliscript_title' => 'Vimeo embed',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Vimeo player embed'
            ),
            array(
                'cliscript_key' => 'google_maps',
                'cliscript_title' => 'Google maps',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Google maps embed'
            ),
            array(
                'cliscript_key' => 'addthis_widget',
                'cliscript_title' => 'Addthis widget',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Addthis social widget'
            ),
            array(
                'cliscript_key' => 'sharethis_widget',
                'cliscript_title' => 'Sharethis widget',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Sharethis social widget'
            ),
            array(
                'cliscript_key' => 'twitter_widget',
                'cliscript_title' => 'Twitter widget',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Twitter social widget'
            ),
            array(
                'cliscript_key' => 'soundcloud_embed',
                'cliscript_title' => 'Soundcloud embed',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Soundcloud player embed'
            ),
            array(
                'cliscript_key' => 'slideshare_embed',
                'cliscript_title' => 'Slideshare embed',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Slideshare embed'
            ),
            array(
                'cliscript_key' => 'linkedin_widget',
                'cliscript_title' => 'Linkedin widget',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Linkedin social widget'
            ),
            array(
                'cliscript_key' => 'instagram_embed',
                'cliscript_title' => 'Instagram embed',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Instagram embed'
            ),
            /**
            *
            * @since 2.1.8
            */
            array(
                'cliscript_key' => 'pinterest',
                'cliscript_title' => 'Pinterest widget',
                'cliscript_category' => $nonnecessary_category_id,
                'cliscript_status' => 'yes',
                'cliscript_description' => 'Pinterest widget'
            )
        );
        foreach($data as $key => $value) 
        {
            $data_exists=$wpdb->get_row("SELECT id FROM `$table_name` WHERE `cliscript_key`='".$value['cliscript_key']."'",ARRAY_A);
            if(!$data_exists)
            {
               $wpdb->insert($table_name, $value);
            }
        }
    }

    /*
    * below function only use at the time of activation
    */
    public static function create_taxonomy() 
    {
        register_taxonomy(
            'cookielawinfo-category',
            'cookielawinfo',
            array(
                'label' => __( 'Cookie Category','cookie-law-info' ),
                'rewrite' => array( 'slug' => 'cookielawinfo-category' ),
                'hierarchical' => false,
            )
        );

        global $sitepress;
        $wpml_default_lang='en';
        $wpml_current_lang='en';
        if(function_exists('icl_object_id') && $sitepress) //wpml enabled
        {
            $wpml_default_lang=$sitepress->get_default_language();
            $wpml_current_lang=ICL_LANGUAGE_CODE;
        }
        if($wpml_default_lang==$wpml_current_lang)  //check only in default language
        {       
            $term = get_term_by('slug', 'necessary', 'cookielawinfo-category');    
            if (@!$term->term_id) {
                wp_insert_term(
                        'Necessary',
                        'cookielawinfo-category',
                        array(
                            'description' => 'Necessary cookies are absolutely essential for the website to function properly. This category only includes cookies that ensures basic functionalities and security features of the website. These cookies do not store any personal information.',
                            'slug' => 'necessary'
                        )
                );
            }    
            $term = get_term_by('slug', 'non-necessary', 'cookielawinfo-category');
        
            if(@!$term->term_id) 
            {
                wp_insert_term(
                        'Non Necessary',
                        'cookielawinfo-category',
                        array(
                            'description' => 'Any cookies that may not be particularly necessary for the website to function and is used specifically to collect user personal data via analytics, ads, other embedded contents are termed as non-necessary cookies. It is mandatory to procure user consent prior to running these cookies on your website.',
                            'slug' => 'non-necessary'
                        )
                );
            }
        }       
    
    }



    public function frontend_module()
    {
        include( plugin_dir_path( __FILE__ ).'classes/class-script-blocker.php' );
    }


    /* 
    * 
    * enable/disable item on list page (ajax) 
    */	
    public function toggle_cliscript_enabled() 
    {
        if (current_user_can('manage_options') && check_ajax_referer('cli-toggle-script-enabled', 'security')) {

            // Get posted script id.
            $cliscript_id = $this->cli_clean(wp_unslash($_POST['cliscript_id']));

            $script_details = self::cli_script_get_data($cliscript_id);
            if ($script_details) {

                $enabled = $script_details[0]->cliscript_status;

                if (!self::cli_string_to_bool($enabled)) {

                    self::cli_script_update_status($cliscript_id, 'yes');
                    
                } else {

                    self::cli_script_update_status($cliscript_id, 'no');
                }

                wp_send_json_success(!self::cli_string_to_bool($enabled));
                wp_die();
            }
        }

        wp_send_json_error('invalid_script_id');
        wp_die();
    }

    /* change category of item on list page (ajax) */
    public function cli_change_script_category(){
        
        if (current_user_can('manage_options') && check_ajax_referer('cli-change-script-category', 'security')) 
        {
            // Get posted script id.
            $cliscript_id = $this->cli_clean(wp_unslash($_POST['cliscript_id']));
            $clicat_id = $this->cli_clean(wp_unslash($_POST['category']));

            $script_details = self::cli_script_update_category($cliscript_id, $clicat_id);

            wp_send_json_success();
            wp_die();
            wp_send_json_error('invalid_script_id');
            wp_die();
        }
        
    }

    public function cli_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'cli_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
    public static function cli_string_to_bool( $string ) {
        return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
    }

    /**
	 * Add administration menus
	 *
	 * @since 2.1.3
	 **/
	public function add_admin_pages() 
	{
        add_submenu_page( 
	    	'edit.php?post_type='.CLI_POST_TYPE, 
	    	__( 'Script Blocker', 'cookie-law-info' ), 
	    	__( 'Script Blocker', 'cookie-law-info' ), 
	    	'manage_options', 
	    	'cli-script-settings',  
	    	array($this,'admin_script_blocker_page')
	    );
	}

	/*
	* Script Blocker settings
	*/
	public function admin_script_blocker_page()
	{
		wp_enqueue_script( 'cookielawinfo_script_blocker_js',plugin_dir_url( __FILE__ ).'assets/js/script-blocker.js',array(),$this->version );
        
        $params = array(
            'nonces' => array(
                'cli_toggle_script' => wp_create_nonce('cli-toggle-script-enabled'),
                'cli_change_script_category' => wp_create_nonce('cli-change-script-category'),
            ),
            'ajax_url'=>admin_url( 'admin-ajax.php' )
        );
        wp_localize_script( 'cookielawinfo_script_blocker_js', 'cli_script_admin', $params );
        include( plugin_dir_path( __FILE__ ).'views/admin_script_blocker.php' );
	}

	public static function cli_script_table_data() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'cli_scripts';

        $data = $wpdb->get_results("SELECT * FROM $table_name");

        return $data;
    }

    public static function get_cookieid_by_cookieslug($slug) {

        $id_obj = get_term_by('slug', $slug, 'cookielawinfo-category');
        $id = $id_obj->term_id;
        return $id;
    }
    
    public static function get_nonnecessary_category_id() {

        $id_obj = get_term_by('slug', 'non-necessary', 'cookielawinfo-category');
        $id = 3; // for non-necessary default - this may change
        if($id_obj){
        $id = $id_obj->term_id;
        }
        return $id;
    }

    public static function get_cookie_scriptkey_by_category_id($category_id) {

        global $wpdb;

        $script_keys = array();

        $table_name = $wpdb->prefix . 'cli_scripts';
        $data = $wpdb->get_results("SELECT cliscript_key FROM $table_name WHERE cliscript_status = 'yes' AND cliscript_category=" . $category_id);

        if (!empty($data)) {
            foreach ($data as $value) {
                $script_keys[] = $value->cliscript_key;
            }
        }

        return array_values($script_keys);
    }
    
    /*
    * All scripts from DB
    */
    public static function get_blocker_script_list() 
    {
        global $wpdb;
        if(isset($wpdb) && $wpdb!=null)
        {
            $table_name = $wpdb->prefix . 'cli_scripts';
            $term_table_name = $wpdb->prefix . 'terms';
            $termmeta_table_name = $wpdb->prefix.'termmeta';
            $data=null;
            $search_query = "SHOW TABLES LIKE %s";
            $like = '%' . $wpdb->prefix . 'cli_scripts%';
            if($wpdb->get_results($wpdb->prepare($search_query, $like), ARRAY_N)) 
            {
                $data = $wpdb->get_results("SELECT a.cliscript_status,a.cliscript_key,b.slug AS category_slug,c.meta_value AS loadonstart FROM `$table_name` a LEFT JOIN $term_table_name b ON(a.cliscript_category=b.term_id) LEFT JOIN $termmeta_table_name c ON(b.term_id=c.term_id AND c.meta_key='CLIloadonstart')");
                //$data = $wpdb->get_results("SELECT a.cliscript_status,a.cliscript_key,b.slug AS category_slug FROM `$table_name` a LEFT JOIN $term_table_name b ON(a.cliscript_category=b.term_id) LEFT JOIN $termmeta_table_name c ON(b.term_id=c.term_id AND c.meta_key='CLIloadonstart') WHERE b.slug IS NULL OR (b.slug IS NOT NULL AND c.meta_value!=1)");
            }
            return $data;
        }else
        {
            return null;
        } 
        
    }

    /*
    * get disabled script keys
    */
    public static function get_disabled_blocker_scriptkeys() {

        global $wpdb;

        $script_keys = array();

        $table_name = $wpdb->prefix . 'cli_scripts';
        $data = $wpdb->get_results("SELECT cliscript_key FROM $table_name WHERE cliscript_status = 'no'");

        if (!empty($data)) {
            foreach ($data as $value) {
                $script_keys[] = $value->cliscript_key;
            }
        }

        return array_values($script_keys);
    }

    public static function cli_insert_log_event($args = array()) {


        ini_set('max_execution_time', 300);

        global $wpdb;
        $table = $wpdb->prefix . 'cli_scripts';

        $data = array(
            'visitor_ip' => cli_get_client_ip(),
            'visitor_date' => gmdate("M d, Y h:i:s A"),
            'visitor_cookie' => maybe_serialize($args),
        );

        $result = $wpdb->insert($table, $data);
        $track_id = (int) $wpdb->insert_id;

        if (!$result) {
            return false;
        }
        return $track_id;
    }

    public static function cli_script_get_data($id = 0) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'cli_scripts';

        $data = $wpdb->get_results("SELECT * FROM $table_name WHERE id=" . $id);


        return $data;
    }

    public static function cli_script_update_status($id = 0, $status = 'yes') {

        global $wpdb;

        $table_name = $wpdb->prefix . 'cli_scripts';

        $wpdb->query($wpdb->prepare("UPDATE $table_name SET cliscript_status = %s WHERE id = %s", $status, $id));
    }

    //$cat = 3 // default non-necessary
    public static function cli_script_update_category($id = 0, $cat = 3) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'cli_scripts';

        $wpdb->query($wpdb->prepare("UPDATE $table_name SET cliscript_category = %s WHERE id = %s", $cat, $id));
    }
}
new Cookie_Law_Info_Script_Blocker($this);