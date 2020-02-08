<?php

define( '__ROOT__', dirname( __FILE__ ) );

require_once( __ROOT__ . '/functions/library/class-ws-cdn-url.php');

require_once( __ROOT__ . '/functions/class-ws-site-admin.php' );
require_once( __ROOT__ . '/functions/class-ws-site-init.php' );

require_once( __ROOT__ . '/functions/library/class-ws-flexible-content.php' );
require_once( __ROOT__ . '/functions/library/class-helpers.php' );

require_once( __ROOT__ . '/functions/library/events_functions.php' );
require_once( __ROOT__ . '/functions/library/resources_functions.php' );


function my_mce4_options($init) {

	$custom_colours = '
	"19bae3", "Brand Primary",
	"164579", "Brand Navy",
	"FFB5D8", "Brand Pink",
	"1B334D", "Brand Text Gray",
	"F9A619", "Brand Orange",
	"FEF2DD", "Orange Tint",
	"9FDBED", "Primary Tint"
	';

    // build colour grid default+custom colors
	$init['textcolor_map'] = '['.$custom_colours.']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
	$init['textcolor_rows'] = 1;

	return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');

new WS_Site();
new WS_Site_Admin();

