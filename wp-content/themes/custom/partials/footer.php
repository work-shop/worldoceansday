        </main>

        <?php get_template_part('partials/footer_site' ); ?>

        <?php get_template_part('partials/search' ); ?>

        <?php get_template_part('partials/modals' ); ?>

        <?php //get_template_part('partials/viewport_label' ); ?>


          <?php wp_footer(); ?>

          <?php if( is_singular('event_listing') || is_page(11)): ?>
          <!-- Go to www.addthis.com/dashboard to customize your tools -->
          <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5e3dbd74bbbe2eb2"></script>
        <?php endif; ?>

        <script src="<?php bloginfo('template_directory'); ?>/js/instantpage.js" type="module" defer ></script>

      </body>
      </html>
