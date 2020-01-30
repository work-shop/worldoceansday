<?php
/*
* This file use for setings at admin site for google maps settings.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WP_Event_Manager_Google_Maps_Settings class.
 */
class WP_Event_Manager_Google_Maps_Settings {


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() 
    {		
		add_filter( 'event_manager_settings', array( $this, 'google_maps_settings' ) );
		
        
       
	}

	/**
	 *  google_maps_settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function google_maps_settings($settings) {
		
			$settings[ 'google_maps_gerenal_settings' ] = array(
													
					                                __( 'Google Maps Gerenal Settings', 'wp-event-manager-google-maps' ),
					                                array(
						                                        array(
							                                              'name'       => 'event_manager_google_maps_api_language',  
							                                              'std'        => '',
							                                              'label'      => __( 'Google API language', 'wp-event-manager-google-maps' ),		
							                                              'desc'       => __( 'This feature controls the language of the autocomplete results and Google maps. Enter the language code of the langauge you would like to use. List of avaliable langauges can be found <a href="http://www.mathguide.de/info/tools/languagecode.html" target="_blank"> here</a>','wp-event-manager-google-maps' ),											 
																		   'type'       => 'text',
							                                               'attributes' => array()
						                                            ),
																	array(
							                                              'name'       => 'event_manager_google_maps_api_default_region',  
							                                              'std'        => '',
							                                              'label'      => __( 'Google API default region', 'wp-event-manager-google-maps' ),		
							                                              'desc'       => __( 'This feature controls the regions of Goole API. Enter a country code; for example for United States enter US. you can find your country code <a href="https://countrycode.org/" target="blank">here</a>' ),											 
																		   'type'       => 'text',
							                                               'attributes' => array()
						                                               ),
																	   array(
							                                              'name'       => 'event_manager_google_maps_google_address_autocomplete_backend',  
							                                              'std'        => '',
							                                              'label'      => __( ' Google address autocomplete(For Backend submission form)', 'wp-event-manager-google-maps' ),		
							                                              'desc'       => __( 'Disply suggested results by Google when typing an address in the location field of the new/edit WP Event Manager Google Maps screen.',  'wp-event-manager-google-maps' ),											 
																		  'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																		  'type'       => 'checkbox',
							                                              'attributes' => array()
						                                               ),
																	    array(
							                                              'name'       => 'event_manager_google_maps_google_address_autocomplete_frontend',  
							                                              'std'        => '',
							                                              'label'      => __( 'Google address autocomplete(For Frontend submission form)', 'wp-event-manager-google-maps' ),		
							                                              'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																		  'desc'       => __( 'Disply suggested results by Google when typing an address in the location field of the new/edit Google Maps form in the front end' ,'wp-event-manager-google-maps' ),											 
																		  'type'       => 'checkbox',
							                                              'attributes' => array()
						                                               ),
																	    array(
							                                              'name'       => 'event_manager_google_maps_autocomplete_country_restriction',  
							                                              'std'        => '',
							                                              'label'      => __( 'Autocomplete country restriction', 'wp-event-manager-google-maps' ),		
																		  'desc'       => __( 'Enter the country code of the country which you would like to restrict the autocomplete results to. Leave it empty to show all countries', 'wp-event-manager-google-maps' ),											 
																		  'type'       => 'text',
							                                              'attributes' => array()
						                                               ),
																	   array(
							                                              'name'       => 'event_manager_google_maps_location_marker',  
							                                              'std'        => '',
							                                              'label'      => __( 'Location Marker', 'wp-event-manager-google-maps' ),
																		  'std'		   => 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
																		  'desc'       => __( 'Url to the marker represents locations on the map.' , 'wp-event-manager-google-maps' ),											 
																		  'type'       => 'text',
							                                              'attributes' => array()
						                                               ),
					                                        )
										);    
			$settings['Google_maps_search_form_settings'] = array(
		 														__( 'Google Maps Search Form Settings', 'wp-event-manager-google-maps' ),

																array(
																		array(
																				'name'        => 'event_manager_autocomplete',
																				'std'         => '1',
																				'label'       => __( 'Google address autocomplete', 'wp-event-manager-google-maps' ),
																				'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																				'desc'        => sprintf( __( 'Disply suggested results by Google when typing an address in the location field of the %s search form', 'wp-event-manager-google-maps' ),'google_map'),
																				'type'        => 'checkbox',
																				'attributes'  => array()
																		),
																		array(
																				'name'        => 'event_manager_radius',
																				'std'         => '5,10,15,25,50,100',
																				'placeholder' => '',
																				'label'       => __( 'Radius', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Enter single value to be the default or multiple values comma separated to be displaed as a dropdown', 'wp-event-manager-google-maps' ),
																				'attributes'  => array()
																		),												
																		array(
																				'name'        => 'event_manager_orderby',
																				'std'         => 'distance,title,featured,date',
																				'placeholder' => '',
																				'label'       => __( 'Order By', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Enter the values you want to use in the "Sort by" dropdown select box. Enter, comma separated, in the order that you want the values to appear any of the values: distance, title, date and featured.', 'wp-event-manager-google-maps' ),
																				'attributes'  => array()
												
																		),
																		array(
																				'name'        => 'event_manager_display_maps',
																				'std'         => '1',
																				'label'       => __( 'Display maps', 'wp-event-manager-google-maps' ),
																				'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Disply maps with google_map location above the list of results', 'wp-event-manager-google-maps' ),
																				'type'        => 'checkbox',
																				'attributes'  => array()
																		),
												
																		array(
																				'name'        => 'event_manager_maps_width',
																				'std'         => '100%',
																				'placeholder' => '',
																				'label'       => __( 'Maps Width', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Maps width in pixels or percentage (ex. 100% or 250px)', 'wp-event-manager-google-maps' ),
																				'attributes'  => array()
																		),
																		array(
																				'name'        => 'event_manager_maps_height',
																				'std'         => '250px',
																				'placeholder' => '',
																				'label'       => __( 'Maps height', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Maps height in pixels or percentage (ex. 100% or 250px)', 'wp-event-manager-google-maps' ),
																				'attributes'  => array()
																		),
												
																		array(
																				'name'        => 'event_manager_maps_type',
																				'std'         => 'ROADMAP',
																				'label'       => __( 'Maps Type', 'wp-event-manager-google-maps' ),
																				'desc'        => __( 'Choose the maps type', 'wp-event-manager-google-maps' ),
																				'type'		  => 'select',
																				'options'	  => array(
																						'ROADMAP' 	=> __( 'ROADMAP' , 'wp-event-manager-google-maps' ),
																						'SATELLITE' => __( 'SATELLITE' , 'wp-event-manager-google-maps' ),
																						'HYBRID'    => __( 'HYBRID' , 'wp-event-manager-google-maps' ),
																						'TERRAIN'   => __( 'TERRAIN' , 'wp-event-manager-google-maps' )
																				),
																		),
																		array(
																				'name'        => 'event_manager_scroll_wheel',
																				'std'         => '1',
																				'label'       => __( "Enable Maps scrollwheel control?", 'wp-event-manager-google-maps' ),
																				'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																				'desc'        => __( "Zoom maps in/out using mouse wheel?", 'wp-event-manager-google-maps' ),
																				'type'        => 'checkbox',
																				'attributes'  => array()
																		),
																),
												
		 													); 
			$settings['google_maps_single_page_options'] = array(
													__( 'Google Maps Single Page Settings', 'wp-event-manager-google-maps' ),
													array(
															array(
																	'name'        => 'event_manager_single_maps_use',
																	'std'         => '1',
																	'label'       => __( 'Display maps', 'wp-event-manager-google-maps' ),
																	'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																	'desc'        => sprintf( __( 'Display maps showing the %s location in a single %s page', 'wp-event-manager-google-maps' ),'google_maps', 'google_maps' ),
																	'type'        => 'checkbox',
																	'attributes'  => array()
															),
															array(
																	'name'        => 'event_manager_single_maps_width',
																	'std'         => '100%',
																	'placeholder' => '',
																	'label'       => __( 'Maps Width', 'wp-event-manager-google-maps' ),
																	'desc'        => __( 'Maps width in pixels or percentage (ex. 100% or 250px)', 'wp-event-manager-google-maps' ),
																	'attributes'  => array()
															),
															array(
																	'name'        => 'event_manager_single_maps_height',
																	'std'         => '250px',
																	'placeholder' => '',
																	'label'       => __( 'Maps height', 'wp-event-manager-google-maps' ),
																	'desc'        => __( 'Maps height in pixels or percentage (ex. 100% or 250px)', 'wp-event-manager-google-maps' ),
																	'attributes'  => array()
															),
															array(
																	'name'        => 'event_manager_single_maps_type',
																	'std'         => 'ROADMAP',
																	'label'       => __( 'Maps Type', 'wp-event-manager-google-maps' ),
																	'desc'        => __( 'Choose the maps type', 'wp-event-manager-google-maps' ),
																	'type'		  => 'select',
																	'options'	  => array(
																			'ROADMAP' 	=> __( 'ROADMAP' , 'wp-event-manager-google-maps' ),
																			'SATELLITE' => __( 'SATELLITE' , 'wp-event-manager-google-maps' ),
																			'HYBRID'    => __( 'HYBRID' , 'wp-event-manager-google-maps' ),
																			'TERRAIN'   => __( 'TERRAIN' , 'wp-event-manager-google-maps' )
																	),
															),
															array(
																	'name'        => 'event_manager_single_maps_scroll_wheel',
																	'std'         => '1',
																	'label'       => __( 'Enable maps scrollwheel control?', 'wp-event-manager-google-maps' ),
																	'cb_label'    => __( 'Yes', 'wp-event-manager-google-maps' ),
																	'desc'        => __( 'Zoom maps in/out using mouse wheel?', 'wp-event-manager-google-maps' ),
																	'type'        => 'checkbox',
																	'attributes'  => array()
															),
															array(
																	'name'        => 'event_manager_single_maps_zoom',
																	'std'         => '5',
																	'label'       => __( 'Zoom Level', 'wp-event-manager-google-maps' ),
																	'cb_label'    => '',
																	'desc'        => __('Add valid zoom level.<a href="https://developers.google.com/maps/documentation/javascript/tutorial#MapOptions">Click here</a> for more zoom level', 'wp-event-manager-google-maps' )
															)
									
													),

		);                                   
         return $settings;
			                                                          
	}
  
}
new WP_Event_Manager_Google_Maps_Settings();