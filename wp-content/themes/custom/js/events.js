'use strict';


var getUrl = window.location;
//var siteUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var siteUrl = '';
var local = false;
if(  ( window.location.href.indexOf('localhost') !== -1 ) ){
	siteUrl = 'http://localhost/worldoceansday';
	local = true;
} else{
	siteUrl = 'https://worldoceansday.kinsta.cloud';
	local = false;
}
var baseUrl = siteUrl + '/wp-json/wod-events/v1/list';
var baseMapUrl = siteUrl + '/wp-json/wod-events/v1/map-locations';
var perPage = 3;
var page = 1;
var totalItems = 0;
var currentItems = 0;
var totalPages = 0;
var full = false;
var updating = false;
var adding = false;
var currentCategory = 'all';
var currentCountry = 'all';
var filtered = false;
var eventsMap;
var mapInitialized = false;

var emptyMessage = '<div class="col"><div class="error"><h3>No events found with those parameters</h3></div></div>';
var errorMessage = '<div class="col"><div class="error"><h3>Oops, something went wrong. Please try again.</h3></div></div>';


function events() {
	//console.log('events.js loaded');

	$(document).ready( function() {

		if( $('body').hasClass('page-id-13') ){

			console.clear();
			console.log('----- Initializing events -----');

			initialRequest();

			$('.filter-menu-button').click(function() {
				toggleFilterMenu($(this));
			});

			$('.filter-button').click(function(e) {
				e.preventDefault();
				updateButtons($(this), false);
			});

			$('.filter-clear').click(function() {
				page = 1;
				getEvents();
				filtered = false;
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
					getEvents(currentCategory, currentCountry);
				}

			});

			//list your event banner
			$('#list-your-event-banner-close').click(function(e){
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
					getEvents(category, country);
					updateButtons();
				}

			});

		}


	});// end document.ready


	function initialRequest(){

		var initialUrlVars = getUrlVars();

		var category = initialUrlVars.category;
		var country = initialUrlVars.country;

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

		getEvents(category, country);

	}


	function getEvents(category = 'all', country = 'all'){

		if(!updating){
			updating = true;
			updateView();

			currentCategory = category;
			currentCountry = country;
			updateUrl();

			var parameters = '?category=' + category + '&country=' + country;
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
						initMap(data);
					} else{
						console.log('no map events found with this request');
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
					} else{
						totalItems = data.found_posts;
						totalPages = Math.ceil(totalItems / currentItems);
						$('#events-container').html(html);
						updating = false;
						updateView();
					}

					updatePagination();
					bindEvents();

				} else{
					console.log('no events found with this request');
					updating = false;
					updateView();
					$('#events-container').html(emptyMessage);
				}

			})
			.fail(function() {
				console.log('error getting events from API');
				$('#events-container').html(emptyMessage);
				updating = false;
				updateView();
			})
			.always(function() {
				//console.log('completed request for events');
			});


		}


	}



	function updateView(){
		//console.log('updateView');
		if(mapInitialized && $('#events-wrapper').hasClass('filter-loading') ){
			$('#events-wrapper').removeClass('filter-loading'); 
		} else{
			$('#events-wrapper').addClass('filter-loading'); 
		}
	}


	function updatePagination(){

		// console.log('page: ' + page);
		// console.log('totalPages: ' + totalPages);

		if(page === totalPages){
			//console.log('full');
			full = true;
			$('.load-more-button').removeClass('active');
		} else{
			//console.log('not full');
			full = false;
			$('.load-more-button').addClass('active');
		}

		var currentSpanTop = page * perPage;
		if( currentSpanTop > totalItems ){ 
			currentSpanTop = totalItems;
		}
		//var currentSpanBottom = currentSpanTop - perPage + 1;// use this with real pagination
		var currentSpanBottom = 1;
		var summary = currentSpanBottom + ' - ' + currentSpanTop + ' of ' + totalItems + ' Events';
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


	function updateButtons(button, cosmetic){

		page = 1;

		var filterType = button.data('filter-type');
		var slug =  button.data('slug');
		var name =  button.data('name');

		if( slug !== 'all'){
			filtered = true;
		}

		if( !cosmetic ){		
			if( filterType ==='category'){
				getEvents(slug, currentCountry);
			}else if( filterType ==='country'){
				getEvents(currentCategory, slug);
			}
		}

		if(filtered){
			$('#filter-clear').addClass('on');
		} else{
			$('#filter-clear').removeClass('on');
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

	function initMap(mapData) {
		//console.log('init map');

		mapData.data.forEach( function( location ) {
			//console.log(location.marker);
			location.marker.position.lat = parseFloat(location.marker.position.lat);
			location.marker.position.lng = parseFloat(location.marker.position.lng);
		});

		var locations = mapData.data; 

		var center = {lat: 0, lng: 0};

		eventsMap = new google.maps.Map( document.getElementById('events-map'), {zoom: 2, center: center} );

		var markers = locations.map(function(location, i) {
			return new google.maps.Marker({
				position: location.marker.position
			});
		});

		var clusterStyles = [
		{
			textColor: 'white',
			url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m1.svg',
			height: 50,
			width: 50
		},
		{
			textColor: 'white',
			url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m1.svg',
			height: 50,
			width: 50
		},
		{
			textColor: 'white',
			url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
			height: 50,
			width: 50
		},
		{
			textColor: 'white',
			url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
			height: 50,
			width: 50
		},
		{
			textColor: 'white',
			url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/m/m2.svg',
			height: 50,
			width: 50
		}
		];

		var mcOptions = {
			gridSize: 100,
			styles: clusterStyles
		};

		var markerCluster = new MarkerClusterer(map, markers, mcOptions);

		if(mapInitialized === false){
			mapInitialized = true;
			updateView();
		}

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
			country : currentCountry
		};
		var url = '/events/?category=' + currentCategory + '&country=' + currentCountry;
		if(local){
			url = '/worldoceansday/events/?category=' + currentCategory + '&country=' + currentCountry;
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