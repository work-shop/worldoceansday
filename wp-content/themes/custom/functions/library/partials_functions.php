<?php 

add_action( 'rest_api_init', function () {
	register_rest_route( 'wod-partials/v1', '/supporters-slideshow', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_supporters_slideshow',
	) );
} );


function get_supporters_slideshow( $request ){

	ob_start();
	?>
	<div class="slick-home-supporters">
		<?php $count = 1; ?>
		<?php while ( have_rows('sustaining_partners',105) ) : the_row(); ?>
			<div class="home-supporters-slide">
				<?php $link = get_field('link'); ?>
				<?php if( $link ): ?>
					<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="">
					<?php endif; ?>
					<?php $image = get_sub_field('supporter_image'); ?>
					<img src="<?php echo $image['sizes']['sm']; ?>" alt="<?php echo $image['alt']; ?>" class="">
					<?php if( $link ): ?>
					</a>
				<?php endif; ?>
			</div>
			<?php $count++; ?>
		<?php endwhile; ?>
	</div>
	<script>
		var slickHomeSuppporters;

		slickHomeSuppporters = $('.slick-home-supporters').slick({
			slidesToShow: 6,
			slidesToScroll: 6,
			dots: true,
			arrows: true,
			autoplay: false,
			fade: false,
			autoplaySpeed: 5000,
			speed: 700
		});		

		if($('body').hasClass('home')){
			window.addEventListener('scroll', checkViewport);
			window.addEventListener('resize', checkViewport);	
		}

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

</script>
<?php 
$results = ob_get_clean();

return $results;

}



?>
