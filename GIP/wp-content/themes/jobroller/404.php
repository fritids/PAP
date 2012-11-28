<?php get_header(); ?>
	
		<div class="section">

			<div class="section_content">

				<?php if (is_admin()) : ?>
				
					<h1><?php _e('Access Denied.', 'appthemes'); ?></h1>
					<p><?php _e('Your site administrator has blocked your access to the WordPress back-office.', 'appthemes') ?></p>
					
				<?php elseif ($wp_query->query_vars['post_type']=='resume') : ?>
					
					<h1><?php _e('No resumes exist', 'appthemes'); ?></h1>
					<p><?php _e('No resumes have been submitted yet. When a resume is submitted it will appear here.', 'appthemes') ?></p>
				
				<?php else : ?>
				
					<h1><?php _e('Sorry, Nothing was Found', 'appthemes'); ?></h1>
					<p><?php _e('The page, job, or post may have been moved or no longer exists.', 'appthemes'); ?></p>
					
				<?php endif; ?>

			</div>

		</div><!-- end section -->

		<div class="clear"></div>

	</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>
	
<?php get_footer(); ?>