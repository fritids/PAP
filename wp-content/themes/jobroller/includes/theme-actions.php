<?php
/**
 * JobRoller Actions
 * Hooks into various actions in the theme.
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2011 all rights reserved
 *
 */

// add the post meta before the blog post content 
function jr_blog_post_meta() {
	if(is_page()) return; // don't do post-meta on pages
	global $post;	
	
	?>
	<p class="meta"><em><?php _e('Posted by', 'appthemes'); ?></em> <?php the_author_posts_link(); ?> | <?php echo jr_ad_posted($post->post_date); ?> | <?php the_category(', '); ?></p>
	<?php
}
add_action('appthemes_after_blog_post_title', 'jr_blog_post_meta');


// add the post tags and counter after the blog post content only on single blog page
function jr_blog_post_after() {
	if( !is_singular('post') ) return; // only show on blog post single page
	if(is_page()) return; // don't do post-meta on pages
	global $post;	
	
	if (get_option('jr_ad_stats_all') == 'yes') :
		?>
		<p class="stats"><?php appthemes_stats_counter($post->ID); ?></p>
		<?php
	endif;				
	
	the_tags('<p class="tags">' . __('Tags:', 'appthemes') . ' ', ', ', '</p>');
}
add_action('appthemes_after_blog_post_content', 'jr_blog_post_after');


// add the error message if no pages are found
function jr_page_loop_else() {
	?>	
	<p><?php _e('Sorry, no posts matched your criteria.', 'appthemes'); ?></p>
	<?php
}
add_action('appthemes_page_loop_else', 'jr_page_loop_else');


// add the error message if no blog posts are found
function jr_blog_loop_else() {
	?>	
	<p class="posts"><?php _e('No blog posts found.', 'appthemes'); ?></p>
	<?php
}
add_action('appthemes_blog_loop_else', 'jr_blog_loop_else');


// add the error message if no resume posts are found
function jr_resume_loop_else() {
	?>	
	<p class="resumes"><?php _e('No matching resumes found.', 'appthemes'); ?></p>
	<?php
}
add_action('appthemes_resume_loop_else', 'jr_resume_loop_else');

// add the error message if no pages are found
function jr_job_loop_else() {
	?>	
	<p class="jobs"><?php _e('No jobs found.', 'appthemes'); ?></p>
	<?php
}
add_action('appthemes_job_listing_loop_else', 'jr_job_loop_else');


/* Job Listing Pages */
function jr_expired_action() {
	$action = get_option('jr_expired_action');
	if ($action=='hide') :
		add_filter('posts_where', 'jr_job_not_expired');
	endif;
}
add_action('jobs_will_display', 'jr_expired_action');


/* Main Section */
add_action('job_main_section', 'jr_process_application_form', 1);
add_action('job_main_section', 'jr_expired_message', 1, 1);

/* Footer */
add_action('job_footer', 'jr_application_form', 1);
add_action('job_footer', 'jr_share_form', 2);
add_action('job_footer', 'jr_job_map', 3);
