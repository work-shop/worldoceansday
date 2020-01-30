<?php

global $wpdb;

global $WPGMZA_TABLE_NAME_HEATMAPS;
global $WPGMZA_TABLE_NAME_CATEGORIES;
global $WPGMZA_TABLE_NAME_MARKERS_HAS_CATEGORIES;
global $WPGMZA_TABLE_NAME_CATEGORY_MAPS;

define('WPGMZA_PRO_DIR_PATH', plugin_dir_path(__FILE__));
define('WPGMZA_PRO_DIR_URL', plugin_dir_url(__FILE__));

$WPGMZA_TABLE_NAME_HEATMAPS					= $wpdb->prefix . 'wpgmza_datasets';
$WPGMZA_TABLE_NAME_CATEGORIES				= $wpdb->prefix . 'wpgmza_categories';
$WPGMZA_TABLE_NAME_MARKERS_HAS_CATEGORIES	= $wpdb->prefix . 'wpgmza_markers_has_categories';
$WPGMZA_TABLE_NAME_CATEGORY_MAPS			= $wpdb->prefix . 'wpgmza_category_maps';
$WPGMZA_TABLE_NAME_BATCHED_IMPORTS			= $wpdb->prefix . 'wpgmza_batched_imports';