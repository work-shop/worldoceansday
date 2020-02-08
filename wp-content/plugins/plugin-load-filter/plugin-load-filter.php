<?php
/*
  Plugin Name: plugin load filter
  Description: Dynamically activate the selected plugins for each page. Response will be faster by filtering plugins.
  Version: 3.1.1
  Plugin URI: http://celtislab.net/wp_plugin_load_filter
  Author: enomoto@celtislab
  Author URI: http://celtislab.net/
  License: GPLv2
  Text Domain: plf
  Domain Path: /languages
 */
defined( 'ABSPATH' ) || exit;

/***************************************************************************
 * plugin activation / deactivation / uninstall
 **************************************************************************/
if(is_admin()){ 
    //deactivation
    function plugin_load_filter_deactivation( $network_deactivating ) {
        $act = false;
        if (is_multisite()) {
            if(! $network_deactivating){
                global $wpdb;
                $current_blog_id = get_current_blog_id();
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                foreach ( $blog_ids as $blog_id ) {
                    if($blog_id == $current_blog_id){
                        //current site
                    }
                    else {
                        //other site active check
                        switch_to_blog( $blog_id );
                        if ( is_plugin_active( plugin_basename( __FILE__ )))
                            $act = true;
                    }
                }
                switch_to_blog( $current_blog_id );
            }
        }
        if($act === false){
            flush_rewrite_rules();  //options data 'rewrite_rules' clear for remake.
            if ( file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
                @unlink( WPMU_PLUGIN_DIR . '/plf-filter.php' );
            }
        }
    }
    register_deactivation_hook( __FILE__,   'plugin_load_filter_deactivation' );

    //uninstall
    function plugin_load_filter_uninstall() {
        
        if ( !is_multisite()) {
            delete_option('plf_queryvars');
            delete_option('plf_option' );
        } else {
            global $wpdb;
            $current_blog_id = get_current_blog_id();
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                delete_option('plf_queryvars');
                delete_option('plf_option' );
            }
            switch_to_blog( $current_blog_id );
        }        
        if ( file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
            @unlink( WPMU_PLUGIN_DIR . '/plf-filter.php' );
        }
    }
    register_uninstall_hook(__FILE__, 'plugin_load_filter_uninstall');
    
}

$Plf_setting = new Plf_setting();

class Plf_setting {
    
    private $plugins_inf = '';  //active plugin/module infomation
    private $filter = array();  //filter option data
    private $tab_num = 0;
        
    /***************************************************************************
     * Style Sheet
     **************************************************************************/
    function plf_css() { ?>
    <style type="text/css">
    #plugin-filter-select {margin-top: 12px;}
    #plugin-filter-select p {margin: 1em 0;}
    #page-filter-stat { margin-top: 12px;}
    #activation-table { border: 1px solid #eee;}
    #activation-table th { font-size: 97%; text-align: center;}
    #activation-table td { font-size: 97%;}
    #activation-table input[type=checkbox] {  height: 25px; width: 25px; opacity: 0;}
    thead .plugins-name { background-color: aliceblue;}
    thead .device-type { background-color: oldlace;}
    thead .plugins-name, tbody .plugins-name { min-width: 144px; max-width: 200px; padding: 3px 3px 3px 5px;}
    thead .device-type, tbody .device-type { min-width: 40px; max-width: 40px; padding: 5px 1px 2px;}
    .dashicons-yes:before { font-size: 20px; border: 1px solid #eee; background-color: whitesmoke;} 
    .device-type label { color: whitesmoke; margin-left: -28px; }
    .device-type input[type="checkbox"]:checked + label .dashicons-yes:before { background-color: yellowgreen; }   
    </style>
    <?php }    

    function jquery_tab_css() { ?>
    <style type="text/css">
    .ui-helper-reset { margin: 0; padding: 0; border: 0; outline: 0; line-height: 1.5; text-decoration: none; font-size: 100%; list-style: none; }
    .ui-helper-clearfix:before, .ui-helper-clearfix:after { content: ""; display: table; }
    .ui-helper-clearfix:after { clear: both; }
    .ui-helper-clearfix { zoom: 1; }
    .ui-tabs { position: relative; padding: .2em; zoom: 1; } /* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as "fixed") */
    .ui-tabs .ui-tabs-nav { margin: 1px 8px; padding: .2em .2em; }
    .ui-tabs .ui-tabs-nav li { list-style: none; float: left; position: relative; top: 0; margin: 1px .3em 0 0; border-bottom: 0; padding: 0; white-space: nowrap; }
    .ui-tabs .ui-tabs-nav li a { float: left; text-decoration: none; }
    .ui-tabs .ui-tabs-nav li.ui-tabs-active { margin-bottom: -1px; padding-bottom: 1px; }
    .ui-tabs .ui-tabs-panel { display: block; border-width: 0;  background: none; }
    .ui-tabs .ui-tabs-nav a { margin: 8px 10px; }
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #dddddd; background-color: #f4f4f4; font-weight: bold; color: #0073ea; }
    .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #0073ea; text-decoration: none; }
    .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus,.ui-widget-header .ui-state-focus { border: 1px solid #0073ea; background-color: #0073ea; font-weight: bold; color: #ffffff; }
    .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active { border: 1px solid #dddddd; background-color: #0073ea; font-weight: bold; color: #ffffff; }
    .ui-state-hover a, .ui-state-hover a:hover, .ui-state-hover a:link, .ui-state-hover a:visited { color: #ffffff; text-decoration: none; }
    .ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #ffffff; text-decoration: none; }

    #wrap_registration-table, #wrap_activation-table { overflow:auto; height:600px; position: relative;}
    #registration-table input[type=radio], #registration-table input[type=checkbox], #activation-table input[type=checkbox] { height: 25px; width: 25px; opacity: 0;}
    thead, tbody { display: block;}
    .widefat * { word-wrap: break-word !important;}
    .widefat thead { position:sticky; top:0px; z-index:1;}
    .widefat tr:first-of-type th:first-of-type {position: sticky; left: 0px; text-align: left; background-color: aliceblue; z-index:3;}    
    .widefat th { padding: 8px 2px 8px 6px;}
    .widefat td { padding: 8px 4px;}
    .widefat td:first-of-type { position: sticky; left: 0px; text-align: left; background-color: white;}    
    thead .filter-plugins-name, thead .plugins-name { background-color: aliceblue; }
    thead .urlfilter { background-color: lavenderblush;}
    .filter-none, .filter-admin, .filter-tmpl { background-color: honeydew;}
    thead .device-type { background-color: oldlace;}
    thead .ckbox-type { background-color: lavender;}
    thead .pformat { background-color: honeydew;}
    thead .tmpl-embed { background-color: lightyellow;}
    thead .tmpl-custom { background-color: lavenderblush;}
    thead .filter-plugins-name, tbody .filter-plugins-name { min-width: 260px;}
    thead .plugins-name, tbody .plugins-name { min-width: 180px; max-width: 180px;}
    thead .device-type, tbody .device-type, thead .ckbox-type, tbody .ckbox-type, thead .deny-type, tbody .deny-type { min-width: 40px; max-width: 40px; }
    thead .filter-type, tbody .filter-type { min-width: 56px; max-width: 56px;}
    .urlfilter-description { padding: 0 10px 15px;}
    .filter-description { padding: 0 10px;}
    .exclude-pformat { padding: 5px 0 20px}
    .exclude-pformat label { white-space:nowrap;}
    .exclude-pformat span { margin-right: 12px; }
    .dashicons:before { font-size: 24px; }
    .radio-green label, .radio-red label, .ckbox-type label { color: #ddd; margin-left: -28px; }
    .radio-green input[type="radio"]:checked + label { color: #339966; }
    .radio-red input[type="radio"]:checked + label { color: tomato; }   
    .ckbox-type input[type="checkbox"]:checked + label { color: #339966; }
    .dashicons-dismiss:before { background-color: yellowgreen; font-size: 20px; border-radius: 12px; }    
    .deny-type label { color: yellowgreen; margin-left: -28px; }
    .deny-type input[type="checkbox"]:checked + label { color: tomato; }
    .dashicons-yes:before { font-size: 20px; border: 1px solid #eee; } 
    .device-type label { color: whitesmoke; margin-left: -28px; }
    .device-type input[type="checkbox"]:checked + label .dashicons-yes:before { background-color: yellowgreen; }   
    </style>
    <?php }    

    /***************************************************************************
     * Plugin Load Filter Option Setting
     **************************************************************************/

    public function __construct() {
        
        load_plugin_textdomain('plf', false, basename( dirname( __FILE__ ) ).'/languages' );

        $this->filter = get_option('plf_option');
        if(empty($this->filter['optver']) || $this->filter['optver'] < '2'){
            $this->filter['optver'] = '2';
            //ここにデータフォーマットが変わった場合の変換処理を記述
        }
        
        if(is_admin()) {
            add_action( 'plugins_loaded', array(&$this, 'plf_admin_start'), 9999 );
            add_action( 'admin_init', array(&$this, 'action_posts'));
            add_action( 'add_meta_boxes', array(&$this, 'load_meta_boxes'), 10, 2 );
        }
        add_action( 'wp_ajax_plugin_load_filter', array(&$this, 'plf_ajax_postidfilter'));
    }

    //Plugin Load Filter admin setting start 
    public function plf_admin_start() {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        
        $this->plugins_inf = get_plugins();
        $packplug = array();
        foreach ( $this->plugins_inf as $plugin_key => $a_plugin ) {
            if(is_plugin_inactive( $plugin_key )){
                unset($this->plugins_inf[$plugin_key]);
            }
        }
        //jetpack active module 
        if(method_exists('Jetpack', 'get_module')){
            $modules = Jetpack::get_active_modules();
            $modules = array_diff( $modules, array( 'vaultpress' ) );
            foreach ( $modules as $key => $module_name ) {
                if(!empty($module_name)){
                    $module = Jetpack::get_module( $module_name );
                    if(!empty($module))
                        $this->plugins_inf['jetpack_module/' . $module_name] = $module;
                }
            }
        }
        //celtispack active module 
        if(method_exists('Celtispack', 'get_module')){
            $modules = Celtispack::get_active_modules();
            foreach ( $modules as $key => $module_name ) {
                if(!empty($module_name)){
                    $this->plugins_inf['celtispack_module/' . $module_name] = Celtispack::get_module( $module_name );
                }
            }
        }
        if ( empty( $this->plugins_inf ) ) 
            return;

        add_action('admin_menu', array(&$this, 'plf_option_menu')); 
    }
    
    //Plugins sub menu add
    public function plf_option_menu() {
        $page = add_plugins_page( 'Plugin Load Filter', __('Plugin Load Filter', 'plf'), 'manage_options', 'plugin_load_filter_admin_manage_page', array(&$this,'plf_option_page'));
        add_action('admin_print_scripts-'.$page,  array(&$this, 'plf_scripts'));    
        add_action('admin_print_scripts-'.$page,  array(&$this, 'deploy_mu_plugins'));
    }

    //Plugin Load Filter setting page script 
    function plf_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        add_action( 'admin_head', array(&$this, 'plf_css' ));
        add_action( 'admin_head', array(&$this, 'jquery_tab_css' ));
        add_action( 'admin_footer', array(&$this, 'activetab_script' ));
        add_action( 'admin_notices', array(&$this, 'plf_notice'));       
    }

    //plf-filter.php mu-plugins module set
    public function deploy_mu_plugins() {
        if(wp_mkdir_p( WPMU_PLUGIN_DIR )){
            if ( !file_exists( WPMU_PLUGIN_DIR . "/plf-filter.php" )) { 
                @copy(__DIR__ . '/mu-plugins/plf-filter.php', WPMU_PLUGIN_DIR . '/plf-filter.php');
            }
            else {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                $dp = get_plugin_data( WPMU_PLUGIN_DIR . "/plf-filter.php", false, false );
                $sp = get_plugin_data( __DIR__ . '/mu-plugins/plf-filter.php', false, false );
                if(version_compare( $dp['Version'], $sp['Version'], '!=')){
                    @copy(__DIR__ . '/mu-plugins/plf-filter.php', WPMU_PLUGIN_DIR . '/plf-filter.php');
                }
            }
        }
    }

    //Notice Message display
    public function plf_notice() {
        $notice = get_transient('plf_notice');
        if(!empty($notice)){
            echo "<div class='message error'><p>Plugin Load Filter : $notice</p></div>";
            delete_transient('plf_notice');
        }        
    }
    
    //plugin filter option action request (add, update, delete)
    function action_posts() {
        if (current_user_can( 'activate_plugins' )) {
            if( isset($_POST['edit_regist_filter']) ) {
                if(isset($_POST['plfregist'])){
                    check_admin_referer('plugin_load_filter');
                    //url filter
                    if(isset($_POST['plfurlkey'])){
                        $urlkeys = array_keys($_POST['plfurlkey']);
                        foreach( $urlkeys as $item){
                            $plugins = array();
                            foreach ( $_POST['plfurlkey'][$item] as $p_key => $val ) {
                                if($val == '1')
                                    $plugins[] = $p_key;
                            }
                            $option["plugins"] = implode(",", $plugins);
                            $this->filter['plfurlkey'][$item] = $option;
                        }
                    }
                    //page type filter
                    foreach( array('_admin', '_pagefilter') as $item){
                        $plugins = array();
                        foreach ( $_POST['plfregist'] as $p_key => $val ) {
                            if($val == $item)
                                $plugins[$p_key] = $val;
                        }
                        if($item == '_pagefilter'){
                            //If all modules is specified filter, in some cases you want to deactivate plugin itself.
                            $jbase = $cbase = '';
                            $jall = $call = true;
                            foreach ( $_POST['plfregist'] as $p_key => $val ) {
                                if(strpos($p_key, 'jetpack/') !== false)
                                    $jbase = $p_key;
                                else if(strpos($p_key, 'celtispack/') !== false)
                                    $cbase = $p_key;
                                else if(strpos($p_key, 'jetpack_module/') !== false){
                                    if($val != '_pagefilter' && $val != '_admin'){
                                        $jall = false;
                                    }
                                }
                                else if(strpos($p_key, 'celtispack_module/') !== false){
                                    if($val != '_pagefilter' && $val != '_admin')
                                        $call = false;
                                }
                            }
                            if(!empty($jbase) && $jall === false)
                                unset($plugins[$jbase]);
                            if(!empty($cbase) && $call === false)
                                unset($plugins[$cbase]);
                        }
                        $option["plugins"] = implode(",", array_keys($plugins));
                        $this->filter[$item] = $option;
                    }
                    //exclude option
                    if(isset($_POST['plf_option']['exclude'])){
                        $exclude = array();
                        foreach ( $_POST['plf_option']['exclude'] as $ft => $v ) {
                            if(!empty($v))
                                $exclude[$ft] = true;
                        }
                        $this->filter['exclude'] = $exclude;
                    }
                    else {
                        $this->filter['exclude'] = array();
                    }
                    //url filter keyword option
                    //$items = array( 'amp', 'url_1', 'url_2', 'url_3' );
                    $items = array( 'amp' );
                	foreach ($items as $key) {
                        $url_key = (isset($_POST['plf_option'][$key]))? $_POST['plf_option'][$key] : '';
                        if(empty($url_key) || preg_match('#\A([a-zA-Z0-9_\-/\.\?=%]+)\Z#u', $url_key)){
                            $this->filter['urlkey'][$key] = $url_key;
                        } else {
                            $notice = __('There are invalid characters in Keyword. Use characters include Alphanumeric, Hyphens, Underscores, slash, dot, question, equal, percent sign','plf');
                            set_transient('plf_notice', $notice, 30);
                        }
                    }
                    //trim & 空行 & 重複キー削除を行ってPHP_EOL 区切りの文字列へ戻す
                    $keylist = (!empty($_POST['plf_option']['urlkeylist'])) ? stripslashes_from_strings_only(trim($_POST['plf_option']['urlkeylist'])) : '';
                    $ar_key = (!empty($keylist))? array_filter( array_map("trim", explode(PHP_EOL, $keylist))) : array();
                    if(!empty($ar_key)) {
                        $ar_new = array();
                        foreach ($ar_key as $key) {
                            if(!in_array($key, $ar_new)){
                                if(empty($key) || preg_match('#\A([a-zA-Z0-9_\-/\.\?=%]+)\Z#u', $key)){
                                    $ar_new[] = $key;
                                } else {
                                    $notice = __('There are invalid characters in Keyword. Use characters include Alphanumeric, Hyphens, Underscores, slash, dot, question, equal, percent sign','plf');
                                    set_transient('plf_notice', $notice, 30);
                                }
                            }
                        }
                        $ar_key = $ar_new;
                    }                     
                    $this->filter['urlkeylist'] = (!empty($ar_key))? implode(PHP_EOL, $ar_key) : ''; 
                    
                    update_option('plf_option', $this->filter );
                }
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page'));
                exit;
            }
            elseif( isset($_POST['clear_regist_filter']) ) {
                check_admin_referer('plugin_load_filter');
                if(!empty($this->filter['plfurlkey'])){
                    foreach ($this->filter['plfurlkey'] as $key => $val) {
                        unset($this->filter['plfurlkey'][$key]);
                    }
                }
                foreach( array('_admin', '_pagefilter') as $item){
                    $this->filter[$item] = array();
                }
                $this->filter['exclude'] = array();
                $this->filter['urlkey'] = array();
                $this->filter['urlkeylist'] = '';
                update_option('plf_option', $this->filter );
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page'));
                exit;
            }
            else if(isset($_POST['edit_activate_page_filter']) ) {
                if(isset($_POST['plfactive'])){
                    check_admin_referer('plugin_load_filter');
                    $group = array_keys($_POST['plfactive']);
                    foreach( $group as $item){
                        $plugins = array();
                        foreach ( $_POST['plfactive'][$item] as $p_key => $val ) {
                            if($val == '1')
                                $plugins[] = $p_key;
                        }
                        $option["plugins"] = implode(",", $plugins);
                        $this->filter['group'][$item] = $option;
                    }
                    update_option('plf_option', $this->filter );
                }
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page&action=tab_1'));
                exit;
            } 
            elseif( isset($_POST['clear_activate_page_filter']) ) {
                check_admin_referer('plugin_load_filter');
                $group = array_keys($_POST['plfactive']);
                foreach( $group as $item){
                    $this->filter['group'][$item] = array();
                }
                update_option('plf_option', $this->filter );
                header('Location: ' . admin_url('plugins.php?page=plugin_load_filter_admin_manage_page&action=tab_1'));
                exit;
            }
            if(!empty($_GET['action']) && $_GET['action']=='tab_1') {
                $this->tab_num = 1;
            }
        }
    }

    //Plugin or Module key to name
    // $type : list/smart/tree
    public function pluginkey_to_name( $infkey, $type='list') {

        $name = '';
        if(strpos($infkey, 'jetpack_module/') !== false){
            if(!empty($this->plugins_inf[$infkey]['name'])){
                $m_mark = ($type !== 'list')? '-' : 'Jetpack-';
                if($type === 'smart') {
                    if(empty($this->filter['_pagefilter']['plugins']) || strpos($this->filter['_pagefilter']['plugins'], 'jetpack/') === false)
                        $name = $m_mark . $this->plugins_inf[$infkey]['name'];
                }
                else
                    $name = $m_mark . $this->plugins_inf[$infkey]['name'];
            }
        }
        elseif(strpos($infkey, 'celtispack_module/') !== false){
            if(!empty($this->plugins_inf[$infkey]['Name'])){
                $m_mark = ($type !== 'list')? '-' : 'Celtispack-';
                if($type === 'smart') {
                    if(empty($this->filter['_pagefilter']['plugins']) || strpos($this->filter['_pagefilter']['plugins'], 'celtispack/') === false)
                        $name = $m_mark . $this->plugins_inf[$infkey]['Name'];
                }
                else
                    $name = $m_mark . $this->plugins_inf[$infkey]['Name'];
            }
        }
        else {
            if(!empty($this->plugins_inf[$infkey]['Name']))
                $name = $this->plugins_inf[$infkey]['Name'];
        }
        return($name);
    } 

    //Checkbox
	static function checkbox($name, $value, $label = '') {
        return "<label><input type='checkbox' name='$name' value='1' " . checked( $value, 1, false ).  "/> $label</label>";
	}
	static function altcheckbox($name, $value, $label = '') {
        return "<input type='hidden' name='$name' value='0'><input type='checkbox' name='$name' value='1' " . checked( $value, 1, false ).  "/><label> $label</label>";
	}

    public function plfregist_item($key, $chklist, $filter) {
        $p_name = $this->pluginkey_to_name($key);
        $opt_name = "plfregist[$key]";
        ?>
        <tr id="plfregist_<?php echo $key; ?>">
          <td class="filter-plugins-name"><?php echo $p_name; ?></td>
          <?php
            foreach($chklist as $urlkey){
                $checked = (empty($filter['plfurlkey'][$urlkey]['plugins']) || false === strpos($filter['plfurlkey'][$urlkey]['plugins'], $key))? false : true;
                echo '<td class="deny-type filter-type">' . self::altcheckbox("plfurlkey[$urlkey][$key]", $checked, '<span class="dashicons dashicons-admin-plugins"></span>') . '</td>';
            }
            $radio = '';
            if(!empty($filter['_admin']['plugins']) && false !== strpos($filter['_admin']['plugins'], $key))
                $radio = '_admin';
            else if(!empty($filter['_pagefilter']['plugins']) && false !== strpos($filter['_pagefilter']['plugins'], $key))
                $radio = '_pagefilter';
          ?>
          <td class="radio-green filter-type"><input type="radio" name="<?php echo $opt_name; ?>" value='' <?php checked('', $radio); ?>/><label><span class="dashicons dashicons-admin-plugins"></span></label></td>
          <td class="radio-red filter-type"><input type="radio" name="<?php echo $opt_name; ?>" value="_admin" <?php checked('_admin', $radio); ?>/><label><span class="dashicons dashicons-admin-plugins"></span></label></td>
          <td class="radio-red filter-type"><input type="radio" name="<?php echo $opt_name; ?>" value="_pagefilter" <?php checked('_pagefilter', $radio); ?>/><label><span class="dashicons dashicons-admin-plugins"></span></label></td>
        </tr>
        <?php
    }

    //Filterring plugins select   
    public function plfregist_table($plugins, $filter) {
    ?>
    <div id="wrap_registration-table">        
    <table id="registration-table" class="widefat">
        <thead>
           <?php
            //$items = array( 'amp' => 'AMP filter', 'url_1' => 'URL filter1', 'url_2' => 'URL filter2', 'url_3' => 'URL filter3' );
            $items = array( 'amp' => 'AMP filter' );
            $urlnum = 3;
            foreach ($items as $key => $label) {
                if(!empty($filter['urlkey'][$key]))
                    $urlnum++;
            }
            $urlkey = (!empty($filter['urlkeylist']))? array_filter( array_map("trim", explode(PHP_EOL, $filter['urlkeylist']))) : array();
            $urlnum += count($urlkey);
           ?>
           <tr>
               <th class="filter-plugins-name" rowspan="2"><?php _e('Plugins'); ?></th>
               <th class="urlfilter" colspan="<?php echo $urlnum; ?>" style="font-weight:bold; font-size:smaller;"><?php _e('URL Filter for Expert', 'plf'); ?></th>
               <th class="filter-type filter-tmpl" colspan="3" style="font-weight:bold; font-size:smaller;"><?php _e('Page Type Filter', 'plf'); ?></th>
           </tr>
           <tr>
               <th class="filter-type urlfilter"><span title="REST API filter Keyword : wp-json" style='font-size:smaller'><?php _e('REST API', 'plf'); ?></span></th>
               <th class="filter-type urlfilter"><span title="Heartbeat filter Keyword : admin-ajax(heartbeat)" style='font-size:smaller'><?php _e('Heartbeat', 'plf'); ?></span></th>
               <th class="filter-type urlfilter"><span title="Ajax filter Keyword : admin-ajax" style='font-size:smaller'><?php _e('Ajax', 'plf'); ?></span></th>
               <?php
            	foreach ($items as $key => $label) {
                    if(!empty($filter['urlkey'][$key])){
                        $title = "$label Keyword : " . $filter['urlkey'][$key];
                        echo "<th class='filter-type urlfilter'><span title='$title' style='font-size:smaller'>{$filter['urlkey'][$key]}</span></th>";
                    }
                }
                //汎用URLフィルタータイトル表示                
            	foreach ($urlkey as $key) {
                    $title = "URL filter Keyword : " . $key;
                    echo "<th class='filter-type urlfilter'><span title='$title' style='font-size:smaller'>{$key}</span></th>";
                }
               ?>
               <th class="filter-type filter-none"><span style="font-size:smaller"><?php _e('Normal Mode', 'plf'); ?></span></th>
               <th class="filter-type filter-admin"><span style="font-size:smaller"><?php _e('Admin Page', 'plf'); ?></span></th>
               <th class="filter-type filter-tmpl"><span style="font-size:smaller"><?php _e('Page Type', 'plf'); ?></span></th>
           </tr>
        </thead>
        <tbody class="plugins-table-body">
        <?php
        //plugins filter registoration table
        $plist = array();
        foreach ( $plugins as $p_key => $val ) {
            $name = $this->pluginkey_to_name($p_key);
            if(!empty($name)) 
                $plist[$p_key] = '';
        }
        $jlist = $clist = array();
        foreach ( $plist as $p_key => $val ) {
            if(strpos($p_key, 'jetpack_module/') !== false){
                $jlist[$p_key] = $plist[$p_key];
                unset($plist[$p_key]);
            }
            else if(strpos($p_key, 'celtispack_module/') !== false){
                $clist[$p_key] = $plist[$p_key];
                unset($plist[$p_key]);
            }
        }
        $chklist = array('wp-json','heartbeat','admin-ajax');
        foreach ($items as $key => $label) {
            if(!empty($filter['urlkey'][$key])){
                $chklist[] = $key;
            }
        }
        //汎用URLフィルタータイトル表示                
        foreach ($urlkey as $key) {
            $chklist[] = $key;
        }
        
        foreach ( $plist as $p_key => $val ) {
            $modules = array();
            if(strpos($p_key, 'jetpack/') !== false)
                $modules = $jlist;
            else if(strpos($p_key, 'celtispack/') !== false)
                $modules = $clist;
            else
                $this->plfregist_item($p_key, $chklist, $filter);
            if(!empty($modules)){
                echo "<input type='hidden' name='plfregist[$p_key]' value='_pagefilter'>";
                foreach ( $modules as $m_key => $val) {
                    $this->plfregist_item($m_key, $chklist, $filter);
                }
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    <br />
    <p><strong>[ URL Filter for Expert ]</strong></p>
    <div class="urlfilter-description">
        <p><?php _e('Normally all are active. It is used when there is a plugin that you want to deactive specially for requests such as REST API, Ajax, AMP, etc.', 'plf'); ?></p>
        <p><?php _e('REST API / Ajax / AMP / URL page judgment is done from REQUEST URI with regular expressions including keyword.  <code>(/|&|\.|\?|=)keyword(/|&|\.|\?|=|$)</code>', 'plf'); ?><br />
        <?php _e('AMP / URL Setting the keyword enables the filter. [Valid characters: alphanumeric characters, hyphens (-), underscores (_), slash (/), dot(.), question(?), equal(=), percent sign(%) ]', 'plf'); ?></p>
        <p><span style='background-color: #fbf7dc; padding: 2px;'>
        <?php _e('AMP keyword filter', 'plf')?>    
        </span></p>
        <?php
        $items = array( 'amp' => __('In many cases, it is identified with "amp". (*AMP page display requires AMP plugin or theme)', 'plf') );
        $html = "";
        foreach ($items as $key => $label) {
            $html .= "<div>";
            $val   = (!empty($this->filter['urlkey'][$key]))? $this->filter['urlkey'][$key] : '';
            $html .= "<input id='plf_option[$key]' class='medium-text' type='text' name='plf_option[$key]' value='$val' />";
            $html .= "<label style='margin-left:10px;'>$label</label>";
            $html .= "</div>";
        }
        echo $html;
        $urlkeylist = (!empty($this->filter['urlkeylist']))? esc_textarea($this->filter['urlkeylist']): '';
        ?>
        <p><span style='background-color: #fbf7dc; padding: 2px;'><?php _e('URL keyword filter (Register URL matching judgment keywords separated by line breaks)', 'plf'); ?></span></p>
        <div><textarea name="plf_option[urlkeylist]" rows="10" cols="30" id="urlkeylist" class="medium-text code"><?php echo $urlkeylist; ?></textarea></div>
    </div>
    
    <p><strong>[ Page Type Filter ]</strong></p>
    <div class="filter-description"><p><strong> Normal Mode</strong><br /><?php _e('Plugins load without Page Type Filtering.', 'plf'); ?></p></div>
    <div class="filter-description"><p><strong> Admin Page</strong><br /><?php _e('Plugins to be used only in admin page.', 'plf'); ?></p></div>
    <div class="filter-description"><p><strong> Page Type</strong><br />
        <?php _e('Plugins for selecting whether to activate each Page type or Post.', 'plf'); ?><br />
        <?php _e('Selected page type filter plugins are once blocked, but is activated by "Page Type filter Activation" setting.', 'plf'); ?></p>
        <div class="exclude-pformat">
            <p><strong><?php _e( 'Exclude Post Format Type', 'plf' ); ?></strong><br />
            <?php _e('Choose Post Format Type you are not using. To exclude from Page Type Filter item subject.', 'plf'); ?>
            </p>
          <?php
            $html =  '<div>';
            $pformat = array('image', 'gallery', 'video', 'audio', 'aside', 'status', 'quote', 'link', 'chat' );
            foreach ( $pformat as $type ) {
                $checked = (!empty($this->filter['exclude'][$type]))? $this->filter['exclude'][$type] : false;
                $label = "<span>$type</span>";
                $html .= self::checkbox("plf_option[exclude][$type]", $checked, $label);
            }
            $html .= '</div>';
            echo $html;
          ?>
        </div>
    </div>
    <?php
    }

    //Activate plugins select from Page Filter  
    public function _plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter) {

        $selplugins = array_map("trim", explode(',', $select_cvplugins));
        $devlist = array('desktop','mobile');
        if(in_array( $p_key, $selplugins )){
            $p_name = $this->pluginkey_to_name($p_key);                
            echo "<tr><td class='plugins-name'>$p_name</td>";
            foreach($devlist as $devtype){
                $checked = (empty($filter['group'][$devtype]['plugins']) || false === strpos($filter['group'][$devtype]['plugins'], $p_key))? false : true;
                echo '<td class="device-type">' . self::altcheckbox("plfactive[$devtype][$p_key]", $checked, '<span class="dashicons dashicons-yes"></span>') . '</td>';
            }
            foreach($chklist as $pgtype){
                $checked = (empty($filter['group'][$pgtype]['plugins']) || false === strpos($filter['group'][$pgtype]['plugins'], $p_key))? false : true;
                echo '<td class="ckbox-type">' . self::altcheckbox("plfactive[$pgtype][$p_key]", $checked, '<span class="dashicons dashicons-admin-plugins"></span>') . '</td>';
            }
            echo "</tr>";
        }
    }
    
    public function plfactive_table($plugins, $select_cvplugins, $filter) {
        if(empty($select_cvplugins))
            return;
        
    ?>
    <div id="wrap_activation-table">
    <table id="activation-table" class="widefat">
        <thead>
           <tr><th class="plugins-name"><?php _e('Plugins'); ?></th>
               <th class="device-type"><span title="<?php _e('Desktop Device', 'plf'); ?>" class="dashicons dashicons-desktop"></span><br /><span style="font-size:xx-small">Desktop</span></th>
               <th class="device-type"><span title="<?php _e('Mobile Device', 'plf'); ?>" class="dashicons dashicons-smartphone"></span><br /><span style="font-size:xx-small">Mobile</span></th>
               <th class="ckbox-type"><span title="<?php _e('Home/Front-page', 'plf'); ?>" class="dashicons dashicons-admin-home"></span><br /><span style="font-size:xx-small">Home</span></th>
               <th class="ckbox-type"><span title="<?php _e('Archive page', 'plf'); ?>" class="dashicons dashicons-list-view"></span><br /><span style="font-size:xx-small">Archive</span></th>
               <th class="ckbox-type"><span title="<?php _e('Search page', 'plf'); ?>" class="dashicons dashicons-search"></span><br /><span style="font-size:xx-small">Search</span></th>
               <th class="ckbox-type"><span title="<?php _e('Attachment page', 'plf'); ?>" class="dashicons dashicons-media-default"></span><br /><span style="font-size:xx-small">Attach</span></th>
               <th class="ckbox-type"><span title="<?php _e('Page', 'plf'); ?>" class="dashicons dashicons-admin-page"></span><br /><span style="font-size:xx-small">Page</span></th>
               <th class="ckbox-type pformat"><span title="<?php _e('Post : ', 'plf'); _e('Standard', 'plf'); ?>" class="dashicons dashicons-admin-post"></span><br /><span style="font-size:xx-small">Post</span></th>
               <?php
                $pformat = array('image', 'gallery', 'video', 'audio', 'aside', 'status', 'quote', 'link', 'chat' );
                $exclude = array();
                if(!empty($filter['exclude'])){
                    foreach ( $filter['exclude'] as $type => $v) {
                        if(!empty($v)){
                            $exclude[] = $type;
                        }
                    }
                }
                foreach ( $pformat as $type) {
                    if(!in_array($type, $exclude)){
                        $title = __('Post : ', 'plf') . $type;
                        $icon  = ($type === "link")? "dashicons-admin-links" : "dashicons-format-$type";
                        echo '<th class="ckbox-type pformat"><span title="' . $title . '" class="dashicons ' . $icon .'"></span><br /><span style="font-size:xx-small">' . $type .'</span></th>';
                    }
                }
                if(function_exists('is_embed')){
                    $title = __('WordPress Embed Content Card (API)', 'plf');
                    echo "<th class='ckbox-type tmpl-embed'><span title='$title' style='font-size:xx-small'>Embed Content</span></th>";
                }
                $post_types = get_post_types( array('public' => true, '_builtin' => false) );                    
                foreach ( $post_types as $post_type ) {
                    if(!empty($post_type)){
                       $title = __('Custom Post : ', 'plf') . $post_type;
                       echo "<th class='ckbox-type tmpl-custom'><span title='$title' style='font-size:xx-small'>$post_type</span></th>";
                    }
                }
               ?>
           </tr>
        </thead>
        <tbody class="plugins-table-body">
        <?php
        $nplugins = $jmodules = $cmodules = $allmodule = array();
        foreach ( $plugins as $p_key => $p_data ) {
            $p_name = $this->pluginkey_to_name($p_key);
            if(empty($p_name))
                continue;
            if(strpos($p_key, 'jetpack_module/') !== false)
                $jmodules[] = $p_key;
            else if(strpos($p_key, 'celtispack_module/') !== false)
                $cmodules[] = $p_key;
            else 
                $nplugins[] = $p_key; 
        }
        $chklist = array('home', 'archive', 'search', 'attachment', 'page', 'post');
        foreach ( $pformat as $type) {
            if(!in_array($type, $exclude)){
                $chklist[] = "post-$type";
            }
        }
        if(function_exists('is_embed')){
            $chklist[] = 'content-card';
        }
        $post_types = get_post_types( array('public' => true, '_builtin' => false) );                    
        foreach ( $post_types as $post_type ) {
            $chklist[] = $post_type;
        }
        foreach ( $nplugins as $p_key ) {
            if(strpos($p_key, 'jetpack/') !== false){
                foreach ( $jmodules as $p_key ) {
                    $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
                }
            }
            else if(strpos($p_key, 'celtispack/') !== false){                
                foreach ( $cmodules as $p_key ) {
                    $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
                }
            }
            else {
                $this->_plfactive_checkbox_row($p_key, $select_cvplugins, $chklist, $filter);
            }
        }
        ?>
        </tbody>
    </table>
    </div>
    <?php
    }
    
    //Option Setting Form Display
    public function plf_option_page() {
        $clear_dialog = __('Plugin Load Filter Settings\nClick OK to clear it.', 'plf');
    ?>
    <h2><?php _e('Plugin Load Filter Settings', 'plf'); ?></h2>
    <p></p>
    <div id="plf-setting-tabs">
        <ul>
            <li><a href="#plf-registration-tab" ><?php _e('Filter Registration', 'plf'); ?></a></li>
            <li><a href="#plf-activation-tab" ><?php _e('Page Type Filter Activation', 'plf'); ?></a></li>
        </ul>
        <form method="post" autocomplete="off">
		<?php wp_nonce_field( 'plugin_load_filter'); ?>
        <div id="plf-registration-tab" style="display : none;">
            <?php $this->plfregist_table($this->plugins_inf, $this->filter); ?>
            <p class="submit">
                <input type="submit" class="button-primary" name="clear_regist_filter" value="<?php _e('Clear', 'plf'); ?>" onclick="return confirm('<?php echo $clear_dialog; ?>')" />&nbsp;&nbsp;&nbsp;
                <input type="submit" class="button-primary" name="edit_regist_filter" value="<?php _e('Filter Entry &raquo;', 'plf'); ?>" />
            </p>
        </div>
        <div id="plf-activation-tab" style="display : none;">
            <?php
            $pgfilter = (!empty($this->filter['_pagefilter']['plugins']))? $this->filter['_pagefilter']['plugins'] : array();
            if(!empty($pgfilter)){
                $this->plfactive_table($this->plugins_inf, $pgfilter, $this->filter);
                ?>
                <br />
                <p><?php _e('Select plugins to be activated for each page type by clicking on <span class="dashicons dashicons-admin-plugins"></span> mark from "page type filter" registered plugins.', 'plf') ?><br />
                   <?php _e('You can also select plugins to activate from Post/Page content editing screen.', 'plf') ?>
                </p>
                <p class="submit">
                  <input type="submit" class="button-primary" name="clear_activate_page_filter" value="<?php _e('Clear', 'plf'); ?>" onclick="return confirm('<?php echo $clear_dialog; ?>')" />&nbsp;&nbsp;&nbsp;
                  <input type="submit" class="button-primary" name="edit_activate_page_filter" value="<?php _e('Activate Plugin Entry &raquo;', 'plf'); ?>" />
                </p>
                <?php
            }
            else {
                ?>
                <br />
                <p><span style="color: #ff0000;"><?php _e('Page Filter is not registered', 'plf') ?></span></p>
                <?php
            }
            ?>
        </div>
        </form>
    </div>
    <?php
    }

    /***************************************************************************
     * Meta box
     * Individual of the plug-in filter meta box for Post/Page/CustomPost
     **************************************************************************/
    function load_meta_boxes( $post_type, $post ) {
        if ( current_user_can('activate_plugins', $post->ID) ) { 
          	add_meta_box( 'pluginfilterdiv', __( 'Page Filter Plugin', 'plf' ), array(&$this, 'plf_meta_box'), null, 'side' );
            //add_action( 'admin_head', array(&$this, 'plf_css' ));
            add_action( 'admin_footer', array(&$this, 'plf_meta_script' ));
        }
    }

    //Plugin pagefilter Selected Checkbox (plugin and modules list)
    // $p_key 
    // $select_cvplugins  : csv string : pagefilter selected plugins 
    // $checked_arcvplugins : array csv string : ['desktop']=desktop enable plugins, ['mobile']=mobile enable plugins
    function _pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins ) {

        $html = '';
        $selplugins = array_map("trim", explode(',', $select_cvplugins));
        $device['desktop'] = $checked_arcvplugins['desktop'];
        $device['mobile']  = $checked_arcvplugins['mobile'];
        $devlist = array('desktop','mobile');
        if(in_array( $p_key, $selplugins )){
            $p_name = $this->pluginkey_to_name($p_key);                
            $html .= "<tr><td class='plugins-name'>$p_name</td>";
            foreach($devlist as $devtype){
                $checked = (empty($device[$devtype]) || false === strpos($device[$devtype], $p_key))? false : true;
                $html .= "<td class='device-type $devtype'>" . self::altcheckbox("plf_option[$devtype][$p_key]", $checked, '<span class="dashicons dashicons-yes"></span>') . '</td>';
            }
            $html .= "</tr>";
        }
        return $html;
    }
    
    //Plugin pagefilter Selected Checkbox (plugin and modules list)
    // $plugins : array : all active plugins 
    // $select_cvplugins  : csv string : pagefilter selected plugins 
    // $checked_arcvplugins : array csv string : ['desktop']=desktop enable plugins, ['mobile']=mobile enable plugins
    function pagefilter_plugins_checklist( $plugins, $select_cvplugins, $checked_arcvplugins ) {
        
        if(empty($select_cvplugins))
            return __('Page Filter is not registered', 'plf');
        
        $html = '<table id="activation-table">';
        $html .= '<thead>';
        $html .= '<tr><th class="plugins-name">'. __('Plugins') . '</th>';
        $html .= '<th class="device-type"><span title="'. __('Desktop Device', 'plf'). '" class="dashicons dashicons-desktop"></span><br /><span style="font-size:xx-small">Desktop</span></th>';
        $html .= '<th class="device-type"><span title="'. __('Mobile Device', 'plf'). '" class="dashicons dashicons-smartphone"></span><br /><span style="font-size:xx-small">Mobile</span></th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody class="plugins-table-body meta-boxes-plugins-table">';
        $nplugins = $jmodules = $cmodules = array();
        foreach ( $plugins as $p_key => $p_data ) {
            $p_name = $this->pluginkey_to_name($p_key);
            if(empty($p_name))
                continue;
            if(strpos($p_key, 'jetpack_module/') !== false)
                $jmodules[] = $p_key;
            else if(strpos($p_key, 'celtispack_module/') !== false)
                $cmodules[] = $p_key;
            else 
                $nplugins[] = $p_key; 
        }

        foreach ( $nplugins as $p_key ) {
            if(strpos($p_key, 'jetpack/') !== false){
                foreach ( $jmodules as $p_key ) {
                    $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
                }
            }
            else if(strpos($p_key, 'celtispack/') !== false){                
                foreach ( $cmodules as $p_key ) {
                    $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
                }
            }
            else {
                $html .= $this->_pagefilter_plugins_checklist( $p_key, $select_cvplugins, $checked_arcvplugins );                    
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    function plf_meta_box( $post, $box ) {     
        if(is_object($post)){
            $myfilter = get_post_meta( $post->ID, '_plugin_load_filter', true );
            //ver2.2.0 for compatibility, set 'plugins' data to 'desktop' and 'mobile'
            if(!empty($myfilter['plugins'])){
                $myfilter['desktop'] = $myfilter['plugins'];
                $myfilter['mobile']  = $myfilter['plugins'];
                unset($myfilter['plugins']);
            }
            $default = array( 'filter' => 'default', 'desktop' => '', 'mobile' => '');
            $option = (!empty($myfilter))? $myfilter : $default;
            $option = wp_parse_args( $option, $default);
            $pgfilter = (!empty($this->filter['_pagefilter']['plugins']))? $this->filter['_pagefilter']['plugins'] : array();
			$ajax_nonce = wp_create_nonce( 'plugin_load_filter-' . $post->ID );
            $this->plf_css();
            ?>
        <div id="plugin-filter-select">
            <label><input type="radio" name="pagefilter" value="default" <?php checked('default', $option['filter']); ?>/><?php _e('Not Use', 'plf' ); ?></label>
            <label><input type="radio" name="pagefilter" value="include" <?php checked('include', $option['filter']); ?>/><?php _e('Use', 'plf'); ?></label>
            <div id="page-filter-stat">
            <?php echo $this->pagefilter_plugins_checklist( $this->plugins_inf, $pgfilter, $option ); ?>
            </div>
            <?php echo '<p class="hide-if-no-js"><a id="plugin-filter-submit" class="button" href="#pluginfilterdiv" onclick="WPAddPagePluginLoadFilter(\'' . $ajax_nonce . '\');return false;" >'. __('Save') .'</a></p>'; ?>
        </div>
        <?php
        }
    }    

    //wp_ajax_plugin_load_filter called function
    function plf_ajax_postidfilter() {
        if ( isset($_POST['post_id']) ) {
            $pid = (int) $_POST['post_id'];
            if ( !current_user_can( 'activate_plugins', $pid ) )
                wp_die( -1 );            
            check_ajax_referer( "plugin_load_filter-$pid" );
            
            $pgfilter = (!empty($this->filter['_pagefilter']['plugins']))? $this->filter['_pagefilter']['plugins'] : array();
            $option["filter"] = (empty($_POST['filter']))? 'default' : $_POST['filter'];
            if('default' == $option["filter"]){
                delete_post_meta( $pid, '_plugin_load_filter');
            }
            else {
                $plugins = array();
                if( preg_match_all('/plf_option\[desktop\]\[(.+?)\]/u', $_POST['desktop'], $matches)){
                    if(!empty($matches[1])){ 
                        foreach ($matches[1] as $plugin){
                            $plugins[] = $plugin;
                        }
                        $option["desktop"] = implode(",", $plugins);
                    }
                }
                $plugins = array();
                if( preg_match_all('/plf_option\[mobile\]\[(.+?)\]/u', $_POST['mobile'], $matches)){
                    if(!empty($matches[1])){ 
                        foreach ($matches[1] as $plugin){
                            $plugins[] = $plugin;
                        }
                        $option["mobile"] = implode(",", $plugins);
                    }
                }
                update_post_meta( $pid, '_plugin_load_filter', $option );
            }
            
            $html = $this->pagefilter_plugins_checklist( $this->plugins_inf, $pgfilter, $option );
            wp_send_json_success($html);
        }
        wp_die( 0 );
    }

    /***************************************************************************
     * Javascript 
     **************************************************************************/
    function activetab_script() { ?>
    <script type='text/javascript' >
    /* <![CDATA[ */
    var plf_activetab = <?php echo $this->tab_num; ?>
    /* ]]> */
    jQuery(document).ready(function ($) { plf_setting_tabs(); function plf_setting_tabs(){ $('#plf-setting-tabs').tabs({ active:plf_activetab, }); }});    
    </script>  
    <?php }
    
    function plf_meta_script() { ?>
    <script type='text/javascript' >
    WPAddPagePluginLoadFilter = function(nonce){ 
        jQuery.ajax({ 
            type: 'POST', 
            url: ajaxurl, 
            data: { 
                action: "plugin_load_filter", 
                post_id : jQuery( '#post_ID' ).val(), 
                _ajax_nonce: nonce, 
                filter: jQuery("input[name='pagefilter']:checked").val(), 
                desktop: jQuery('.meta-boxes-plugins-table td.desktop input:checked').map(function(){ return jQuery(this).attr("name"); }).get().join(','), 
                mobile: jQuery('.meta-boxes-plugins-table td.mobile input:checked').map(function(){ return jQuery(this).attr("name"); }).get().join(','),
            }, 
            dataType: 'json', 
            success: function(response, dataType) { 
                jQuery('#page-filter-stat').html(response.data); 
            }
        }); 
        return false; 
    };
    </script>  
    <?php }
}