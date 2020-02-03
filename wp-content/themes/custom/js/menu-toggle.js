"use strict";

function menuToggle( config ) {
	//console.log('menu-toggle.js loaded');

	$(document).ready( function() {
		$(config.menuToggleSelector).click(function(e) {
			e.preventDefault();
			menuToggle();
		});		

		$('.search-trigger').click(function(e) {
			e.preventDefault();
			searchToggle();
		});				
		
	});

	//open and close the menu
	function menuToggle(){
		//console.log('menuToggle');

		if($('body').hasClass(config.bodyOffClass)){
			$(config.menuToggleSelector).removeClass('closed').addClass('open');
			$(config.blanketSelector).removeClass('off').addClass('on');						
			$('body').removeClass(config.bodyOffClass).addClass(config.bodyOnClass);
		}
		else if($('body').hasClass(config.bodyOnClass)){
			$(config.menuToggleSelector).removeClass('open').addClass('closed');
			$(config.blanketSelector).removeClass('on').addClass('off');			
			$('body').removeClass(config.bodyOnClass).addClass(config.bodyOffClass);
			$('body').removeClass('dropdown-on').addClass('dropdown-off');
			$('.has-sub-menu').removeClass('open').addClass('closed');
		}

	}	

	function searchToggle(){

		if($('#search-box').hasClass('off')){
			$('input.orig').focus();
			$('#search-box').removeClass('off').addClass('on');
			$('#overlay-search').removeClass('off').addClass('on');						
		}
		else if($('#search-box').hasClass('on')){
			$('#search-box').removeClass('on').addClass('off');
			$('#overlay-search').removeClass('on').addClass('off');						
		}

	}	

}

export { menuToggle };
