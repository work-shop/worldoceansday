<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Cookie_Law_Info_Script_Blocker_Frontend
{
    public $version;

    public $parent_obj; //frontend class of the plugin

    public $plugin_obj; //main class file

    public $module_obj; //script blocker module class object file

    public $buffer_type=1;
    private function cli_check_script_blocker_status()
    {   
        $the_options = Cookie_Law_Info::get_settings();
        if ( $the_options['is_on'] == true ) 
        {
            $cli_sb_status=get_option('cli_script_blocker_status');
            if($cli_sb_status === "disabled")
            {
                return false;
            }   
            return true;
        }
        else
        {
            return false;
        }
        
    }
    public function __construct()
    {   
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if ( is_plugin_active( CLI_PLUGIN_BASENAME ) ) {
            $is_script_blocker_enabled=$this->cli_check_script_blocker_status();
            $is_ok = false;     
            if($is_script_blocker_enabled)
            {
                $is_ok = true;
            }   
            if(is_admin() || Cookie_Law_Info::wt_cli_is_disable_blocking()) 
            {
                $is_ok = false;
            }
           
            if($is_ok) 
            {   
                    
                //checking buffer type
                $this->buffer_type=Cookie_Law_Info_Script_Blocker::get_buffer_type();

                add_action('template_redirect',array($this,'wt_start_custom_buffer'), 9999);
                if($this->buffer_type==2)
                {   
                    remove_action('shutdown', 'wp_ob_end_flush_all',1);
                    add_action('shutdown',array($this,'wt_end_custom_buffer'), 1);
                }
            }
            else
            {
                return;
            }
        }
    }
    public function wt_start_custom_buffer() 
    {   
        
        ob_start();
        if($this->buffer_type==1)
        {
            ob_start(array($this,'wt_end_custom_buffer'));
        }
    }
    public function takeBuffer()
    {
        $buffer_option=get_option('cli_sb_buffer_option');
        if($buffer_option)
        {
             $buffer_option=get_option('cli_sb_buffer_option');
        }else
        {
            $buffer_option=Cookie_Law_Info_Script_Blocker::decideBuffer();
            update_option('cli_sb_buffer_option',$buffer_option);
        }
        $buffer = '';
        if($buffer_option==1)
        {
            $level = @ob_get_level();
            for ($i = 0; $i < $level; $i++) 
            {
                $buffer .= @ob_get_clean();
            }
        }else
        {
            $buffer=@ob_get_contents();
            @ob_end_clean();
        }
        return $buffer;
    }
    public function wt_end_custom_buffer($buffer="")
    {
        if($this->buffer_type==2)
        {
            $buffer=$this->takeBuffer();
        }
        try {

            $script_list =  Cookie_Law_Info_Script_Blocker::get_blocker_script_list();
            
            $thirdPartyScript = $this->cli_getScriptPatterns();
            $viewed_cookie="viewed_cookie_policy";
            $scripts='';
            $wt_cli_placeholder = '';
            $scripts = apply_filters('cli_extend_script_blocker',$scripts);
            if($scripts && is_array($scripts)){
                foreach($scripts as $k => $v)
                {    
                    $wt_cli_is_html_element = $wt_cli_html_element = false;
                    if(isset($v['html_elem']))
                    {
                        $wt_cli_is_html_element = true;
                        $wt_cli_html_element=$v['html_elem'];
                    }
                    if(isset($v['placeholder']))
                    {
                        $wt_cli_placeholder = $v['placeholder'];
                    }
                    $thirdPartyScript[$v['id']] = array(
                        'label'     => __( $v['label'], 'cookie-law-info' ),
                        'js_needle' => $v['key'],
                        'js'    => $v['key'][0],
                        'cc'        => $v['category'],
                        'has_s' => false,
                        'has_js' => true,
                        'has_js_needle' =>true,
                        'has_uri' => false,
                        'has_cc' => false,
                        'has_html_elem' => $wt_cli_is_html_element ,
                        'internal_cb' => true,
                        's' => false,
                        'uri' => false ,
                        'html_elem' =>$wt_cli_html_element ,
                        'callback' => 'cli_automateDefault',
                        'placeholder' => $wt_cli_placeholder
                    );
                    $temp_scripts = new stdClass();  
                    $temp_scripts->cliscript_status=$v['status'];
                    $temp_scripts->cliscript_key=$v['id'];
                    $temp_scripts->category_slug=$v['category'];
                    $temp_scripts->loadonstart = 0;
                    $term = get_term_by('slug', $v['category'], 'cookielawinfo-category');
                    if(isset($term))
                    {
                        $term_loadonstart = get_term_meta( $term->term_id, 'CLIloadonstart', true);    
                        $temp_scripts->loadonstart = (int)$term_loadonstart;
                    }
                    array_push($script_list, $temp_scripts);
                }
           }
            if(!empty($script_list))
            {   
                $scripts = array();
                $scripts = apply_filters('wt_cli_add_placeholder',$scripts);
                foreach ($script_list as $k=>$v) 
                {   
                    $placeholder_text = __('Accept consent to view this','cookie-law-info');
                    $scriptkey=$v->cliscript_key;
                    if(isset($thirdPartyScript[$scriptkey])) {   
                        if($v->cliscript_status=='yes') //assign category slug for admin enabled categories
                        {
                            $thirdPartyScript[$scriptkey]['block_script']='true';
                            $thirdPartyScript[$scriptkey]['category']='';
                            $thirdPartyScript[$scriptkey]['category_name']= '';
                            if($v->category_slug!="" || $v->category_slug !== NULL) { //a category assigned
                                if($v->loadonstart==1)
                                {
                                    $thirdPartyScript[$scriptkey]['block_script']='false';
                                }
                                $category = get_term_by('slug', $v->category_slug , 'cookielawinfo-category');
                                $thirdPartyScript[$scriptkey]['category'] = $v->category_slug;
                                $thirdPartyScript[$scriptkey]['category_name']=$category->name;
                                $placeholder_text = sprintf( __("Accept <a class='cli_manage_current_consent'>%s</a> cookies to view the content.","cookie-law-info"),$category->name);

                            }
                            if($scripts && is_array($scripts)) {
                                if(isset($scripts[$scriptkey])) {   
                                    $wt_cli_custom_script = $scripts[$scriptkey];
                                    if(isset($wt_cli_custom_script['placeholder'])) {
                                        $placeholder_text = $wt_cli_custom_script['placeholder'];
                                    }
                                }
                            }
                            $thirdPartyScript[$scriptkey]['placeholder'] = $placeholder_text;  
                        }
                        else //only codes that was enabled by admin. Unset other items
                        {
                            unset($thirdPartyScript[$scriptkey]);
                        }  

                    }
                }
                
            }
            else //unable to load cookie table data - May DB error.
            {
                if($this->buffer_type==2)
                {
                    echo $buffer;
                    exit();
                }else
                {
                    return $buffer;
                }
            }

            //  $thirdPartyScript[$key]['check'] = true; - if true  - it will replce the code - it means the code will not render
            foreach ($thirdPartyScript as $k => $v) //only codes that was enabled by admin
            {
                if(isset($v['category']))
                {
                    $category_cookie="cookielawinfo-checkbox-".$v['category'];
                    if(isset($_COOKIE[$category_cookie]) && isset($_COOKIE[$viewed_cookie]))
                    {   
                        if($_COOKIE[$category_cookie] == 'yes' && $_COOKIE[$viewed_cookie] == 'yes')
                        {
                            $thirdPartyScript[$k]['check'] =false; //allowed by user then false
                        }else
                        {
                            $thirdPartyScript[$k]['check'] =true; //not allowed by user then true
                        }
                    }else
                    {
                        $thirdPartyScript[$k]['check'] =true; //default it is true so blocks the code
                    }
                }else
                {
                    $thirdPartyScript[$k]['check'] =false; //not configured from admin then false;
                }
            }
            
            $buffer = $this->cli_beforeAutomate($buffer);
            $parts = $this->cli_getHeadBodyParts($buffer);
            if ($parts) 
            {   
                
                foreach ($thirdPartyScript as $type => $autoData) {
                    if (!isset($autoData['callback'])) {
                        $autoData['callback'] = '_automateDefault';
                    }
                    if (0 === (int) $autoData['check']) {
                        continue;
                    } else {
                        $callback = $autoData['callback'];
        
                        if ($autoData['internal_cb']) {
                            $callback = array($this,$callback);
                        }
                    }
                    // set parameters for preg_replace_callback() callback
                    $parts = call_user_func_array($callback, array($type, $autoData, $parts));
                }
                $buffer = $parts['head'] . $parts['split'] . $parts['body'];
                
            }
            $buffer = $this->cli_afterAutomate($buffer); 
            if($this->buffer_type==2)
            {
                echo $buffer;
                exit();
            }else
            {
                return $buffer;
            }
        } catch (Exception $e) 
        {

            $message = $e->getMessage();
            if ('' !== $message && defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Error: ' . $message . ' in ' . $e->getFile() . ':' . $e->getLine());
            }
            if($this->buffer_type==2)
            {
                echo $buffer;
                exit();
            }else
            {
                return $buffer;
            }
        }
        if($this->buffer_type==2)
        {
            echo $buffer;
            exit();
        }else
        {
            return $buffer;
        }
    }

    public function cli_defineRegex() 
    {
        return array('_regexParts' => array(
                '-lookbehind_img' => '(?<!src=")',
                '-lookbehind_link' => '(?<!href=")',
                '-lookbehind_link_img' => '(?<!href=")(?<!src=")',
                '-lookbehind_shortcode' => '(?<!])',
                '-lookbehind_after_body' => '(?<=\<body\>)',
                '-lookahead_body_end' => '(?=.*\</body\>)',
                '-lookahead_head_end' => '(?=.*\</head\>)',
                'random_chars' => '[^\s\["\']+',
                'src_scheme_www' => '(?:https?://|//)?(?:[www\.]{4})?'
            ),
            '_regexPatternScriptBasic' => '\<script' .
            '.+?' .
            '\</script\>',
            '_regexPatternScriptTagOpen' => '\<script[^\>]*?\>',
            '_regexPatternScriptTagClose' => '\</script\>',
            '_regexPatternScriptAllAdvanced' => '\<script' .
            '[^>]*?' .
            '\>' .
            '(' .
            '(?!\</script\>)' .
            '.*?' .
            ')' .
            '?' .
            '\</script\>',
            '_regexPatternScriptHasNeedle' => '\<script' .
            '[^>]*?' .
            '\>' .
            '(?!\</script>)' .
            '[^<]*' .
            '%s' .
            '[^<]*' .
            '\</script\>',
            '_regexPatternScriptSrc' => '\<script' .
            '[^>]+?' .
            'src=' .
            '("|\')' .
            '(' .
            '(https?:)?' .
            '//(?:[www\.]{4})?' .
            '%s' .
            '%s' .
            '[^\s"\']*?' .
            ')' .
            '("|\')' .
            '[^>]*' .
            '\>' .
            '[^<]*' .
            '\</script\>',
            '_regexPatternIframeBasic' => '\<iframe' .
            '.+?' .
            '\</iframe\>',
            '_regexPatternIframe' => '\<iframe' .
            '[^>]+?' .
            'src=' .
            '("|\')' .
            '(' .
            '(https?://|//)?' .
            '(?:[www\.]{4})?' .
            '%s' .
            '%s' .
            '[^"\']*?' .
            ')' .
            '("|\')' .
            '[^>]*' .
            '\>' .
            '(?:' .
            '(?!\<iframe).*?' .
            ')' .
            '\</iframe\>',
            '_regexPatternHtmlElemWithAttr' => '\<%s' .
            '[^>]+?' .
            '%s=' .
            '(?:"|\')' .
            '(?:' .
            '%s' .
            '%s' .
            '[^"\']*?' .
            ')' .
            '(?:"|\')' .
            '[^>]*' .
            '(?:' .
            '\>' .
            '(' .
            '(?!\<%s).*?' .
            ')' .
            '\</%s\>' .
            '|' .
            '/\>' .
            ')',
            '_regexPatternHtmlElemWithAttrTypeA' => '\<%s' .
            '[^>]+?' .
            '%s=' .
            '(?:"|\')' .
            '(?:' .
            '%s' .
            '%s' .
            '[^"\']*?' .
            ')' .
            '(?:"|\')' .
            '[^>]*' .
            '(?:' .
            '\>' .
            ')',
            
        );
    }

    public function cli_beforeAutomate($content) 
    {
        
        $textarr = wp_html_split($content);
        $regex_patterns = $this->cli_defineRegex();
        $_regexPatternScriptTagOpen = $regex_patterns['_regexPatternScriptTagOpen'];
        $_regexPatternScriptTagClose = $regex_patterns['_regexPatternScriptTagClose'];
        $changed = false;
        $replacePairs = array("\r\n" => '_RNL_', "\n" => '_NL_', '<' => '_LT_');
        $c = count($textarr);
        $is_script=false;
        foreach ($replacePairs as $needle => $replace) {
            foreach ($textarr as $i => $html) {
                if (preg_match("#^$_regexPatternScriptTagOpen#", $textarr[$i], $m)) {
                    if (false !== strpos($textarr[$i+1], $needle)) {
                        $textarr[$i+1] = str_replace($needle, $replace, $textarr[$i+1]);
                        $changed = true;
                    }

                    if ('<' === $needle && $needle === $textarr[$i + 2][0] && '</script>' !== $textarr[$i + 2]) {
                        $textarr[$i + 2] = preg_replace('#\<(?!/script\>)#', $replace, $textarr[$i + 2]);
                    }
                }
            }
        }
        if ($changed) {
            $content = implode($textarr);
        }
        unset($textarr);
       
        return $content;
    }

    public function cli_getHeadBodyParts($buffer) {

        $parts = array(
            'head' => '',
            'body' => '',
            'split' => ''
        );
        $pattern = '#\</head\>[^<]*\<body[^\>]*?\>#';

        if (preg_match($pattern, $buffer, $m)) {
            
            $splitted = preg_split($pattern, $buffer);
            if (2 !== count($splitted)) {
                throw new RuntimeException('Could not split content in <head> and <body> parts.');
            }
            $parts['head'] = $splitted[0];
            $parts['body'] = $splitted[1];
            $parts['split'] = $m[0];
            unset($splitted);
            return $parts;
           
        }
        
        return false;
    }

    public function cli_afterAutomate($content) {
        
        return str_replace(array('_RNL_', '_NL_', '_LT_'), array("\r\n", "\n", "<"), $content);
    }

    public function cli_getScriptPatterns() 
    {

        $thirdPartyScript = array();


        $thirdPartyScript['googleanalytics'] = array(
            'label' => __('Google Analytics', 'cookie-law-info'),
            's' => 'google-analytics.com',
            'js' => 'www.google-analytics.com/analytics.js',
            'js_needle' => array('www.google-analytics.com/analytics.js',
                'google-analytics.com/ga.js',
                'stats.g.doubleclick.net/dc.js',
                'window.ga=window.ga',
                '_getTracker',
                '__gaTracker',
                'GoogleAnalyticsObject'
            ),
            'cc' => 'analytical'
        );

        $thirdPartyScript['facebook_pixel'] = array(
            'label' => __('Facebook Pixel Code', 'cookie-law-info'),
            'js' => 'connect.facebook.net/en_US/fbevents.js',
            'js_needle' =>array('connect.facebook.net/en_US/fbevents.js',
                'fbq',
                'fjs',
                'facebook-jssdk'
            ),
            'cc' => 'analytical',
            'html_elem' => array(
                'name' => 'img',
                'attr' => 'src:facebook.com/tr'
            )
        );

        $thirdPartyScript['google_tag_manager'] = array(
            'label'     => __( 'Google Tag Manager', 'cookie-law-info' ),
            's'         => 'www.googletagmanager.com/ns.html?id=GTM-',
            'js'        => 'googletagmanager.com/gtag/js',
            'js_needle' => array( 'www.googletagmanager.com/gtm' ),
            'cc'        => 'analytical'
        );
        
        $thirdPartyScript['hotjar'] = array(
            'label'     => __( 'Hotjar', 'cookie-law-info' ),
            'js'        => false,
            'js_needle' => array( 'static.hotjar.com/c/hotjar-'),
            'cc'        => 'analytical'
        );

        $thirdPartyScript['google_publisher_tag'] = array(
            'label'     => __( 'Google Publisher Tag', 'cookie-law-info' ),
            'js'        =>array( 'www.googletagservices.com/tag/js/gpt.js','www.googleadservices.com/pagead/conversion.js'),
            'js_needle' => array('googletag.pubads','googletag.enableServices','googletag.display','www.googletagservices.com/tag/js/gpt.js','www.googleadservices.com/pagead/conversion.js'),
            'cc'        => 'advertising',
            'html_elem' => array(
                array('name' => 'img',
                'attr' => 'src:pubads.g.doubleclick.net/gampad'),
                array('name' => 'img',
                'attr' => 'src:googleads.g.doubleclick.net/pagead')
            )
        );

        $thirdPartyScript['youtube_embed'] = array(
            'label'     => __( 'Youtube embed', 'cookie-law-info' ),
            'js'        => 'www.youtube.com/player_api',
            'js_needle' => array('www.youtube.com/player_api','onYouTubePlayerAPIReady','YT.Player','onYouTubeIframeAPIReady','www.youtube.com/iframe_api'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:www.youtube.com/embed'),
                array('name' => 'iframe',
                'attr' => 'src:youtu.be'),
                array('name' => 'object',
                'attr' => 'data:www.youtube.com/embed'),
                array('name' => 'embed',
                'attr' => 'src:www.youtube.com/embed'),
                array('name' => 'img',
                'attr' => 'src:www.youtube.com/embed'),
            )
        );

        $thirdPartyScript['vimeo_embed'] = array(
            'label'     => __( 'Vimeo embed', 'cookie-law-info' ),
            'js'        => 'player.vimeo.com/api/player.js',
            'js_needle' => array('www.vimeo.com/api/oembed','player.vimeo.com/api/player.js','Vimeo.Player','new Player'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:player.vimeo.com/video')
            )
        );
        $thirdPartyScript['libsyn_embed'] = array(
            'label'     => __( 'Libsyn', 'cookie-law-info' ),
            'js'        => 'cdn.embed.ly/player-0.0.12.min.js',
            'js_needle' => array('html5-player.libsyn.com/embed'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:html5-player.libsyn.com')
            )
        );
        $thirdPartyScript['google_maps'] = array(
            'label'     => __( 'Google maps', 'cookie-law-info' ),
            'js'        => 'maps.googleapis.com/maps/api',
            'js_needle' => array('maps.googleapis.com/maps/api','google.map','initMap'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:www.google.com/maps/embed'),
                array('name' => 'iframe',
                'attr' => 'src:maps.google.com/maps')
            )
        );

        $thirdPartyScript['addthis_widget'] = array(
            'label'     => __( 'Addthis widget', 'cookie-law-info' ),
            'js'        => 's7.addthis.com/js',
            'js_needle' => array('addthis_widget'),
            'cc'        => 'social-media',
        );

        $thirdPartyScript['sharethis_widget'] = array(
            'label'     => __( 'Sharethis widget', 'cookie-law-info' ),
            'js'        => 'platform-api.sharethis.com/js/sharethis.js',
            'js_needle' => array('sharethis.js'),
            'cc'        => 'social-media',
        );

        $thirdPartyScript['twitter_widget'] = array(
            'label'     => __( 'Twitter widget', 'cookie-law-info' ),
            'js'        => 'platform.twitter.com/widgets.js',
            'js_needle' => array('platform.twitter.com/widgets.js','twitter-wjs','twttr.widgets','twttr.events','twttr.ready','window.twttr'),
            'cc'        => 'social-media',
        );


        $thirdPartyScript['soundcloud_embed'] = array(
            'label'     => __( 'Soundcloud embed', 'cookie-law-info' ),
            'js'        => 'connect.soundcloud.com',
            'js_needle' => array('SC.initialize','SC.get','SC.connectCallback','SC.connect','SC.put','SC.stream','SC.Recorder','SC.upload','SC.oEmbed','soundcloud.com'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:w.soundcloud.com/player'),
                array('name' => 'iframe',
                'attr' => 'src:api.soundcloud.com')
            )
        );

        $thirdPartyScript['slideshare_embed'] = array(
            'label'     => __( 'Slideshare embed', 'cookie-law-info' ),
            'js'        => 'www.slideshare.net/api/oembed',
            'js_needle' => array('www.slideshare.net/api/oembed'),
            'cc'        => 'other',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:www.slideshare.net/slideshow')
            )
        );

        $thirdPartyScript['linkedin_widget'] = array(
            'label'     => __( 'Linkedin widget/Analytics', 'cookie-law-info' ),
            'js'        => 'platform.linkedin.com/in.js',
            'js_needle' => array('platform.linkedin.com/in.js','snap.licdn.com/li.lms-analytics/insight.min.js','_linkedin_partner_id'),
            'cc'        => 'social-media',
            'html_elem' => array(
                array('name' => 'img',
                'attr' => 'src:dc.ads.linkedin.com/collect/')
            )
        );

        $thirdPartyScript['instagram_embed'] = array(
            'label'     => __( 'Instagram embed', 'cookie-law-info' ),
            'js'        => 'www.instagram.com/embed.js',
            'js_needle' => array('www.instagram.com/embed.js','api.instagram.com/oembed'),
            'cc'        => 'social-media',
            'html_elem' => array(
                array('name' => 'iframe',
                'attr' => 'src:www.instagram.com/p')
            )
        );

        $thirdPartyScript['pinterest'] = array(
            'label'     => __( 'Pinterest widget', 'cookie-law-info' ),
            'js'        => 'assets.pinterest.com/js/pinit.js',
            'js_needle' => array('assets.pinterest.com/js/pinit.js'),
            'cc'        => 'social-media',
        );

        foreach ($thirdPartyScript as $key => $data) 
        {

            if (!is_string($key)) {
                throw new Exception(sprintf(__("Invalid index found in the thirdparties array. Index should be of type 'string'. Index found: %d.", 'cookie-law-info'), $key));
                break;
            }

            $s = $label = $js = $jsNeedle = $uri = $cb = $htmlElem = null;
            $hasJs = $hasJsNeedle = $hasUri = false;

            $defaultCallback = '_automate' . ucfirst($key);


            $defaultCallbackExist = function_exists($defaultCallback);

            $thirdPartyScript[$key]['has_s'] = false;
            $thirdPartyScript[$key]['has_js'] = false;
            $thirdPartyScript[$key]['has_js_needle'] = false;
            $thirdPartyScript[$key]['has_uri'] = false;
            $thirdPartyScript[$key]['has_cc'] = false;
            $thirdPartyScript[$key]['has_html_elem'] = false;
            $thirdPartyScript[$key]['internal_cb'] = false;

            if (!isset($data['label'])) {
                $label = ucfirst($key);
                $thirdPartyScript[$key]['label'] = $label;
            } elseif (is_string($data['label'])) 
            {
                $label = sanitize_text_field($data['label']);
                $thirdPartyScript[$key]['label'] = $label;
            }

            if (!isset($data['s'])) {
                $thirdPartyScript[$key]['s'] = $s;
            } elseif (is_string($data['s'])) {
                $s = sanitize_text_field($data['s']);
                $thirdPartyScript[$key]['s'] = $s;
                $thirdPartyScript[$key]['has_s'] = true;
            } elseif (is_array($data['s'])) {
                foreach ($data['s'] as $k => $v) {
                    if (is_string($v)) {
                        $thirdPartyScript[$key]['s'][$k] = sanitize_text_field($v);
                        $has_s = true;
                    } else {
                        $thirdPartyScript[$key]['s'] = $s;
                        $has_s = false;
                        break;
                    }
                }
                $thirdPartyScript[$key]['has_s'] = $has_s;
            }

            if (!isset($data['js'])) {
                $thirdPartyScript[$key]['js'] = $js;
            } elseif (is_string($data['js'])) {
                $js = sanitize_text_field($data['js']);
                $thirdPartyScript[$key]['js'] = $js;
                $thirdPartyScript[$key]['has_js'] = true;
            } elseif (is_array($data['js'])) {
                foreach ($data['js'] as $k => $v) {
                    if (is_string($v)) {
                        $thirdPartyScript[$key]['js'][$k] = sanitize_text_field($v);
                        $hasJs = true;
                    } else {
                        $thirdPartyScript[$key]['js'] = $js;
                        $hasJs = false;
                        break;
                    }
                }
                $thirdPartyScript[$key]['has_js'] = $hasJs;
            }

            if (!isset($data['js_needle'])) {
                $thirdPartyScript[$key]['js_needle'] = $jsNeedle;
            } elseif (is_string($data['js_needle'])) {
                $jsNeedle = sanitize_text_field($data['js_needle']);
                $thirdPartyScript[$key]['js_needle'] = $jsNeedle;
                $thirdPartyScript[$key]['has_js_needle'] = true;
            } elseif (is_array($data['js_needle'])) {
                foreach ($data['js_needle'] as $k => $v) {
                    if (is_string($v)) {
                        $thirdPartyScript[$key]['js_needle'][$k] = sanitize_text_field($v);
                        $hasJsNeedle = true;
                    } else {
                        $thirdPartyScript[$key]['js_needle'] = $jsNeedle;
                        $hasJsNeedle = false;
                        break;
                    }
                }
                $thirdPartyScript[$key]['has_js_needle'] = $hasJsNeedle;
            }

            if (!isset($data['uri'])) {
                $thirdPartyScript[$key]['uri'] = $uri;
            } elseif (is_string($data['uri'])) {
                $uri = esc_url_raw($data['uri'], array('http', 'https'));
                $thirdPartyScript[$key]['uri'] = $uri;
                $thirdPartyScript[$key]['has_uri'] = true;
            } elseif (is_array($data['uri'])) {
                foreach ($data['uri'] as $k => $v) {
                    if (is_string($v)) {
                        $thirdPartyScript[$key]['uri'][$k] = esc_url_raw($v, array('http', 'https'));
                        $hasUri = true;
                    } else {
                        $thirdPartyScript[$key]['uri'] = $uri;
                        $hasUri = false;
                        break;
                    }
                }
                $thirdPartyScript[$key]['has_uri'] = $hasUri;
            }

            if (isset($data['callback']) && is_string($data['callback']) && !empty($data['callback'])) {
                $cb = trim($data['callback']);
            } elseif (isset($data['callback']) && is_array($data['callback']) && 2 === count($data['callback'])) {
                $cbMethod = trim($data['callback'][1]);
                $data['callback'][1] = $cbMethod;
                $cb = & $data['callback'];
            } elseif (!isset($data['callback']) && $defaultCallbackExist) {
                $cb = $defaultCallback;
            } else {
                $cb = 'cli_automateDefault';
            }

            if (!isset($data['cc'])) {
                $thirdPartyScript[$key]['cc'] = 'other';
            } elseif (is_string($data['cc'])) {
                $thirdPartyScript[$key]['has_cc'] = true;
                $cc = sanitize_title($data['cc']);
                if (!$this->cli_isAllowedCookieCategory($cc)) {
                    $thirdPartyScript[$key]['cc'] = 'other';
                } else {
                    $thirdPartyScript[$key]['cc'] = $cc;
                }
            }

            if(isset($data['html_elem'])) 
            {
                if(is_array($data['html_elem']) && isset($data['html_elem'][0])) //multiple html elements
                {
                    $thirdPartyScript[$key]['html_elem']=array();
                    for($i=0; $i<count($data['html_elem']); $i++)
                    {
                        $this->processHTMLelm($data['html_elem'][$i],$thirdPartyScript[$key],$i); //$data['html_elem'], $thirdPartyScript[$key]
                    }
                }else
                {
                    $thirdPartyScript[$key]['html_elem']=array();
                    $this->processHTMLelm($data['html_elem'],$thirdPartyScript[$key],0); //$data['html_elem'], $thirdPartyScript[$key]
                }

            } else {
                $thirdPartyScript[$key]['html_elem'] = $htmlElem;
            }

            if(method_exists($this,$cb)) 
            {
                $thirdPartyScript[$key]['internal_cb'] = true;
            }
            $thirdPartyScript[$key]['callback'] = $cb;
        }
        return $thirdPartyScript;
    }
    public function processHTMLelm(&$data,&$thirdPartyScript,$i) //$data['html_elem'], $thirdPartyScript[$key]
    {
        $thirdPartyScript['html_elem'][$i]=array(

        );
        if (!isset($data['name'])) 
        {
            $thirdPartyScript['html_elem'][$i]['name'] = null;
        }elseif (isset($data['name']) && !is_string($data['name']))
        {
            $thirdPartyScript['html_elem'][$i]['name'] = null;
        } elseif (!isset($data['attr'])) 
        {
            $thirdPartyScript['html_elem'][$i]['attr'] = null;
        } elseif (isset($data['attr']) && !is_string($data['attr'])) 
        {
            $thirdPartyScript['html_elem'][$i]['attr'] = null;
        } elseif (isset($data['attr'])) {
            $pos = strpos($data['attr'], ':');
            if (false === $pos || $pos < 1) {
                $thirdPartyScript['html_elem'][$i]['attr'] = null;
            }
        }
        if (null !== $data['name']) 
        {
            $thirdPartyScript['html_elem'][$i]['name'] = sanitize_key($data['name']);
        }
        if (null !== $data['attr']) 
        {
            $attr = trim($data['attr']);
            $thirdPartyScript['html_elem'][$i]['attr'] =$attr;
            $attrArr = explode(':', $attr);
            $k = sanitize_key($attrArr[0]);
            $v = sanitize_html_class($attrArr[1]);
            //$thirdPartyScript['html_elem'][$i]['attr'] = "$k:$v";
            $thirdPartyScript['has_html_elem'] = true;
        }
    }
    public function cli_isAllowedCookieCategory() {

        return array(
            'functional',
            'analytical',
            'social-media',
            'advertising',
            'other'
        );
    }

    public function cli_automateDefault($type = null, $autoData = array(), $parts = array()) {
        
        $patterns = array();
        $hasS = $autoData['has_s'];
        $hasJs = $autoData['has_js'];
        $hasJsNeedle = $autoData['has_js_needle'];
        $hasUri = $autoData['has_uri'];
        $hasHtmlElem = $autoData['has_html_elem'];

        $regex = $this->cli_defineRegex();

        if ($hasUri) {
            $uri = $autoData['uri'];
            
            $uriPattTmpl = $regex['_regexParts']['-lookbehind_link_img'] . 'https?://(?:[www\.]{4})?%s';
            foreach ((array) $uri as $u) {
                $url = $this->cli_getUriWithoutSchemaSubdomain($u);
                $url = str_replace('*', $regex['_regexParts']['random_chars'], $url);
                $escapedUri = $this->cli_escapeRegexChars($url);
                $patt = sprintf($uriPattTmpl, $escapedUri);
                $patterns[] = $patt;
            }
        }

        if ($hasS) {
            $s = $autoData['s'];
            foreach ((array) $s as $term) {
                $cleanUri = $this->cli_getCleanUri($term, true);
                $subdmain = ( '' !== $cleanUri && '.' === $cleanUri[0] ) ? '[^.]+?' : '';
                $escapedUri = $this->cli_escapeRegexChars($cleanUri);
                $patt = sprintf($regex['_regexPatternIframe'], $subdmain, $escapedUri);
                $patterns[] = $patt;
            }
        }

        if ($hasJs) {
            $js = $autoData['js'];
            foreach ((array) $js as $script) {
                $hasPluginUri = false;
                $cleanUri = $this->cli_getCleanUri($script, true);
                 $allowedLocations = array('plugin' => 'wp-content/plugins', 'theme' => 'wp-content/themes');

                if ('' !== $cleanUri && !empty($allowedLocations) && preg_match('#^' . join('|', $allowedLocations) . '#', $cleanUri)) {
                    $hasPluginUri = true;
                    $uriBegin = trailingslashit($this->cli_getCleanUri(home_url(add_query_arg(NULL, NULL))));
        
                } elseif ('' !== $cleanUri && '.' === $cleanUri[0]) {
                    $uriBegin = '[^.]+?';
                } else {
                    $uriBegin = '';
                }

                $escapedUri = $this->cli_escapeRegexChars($cleanUri);
                if ($hasPluginUri) {
                    $uriBegin = $this->cli_escapeRegexChars($uriBegin);
                }

                $patt = sprintf($regex['_regexPatternScriptSrc'], $uriBegin, $escapedUri);
                $patterns[] = $patt;
            }
        }

        if ($hasJsNeedle) {
            $jsNeedle = $autoData['js_needle'];
            foreach ((array) $jsNeedle as $needle) {
                $escaped = $this->cli_escapeRegexChars($needle);
                $patt = sprintf($regex['_regexPatternScriptHasNeedle'], $escaped);
                $patterns[] = $patt;
            }
        }

        if($hasHtmlElem) 
        {   
            
            for($j=0; $j<count($autoData['html_elem']); $j++)
            {
                $htmlElemAttr = explode(':', $autoData['html_elem'][$j]['attr']);
                $htmlElemName = $this->cli_escapeRegexChars($autoData['html_elem'][$j]['name']);
                $htmlElemAttrName = $this->cli_escapeRegexChars($htmlElemAttr[0]);
                $htmlElemAttrValue = $this->cli_escapeRegexChars($htmlElemAttr[1]);
                $prefix='';
                if(($htmlElemAttrName=='src') || ($htmlElemAttrName=='data' && $htmlElemName=='object'))
                {
                    $prefix=$regex['_regexParts']['src_scheme_www'];
                }
                if(($htmlElemName=='img') || ($htmlElemName=='embed'))
                {
                    $patterns[] = sprintf($regex['_regexPatternHtmlElemWithAttrTypeA'], $htmlElemName, $htmlElemAttrName, $prefix, $htmlElemAttrValue);
                }
                else {
                    $patterns[] = sprintf($regex['_regexPatternHtmlElemWithAttr'], $htmlElemName, $htmlElemAttrName, $prefix, $htmlElemAttrValue, $htmlElemName, $htmlElemName);
                }
                
            }
            
        }
        return $this->wt_cli_prepare_script($patterns, '', $type, $parts,$autoData);
    }

    public function cli_getUriWithoutSchemaSubdomain($uri = '', $subdomain = 'www') {

        $uri = preg_replace("#(https?://|//|$subdomain\.)#", '', $uri);
        return ( null === $uri ) ? '' : $uri;
    }

    public function cli_automate($patterns = '', $modifiers = '', $type = null, $autoData = array(), $parts = array()) {
        
        $action = 'erase';
        switch ($action) {
            case 'erase':
            case 'erase-all':
                return $this->cli_erase($patterns, $modifiers, $type, $parts,$autoData);
                break;
            default:
                throw new Exception(sprintf(__("Action is unknown.", 'cookie-law-info')));
                break;
        }
    }
    public function wt_cli_prepare_script($patterns = '', $modifiers = '', $type = null, $parts = array(),$autoData = array())
    {   
       
        $prefix = '(?:\<!--\s+\[cli_skip]\s+--\>_NL_)?';
        $wrapperPattern = '#' . $prefix . '%s#' . $modifiers;
        $pattern = $replacement = array();
        
        foreach ($patterns as $pttrn) {
            $pattern[] = sprintf($wrapperPattern, $pttrn);
        }
        
        if (!isset($parts['head']) || !isset($parts['body'])) {
            throw new InvalidArgumentException('Parts array is not valid for ' . $type . ': head or body entry not found.');
        }    
        $parts['head'] = $this->script_replace_callback($parts['head'],$pattern,$autoData,'head');
        if (null === $parts['head']) {
            throw new RuntimeException('An error occured calling preg_replace_callback() context head.');
        }

        $prefix = '((?:\<!--\s+\[cli_skip]\s+--\>_NL_)?';
        $suffix = ')';
        $wrapperPattern = '#' . $prefix . '%s' . $suffix . '#' . $modifiers;
        $pattern = $replacement = array();
        foreach ($patterns as $pttrn) {
            $pattern[] = sprintf($wrapperPattern, $pttrn);
        }
        $parts['body'] = $this->script_replace_callback($parts['body'],$pattern,$autoData,'body');
        if (null === $parts['body']) {
            throw new RuntimeException('An error occured calling preg_replace_callback() context body.');
        }

        return $parts;  
    }
    public function script_replace_callback( $html,$pattern,$autoData,$elm_position='head' )
    {   
        

        return preg_replace_callback($pattern,function( $matches ) use ( $autoData, $elm_position ){
            
            $placeholder_text = '';
            $script_cat_slug = $autoData['category'];
            $script_label = $autoData['label'];
            $script_load_on_start =  $autoData['block_script'];
            $script_type = "text/plain";
            $match = $matches[0];
            if(isset($autoData['placeholder']))
            {   
                $placeholder_text = $autoData['placeholder'];
            }
            $wt_cli_replace = 'data-cli-class="cli-blocker-script" data-cli-label="'.$script_label.'"  data-cli-script-type="'.$script_cat_slug.'" data-cli-block="'.$script_load_on_start.'" data-cli-element-position="'.$elm_position.'"';
            
            if( strpos($match, 'data-cli-class') === false ){

                if ( (preg_match('/<iframe.*(src=(?:"|\')(.*)(?:"|\')).*>.*<\/iframe>/i', $match, $element_match)) ||(preg_match('/<object.*(src=\"(.*)\").*>.*<\/object >/', $match, $element_match)) || (preg_match('/<embed.*(src=\"(.*)\").*>/', $match, $element_match)) || (preg_match('/<img.*(src=\"(.*)\").*>/', $match, $element_match)) ) {
                
                
                    $element_src = $element_match[1];
                    $element_modded_src = preg_replace('/(src=)(?:"|\')/',$wt_cli_replace.' data-cli-placeholder="'.$placeholder_text.'" data-cli-src=',$element_src);
                    $match = str_replace($element_src,$element_modded_src,$match);
                   
                        
                }
                else {

                if (preg_match('/<script[^\>](type=(?:"|\')(.*?)(?:"|\')).*?>/', $match) && preg_match('/<script[^\>](type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $match)) {
                    
                    preg_match('/<script[^\>](type=(?:"|\')text\/javascript(.*?)(?:"|\')).*?>/', $match, $output_array);
                    
                    $re = preg_quote($output_array[1],'/');
                    
                    if(!empty($output_array)) {   
                        
                        $match = preg_replace('/' .$re .'/', 'type="'.$script_type.'"'.' '.$wt_cli_replace, $match,1);

                    }
        
                }
                else {
                    
                    $match = str_replace('<script', '<script type="'.$script_type.'"'.' '.$wt_cli_replace, $match);   
                
                }
            }
            }
            return $match;
        }, $html);
    }
    public function cli_escapeRegexChars($str = '') {

        $chars = array('^', '$', '(', ')', '<', '>', '.', '*', '+', '?', '[', '{', '\\', '|');

        foreach ($chars as $k => $char) {
            $chars[$k] = '\\' . $char;
        }

        $replaced = preg_replace('#(' . join('|', $chars) . ')#', '\\\${1}', $str);

        return ( null !== $replaced ) ? $replaced : $str;
    }

    public function cli_getCleanUri($uri = '', $stripSubDomain = false, $subdomain = 'www') {

        if (!is_string($uri))
            return '';

        $regexSubdomain = '';
        if ($stripSubDomain && is_string($subdomain)) {
            $subdomain = trim($subdomain);
            if ('' !== $subdomain) {
                $regexSubdomain = $this->cli_escapeRegexChars("$subdomain.");
            }
        }

        $regex = '^' .
                'https?://' .
                $regexSubdomain .
                '([^/?]+)' .
                '(.*)' .
                '$';

        $uri = preg_replace("#$regex#", '${1}', $uri);
        return ( null === $uri ) ? '' : $uri;
    }
}
new Cookie_Law_Info_Script_Blocker_Frontend($this);