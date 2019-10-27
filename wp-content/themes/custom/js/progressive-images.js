'use strict';

var progressiveLength = 0;
var progressiveBackgroundLength = 0;
var progressiveCount = 0;
var progressiveBackgroundCount = 0;



function progressiveImages(){
	//console.log('progressive.js loaded');

	$(window).on('load', function() {
		getProgressiveImages();
		getProgressiveBackgroundImages();
	});


	function getProgressiveImages(){
		var images = $('.progressive');
		progressiveLength = images.length;

		if (progressiveLength > 0){

			for(var i = 0; i < progressiveLength; i++){
				var container = $(images[i]);
				var src = container.data('src');
				var srcset = container.data('srcset');
				var sizes = container.data('sizes');
				var img = container.find('img');

				var newImg = $('<img>');
				newImg.attr('srcset',srcset);
				newImg.attr('sizes',sizes);
				newImg.attr('src',src);
				newImg.addClass('reveal');
				container.append(newImg);

				displayProgressiveImage(newImg,img);

			}

		}
	}


	function getProgressiveBackgroundImages(){
		var backgroundImages = $('.progressive-background');
		progressiveBackgroundLength = backgroundImages.length;

		if (progressiveBackgroundLength > 0){

			for(var i = 0; i < progressiveLength; i++){
				var element = $(backgroundImages[i]);
				var src = element.data('src');
				element.css('background-image', 'url("' + src + '")');
			}

		}
	}


	function displayProgressiveImage(_newImg,_oldImg){
		setTimeout(function() {
			_newImg.addClass('revealed');
			_oldImg.addClass('progessive-hidden');	
		}, 500);
		progressiveCount++;
	}

}


export { progressiveImages };


