<?php 

add_action( 'rest_api_init', function () {
	register_rest_route( 'wod-resources/v1', '/resources', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'get_resources_list',
	) );
} );

function get_resources_list( $request ){

	$html = '';
	$results = array();

	$topic = $request['topic'];
	$type = $request['type'];
	$language = $request['language'];
	$per_page = $request['per_page'];
	$page = $request['page'];

	if($topic === 'all' || $topic === 'All' || $topic == false){
		$terms = get_terms( array(
			'taxonomy' => 'resources-topics',
			'hide_empty' => true,
		) ); 
		$topic = array();
		foreach ($terms as $term){
			$topic[] = $term->slug;
		}
	}

	if($type === 'all' || $type === 'All' || $type == false){
		$terms = get_terms( array(
			'taxonomy' => 'resources-type',
			'hide_empty' => true,
		) ); 
		$type = array();
		foreach ($terms as $term){
			$type[] = $term->slug;
		}
	}

	if($language === 'all' || $language === 'All' || $language == false){
		$terms = get_terms( array(
			'taxonomy' => 'resources-language',
			'hide_empty' => true,
		) ); 
		$language = array();
		foreach ($terms as $term){
			$language[] = $term->slug;
		}
	}

	$resources = array( 'data' => array() );
	$my_query = new WP_Query( array(
		'post_type' => 'resources',
		'posts_per_page' => $per_page,
		'paged' => $page,
		'order' => 'ASC',
		'tax_query' => array(
			'relation' => 'AND',
			array (
				'taxonomy' => 'resources-topics',
				'field' => 'slug',
				'terms' => $topic,
			),
			array (
				'taxonomy' => 'resources-type',
				'field' => 'slug',
				'terms' => $type,
			),
			array (
				'taxonomy' => 'resources-language',
				'field' => 'slug',
				'terms' => $language,
			)
		),
	) );

	$results['found_posts'] = $my_query->found_posts;
	$results['post_count'] = $my_query->post_count;

	if( $my_query->have_posts() ){
		while ( $my_query->have_posts() ) { $my_query->the_post();
			ob_start();
			get_template_part('partials/resources/resource_card');
			$resource_html = ob_get_clean();
			$html .= $resource_html;
		}
		$results['html'] = $html;
		return $results;
	} else{
		return false;
	}


	//$resources = json_encode($resources);
	

	// ob_start();
	// print_r($request['topic']);
	// $value = ob_get_clean();
	// //$value = json_encode($value);
	// return $value;

}


?>
