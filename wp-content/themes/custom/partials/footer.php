        </main>

        <?php get_template_part('partials/footer_site' ); ?>

        <?php get_template_part('partials/search' ); ?>

        <?php get_template_part('partials/modals' ); ?>

        <?php //get_template_part('partials/viewport_label' ); ?>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-"></script>
        <script>
        	window.dataLayer = window.dataLayer || [];
        	function gtag(){dataLayer.push(arguments);}
        	gtag('js', new Date());
        	gtag('config', 'UA-');
        </script> -->

        <?php wp_footer(); ?>

        <script src="<?php bloginfo('template_directory'); ?>/js/instantpage.js" type="module" defer ></script>

    </body>
    </html>
