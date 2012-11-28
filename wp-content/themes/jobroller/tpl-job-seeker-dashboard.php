<?php
/*
Template Name: Job Seeker Dashboard
*/
?>

<?php
### Prevent Caching
nocache_headers();

appthemes_auth_redirect_login();

global $message, $userdata, $posted;

do_action('jr_process_job_seeker_form');

$activeTab = 0;
$manual_subscr = jr_resume_is_active_manual_subscr();
$can_subscribe = jr_current_user_can_subscribe_for_resumes();

?>

	<div class="section">
		
		<div class="section_content">
		
			<?php 
				if (isset($_GET['delete_resume']) && is_numeric($_GET['delete_resume'])) :
			
					if (isset($_GET['confirm'])) :

						$post_id = $_GET['delete_resume'];
						$post_to_remove = get_post($post_id);
	
						global $user_ID;
	
						if ($post_to_remove->ID==$post_id && $post_to_remove->post_author==$user_ID) :
							wp_delete_post($post_to_remove->ID);
	
							$message = __('Resume deleted.','appthemes');
						endif;
						
						$activeTab = 1;
	
					endif;
					
				endif;
				
				if (isset($_GET['toggle_visibility']) && is_numeric($_GET['toggle_visibility'])) :
					
					$post_id = $_GET['toggle_visibility'];
					$post_to_edit = get_post($post_id);
	
					global $user_ID;

					if ($post_to_edit->ID==$post_id && $post_to_edit->post_author==$user_ID) :
						$update_resume = array();
						$update_resume['ID'] = $post_to_edit->ID;
						if ($post_to_edit->post_status=='private') :
							$update_resume['post_status'] = 'publish';
						else :
							$update_resume['post_status'] = 'private';
						endif;
						wp_update_post( $update_resume );

						$message = __('Resume visibility modified.','appthemes');
					endif;
					
					$activeTab = 1;
					
				endif;
			?>

			<h1><?php printf( __('%s\'s Dashboard', 'appthemes'), ucwords( $userdata->user_login )); ?></h1>

			<?php do_action( 'appthemes_notices' ); ?>

			<ul class="display_section">
				<li><a href="#dashboard" class="noscroll"><?php _e('Dashboard', 'appthemes'); ?></a></li>
				<li><a href="#resumes" class="noscroll"><?php _e('Resumes', 'appthemes'); ?></a></li>
				<?php if ( $manual_subscr && $can_subscribe ) : ?><li><a href="#subscriptions" class="noscroll"><?php _e('Subscriptions', 'appthemes'); ?></a></li><?php endif; ?>
				<?php if ( jr_job_alerts_auth() ): ?>	<li><a href="#alerts" class="noscroll"><?php _e('Job Alerts', 'appthemes'); ?></a></li>	<?php endif; ?>
				<li><a href="#prefs" class="noscroll"><?php _e('Preferences', 'appthemes'); ?></a></li>
			</ul>
			
			<div id="dashboard" class="myprofile_section">
				<h2><?php _e('Dashboard', 'appthemes'); ?></h2>
				
				<?php jr_before_job_seeker_dashboard(); ?>
				
				<h3><?php _e('Starred Jobs', 'appthemes'); ?></h3>
				<?php
					$_starred_jobs = get_user_meta(get_current_user_id(), '_starred_jobs', true);
					if (is_array($_starred_jobs) && sizeof($_starred_jobs) > 0) :
						$args = array(
							'post_type'	=> 'job_listing',
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'post__in' => $_starred_jobs,
							'posts_per_page' => -1
						);
						query_posts($args);
						get_template_part( 'loop', 'job' );
						wp_reset_query();
					else :
						echo '<p>'.__('You have not starred any jobs yet. You can star jobs from the individual job listing pages.', 'appthemes').'</p>';
					endif;
				?>
				
				<h3><?php _e('Recently Viewed Jobs', 'appthemes'); ?></h3>
				<?php
					$_viewed_jobs = get_user_meta(get_current_user_id(), '_viewed_jobs', true);
					if (is_array($_viewed_jobs) && sizeof($_viewed_jobs) > 0) :
						$args = array(
							'post_type'	=> 'job_listing',
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'post__in' => $_viewed_jobs,
							'posts_per_page' => 5
						);
						query_posts($args);
						get_template_part( 'loop', 'job' );
						wp_reset_query();
					else :
						echo '<p>'.__('You have not viewed any jobs yet. When you do, the 5 most recent will display here.', 'appthemes').'</p>';
					endif;
				?>
				
				<h3><?php _e('Job Recommendations', 'appthemes'); ?></h3>
				<?php
					global $wpdb;
					/*
					Gather recommendations
					*/
					$willing_to_relocate 	= get_user_meta(get_current_user_id(), 'willing_to_relocate', true);
					$willing_to_travel 		= get_user_meta(get_current_user_id(), 'willing_to_travel', true);
					$keywords 				= get_user_meta(get_current_user_id(), 'keywords', true);
					$search_location 			= get_user_meta(get_current_user_id(), 'search_location', true);
					$job_types 				= get_user_meta(get_current_user_id(), 'job_types', true);
					
					$found_posts = array();

					if ($keywords) $keywords = explode(',', $keywords);
					
					if (is_array($keywords) && sizeof($keywords)>0) :
						foreach ($keywords as $keyword) :
							$keyword = trim($keyword);
							$result = $wpdb->get_col("SELECT ID from $wpdb->posts WHERE post_title LIKE '%$keyword%' OR post_content LIKE '%$keyword%';");
							if ($result) $found_posts = array_merge($result, $found_posts);
						endforeach;
					endif;
					
					if ($search_location) :
					
						$find_posts_in = array();
					
						$radius = 0;
						if ($willing_to_relocate=='yes') :
							$radius += 8000;
						else :
							if ($willing_to_travel==100) :
								$radius += 100;
							elseif ($willing_to_travel==75) :
								$radius += 75;
							elseif ($willing_to_travel==50) :
								$radius += 50;
							elseif ($willing_to_travel==25) :
								$radius += 10;
							endif;
						endif;
						
						if ($radius==0) $radius = 500;
						
						$radial_result = jr_radial_search($search_location, $radius);
						if (is_array($radial_result)) :
							$find_posts_in = array_merge($radial_result['posts'], $find_posts_in);
						endif;
						$found_posts = array_intersect($found_posts, $find_posts_in);
					endif;

					if (sizeof($job_types)==0) {
						$job_types = array('');
					}
			
					if (is_array($found_posts) && sizeof($found_posts) > 0) :
						$args = array(
							'post_type'	=> 'job_listing',
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'post__in' => $found_posts,
							'posts_per_page' => 5,
							'tax_query' => array(
								array(
									'taxonomy' 	=> 'job_type',
									'field' 	=> 'slug',
									'terms' 	=> $job_types
								)
							)
						);
						query_posts($args);
						get_template_part( 'loop', 'job' );
						wp_reset_query();
					else :
						echo '<p>'.__('You don\'t have any recommendations: try adjusting your preferences in order to get more results.', 'appthemes').'</p>';
					endif;
				?>
				
				<?php jr_after_job_seeker_dashboard(); ?>
				
			</div>
			<div id="resumes" class="myprofile_section">
				<h2><?php _e('My Resumes', 'appthemes'); ?></h2>
				<p><?php _e('Your resumes are displayed below. From this page you can create a new resume, edit a resume, and set whether or not it is visible on the site or private.', 'appthemes'); ?></p>
				
				<table cellpadding="0" cellspacing="0" class="data_list">
					<thead>
						<tr>
							<th><?php _e('Resume Name','appthemes'); ?></th>
							<th class="center"><?php _e('Date Created','appthemes'); ?></th>
							<th class="center"><?php _e('Last Modified','appthemes'); ?></th>
							<th class="center"><?php _e('Views','appthemes'); ?></th>
							<th class="center"><?php _e('Visibility','appthemes'); ?></th>
							<th class="right"><?php _e('Actions','appthemes'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							global $user_ID;
							$args = array(
									'ignore_sticky_posts'	=> 1,
									'posts_per_page' => -1,
									'author' => $user_ID,
									'post_type' => 'resume'
							);
							$my_query = new WP_Query($args);
							$count = 0;
						?>
						<?php if ($my_query->have_posts()) : ?>
						
							<?php while ($my_query->have_posts()) : ?>
							
								<?php $my_query->the_post(); ?>

								<?php if (get_post_meta($my_query->post->ID, 'jr_total_count', true)) $job_views = number_format(get_post_meta($my_query->post->ID, 'jr_total_count', true)); else $job_views = '-'; ?>
						
								<tr>
									<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></td>
									<td class="date"><strong><?php the_time('j M'); ?></strong> <span class="year"><?php the_time('Y'); ?></span></td>
									
									<td class="date"><strong><?php echo date('j M', strtotime($my_query->post->post_modified)); ?></strong> <span class="year"><?php echo date('Y', strtotime($my_query->post->post_modified)); ?></span></td>
									
									<td class="center"><?php echo $job_views; ?></td>
									<td class="center"><?php echo $my_query->post->post_status; ?></td>
									
									<td class="actions"><a href="<?php echo add_query_arg('edit', $my_query->post->ID, get_permalink(get_option('jr_job_seeker_resume_page_id'))); ?>"><?php _e('Edit&nbsp;&rarr;','appthemes'); ?></a>&nbsp;<a href="<?php echo add_query_arg('toggle_visibility', $my_query->post->ID); ?>"><?php if ($my_query->post->post_status=='private') _e('Publish','appthemes'); else _e('Hide','appthemes'); ?></a>&nbsp;<a href="<?php echo add_query_arg('delete_resume', $my_query->post->ID); ?>" class="delete"><?php _e('Delete','appthemes'); ?></a></td>
									
								</tr>
								<?php 
								$count++; 
							endwhile;
						else :
							?><tr><td colspan="6"><?php _e('No resumes found.','appthemes'); ?></td></tr><?php
						endif; 
						wp_reset_query();
						
						?>
					</tbody>
				</table>

				
				<form class="submit_form main_form" method="post" action="<?php echo get_permalink(get_option('jr_job_seeker_resume_page_id')); ?>">
					<p><input type="submit" class="submit" value="<?php _e('Add Resume &raquo;', 'appthemes')?>" /></p>
				</form>
				
				<div class="clear"></div>
				
			</div>
			
			<?php if ( $manual_subscr && $can_subscribe ) : ?>
			<div id="subscriptions" class="myprofile_section">
				<h2><?php _e('Resume Subscriptions ', 'appthemes'); ?></h2>
				<?php
					$curr_user_id = get_current_user_id();
					
					$valid_subscription = (get_user_meta( $curr_user_id, '_valid_resume_subscription', true ) == '1');
					$valid_trial =  (get_user_meta( $curr_user_id, '_valid_resume_trial', true ) == '1');
					$valid_subscr_date = get_user_meta( $curr_user_id, '_valid_resume_subscription_end', true );					
					$active_subscription = ($valid_subscription && $valid_subscr_date);
				?>
					
				<?php if ($active_subscription): ?>
					<p><?php echo sprintf (__('Your resume <em>%s</em> ends <strong>%s</strong>.','appthemes'), ($valid_trial?'Trial':'Subscription'), date_i18n(__('j M Y g:i:s a','appthemes'), $valid_subscr_date)); ?></p>
				<?php else: ?>	
					<p><?php echo sprintf(__('No active resume subscriptions. <a href=\'%s\'>Subscribe</a>. ','appthemes'),get_post_type_archive_link('resume')) ?></p>
				<?php endif;?>
				
				<div class="clear"></div>
				
			</div>
			<?php endif; ?>
			
			<div id="alerts" class="myprofile_section">
				<h2><?php _e('My Job Alerts', 'appthemes'); ?></h2>
				<?php jr_job_seeker_alerts_form(); ?>
			</div>
			
			<div id="prefs" class="myprofile_section">
				<h2><?php _e('My Preferences', 'appthemes'); ?></h2>
				<?php jr_job_seeker_prefs_form(); ?>
			</div>

		</div><!-- end section_content -->

	</div><!-- end section -->
	
	<script type='text/javascript' src='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/js/password-strength-meter.js?ver=20081210'></script>
	<script type="text/javascript">
	// <![CDATA[
		jQuery('ul.display_section li a').click(function(){

			jQuery('div.myprofile_section').hide();
			
			jQuery(jQuery(this).attr('href')).show();
			
			jQuery('ul.display_section li').removeClass('active');
			
			jQuery(this).parent().addClass('active');
			
			return false;
		});
		jQuery('ul.display_section li a:eq(<?php echo $activeTab; ?>)').click();
		
		jQuery('a.delete').click(function(){
			var answer = confirm("<?php _e('Are you sure you want to delete this resume? This action cannot be undone.',"appthemes"); ?>")
			if (answer){
				jQuery(this).attr('href', jQuery(this).attr('href') + '&confirm=true');
				return true;
			}
			else{
				return false;
			}					
		});
	// ]]>
	</script>

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('user'); ?>

<?php get_footer(); ?>