'use strict';

var slick = require ('slick-carousel');


function slickSlideshows( config ) {
	//console.log('slick-slideshows.js loaded');

	var slickHomeSuppporters;

	$( document ).ready( function() {

		$('.slick-default').slick({
			slidesToShow: config.slidesToShow,
			dots: config.dots,
			arrows: config.arrows,
			autoplay: config.autoplay,
			fade: config.fade,
			autoplaySpeed: config.autoplaySpeed,
			speed: config.speed
		});

		$('.slick-supporters').slick({
			slidesToShow: config.slidesToShow,
			dots: config.dots,
			arrows: config.arrows,
			autoplay: false,
			fade: config.fade,
			autoplaySpeed: config.autoplaySpeed,
			speed: config.speed
		});


		$('.slick-history').slick({
			slidesToShow: 3,
			slidesToScroll: 2,
			dots: false,
			arrows: true,
			autoplay: false,
			fade: false,
			infinite: false,
			autoplaySpeed: config.autoplaySpeed,
			speed: config.speed,
			pauseOnDotsHover: true,
			responsive: [
			{
				breakpoint: 993,
				settings: {
					infinite: false,
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					infinite: false,
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
			]
		});

		//if($('body').hasClass('home')){
			//window.addEventListener('scroll', checkViewport);
			//window.addEventListener('resize', checkViewport);	
		//}


	});


	function checkViewport(){

		$('.block').each(function() {
			//console.log('-------');
			
			if (isInViewport($(this))) {
				//console.log($(this).attr('id') + ' in viewport');
				//$(this).addClass('in-viewport');

				if($(this).attr('id') === 'home-supporters'){
					slickHomeSuppporters.slick('slickPlay');
				}
				
			} else {
				//console.log($(this).attr('id') + ' NOT in viewport');
				//$(this).removeClass('in-viewport');
				if($(this).attr('id') === 'home-supporters'){
					slickHomeSuppporters.slick('slickPause');
				}
			}

			//console.log('-------');
		});
	}


	function isInViewport(element) {
		//console.log('in viewport function');
		var elementTop = $(element).offset().top;
		var elementBottom = elementTop + $(element).outerHeight();

		var viewportTop = $(window).scrollTop();
		var viewportBottom = viewportTop + $(window).height();

		//console.log('elementTop: ' + elementTop);
		//console.log('elementBottom: ' + elementBottom);
		//console.log('viewportTop: ' + viewportTop);
		//console.log('viewportBottom: ' + viewportBottom);

		var isInViewportFlag =  elementBottom > viewportTop && elementTop < viewportBottom;

		//console.log('isInViewportFlag: ' + isInViewportFlag);

		return isInViewportFlag;
	}



	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

}



export { slickSlideshows };
