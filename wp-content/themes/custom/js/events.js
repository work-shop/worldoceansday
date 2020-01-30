"use strict";


function events(config){
	//console.log("events.js loaded");

	$(document).ready( function() {

		//list your event banner
		$('#list-your-event-banner-close').click(function(e){
			$('#list-your-event-banner').slideUp('slow');
			var cookie = 'wod_show_list_your_event_banner';
			var d = new Date();
			d.setHours(168);
			var expires = 'expires='+d.toUTCString();
			document.cookie = cookie + '=' + 'false' + ';' + expires + ';path=/';
		});

	});

}


export { events };