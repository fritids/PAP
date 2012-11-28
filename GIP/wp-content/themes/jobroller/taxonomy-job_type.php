<?php 
	get_header('search');
	
	$job_type_slug = get_query_var('job_type');
	$job_type = get_term_by( 'slug', $job_type_slug, 'job_type');	
	
	do_action('jobs_will_display'); 

	do_action('before_jobs_taxonomy', 'job_type', $job_type_slug);
?>
	<div class="section">

		<h1 class="pagetitle"><?php echo '<small class="rss"><a href="'.jr_get_current_url().'rss"><img src="'.get_bloginfo('template_url').'/images/feed.png" title="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" alt="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" /></a></small>'; ?> <?php echo wptexturize($job_type->name); ?> <?php _e('Jobs','appthemes'); ?> <?php
		?></h1>

		<?php	
			$args = array(
				'job_type' => $job_type_slug,
				'post_type'	=> 'job_listing',
				'post_status' => 'publish'
			);	
			query_posts($args);
		?>
		
		<?php get_template_part( 'loop', 'job' ); ?>

		<?php jr_paging(); ?>
		
		<div class="clear"></div>

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>