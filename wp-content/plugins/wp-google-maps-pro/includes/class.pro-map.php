<?php

namespace WPGMZA;

$dir = wpgmza_get_basic_dir();

wpgmza_require_once($dir . 'includes/class.factory.php');
wpgmza_require_once($dir . 'includes/class.crud.php');
wpgmza_require_once($dir . 'includes/class.map.php');

class ProMap extends Map
{
	protected $_proSettingsMigrator;
	protected $_directionsBox;
	protected $_storeLocator;
	protected $_categoryTree;
	protected $_categoryFilterWidget;
	
	public function __construct($id_or_fields=-1, $overrides=null)
	{
		global $wpgmza;
		
		Map::__construct($id_or_fields, $overrides);
		
		$this->_proSettingsMigrator = new ProSettingsMigrator();
		$this->_proSettingsMigrator->migrateMapSettings($this);
		
		$base = plugin_dir_url( wpgmza_get_basic_dir() . 'wp-google-maps.php' );
		
		// TODO: Check carousel style
		wp_enqueue_script('owl-carousel', 						$base . 'lib/owl.carousel.js', array('jquery'), $wpgmza->getProVersion());
		wp_enqueue_style('owl-carousel_style',					$base . 'lib/owl.carousel.min.css', array(), $wpgmza->getProVersion());
		// wp_enqueue_style('owl-carousel_style_theme',			$base . 'lib/owl.theme.css', array(), $wpgmza->getProVersion());
		wp_enqueue_style('owl-carousel_style__default_theme',	$base . 'lib/owl.theme.default.min.css', array(), $wpgmza->getProVersion());
		
		$base = plugin_dir_url(__DIR__);
		
		wp_enqueue_script('featherlight',				$base . 'lib/featherlight.min.js', array('jquery'), $wpgmza->getProVersion());
		wp_enqueue_style('featherlight',				$base . 'lib/featherlight.min.css', array(), $wpgmza->getProVersion());
		
		// wp_enqueue_script('polylabel',					$base . 'lib/polylabel.js', array(), $wpgmza->getProVersion());
		wp_enqueue_script('polyline',					$base . 'lib/polyline.js', array(), $wpgmza->getProVersion());
		
		$this->_categoryTree = new CategoryTree($this);
		
		//if($this->isStoreLocatorEnabled())
			//$this->_storeLocator = new ProStoreLocator($this);
		
		if($this->isDirectionsEnabled())
			$this->_directionsBox = new DirectionsBox($this);
		
		switch($wpgmza->settings->wpgmza_settings_filterbycat_type)
		{
			case \WPGMZA\CategoryFilterWidget::TYPE_CHECKBOXES:
				$this->_categoryFilterWidget = new \WPGMZA\CategoryFilterWidget\Checkboxes($this);
				break;
			
			default:
				$this->_categoryFilterWidget = new \WPGMZA\CategoryFilterWidget\Dropdown($this);
				break;
		}

		if(is_admin() && !empty($this->fusion))
		{
			add_action('admin_notices', function() {
				
				?>
				
				<div class="notice notice-error is-dismissible">
					<p>
						<?php
						_e('<strong>WP Google Maps:</strong> Fusion Tables are deprecated and will be turned off as of December the 3rd, 2019. Google Maps will no longer support Fusion Tables from this date forward.', 'wp-google-maps');
						?>
					</p>
				</div>
				
				<?php
				
			});
		}
	}
	
	public function __get($name)
	{
		switch($name)
		{
			case "directionsBox":
			case "storeLocator":
			case "categoryTree":
			case "categoryFilterWidget":
				return $this->{"_$name"};
				break;
		}
		
		return Map::__get($name);
	}
	
	public function isStoreLocatorEnabled()
	{
		return $this->store_locator_enabled == "1";
	}
	
	public function isDirectionsEnabled()
	{
		global $wpgmza;
		
		if($this->directions_enabled == "1")
			return true;
		
		if(!empty($this->overrides['enable_directions']))
			return true;
		
		return false;
	}
	
	protected function getMarkersQuery()
	{
		global $wpdb, $WPGMZA_TABLE_NAME_MARKERS;
		
		$columns = array();
		
		foreach($wpdb->get_col("SHOW COLUMNS FROM $WPGMZA_TABLE_NAME_MARKERS") as $name)
		{
			switch($name)
			{
				case "icon":
					$columns[] = ProMarker::getIconSQL($this->id);
					break;
				
				default:
					$columns[] = $name;
					break;
			}
		}
		
		$stmt = $wpdb->prepare("SELECT " . implode(", ", $columns) . " FROM $WPGMZA_TABLE_NAME_MARKERS WHERE approved=1 AND map_id=%d", array($this->id));
		
		return $stmt;
	}
}

add_filter('wpgmza_create_WPGMZA\\Map', function($id_or_fields, $overrides=null) {
	
	return new ProMap($id_or_fields, $overrides);
	
}, 10, 2);
