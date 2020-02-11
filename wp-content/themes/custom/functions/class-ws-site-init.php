<?php


class WS_Site {

    public function __construct() {

        add_action('init', array( $this, 'register_image_sizing') );
        add_action('init', array( $this, 'register_theme_support') );
        add_action('init', array( $this, 'register_post_types_and_taxonomies' ) );

        add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

        add_filter('show_admin_bar', '__return_false');

        new WS_CDN_Url();

    }


    public function register_post_types_and_taxonomies() {

        // WS_Custom_Category::register();
        // WS_Custom_Post::register();

        register_post_type( 'resources',
            array(
                'labels' => array(
                    'name' => 'Resources',
                    'singular_name' =>'Resource',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add Resource',
                    'edit_item' => 'Edit Resource',
                    'new_item' => 'New Resource',
                    'all_items' => 'All Resource',
                    'view_item' => 'View Resources',
                    'search_items' => 'Search Resources',
                    'not_found' =>  'No Resources found',
                    'not_found_in_trash' => 'No Resources found in Trash',
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'resources'),
                'show_in_rest'       => true,
                'rest_base'          => 'resources',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
                'supports' => array( 'title', 'thumbnail'),
                'menu_icon'   => 'dashicons-media-document'
            ));

        register_taxonomy(
            'resources-topics',
            'resources',
            array(
                'hierarchical' => true,
                'label' => 'Resource Topics',
                'query_var' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'resource-topics'),
                'rest_base'          => 'resource-topics',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        register_taxonomy(
            'resources-type',
            'resources',
            array(
                'hierarchical' => true,
                'label' => 'Resource Type',
                'query_var' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'resource-type'),
                'rest_base'          => 'resource-type',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        register_taxonomy(
            'resources-language',
            'resources',
            array(
                'hierarchical' => true,
                'label' => 'Resource Language',
                'query_var' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'resource-language'),
                'rest_base'          => 'resource-language',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        register_taxonomy(
            'event_listing_country',
            'event_listing',
            array(
                'hierarchical' => true,
                'label' => 'Event Country',
                'query_var' => true,
                'rewrite' => array('slug' => 'event_listing_country'),
                'rest_base'          => 'event_listing_country',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        

    }


    public function register_image_sizing() {
        if ( function_exists( 'add_image_size' ) ) {
            // add_image_size('progressive', 16, 10, false); //1.6:1
            // add_image_size('progressive_cropped', 16, 10, true); //1.6:1
            add_image_size('xs', 300, 187, false); //1.6:1
            //add_image_size('xs_portrait', 187, 300, true); //1.6:1
            //add_image_size('xs_landscape', 300, 187, true); //1.6:1
            //add_image_size('xs_square', 300, 300, true);
            add_image_size('sm', 512, 320, false); //1.6:1
            add_image_size('sm_landscape', 512, 320, true); //1.6:1
            add_image_size('sm_portrait', 320, 512, true); //1.6:1
            add_image_size('sm_square', 512, 512, true);
            add_image_size('md', 768, 480, false); //1.6:1
            //add_image_size('md_landscape', 768, 480, true); //1.6:1
            add_image_size('md_portrait', 480, 768, true); //1.6:1
            //add_image_size('md_square', 768, 768, true);
            add_image_size('lg', 1280, 800, false); //1.6:1
            // add_image_size('lg_landscape', 1280, 800, true); //1.6:1
            // add_image_size('lg_portrait', 800, 1200, true); //1.6:1
            //add_image_size('lg_square', 1280, 1280, true);   
            add_image_size('xl', 1680, 1050, false); //1.6:1
            add_image_size('xl_landscape', 1680, 1050, true); //1.6:1
            //add_image_size('xl_portrait', 1200, 1920, true); //1.6:1
            //add_image_size('xl_square', 1920, 1920, true);  
            add_image_size('fb', 1200, 630, true);
        }
    }


    public function register_theme_support() {
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support('post-thumbnails');
            add_theme_support( 'menus' );
        }
        
        //add_post_type_support( 'page', 'excerpt' );
    }


    public function enqueue_scripts_and_styles() {
        if ( function_exists( 'get_template_directory_uri' ) && function_exists( 'wp_enqueue_style' ) && function_exists( 'wp_enqueue_script' ) ) {

            $main_css = '/bundles/bundle.css';
            $main_js = '/bundles/bundle.js';
           // $instantpage = get_template_directory_uri() . '/js/instantpage.js';

            $compiled_resources_dir = get_template_directory();
            $compiled_resources_uri = get_template_directory_uri();

            $main_css_ver = filemtime( $compiled_resources_dir . $main_css ); // version suffixes for cache-busting.
            $main_js_ver = filemtime( $compiled_resources_dir . $main_css ); // version suffixes for cache-busting.

            //wp_register_style( 'fonts', get_template_directory_uri() . '/fonts/fonts.css');
            //wp_enqueue_style( 'fonts' );  
            wp_enqueue_style('main-css', $compiled_resources_uri . $main_css, array(), null);
            wp_enqueue_script('main-js', $compiled_resources_uri . $main_js, $main_js_ver);
            //wp_enqueue_script('instantpage', $instantpage);

        }
    }


}

?>
