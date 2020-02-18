<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Cookie_Law_Info_Cookie_Scanner_Ajax extends Cookie_Law_Info_Cookie_Scaner
{
	
	public function __construct()
	{		
		add_action('wp_ajax_cli_cookie_scaner',array($this,'ajax_cookie_scaner'));
		$url_per_request=get_option('cli_cs_url_per_request');
        if(!$url_per_request)
        {
            $url_per_request=5;
        }
        $this->scan_page_mxdata=$url_per_request;
	}

	/*
	*
	* Main Ajax hook for processing requests
	*/
	public function ajax_cookie_scaner()
	{	
		if (!current_user_can('manage_options')) 
		{
		    wp_die(__('You do not have sufficient permission to perform this operation', 'cookie-law-info'));
		}
		check_ajax_referer('cli_cookie_scaner','security');
		$out=array(
			'response'=>false,
			'message'=>__('Unable to handle your request.','cookie-law-info'),
		);
		if(isset($_POST['cli_scaner_action']))
		{
			$cli_scan_action=$_POST['cli_scaner_action'];
			$allowed_actions=array('get_pages','scan_pages','stop_scan','import_now','check_api','report_now');
			if(in_array($cli_scan_action,$allowed_actions) && method_exists($this,$cli_scan_action))
			{
				$out=$this->{$cli_scan_action}();
			}
		}
		echo json_encode($out);
		exit();
	}

	/*
	* Send Cookie serve API un avaialable report
	*
	*
	*/
	public function report_now()
	{
		$to="support@webtoffee.com";
		$sub="Cookie server API unavailable.";
		$msg="Cookie serve API is down. <br /> Site URL: ".site_url();

		$cli_activation_status=get_option(CLI_ACTIVATION_ID.'_activation_status');
		if($cli_activation_status) //if activated then send user registration email
		{
			$reg_email=get_option(CLI_ACTIVATION_ID.'_email');
			$msg.="<br /> Registered email: ".$reg_email;
		}

		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($to,$sub,$msg,$headers);
	}

	/*
	* Cookie serve API is avaialable or not
	*
	*/
	public function check_api()
	{
		$error_head='<h3 style="color:#333;">'.__('Sorry...','cookie-law-info').'</h3>';
		$report_now='&nbsp; <a class="button-primary cli_scanner_send_report">Yes</a>&nbsp;<a class="button-secondary cli_scanner_not_send_report">No</a>';
		$out=array(
			'message'=>$error_head.__("Cookie Scanner API is not available now. Please try again later. <br />Do you want to report this to developer and get notified?","cookie-law-info").$report_now,
			'response'=>false,
		);
		//cookie serve API
        include( plugin_dir_path( __FILE__ ).'class-cookie-serve.php');
        $cookie_serve_api=new Cookie_Law_Info_Cookie_Serve_Api();
        if($cookie_serve_api->check_server())
        {
        	$out['response']=true;
        	$out['message']=__("Success","cookie-law-info");
        }
		return $out;
	}

	/*
	*
	*	Stop scanning (Ajax-main)
	*/
	public function stop_scan()
	{
		$scan_id=(int) isset($_POST['scan_id']) ? $_POST['scan_id'] : 0;
		$data_arr=array('status'=>3); //updating scan status to stopped
		$this->updateScanEntry($data_arr,$scan_id);
		$cookies=$this->get_scan_cookies($scan_id,0,1); //we just need total so `limit` argument is set as one
		update_option('CLI_BYPASS',0);
		$out=array(
			'log'=>array(),
			'scan_id'=>$scan_id,
			'total'=>$cookies['total']
		);
		return $out;
	}

	/*
	*
	*	Import Cookies (Ajax-main)
	*/
	public function import_now()
	{
		$out=array(
			'response'=>false,
			'scan_id'=>$scan_id,
			'message'=>__('Unable to handle your request','cookie-law-info'),
		);
		if(!current_user_can('manage_options'))
		{
			$out['message']=__('You do not have sufficient permissions to access this page.', 'cookie-law-info');
			return $out;
		}
		$deleted=0;
		$skipped=0;
		$added=0;
		$scan_id=(int) isset($_POST['scan_id']) ? $_POST['scan_id'] : 0;
		$import_option=(int) isset($_POST['import_option']) ? $_POST['import_option'] : 2;
		if($scan_id>0)
		{
			$cookies=$this->get_scan_cookies($scan_id,0,-1); // taking cookies
			if($cookies['total']>0)
			{
				if($import_option==1) //replace old (Delete all old cookies)
				{
					$all_cookies= get_posts(array('post_type'=>CLI_POST_TYPE,'numberposts'=>-1) );
					foreach($all_cookies as $cookie) 
					{
						$deleted++;
						wp_delete_post($cookie->ID,true);
					}
				}
				foreach($cookies['data'] as $cookie)
				{
					
					$skip=false;
					if($import_option==2) //merge - skip the insertion of existing cookies
					{
						$existing_cookie=get_posts(array('name' =>$cookie['cookie_id'],'post_type' =>CLI_POST_TYPE));
						if(!empty($existing_cookie))
						{	
							$cli_post=$existing_cookie[0];
							if(empty($cli_post->post_content))
							{	
								$post_data = array(
									'ID'           => $cli_post->ID,
									'post_content' => $cookie['description'],
								);
								wp_update_post( $post_data );
							}
							$skipped++;
							$skip=true;	
						}
					}
					if($skip===false) //adding new cookies
					{
						$added++;
						$cookie_data = array(
			                'post_type' => CLI_POST_TYPE,
			                'post_title' => $cookie['cookie_id'],
			                'post_content' =>$cookie['description'],
			                'post_status' => 'publish',
			                'ping_status' => 'closed',
			                'post_excerpt' => $cookie['cookie_id'],
			                'post_author' => 1,
			            );
			            $post_id = wp_insert_post($cookie_data);
			            update_post_meta($post_id, '_cli_cookie_type',$cookie['type']);
			            update_post_meta($post_id, '_cli_cookie_duration',$cookie['expiry']);
			            update_post_meta($post_id, '_cli_cookie_sensitivity','non-necessary');
			            update_post_meta($post_id, '_cli_cookie_slugid',$cookie['cookie_id']);
			            update_post_meta($post_id, '_cli_cookie_headscript_meta', "");
			            update_post_meta($post_id, '_cli_cookie_bodyscript_meta', "");
						wp_set_object_terms($post_id, array($cookie['category']), 'cookielawinfo-category', true);
						
						// Import Categories 
						$category = get_term_by('name', $cookie['category'], 'cookielawinfo-category');
						// Check if category exist
						if($category && is_object($category))
						{	
							
							$category_id=$category->term_id;
							$category_description=$category->description;
							// Check if catgory has description
							if(is_null($category_description))
							{	
								wp_update_term($category_id, 'cookielawinfo-category', array(
									'description' => $cookie['cli_cookie_category_description'],
								));
							}
						
						}
					}
				}
				
				//preparing response message based on choosed option
				$out_message=$added.' '.__('cookies added.','cookie-law-info');
				if($import_option==2) //merge
				{
					$out_message.=' '.$skipped.' '.__('cookies skipped.','cookie-law-info');
				}
				if($import_option==1) //replace old
				{
					$out_message.=' '.$deleted.' '.__('cookies deleted.','cookie-law-info');
				}
				$out['response']=true;
				$out['message']=$out_message;
			}else
			{
				$out['response']=false;
				$out['message']=__('No cookies found','cookie-law-info');
			}
		}
		return $out;
	}


	/*
	*
	*	Scan pages for cookies (Ajax-main)
	*/
	public function scan_pages()
	{
		global $wpdb;
		$mxdata=$this->scan_page_mxdata;  //do not increase the value more than 5 because cookieserve api will not accept more than 5 urls per request 
		$offset=(int) isset($_POST['offset']) ? $_POST['offset'] : 0;
		$scan_id=(int) isset($_POST['scan_id']) ? $_POST['scan_id'] : 0;
		$total=(int) isset($_POST['total']) ? $_POST['total'] : 0;
		$new_offset=$offset+$mxdata;
		$out=array(
			'log'=>array(),
			'offset'=>$new_offset,
			'scan_id'=>$scan_id,
			'total'=>$total,
			'total_scanned'=>0,
			'total_cookies'=>0,
			'response'=>true,
			'continue'=>true,
		);
		$data_arr=array('current_action'=>'scan_pages','current_offset'=>$offset);
		if($new_offset>=$total)
		{
			$out['continue']=false;
			$data_arr['status']=2; //setting finished status
		}else
		{
			$data_arr['status']=1; //status uncompleted
		}
		$this->updateScanEntry($data_arr,$scan_id);
		$out=$this->scan_urls($scan_id,$offset,$mxdata,$out);

		//just give list of cookies
		$cookies_list=$this->get_scan_cookies($scan_id,0,1);
		$out['total_cookies']=$cookies_list['total'];
		return $out;
	}

	/*
	*
	*	Taking public pages of the website (Ajax-main)
	*/
	public function get_pages()
	{
		global $wpdb;
		$post_table=$wpdb->prefix."posts";
		$mxdata=$this->fetch_page_mxdata;
		//taking query params
		$offset=(int) isset($_POST['offset']) ? $_POST['offset'] : 0;
		//$limit=(int) isset($_POST['limit']) ? $_POST['limit'] : $mxdata;
		$scan_id=(int) isset($_POST['scan_id']) ? $_POST['scan_id'] : 0;
		$total=(int) isset($_POST['total']) ? $_POST['total'] : 0;
		$wt_cli_site_host = $this->wt_cli_get_host( get_site_url() );
		$out=array(
			'log'=>array(),
			'total'=>$total,
			'offset'=>$offset,
			'limit'=>$mxdata,
			'scan_id'=>$scan_id,
			'response'=>true,
		);		
		//taking post types
		$post_types=get_post_types(array(
	    	'public'=>true,
	    ));
	    unset($post_types['attachment']);
	    unset($post_types['revision']);
	    unset($post_types['custom_css']);
	    unset($post_types['customize_changeset']);
	    unset($post_types['user_request']);
		//generating sql conditions
		$sql=" FROM $post_table WHERE post_type IN('".implode("','",$post_types)."') AND post_status='publish'";

		if($total==0) //may be this is first time
		{
			//taking total
			$total_rows=$wpdb->get_row("SELECT COUNT(ID) AS ttnum".$sql,ARRAY_A);
			$total=$total_rows ? $total_rows['ttnum']+1 : 1; //always add 1 becuase home url is there
			$out['total'] = apply_filters( 'wt_cli_cookie_scanner_urls', $total);
		}
		if($scan_id==0) //first scan, create scan entry and add home url
		{
			$scan_id=$this->createScanEntry($total);
			$out['scan_id']=$scan_id;
			$out['log'][]=get_home_url();
            $this->insertUrl($scan_id,get_home_url());
		}


		//creating sql for fetching data
		$sql="SELECT post_name,post_title,post_type,ID".$sql." ORDER BY post_type='page' DESC LIMIT $offset,$mxdata";
		$data=$wpdb->get_results($sql,ARRAY_A);
		if(!empty($data))
		{
            foreach($data as $value) 
            {
				$permalink=get_permalink($value['ID']);
				$currrent_url_host = $this->wt_cli_get_host($permalink);

                if( ( $this->filter_url($permalink) ) && ( $currrent_url_host == $wt_cli_site_host ) )
                {	
                	$out['log'][]=$permalink;
                	$this->insertUrl($scan_id,$permalink);
                }else
                {
                	$out['total']=$out['total']-1;
                } 
            }
        }
        //saving current action status
		$data_arr=array('current_action'=>'get_pages','current_offset'=>$offset,'status'=>1,'total_url'=>$out['total']);
		$this->updateScanEntry($data_arr,$scan_id);
		
	    return $out;
	}
	/*
	*
	* Return site host name 
	* @return string
	* @since 2.2.4
	*/
	private function wt_cli_get_host($url)
	{
		$site_host = '';
		$parsed_url = parse_url($url);
		$site_host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
		return $site_host;
	}
	/*
	*
	* Filtering non html URLS
	* @return boolean
	*/
	private function filter_url($permalink)
	{
		$url_arr=explode("/",$permalink);
		$end=trim(end($url_arr));
		if($end!="")
		{
			$url_end_arr=explode(".",$end);
			if(count($url_end_arr)>1)
			{
				$end_end=trim(end($url_end_arr));
				if($end_end!="")
				{
					$allowed=array('html','htm','shtml','php');
					if(!in_array($end_end,$allowed))
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	/*
	*
	*
	* Scan URLS (Ajax-sub)
	*/
	private function scan_urls($scan_id,$offset,$limit,$out)
	{
		global $wpdb;
		$out_log=array();
		$url_table=$wpdb->prefix.$this->url_tb;
		$sql="SELECT id_cli_cookie_scan_url,url FROM `$url_table` WHERE id_cli_cookie_scan=$scan_id ORDER BY id_cli_cookie_scan_url ASC LIMIT $offset,$limit"; // AND scanned=0
		$data=$wpdb->get_results($sql,ARRAY_A);
		if(!empty($data))
		{
            $data_for_api=array(); //data for API request
            $data_for_db=array(); //data for insert into db
            $url_id_arr=array();
            foreach($data as $v)
            {
            	$data_for_api[]=$v['url'];
            	$data_for_db[$v['url']]=$v['id_cli_cookie_scan_url'];
            	$url_id_arr[]=$v['id_cli_cookie_scan_url'];
            }

            $api_data_chunks=array_chunk($data_for_api,$this->scan_page_mxdata); //!important do not give value more than 5
            
            //cookie serve API
            include( plugin_dir_path( __FILE__ ).'class-cookie-serve.php');
            $cookie_serve_api=new Cookie_Law_Info_Cookie_Serve_Api();
            
            //loop through the chunks becuase cookieserve only accept maximum 5 per request
            foreach($api_data_chunks as $value) 
            {
            	$cookies_arr=$cookie_serve_api->get_cookies($value);
            	if($cookies_arr)
            	{
            		foreach($cookies_arr as $url=>$cookies)
            		{	
						$this->insertCategories($cookies);
            			$out_log=$this->insertCookies($scan_id,$data_for_db[$url],$url,$cookies,$out_log);
            		}
            	}else
            	{
            		
            	}
            }
            //$this->updateUrl($url_id_arr); //updating url as scanned
            $out['total_scanned']=count($data);
        }
        $out['log']=$out_log;       
        return $out;
	}
}
new Cookie_Law_Info_Cookie_Scanner_Ajax();