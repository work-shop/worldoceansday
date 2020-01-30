<?php

namespace WPGMZA;

class ProRestAPI extends RestAPI
{
	public function __construct()
	{
		RestAPI::__construct();
	}
	
	protected function registerRoutes()
	{
		global $wpgmza;
		
		if(method_exists(get_parent_class($this), 'registerRoutes'))
			RestAPI::registerRoutes(); // Failsafe for basic < 7.11.40 w/Pro >= 7.11.47 in which this method doesn't exist on the parent class
		
		if(!method_exists($this, 'registerRoute'))
			return; // Legacy basic failsafe
		
		$this->registerRoute('/marker-listing/', array(
			'methods'					=> array('GET'),
			'callback' 					=> array($this, 'markerListing'),
			'useCompressedPathVariable' => true
		));
		
		$this->registerRoute('/marker-listing/', array(
			'methods'					=> array('POST'),
			'callback' 					=> array($this, 'markerListing')
		));
		
		$this->registerRoute('/categories/', array(
			'methods'					=> array('GET'),
			'callback'					=> array($this, 'categories')
		));
	}
	
	public function markerListing($request)
	{
		if(preg_match('/base64eJyrVirIKHDOSSwuVrJSCg9w941yjInxTSzKTi3yySwuycxLj4lxL8pMUdJRKi5JLCpRsjLQUcpJzUsvyVCyMgSycxML4oHSVkpGBkq1ABgcGVA/', $_SERVER['REQUEST_URI']))
			sleep(10);
		
		$request = $this->getRequestParameters();
		$map_id = $request['map_id'];
		
		if(RestAPI::isRequestURIUsingCompressedPathVariable())
			$class = '\\' . $request['phpClass'];
		else
			$class = '\\' . stripslashes( $request['phpClass'] );
		
		if(isset($request['overrideMarkerIDs']) && is_string($request['overrideMarkerIDs']))
			$request['overrideMarkerIDs'] = explode(',', $request['overrideMarkerIDs']);
		
		$instance = $class::createInstance($map_id);
		
		if(!($instance instanceof MarkerListing))
			return WP_Error('wpgmza_invalid_datatable_class', 'Specified PHP class must extend WPGMZA\\MarkerListing', array('status' => 403));
		
		$response = $instance->getAjaxResponse($request);
		
		return $response;
	}
	
	public function categories($request)
	{
		$params = $this->getRequestParameters();
		$map = null;
		
		if(!empty($params['filter']))
		{
			if(is_object($params['filter']))
				$filteringParameters = (array)$params['filter'];
			else if(is_array($params['filter']))
				$filteringParameters = $params['filter'];
			else if(is_string($params['filter']))
				$filteringParameters = json_decode( stripslashes($params['filter']) );
			else
				throw new \Exception("Failed to interpret filtering parameters");
			
			if($filteringParameters['map_id'])
				$map = \WPGMZA\Map::createInstance( $filteringParameters['map_id'] );
		}
		
		$categoryTree = new CategoryTree($map);
		return $categoryTree;
	}
}

add_filter('wpgmza_create_WPGMZA\\RestAPI', function() {
	
	return new ProRestAPI();
	
}, 10, 0);
