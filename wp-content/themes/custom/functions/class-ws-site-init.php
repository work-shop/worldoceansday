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

        //WS_Custom_Category::register();
        //WS_Custom_Post::register();

        register_post_type( 'people',
            array(
                'labels' => array(
                    'name' => 'People',
                    'singular_name' =>'Person',
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Person',
                    'edit_item' => 'Edit Person',
                    'new_item' => 'New Person',
                    'all_items' => 'All People',
                    'view_item' => 'View Person',
                    'search_items' => 'Search People',
                    'not_found' =>  'No People found',
                    'not_found_in_trash' => 'No People found in Trash',
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'people'),
                'show_in_rest'       => true,
                'rest_base'          => 'people',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
                'supports' => array( 'title', 'thumbnail'),
                'menu_icon'   => 'dashicons-id'
            ));

        register_taxonomy(
            'people-categories',
            'people',
            array(
                'hierarchical' => true,
                'label' => 'People Categories',
                'query_var' => true,
                'show_admin_column' => true,
                'rewrite' => array('slug' => 'people-categories'),
                'rest_base'          => 'people-categories',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );

        

    }


    public function register_image_sizing() {
        if ( function_exists( 'add_image_size' ) ) {
            add_image_size('progressive', 16, 10, false); //1.6:1
            add_image_size('progressive_cropped', 16, 10, true); //1.6:1
            add_image_size('xs', 300, 187, false); //1.6:1
            add_image_size('xs_landscape', 187, 300, true); //1.6:1
            add_image_size('xs_portrait', 300, 187, true); //1.6:1
            add_image_size('xs_square', 300, 300, true);
            add_image_size('sm', 512, 320, false); //1.6:1
            add_image_size('sm_landscape', 512, 320, true); //1.6:1
            add_image_size('sm_landscape', 512, 320, true); //1.6:1
            add_image_size('sm_portrait', 320, 512, true); //1.6:1
            add_image_size('sm_square', 512, 512, true);
            add_image_size('md', 768, 480, false); //1.6:1
            add_image_size('md_landscape', 768, 480, true); //1.6:1
            add_image_size('md_portrait', 480, 768, true); //1.6:1
            add_image_size('md_square', 768, 768, true);
            add_image_size('lg', 1280, 800, false); //1.6:1
            add_image_size('lg_landscape', 1280, 800, true); //1.6:1
            add_image_size('lg_portrait', 800, 1200, true); //1.6:1
            add_image_size('lg_square', 1280, 1280, true);   
            add_image_size('xl', 1920, 1200, false); //1.6:1
            add_image_size('xl_landscape', 1920, 1200, true); //1.6:1
            add_image_size('xl_portrait', 1200, 1920, true); //1.6:1
            add_image_size('xl_square', 1920, 1920, true);  
            add_image_size('fb', 1200, 630, true);
        }
    }


    public function register_theme_support() {
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support('post-thumbnails');
            add_theme_support( 'menus' );
        }
    }


    public function enqueue_scripts_and_styles() {
        if ( function_exists( 'get_template_directory_uri' ) && function_exists( 'wp_enqueue_style' ) && function_exists( 'wp_enqueue_script' ) ) {

            $main_css = '/bundles/bundle.css';
            $main_js = '/bundles/bundle.js';

            $compiled_resources_dir = get_template_directory();
            $compiled_resources_uri = get_template_directory_uri();

            $main_css_ver = filemtime( $compiled_resources_dir . $main_css ); // version suffixes for cache-busting.
            $main_js_ver = filemtime( $compiled_resources_dir . $main_css ); // version suffixes for cache-busting.

            wp_register_style( 'fonts', get_template_directory_uri() . '/fonts/fonts.css');
            wp_enqueue_style( 'fonts' );  
            wp_enqueue_style('main-css', $compiled_resources_uri . $main_css, array(), null);
            wp_enqueue_script('main-js', $compiled_resources_uri . $main_js, $main_js_ver);

        }
    }


}

?>