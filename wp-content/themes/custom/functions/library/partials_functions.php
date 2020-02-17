<?php 

add_action( 'rest_api_init', function () {
	register_rest_route( 'wod-partials/v1', '/supporters-slideshow', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_supporters_slideshow',
	) );
	register_rest_route( 'wod-partials/v1', '/subscribe-form', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_subscribe_form',
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
			speed: 700,
			responsive: [
			{
				breakpoint: 993,
				settings: {
					infinite: false,
					slidesToShow: 4,
					slidesToScroll: 4
				}
			}
			]
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



function get_subscribe_form( $request ){

	//global $wp;
	//$home_url = home_url( $wp->request );
	//return $home_url;
	$form_id = $request['formId'];
	$currentPage = $request['currentPage'];
	//$siteUrl = get_bloginfo('url');
	//$currentPage = str_replace($)
	$action_url = $currentPage;
	ob_start();
	gravity_form( $form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, 1, $echo = true ); 
	?>
	<script type='text/javascript' src='<?php bloginfo('url');?>/wp-content/plugins/gravityforms/js/jquery.json.min.js?ver=2.4.17'></script>
	<script type='text/javascript' src='<?php bloginfo('url');?>/wp-content/plugins/gravityforms/js/gravityforms.min.js?ver=2.4.17'></script>
	<script type='text/javascript' src='<?php bloginfo('url');?>/wp-content/plugins/gravityforms/js/placeholders.jquery.min.js?ver=2.4.17'></script>
	<?php
	$results = ob_get_clean();

	$needle_start = "action='";
	$needle_end = "#gf_" . $form_id . "'";
	$results = replace_between($results, $needle_start, $needle_end, $currentPage);
	//$results = str_replace('https', 'http', $results);
	return $results;

}

function replace_between($str, $needle_start, $needle_end, $replacement) {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);

    $pos = strpos($str, $needle_end, $start);
    $end = $pos === false ? strlen($str) : $pos;

    return substr_replace($str, $replacement, $start, $end - $start);
}



?>
