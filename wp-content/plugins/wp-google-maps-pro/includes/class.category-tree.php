<?php

namespace WPGMZA;

require_once(plugin_dir_path(__FILE__) . 'class.category-tree-node.php');

class CategoryTree extends CategoryTreeNode
{
	public function __construct($map=null)
	{
		global $wpdb;
		global $wpgmza;
		global $WPGMZA_TABLE_NAME_MARKERS;
		global $WPGMZA_TABLE_NAME_CATEGORIES;
		global $WPGMZA_TABLE_NAME_MARKERS_HAS_CATEGORIES;
		global $WPGMZA_TABLE_NAME_CATEGORY_MAPS;
		
		CategoryTreeNode::__construct();
		
		$this->map = $map;
		
		$this->id = "0";
		$this->name = apply_filters('wpgmza_all_categories_text', __('All', 'wp-google-maps'));
		
		$this->category_name = $this->name;
		
		// Build the tree
		$qstr = "SELECT * FROM $WPGMZA_TABLE_NAME_CATEGORIES
			
			WHERE active = 0
			
			" . ($map == null ? '' : "
			
				AND $WPGMZA_TABLE_NAME_CATEGORIES.id IN
				(
					SELECT cat_id FROM $WPGMZA_TABLE_NAME_CATEGORY_MAPS
					WHERE map_id = 0
					OR
					map_id = %d
				)
				
			
			") . "
			
			ORDER BY " . \WPGMZA\Category::getOrderBy();
		
		$params = array();
		if($map)
			$params[] = $map->id;
		
		$qstr = apply_filters('wpgmza_category_tree_query_string', $qstr);
		$params = apply_filters('wpgmza_category_tree_query_params', $params);
		
		if(!empty($params))
			$stmt = $wpdb->prepare($qstr, $params);
		else
			$stmt = $qstr;
		
		/*echo "<pre>";
		var_dump($stmt);
		exit;*/
		
		$categoryData = $wpdb->get_results($stmt);
		
		$nodesByID = array(
			"0" => $this
		);
		
		// Create nodes
		foreach($categoryData as $obj)
		{
			$node = new CategoryTreeNode();
			
			foreach($obj as $key => $value)
			{
				$node->{$key} = $value;
			}
			
			$nodesByID[$obj->id] = $node;
		}
		
		// Build the structure
		foreach($nodesByID as $id => $node)
		{
			$parentID = $node->parent;
			
			if($node == $this)
				continue;
			
			if(!isset($nodesByID[$parentID]))
			{
				// NB: Temporarily removed, it was firing warnings for categories not on the map
				if($wpgmza->isInDeveloperMode() && !(defined( 'DOING_AJAX' ) && DOING_AJAX))
					trigger_error("Parent category $parentID missing", E_USER_NOTICE);
				
				$node->parent = $this;
			}
			else
			{
				$parent = $nodesByID[$parentID];
				$node->parent = $parent;
			}
			
			$parent->children[] = $node;
		}
		
		foreach($nodesByID as $node)
		{
			if($this->isCircular($node))
			{
				// NB: Move the node to the top of the tree and drop children to isolate recursion
				$node->parent = $this;
				$node->children = array();
				
				trigger_error("Recursion inside category tree (ID {$node->id})", E_USER_WARNING);
			}
		}
		
		foreach($nodesByID as $node)
		{
			$node->marker_count = $this->getMarkerCount($node);
		}
	}
	
	private function isCircular($target)
	{
		for($node = $target->parent; $node != null; $node = $node->parent)
		{
			if($node->id == $target->id)
				return true;
		}
		
		return false;
	}
	
	private function getMarkerCount($node)
	{
		global $wpdb;
		global $wpgmza;
		global $WPGMZA_TABLE_NAME_MARKERS;
		global $WPGMZA_TABLE_NAME_CATEGORIES;
		global $WPGMZA_TABLE_NAME_MARKERS_HAS_CATEGORIES;
		global $WPGMZA_TABLE_NAME_CATEGORY_MAPS;
		
		$params = array();
		
		$categories = array_merge( array($node), $node->getDescendants() );
		$category_ids = array();
		
		foreach($categories as $category)
			$category_ids []= $category->id;
		
		$imploded = implode(',', $category_ids);
		
		if($this->map)
		{
			$map_id_clause = "AND map_id = %d";
			$params []= $this->map->id;
		}
		else
			$map_id_clause = "";
		
		$qstr = "
			SELECT COUNT(DISTINCT marker_id) 
			FROM $WPGMZA_TABLE_NAME_MARKERS_HAS_CATEGORIES
			WHERE category_id IN ($imploded)
			AND marker_id IN (
				SELECT id
				FROM $WPGMZA_TABLE_NAME_MARKERS
				WHERE approved = 1
				$map_id_clause
			)
		";
		
		if(!empty($params))
			$stmt = $wpdb->prepare($qstr, $params);
		else
			$stmt = $qstr;
		
		return $wpdb->get_var($stmt);
	}
}
