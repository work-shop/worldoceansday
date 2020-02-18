<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*
    ===============================================================================

    Copyright 2018 @ WebToffee

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


class Cookie_Law_Info_Shortcode {

    public $version;

    public $parent_obj; //instance of the class that includes this class

    public $plugin_obj;

    public $plugin_name;
    public $enable_shortcode;
	public function __construct($parent_obj)
	{
		$this->version=$parent_obj->version;
        $this->parent_obj=$parent_obj;
        $this->plugin_obj=$parent_obj->plugin_obj;
        $this->plugin_name=$parent_obj->plugin_name;
        
        $this->enable_shortcode=true;
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if ( is_plugin_active( CLI_PLUGIN_BASENAME ) ) {
            $the_options = Cookie_Law_Info::get_settings();
            
            // Shortcodes:
            add_shortcode( 'delete_cookies',array($this,'cookielawinfo_delete_cookies_shortcode')); // a shortcode [delete_cookies (text="Delete Cookies")]
            add_shortcode('cookie_audit',array($this,'cookielawinfo_table_shortcode'));           // a shortcode [cookie_audit style="winter"]
            add_shortcode( 'cookie_audit_category',array($this,'cookielawinfo_category_table_shortcode')); // a shortcode [cookie_audit_category style="winter"]
            add_shortcode( 'cookie_accept',array($this,'cookielawinfo_shortcode_accept_button'));      // a shortcode [cookie_accept (colour="red")]
            add_shortcode( 'cookie_reject',array($this,'cookielawinfo_shortcode_reject_button'));      // a shortcode [cookie_reject (colour="red")]
            add_shortcode( 'cookie_settings',array($this,'cookielawinfo_shortcode_settings_button'));      // a shortcode [cookie_reject (colour="red")]
            add_shortcode( 'cookie_link',array($this,'cookielawinfo_shortcode_more_link'));            // a shortcode [cookie_link]
            add_shortcode( 'cookie_button',array($this,'cookielawinfo_shortcode_main_button'));        // a shortcode [cookie_button]
            add_shortcode( 'cookie_close',array($this,'cookielawinfo_shortcode_close_button'));        // a shortcode [close_button]
            add_shortcode( 'cookie_popup_content',array($this,'cookielawinfo_popup_content_shortcode'));
            add_shortcode('cookie_after_accept',array($this,'cookie_after_accept_shortcode'));
            add_shortcode('user_consent_state',array($this,'user_consent_state_shortcode'));
            add_shortcode('cookie_category',array($this,'cookie_category_shortcode'));
            add_shortcode('webtoffee_powered_by',array($this,'wf_powered_by'));
            add_shortcode('wt_cli_category_widget',array($this,'cookielawinfo_category_widget'));
        }
        
        
        
	}
    /*
    *   Cookie category widget
    *   @since 2.2.3
    */
    public function cookielawinfo_category_widget()
    {   
        $strict_enabled = apply_filters('gdpr_strictly_enabled_category', array('necessary', 'obligatoire'));
        $cookie_list =Cookie_Law_Info_Public::get_cookie_list();
        $the_cookie_list = Cookie_Law_Info_Public::wt_cli_sort_cookies($cookie_list);
        $wt_cli_categories = '';
        $wt_cli_categories .= '<span class="wt-cli-category-widget">';
        foreach ($the_cookie_list as $key => $cookie) 
        { 
            $checked = '';
            if(isset($_COOKIE["cookielawinfo-checkbox-$key"]) && $_COOKIE["cookielawinfo-checkbox-$key"] =='yes')
            {
                $checked = 'checked';  
            }
            if(!isset($_COOKIE["cookielawinfo-checkbox-$key"]) && $cookie['defaultstate']== 'enabled')
            {   
                $checked = 'checked';     
            }
            if(in_array($key, $strict_enabled))
            {   
                $checked = 'checked'.' '.'disabled';  
                
            }
            $wt_cli_categories .= '<span class="wt-cli-form-group wt-cli-custom-checkbox"><input type="checkbox" class="cli-user-preference-checkbox" data-id="checkbox-'.$key.'" id="checkbox-'.$key.'" '.$checked.'><label for="checkbox-'.$key.'">'.$cookie["name"].'</label></span>';
            

        }
        $wt_cli_categories .= '</span>';
        return $wt_cli_categories;
    }
    /*
    *   Powered by WebToffe
    *   @since 2.1.9
    */
    public function wf_powered_by()
    {
        return '<p class="wt-cli-element" style="color:#333; clear:both; font-style:italic; font-size:12px; margin-top:15px;">Powered By <a href="https://www.webtoffee.com/" style="color:#333; font-weight:600; font-size:12px;">WebToffee</a></p>';
    }

    /*
    *   Prints cookie categories and description.
    *   @since 2.1.9
    */
    public function cookie_category_shortcode()
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $html='<div class="wt-cli-element cli_cookie_category_single">';
        $the_cookie_list =Cookie_Law_Info_Public::get_cookie_list();
        foreach($the_cookie_list as $key=>$cookie) 
        {
           $html.='<div class="cli_cookie_category_single"><h5 class="cli_cookie_category_single_hd">'.$cookie['name'].'</h5><div class="cli_cookie_category_single_description">'.do_shortcode(term_description($cookie['term_id'],'cookielawinfo-category')).'</div></div>'; 
        }
        $html.='</div>';
        return $html;
    }
    

    /*
    *   User can manage his current consent. This function is used in [user_consent_state] shortcode
    *   @since 2.1.9
    */
    public function manage_user_consent_jsblock()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('.cli_manage_current_consent').click(function(){
                    jQuery('#cookie-law-info-again').click();
                    setTimeout(function(){
                        jQuery(window).scrollTop(jQuery('#cookie-law-info-bar').offset().top);
                    },1000);
                });
            });
        </script>
        <?php
    }

    /*
    *   Show current user's consent state
    *   @since 2.1.9
    */
    public function user_consent_state_shortcode($atts=array())
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        add_action('wp_footer',array($this,'manage_user_consent_jsblock'),15);

        $html='<div class="wt-cli-element cli_user_consent_state">'.__('Your current state:','cookie-law-info');
        if(isset($_COOKIE["viewed_cookie_policy"])) //consent given by user
        {
            if($_COOKIE["viewed_cookie_policy"]=='yes')
            {
                $the_cookie_list =Cookie_Law_Info_Public::get_cookie_list();
                $allowed_arr=array();          
                $not_allowed_arr=array();
                $not_allowed_txt=''; 
                $allowed_txt='';         
                foreach($the_cookie_list as $key=>$cookie) 
                {
                    $cookie_key="cookielawinfo-checkbox-$key";
                    $cookie_name=$cookie['name'];
                    if(!isset($_COOKIE[$cookie_key]))
                    {
                        $not_allowed_arr[]=$cookie_name;
                    }else
                    {
                        if($_COOKIE[$cookie_key]=='no')
                        {
                            $not_allowed_arr[]=$cookie_name;
                        }else
                        {
                            $allowed_arr[]=$cookie_name;
                        }
                    }
                }
                if(count($not_allowed_arr)>0)
                {
                    $not_allowed_txt=__('Not allowed cookies ','cookie-law-info').'('.implode(", ",$not_allowed_arr).').';
                }
                if(count($allowed_arr)>0)
                {
                    $allowed_txt=count($not_allowed_arr)>0 ? __('Allowed cookies ','cookie-law-info') : __('Allow all cookies ','cookie-law-info');
                    $allowed_txt.='('.implode(", ",$allowed_arr).').';
                }
                $html.=__('Consent Accepted. ','cookie-law-info').$allowed_txt.' '.$not_allowed_txt;
            }else
            {
                $html.=__('Consent rejected.','cookie-law-info');
            }
        }else //no conset given
        {
            $html.=__('No consent given.','cookie-law-info');
        }
        $html.=' <a class="cli_manage_current_consent" style="cursor:pointer;">'.__('Manage your consent.','cookie-law-info').'</a> </div>';
        return $html;
    }

    /*
    *   Add content after accepting the cookie notice. Category wise checking allowed
    *   @params category: category slug (Main language)
    *   @params condition: and/or   In the case of multiple categories, default `and`
    *   Usage : 
                Inside post editor
                [cookie_after_accept] ...Your content goes here...  [/cookie_after_accept]
                [cookie_after_accept category="non-necessary"] ...Your content goes here...  [/cookie_after_accept]
                [cookie_after_accept category="non-necessary, analytical" condition="or"] ...Your content goes here...  [/cookie_after_accept]

                Inside template
                <?php echo do_shortcode('...shortcode goes here...'); ?>
    */
    public function cookie_after_accept_shortcode($atts=array(),$content='')
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $atts=shortcode_atts( array(
            'category' =>'',
            'condition'=>'and'
        ), $atts );
        $ok=0;
        //accepted
        if(isset($_COOKIE["viewed_cookie_policy"]) && $_COOKIE["viewed_cookie_policy"] == 'yes')
        {
            if(trim($atts['category'])=="")
            {
                $ok=1;
            }else
            {
               $cat_arr=explode(",",$atts['category']);
               $check=0;
               foreach($cat_arr as $value) 
               {
                  $value=trim($value);
                  if(isset($_COOKIE["cookielawinfo-checkbox-$value"]) && $_COOKIE["cookielawinfo-checkbox-$value"]== 'yes')
                  {
                    $check++; 
                  }
               }
               //all accepted
               if($atts['condition']=='and' && $check==count($cat_arr))
               {
                    $ok=1;
               }

               //any one accepted
               if($atts['condition']=='or' && $check>0)
               {
                    $ok=1;
               }
            }
        }
        if($ok==0)
        {
            $content='';
        }
        return $content;
    }

    /**
     A shortcode that outputs a link which will delete the cookie used to track
     whether or not a vistor has dismissed the header message (i.e. so it doesn't
     keep on showing on all pages)

     Usage: [delete_cookies]
            [delete_cookies linktext="delete cookies"]
     
     N.B. This shortcut does not block cookies, or delete any other cookies!
    */
    public function cookielawinfo_delete_cookies_shortcode($atts) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        extract(shortcode_atts( array(
            'text' => __('Delete Cookies', 'cookie-law-info'),
        ), $atts ) );
        return "<a class='cookielawinfo-cookie-delete wt-cli-element'>{$text}</a>";
    }


    /**
     A nice shortcode to output a table of cookies you have saved, output in ascending
     alphabetical order. If there are no cookie records found a single empty row is shown.
     You can customise the 'not shown' message (see commented code below)

     N.B. This only shows the information you entered on the "cookie" admin page, it
     does not necessarily mean you comply with the cookie law. It is up to you, or
     the website owner, to make sure you have conducted an appropriate cookie audit
     and are informing website visitors of the actual cookies that are being stored.

     Usage:                 [cookie_audit]
                            [cookie_audit style="winter"]
                            [cookie_audit not_shown_message="No records found"]
                            [cookie_audit style="winter" not_shown_message="Not found"]

     Styles included:       simple, classic, modern, rounded, elegant, winter.
                            Default style applied: classic.

     Additional styles:     You can customise the CSS by editing the CSS file itself,
                            included with plugin.
    */
    public function cookielawinfo_table_shortcode( $atts )
    {
        /**
        *  cli_audit_table_on_off: contol the visibility of cookie audit table with/without EU option
        *  @since 2.1.7
        */
        $enable_shortcode=apply_filters('cli_audit_table_on_off',$this->enable_shortcode,'cookie_audit');
        if($enable_shortcode===false)
        {
            return '';
        }
        //table enabled by user so we need to include css file
        if($this->enable_shortcode===false && $enable_shortcode!==false)
        {
            wp_register_style($this->plugin_name.'-table', plugin_dir_url(CLI_PLUGIN_FILENAME) . 'public/css/cookie-law-info-table.css', array(),$this->version, 'all' );
        }

        /** RICHARDASHBY EDIT: only add CSS if table is being used */
        wp_enqueue_style($this->plugin_name.'-table');
        /** END EDIT */
        
        extract( shortcode_atts( array(
            'style' => 'classic',
            'not_shown_message' => '',
            'columns' =>'cookie,type,duration,description',
            'heading' =>'',
            'category'=>''
        ), $atts ) );
        $columns=explode(",",$columns);
        $posts = false;
        $args = array(
            'post_type' => CLI_POST_TYPE,
            /** 28/05/2013: Changing from 10 to 50 to allow longer tables of cookie data */
            'posts_per_page' => 50,
            'tax_query' => array(
             ),
            'order' => 'ASC',
            'orderby' => 'title',
        );

        global $sitepress;
        $is_wpml_enabled=false;
        if(function_exists('icl_object_id') && $sitepress) //wpml enabled
        {
            $is_wpml_enabled=true;
            $args['suppress_filters']=false;
        }
        if(isset($category) && $category!="")
        {
            $wpml_default_lang='en';
            $wpml_current_lang='en';
            $term=false;
            if($is_wpml_enabled) //wpml enabled
            {
                $wpml_default_lang=$sitepress->get_default_language();
                $wpml_current_lang=ICL_LANGUAGE_CODE;
                if($wpml_default_lang!=$wpml_current_lang)//current lang is not default
                {
                    $sitepress->switch_lang($wpml_default_lang); //switching to default lang
                    $term=get_term_by('slug',$category,'cookielawinfo-category'); //original term
                    $sitepress->switch_lang($wpml_current_lang); //revert back to current lang
                    if(!$term) //term not exists in original lang
                    {
                        $term=get_term_by('slug',$category,'cookielawinfo-category'); //current lang term
                    }
                }else
                {
                    $term=get_term_by('slug',$category,'cookielawinfo-category'); 
                }
            }else
            {
                $term=get_term_by('slug',$category,'cookielawinfo-category'); 
            }
            if($term) //corresponding term available with the provided slug
            {
                $args['tax_query'][]=array(
                    'taxonomy' => 'cookielawinfo-category',
                    'terms' =>$term->term_id,
                    'include_children' => false
                );
                $posts = get_posts($args); //only return posts if term available
            }
        }else
        {
            $posts = get_posts($args);
        }
        
        $ret = '<table class="wt-cli-element cookielawinfo-row-cat-table cookielawinfo-' . $style . '"><thead><tr>';
        if(in_array('cookie',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-1">'.__('Cookie', 'cookie-law-info').'</th>';
        }
        if(in_array('type',$columns))
        {       
            $ret .= '<th class="cookielawinfo-column-2">'.__('Type', 'cookie-law-info').'</th>';
        }
        if(in_array('duration',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-3">'.__('Duration', 'cookie-law-info').'</th>';
        }
        if(in_array('description',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-4">'.__('Description', 'cookie-law-info').'</th>';
        }
        $ret = apply_filters('cli_new_columns_to_audit_table',$ret);
        $ret .= '</tr>';
        $ret .= '</thead><tbody>';
        
        if(!$posts) 
        {
            $ret .= '<tr class="cookielawinfo-row"><td colspan="4" class="cookielawinfo-column-empty">' . $not_shown_message . '</td></tr>';
        }

        // Get custom fields:
        if($posts)
        {
            foreach( $posts as $post )
            {
                $custom = get_post_custom( $post->ID );
                $cookie_type = ( isset ( $custom["_cli_cookie_type"][0] ) ) ? $custom["_cli_cookie_type"][0] : '';
                $cookie_duration = ( isset ( $custom["_cli_cookie_duration"][0] ) ) ? $custom["_cli_cookie_duration"][0] : '';
                // Output HTML:
                $ret.='<tr class="cookielawinfo-row">';
                if(in_array('cookie',$columns))
                {
                    $ret .= '<td class="cookielawinfo-column-1">' . $post->post_title . '</td>';
                }
                if(in_array('type',$columns))
                {
                    $ret .= '<td class="cookielawinfo-column-2">' . $cookie_type .'</td>';
                }
                if(in_array('duration',$columns))
                {
                    $ret .= '<td class="cookielawinfo-column-3">' . $cookie_duration .'</td>';
                }
                if(in_array('description',$columns))
                {
                    $ret .= '<td class="cookielawinfo-column-4">' . $post->post_content .'</td>';
                }
                $ret = apply_filters('cli_new_column_values_to_audit_table',$ret, $custom);
                $ret .= '</tr>';
            }
        }
        $ret .= '</tbody></table>';
        return $ret;
    }




    /**
     A nice shortcode to output a table of cookies you have saved, output in ascending
     alphabetical order. If there are no cookie records found a single empty row is shown.
     You can customise the 'not shown' message (see commented code below)

     N.B. This only shows the information you entered on the "cookie" admin page, it
     does not necessarily mean you comply with the cookie law. It is up to you, or
     the website owner, to make sure you have conducted an appropriate cookie audit
     and are informing website visitors of the actual cookies that are being stored.

     Usage:                 [cookie_audit_category]

     Styles included:       simple, classic, modern, rounded, elegant, winter.
                            Default style applied: classic.

     Additional styles:     You can customise the CSS by editing the CSS file itself,
                            included with plugin.
    */
    public function cookielawinfo_category_table_shortcode( $atts ) 
    {
        /**
        *  cli_audit_table_on_off: contol the visibility of cookie audit table with/without EU option
        *  @since 2.1.7
        */
        $enable_shortcode=apply_filters('cli_audit_table_on_off',$this->enable_shortcode,'cookie_audit_category');
        if($enable_shortcode===false)
        {
            return '';
        }
        //table enabled by user so we need to include css file
        if($this->enable_shortcode===false && $enable_shortcode!==false)
        {
            wp_register_style($this->plugin_name.'-table', plugin_dir_url(CLI_PLUGIN_FILENAME) . 'public/css/cookie-law-info-table.css', array(),$this->version, 'all' );
        }


        /** RICHARDASHBY EDIT: only add CSS if table is being used */
        wp_enqueue_style($this->plugin_name.'-table');
        /** END EDIT */
        
        extract( shortcode_atts( array(
            'style' => 'classic',
            'not_shown_message' => '',
            'columns'=>'cookie,type,duration,description',
        ), $atts ) );
        $columns=explode(",",$columns);
        
        $cookie_list = Cookie_Law_Info_Public::get_cookie_list();
        
        $ret = '<table class="wt-cli-element cookielawinfo-' . $style . ' cookielawinfo-row-cat-table"><thead><tr>';
        if(in_array('cookie',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-1">'.__('Cookie', 'cookie-law-info').'</th>';
        }
        if(in_array('type',$columns))
        {       
            $ret .= '<th class="cookielawinfo-column-2">'.__('Type', 'cookie-law-info').'</th>';
        }
        if(in_array('duration',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-3">'.__('Duration', 'cookie-law-info').'</th>';
        }
        if(in_array('description',$columns))
        {
            $ret .= '<th class="cookielawinfo-column-4">'.__('Description', 'cookie-law-info').'</th>';
        }
        $ret .= '</thead><tbody>';
        
        if(empty($cookie_list)) 
        {
            $ret .= '<tr class="cookielawinfo-row"><td colspan="'.count($columns).'" class="cookielawinfo-column-empty">' . $not_shown_message . '</td></tr>';
        }            
        foreach ($cookie_list as $key => $cookie) 
        {                
            unset($cookie['term_id']);
            $ret.= '<tr class="cookielawinfo-row-cat-title"><th colspan="'.count($columns).'" class="cookielawinfo-row-cat-title-head">' . $cookie['name'] . '</th></tr>';
            unset($cookie['name']);
            unset($cookie['loadonstart']);    
            unset($cookie['defaultstate']);      
            foreach ($cookie as $cookie_post) 
            {               
                $re = '';
                $ret.= $this->render_cookie_raw_table($cookie_post,$re,$columns);           
            }
            
        }
        $ret .= '</tbody></table>';
        return $ret;
    }

    public function render_cookie_raw_table($cookie_post=array(),$ret,$columns)
    {
        
        // Get custom fields:
        $custom = get_post_custom( $cookie_post->ID );
        $post = get_post($cookie_post->ID);
        $cookie_type = ( isset ( $custom["_cli_cookie_type"][0] ) ) ? $custom["_cli_cookie_type"][0] : '';
        $cookie_duration = ( isset ( $custom["_cli_cookie_duration"][0] ) ) ? $custom["_cli_cookie_duration"][0] : '';
        // Output HTML:
        $ret .= '<tr class="cookielawinfo-row">';
        if(in_array('cookie',$columns))
        {
            $ret .= '<td class="cookielawinfo-column-1">' . $post->post_title . '</td>';
        }
        if(in_array('type',$columns))
        {
            $ret .= '<td class="cookielawinfo-column-2">' . $cookie_type .'</td>';
        }
        if(in_array('duration',$columns))
        {
            $ret .= '<td class="cookielawinfo-column-3">' . $cookie_duration .'</td>';
        }
        if(in_array('description',$columns))
        {
            $ret .= '<td class="cookielawinfo-column-4">' . $post->post_content .'</td>';
        }
        $ret .= '</tr>';
        return $ret;       
    }




    /**  
    *   Returns HTML for a standard (green, medium sized) 'Accept' button
    */
    public function cookielawinfo_shortcode_accept_button( $atts ) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        extract(shortcode_atts(array(
            'colour' => 'green'
        ), $atts ));
        $defaults =Cookie_Law_Info::get_default_settings('button_1_text');
        $settings = wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);
        return '<a  role="button" tabindex="0" class="wt-cli-element cli_action_button cli-accept-button medium cli-plugin-button ' . $colour . '" data-cli_action="accept" >' . stripslashes( $settings['button_1_text'] ) . '</a>';
    }

    /** Returns HTML for a standard (green, medium sized) 'Reject' button */
    public function cookielawinfo_shortcode_reject_button( $atts ) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $defaults = Cookie_Law_Info::get_default_settings();
        $settings = wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);
        
        $classr = '';
        if($settings['button_3_as_button']) 
        {
            $classr=' class="wt-cli-element' .' '.  $settings['button_3_button_size'] . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header_reject cli_action_button"';
        }
        else 
        {
            $classr=' class="wt-cli-element cookie_action_close_header_reject cli_action_button" '; 
        }

        //adding custom style
        $styles=$this->generateStyle($settings,'button_3_style');
                
        $url_reject = ( $settings['button_3_action'] == "CONSTANT_OPEN_URL" && $settings['button_3_url'] != "#" ) ? "href='$settings[button_3_url]'" : "role='button' tabindex='0'";
        $link_tag = '';
        $link_tag .= ' <a '.$url_reject.' style="'.$styles.'" id="'.Cookie_Law_Info_Public::cookielawinfo_remove_hash($settings['button_3_action']).'" ';
        $link_tag .= ($settings['button_3_new_win'] ) ? ' target="_blank" ' : '' ;
        $link_tag .= $classr . '  data-cli_action="reject">' . stripslashes( $settings['button_3_text'] ) . '</a>';
        return $link_tag;
            
    }

    public function cookielawinfo_shortcode_settings_button( $atts ) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $defaults =Cookie_Law_Info::get_default_settings();
        $settings =wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);
        
        //overriding custom setting @version: 2.1.2
        $settings['button_4_url']="#";
        $settings['button_4_action']='#cookie_action_settings';
        $settings['button_4_new_win']=false;

        $classr = '';
        if( $settings['button_4_as_button'] ) 
        {
            $classr= ' class="wt-cli-element'. ' ' . $settings['button_4_button_size'] . ' cli-plugin-button cli-plugin-main-button cli_settings_button"';
        }
        else 
        {
            $classr= 'class="wt-cli-element cli_settings_button" ';
        }

        //adding custom style
        $styles=$this->generateStyle($settings,'button_4_style');
        $url_s = ( $settings['button_4_action'] == "CONSTANT_OPEN_URL" && $settings['button_4_url'] != "#" ) ? "href='$settings[button_4_url]'" : "role='button' tabindex='0'";
        $link_tag = '';
        $link_tag .= '<a ' . $url_s . ' style="'.$styles.'"';
        $link_tag .= ( $settings['button_4_new_win'] ) ? ' target="_blank" ' : '' ;
        $link_tag .= $classr . ' >' . stripslashes( $settings['button_4_text'] ) . '</a>';
        return $link_tag;           
    }


    /** Returns HTML for a generic button */
    public function cookielawinfo_shortcode_more_link( $atts ) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        return $this->cookielawinfo_shortcode_button_DRY_code('button_2');
    }


    /** Returns HTML for a generic button */
    public function cookielawinfo_shortcode_main_button( $atts ) 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $defaults =Cookie_Law_Info::get_default_settings();            
        $settings = wp_parse_args(Cookie_Law_Info::get_settings(),$defaults);        
        $class = '';
        if($settings['button_1_as_button']) 
        {
            $class = ' class="wt-cli-element'. ' ' . $settings['button_1_button_size'] . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header cli_action_button"';
        }
        else {
            $class = ' class="wt-cli-element cli-plugin-main-button cookie_action_close_header cli_action_button" ' ;
        }
        
        // If is action not URL then don't use URL!
        $url = ( $settings['button_1_action'] == "CONSTANT_OPEN_URL" && $settings['button_1_url'] != "#" ) ? "href='$settings[button_1_url]'" : "role='button' tabindex='0'" ;                  
        
        //adding custom style
        $styles=$this->generateStyle($settings,'button_1_style');  
        $link_tag = '<a '.$url.' style="'.$styles.'" data-cli_action="accept" id="' . Cookie_Law_Info_Public::cookielawinfo_remove_hash ( $settings['button_1_action'] ) . '" ';
        $link_tag .= ( $settings['button_1_new_win'] ) ? ' target="_blank" ' : '' ;
        $link_tag .= $class . ' >' . stripslashes( $settings['button_1_text'] ) . '</a>';
        return $link_tag;
    }

    public function cookielawinfo_shortcode_close_button()
    {        
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $arr = Cookie_Law_Info::get_settings();
        $styles=$this->generateStyle($arr,'button_5_style');
        $txt='';
        return '<a style="'.$styles.'" data-cli_action="accept" class="wt-cli-element cli_cookie_close_button" title="'.__('Close and Accept','cookie-law-info').'">X</a>';
    }
    private function generateStyle($arr,$style_key)
    {
        $styles='';
        if(Cookie_Law_Info_Admin::module_exists('cli-themes') && isset($arr[$style_key]))
        {
           $styles=Cookie_Law_Info_Cli_Themes::create_style_attr($arr[$style_key]);
        }
        return stripslashes($styles);
    }

    /** Returns HTML for a generic button */
    public function cookielawinfo_shortcode_button_DRY_code( $name ) {
        
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $arr = Cookie_Law_Info::get_settings();
        $settings = array();
        $class_name = '';

        //adding custom style
        $styles=$this->generateStyle($arr,$name.'_style'); 
        
        if ( $name == "button_1" ) {
            $settings = array(
                'button_x_text' => stripslashes( $arr['button_1_text'] ),
                'button_x_url' => $arr['button_1_url'],
                'button_x_action' => $arr['button_1_action'],
                
                'button_x_link_colour' => $arr['button_1_link_colour'],
                'button_x_new_win' => $arr['button_1_new_win'],
                'button_x_as_button' => $arr['button_1_as_button'],
                'button_x_button_colour' => $arr['button_1_button_colour'],
                'button_x_button_size' => $arr['button_1_button_size']
            );
            $class_name.= 'wt-cli-element cli-plugin-main-button';
        }
        elseif ( $name == "button_2" ) {
            $settings = array(
                'button_x_text' => stripslashes( $arr['button_2_text'] ),
                'button_x_action' => $arr['button_2_action'],
                
                'button_x_link_colour' => $arr['button_2_link_colour'],
                'button_x_new_win' => $arr['button_2_new_win'],
                'button_x_as_button' => $arr['button_2_as_button'],
                'button_x_button_colour' => $arr['button_2_button_colour'],
                'button_x_button_size' => $arr['button_2_button_size']
            );
            $class_name.= 'wt-cli-element cli-plugin-main-link';
            if($arr['button_2_url_type']=='url')
            {
                $settings['button_x_url']=$arr['button_2_url'];

                /* 
                * @since 2.1.9
                * Checks if user enabled minify bar in the current page 
                */
                if($arr['button_2_hidebar']===true)
                {
                    global $wp;
                    $current_url=home_url(add_query_arg(array(),$wp->request));
                    $btn2_url=$current_url[strlen($current_url)-1]=='/' ? substr($current_url,0,-1) : $current_url;
                    $btn2_url=$arr['button_2_url'][strlen($arr['button_2_url'])-1]=='/' ? substr($arr['button_2_url'],0,-1) : $arr['button_2_url'];
                    if(strpos($btn2_url,$current_url)!==false)
                    { 
                        if($btn2_url!=$current_url)
                        {
                            $qry_var_arr=explode("?",$current_url);
                            $hash_var_arr=explode("#",$current_url);
                            if($qry_var_arr[0]==$btn2_url || $hash_var_arr[0]==$btn2_url)
                            {
                                $class_name.=' cli-minimize-bar';
                            }
                        }else
                        {
                             $class_name.=' cli-minimize-bar';
                        }
                    }
                }
            }else
            {
                $privacy_page_exists=0;
                if($arr['button_2_page']>0) //page choosed
                {
                    $privacy_policy_page=get_post($arr['button_2_page']);                    
                    if($privacy_policy_page instanceof WP_Post)
                    {
                        if($privacy_policy_page->post_status==='publish') 
                        {
                            $privacy_page_exists=1;
                            $settings['button_x_url']=get_page_link($privacy_policy_page);

                            /* 
                            * @since 2.1.9
                            * Checks if user enabled minify bar in the current page 
                            */
                            if($arr['button_2_hidebar']===true)
                            {
                                if(is_page($arr['button_2_page']))
                                {
                                    $class_name.=' cli-minimize-bar';
                                }
                            }
                        }  
                    }
                }
                if($privacy_page_exists==0)
                {
                    return '';   
                }
            }
        }
        
        $settings = apply_filters('wt_readmore_link_settings', $settings);            
        $class = '';
        if($settings['button_x_as_button'] ) 
        {
            $class .= ' class="wt-cli-element' . ' ' . $settings['button_x_button_size'] . ' cli-plugin-button ' . $class_name . '"';
        }
        else {
            $class .= ' class="wt-cli-element' . ' ' . $class_name . '" ' ;
        }
        // If no follow is set set rel="nofollow"
    
        $rel=($arr['button_2_nofollow']) ? "rel=nofollow" : "";
       
        // If is action not URL then don't use URL!
        $url = ( $settings['button_x_action'] == "CONSTANT_OPEN_URL" && $settings['button_x_url'] != "#" ) ? "href='$settings[button_x_url]'" : "role='button' tabindex='0'" ;        
        $link_tag = '<a '.$url .''.$rel.' id="' . Cookie_Law_Info_Public::cookielawinfo_remove_hash ( $settings['button_x_action'] ) . '" style="'.$styles.'"';
        $link_tag .= ( $settings['button_x_new_win'] ) ? ' target="_blank" ' : '' ;
        $link_tag .= $class . ' >' . $settings['button_x_text'] . '</a>';       
        return $link_tag;
    }

    public function cookielawinfo_popup_content_shortcode() 
    {
        if($this->enable_shortcode===false)
        {
            return '';
        }
        $the_options = Cookie_Law_Info::get_settings();        
        if($the_options['is_on'] == true ) 
        {
            $cookie_list =Cookie_Law_Info_Public::get_cookie_list();
            $the_cookie_list = Cookie_Law_Info_Public::wt_cli_sort_cookies($cookie_list);
            $pop_content_html_file=plugin_dir_path(CLI_PLUGIN_FILENAME).'public/views/cookie-law-info_popup_content.php';
            if(file_exists($pop_content_html_file))
            {
                include $pop_content_html_file;
                return $pop_out;

            }
        }
    }
}
new Cookie_Law_Info_Shortcode($this);