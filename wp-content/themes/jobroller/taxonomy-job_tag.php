<?php 
	get_header('search');

	$job_tag_slug = get_query_var('job_tag');
	$job_tag = get_term_by( 'slug', $job_tag_slug, 'job_tag');
	
	do_action('jobs_will_display');
	
	do_action('before_jobs_taxonomy', 'job_tag', $job_tag_slug);
?>	

	<div class="section">

		<h1 class="pagetitle"><?php echo '<small class="rss"><a href="'.jr_get_current_url().'rss"><img src="'.get_bloginfo('template_url').'/images/feed.png" title="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" alt="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" /></a></small>'; ?> <?php _e('Jobs tagged','appthemes'); ?> &ldquo;<?php echo wptexturize($job_tag->name); ?>&rdquo; <?php
		?></h1>

		<?php
			$args = jr_process_filter_form();
			$args = array_merge(
				array(
					'job_tag' 		=> $job_tag_slug,
					'post_type'		=> 'job_listing',
					'post_status' 	=> 'publish'
				),
				$args
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