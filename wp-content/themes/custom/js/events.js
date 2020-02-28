'use strict';


var getUrl = window.location;
var siteUrl = '';
var local = false;
var staging = false;
if(  ( window.location.href.indexOf('localhost') !== -1 ) ){
	siteUrl = 'http://localhost/worldoceansday';
	//console.log('local environment');
	local = true;
} else if(( window.location.href.indexOf('staging.worldoceansday.org') !== -1 )){
	siteUrl = 'https://staging.worldoceansday.org';
	//console.log('staging environment');
	local = false;
	staging = true;
}
else{
	siteUrl = 'https://worldoceansday.org';
	//console.log('live environment');
	local = false;
}
var baseUrl = siteUrl + '/wp-json/wod-events/v1/list';
var baseMapUrl = siteUrl + '/wp-json/wod-events/v1/map-locations';
var perPage = 10;
var page = 1;
var totalItems = 0;
var currentItems = 0;
var totalPages = 0;
var full = false;
var updating = false;
var adding = false;
var currentCategory = 'all';
var currentCountry = 'all';
var currentStartDate = 'all';
var currentEndDate = 'all';
var filtered = false;
var dateFiltered = false;
var eventsMap, popup, Popup, markerCluster;
var markers = [];
var mapInitialized = false;
var picker;
var pickerInitialized = false;
var pickerVisible = false;

var clusterStyles = [
{
	textColor: 'white',
	url: siteUrl + '/wp-content/themes/custom/images/m/m1.svg',
	height: 50,
	width: 50
},
{
	textColor: 'white',
	url: siteUrl + '/wp-content/themes/custom/images/m/m1.svg',
	height: 50,
	width: 50
},
{
	textColor: 'white',
	url: siteUrl + '/wp-content/themes/custom/images/m/m2.svg',
	height: 50,
	width: 50
},
{
	textColor: 'white',
	url: siteUrl + '/wp-content/themes/custom/images/m/m2.svg',
	height: 50,
	width: 50
},
{
	textColor: 'white',
	url: siteUrl + '/wp-content/themes/custom/images/m/m2.svg',
	height: 50,
	width: 50
}
];
var mcOptions = {
	gridSize: 75,
	styles: clusterStyles
};

var emptyMessage = '<div class="col"><div class="wod-alert wod-alert-error">No events found with those settings.</div></div>';
var errorMessage = '<div class="col"><div class="wod-alert wod-alert-error">Oops, something went wrong. Please try again.</div></div>';


function events() {
	//console.log('events.js loaded');

	$(document).ready( function() {

		if( $('body').hasClass('page-id-13') ){

			//console.log('----- Initializing events -----');

			$('.filter-menu-button').click(function() {
				toggleFilterMenu($(this));

				if(dateFiltered == false){
					$('#litepicker').val('Date');
				}

				if( pickerVisible ){
					picker.hide();
				}
				
			});

			$('.filter-button').click(function(e) {
				e.preventDefault();
				toggleFilterMenu($(this));
				updateButtons($(this), false);
			});

			$('.filter-clear').click(function() {
				if( picker != false){
					picker.clearSelection();
					$('#litepicker').val('Date');
					$('#litepicker').removeClass('active');
				}
				page = 1;				
				filtered = false;
				dateFiltered = false;
				getEvents();
				$('.filter-menu').removeClass('open');
				$('.filter-menu-button').removeClass('active');
				updateButtons( $('#filter-button-all-category'), true );
				updateButtons( $('#filter-button-all-country'), true );
			});

			$('.paginate-next').click(function(e) {
				e.preventDefault();

				if( !full ){
					page++;
					adding = true;
					getEvents(currentCategory, currentCountry, currentStartDate, currentEndDate);
				}

			});

			//add condition for if litepicker is defined
			if( typeof Litepicker !== 'undefined'){
				picker = new Litepicker({ 
					element: document.getElementById('litepicker'),
					singleMode: false,
					splitView: true,
					format : 'MMM DD',
					firstDay: 0,
					onSelect : function(date1, date2) { 
						if(pickerInitialized){
							pickerSelect(date1, date2);
						}
					},
					onShow : function(){
						pickerVisible = true;
						if(dateFiltered == false){
							$('#litepicker').val('Date');
						}
					}, 
					onHide : function(){
						pickerVisible = false;
						if(dateFiltered == false){
							$('#litepicker').val('Date');
						}
					}
				});


				$('#litepicker').val('Date');

				$('#litepicker').click(function(e) {
					if(dateFiltered == false){
						$(this).val('Date');
					}
					toggleFilterMenu($('.filter-menu-button.active'));
				});	
			} else{
				picker = false;
				$('#litepicker').addClass('hidden');
			}

			initialRequest();

			//list your event banner
			$('#list-your-event-banner-close').click(function(e){
				e.preventDefault();
				$('#list-your-event-banner').slideUp('slow');
				var cookie = 'wod_show_list_your_event_banner';
				var d = new Date();
				d.setHours(168);
				var expires = 'expires='+d.toUTCString();
				document.cookie = cookie + '=' + 'false' + ';' + expires + ';path=/';
			});

			window.addEventListener('popstate', function(e) {
				if( e.state == null ){

				} else{
					var category = e.state.category;
					var country = e.state.country;
					page = 1;
					getEvents(category, country, currentStartDate, currentEndDate);
					updateButtons();
				}

			});

		}


	});// end document.ready


	function initialRequest(){

		var initialUrlVars = getUrlVars();

		var category = initialUrlVars.category;
		var country = initialUrlVars.country;
		var startDate = initialUrlVars.startDate;
		var endDate = initialUrlVars.endDate;

		if( !isEmpty(category) ){
			if( category !== 'all' ){
				filtered = true;
				updateButtons( $('.filter-button-category[data-slug="' + category + '"]' ) , true );
			}
		}

		if( !isEmpty(country) ){
			if( country !== 'all' ){
				filtered = true;
				updateButtons( $('.filter-button-country[data-slug="' + country + '"]' ) , true );
			}
		}

		//console.log('from url startDate: ' + startDate);
		//console.log('from url endDate: ' + endDate);

		if( !isEmpty(startDate) || !isEmpty(endDate) ){

			if( startDate != 'all' || endDate != 'all'){
				//console.log('at least one is not set to all');
				filtered = true;
				dateFiltered = true;
				updatePickerButton();

				if( startDate == 'all' && endDate !== 'all'){
					//console.log('start is all, end is not');
					var today = new Date();
					startDate = formatDate(today);
				} else if( startDate !== 'all' && endDate == 'all'){
					//console.log('end is all, start is not');
					var today = new Date();
					endDate = formatDate(today);
				} else{
					//console.log('both are not all');
				}

				var start = new Date(startDate);
				var end = new Date(endDate);
				var pickerStartDate = start.setDate(start.getDate()+1);
				var varpickerEndDate = end.setDate(end.getDate()+1);
				//console.log('updating picker with: ');
				//console.log(pickerStartDate, varpickerEndDate);
				if( picker != false){
					picker.setDateRange(pickerStartDate, varpickerEndDate);
				}

			} else{
				//console.log('start and end are both all')
			}

		}
		if( picker != false){
			pickerInitialized = true;
		}

		getEvents(category, country, startDate, endDate);

	}



	function getEvents(category = 'all', country = 'all', startDate = 'all', endDate = 'all'){

		if(!updating){
			updating = true;
			updateView();

			currentCategory = category;
			currentCountry = country;
			currentStartDate = startDate;
			currentEndDate = endDate;
			updateUrl();

			var parameters = '?category=' + category + '&country=' + country + '&startDate=' + startDate + '&endDate=' + endDate;
			var additionalParameters = '&per_page=' + perPage + '&page=' + page;
			var endpoint = baseUrl + parameters + additionalParameters;


			if(adding === false){

				var mapParameters = parameters;
				var mapEndpoint = baseMapUrl + mapParameters;

				//console.log(mapEndpoint);

				//map request
				$.ajax({
					url: mapEndpoint,
					dataType: 'json'
				})
				.done(function(data) {
					//console.log('successful request for events');
					//console.log(data);

					if( data.data.length > 0 ){
						if( !mapInitialized ){
							initMap(data.data);
						} else{
							reloadMarkers(data.data);
						}
					} else{
						console.log('no map events found with this request');
						if( !mapInitialized ){
							initMap();
						} else{
							reloadMarkers();
						}
					}

				})
				.fail(function() {
					console.log('error getting map events from API');
				})
				.always(function() {
					//console.log('completed request for events');
				});

			}	

			//console.log(endpoint);

			//list request
			$.ajax({
				url: endpoint,
				dataType: 'json'
			})
			.done(function(data) {
				//console.log('successful request for events');
				//console.log(data);

				if( data.post_count > 0 ){

					var html = data.html;
					currentItems = data.post_count;

					if(adding){
						$('#events-container').append(html);
						adding = false;
						updating = false;
						updateView();
						//console.log('adding');
					} else{
						totalItems = data.found_posts;
						totalPages = Math.ceil(totalItems / currentItems);
						$('#events-container').html(html);
						updating = false;
						updateView();
						//console.log('notadding');
					}

					updatePagination();
					bindEvents();

				} else{
					console.log('no events found with this request');
					updating = false;
					updateView();
					$('#events-container').html(emptyMessage);
					totalPages = 0;
					updatePagination();
				}

			})
			.fail(function() {
				console.log('error getting events from API');
				$('#events-container').html(emptyMessage);
				updating = false;
				updateView();
				console.log('error');
			})
			.always(function() {
				//console.log('completed request for events');
			});


		}


	}



	function pickerSelect(date1, date2){

		if( picker != false){

			//console.log('onSelect with dates:');
			//console.log(date1, date2); 

			filtered = true;
			dateFiltered = true;

			updatePickerButton();

			var startDate = formatDate(date1);
			var endDate = formatDate(date2);
			//console.log(startDate);
			//console.log(endDate);

			getEvents(currentCategory, currentCountry, startDate, endDate);

		}

	}


	function updatePickerButton(){

		if( picker != false){

			//console.log('updatePickerButton');
			//console.log('filtered: ' + filtered);

			//console.log(picker);

			var pickerInput = $('#litepicker');

			if( pickerInput.hasClass('active') === false ){
				pickerInput.addClass('active');
			}

			if(filtered){
				//$('#filter-clear').addClass('on');
			} else{
				pickerInput.val('Date');
			}

		}

	}


	function formatDate(date){
		var year = date.getFullYear();

		var month = (1 + date.getMonth()).toString();
		month = month.length > 1 ? month : '0' + month;

		var day = date.getDate().toString();
		day = day.length > 1 ? day : '0' + day;

		return year + '-' + month + '-' + day;
	}



	function updateView(){

		//console.log('updateView');
		
		if($('body').hasClass('filter-loading') ){
			$('body').removeClass('filter-loading'); 
		} else{
			$('body').addClass('filter-loading'); 
		}

		if(updating){
			//console.log('in updateView -  updating');
			$('#filter-screen').addClass('on');
		}else {
			$('#filter-screen').removeClass('on');
			//console.log('in updateView - NOT updating');
			if(filtered){
				$('#filter-clear').addClass('on');
			} else{
				$('#filter-clear').removeClass('on');
			}
			if(dateFiltered == false){
				$('#litepicker').val('Date');
			}	
		}

	}


	function updatePagination(){

		console.log('page: ' + page);
		console.log('totalPages: ' + totalPages);

		if( (page === totalPages) || totalPages == 0){
			//console.log('full');
			full = true;
			$('.load-more-button').removeClass('active');
		} else{
			//console.log('not full');
			full = false;
			$('.load-more-button').addClass('active');
		}

		if(totalPages > 0){
			var currentSpanTop = page * perPage;
			if( currentSpanTop > totalItems ){ 
				currentSpanTop = totalItems;
			}
			//var currentSpanBottom = currentSpanTop - perPage + 1;// use this with real pagination
			var currentSpanBottom = 1;
			var summary = currentSpanBottom + ' - ' + currentSpanTop + ' of ' + totalItems + ' Events';
		} else{
			var summary = 'No Events Found';
		}


		$('#filter-summary-events').html(summary);

	}


	function bindEvents(){

	}


	function toggleFilterMenu(button){

		var menu = $(button.data('menu'));

		if( button.hasClass('active') ){
			button.removeClass('active');
			closeMenu(menu);
		} else{
			$('.filter-menu-button').removeClass('active');
			button.addClass('active');
			$('.filter-menu').removeClass('open');
			openMenu(menu);
		}

	}


	function openMenu(menu){
		menu.addClass('open');
	}


	function closeMenu(menu){
		menu.removeClass('open');
	}


	function updateButtons(button, cosmetic = false){

		page = 1;

		var filterType = button.data('filter-type');
		var slug =  button.data('slug');
		var name =  button.data('name');

		if( slug !== 'all'){
			filtered = true;
		}

		if( !cosmetic ){		
			if( filterType ==='category'){
				getEvents(slug, currentCountry, currentStartDate, currentEndDate);
			}else if( filterType ==='country'){
				getEvents(currentCategory, slug, currentStartDate, currentEndDate);
			}
		}

		$('.filter-button-' + filterType ).removeClass('active');
		button.addClass('active');

		if( slug !== 'all'){
			$('#filter-menu-button-' + filterType).addClass('on');
			$('#filter-menu-button-label-' + filterType).html(': ' + name);
		} else{
			$('#filter-menu-button-' + filterType).removeClass('on');
			$('#filter-menu-button-label-' + filterType).html('');
		}

	}


	//map related functions below

	function initMap(locations) {
		//console.log('init map');

		var center = {lat: 0, lng: 0};

		var zoom = 2;
		var minZoom = 2;
		var maxZoom = 15;
		var gestureHandling = 'cooperative';
		if( $(window).width() < 768 ){
			console.log('mobile map settings');
			zoom = 1;
			minZoom = 1;
			gestureHandling = 'greedy';
		}

		eventsMap = new google.maps.Map( document.getElementById('events-map'), {
			zoom: zoom, 
			maxZoom: maxZoom,
			minZoom: minZoom,
			center: center,
			mapTypeControl: false,
			streetViewControl: false,
			rotateControl: false, 
			gestureHandling: gestureHandling
		} );		

		//console.log(locations);
		setMarkers(locations);

		if(mapInitialized === false){
			mapInitialized = true;
		}

	}



	function setMarkers(locations){
		//console.log('set markers');

		if(!isEmpty(locations)){

			var bounds = new google.maps.LatLngBounds();

			locations.forEach( function( location ) {
				location.marker.position.lat = parseFloat(location.marker.position.lat);
				location.marker.position.lng = parseFloat(location.marker.position.lng);
				var popupContent = location.marker.popup.marker_card;
				location.marker.popup.content = popupContent;

				var marker = new google.maps.Marker({
					position: location.marker.position,
					content: location.marker.popup.marker_card,
					map: eventsMap
					//animation: google.maps.Animation.DROP
				//icon: siteUrl + '/wp-content/themes/custom/images/marker_v-07.svg'
			});

				marker.addListener('click', function() {
					infowindow.open(eventsMap, marker);
				});

				markers.push(marker);

				bounds.extend(marker.getPosition());

				var infowindow = new google.maps.InfoWindow({
					content: marker.content
				});

			});

			markerCluster = new MarkerClusterer(eventsMap, markers, mcOptions);

			if (mapInitialized){
				eventsMap.fitBounds(bounds);
			}

		} else{
			markerCluster = new MarkerClusterer(eventsMap, markers, mcOptions);
			console.log('set markers but locations is empty');
		}

	}



	function reloadMarkers(locations){
		//console.log('reload markers');

		for (var i=0; i<markers.length; i++) {
			markers[i].setMap(null);
		}

		markers = [];

		markerCluster.clearMarkers();

		setMarkers(locations);

	}



	//helper functions below

	function isEmpty(val){
		return ( typeof val === 'undefined' || val === null || val.length <= 0 ) ? true : false;
	}


	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars(){
		var vars = [], hash;
		var url = stripTrailingSlash(window.location.href);
		var hashes = url.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++){
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	function updateUrl(){
		var stateObj = {
			category : currentCategory,
			country : currentCountry,
			startDate : currentStartDate,
			endDate : currentEndDate
		};
		var url = '/events/?category=' + currentCategory + '&country=' + currentCountry + '&startDate=' + currentStartDate + '&endDate=' + currentEndDate;
		if(local){
			url = '/worldoceansday/events/?category=' + currentCategory + '&country=' + currentCountry + '&startDate=' + currentStartDate + '&endDate=' + currentEndDate;
		}
		history.pushState(stateObj, 'Events', url );
	}


	function stripTrailingSlash(url){
		return url.replace(/\/$/, "");
	}


	function throwError(){
		console.log('throwError');
	}


}


export { events };