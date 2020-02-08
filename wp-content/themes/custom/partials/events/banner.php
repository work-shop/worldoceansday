
<?php if( get_field('show_list_your_event_banner', 13)): ?>
	<?php if( !isset($_COOKIE['wod_show_list_your_event_banner']) || $_COOKIE['wod_show_list_your_event_banner'] === false || is_singular('event_listing') ): ?>
	<section class="block padded-less" id="list-your-event-banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-4 offset-lg-2 list-your-event-banner-1">
					<h3 class="brand font-black">
						<?php the_field('list_your_event_banner_text', 13); ?>
					</h3>
				</div>
				<div class="col-lg-3 offset-lg-1 list-your-event-banner-2">
					<?php $link = get_field('list_your_event_banner_link', 13); ?>
					<?php if( $link ): ?>
						<div class="link-container">
							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" class="button button-brand">
								<?php echo $link['title']; ?>
							</a>
						</div>	
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if( is_singular('event_listing') === false): ?>
			<div class="banner-close" id="list-your-event-banner-close">
				<a href="#">ﬂ</a>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>
<?php endif; ?>