<?php

namespace WPGMZA;

class MarkerGalleryItem
{
	public $attachment_id;
	public $url;
	public $thumbnail;
	
	public function __construct($data)
	{
		foreach($data as $key => $value)
			$this->{$key} = $value;
		
		if(empty($this->thumbnail))
		{
			$src = wp_get_attachment_image_src($this->attachment_id, 'medium');
			
			if($src)
				$this->thunbnail = $src[0];
			
			if(!$this->thumbnail)
				$this->thumbnail = $this->url;
		}
	}
}
