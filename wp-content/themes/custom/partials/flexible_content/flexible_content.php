<?php if( have_rows('page_flexible_content') ){ ?>
	<div id="flexible-content">
		<?php $fc_index = 0; ?>
		<?php while ( have_rows('page_flexible_content') ) : the_row(); ?>
			<?php $GLOBALS['fc_index'] = $fc_index; ?>
			<?php //var_dump(get_field('page_flexible_content')); ?>
			<?php 
			$section_settings = get_sub_field('section_settings');
			$active = false;
			$active = $section_settings['active']; 
			if( $active ):
				$fc_type = get_row_layout();
				get_template_part('partials/flexible_content/' . $fc_type );
			endif;
			$fc_index++; 
			?>
		<?php endwhile; ?>
	</div>
<?php } ?>
