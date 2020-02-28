'use strict';


function addToCalendar() {
	//console.log('add-to-calendar.js loaded');

	$(document).ready( function() {

		if(false){

			var addToCalendars = $('.add-to-calendar-container');

			if( addToCalendars.length > 0){

				$('.add-to-calendar-container').each(function(index) {
					var startDate = $(this).data('start-date');
					var startTime = $(this).data('start-time');
					var endDate = $(this).data('end-date');
					var endTime = $(this).data('end-time');
					var title = $(this).data('title');
					var online = $(this).data('online');
					var address = $(this).data('address');
					console.log($(this));
					console.log(startDate + ' ' +  startTime);


					var button = createCalendar({
						options: {
							class: 'add-to-calendar-button'
						},
						data: {
							title: title,
							start: new Date(startDate + ' ' +  startTime),
							end: new Date(endDate + ' ' +  endTime),  
							timeZone: timeZone,   
							address: address
						}
					});

					$(this).append(button);

				});

			}

		}

	});

}


export { addToCalendar };


// var myCalendar = createCalendar({
// 	options: {
// 		class: 'my-class',	
// 				    // You can pass an ID. If you don't, one will be generated for you
// 				    id: 'my-id'
// 				},
// 				data: {
// 				    // Event title
// 				    title: 'Get on the front page of HN',

// 				    // Event start date
// 				    start: new Date('June 15, 2013 19:00'),

// 				    // Event duration (IN MINUTES)
// 				    duration: 120,

// 				    // You can also choose to set an end time
// 				    // If an end time is set, this will take precedence over duration
// 				    end: new Date('June 15, 2013 23:00'),     

// 				    // Event Address
// 				    address: 'The internet',

// 				    // Event Description
// 				    description: 'Get on the front page of HN, then prepare for world domination.'
// 				}
// 			});

// document.querySelector('#event-single-add-to-calendar-container').appendChild(myCalendar);
// });

// }


