<?php get_header('search'); ?>

    <div class="section">    

        <?php if (is_category()) { ?>

            <h1 class="pagetitle"><?php single_cat_title(); ?></h1>

        <?php /* If this is a tag archive */
        } elseif( is_tag() ) { ?>

            <h1 class="pagetitle"><?php _e('Posts Tagged','appthemes'); ?> &ldquo;<?php single_tag_title(); ?>&rdquo;</h1>

        <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
            <h1 class="pagetitle"><?php _e('Archive for','appthemes'); ?> <?php the_time('F jS, Y'); ?></h1>

        <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
            <h1 class="pagetitle"><?php _e('Archive for','appthemes'); ?> <?php the_time('F, Y'); ?></h1>

        <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
            <h1 class="pagetitle"><?php _e('Archive for','appthemes'); ?> <?php the_time('Y'); ?></h1>

        <?php /* If this is an author archive */ } elseif (is_author()) { ?>
            <h1 class="pagetitle"><?php _e('Author Archive','appthemes'); ?></h1>

        <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
            <h1 class="pagetitle"><?php _e('Blog Archives','appthemes'); ?></h1>

        <?php } ?>

        <?php get_template_part( 'loop' ); ?>

        <?php jr_paging(); ?>

        <div class="clear"></div>

    </div><!-- end section -->

    <div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>