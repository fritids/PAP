<?php
/**
 * JobRoller Cron Jobs
 * This file contains the cron jobs used on the theme.
 *
 *
 * @version 1.2
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_schedule_expire_check() {
	wp_schedule_event(time(), 'hourly', 'jr_check_jobs_expired');
	update_option('jr_check_jobs_expired', 'yes');
}

if (get_option('jr_check_jobs_expired')!='yes') :
	jr_schedule_expire_check();
endif;

add_action('jr_check_jobs_expired', 'jr_check_expired_cron');

function jr_check_expired_cron() {
	global $wpdb;
	$action = get_option('jr_expired_action');
	
	// Get list of expired posts that are published
	$postids = $wpdb->get_col($wpdb->prepare("
		SELECT      postmeta.post_id
		FROM        $wpdb->postmeta postmeta
		LEFT JOIN	$wpdb->posts posts ON postmeta.post_id = posts.ID
		WHERE       postmeta.meta_key = '_expires' 
		            AND postmeta.meta_value < '%s'
		            AND post_status = 'publish'
		            AND post_type = 'job_listing'
	", strtotime('NOW'))); 

	if ($action=='hide') :
		if ($postids) foreach ($postids as $id) { 		
			// Captains log supplemental, we have detected a job which is out of date
			// Activate Cloak
			$post = get_post($id);
			if ( empty($post) ) return;
			if ( 'private' == $post->post_status ) return;
			
			$old_status = $post->post_status;
			
			$job_post = array();
			$job_post['ID'] = $id;				
			$job_post['post_status'] = 'private';					
			wp_update_post( $job_post );
						
			// Update counts for the post's terms.
			foreach ( (array) get_object_taxonomies('job_listing') as $taxonomy ) {	
				$tt_ids = wp_get_object_terms($id, $taxonomy, array('fields' => 'tt_ids'));
				wp_update_term_count($tt_ids, $taxonomy);
			}
			
			do_action('edit_post', $id, $post);
			do_action('save_post', $id, $post);
			do_action('wp_insert_post', $id, $post);
		}
	endif;
	
	if (get_option('jr_expired_job_email_owner')=='yes') :
	
		$notify_ids = array();
		
		// Get list of expiring posts that are published
		$postids = $wpdb->get_col($wpdb->prepare("
			SELECT      DISTINCT postmeta.post_id
			FROM        $wpdb->postmeta postmeta
			LEFT JOIN	$wpdb->posts posts ON postmeta.post_id = posts.ID
			WHERE       postmeta.meta_key = '_expires' 
			            AND postmeta.meta_value > '%s'
			            AND postmeta.meta_value < '%s'
			            AND post_status = 'publish'
			            AND post_type = 'job_listing'
		", strtotime('NOW'), strtotime('+5 day'))); 
		
		if (sizeof($postids)>0) :
		
			// of those, get ids of posts that have already been notified
			$jobs_notified = $wpdb->get_col($wpdb->prepare("
				SELECT      postmeta.post_id
				FROM        $wpdb->postmeta postmeta
				WHERE       postmeta.meta_key = 'reminder_email_sent' 
				            AND postmeta.meta_value IN ('5','1')
			")); 
			// Now only send to those who need sending to
			$notify_ids = array_diff($postids, $jobs_notified);
			if ($notify_ids && sizeof($notify_ids)>0) foreach ($notify_ids as $id) {
				update_post_meta( $id, 'reminder_email_sent', '5' );
				jr_owner_job_expiring_soon( $id, 5 );
			}
		endif;
		
		// Get list of expiring posts (1 day left) that are published
		$postids = $wpdb->get_col($wpdb->prepare("
			SELECT      postmeta.post_id
			FROM        $wpdb->postmeta postmeta
			LEFT JOIN	$wpdb->posts posts ON postmeta.post_id = posts.ID
			WHERE       postmeta.meta_key = '_expires' 
			            AND postmeta.meta_value > '%s'
			            AND postmeta.meta_value < '%s'
			            AND post_status = 'publish'
			            AND post_type = 'job_listing'
		", strtotime('NOW'), strtotime('+1 day'))); 
		
		if (sizeof($postids)>0) :
		
			// of those, get ids of posts that have already been notified
			$jobs_notified = $wpdb->get_col($wpdb->prepare("
				SELECT      postmeta.post_id
				FROM        $wpdb->postmeta postmeta
				WHERE       postmeta.meta_key = 'reminder_email_sent' 
				            AND postmeta.meta_value IN ('1')
			", implode(',', $postids) )); 
			
			// Now only send to those who need sending to
			$notify_ids_2 = array_diff($postids, $jobs_notified, $notify_ids);
			
			if ($notify_ids_2 && sizeof($notify_ids_2)>0) foreach ($notify_ids_2 as $id) {
				update_post_meta( $id, 'reminder_email_sent', '1' );
				jr_owner_job_expiring_soon( $id, 1 );
			}
			
		endif;
	endif;
}

// init the job alerts cron afer all the admin options finish loading
function jr_cron_init_alerts() {
	global $app_abbr;
	
	if ( get_option($app_abbr.'_job_alerts') == 'yes' )
		jr_cron_job_alerts_schedule();
	else
		jr_cron_job_alerts_clear();
	
}

add_action('admin_init','jr_cron_init_alerts');


// schedule job alerts 
function jr_cron_job_alerts_schedule() {
	global $app_abbr;
	
	if  ( !wp_next_scheduled('jr_job_alerts') ):
	
		$recurrence = get_option($app_abbr.'_job_alerts_cron');
	
		wp_schedule_event( time(), $recurrence, 'jr_job_alerts');
		
	endif;	
}

// add custom job alerts schedules to cron
function jr_cron_add_custom_schedules() {
	  
	$schedules['ten_minutes'] = array ( 
			'display'  => __('Every Ten Minutes', 'appthemes'), 
			'interval' => 10*60, 
	);
		
	$schedules['twenty_minutes'] = array ( 
			'display' => __('Every Twenty Minutes', 'appthemes'), 
			'interval' => 20*60, 
	);	
	
	$schedules['thirty_minutes'] = array ( 
			'display'  => __('Every Thirty Minutes', 'appthemes'), 
			'interval' => 30*60, 
	);	
					
 	return $schedules;
 	
}

add_filter('cron_schedules','jr_cron_add_custom_schedules');


// clear the job alerts cron
function jr_cron_job_alerts_clear() {	
	wp_clear_scheduled_hook('jr_job_alerts');	
}


// update the job alerts cron when the schedule is changed
function jr_cron_update_job_alerts( $option ) {
	global $app_abbr;

	if ( get_option($app_abbr.'_job_alerts_cron') != $option )
		jr_cron_job_alerts_clear();
				
	return $option;
}

add_filter('pre_update_option_jr_job_alerts_cron','jr_cron_update_job_alerts');

?>