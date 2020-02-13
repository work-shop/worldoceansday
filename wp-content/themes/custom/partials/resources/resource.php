<section class="block padded" id="resource-single-main">
	<div class="container-fluid">
		<div class="row mb3">
			<div class="col">
			<a class="button button-small" href="<?php bloginfo('url');?>/resources"><span class="icon mr1" data-icon="‰"></span> Back to Resources</a>
			</div>
		</div>
		<div class="row">
			<?php get_template_part('partials/resources/resource_card' ); ?>
		</div>
	</div>
	<div id="resource-modal" class="modal off">
		<div id="resource-modal-inner">
		</div>
	</div>
</section>
