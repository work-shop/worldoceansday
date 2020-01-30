<?php

namespace WPGMZA;

class CategoryTreeNode implements \JsonSerializable
{
	public $id;
	public $category_name	= "";
	public $category_icon	= "";
	public $priority		= 0;
	public $marker_count	= 0;
	
	public $children;
	public $parent;
	
	public function __construct($parent=null)
	{
		$this->children = array();
	}
	
	public function jsonSerialize()
	{
		return array(
			'id' 			=> (int)$this->id,
			'name'			=> $this->category_name,
			'icon'			=> $this->category_icon,
			'priority'		=> (int)$this->priority,
			'children'		=> $this->children,
			'marker_count'	=> (int)$this->marker_count
		);
	}
	
	public function getChildByID($id)
	{
		if($this->id == $id)
			return $this;
		
		foreach($this->children as $child)
		{
			if($result = $child->getChildByID($id))
				return $result;
		}
		
		return null;
	}
	
	public function getDepth()
	{
		$result = 0;
		
		for($node = $this->parent; $node != null; $node = $node->parent)
			$result++;
		
		return $result;
	}
	
	public function getAncestors()
	{
		$result = array();
		
		for($node = $this->parent; $node != null; $node = $node->parent)
			$result[] = $node;
		
		return $result;
	}
	
	public function getDescendants()
	{
		$result = array();
		
		foreach($this->children as $child)
		{
			$result[] = $child;
			$result = array_merge($result, $child->getDescendants());
		}
			
		return $result;
	}
	
	public function getLeafNodes()
	{
		$result = array();
		$descendants = $this->getDescendants();
		
		foreach($descendants as $node)
			if(empty($node->children))
				$result[] = $node;
			
		return $result;
	}
	
	public function toJsTreeStructure()
	{
		$obj = array(
			'id'		=> $this->id
		);
		
		if(!empty($this->category_name))
			$obj['text'] = $this->category_name;
		else if(!empty($this->name))
			$obj['text'] = $this->name;
		
		if(!empty($this->children))
		{
			$obj['children'] = array();
			
			foreach($this->children as $child)
				$obj['children'][] = $child->toJsTreeStructure();
		}
		
		return $obj;
	}
}
