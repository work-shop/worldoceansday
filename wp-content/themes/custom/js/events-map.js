
var mapInitialized = false;

$(document).ready( function() {

	//updateView();

});

function initMap() {
	console.log('init map');

	mapOptions.data.forEach( function( location ) {
		//console.log(location.marker);
		location.marker.position.lat = parseFloat(location.marker.position.lat);
		location.marker.position.lng = parseFloat(location.marker.position.lng);
	});

	var locations = mapOptions.data; 

	var center = {lat: 0, lng: 0};

	var map = new google.maps.Map( document.getElementById('events-map'), {zoom: 2, center: center} );

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


function updateView(){
	console.log('updateView');
	if(mapInitialized && $('#events-container').hasClass('loading') ){
		$('#events-container').removeClass('loading'); 
	}
}