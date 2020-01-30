<?php

namespace WPGMZA;

class MarkerIconPicker extends DOMDocument
{
    public function __construct($options=null)
    {
        DOMDocument::__construct();
        
        $this->loadPHPFile(WPGMZA_PRO_DIR_PATH . 'html/marker-icon-picker.html.php');
        
        $preview = $this->querySelector('.wpgmza-marker-icon-preview');
        $preview->setInlineStyle(
            'background-image',
            "url('" . Marker::DEFAULT_ICON . "')"
        );
        
        if(!empty($options))
        {
            if(is_array($options))
                $options = (array)$options;
            
            $input = $this->querySelector('.wpgmza-marker-icon-url');
            
            if(isset($options['name']))
                $input->setAttribute('name', $options['name']);
            
            if(isset($options['ajaxName']))
                $input->setAttribute('data-ajax-name', $options['ajaxName']);
            
            if(!empty($options['value']))
            {
                $value = $options['value'];
                
                $input->setAttribute('value', $value);
                $preview->setInlineStyle('background-image', "url('$value')");
            }
        }
    }
}