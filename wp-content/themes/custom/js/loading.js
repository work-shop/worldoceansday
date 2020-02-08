'use strict';

var getUrl = window.location;
var siteUrl = '';
var local = false;
if(  ( window.location.href.indexOf('localhost') !== -1 ) ){
	siteUrl = 'http://localhost/worldoceansday';
	local = true;
} else{
	siteUrl = 'https://worldoceansday.kinsta.cloud';
	local = false;
}
var baseUrl = siteUrl + '/wp-json/wod-partials/v1/';

function loading( config ){
	//console.log('loading.js loaded');


	$( document ).ready( function() {

		setTimeout(function(){
			$( '.' + config.loadingClass ).addClass( config.loadedClass );
		}, config.loadDelay );

		getPartial('supporters-slideshow', $('#supporters-slideshow-target'));

	});



	function getPartial( endpoint, target ){

		var url = baseUrl + endpoint;

		$.ajax({
			url: url,
			dataType: 'json'
		})
		.done(function(data) {
			//console.log('successful request for partial');
			//console.log(data);

			if( data ){

				target.html(data);

			} else{

			}

		})
		.fail(function() {
			console.log('error getting partial from API');
		})
		.always(function() {
			//console.log('completed request for events');
		});

	}





}


export { loading };