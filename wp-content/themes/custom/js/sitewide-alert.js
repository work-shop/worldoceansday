'use strict';


function sitewideAlert() {
	//console.log('sitewideAlert.js loaded');

	$(document).ready( function() {

		$('#sitewide-alert-close').click(function(e) {
			e.preventDefault();
			$('#sitewide-alert').addClass('hidden');
			$('body').removeClass('sitewide-alert-on');
			var cookie = 'ws_show_sitewide_alert';
			var d = new Date();
			d.setHours(23,59,59,999);
			var expires = 'expires='+d.toUTCString();
			document.cookie = cookie + '=' + 'false' + ';' + expires + ';path=/';
		});

	});

}

export { sitewideAlert };
