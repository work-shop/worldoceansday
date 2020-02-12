'use strict';

function submitEvent() {
	//console.log('submit-event.js loaded');

	var emailMarkup = '<small class="description">A password will be emailed to you</small>';

	var accountCreationActivated = false;

	$(document).ready( function() {

		if( $('#event_preview').length > 0){
			//console.log('on preview page');
			$('body').addClass('event-submit-preview');
		} else if( $('.post-submitted-success-green-message').length > 0 ){
			//console.log('on success page');
			$('body').addClass('event-submit-success');
		}
		else{
			//console.log('on submit page');
			$('body').addClass('event-submit-submit');
		}

		//submit event page
		$('.wpem-form-group:first-child').remove();

		$('.fieldset-create_account_email label').html('Email address for your account');

		$('.fieldset-create_account_email').addClass('hidden');

		$('.fieldset-add_my_organization_to_the_world_oceans_day_network input').after('<small class="checkbox-label description">Add My Organization</small>');

		$('.submit-event-button-create').click(function(e) {
			e.preventDefault();
			activateAccountCreation();
		});

		if( $('body').hasClass('event-submit-submit') && $('.event-manager-error.wpem-alert').length > 0 ){
			$('body').addClass('event-submit-submit-error');
			activateAccountCreation();
		}


		//$('.fieldset-create_account_email .field').append(emailMarkup);

		
	});

	function activateAccountCreation(){
		if (accountCreationActivated === false){
			accountCreationActivated = true;
			$('.fieldset-create_account_email').removeClass('hidden');
			var accountCreationMessage = $('#create-account-message-placeholder');
			accountCreationMessage.removeClass('hidden');
			$('#submit-event-form').prepend(accountCreationMessage);
		}
	}
}

export { submitEvent };
