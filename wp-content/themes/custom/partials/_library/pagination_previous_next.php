<section class="block padded-less pagination-previous-next" id="pagination">
	<div class="container-fluid">
		<div class="row">
			<div class="col-6 pagination-next d-flex align-items-center justify-content-start">
				<?php $next_post = get_next_post(); //var_dump($next_post) ?>
				<?php if($next_post){ ?>
					<?php 
					global $post; 
					$ID = $next_post->ID;
					$post = get_post( $ID, OBJECT );
					setup_postdata( $post ); ?>
					<a href="<?php the_permalink(); ?>">
						<span class="icon pagination-icon" data-icon="‘"></span><?php the_title(); ?>
					</a>
					<?php wp_reset_postdata(); ?>
				<?php } ?>
			</div>
			<div class="col-6 pagination-previous d-flex align-items-center justify-content-end"> 
				<?php $previous_post = get_previous_post(); //var_dump($previous_post) ?>
				<?php if($previous_post){ ?>
					<?php 
					global $post; 
					$ID = $previous_post->ID;
					$post = get_post( $ID, OBJECT );
					setup_postdata( $post ); ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?><span class="icon pagination-icon" data-icon="—"></span>
					</a>
					<?php wp_reset_postdata(); ?>
				<?php } ?>	
			</div>
		</div>
	</div>
</section>