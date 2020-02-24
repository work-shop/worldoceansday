<section class="block" id="events">
	<div id="events-wrapper" class="">
		<div id="events-map" class="events-main">
		</div>
		<script src="<?php bloginfo('template_directory'); ?>/js/markerclusterer.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCUZ88sqTgo2gkvg-5q6xxawt9wZkTRCv8" async defer></script>
		<div id="events-list" class="events-main">
			<div id="events-scroll">
				<div id="filter-summary-events" class="filter-summary">
				</div>
				<div id="events-container">
				</div>
				<div class="row mt2">
					<div class="col">
						<div id="load-more" class="centered">
							<a href="#" class="button load-more load-more-button paginate paginate-next">Load More</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
	</div>
</section>
