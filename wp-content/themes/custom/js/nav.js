"use strict";


function nav( config ) {
	//console.log("nav.js loaded");


	$(document).ready( function() {

		$('.menu-title-link').click(function(e) {
			if( $(window).width() < 992){
				e.preventDefault();
				toggleSubMenu($(this));
			}
		});

	});

}


function toggleSubMenu(link){
	var menuTitle = link.parent();
	var menu = menuTitle.parent();

	if ( menu.hasClass('mobile-menu-closed') ){
		menu.removeClass('mobile-menu-closed').addClass('mobile-menu-open');
	}else if( menu.hasClass('mobile-menu-open') ){
		menu.removeClass('mobile-menu-open').addClass('mobile-menu-closed');		
	}	
}


// function navHighlight() {

// 	var str = window.location.href.split(window.location.host);
// 	var currentUrl = str[1];
// 	//console.log('currentUrl: ' + currentUrl);

// 	var selector = '#page-nav a[href$="' + currentUrl + '"]';
// 	//console.log('selector: ' + selector);

// 	var activeLink = $(selector);
// 	//console.log(activeLink.attr('href'));
// 	activeLink.addClass('active');

// }


export { nav };