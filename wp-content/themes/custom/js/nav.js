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

		var top = menuTitle.offset().top;
		var menuScrollTop = $('#menus').scrollTop();
		var navHeight = $('#nav').height();
		var bodyScrollTop =  $('html').scrollTop();
		var newScrollTop = (menuScrollTop + (top - navHeight) - bodyScrollTop);

		console.log('-------------------');
		console.log('bodyScrollTop: ' + bodyScrollTop);
		console.log('menuScrollTop: ' + menuScrollTop);
		console.log('offset.top: ' + top);
		console.log('newScrollTop: ' + newScrollTop);

		$('#menus').animate({
			scrollTop: newScrollTop
		}, 150);

	}else if( menu.hasClass('mobile-menu-open') ){

		var sibling = menuTitle.parent().prev();
		console.log(sibling);
		var top = sibling.offset().top;
		var menuScrollTop = $('#menus').scrollTop();
		var navHeight = $('#nav').height();
		var newScrollTop = menuScrollTop + (top - navHeight);	

		$('#menus').animate({
			scrollTop: newScrollTop
		}, 150);

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