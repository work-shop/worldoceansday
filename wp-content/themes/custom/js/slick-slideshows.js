'use strict';

var slick = require ('slick-carousel');

function slickSlideshows( config ) {
	//console.log('slick-slideshows.js loaded');

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

		$('.slick-home-supporters').slick({
			slidesToShow: 6,
			slidesToScroll: 6,
			dots: config.dots,
			arrows: config.arrows,
			autoplay: false,
			fade: false,
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

	});

}


export { slickSlideshows };
