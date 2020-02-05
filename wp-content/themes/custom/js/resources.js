'use strict';


var getUrl = window.location;
//var siteUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var siteUrl = '';
var local = false;
if(  ( window.location.href.indexOf('localhost') !== -1 ) ){
	siteUrl = 'http://localhost/worldoceansday';
	local = true;
} else{
	siteUrl = 'https://worldoceansday.kinsta.cloud';
	local = false;
}
var baseUrl = siteUrl + '/wp-json/wod-resources/v1/resources';
var perPage = 10;
var page = 1;
var totalItems = 0;
var currentItems = 0;
var totalPages = 0;
var full = false;
var updating = false;
var adding = false;
var currentTopic = 'all';
var currentType = 'all';
var currentLanguage = 'all';
var filtered = false;

var emptyMessage = '<div class="col"><div class="error"><h3>No resources found with those parameters</h3></div></div>';
var errorMessage = '<div class="col"><div class="error"><h3>Oops, something went wrong. Please try again.</h3></div></div>';


function resources() {
	//console.log('resources.js loaded');

	$(document).ready( function() {

		if( $('body').hasClass('post-type-archive-resources') ){

			//console.clear();
			//console.log('----- Initializing resources -----');

			initialRequest();

			$('.filter-menu-button').click(function() {
				toggleFilterMenu($(this));
			});

			$('.filter-button').click(function(e) {
				e.preventDefault();
				updateButtons($(this), false);
			});

			$('.filter-clear').click(function() {
				page = 1;
				getResources();
				filtered = false;
				$('.filter-menu').removeClass('open');
				$('.filter-menu-button').removeClass('active');
				updateButtons( $('#filter-button-all-topic'), true );
				updateButtons( $('#filter-button-all-type'), true );
				updateButtons( $('#filter-button-all-language'), true );
			});

			$('.paginate-next').click(function(e) {
				e.preventDefault();

				if( !full ){
					page++;
					adding = true;
					getResources(currentTopic, currentType, currentLanguage);
				}

			});

			window.addEventListener('popstate', function(e) {
				if( e.state == null ){

				} else{
					var topic = e.state.topic;
					var type = e.state.type;
					var language = e.state.language;
					page = 1;
					getResources(topic, type, language);
					updateButtons();
				}

			});

		}


	});// end document.ready


	function initialRequest(){

		var initialUrlVars = getUrlVars();

		var topic = initialUrlVars.topic;
		var type = initialUrlVars.type;
		var language = initialUrlVars.language;

		if( !isEmpty(topic) ){
			if( topic !== 'all' ){
				filtered = true;
				updateButtons( $('.filter-button-topic[data-slug="' + topic + '"]' ) , true );
			}
		}
		if( !isEmpty(type) ){
			if( type !== 'all' ){
				filtered = true;
				updateButtons( $('.filter-button-type[data-slug="' + type + '"]' ) , true );
			}
		}
		if( !isEmpty(language) ){
			if( language !== 'all' ){
				filtered = true;
				updateButtons( $('.filter-button-language[data-slug="' + language + '"]' ) , true );
			}
		}

		getResources(topic, type, language);

	}


	function getResources(topic = 'all', type = 'all', language = 'all'){

		currentTopic = topic;
		currentType = type;
		currentLanguage = language;
		updateUrl();

		var parameters = '?topic=' + topic + '&type=' + type + '&language=' + language;
		var additionalParameters = '&per_page=' + perPage + '&page=' + page;
		var endpoint = baseUrl + parameters + additionalParameters;
		//console.log(endpoint);

		$.ajax({
			url: endpoint,
			dataType: 'json'
		})
		.done(function(data) {
			//console.log('successful request for resources');
			//console.log(data);

			if( data.post_count > 0 ){

				var html = data.html;
				currentItems = data.post_count;

				if(adding){
					$('#resources-container').append(html);
					adding = false;
				} else{
					totalItems = data.found_posts;
					totalPages = Math.ceil(totalItems / currentItems);
					$('#resources-container').html(html);
				}

				//console.log('page: ' + page);
				//console.log('totalPages: ' + totalPages);

				if(page === totalPages){
					//console.log('full');
					full = true;
					$('.load-more-button').removeClass('active');
				} else{
					//console.log('not full');
					full = false;
					$('.load-more-button').addClass('active');
				}

				bindEvents();

			} else{
				console.log('no resources found with this request');
				$('#resources-container').html(emptyMessage);
			}

		})
		.fail(function() {
			console.log('error getting resources from API');
			$('#resources-container').html(emptyMessage);
		})
		.always(function() {
			//console.log('completed request for resources');
		});

	}


	function bindEvents(){
		$( '.resource-preview' ).click(function(e){
			e.preventDefault();
			var target = $(this).data('modal-target');

			if($(this).hasClass('resource-preview')){
				var url = $(this).data('file-url');
				var iframe = '<iframe id="resource-iframe" class="resource-iframe" frameborder="0" vspace="0" hspace="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" allowtransparency="true" src="' + url + '" scrolling="auto"></iframe>';
				$('#resource-modal-inner').html(iframe);
			}

			modalToggle(target, false);	
		});
	}


	function modalToggle(_target, swap){

		var modalTarget = '#' + _target;

		if(swap){
			console.log(modalTarget);
			$('.modal').removeClass('on');
			$(modalTarget).removeClass('off').addClass('on');
		}
		else{
			if( $('body').hasClass( 'modal-off' ) ){
				$(modalTarget).removeClass('off').addClass('on');
				$('body').removeClass( 'modal-off' ).addClass( 'modal-on' );
			}	
		}

	}


	function toggleFilterMenu(button){

		var menu = $(button.data('menu'));

		if( button.hasClass('active') ){
			button.removeClass('active');
			closeMenu(menu);
		} else{
			$('.filter-menu-button').removeClass('active');
			button.addClass('active');
			$('.filter-menu').removeClass('open');
			openMenu(menu);
		}

	}


	function openMenu(menu){
		menu.addClass('open');
	}


	function closeMenu(menu){
		menu.removeClass('open');
	}


	function updateButtons(button, cosmetic){

		page = 1;

		var filterType = button.data('filter-type');
		var slug =  button.data('slug');
		var name =  button.data('name');

		if( slug !== 'all'){
			filtered = true;
		}

		if( !cosmetic ){		
			if( filterType ==='topic'){
				getResources(slug, currentType, currentLanguage);
			}else if( filterType ==='type'){
				getResources(currentTopic, slug, currentLanguage);
			}else if( filterType ==='language'){
				getResources(currentTopic, currentType, slug);
			}
		}

		if(filtered){
			$('#filter-clear').addClass('on');
		} else{
			$('#filter-clear').removeClass('on');
		}

		$('.filter-button-' + filterType ).removeClass('active');
		button.addClass('active');

		if( slug !== 'all'){
			$('#filter-menu-button-' + filterType).addClass('on');
			$('#filter-menu-button-label-' + filterType).html(': ' + name);
		} else{
			$('#filter-menu-button-' + filterType).removeClass('on');
			$('#filter-menu-button-label-' + filterType).html('');
		}

	}


	function isEmpty(val){
		return ( typeof val === 'undefined' || val === null || val.length <= 0 ) ? true : false;
	}


	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars(){
		var vars = [], hash;
		var url = stripTrailingSlash(window.location.href);
		var hashes = url.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++){
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	function updateUrl(){
		var stateObj = {
			topic : currentTopic,
			type : currentType,
			language : currentLanguage
		};
		var url = '/resources/?topic=' + currentTopic + '&type=' + currentType + '&language=' + currentLanguage;
		if(local){
			url = '/worldoceansday/resources/?topic=' + currentTopic + '&type=' + currentType + '&language=' + currentLanguage;
		}
		history.pushState(stateObj, 'Resources', url );
	}


	function stripTrailingSlash(url){
		return url.replace(/\/$/, "");
	}


	function throwError(){
		console.log('throwError');
	}


}


export { resources };