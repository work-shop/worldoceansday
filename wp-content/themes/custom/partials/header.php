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
			wp_title(false, true); echo ' - '; bloginfo( 'name' );
		}
		?>
	</title>

	<?php 
	if( get_field('social_media_title') ):
		$social_title = get_field('social_media_title'); 
	else:
		$social_title = wp_title(' - ', false);
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
		if( is_singular('event_listing')):
			$banner = get_event_banner();
			$social_image = event_manager_get_resized_image( $banner, 'fb' ); 
		else:
			$social_image = '';
		endif;
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


        <?php if( get_bloginfo('url') == 'https://worldoceansday.org'): ?>

          <?php
          $user = wp_get_current_user();
          $allowed_roles = array('editor', 'administrator', 'author'); ?>
          <?php if( array_intersect($allowed_roles, $user->roles ) == false ) :  ?>

            <?php if(true): ?>
              <!-- Global site tag (gtag.js) - Google Analytics -->
              <script async src="https://www.googletagmanager.com/gtag/js?id=UA-22692734-2"></script>
              <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'UA-22692734-2');
              </script>
            <?php endif; ?>

          <?php endif; ?>

          <?php else: ?>

            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43897729-4"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());

              gtag('config', 'UA-43897729-4');
            </script>

          <?php endif; ?>

	<?php
	//$sitewide_alert_on = get_field('show_sitewide_alert', 'option');
	$sitewide_alert_class = 'sitewide-alert-off';
	//if( $sitewide_alert_on === true ):
		//if( !isset($_COOKIE['ws_show_sitewide_alert']) || $_COOKIE['ws_show_sitewide_alert'] === false ):
			//$sitewide_alert_class = 'sitewide-alert-on';
			//$show_sitewide_alert = true;
		//endif;
	//endif;
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

	<?php //get_template_part('partials/sitewide_alert'); ?>
	<?php get_template_part('partials/nav'); ?>
	<?php get_template_part('partials/menus'); ?>

	<main id="content">
