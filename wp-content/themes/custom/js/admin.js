
console.log('admin.js');

jQuery(document).ready( function() {

	console.log('admin.js');

	$ = jQuery;

	var locationMessageHtml = $('.acf-field-5e3de7fdda967 .acf-input textarea').val();

	console.log(locationMessageHtml);

	$('.acf-field-5e3442a5e4f45 .acf-input').html(locationMessageHtml);


});

