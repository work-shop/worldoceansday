
<?php get_template_part('partials/header'); ?>

<?php 
$post_id = $post->ID;
$value = get_post_meta($post_id,'_event_banner');
var_dump($value);

//echo $post->_event_start_date; ?>

<?php get_template_part('partials/events/event' ); ?>

<?php get_template_part('partials/events/banner' ); ?>

<?php get_template_part('partials/footer' ); ?>