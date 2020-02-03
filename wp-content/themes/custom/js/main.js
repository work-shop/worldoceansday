'use strict';

global.$ = require('jquery');
global.jQuery = global.$;
window.$ = global.$;

//must use
import { config } from './config.js';
import { loading } from './loading.js';
import { viewportLabel } from './viewport-label.js';
import { linksNewtab } from './links-newtab.js';
import { jqueryAccordian } from './jquery-accordian.js';
import { accordian } from './accordian.js';
import { jumpLinks } from './jump-links.js';
import { modals } from './modals.js';
import { slickSlideshows } from './slick-slideshows.js';
import { sitewideAlert } from './sitewide-alert.js';
import { progressiveImages } from './progressive-images.js';
import { vh } from './vh.js';
import { livereload } from './livereload-client.js';
import { nav } from './nav.js';
import { news } from './news.js';
//import { makeMap } from './map.js';
import { scrollSpy } from './scroll-spy.js';
import { events } from './events.js';

//optional
import { stickyNav } from './sticky-nav.js';
import { dropdowns } from './dropdowns.js';
import { menuToggle } from './menu-toggle.js';


//must use 
nav();
scrollSpy(config.scrollSpy);
livereload();
loading(config.loading);
linksNewtab(config.linksNewtab);
viewportLabel(config.viewportLabel);
jqueryAccordian();
accordian();
jumpLinks(config.jumpLinks);
modals(config.modals);
slickSlideshows(config.slickSlideshows);
sitewideAlert();
progressiveImages();
vh();
//makeMap();
stickyNav(config.stickyNav);
events(config.stickyNav);


//optional
dropdowns(config.dropdowns);
menuToggle(config.menuToggle);

$(document).ready( function() {
	if( $('body').hasClass('page-id-69')){
		news();
	}
});
