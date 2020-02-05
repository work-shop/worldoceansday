<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>
		<?php 
		if( is_front_page() ){
			bloginfo( 'name' ); echo ' - ';  bloginfo( 'description' );
		} elseif( is_404() ){
			bloginfo( 'name' );
		} 
		else{
			wp_title(false); echo ' - '; bloginfo( 'name' );
		}
		?>
	</title>

	<?php 
	if( get_field('social_media_title') ):
		$social_title = get_field('social_media_title'); 
	else:
		$social_title = get_bloginfo( 'name' );
	endif;
	if( get_field('social_media_description') ):
		$social_description = get_field('social_media_description');
	else:
		$social_description = '';
	endif;
	if( get_field('social_media_url') ):
		$social_url = get_field('social_media_url'); 
	else: 
		$social_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	endif;
	if( get_field('social_media_image') ):
		$social_image_array = get_field('social_media_image');
		$social_image = $social_image_array['sizes']['fb'];
	else:
		$social_image = get_bloginfo( 'template_directory' ) . '/images/social_card_v1.jpg';
	endif;

	?>

	<!-- Facebook Open Graph data -->
	<meta property="og:title" content="<?php echo $social_title; ?>" />
	<meta property="og:description" content="<?php echo $social_description; ?>" />
	<meta property="og:image" content="<?php echo $social_image; ?>" />
	<meta property="og:url" content="<?php echo $social_url; ?>" />
	<meta property="og:type" content="website" />

	<!-- Twitter Card data -->
	<meta name="twitter:card" value="<?php echo $social_description; ?>">

	<link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('template_directory');?>/images/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('template_directory');?>/images/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory');?>/images/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo('template_directory');?>/images/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory');?>/images/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('template_directory');?>/images/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('template_directory');?>/images/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo('template_directory');?>/images/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory');?>/images/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php bloginfo('template_directory');?>/images/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory');?>/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo('template_directory');?>/images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory');?>/images/favicon-16x16.png">
	<link rel="manifest" href="<?php bloginfo('template_directory');?>/images/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php bloginfo('template_directory');?>/images/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="author" content="Work-Shop Design Studio http://workshop.co">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php wp_head(); ?>

	<?php
	$sitewide_alert_on = get_field('show_sitewide_alert', 'option');
	$sitewide_alert_class = 'sitewide-alert-off';
	if( $sitewide_alert_on === true ):
		if( !isset($_COOKIE['ws_show_sitewide_alert']) || $_COOKIE['ws_show_sitewide_alert'] === false ):
			$sitewide_alert_class = 'sitewide-alert-on';
			$show_sitewide_alert = true;
		endif;
	endif;
	?>

	<?php 
	if( is_user_logged_in() ): 
		$logged_in_classes = ' user_logged_in ';
	else:
		$logged_in_classes = ' user_logged_out ';
	endif; 
	?>

</head>
<body <?php body_class('loading before-scroll modal-off menu-closed dropdown-off mobile-dropdown-off curve-off ' . $sitewide_alert_class . ' ' . $logged_in_classes . ' '); ?>>

	<?php get_template_part('partials/sitewide_alert'); ?>
	<?php get_template_part('partials/nav'); ?>
	<?php get_template_part('partials/menus'); ?>

	<main id="content">
