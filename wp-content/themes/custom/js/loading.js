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

		if( $('body').hasClass('home')){
			getPartial('supporters-slideshow', $('#supporters-slideshow-target'));
		}

		if( $('.subscribe-form-target').length > 0 ){
			$('.subscribe-form-target').each(function(index, el) {
				var currentPage = $(this).data('page');
				var formID = $(this).data('form-id');
				console.log(currentPage);
				var partialEndpoint = 'subscribe-form?currentPage=' + currentPage + '&formId=' + formID;
				var target = $('#' + $(this).attr('id'));
				//console.log(target);
				getPartial(partialEndpoint, target);
			});
			
		}


	});



	function getPartial( endpoint, target ){

		var url = baseUrl + endpoint;
		console.log(url);

		$.ajax({
			url: url,
			dataType: 'json'
		})
		.done(function(data) {
			//console.log('successful request for partial');
			console.log(data);

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