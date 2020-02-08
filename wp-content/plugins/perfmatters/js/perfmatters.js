//Perfmatters Admin JS
jQuery(document).ready(function($) {
	
	$('#perfmatters-add-preconnect').on('click', function(ev) {
		ev.preventDefault();

		var rowCount = $(this).prop('rel');

		rowCount++;
		
		$('#perfmatters-preconnect-wrapper').append("<div class='perfmatters-preconnect-row'><input type='text' id='preconnect-" + rowCount + "-url' name='perfmatters_extras[preconnect][" + rowCount + "][url]' value='' placeholder='https://example.com' /><label for='preconnect-" + rowCount + "-crossorigin'><input type='checkbox' id='preconnect-" + rowCount + "-crossorigin' name='perfmatters_extras[preconnect][" + rowCount + "][crossorigin]' value='1' /> CrossOrigin</label><a href='#' class='perfmatters-delete-preconnect' title='Remove'><span class='dashicons dashicons-no'></span></a></div>");

		$(this).prop('rel', rowCount);

	});

	$('#perfmatters-preconnect-wrapper').on('click', '.perfmatters-delete-preconnect', function(ev) {
		console.log('clicked');
		ev.preventDefault();

		var siblings = $(this).closest('div').siblings();
		
		$(this).closest('div').remove();

		siblings.each(function(i){

			var url = $(this).find('input:text');

			url.attr('id', 'preconnect-' + i + '-url');
			url.attr('name', 'perfmatters_extras[preconnect][' + i + '][url]');

			var crossorigin = $(this).find('input:checkbox');

			crossorigin.attr('id', 'preconnect-' + i + '-crossorigin');
			crossorigin.attr('name', 'perfmatters_extras[preconnect][' + i + '][crossorigin]');

		})

		var rowCount = $('#perfmatters-add-preconnect').prop('rel');
		$('#perfmatters-add-preconnect').prop('rel', rowCount - 1);

	});

	//validate Login URL
	$(".perfmatters-admin #login_url").keypress(function(e) {
		var code = e.which;
		var character = String.fromCharCode(code);
		if(!perfmattersValidateInput(character, /^[a-z0-9-]+$/)) {
			e.preventDefault();
		};
	});
});

//validate settings input
function perfmattersValidateInput(input, pattern) {
	if(input.match(pattern)) {
		return true;
	} else {
		return false;
	}
}