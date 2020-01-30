 var GoogleMaps= function () {
    /// <summary>Constructor function of the event GoogleMaps class.</summary>
    /// <since>1.0.0</since>
    /// <returns type="GoogleMaps" />  
    
  	return {
		/// <summary>
        /// Initializes the GoogleMaps.       
        /// </summary>                 
        /// <returns type="initialization settings" />     
        /// <since>1.0.0</since>  
        init: function() 
        {
               Common.logInfo("GoogleMaps.init...");  
			  
			   google.maps.event.addDomListener(window, 'load', GoogleMaps.actions.showMap); 
		},
		actions:
		{
			/// <summary>
	        /// Show google map on single event page.	     
	        /// </summary>
	        /// <param name="parent" type="assign"></param>           
	        /// <returns type="actions" />     
	        /// <since>1.0.0</since>       
	        showMap: function(event) 
	        {
	        	Common.logInfo("GoogleMaps.action.showMap...");		
	        	var map_type = event_manager_google_maps.map_type;
				var myCenter = new google.maps.LatLng(event_manager_google_maps.lat,event_manager_google_maps.lag);
				var icon = event_manager_google_maps.marker;
			
				var map=new google.maps.Map(document.getElementById("googleMap"),{ 
								  zoom : parseInt(event_manager_google_maps.zoom),
								  center : myCenter,
								  icon: icon,
								  mapTypeId : google.maps.MapTypeId.map_type,
								  scrollwheel : event_manager_google_maps.scrollwheel
							  });
			    var marker = new google.maps.Marker({
							position: myCenter,
							map: map,
							icon: icon 
							});	
							
							 if(map_type == 'TERRAIN' ){ 
							  map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
							 }
							 else if(map_type == 'SATELLITE'){
							    map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
							 }
							 else if(map_type == 'HYBRID'){
							    map.setMapTypeId(google.maps.MapTypeId.HYBRID); 
							 }
							 else{
							   map.setMapTypeId(google.maps.MapTypeId.ROADMAP);       
							 }
			}
		}
	}
};
			   

GoogleMaps= GoogleMaps();

jQuery(document).ready(function($) 
{
	GoogleMaps.init();
});