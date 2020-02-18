<?php
/**
 * The cookie scanning functionality of the plugin.
 *
 * @link       http://cookielawinfo.com/
 * @since      2.1.5
 *
 * @package    Cookie_Law_Info
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include( plugin_dir_path( __FILE__ ).'classes/class-cookie-scanner-ajax.php');
class Cookie_Law_Info_Cookie_Scaner
{
	
	public $main_tb='cli_cookie_scan';
	public $url_tb='cli_cookie_scan_url';
	public $cookies_tb='cli_cookie_scan_cookies';
	public $category_table = 'cli_cookie_scan_categories';
	public $not_keep_records=true;
	public $scan_page_mxdata; //maximum url per request for scanning //!important do not give value more than 5
	public $fetch_page_mxdata=100; //take pages
	public function __construct()
	{		
		/* creating necessary tables for cookie scaner  */
        register_activation_hook(CLI_PLUGIN_FILENAME,array($this,'activator'));
        $this->status_labels=array(
			0=>'',
			1=>__('Incomplete','cookie-law-info'),
			2=>__('Completed','cookie-law-info'),
			3=>__('Stopped','cookie-law-info'),
		);
        add_action('admin_init',array( $this,'export_result'));
		add_action( 'admin_menu', array($this,'add_admin_pages'));
		
		$url_per_request=get_option('cli_cs_url_per_request');
        if(!$url_per_request)
        {
            $url_per_request=5;
        }
        $this->scan_page_mxdata=$url_per_request;

		add_action('cli_module_settings_advanced',array($this,'settings_advanced'));
        add_action('cli_module_save_settings',array( $this,'save_settings'));
	}
	

	/**
     *  =====Plugin settings page Hook=====
     * save settings hook
     * @since 2.1.7
     **/
    public function save_settings()
    {
        if(isset($_POST['cli_cs_url_per_request']))
        {
            $allowed_options=range(1, 10);
            if(in_array($_POST['cli_cs_url_per_request'],$allowed_options))
            {
                $url_per_request=$_POST['cli_cs_url_per_request'];
            }else
            {
                $url_per_request=5;
            }
            update_option('cli_cs_url_per_request',$url_per_request);
        }
    }
    /**
     *  =====Plugin settings page Hook=====
     *  Insert content to advanced tab
     * 	@since 2.1.7
     **/
    public function settings_advanced()
    {
        $url_per_request=get_option('cli_cs_url_per_request');
        if(!$url_per_request)
        {
            $url_per_request=5;
        }
        ?>
        <table class="form-table">
        	<tr valign="top">
            <th scope="row"><?php _e('Cookie scanner URL per request', 'cookie-law-info'); ?></th>
            <td>
            	<select class="vvv_combobox" style="width: 175px" name="cli_cs_url_per_request">
        		<?php
        		for($i=1; $i<=10; $i++)
        		{
        			?>
        			<option value="<?php echo $i; ?>" <?php echo $i==$url_per_request ? 'selected' : ''; ?>><?php echo $i; ?></option>
        			<?php
        		}
        		?>
            	</select>
                <span class="cli_form_help"><?php _e("Reduce/control the number of URLs scanned per request depending on the server limitation. E.g if you see an error \"Unable to connect..retrying \" during a scan try reducing this number to '2'.", 'cookie-law-info'); ?></span>
            </td>
            </tr>
        </table>
        <?php
    }


	/*
    * returning labels of status
    */
	public function getStatusText($status)
	{
		return isset($this->status_labels[$status]) ? $this->status_labels[$status] : __('Unknown','cookie-law-info');
	}

	/*
    * export to csv
    */
	public function export_result()
	{
		if(isset($_GET['cli_scan_export']) && (int) $_GET['cli_scan_export']>0) 
		{
			//cookie export class
            include( plugin_dir_path( __FILE__ ).'classes/class-cookie-export.php');
            $cookie_serve_export=new Cookie_Law_Info_Cookie_Export();
            $cookie_serve_export->do_export($_GET['cli_scan_export'],$this);
			exit();
		}
	}

	/*
    *called on activation
    */
    public function activator()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );       
        if(is_multisite()) 
        {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach($blog_ids as $blog_id) 
            {
                switch_to_blog( $blog_id );
                $this->install_tables();
		        if(!get_option('cli_cs_url_per_request'))
		        {
		            update_option('cli_cs_url_per_request',5);
		        }
                restore_current_blog();
            }
        }
        else 
        {
            $this->install_tables();
            if(!get_option('cli_cs_url_per_request'))
	        {
	            update_option('cli_cs_url_per_request',5);
	        }
        }
    }

    /*
    * Install necessary tables
    */
    public function install_tables()
    {
        global $wpdb;
        
        //creating main table ========================
        $table_name=$wpdb->prefix.$this->main_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $create_table_sql= "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan` INT NOT NULL AUTO_INCREMENT,
			    `status` INT NOT NULL DEFAULT '0',
			    `created_at` INT NOT NULL DEFAULT '0',
			    `total_url` INT NOT NULL DEFAULT '0',
			    `total_cookies` INT NOT NULL DEFAULT '0',
			    `current_action` VARCHAR(50) NOT NULL,
			    `current_offset` INT NOT NULL DEFAULT '0',
			    PRIMARY KEY(`id_cli_cookie_scan`)
			);";
            dbDelta($create_table_sql);
        }
        //creating main table ========================


        //creating url table ========================
        $table_name=$wpdb->prefix.$this->url_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $create_table_sql= "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan_url` INT NOT NULL AUTO_INCREMENT,
			    `id_cli_cookie_scan` INT NOT NULL DEFAULT '0',
			    `url` TEXT NOT NULL,
			    `scanned` INT NOT NULL DEFAULT '0',
			    `total_cookies` INT NOT NULL DEFAULT '0',
			    PRIMARY KEY(`id_cli_cookie_scan_url`)
			);";
            dbDelta($create_table_sql);
        }
        //creating url table ========================

        //creating cookies table ========================
        $table_name=$wpdb->prefix.$this->cookies_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $create_table_sql= "CREATE TABLE `$table_name`(
			    `id_cli_cookie_scan_cookies` INT NOT NULL AUTO_INCREMENT,
			    `id_cli_cookie_scan` INT NOT NULL DEFAULT '0',
			    `id_cli_cookie_scan_url` INT NOT NULL DEFAULT '0',
			    `cookie_id` VARCHAR(255) NOT NULL,
			    `expiry` VARCHAR(255) NOT NULL,
			    `type` VARCHAR(255) NOT NULL,
			    `category` VARCHAR(255) NOT NULL,
			    PRIMARY KEY(`id_cli_cookie_scan_cookies`),
			    UNIQUE `cookie` (`id_cli_cookie_scan`, `cookie_id`)
			) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
            dbDelta($create_table_sql);
        }
		//creating cookies table ========================

		 //creating categories table ========================
		 $table_name=$wpdb->prefix.$this->category_table;
		 $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
		 if(!$wpdb->get_results($search_query,ARRAY_N)) 
		 {           
			 $create_table_sql= "CREATE TABLE `$table_name`(
				 `id_cli_cookie_category` INT NOT NULL AUTO_INCREMENT,
				 `cli_cookie_category_name` VARCHAR(255) NOT NULL,
				 `cli_cookie_category_description` TEXT  NULL,
				 PRIMARY KEY(`id_cli_cookie_category`),
				 UNIQUE `cookie` (`cli_cookie_category_name`)
			 ) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
			 dbDelta($create_table_sql);
		 }
		 //creating cookies table ========================
        $this->update_tables();
	}
	
	/*
    * @since 2.1.9
    * update the table
    */
    private function update_tables()
    {
    	global $wpdb;
    	//Cookie table =======
        //`description` column
		$table_name=$wpdb->prefix.$this->cookies_tb;
		$cat_table=$wpdb->prefix.$this->category_table;
        $search_query = "SHOW COLUMNS FROM `$table_name` LIKE 'description'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {
        	$wpdb->query("ALTER TABLE `$table_name` ADD `description` TEXT NULL DEFAULT '' AFTER `category`");
		}
		// category_id` column
		$search_query = "SHOW COLUMNS FROM `$table_name` LIKE 'category_id'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {
			$wpdb->query("ALTER TABLE `$table_name` ADD `category_id` INT NOT NULL  AFTER `category`");
			$wpdb->query("ALTER TABLE `$table_name` ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES `$cat_table` (`id_cli_cookie_category`)");
			
		}
		
	}
	
    /*
    * checking necessary tables are installed
    */
    protected function check_tables()
    {
    	global $wpdb;
    	$out=true;
    	//checking main table ========================
        $table_name=$wpdb->prefix.$this->main_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $out=false;
        }

        //checking url table ========================
        $table_name=$wpdb->prefix.$this->url_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $out=false;
        }

        //checking cookies table ========================
        $table_name=$wpdb->prefix.$this->cookies_tb;
        $search_query = "SHOW TABLES LIKE '%".$table_name."%'";
        if(!$wpdb->get_results($search_query,ARRAY_N)) 
        {           
            $out=false;
        }
        return $out;
    }

	/**
	 * Add administration menus
	 *
	 * @since 2.1.5
	 **/
	public function add_admin_pages() 
	{
        add_submenu_page(
			'edit.php?post_type='.CLI_POST_TYPE,
			__('Cookie Scanner','cookie-law-info'),
			__('Cookie Scanner','cookie-law-info'),
			'manage_options',
			'cookie-law-info-cookie-scaner',
			array($this, 'cookie_scaner_page')
		);		
	}

	/*
	*
	* Scaner page (Admin page)
	*/
	public function cookie_scaner_page()
	{
		$cookie_list=self::get_cookie_list();
		wp_enqueue_script('cookielawinfo_cookie_scaner',plugin_dir_url( __FILE__ ).'assets/js/cookie-scaner.js',array(),CLI_VERSION);
		$scan_page_url=admin_url('edit.php?post_type='.CLI_POST_TYPE.'&page=cookie-law-info-cookie-scaner');
		$result_page_url=$scan_page_url.'&scan_result';
		$export_page_url=$scan_page_url.'&cli_scan_export=';
		$import_page_url=$scan_page_url.'&cli_cookie_import=';
		$last_scan=$this->get_last_scan();
		$params = array(
	        'nonces' => array(
	            'cli_cookie_scaner' => wp_create_nonce('cli_cookie_scaner'),
	        ),
	        'ajax_url' => admin_url('admin-ajax.php'),
	        'scan_page_url'=>$scan_page_url,
	        'result_page_url'=>$result_page_url,
	        'export_page_url'=>$export_page_url,
	        'loading_gif'=>plugin_dir_url(__FILE__).'assets/images/loading.gif',
	        'labels'=>array(
	        	'scanned'=>__('Scanned','cookie-law-info'),
				'finished'=>__('Scanning completed.','cookie-law-info'),
				'import_finished'=>__('Added to cookie list.','cookie-law-info'),
				'retrying'=>__('Unable to connect. Retrying...','cookie-law-info'),
				'finding'=>__('Finding pages...','cookie-law-info'),
				'scanning'=>__('Scanning pages...','cookie-law-info'),
				'error'=>__('Error','cookie-law-info'),
				'stop'=>__('Stop','cookie-law-info'),
				'scan_again'=>__('Scan again','cookie-law-info'),
				'export'=>__('Download cookies as CSV','cookie-law-info'),
				'import'=>__('Add to cookie list','cookie-law-info'),
				'view_result'=>__('View scan result','cookie-law-info'),
				'import_options'=>__('Import options','cookie-law-info'),
				'replace_old'=>__('Replace old','cookie-law-info'),
				'merge'=>__('Merge','cookie-law-info'),
				'recommended'=>__('Recommended','cookie-law-info'),
				'append'=>__('Append','cookie-law-info'),
				'not_recommended'=>__('Not recommended','cookie-law-info'),
				'cancel'=>__('Cancel','cookie-law-info'),
				'start_import'=>__('Start import','cookie-law-info'),
				'importing'=>__('Importing....','cookie-law-info'),
				'refreshing'=>__('Refreshing....','cookie-law-info'),
				'reload_page'=>__('Error !!! Please reload the page to see cookie list.','cookie-law-info'),
				'stoping'=>__('Stopping...','cookie-law-info'),
				'scanning_stopped'=>__('Scanning stopped.','cookie-law-info'),
				'ru_sure'=>__('Are you sure?','cookie-law-info'),
				'success'=>__('Success','cookie-law-info'),
				'thankyou'=>__('Thank you','cookie-law-info'),
				'checking_api'=>__('Checking API','cookie-law-info'),
				'sending'=>__('Sending...','cookie-law-info'),
				'total_urls_scanned'=>__('Total URLs scanned','cookie-law-info'),
				'total_cookies_found'=>__('Total Cookies found','cookie-law-info'),
	        )
	    );
	    wp_localize_script('cookielawinfo_cookie_scaner','cookielawinfo_cookie_scaner',$params);
	    if(isset($_GET['scan_result']))
		{
			$scan_details=$this->get_last_scan();
			$scan_urls=array(
				'total'=>0,
				'data'=>array()
			);
			$scan_cookies=array(
				'total'=>0,
				'data'=>array()
			);
			if($scan_details && isset($scan_details['id_cli_cookie_scan']))
			{
				$scan_urls=$this->get_scan_urls($scan_details['id_cli_cookie_scan']);
				$scan_cookies=$this->get_scan_cookies($scan_details['id_cli_cookie_scan'],0,-1);
			}
			$view_file="scan-result.php";
		}else
		{
			$view_file="scan-cookies.php";
		}

		$localhost_arr = array(
		    '127.0.0.1',
		    '::1'
		);
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
		{
		    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip_address = $_SERVER['REMOTE_ADDR'];
		}
	    if(!$this->check_tables() 
	    	|| version_compare(CLI_VERSION,'2.1.4')<=0 
	    	|| in_array($ip_address,$localhost_arr))
		{
			
			$error_message=__("Unable to load cookie scanner.","cookie-law-info");
			if(version_compare(CLI_VERSION,'2.1.4')<=0)
			{
				$error_message.=" ".__("Need `GDPR Cookie Consent` plugin version above 2.1.4","cookie-law-info");
			}
			if(in_array($ip_address,$localhost_arr))
			{
				$error_message.=" ".__("Scanning will not work on local server.","cookie-law-info");
			}
			$view_file="unable-to-start.php";
		}
		include( plugin_dir_path( __FILE__ ).'views/'.$view_file);
	}

	/*
	*
	*	Create a DB entry for scanning
	*/
	protected function createScanEntry($total_url=0)
	{
		global $wpdb;

		//we are not planning to keep records of old scans
		if($this->not_keep_records)
		{
			$this->flushScanRecords();
		}

		$scan_table=$wpdb->prefix.$this->main_tb;
		$data_arr=array(
			'created_at'=>time(),
			'total_url'=>$total_url,
			'total_cookies'=>0,
			'status'=>1
		);
		update_option('CLI_BYPASS',1);
		if($wpdb->insert($scan_table,$data_arr))
		{
			return $wpdb->insert_id;
		}else
		{
			return '0';
		}
	}

	/*
	*
	*	Update scanning status
	*/
	protected function updateScanEntry($data_arr,$scan_id)
	{
		global $wpdb;
		$scan_table=$wpdb->prefix.$this->main_tb;
		if($wpdb->update($scan_table,$data_arr,array('id_cli_cookie_scan'=>$scan_id)))
		{
			return true;
		}else
		{
			return false;
		}
	}

	/*
	*
	*	Insert URLs
	*/
	protected function insertUrl($scan_id,$permalink)
	{
		global $wpdb;
		$url_table=$wpdb->prefix.$this->url_tb;
		$data_arr=array(
        	'id_cli_cookie_scan'=>$scan_id,
        	'url'=>$permalink,
        	'scanned'=>0,
        	'total_cookies'=>0
        );
		$wpdb->insert($url_table,$data_arr);
	}

	/*
	*
	*	Insert Cookies
	*/
	protected function insertCookies($scan_id,$url_id,$url,$cookie_data,$out)
	{
		global $wpdb;
		$url_table=$wpdb->prefix.$this->cookies_tb;
		$cat_table=$wpdb->prefix.$this->category_table;
		$sql="INSERT IGNORE INTO `$url_table` (`id_cli_cookie_scan`,`id_cli_cookie_scan_url`,`cookie_id`,`expiry`,`type`,`category`,`category_id`,`description`) VALUES ";
		$sql_arr=array();
		$out[]=$url;
		foreach($cookie_data as $cookies)
		{
			$cookie_id=trim($cookies['cookie_id']);
			$expiry=trim($cookies['duration']);
			$type=$cookies['type'];
			$category=$cookies['category'];
			$description=addslashes($cookies['description']);
			$category_id = $wpdb->get_var("SELECT `id_cli_cookie_category` FROM `$cat_table` WHERE `cli_cookie_category_name` = '$category'");
			$out[]='&nbsp;&nbsp;&nbsp;'.$cookie_id;
			$sql_arr[]="('$scan_id','$url_id','$cookie_id','$expiry','$type','$category','$category_id','$description')";
		}
		$sql=$sql.implode(",",$sql_arr);
		$wpdb->query($sql);
		return $out;
	}
	protected function insertCategories($cookie_data)
	{
		global $wpdb;
		$cat_table=$wpdb->prefix.$this->category_table;	
		$cat_arr=array();
		$cat_sql="INSERT IGNORE INTO `$cat_table` (`cli_cookie_category_name`,`cli_cookie_category_description`) VALUES ";
		foreach($cookie_data as $cookies)
		{
			$cat_description=addslashes($cookies['category_desc']);
			$category=$cookies['category'];	
			$cat_arr[]="('$category','$cat_description')";
		}
		$cat_sql=$cat_sql.implode(",",$cat_arr);
		$wpdb->query($cat_sql);
	}
	/*
    * @since 2.1.9
    * Insert categories
    */

	/*
	*
	*	Update scanned to URL
	*/
	protected function updateUrl($url_id_arr)
	{
		global $wpdb;
		$url_table=$wpdb->prefix.$this->url_tb;
		$sql="UPDATE `$url_table` SET `scanned`=1 WHERE id_cli_cookie_scan_url IN(".implode(",",$url_id_arr).")";
		$wpdb->query($sql);
	}
	
	/*
	*
	* Get last scan details
	*/
	protected function get_last_scan()
	{
		global $wpdb;
		$scan_table=$wpdb->prefix.$this->main_tb;
		$sql="SELECT * FROM `$scan_table` ORDER BY id_cli_cookie_scan DESC LIMIT 1";
		return $wpdb->get_row($sql,ARRAY_A);
	}

	/*
	*
	* URLs that are scanned
	*/
	public function get_scan_urls($scan_id,$offset=0,$limit=100)
	{
		global $wpdb;
		$out=array(
			'total'=>0,
			'data'=>array()
		);
		$url_table=$wpdb->prefix.$this->url_tb;
		$count_sql="SELECT COUNT(id_cli_cookie_scan_url) AS ttnum FROM $url_table WHERE id_cli_cookie_scan='$scan_id'";
		$count_arr=$wpdb->get_row($count_sql,ARRAY_A);
		if($count_arr){
			$out['total']=$count_arr['ttnum'];
		}

		$sql="SELECT * FROM $url_table WHERE id_cli_cookie_scan='$scan_id' ORDER BY id_cli_cookie_scan_url ASC LIMIT $offset,$limit";
		
		$data_arr=$wpdb->get_results($sql,ARRAY_A);
		if($data_arr){
			$out['data']=$data_arr;
		}
		return $out;
	}

	/*
	*
	* Cookies that are got while scanning
	*/
	public function get_scan_cookies($scan_id,$offset=0,$limit=100)
	{
		global $wpdb;
		$out=array(
			'total'=>0,
			'data'=>array()
		);
		$cookies_table=$wpdb->prefix.$this->cookies_tb;
		$url_table=$wpdb->prefix.$this->url_tb;
		$cat_table=$wpdb->prefix.$this->category_table;
		$count_sql="SELECT COUNT(id_cli_cookie_scan_cookies) AS ttnum FROM $cookies_table WHERE id_cli_cookie_scan='$scan_id'";
		$count_arr=$wpdb->get_row($count_sql,ARRAY_A);
		if($count_arr){
			$out['total']=$count_arr['ttnum'];
		}

		$sql="SELECT * FROM $cookies_table INNER JOIN $cat_table ON $cookies_table.category_id = $cat_table.id_cli_cookie_category INNER JOIN $url_table ON $cookies_table.id_cli_cookie_scan_url = $url_table.id_cli_cookie_scan_url WHERE $cookies_table.id_cli_cookie_scan='$scan_id' ORDER BY id_cli_cookie_scan_cookies ASC".($limit>0 ? " LIMIT $offset,$limit" : "");
		$data_arr=$wpdb->get_results($sql,ARRAY_A);
		if($data_arr){
			$out['data']=$data_arr;
		}
		return $out;
	}


	/*
	*
	* Taking existing cookie list (Manually created and Inserted via scanner)
	*/
	public static function get_cookie_list()
	{
		$args=array(
			'numberposts'=>-1,
			'post_type'=>CLI_POST_TYPE,
			'orderby'=>'ID',
			'order'=>'DESC'
		);
		return get_posts($args);
	}

	/*
	*
	* Delete all previous scan records
	*/
	public function flushScanRecords()
	{
		global $wpdb;
		$table_name=$wpdb->prefix.$this->main_tb; 
		$wpdb->query("TRUNCATE TABLE $table_name");
		$table_name=$wpdb->prefix.$this->url_tb;
		$wpdb->query("TRUNCATE TABLE $table_name");
		$table_name=$wpdb->prefix.$this->cookies_tb;
		$wpdb->query("TRUNCATE TABLE $table_name");
	}

}
new Cookie_Law_Info_Cookie_Scaner();