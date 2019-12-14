
<?php 
$hero_image = get_field('hero_image');
$hero_image = $hero_image['sizes']['xl_landscape'];
$introduction_text = get_field('introduction_text');
?>
<section class="block page-hero present <?php if( $hero_image ): ?> page-hero-with-image <?php endif; ?>" id="page-hero">
	<?php if( $hero_image ): ?>
		<div class="block-background page-hero-image" style="background-image: url('<?php echo $hero_image; ?>');">
		</div>
	<?php endif; ?>
	<div class="page-title-container container-fluid">
		<?php if ( is_page() && $post->post_parent > 0 ) : ?>
			<h4 class="subpage-title">
				<?php echo get_the_title($post->post_parent); ?>
			</h4>
		<?php endif; ?>
		<h1 class="page-hero-title">
			<?php the_title(); ?>
		</h1>
	</div>
</section>
<?php if( $introduction_text ): ?>
	<section class="page-introduction">
		<div class="container-fluid">
			<h2 class="page-introduction-text">
				<?php echo $introduction_text; ?>
			</h2>
		</div>
	</section>
<?php endif; ?>