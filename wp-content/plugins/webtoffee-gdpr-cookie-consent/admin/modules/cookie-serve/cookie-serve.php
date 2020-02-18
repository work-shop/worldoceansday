<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cookie_Law_Info_Cookie_Serve
{
	/**
	 * Initialization
	 *
	 * @since 2.1.3
	 **/
	public static function init() 
	{
		
		/*Using a high value for priority to ensure the function 
		runs after any other added to the same action hook.
		*/
		add_action('http_api_curl',array( __CLASS__,'wt_custom_curl_timeout'), 9999, 1);
		add_filter('http_request_timeout',array( __CLASS__,'wt_custom_http_request_timeout'),9999);
		add_filter('http_request_args',array( __CLASS__,'wt_custom_http_request_args'), 9999, 1);

	}


	/**
	 * Setting a custom timeout value for cURL.
	 *
	 **/ 
	public static function wt_custom_curl_timeout( $handle )
	{
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
		curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
	}
	
	/**
	 * Setting custom timeout for the HTTP request
	 *
	 **/
	public static function wt_custom_http_request_timeout( $timeout_value ) 
	{
		return 30; // 30 seconds. Too much for production, only for dev.
	}

	/**
	 * Setting custom timeout in HTTP request args
	 *
	 **/
	public static function wt_custom_http_request_args($r)
	{
		$r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
		return $r;
	}

	/*
	* scan cookies
	*/
	public static function get_cookies()
	{
		$url = 'http://www.cookieserve.com/get_cookies';
        $array_with_parameters = array('url' => get_home_url(),);
        $data = wp_remote_post($url, array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode($array_with_parameters),
            'method' => 'POST'
        ));       
        $body = wp_remote_retrieve_body($data);       
        return json_decode($body);
	}

}
Cookie_Law_Info_Cookie_Serve::init();