<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://cookielawinfo.com/
 * @since      2.1.3
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cookie_Law_Info
 * @subpackage Cookie_Law_Info/public
 * @author     WebToffee <info@webtoffee.com>
 */
class Cookie_Law_Info_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.1.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.1.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	public $version;

	public $plugin_obj;

	public static $cookie_list_arr=null;

	/*
	 * module list, Module folder and main file must be same as that of module name
	 * Please check the `register_modules` method for more details
	 */
	private $modules=array(
		'script-blocker',		
		'shortcode',
		'visitor-report', //vistor report
	);

	public static $existing_modules=array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.1.3
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_obj) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_obj = $plugin_obj;
		$cookie_domain = ( defined( 'COOKIE_DOMAIN' ) ? (string) COOKIE_DOMAIN : '' );
		if(isset($_GET['cli_bypass']) && get_option('CLI_BYPASS')==1) //just bypassing the blocker for scanning cookies
	    {
	        setcookie("viewed_cookie_policy",'yes',time()+3600,'/',$cookie_domain);
	        foreach($this->modules as $k=>$module)
	        {
	        	if($module=='script-blocker')
	        	{
	        		unset($this->modules[$k]); //disabling script blocker
	        	}
	        }
		}
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		$the_options = Cookie_Law_Info::get_settings();
		if ( $the_options['is_on'] == true ) 
		{
			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-public.css', array(),$this->version, 'all' );
			wp_enqueue_style( $this->plugin_name.'-gdpr', plugin_dir_url( __FILE__ ) . 'css/cookie-law-info-gdpr.css', array(),$this->version, 'all' );
			//this style will include only when shortcode is called
			wp_register_style( $this->plugin_name.'-table', plugin_dir_url(__FILE__) . 'css/cookie-law-info-table.css', array(),$this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		$the_options = Cookie_Law_Info::get_settings();
		$eu_countries =array();
		$eu_countries = apply_filters('wt_gdpr_eu_countrylist',array('GB'));
		$geoIP = 'disabled';
		if($the_options['is_eu_on'])
		{
			$geoIP = 'enabled';
		}
		if ( $the_options['is_on'] == true ) 
		{
			$non_necessary_cookie_ids = Cookie_Law_Info::get_non_necessary_cookie_ids();
			$cookies_by_cateogry = Cookie_Law_Info::get_cookie_by_category();
			$cookie_list = $this->get_cookie_list();              
			global $sitepress;
			$wp_current_lang=explode('_',get_locale());
			$wp_current_lang=$wp_current_lang[0];
			if(function_exists('icl_object_id') && $sitepress) //wpml enabled
			{
				$wp_current_lang=ICL_LANGUAGE_CODE;
			}
			$ajax_nonce = wp_create_nonce( "cli-blocker" );
			$consent_version = Cookie_Law_Info::wt_cli_get_consent_version();
			$strictly_enabled = apply_filters('gdpr_strictly_enabled_category', array('necessary', 'obligatoire'));
			
			$cli_cookie_datas = array(
			'nn_cookie_ids' => !empty($non_necessary_cookie_ids) ? $non_necessary_cookie_ids : array(),
			'non_necessary_cookies' => !empty($cookies_by_cateogry) ? $cookies_by_cateogry : array(),
			'cookielist' => !empty($cookie_list) ? $cookie_list : array(),
			'ajax_url'=> admin_url( 'admin-ajax.php' ),
			'current_lang'=>$wp_current_lang,
			'security'=>$ajax_nonce,
			'eu_countries' => $eu_countries,
			'geoIP' => $geoIP,
			'consentVersion' => $consent_version,
			'strictlyEnabled' => $strictly_enabled,
			'cookieDomain'			=> ( defined( 'COOKIE_DOMAIN' ) ? (string) COOKIE_DOMAIN : '' ),
			);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cookie-law-info-public.js', array( 'jquery' ),$this->version );
			wp_localize_script( $this->plugin_name, 'Cli_Data', $cli_cookie_datas );
			wp_localize_script( $this->plugin_name, 'log_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
		

	}
	/**
	 Ajax hook to give json settings	 
	 */
	public function cli_get_settings_json()
	{
		echo Cookie_Law_Info::get_json_settings();
		exit();
	}
	
	/**
	 Registers modules: public+admin	 
	 */
	public function common_modules()
	{	
		
		$cli_common_modules=get_option('cli_common_modules');
		if($cli_common_modules===false)
		{
			$cli_common_modules=array();
		}
		foreach ($this->modules as $module) //loop through module list and include its file
		{
			$is_active=1;
			if(isset($cli_common_modules[$module]))
			{
				$is_active=$cli_common_modules[$module]; //checking module status
			}else
			{
				$cli_common_modules[$module]=1; //default status is active
			}
			$module_file=plugin_dir_path( __FILE__ )."modules/$module/$module.php";
			if(file_exists($module_file) && $is_active==1)
			{
				self::$existing_modules[]=$module; //this is for module_exits checking
				require_once $module_file;
			}else
			{
				$cli_common_modules[$module]=0;	
			}
		}
		$out=array();
		foreach($cli_common_modules as $k=>$m)
		{
			if(in_array($k,$this->modules))
			{
				$out[$k]=$m;
			}
		}
		update_option('cli_common_modules',$out);
	}
	public static function module_exists($module)
	{
		return in_array($module,self::$existing_modules);
	}

	public function register_custom_post_type()
	{
		$labels = array(
			'name'					=> __('GDPR Cookie Consent','cookie-law-info'),
	        'all_items'             => __('Cookie List','cookie-law-info'),
			'singular_name'			=> __('Cookie','cookie-law-info'),
			'add_new'				=> __('Add New','cookie-law-info'),
			'add_new_item'			=> __('Add New Cookie Type','cookie-law-info'),
			'edit_item'				=> __('Edit Cookie Type','cookie-law-info'),
			'new_item'				=> __('New Cookie Type','cookie-law-info'),
			'view_item'				=> __('View Cookie Type','cookie-law-info'),
			'search_items'			=> __('Search Cookies','cookie-law-info'),
			'not_found'				=> __('Nothing found','cookie-law-info'),
			'not_found_in_trash'	=> __('Nothing found in Trash','cookie-law-info'),
			'parent_item_colon'		=> ''
		);
		$args = array(
			'labels'				=> $labels,
			'public'				=> false,
			'publicly_queryable'	=> false,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'query_var'				=> true,
			'rewrite'				=> true,
			'menu_icon'				=>plugin_dir_url( __FILE__ ).'images/cli_icon.png',
			'capabilities' => array(
				'publish_posts' => 'manage_options',
				'edit_posts' => 'manage_options',
				'edit_others_posts' => 'manage_options',
				'delete_posts' => 'manage_options',
				'delete_others_posts' => 'manage_options',
				'read_private_posts' => 'manage_options',
				'edit_post' => 'manage_options',
				'delete_post' => 'manage_options',
				'read_post' => 'manage_options',
			),
			/** done editing */
			
			'hierarchical'			=> false,
			'menu_position'			=> null,
			'supports'				=> array( 'title','editor' )
		); 
		register_post_type(CLI_POST_TYPE, $args );
	}

	/*
	* Registering cookie category
	*
	*/
	public function create_taxonomy() 
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
	/*
	* Insert Dummy post 
	*
	*/
	public function wt_cli_insert_cookie_lists() 
	{	
		$cookie_post_count = wp_count_posts(CLI_POST_TYPE);
		$total_post_count = $cookie_post_count->publish + $cookie_post_count->trash;
		if($total_post_count === 0 )
		{
			$default_cookies = array(
				array(
					'post_title' => 'viewed_cookie_policy',
					'slug' => 'viewed_cookie_policy',
					'post_content' => 'The cookie is set by the GDPR Cookie Consent plugin and is used to store whether or not user has consented to the use of cookies. It does not store any personal data.',
					'post_category' => 'necessary',
					'type' => 0,
					'expiry' => '11 months',
				),
				array(
					'post_title' => 'cookielawinfo-checkbox-necessary',
					'slug' => 'cookielawinfo-checkbox-necessary',
					'post_content' => 'This cookie is set by GDPR Cookie Consent plugin. The cookies is used to store the user consent for the cookies in the category "Necessary".',
					'post_category' => 'necessary',
					'type' => 0,
					'expiry' => '11 months',
				),
				array(
					'post_title' => 'cookielawinfo-checkbox-non-necessary',
					'slug' => 'cookielawinfo-checkbox-non-necessary',
					'post_content' => 'This cookie is set by GDPR Cookie Consent plugin. The cookies is used to store the user consent for the cookies in the category "Non Necessary".',
					'post_category' => 'necessary',
					'type' => 0,
					'expiry' => '11 months',
				),
				array(
					'post_title' => 'test_cookie',
					'slug' => 'test_cookie',
					'post_content' => '',
					'post_category' => 'non-necessary',
					'type' => 0,
					'expiry' => '11 months',
				)
			);
			foreach($default_cookies as $cookie => $cookie_data )
			{	
				if( !$this->wt_cli_post_exists_by_slug( $cookie_data['slug'] ) ) {

					$category = get_term_by('slug', $cookie_data['post_category'], 'cookielawinfo-category');
					if($category && is_object($category)) {	

						$category_id = $category->term_id;
						$cookie_data = 	array(
							'post_type' => CLI_POST_TYPE,
							'post_title' => $cookie_data['post_title'],
							'post_name' => $cookie_data['slug'],
							'post_content' => $cookie_data['post_content'],
							'post_category' => array($category_id),
							'post_status' => 'publish',
							'ping_status' => 'closed',
							'post_author' => 1,
							'meta_input' => array(
								'_cli_cookie_type' => $cookie_data['type'],
								'_cli_cookie_duration' => $cookie_data['expiry'],
								'_cli_cookie_sensitivity' => $cookie_data['post_category'],
								'_cli_cookie_slugid' => $cookie_data['slug'],
							)
						);
						$post_id = wp_insert_post($cookie_data);
						wp_set_object_terms($post_id, $cookie_data['post_category'], 'cookielawinfo-category');
					}
				}
			}
		}
	}
	private function wt_cli_post_exists_by_slug( $post_slug ) {
		$args_posts = array(
			'post_type'      => CLI_POST_TYPE,
			'post_status'    => 'any',
			'name'           => $post_slug,
			'posts_per_page' => 1,
		);
		$loop_posts = new WP_Query( $args_posts );
		if ( ! $loop_posts->have_posts() ) {
			return false;
		} else {
			$loop_posts->the_post();
			return $loop_posts->post->ID;
		}
	}
	/**
	 Get the cookie list
	*/
	public static function get_cookie_list()
	{	
		
    	if(self::$cookie_list_arr!==null)
    	{
    		return self::$cookie_list_arr;
    	}
		$args = array(  
		   'taxonomy' => 'cookielawinfo-category',
		   //'hide_empty' => false,
		   'meta_key' => 'CLIpriority',
		   'orderby' => 'meta_value_num', // use 'meta_value_num' if the value type of this meta is numeric.
		   'order' => 'DESC',
		);
		$terms = get_terms($args);
		$posts = array();
		global $sitepress;
		$wpml_default_lang='en';
		$wpml_current_lang='en';
		if(function_exists('icl_object_id') && $sitepress) //wpml enabled
		{
			$wpml_default_lang=$sitepress->get_default_language();
			$wpml_current_lang=ICL_LANGUAGE_CODE;
		}

		foreach ( $terms as $term ) 
		{
		    if(is_object($term))
		    {
		    	$term_slug=$term->slug;
		    	//wpml enabled and current language is not default language
		    	if(function_exists('icl_object_id') && $wpml_default_lang!=$wpml_current_lang) 
		    	{
		    		if(version_compare(ICL_SITEPRESS_VERSION, '3.2.0') >= 0) 
		    		{
		    			$original_term_id=apply_filters('wpml_object_id',$term->term_id,'category',true,$wpml_default_lang);
		    		}else
		    		{
		    			$original_term_id=icl_object_id($term->term_id,'category',true,$wpml_default_lang);
		    		}
		    		$sitepress->switch_lang($wpml_default_lang);
		    		$original_term=get_term_by('id',$original_term_id,'cookielawinfo-category');
		    		if($original_term && $original_term->term_id)
		    		{
		    			$term_slug=$original_term->slug;
		    		}
		    		$sitepress->switch_lang($wpml_current_lang);
		    	}
		    	$posts[$term_slug] = get_posts(array('posts_per_page' => -1, 'post_type' => 'cookielawinfo', 'taxonomy' => $term->taxonomy, 'term' => $term->slug,  ));
		    	$posts[$term_slug]['term_id'] = $term->term_id;
		    	$posts[$term_slug]['name'] = $term->name;
		    	
		    	/*
	             * @since 2.1.8
	             * is the current category is `loadonstart`
	            */
				$posts[$term_slug]['loadonstart']=0;
				$posts[$term_slug]['defaultstate']='enabled';
		    	if(function_exists('get_term_meta'))
		    	{
					$term_meta=get_term_meta($term->term_id,'CLIloadonstart',true);
					$term_defaultstate=get_term_meta($term->term_id,'CLIdefaultstate',true);
					$strict_enabled = apply_filters('gdpr_strictly_enabled_category', array('necessary', 'obligatoire'));
					$cookie_category=get_term_by('id',$term->term_id, 'cookielawinfo-category');
					$cookie_category_slug=$cookie_category->slug;
			    	if(!empty($term_meta))
			    	{
						$posts[$term_slug]['loadonstart']=$term_meta;	
					}
					// Set category default state
					if(!empty($term_defaultstate) && !in_array($cookie_category_slug, $strict_enabled))
					{	
						$posts[$term_slug]['defaultstate']=$term_defaultstate;
					}
		    	}		    	
			}
		}
		self::$cookie_list_arr = $posts;	
		return $posts;		    
	}
	/**
	 * Sort cookies based on category type
	 *
	 * @since    2.2.3
	 */
	public static function wt_cli_sort_cookies($cookie_list = array())
	{	
		$strict_enabled = apply_filters('gdpr_strictly_enabled_category', array('necessary', 'obligatoire'));
		$necessary_cookies = array();
		$non_necessary_cookies = array();
		$the_cookie_list = array();
		foreach($cookie_list as $cookie => $cookie_data)
		{
			if(in_array($cookie, $strict_enabled)){

				$necessary_cookies[$cookie] = $cookie_data ;

			}
			else
			{
				$non_necessary_cookies[$cookie] = $cookie_data ;

			}
		}
		$the_cookie_list = array_merge($necessary_cookies,$non_necessary_cookies);
		return $the_cookie_list;
		
	}
	/** Removes leading # characters from a string */
	public static function cookielawinfo_remove_hash( $str ) 
	{
	  if( $str[0] == "#" ) 
	  {
	    $str = substr( $str, 1, strlen($str) );
	  }
	  else {
	    return $str;
	  }
	  return self::cookielawinfo_remove_hash( $str );
	}

	/**
	 Outputs the cookie control script in the footer
	 N.B. This script MUST be output in the footer.
	 
	 This function should be attached to the wp_footer action hook.
	*/
	public function cookielawinfo_inject_cli_script() 
	{
		$the_options = Cookie_Law_Info::get_settings();
	  	$geo_loc_enabled=0; //this is for debugging purpose
		$cookie_domain = ( defined( 'COOKIE_DOMAIN' ) ? (string) COOKIE_DOMAIN : '' );
		if ( $the_options['is_on'] == true ) 
	  	{             
			$cookie_list = self::get_cookie_list();
       		$the_cookie_list = self::wt_cli_sort_cookies($cookie_list);  
	        foreach($the_cookie_list as $key => $cookie) 
	        {  	
				
	            if(empty($_COOKIE["cookielawinfo-checkbox-$key"])) 
	            {	
					if($cookie['defaultstate']== 'enabled')
					{	
						@setcookie("cookielawinfo-checkbox-$key",'yes',time()+3600,'/',$cookie_domain);		
					}
					else
					{
						@setcookie("cookielawinfo-checkbox-$key",'no',time()+3600,'/',$cookie_domain);
					}
	            }
	        }
	        
	        // Output the HTML in the footer:
	        $message = nl2br($the_options['notify_message']);
	        $message = __($message, 'cookie-law-info');

	        //removing close button shortcode from main text and saving close button html to a variable
	        $cli_close_btn_html='';
	        if(strpos($message,'[cookie_close]')!==false)
	        {
	        	$message=str_replace('[cookie_close]','',$message);
	        	$cli_close_btn_html=do_shortcode('[cookie_close]');
	        } 

	    	$str = do_shortcode(stripslashes($message));
	                    
	        $head= trim(stripslashes($the_options['bar_heading_text']));

	        //setting custom style
	        $cli_bar_style='';
	        if(Cookie_Law_Info_Admin::module_exists('cli-themes') && isset($the_options['bar_style']))
	        {
	           $cli_bar_style=stripslashes(Cookie_Law_Info_Cli_Themes::create_style_attr($the_options['bar_style']));
	        } 

	        //setting custom hd style
			$cli_bar_hd_style='';
			$pop_out='';
	        if(Cookie_Law_Info_Admin::module_exists('cli-themes') && isset($the_options['bar_hd_style']))
	        {
	           $cli_bar_hd_style=Cookie_Law_Info_Cli_Themes::create_style_attr($the_options['bar_hd_style']);
			} 
			if($the_options['accept_all']!= true && $the_options['cookie_setting_popup']!= true )
			{
				$pop_content_html_file=plugin_dir_path(CLI_PLUGIN_FILENAME).'public/views/cookie-law-info_popup_content.php';
				if(file_exists($pop_content_html_file))
				{
					include $pop_content_html_file;
				}  
			}     
	              
		    $notify_html = '<div id="' .$this->cookielawinfo_remove_hash( $the_options["notify_div_id"] ) . '" data-cli-geo-loc="'.$geo_loc_enabled.'" style="'.$cli_bar_style.'" class="wt-cli-cookie-bar"><div class="cli-wrapper">'.
		    $cli_close_btn_html.
		    ($head!="" ? '<h5 style="'.$cli_bar_hd_style.'">'.$head.'</h5>' : '')
		    .'<span>' . $str . '</span>'.$pop_out.'</div></div>';
		    
		    //if($the_options['showagain_tab'] === true) 
		    //{
		    	$show_again=__($the_options["showagain_text"],'cookie-law-info');
		      	$notify_html .= '<div id="' . $this->cookielawinfo_remove_hash( $the_options["showagain_div_id"] ) . '" style="display:none;"><span id="cookie_hdr_showagain">'.$show_again.'</span></div>';
		    //}
		    global $wp_query;
		    $current_obj = get_queried_object();
		    $post_slug ='';
		    if(is_object($current_obj))
		    {
			    if(is_category() || is_tag())
			    {
			    	$post_slug =isset($current_obj->slug) ? $current_obj->slug : '';
			    }
			    elseif(is_archive())
			    {
			    	$post_slug =isset($current_obj->rewrite) && isset($current_obj->rewrite['slug']) ? $current_obj->rewrite['slug'] : '';
			    }
			    else
			    {
			    	if(isset($current_obj->post_name))
			    	{
			    		$post_slug =$current_obj->post_name;
			    	}			    	
			    }
			}
			$notify_html = apply_filters('cli_show_cookie_bar_only_on_selected_pages',$notify_html,$post_slug);
		    if($notify_html!="")
		    {
				require_once plugin_dir_path( __FILE__ ).'views/cookie-law-info_bar.php';
		    }
	  	}
	}

	
	/* Print scripts or data in the head tag on the front end. */
	public function include_user_accepted_cookielawinfo()
	{
	     $cookie_list = $this->get_cookie_list(); 
	     $the_options = Cookie_Law_Info::get_settings();
	      
	     if($the_options['is_on'] == true && !is_admin()) 
	     {
	        foreach ($cookie_list as $key => $cookie) 
	        {	  
				$is_script_block = 'true';            
				unset($cookie['term_id']);
				unset($cookie['name']);
				unset($cookie['defaultstate']); 
				$scripts = ''; 
				if(isset($cookie['loadonstart']) && $cookie['loadonstart']==1){
					$is_script_block = 'false';  
				}	        
	            unset($cookie['loadonstart']);
				foreach ($cookie as $cookie_post) 
				{
					$hscript_meta = get_post_meta($cookie_post->ID, '_cli_cookie_headscript_meta', null);
					
					if(!empty($hscript_meta[0]))
					{	
					$wt_cli_replace = 'data-cli-class="cli-blocker-script"  data-cli-category="'.$key.'" data-cli-script-type="'.$key.'" data-cli-block="'.$is_script_block.'" data-cli-element-position="head" ';
					$hscript_meta[0] = str_replace('<script', '<script type="text/plain"'.' '.$wt_cli_replace, $hscript_meta[0]);   
					$scripts.= $hscript_meta[0];
					}  
				}
	            echo $scripts;
	        }
	     }
	}
	public function include_user_accepted_cookielawinfo_in_body()
	{
	
	   $cookie_list = $this->get_cookie_list();
	   $the_options = Cookie_Law_Info::get_settings();
	    if($the_options['is_on'] == true && !is_admin()) 
	    {	        
	        foreach ($cookie_list as $key => $cookie) 
	        {    
				
	            unset($cookie['term_id']);
				unset($cookie['name']); 
				unset($cookie['defaultstate']);          
	            $scripts = '';            
				$is_script_block = 'true';
				if(isset($cookie['loadonstart']) && $cookie['loadonstart']==1){
					$is_script_block = 'false';  
				}	 
	            unset($cookie['loadonstart']);
				foreach ($cookie as $cookie_post) 
				{     
					$bscript_meta = get_post_meta($cookie_post->ID, '_cli_cookie_bodyscript_meta', null);
					
					if(!empty($bscript_meta[0]))
					{	
						$wt_cli_replace = 'data-cli-class="cli-blocker-script"  data-cli-category="'.$key.'" data-cli-script-type="'.$key.'" data-cli-block="'.$is_script_block.'" data-cli-element-position="body"';
						$bscript_meta[0] = str_replace('<script', '<script type="text/plain"'.' '.$wt_cli_replace, $bscript_meta[0]);   
						$scripts.= $bscript_meta[0];
					}
					
					
				}
	            echo $scripts;
	        }
	    }
	}
	public function other_plugin_clear_cache()
	{
		$cli_flush_cache=Cookie_Law_Info::is_cache_plugin_installed() ? 1 : 2;
		//$cli_flush_cache=2;
		?>
		<script type="text/javascript">
			var cli_flush_cache=<?php echo $cli_flush_cache; ?>;
		</script>
		<?php
	}
}
