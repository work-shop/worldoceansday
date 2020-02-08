//Dynamic Form Selection
jQuery(document).ready(function($) {
	/*Group Status*/
	$('.perfmatters-script-manager-group-status .perfmatters-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section .perfmatters-script-manager-assets-disabled').hide();
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section table').show();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section table').hide();
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section .perfmatters-script-manager-assets-disabled').show();
		}
	});
	$('.perfmatters-script-manager-group-status .perfmatters-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section table').hide();
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section .perfmatters-script-manager-assets-disabled').show();
		}
		else {
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section .perfmatters-script-manager-assets-disabled').hide();
			$(this).closest('.perfmatters-script-manager-group').find('.perfmatters-script-manager-section table').show();
		}
	});

	/*Script Status*/
	$('.perfmatters-script-manager-status .perfmatters-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('tr').find('.perfmatters-script-manager-controls').hide();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('tr').find('.perfmatters-script-manager-controls').show();
		}
	});
	$('.perfmatters-script-manager-status .perfmatters-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('tr').find('.perfmatters-script-manager-controls').show();
		}
		else {
			$(this).closest('tr').find('.perfmatters-script-manager-controls').hide();
		}
	});

	/*Disable Radio*/
	$('.perfmatters-disable-select').on('change', function(ev) {
		if($(this).val() == 'everywhere') {
			$(this).closest('.perfmatters-script-manager-controls').find('.perfmatters-script-manager-enable').show();
		}
		else {
			$(this).closest('.perfmatters-script-manager-controls').find('.perfmatters-script-manager-enable').hide();
		}
		if($(this).val() == 'regex') {
			$(this).closest('.perfmatters-script-manager-controls').find('.pmsm-disable-regex').show();
		}
		else {
			$(this).closest('.perfmatters-script-manager-controls').find('.pmsm-disable-regex').hide();
		}
	});

	/*Reset Button*/
	$('.pmsm-reset').click(function(ev) {
		ev.preventDefault();
		$('#pmsm-reset-form').submit();
	});
});