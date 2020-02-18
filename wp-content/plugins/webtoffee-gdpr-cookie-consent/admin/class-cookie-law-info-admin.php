<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://cookielawinfo.com/
 * @since      2.1.3
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/admin
 * @author     WebToffee <info@webtoffee.com>
 */
class Cookie_Law_Info_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.1.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.1.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_obj;

	/*
	 * admin module list, Module folder and main file must be same as that of module name
	 * Please check the `admin_modules` method for more details
	 */
	private $modules=array(
		'csv-import', //CSV import
		'csv-export', //CSV export
		'cookie-serve', //scan cookie
		'cookie-scaner',
		'cli-themes',
		'cli-policy-generator'
	);

	public static $existing_modules=array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.1.3
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version,$plugin_obj ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_obj = $plugin_obj;
		register_activation_hook(CLI_PLUGIN_FILENAME,array($this,'activator'));

	}
	public function activator()
	{
		$privacy_settings = get_option('cookielawinfo_privacy_overview_content_settings');
		$privacy_defaults = array(
			'privacy_overview_content' => 'This website uses cookies to improve your experience while you navigate through the website. Out of these cookies, the cookies that are categorized as necessary are stored on your browser as they are essential for the working of basic functionalities of the website. We also use third-party cookies that help us analyze and understand how you use this website. These cookies will be stored in your browser only with your consent. You also have the option to opt-out of these cookies. But opting out of some of these cookies may have an effect on your browsing experience.','privacy_overview_title' => 'Privacy Overview'
		); 
		($privacy_settings===false) ? update_option('cookielawinfo_privacy_overview_content_settings',$privacy_defaults) : false ;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.1.3
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cookie_Law_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cookie_Law_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(isset($_GET['post_type']) && $_GET['post_type']==CLI_POST_TYPE)
		{
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) ."css/cookie-law-info-admin.css", array(),$this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.1.3
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cookie_Law_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cookie_Law_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(isset($_GET['post_type']) && $_GET['post_type']==CLI_POST_TYPE)
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cookie-law-info-admin.js', array( 'jquery' ,'wp-color-picker'),$this->version, false );
		}

	}

	public function redirect_to_settings_page()
	{
		if(!isset($_GET['post_type']) && isset($_GET['page']) && $_GET['page']=='cookie-law-info')
		{
			wp_redirect(admin_url('edit.php?post_type='.CLI_POST_TYPE.'&page=cookie-law-info'));
			exit();
		}
	}

	/**
	 Registers admin modules	 
	 */
	public function admin_modules()
	{
		$cli_admin_modules=get_option('cli_admin_modules');
		if($cli_admin_modules===false)
		{
			$cli_admin_modules=array();
		}
		foreach ($this->modules as $module) //loop through module list and include its file
		{
			$is_active=1;
			if(isset($cli_admin_modules[$module]))
			{
				$is_active=$cli_admin_modules[$module]; //checking module status
			}else
			{
				$cli_admin_modules[$module]=1; //default status is active
			}
			$module_file=plugin_dir_path( __FILE__ )."modules/$module/$module.php";
			if(file_exists($module_file) && $is_active==1)
			{
				self::$existing_modules[]=$module; //this is for module_exits checking
				require_once $module_file;
			}else
			{
				$cli_admin_modules[$module]=0;	
			} 
		}
		$out=array();
		foreach($cli_admin_modules as $k=>$m)
		{
			if(in_array($k,$this->modules))
			{
				$out[$k]=$m;
			}
		}
		update_option('cli_admin_modules',$out);
	}

	public static function module_exists($module)
	{
		return in_array($module,self::$existing_modules);
	}

	/**
	 Registers menu options
	 Hooked into admin_menu
	 */
	public function admin_menu() {
		global $submenu;
		add_submenu_page(
			'edit.php?post_type='.CLI_POST_TYPE,
			__('Cookie Law Settings','cookie-law-info'),
			__('Cookie Law Settings','cookie-law-info'),
			'manage_options',
			'cookie-law-info',
			array($this,'admin_settings_page')
		);
		$hook=add_submenu_page(
			'edit.php?post_type='.CLI_POST_TYPE,
			__('Privacy Overview','cookie-law-info'),
			__('Privacy Overview','cookie-law-info'),
			'manage_options',
			'cookie-law-info-poverview',
			array($this,'privacy_overview_page')
		);
		
		//rearrange settings menu
		if(isset($submenu) && !empty($submenu) && is_array($submenu))
		{
			$out=array();
			$back_up_settings_menu=array();
			if(isset($submenu['edit.php?post_type='.CLI_POST_TYPE]) && is_array($submenu['edit.php?post_type='.CLI_POST_TYPE]))
			{
				foreach ($submenu['edit.php?post_type='.CLI_POST_TYPE] as $key => $value) 
				{
					if($value[2]=='cookie-law-info')
					{
						$back_up_settings_menu=$value;
					}else
					{
						$out[$key]=$value;
					}
				}
				array_unshift($out,$back_up_settings_menu);
				$submenu['edit.php?post_type='.CLI_POST_TYPE]=$out;
			}
		}
	}

	public function plugin_action_links( $links ) 
	{
	   $links[] = '<a href="'. get_admin_url(null,'edit.php?post_type='.CLI_POST_TYPE.'&page=cookie-law-info') .'">'.__('Settings','cookie-law-info').'</a>';
	   $links[] = '<a href="https://www.webtoffee.com/product/gdpr-cookie-consent/" target="_blank">'.__('Support','cookie-law-info').'</a>';
	   return $links;
	}

	/*
	* Privacy overview CMS page
	*/
	public function privacy_overview_page()
	{
		require_once plugin_dir_path( __FILE__ ).'partials/cookie-law-info-privacy_overview.php';
	}

	/*
	* Form action for debug settings tab
	*
	*/
	public function debug_save()
	{
		if(isset($_POST['cli_export_settings_btn']))
		{
			// Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);

			$the_options =Cookie_Law_Info::get_settings();
			header('Content-Type: application/json');
			header('Content-disposition: attachment; filename="cli_settings.json"');
			echo json_encode($the_options);
			exit();
		}
		if(isset($_POST['cli_import_settings_btn']))
		{
			// Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);

			if(!empty($_FILES['cli_import_settings_json']['tmp_name'])) 
			{
				$filename=$_FILES['cli_import_settings_json']['tmp_name'];
				$json_file=@fopen($filename,'r');
				$json_data=fread($json_file,4096);
				$json_data_arr=json_decode($json_data,true);
				$the_options =Cookie_Law_Info::get_settings();
				foreach($the_options as $key => $value) 
		        {
		            if(isset($json_data_arr[$key])) 
		            {
		                // Store sanitised values only:
		                $the_options[$key] = Cookie_Law_Info::sanitise_settings($key,$json_data_arr[$key]);
		            }
		        }
				update_option(CLI_SETTINGS_FIELD, $the_options);
			}
		}
		if(isset($_POST['cli_admin_modules_btn']))
		{
		    // Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);
	        
		    $cli_common_modules=get_option('cli_common_modules');
		    if($cli_common_modules===false)
		    {
		        $cli_common_modules=array();
		    }
		    if(isset($_POST['cli_common_modules']))
		    {
		        $cli_post=$_POST['cli_common_modules'];
		        foreach($cli_common_modules as $k=>$v)
		        {
		            if(isset($cli_post[$k]) && $cli_post[$k]==1)
		            {
		                $cli_common_modules[$k]=1;
		            }else
		            {
		                $cli_common_modules[$k]=0;
		            }
		        }
		    }else
		    {
		    	foreach($cli_common_modules as $k=>$v)
		        {
					$cli_common_modules[$k]=0;
		        }
		    }

		    $cli_admin_modules=get_option('cli_admin_modules');
		    if($cli_admin_modules===false)
		    {
		        $cli_admin_modules=array();
		    }
		    if(isset($_POST['cli_admin_modules']))
		    {
		        $cli_post=$_POST['cli_admin_modules'];
		        foreach($cli_admin_modules as $k=>$v)
		        {
		            if(isset($cli_post[$k]) && $cli_post[$k]==1)
		            {
		                $cli_admin_modules[$k]=1;
		            }else
		            {
		                $cli_admin_modules[$k]=0;
		            }
		        }
		    }else
		    {
		    	foreach($cli_admin_modules as $k=>$v)
		        {
					$cli_admin_modules[$k]=0;
		        }
		    }
		    update_option('cli_admin_modules',$cli_admin_modules);
		    update_option('cli_common_modules',$cli_common_modules);
		    wp_redirect($_SERVER['REQUEST_URI']); exit();
		}
	    do_action('cli_module_save_debug_settings');

	}
	/*
	* @since    2.2.1
	* @access   private
	* Allows user to enable or disable script blocker. 
	*/
	public function cli_user_script_blocker_status()
	{	
		$cli_sb_status=get_option('cli_script_blocker_status');
		if(!$cli_sb_status)
		{	
			update_option('cli_script_blocker_status','enabled');
		}
		if(isset($_POST['cli_update_script_blocker']))
		{
			check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);
			$cli_sb_status=$_POST['cli_script_blocker_state'];
			update_option('cli_script_blocker_status',$cli_sb_status);
		}	
	}
	/*
	* admin settings page
	*/
	public function admin_settings_page()
	{
		// Lock out non-admins:
		if (!current_user_can('manage_options')) 
		{
		    wp_die(__('You do not have sufficient permission to perform this operation', 'cookie-law-info'));
		}
		
    	// Check if form has been set:
	    if(isset($_POST['update_admin_settings_form']) || //normal php submit
	    (isset($_POST['cli_settings_ajax_update']) && $_POST['cli_settings_ajax_update']=='update_admin_settings_form'))  //ajax submit
	    {
	        // Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);

	        //module settings saving hook
			do_action('cli_module_save_settings');

			// Get options:
	        $the_options =Cookie_Law_Info::get_settings();
	        foreach($the_options as $key => $value) 
	        {
	            if(isset($_POST[$key . '_field'])) 
	            {
	                // Store sanitised values only:
	                $the_options[$key] = Cookie_Law_Info::sanitise_settings($key, $_POST[$key . '_field']);
	            }
			}
			if(Cookie_Law_Info::wt_cli_category_widget_exist($the_options['notify_message']))
			{
				$the_options['accept_all'] = false;
			}
	        update_option(CLI_SETTINGS_FIELD, $the_options);
	        echo '<div class="updated"><p><strong>' . __('Settings Updated.', 'cookie-law-info') . '</strong></p></div>';
	        if(!empty($_SERVER[ 'HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
	        {	            
	        	exit();
	        }
	    } 
	    elseif (isset($_POST['delete_all_settings']) || //normal php submit
	    (isset($_POST['cli_settings_ajax_update']) && $_POST['cli_settings_ajax_update']=='delete_all_settings'))  //ajax submit 
	    {
	        // Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);
	        $this->delete_settings();
	        //$the_options = Cookie_Law_Info::get_settings();
	        //exit();
	    }
	    elseif (isset($_POST['revert_to_previous_settings']))  //disabled on new update
	    {
	        if (!$this->copy_old_settings_to_new()) 
	        {
	            echo '<h3>' . __('ERROR MIGRATING SETTINGS (ERROR: 2)', 'cookie-law-info') . '</h3>';
	        }
	        $the_options = Cookie_Law_Info::get_settings();
		}
		elseif (isset($_POST['cli_renew_consent']) || //normal php submit
	    (isset($_POST['cli_settings_ajax_update']) && $_POST['cli_settings_ajax_update']=='cli_renew_consent'))  //ajax submit 
	    {
	        // Check nonce:
	        check_admin_referer('cookielawinfo-update-' . CLI_SETTINGS_FIELD);
	        $this->wt_cli_renew_consent();
	    } 
		// Get options:
		$the_options = Cookie_Law_Info::get_settings();
		require_once plugin_dir_path( __FILE__ ).'partials/cookie-law-info-admin_settings.php';
	}
	/**
	 * The version of this plugin.
	 *
	 * @since    2.2.2
	 * @access   public
	 */
	public function wt_cli_renew_consent()
	{
		$consent_version = Cookie_Law_Info::wt_cli_get_consent_version();
		if(!empty($consent_version))
		{	
			$consent_version = $consent_version;
			$consent_version = $consent_version + 1;
			update_option('wt_cli_consent_version',$consent_version);
		}
	}
	/**
	 Add custom meta boxes to Cookie Audit custom post type.
	 	- Cookie Type (e.g. session, permanent)
	 	- Cookie Duration (e.g. 2 hours, days, years, etc)
	 */
	public function add_meta_box() {
	    
	    add_meta_box("_cli_cookie_slugid", "Cookie ID", array($this,"metabox_cookie_slugid"), "cookielawinfo", "side", "default");
		add_meta_box("_cli_cookie_type", "Cookie Type", array($this,"metabox_cookie_type"), "cookielawinfo", "side", "default");
		add_meta_box("_cli_cookie_duration", "Cookie Duration", array($this,"metabox_cookie_duration"), "cookielawinfo", "side", "default");
	    add_meta_box("_cli_cookie_sensitivity", "Cookie Sensitivity", array($this,"metabox_cookie_sensitivity"), "cookielawinfo", "side", "default");
	        
	    add_meta_box("_cli_cookie_headscript_meta", "Head Scripts", array($this,"metabox_headscript"), "cookielawinfo", "normal", "low");
	    add_meta_box("_cli_cookie_bodyscript_meta", "Body Scripts", array($this,"metabox_bodyscript"), "cookielawinfo", "normal", "low");
	}

	/** Display the custom meta box for cookie_slugid */
	public function metabox_cookie_slugid() 
	{
		global $post;
		$custom = get_post_custom( $post->ID );
		$cookie_slugid = ( isset ( $custom["_cli_cookie_slugid"][0] ) ) ? $custom["_cli_cookie_slugid"][0] : '';
		?>
		<label>Cookie ID:</label>
		<input name="_cli_cookie_slugid" value="<?php echo sanitize_text_field( $cookie_slugid ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_type */
	public function metabox_cookie_type() 
	{
		global $post;
		$custom = get_post_custom( $post->ID );
		$cookie_type = ( isset ( $custom["_cli_cookie_type"][0] ) ) ? $custom["_cli_cookie_type"][0] : '';
		?>
		<label>Cookie Type: (persistent, session, third party )</label>
		<input name="_cli_cookie_type" value="<?php echo sanitize_text_field( $cookie_type ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_duration */
	public function metabox_cookie_duration() {
		global $post;
		$custom = get_post_custom( $post->ID );
		$cookie_duration = ( isset ( $custom["_cli_cookie_duration"][0] ) ) ? $custom["_cli_cookie_duration"][0] : '';
		?>
		<label>Cookie Duration:</label>
		<input name="_cli_cookie_duration" value="<?php echo sanitize_text_field( $cookie_duration ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for cookie_sensitivity */
	public function metabox_cookie_sensitivity() 
	{
		global $post;
		$custom = get_post_custom( $post->ID );
		$cookie_sensitivity = ( isset ( $custom["_cli_cookie_sensitivity"][0] ) ) ? $custom["_cli_cookie_sensitivity"][0] : '';
		?>
		<label>Cookie Sensitivity: ( necessary , non-necessary )</label>
		<input name="_cli_cookie_sensitivity" value="<?php echo sanitize_text_field( $cookie_sensitivity ); ?>" style="width:95%;" />
		<?php
	}

	/** Display the custom meta box for head script */
	public function metabox_headscript()
	{
		global $post;
		$custom = get_post_custom( $post->ID );
		$_cli_cookie_headscript_meta = ( isset ( $custom["_cli_cookie_headscript_meta"][0] ) ) ? $custom["_cli_cookie_headscript_meta"][0] : '';
	 
		?>
		<style>.width99 {width:99%;}</style>
		<p>
			<label>Script: eg:-  &lt;script&gt; enableGoogleAnalytics(); &lt;/script&gt; </label><br />
			<textarea rows="5" name="_cli_cookie_headscript_meta" class="width99"><?php echo $_cli_cookie_headscript_meta; ?></textarea>
		</p>
		<?php
	}

	/** Display the custom meta box for body script */
	public function metabox_bodyscript()
	{
		global $post;
		$custom = get_post_custom( $post->ID );
		$_cli_cookie_bodyscript_meta = ( isset ( $custom["_cli_cookie_bodyscript_meta"][0] ) ) ? $custom["_cli_cookie_bodyscript_meta"][0] : '';
	 
		?>
		<style>.width99 {width:99%;}</style>
		<p>
	            <label>Script: eg:-  &lt;script&gt; enableGoogleAnalytics(); &lt;/script&gt; </label><br />
			<textarea rows="5" name="_cli_cookie_bodyscript_meta" class="width99"><?php echo $_cli_cookie_bodyscript_meta; ?></textarea>
		</p>
		<?php
	}

	/** Saves all form data from custom post meta boxes, including saitisation of input */
	public function save_custom_metaboxes() 
	{
		global $post;	
		if ( isset ( $_POST["_cli_cookie_type"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_type", sanitize_text_field( $_POST["_cli_cookie_type"] ) );
	        }
	        if ( isset ( $_POST["_cli_cookie_type"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_duration", sanitize_text_field( $_POST["_cli_cookie_duration"] ) );
		}
	        if ( isset ( $_POST["_cli_cookie_sensitivity"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_sensitivity", sanitize_text_field( $_POST["_cli_cookie_sensitivity"] ) );
		}
	        if ( isset ( $_POST["_cli_cookie_slugid"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_slugid", sanitize_text_field( $_POST["_cli_cookie_slugid"] ) );
		}
	        if ( isset ( $_POST["_cli_cookie_headscript_meta"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_headscript_meta", wp_unslash( $_POST["_cli_cookie_headscript_meta"] ) );
		}
	        if ( isset ( $_POST["_cli_cookie_bodyscript_meta"] ) ) {
			update_post_meta( $post->ID, "_cli_cookie_bodyscript_meta", wp_unslash( $_POST["_cli_cookie_bodyscript_meta"] ) );
		}
	}

	/** Apply column names to the custom post type table */
	public function manage_edit_columns( $columns ) 
	{
		$columns = array(
			"cb" 			=> "<input type=\"checkbox\" />",
			"title"			=> "Cookie Name",
			"type"			=> "Type",
	        "category"		=> "Category",
			"duration"		=> "Duration",
	        "sensitivity"	=> "Sensitivity",
	        "slugid"		=> "ID",
			"description"   => "Description"
		);
		return $columns;
	}

	/** Add column data to custom post type table columns */
	public function manage_posts_custom_columns( $column, $post_id=0 ) 
	{
		global $post;
		
		switch ( $column ) {
		case "description":
	            
	                $content_post = get_post($post_id);
	                if($content_post){
	                echo $content_post->post_content;
	                }else{
	                    echo '---';
	                }
			break;
		case "type":
			$custom = get_post_custom();
			if ( isset ( $custom["_cli_cookie_type"][0] ) ) {
				echo $custom["_cli_cookie_type"][0];
			}
			break;
	        case "category":
	            
	            $term_list = wp_get_post_terms($post->ID, 'cookielawinfo-category', array("fields" => "names"));
	            if (!empty($term_list)) {
	                echo $term_list[0];
	            } else
	                echo '<i>---</i>';

	            break;        
		case "duration":
			$custom = get_post_custom();
			if ( isset ( $custom["_cli_cookie_duration"][0] ) ) {
				echo $custom["_cli_cookie_duration"][0];
			}
			break;
	        case "sensitivity":
			$custom = get_post_custom();
			if ( isset ( $custom["_cli_cookie_sensitivity"][0] ) ) {
				echo $custom["_cli_cookie_sensitivity"][0];
			}
			break;
	        case "slugid":
			$custom = get_post_custom();
			if ( isset ( $custom["_cli_cookie_slugid"][0] ) ) {
				echo $custom["_cli_cookie_slugid"][0];
			}
			break;
		}	        
	}
	/*
	* Category default state add form 
	* @since 2.2.0
	*/
	public function cookie_add_defaultstate( $term ) 
	{	
		?>
		<div class="form-field">
			<label for="CLIdefaultstate"><?php _e( 'Category default state', 'cookie-law-info' ); ?></label>
			<input type="radio" name="CLIdefaultstate" value="enabled"  /><?php _e( 'Enabled', 'cookie-law-info' ); ?>
			<input type="radio" name="CLIdefaultstate" value="disabled" checked /><?php _e( 'Disabled', 'cookie-law-info' ); ?>	
			<p class="description"><?php _e('If you enable this option, the category toggle button will be in the active state for cookie consent.', 'cookie-law-info' ); ?></p>
		</div>
	<?php
	}

	/* 
	* Category Active State edit form 
	* @since 2.2.0
	*/
	public function cookie_edit_defaultstate($term) 
	{
		// put the term ID into a variable
		$t_id = $term->term_id;	 
		$term_CLIdefaultstate=get_term_meta( $t_id,'CLIdefaultstate',true ); 
		$strict_enabled = apply_filters('gdpr_strictly_enabled_category', array('necessary', 'obligatoire'));
		$cookie_category=get_term_by('id', $t_id, 'cookielawinfo-category');
		$cookie_category_slug=$cookie_category->slug;
		if(!in_array($cookie_category_slug, $strict_enabled)){
		?>
		<tr class="form-field">
			<th><label for="CLIdefaultstate"><?php _e('Category default state' ); ?></label></th>			 
			<td>
				<input type="radio" name="CLIdefaultstate" value="enabled" <?php checked( $term_CLIdefaultstate, 'enabled' ); ?>/><label><?php _e( 'Enabled', 'cookie-law-info' ); ?></label>
				<input type="radio" name="CLIdefaultstate" value="disabled" <?php checked( $term_CLIdefaultstate, 'disabled' ); ?>/><label><?php _e( 'Disabled', 'cookie-law-info' ); ?></label>		 
				<p class="description"><?php _e('If you enable this option, the category toggle button will be in the active state for cookie consent.', 'cookie-law-info' ); ?></p>
			</td>
		</tr>
	<?php
	}
	}

	/* 
	* Category Active State save form 
	* @since 2.2.0
	*/
	public function cookie_save_defaultstate($term_id)
	{
	    if(isset($_POST['CLIdefaultstate'])) 
	    {
			$term_CLIdefaultstate= $_POST['CLIdefaultstate'];
			
	        if($term_CLIdefaultstate)
	        {	
	            update_term_meta($term_id,'CLIdefaultstate',$term_CLIdefaultstate);
	        }
	    }else
	    {
	        update_term_meta($term_id,'CLIdefaultstate','disabled');
		}
		
	}
	
	/*
	*	Active State scripts 
	*	@since 2.2.0
	*/
	public function initialize_cookie_defaultstate()
	{
	    $args = array(
	        'taxonomy' => 'cookielawinfo-category',
	        'hide_empty' => false,
	        'meta_query' => array(
	            array(
	                'key' => 'CLIdefaultstate',
	                'compare' => 'NOT EXISTS'
	            ),
	        )
	    );
	    $terms = get_terms($args);
	    if(!empty($terms)){
	        foreach ($terms as $term) {
	        update_term_meta($term->term_id, 'CLIdefaultstate','disabled');
	        }
	    }
	}

	/* 
	/* 
	* Category load on start add form 
	* @since 2.1.8
	*/
	public function cookie_add_loadonstart( $term ) 
	{	
		?>
		<div class="form-field">
			<label for="CLIloadonstart"><?php _e( 'Load on start', 'cookie-law-info' ); ?></label>
			<input type="checkbox" name="CLIloadonstart" id="CLIloadonstart" value="1">
			<p class="description"><?php _e('If you enable this option, scripts under this category will be rendered without waiting for user consent on first page visit. Use this option discreetly, only if you are sure that no user sensitive data is being obtained via these scripts.', 'cookie-law-info' ); ?></p>
		</div>
	<?php
	}

	/* 
	* Category load on start edit form 
	* @since 2.1.8
	*/
	public function cookie_edit_loadonstart($term) 
	{
		// put the term ID into a variable
		$t_id = $term->term_id;	 
		$term_CLIloadonstart=get_term_meta( $t_id,'CLIloadonstart',true ); 
		?>
		<tr class="form-field">
			<th><label for="CLIloadonstart"><?php _e('Load on start' ); ?></label></th>			 
			<td>	 
				<input type="checkbox" name="CLIloadonstart" id="CLIloadonstart" value="1" <?php echo $term_CLIloadonstart==1 ? 'checked="checked"' : ''; ?>>
				<p class="description"><?php _e('If you enable this option, scripts under this category will be rendered without waiting for user consent on first page visit. Use this option discreetly, only if you are sure that no user sensitive data is being obtained via these scripts.', 'cookie-law-info' ); ?></p>
			</td>
		</tr>
	<?php
	}

	/* 
	* Category load on start save form 
	* @since 2.1.8
	*/
	public function cookie_save_loadonstart($term_id)
	{
	    if(isset($_POST['CLIloadonstart'])) 
	    {
	        $term_CLIloadonstart= $_POST['CLIloadonstart'];
	        if($term_CLIloadonstart)
	        {
	            update_term_meta($term_id,'CLIloadonstart',$term_CLIloadonstart);
	        }
	    }else
	    {
	        update_term_meta($term_id,'CLIloadonstart',0);
	    }
	}
	
	/*
	*	Load on start scripts 
	*	@since 2.1.8
	*/
	public function initialize_cookie_loadonstart()
	{
	    $args = array(
	        'taxonomy' => 'cookielawinfo-category',
	        'hide_empty' => false,
	        'meta_query' => array(
	            array(
	                'key' => 'CLIloadonstart',
	                'compare' => 'NOT EXISTS'
	            ),
	        )
	    );
	    $terms = get_terms($args);
	    if(!empty($terms)){
	        foreach ($terms as $term) {
	        update_term_meta($term->term_id, 'CLIloadonstart',0);
	        }
	    }
	}

	/* Category Priority Start */
	public function cookie_add_priority( $term ) 
	{	
		?>
		<div class="form-field">
			<label for="CLIpriority"><?php _e( 'Priority', 'cookie-law-info' ); ?></label>
			<input type="number" name="CLIpriority" id="CLIpriority" value="" step="1">
			<p class="description"><?php _e('Numeric - Higher the value, higher the priority', 'cookie-law-info' ); ?></p>
		</div>
	<?php
	}

	public function initialize_cookie_priority()
	{
	    $args = array(
	        'taxonomy' => 'cookielawinfo-category',
	        'hide_empty' => false,
	        'meta_query' => array(
	            array(
	                'key' => 'CLIpriority',
	                'compare' => 'NOT EXISTS'
	            ),
	        )
	    );
	    $terms = get_terms($args);
	    if(!empty($terms)){
	        foreach ($terms as $term) {
	        update_term_meta($term->term_id, 'CLIpriority', 0);

	        }
	    }
	}

	public function cookie_edit_priority($term) 
	{
		// put the term ID into a variable
		$t_id = $term->term_id;	 
		$term_CLIpriority = get_term_meta( $t_id, 'CLIpriority', true ); 
		?>
		<tr class="form-field">
			<th><label for="CLIpriority"><?php _e( 'Priority', 'cookie-law-info' ); ?></label></th>
			 
			<td>	 
				<input type="text" name="CLIpriority" id="CLIpriority" value="<?php echo esc_attr( $term_CLIpriority ) ? esc_attr( $term_CLIpriority ) : ''; ?>" step="1">
				<p class="description"><?php _e('Numeric - Higher the value, higher the priority', 'cookie-law-info' ); ?></p>
			</td>
		</tr>
	<?php
	}
	public function cookie_save_priority($term_id) {

	    if (isset($_POST['CLIpriority'])) {
	        $term_CLIpriority =(int) $_POST['CLIpriority'];
	        if ($term_CLIpriority)
	        {
	            update_term_meta($term_id, 'CLIpriority', $term_CLIpriority);
	        }
	    } else {
	        update_term_meta($term_id, 'CLIpriority', 0);
	    }
	}	

	public function manage_edit_custom_column_header( $columns )
	{
	    $columns['CLIpriority'] = __('Priority', 'cookie-law-info'); 
	    $columns['CLIloadonstart']=__('Load on start', 'cookie-law-info'); 
	    return $columns;
	}

	public function manage_custom_column_content( $value, $column_name, $tax_id )
	{
		if('CLIpriority'===$column_name)
		{
			$value=get_term_meta($tax_id,'CLIpriority',true); 
		}elseif($column_name=='CLIloadonstart')
		{
			$value=get_term_meta($tax_id,'CLIloadonstart',true);
			$value=$value==1 ? __('Yes','cookie-law-info') : __('No','cookie-law-info');
		}
		return $value;
	}	
	function remove_cli_addnew_link() 
	{
	    global $submenu;
	    if(isset($submenu) && !empty($submenu) && is_array($submenu))
		{
	    	unset($submenu['edit.php?post_type='.CLI_POST_TYPE][10]);
		}
	}
	

	/** Updates latest version number of plugin */
	public function update_to_latest_version_number() {
		update_option( CLI_MIGRATED_VERSION, CLI_LATEST_VERSION_NUMBER );
	}
	/**
	 Delete the values in all fields
	 WARNING - this has a predictable result i.e. will delete saved settings! Once deleted,
	 the get_admin_options() function will not find saved settings so will return default values
	 */
	public function delete_settings() 
	{
		if(defined( 'CLI_ADMIN_OPTIONS_NAME' )) 
		{
			delete_option( CLI_ADMIN_OPTIONS_NAME );
		}
		if ( defined ( 'CLI_SETTINGS_FIELD' ) ) 
		{
			delete_option( CLI_SETTINGS_FIELD );
		}
	}
	
	public function copy_old_settings_to_new() {
		$new_settings = Cookie_Law_Info::get_settings();
		$old_settings = get_option( CLI_ADMIN_OPTIONS_NAME );
		
		if ( empty( $old_settings ) ) {
			// Something went wrong:
			return false;
		}
		else {
			// Copy over settings:
			$new_settings['background'] 			= $old_settings['colour_bg'];
			$new_settings['border'] 				= $old_settings['colour_border'];
			$new_settings['button_1_action']		= 'CONSTANT_OPEN_URL';
			$new_settings['button_1_text'] 			= $old_settings['link_text'];
			$new_settings['button_1_url'] 			= $old_settings['link_url'];
			$new_settings['button_1_link_colour'] 	= $old_settings['colour_link'];
			$new_settings['button_1_new_win'] 		= $old_settings['link_opens_new_window'];
			$new_settings['button_1_as_button']		= $old_settings['show_as_button'];
			$new_settings['button_1_button_colour']	= $old_settings['colour_button_bg'];
			$new_settings['notify_message'] 		= $old_settings['message_text'];
			$new_settings['text'] 					= $old_settings['colour_text'];
			
			// Save new values:
			update_option( CLI_SETTINGS_FIELD, $new_settings );
		}
		return true;
	}
	/** Migrates settings from version 0.8.3 to version 0.9 */
	public function migrate_to_new_version() {
		
		if ( $this->has_migrated() ) {
			return false;
		}
		
		if ( !$this->copy_old_settings_to_new() ) {
			return false;
		}
		
		// Register that have completed:
		$this->update_to_latest_version_number();
		return true;
	}

	/** Returns true if user is on latest version of plugin */
	public function has_migrated() {
		// Test for previous version. If doesn't exist then safe to say are fresh install:
		$old_settings = get_option( CLI_ADMIN_OPTIONS_NAME );
		if ( empty( $old_settings ) ) {
			return true;
		}
		// Test for latest version number
		$version = get_option( CLI_MIGRATED_VERSION );
		if ( empty ( $version ) ) {
			// No version stored; not yet migrated:
			return false;
		}
		if ( $version == CLI_LATEST_VERSION_NUMBER ) {
			// Are on latest version
			return true;
		}
		echo 'VERSION: ' . $version . '<br /> V2: ' . CLI_LATEST_VERSION_NUMBER;
		// If you got this far then you're on an inbetween version
		return false;
	}

	/**
	 Prints a combobox based on options and selected=match value
	 
	 Parameters:
	 	$options = array of options (suggest using helper functions)
	 	$selected = which of those options should be selected (allows just one; is case sensitive)
	 
	 Outputs (based on array ( $key => $value ):
	 	<option value=$value>$key</option>
	 	<option value=$value selected="selected">$key</option>
	 */
	public function print_combobox_options( $options, $selected ) 
	{
		foreach ( $options as $key => $value ) {
			echo '<option value="' . $value . '"';
			if ( $value == $selected ) {
				echo ' selected="selected"';
			}
			echo '>' . $key . '</option>';
		}
	}

	/**
	 Returns list of available jQuery actions
	 Used by buttons/links in header
	 */
	public function get_js_actions() {
		$js_actions = array(
			'Close Header' => '#cookie_action_close_header',
			'Open URL' => 'CONSTANT_OPEN_URL'	// Don't change this value, is used by jQuery
		);
		return $js_actions;
	}

	/**
	 Returns button sizes (dependent upon CSS implemented - careful if editing)
	 Used when printing admin form (for combo boxes)
	 */
	public function get_button_sizes() {
		$sizes = Array(
			'Extra Large'	=> 'super',
			'Large'			=> 'large',
			'Medium'		=> 'medium',
			'Small'			=> 'small'
		);
		return $sizes;
	}

	/**
	 Function returns list of supported fonts
	 Used when printing admin form (for combo box)
	 */
	public function get_fonts() {
		$fonts = Array(
			'Default theme font'	=> 'inherit',
			'Sans Serif' 			=> 'Helvetica, Arial, sans-serif',
			'Serif' 				=> 'Georgia, Times New Roman, Times, serif',
			'Arial'					=> 'Arial, Helvetica, sans-serif',
			'Arial Black' 			=> 'Arial Black,Gadget,sans-serif',
			'Georgia' 				=> 'Georgia, serif',
			'Helvetica' 			=> 'Helvetica, sans-serif',
			'Lucida' 				=> 'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'Tahoma' 				=> 'Tahoma, Geneva, sans-serif',
			'Times New Roman' 		=> 'Times New Roman, Times, serif',
			'Trebuchet' 			=> 'Trebuchet MS, sans-serif',
			'Verdana' 				=> 'Verdana, Geneva'
		);
		return $fonts;
	}

}
