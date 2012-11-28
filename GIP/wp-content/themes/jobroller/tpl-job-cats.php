<?php
/*
Template Name: Job Category List Template
*/
?>

<?php get_header('search'); ?>
	
	<div class="section">

		<div class="section_content">

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>

					<h1><?php the_title(); ?></h1>

					<?php the_content(); ?>
					
					<ul class="job_cats"><?php
						// List Job Cats
						$cat_args = array('orderby' => 'name', 'show_count' => -1, 'hierarchical' => true, 'taxonomy' => 'job_cat', 'title_li' => '');
						wp_list_categories( $cat_args );
					?></ul>

				<?php endwhile; ?>
				
				<?php else: ?>

				<p><?php _e('Sorry, no posts matched your criteria.', 'appthemes'); ?></p>

			<?php endif; ?>

			<div class="clear"></div>

		</div><!-- end section_content -->

	</div><!-- end section -->
	
	<?php //if (comments_open()) comments_template(); ?>

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('page'); ?>

<?php get_footer(); ?>
