<?php

namespace WPGMZA;

class ProStoreLocator extends Factory
{
	const SEARCH_AREA_RADIAL	= "radial";
	const SEARCH_AREA_AUTO		= "auto";
	
	protected $map;
	protected $document;
	
	public function __construct($map)
	{
		if(!($map instanceof Map))
			throw new \Exception('Invalid map');
		
		$this->map = $map;
		
		$this->document = new DOMDocument();
		$this->document->loadPHPFile(WPGMZA_PRO_DIR_PATH . 'html/pro-store-locator.html.php');
		
		$strings = array(
			'addressLabel'		=> $this->addressLabel,
			'keywordsLabel'		=> $this->keywordsLabel,
			'defaultAddress'	=> $this->defaultAddress,
			'notFoundMessage'	=> $this->notFoundMessage
		);
		
		$this->document->populate($strings);
		
		if(!$this->keywordSearchEnabled && $element = $this->document->querySelector('div.wpgmza-keywords'))
			$element->remove();
		
		if($this->searchArea == ProStoreLocator::SEARCH_AREA_AUTO && $element = $this->document->querySelector("div.wpgmza-search-area"))
			$element->remove();
		
		
		$this->applyLegacyHTML();
	}
	
	public function __get($name)
	{
		switch($name)
		{
			case "addressLabel":
				
				if(!empty($this->map->store_locator_query_string))
					return __($this->map->store_locator_query_string, 'wp-google-maps');
				
				return __("ZIP / Address:", "wp-google-maps");
				
				break;
			
			case "keywordsLabel":
			
				if(!empty($this->map->store_locator_name_string))
					return __($this->map->store_locator_name_string, 'wp-google-maps');
				
				return __("Title / Description:", "wp-google-maps");
			
				break;
			
			case "defaultAddress":
			
				if(!empty($this->map->store_locator_default_address))
					return __($this->map->store_locator_default_address, 'wp-google-maps');
				
				return "";
			
				break;
				
			case "defaultRadius":
			
				if(!empty($this->map->store_locator_default_radius))
					return $this->map->store_locator_default_radius;
			
				return 2;
			
				break;
			
			case "notFoundMessage":
			
				if(!empty($this->map->store_locator_not_found_message))
					return __($this->map->store_locator_not_found_message, 'wp-google-maps');
				
				return __("No results found in this location. Please try again.", "wp-google-maps");
			
				break;
			
			case "allowUserLocation":
			
				return !empty($this->map->store_locator_use_their_location) && $this->map->store_locator_use_their_location == "1";
			
				break;
			
			case "keywordSearchEnabled":
			
				return $this->map->store_locator_name_search == "1";
			
				break;
			
			case "searchArea":
				
				return $this->map->store_locator_search_area;
				
				break;
			
			case "html":
				
				return $this->document->html;
			
				break;
		}
	}
	
	protected function applyLegacyHTML()
	{
		// Legacy map ID attributes
		$map = array(
			'label.wpgmza-address'			=> "for",
			'input.wpgmza-address'			=> "mid",
			'input.wpgmza-address'			=> "id",
			'label.wpgmza-keywords'			=> "for",
			'input.wpgmza-keywords'			=> "id",
			'label.wpgmza-search-area'		=> "for",
			'select.wpgmza-search-area'		=> "id"
		);
		
		foreach($map as $selector => $attr)
		{
			if(!($element = $this->document->querySelector($selector)))
				continue;
			
			$value = $element->getAttribute($attr);
			$element->setAttribute($attr, $value . $this->map->id);
		}
		
		// Legacy classes
		$classes = array();
	}
}
