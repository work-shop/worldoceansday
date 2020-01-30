/**
 * @namespace WPGMZA
 * @module ProMap
 * @requires WPGMZA.Map
 */
jQuery(function($) {
	
	WPGMZA.ProMap = function(element, options)
	{
		var self = this;
		
		this._markersPlaced = false;
		
		// Some objects created in the parent constructor use the category data, so load that first
		this.element = element;
		
		// Call the parent constructor
		WPGMZA.Map.call(this, element, options);
		
		this.heatmaps = [];
		
		this.showDistanceFromLocation = null;
		
		this.initCustomFieldFilterController();
		this.initUserLocationMarker();
		this.on("filteringcomplete", function() {
			//call onFilteringComplete function
			self.onFilteringComplete();

		});
		this.on("init", function(event) {
			self.onInit(event);
		});
		
		this._onMarkersPlaced = function(event) {
			self.onMarkersPlaced(event);
		}
		this.on("markersplaced", this._onMarkersPlaced);
	}
	
	WPGMZA.ProMap.prototype = Object.create(WPGMZA.Map.prototype);
	WPGMZA.ProMap.prototype.constructor = WPGMZA.ProMap;
	
	WPGMZA.ProMap.SHOW_DISTANCE_FROM_USER_LOCATION		= "user";
	WPGMZA.ProMap.SHOW_DISTANCE_FROM_SEARCHED_ADDRESS	= "searched";
	
	Object.defineProperty(WPGMZA.ProMap.prototype, "mashupIDs", {
		
		get: function() {
			
			var result = [];
			var attr = $(this.element).attr("data-mashup-ids");
			
			if(attr && attr.length)
				result = result = attr.split(",");
			
			return result;
			
		}
		
	});
	
	Object.defineProperty(WPGMZA.ProMap.prototype, "directionsEnabled", {
		
		get: function() {
			return this.settings.directions_enabled == 1;
		}
		
	});
	
	Object.defineProperty(WPGMZA.ProMap.prototype, "markersPlaced", {
		
		get: function() {
			return this._markersPlaced;
		},
		
		set: function(value) {
			throw new Error("Value is read only");
		}
		
	});
	
	WPGMZA.ProMap.prototype.onInit = function(event)
	{
		var self = this;
		
		this.initPreloader();
		this.initDirectionsBox();
		
		if(!("autoFetchMarkers" in this.settings) || (this.settings.autoFetchMarkers !== false))
			this.fetchMarkers();

		if(this.shortcodeAttributes.lat && this.shortcodeAttributes.lng)
		{
			var latLng = new WPGMZA.LatLng({
				lat: this.shortcodeAttributes.lat,
				lng: this.shortcodeAttributes.lng
			});
			
			this.setCenter(latLng);
		}
		
		var zoom;
		if(zoom = WPGMZA.getQueryParamValue("mzoom"))
			this.setZoom(zoom);
		
		if(WPGMZA.getCurrentPage() != WPGMZA.PAGE_MAP_EDIT && this.settings.automatically_pan_to_users_location == "1"){

			WPGMZA.getCurrentPosition(function(result) {
						
				self.setCenter(
					new WPGMZA.LatLng({
						lat: result.coords.latitude,
						lng: result.coords.longitude
					})
				);
					
			});

			if(this.settings.override_users_location_zoom_level == "1"){
				WPGMZA.maps[0].setZoom(this.settings.override_users_location_zoom_levels);
			}
		
		}

	}
	
	WPGMZA.ProMap.prototype.onMarkersPlaced = function(event)
	{
		var self = this;
		
		// NB: Marker listing. We delay this til here because the marker gallery will need to fetch marker data from here
		// A good alternative to this would be to transmit the marker data in a data- attribute
		
		if(this.settings.order_markers_by == WPGMZA.MarkerListing.ORDER_BY_DISTANCE || this.settings.show_distance_from_location == 1)
		{
			WPGMZA.getCurrentPosition(function(result) {
				
				var location = new WPGMZA.LatLng({
					lat: result.coords.latitude,
					lng: result.coords.longitude
				});
				
				self.userLocation = location;
				self.userLocation.source = WPGMZA.ProMap.SHOW_DISTANCE_FROM_USER_LOCATION;
				
				self.showDistanceFromLocation = location;

				self.updateInfoWindowDistances();
				
				if(self.markersPlaced)
				{
					self.initMarkerListing();
				}
				else
				{					
					self.on("markersplaced", function(event) {
						self.initMarkerListing();
					});
				}
				
				// Checks if jump_to_nearest_marker_on_initialization setting is enabled, only on the front end though
				if(WPGMZA.is_admin == 0 && self.settings.jump_to_nearest_marker_on_initialization == 1)
					self.panToNearestMarker(location);
				
			}, function(error) {
				
				self.initMarkerListing();
				
			});
		}
		else
			self.initMarkerListing();

		// Clustering
		if(window.wpgm_g_e && wpgm_g_e == 1 && this.settings.mass_marker_support == 1)
		{
			this.markerClusterer.addMarkers(this.markers);
			
			// Legacy support
			if(typeof window.markerClusterer == "array")
				window.markerClusterer[this.id] = clusterer;
		}

		//Check if Fit map bounds to markers setting is enable
		if(this.settings.fit_maps_bounds_to_markers == '1')
		{
			self.fitMapBoundsToMarkers();
		}
	}

	// This will jump to the nearest marker to your location
	WPGMZA.ProMap.prototype.panToNearestMarker = function(latlng)
	{
		var closestMarker;

		var distance = Infinity;

    	// Loop through each marker on this map
    	for (var i = 0; i < this.markers.length; i++) {

        	// Calculate the distance from the latlng passed in to marker[i]
        	var distanceToMarker = WPGMZA.Distance.between(latlng, this.markers[i].getPosition());
        
        	// Is this closer than our current recorded nearest marker?
        	if(distanceToMarker < distance)
        	{
            	// Yes it is, store marker[i] as the closest marker
            	closestMarker = this.markers[i];
            
            	// Store the distance as the new closest difference
            	distance = distanceToMarker;
        	}
		}

    	// Now that the loop has completed, marker will hold the nearest marker to latlng (or null if there are no markers on this map)
    	if(!closestMarker)
        	return;
    
   		 // Pan to it
    	this.panTo(closestMarker.getPosition(this.setZoom(7)));
	}

	//This will fit map bounds to markers
	WPGMZA.ProMap.prototype.fitMapBoundsToMarkers = function()
	{

		var bounds = new WPGMZA.LatLngBounds();

		//Loop through the markers
		for (var i = 0; i < this.markers.length; i++) {
			if(!this.markers[i].isFiltered){
				//set map bounds to these markers
				bounds.extend(this.markers[i]);
				this.fitBounds(bounds);
			}
		}
	}

	//This function will reset initial zoom and location
	WPGMZA.ProMap.prototype.resetBounds = function()
	{
		var set_initial_bounds = new WPGMZA.LatLng(this.settings.map_start_lat, this.settings.map_start_lng);
		this.panTo(set_initial_bounds);;
		this.setZoom(this.settings.map_start_zoom);
	}

	//This will fit map bounds to markers
	WPGMZA.ProMap.prototype.onFilteringComplete = function()
	{
		//Check if Fit map bounds to markers after filtering setting is enabled
		if(this.settings.fit_maps_bounds_to_markers_after_filtering == '1')
		{
			var self = this;
			var total_markers_filtered;
			//Loop through the markers
			for (var i = 0; i < this.markers.length; i++) {
				if(!this.markers[i].isFiltered){
					//Total markers filtered
					total_markers_filtered = i;
				}
			}		
			if(total_markers_filtered > 0){
				//if total markers filtered is more than 0, call fitMapBoundsToMarkers function
				self.fitMapBoundsToMarkers();
			}
			else{
				//if total markers filtered is 0, call resetBounds function
				self.resetBounds();
			}	

		}
	}
	
	WPGMZA.ProMap.prototype.initPreloader = function()
	{
		this.preloader = $("<div class='wpgmza-preloader'><div></div><div></div><div></div><div></div></div>");
		
		/*$(this.preloader).css({
			"background-image": "url(" + WPGMZA.defaultPreloaderImage + ")"
		});*/
		$(this.preloader).hide();
		
		$(this.element).append(this.preloader);
	}
	
	WPGMZA.ProMap.prototype.showPreloader = function(show)
	{
		if(show)
			$(this.preloader).show();
		else
			$(this.preloader).hide();
	}
	
	WPGMZA.ProMap.prototype.initMarkerListing = function()
	{
		if(WPGMZA.is_admin == "1")
			return;	// NB: No marker listings on the back end
		
		/*if(this.markerListing)
		{
			console.warn("Marker listing already initialized. No action will be taken.");
			return;
		}*/
		
		var markerListingElement = $("[data-wpgmza-marker-listing][id$='_" + this.id + "']");
		
		// NB: This is commented out to allow the category filter to still function with "No marker listing". This will be rectified in the future with a unified filtering interface
		//if(markerListingElement.length)
		this.markerListing = WPGMZA.MarkerListing.createInstance(this, markerListingElement[0]);
	
		this.off("markersplaced", this._onMarkersPlaced);
		delete this._onMarkersPlaced;
	}
	
	WPGMZA.ProMap.prototype.initCustomFieldFilterController = function()
	{
		this.customFieldFilterController = WPGMZA.CustomFieldFilterController.createInstance(this.id);
	}
	
	WPGMZA.ProMap.prototype.initUserLocationMarker = function()
	{
		var self = this;
		
		if(this.settings.show_user_location != 1)
			return;
		
		var icon = this.settings.upload_default_ul_marker;
		var options = {
			id: WPGMZA.guid(),
			animation: WPGMZA.Marker.ANIMATION_DROP
		};
		
		if(icon && icon.length)
			options.icon = icon;
		
		var marker = WPGMZA.Marker.createInstance(options);
		
		marker.isFilterable = false;
		
		WPGMZA.watchPosition(function(position) {
			
			marker.setPosition({
				lat: position.coords.latitude,
				lng: position.coords.longitude
			});
			
			if(!marker.map)
				self.addMarker(marker);
			
			if(!self.userLocationMarker)
			{
				self.userLocationMarker = marker;
				self.trigger("userlocationmarkerplaced");
			}

			var directionsFromField = jQuery('body').find('.wpgmza-directions-from');
			directionsFromField.val(position.coords.latitude + ", " + position.coords.longitude);
			
			// WPGMZA.Geocoder.createInstance().geocode({
			//     latLng: {
			//     	lat: position.coords.latitude,
			//     	lng: position.coords.longitude
			//     }
			// }, function(results){
			// 	if (results.length > 0) {
			// 		directionsFromField.val(results[0]);
			// 	}
			//     console.log(results);
			// });
			
		});
	}
	
	WPGMZA.ProMap.prototype.initDirectionsBox = function()
	{
		if(WPGMZA.is_admin == 1)
			return;
		
		if(!this.directionsEnabled)
			return;
		
		this.directionsBox = WPGMZA.DirectionsBox.createInstance(this);
	}
	
	WPGMZA.ProMap.prototype.getMapObjectArrays = function()
	{
		var arrays = WPGMZA.Map.prototype.getMapObjectArrays.call(this);
		
		arrays.heatmaps = this.heatmaps;
		
		return arrays;
	}
	
	/**
	 * Adds the specified heatmap to the map
	 * @return void
	 */
	WPGMZA.ProMap.prototype.addHeatmap = function(heatmap)
	{
		if(!(heatmap instanceof WPGMZA.Heatmap))
			throw new Error("Argument must be an instance of WPGMZA.Heatmap");
		
		heatmap.map = this;
		
		this.heatmaps.push(heatmap);
		this.dispatchEvent({type: "heatmapadded", heatmap: heatmap});
	}
	
	/**
	 * Gets a heatmap by ID
	 * @return void
	 */
	WPGMZA.ProMap.prototype.getHeatmapByID = function(id)
	{
		for(var i = 0; i < this.heatmaps.length; i++)
			if(this.heatmaps[i].id == id)
				return this.heatmaps[i];
			
		return null;
	}
	
	/**
	 * Removes the specified heatmap and fires an event
	 * @return void
	 */
	WPGMZA.ProMap.prototype.removeHeatmap = function(heatmap)
	{
		if(!(heatmap instanceof WPGMZA.Heatmap))
			throw new Error("Argument must be an instance of WPGMZA.Heatmap");
		
		if(heatmap.map != this)
			throw new Error("Wrong map error");
		
		heatmap.map = null;
		
		// TODO: This shoud not be here in the generic class
		heatmap.googleHeatmap.setMap(null);
		
		this.heatmaps.splice(this.heatmaps.indexOf(heatmap), 1);
		this.dispatchEvent({type: "heatmapremoved", heatmap: heatmap});
	}
	
	/**
	 * Removes the specified heatmap and fires an event
	 * @return void
	 */
	WPGMZA.ProMap.prototype.removeHeatmapByID = function(id)
	{
		var heatmap = this.getHeatmapByID(id);
		
		if(!heatmap)
			return;
		
		this.removeHeatmap(heatmap);
	}
	
	WPGMZA.ProMap.prototype.getInfoWindowStyle = function()
	{
		if(!this.settings.other_settings)
			return WPGMZA.ProInfoWindow.STYLE_NATIVE_GOOGLE;
		
		var local = this.settings.other_settings.wpgmza_iw_type;
		var global = WPGMZA.settings.wpgmza_iw_type;
		
		if(local == "-1" && global == "-1")
			return WPGMZA.ProInfoWindow.STYLE_NATIVE_GOOGLE;
		
		if(local == "-1")
			return global;
		
		if(local)
			return local;
		
		return WPGMZA.ProInfoWindow.STYLE_NATIVE_GOOGLE;
	}
	
	WPGMZA.ProMap.prototype.fetchMarkers = function()
	{
		var self = this;
		
		if(WPGMZA.settings.wpgmza_settings_marker_pull != WPGMZA.MARKER_PULL_XML || WPGMZA.is_admin == "1")
		{
			var data, request;
			var filter = {
				map_id: this.id,
				mashup_ids: this.mashupIDs
			};
			
			if(WPGMZA.is_admin == "1")
			{
				filter.includeUnapproved = true;
				filter.excludeIntegrated = true;
			}
			
			if(this.shortcodeAttributes.acf_post_id)
			{
				if($.isNumeric(this.shortcodeAttributes.acf_post_id))
					filter.acf_post_id = this.shortcodeAttributes.acf_post_id;
				else if(this.shortcodeAttributes.acf_post_id == "this")
					filter.acf_post_id = WPGMZA.postID;
			}
			
			data = {
				filter: JSON.stringify(filter)
			};
			
			request = {
				useCompressedPathVariable: true,
				
				data: data,
				
				success: function(data, status, xhr) {
					self.onMarkersFetched(data);
				}
			};
			
			if(WPGMZA.is_admin == 1)
			{
				data.skip_cache = 1;
				request.useCompressedPathVariable = false;
			}
			
			this.showPreloader(true);
			WPGMZA.restAPI.call("/markers/", request);
		}
		else
		{
			var urls = [
				WPGMZA.markerXMLPathURL + this.id + "markers.xml"
			];
			
			if(this.mashupIDs)
				this.mashupIDs.forEach(function(id) {
					urls.push(WPGMZA.markerXMLPathURL + id + "markers.xml")
				});
			
			var unique = urls.filter(function(item, index) {
				return urls.indexOf(item) == index;
			});
			
			urls = unique;
			
			if(window.Worker && window.Blob && window.URL && WPGMZA.settings.enable_asynchronous_xml_parsing)
			{
				var source 	= WPGMZA.loadXMLAsWebWorker.toString().replace(/function\(\)\s*{([\s\S]+)}/, "$1");
				var blob 	= new Blob([source], {type: "text/javascript"});
				var worker	= new Worker(URL.createObjectURL(blob));
				
				worker.onmessage = function(event) {
					self.onMarkersFetched(event.data);
				};
				
				worker.postMessage({
					command: "load",
					protocol: window.location.protocol,
					urls: urls
				});
			}
			else
			{
				var filesLoaded = 0;
				var converter = new WPGMZA.XMLCacheConverter();
				var converted = [];
				
				for(var i = 0; i < urls.length; i++)
				{
					$.ajax(urls[i], {
						success: function(response, status, xhr) {
							converted = converted.concat( converter.convert(response) );
							
							if(++filesLoaded == urls.length)
								self.onMarkersFetched(converted);
						}
					});
				}
			}
		}
	}
	
	WPGMZA.ProMap.prototype.onMarkersFetched = function(data)
	{
		var self = this;
		var startFiltered = (this.shortcodeAttributes.cat && this.shortcodeAttributes.cat.length)
		
		this.showPreloader(false);
		
		for(var i = 0; i < data.length; i++)
		{
			var obj = data[i];
			var marker = WPGMZA.Marker.createInstance(obj);
			
			if(startFiltered)
			{
				marker.isFiltered = true;
				marker.setVisible(false);
			}
			
			this.addMarker(marker);
			
			// Legacy support
			if(window.marker_array)
				marker_array[this.id][obj.id] = marker;
		}
		
		var triggerEvent = function()
		{
			self._markersPlaced = true;
			self.trigger("markersplaced");
			self.off("filteringcomplete", triggerEvent);
		}
		
		if(this.shortcodeAttributes.cat)
		{
			var categories = this.shortcodeAttributes.cat.split(",");
			
			// Set filtering controls
			var select = $("select[mid='" + this.id + "'][name='wpgmza_filter_select']");
			
			for(var i = 0; i < categories.length; i++)
			{
				$("input[type='checkbox'][mid='" + this.id + "'][value='" + categories[i] + "']").prop("checked", true);
				select.val(categories[i]);
			}
			
			this.on("filteringcomplete", triggerEvent);
			
			// Force category ID's in case no filtering controls are present
			this.markerFilter.update({
				categories: categories
			});
		}
		else
			triggerEvent();
	}
	
	WPGMZA.ProMap.prototype.updateInfoWindowDistances = function()
	{
		var location = this.showDistanceFromLocation;
		
		this.markers.forEach(function(marker) {
			
			if(!marker.infoWindow)
				return;
			
			marker.infoWindow.updateDistanceFromLocation();
			
		});
	}

	WPGMZA.ProMap.prototype.hasVisibleMarkers = function(event)
	{
		 // grab markers
		 var markers = this.markers;
		 
		 // create variable for visible markers after filtering
		 var visible_markers = 0;
		 
		 // loop through all the markers
		 for (var i = 0; i < markers.length; i++)
		 {
			 // Find only visible markers after filtering
			 if(markers[i].isFilterable && markers[i].getVisible())
			 {
				 visible_markers++;
				 break;	// No need to iterate any further, at least one marker isi visible
			 }
		 }
		 
		 return visible_markers > 0; // Returns true if markers are visible, false if not
	}
	
});