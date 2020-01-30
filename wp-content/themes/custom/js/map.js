'use strict';

var mapModule = require( '@work-shop/map-module' );
var tileStyle = require( './tile-style.json' );

function makeMap () {
  //console.log('map.js loaded');
  
  var brandColor = '#164579';
  //var latLng = { lat: 30, lng: 10 };

  $( document ).ready( function() {

    return mapModule( {
      selector: '.ws-map',
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false,
      styles: tileStyle,
      //center: latLng,
      // zoom: 7,
      // render: {
      //   zoom: 1,
      // },
      marker: {
       icon: {
        fillColor: brandColor,
        url: 'http://localhost/worldoceansday/wp-content/themes/custom/images/marker2.png',
        size: new google.maps.Size(10,10),
        scaledSize: new google.maps.Size(10,10)//,
        //origin: new google.maps.Point( 44, 68 ),
        //anchor: new google.maps.Point( 22, 0 )
      },
      popup: {
        pointer: '10px',
      }
    },
  } );

  } );


}

export { makeMap };
