<?php

/**
 * Allow changing of values from user page
 */
function jr_profile_fields( $user ) { 

	if (get_user_meta($user->ID, '_valid_resume_subscription', true)) $can_view_resumes = 1; else $can_view_resumes = 0;
	
	?>
	<h3><?php _e('Job Packs', 'appthemes'); ?></h3>
	
	<table class="form-table">
 
		<tr>
			<th><label><?php _e('Current Job Packs', 'appthemes'); ?></label></th>
			<td>
				<?php
					$user_packs = jr_get_user_job_packs( $user->ID );
					if (sizeof($user_packs)>0) :
					
						echo '
						<table class="job_packs">
							<thead>
								<tr>
								<th>'.__('Name', 'appthemes').'</th>
								<th>'.__('Jobs Remaining', 'appthemes').'</th>
								<th>'.__('Job Duration', 'appthemes').'</th>
								<th>'.__('Expires', 'appthemes').'</th>
								<th>'.__('Delete pack?', 'appthemes').'</th>
								</tr>
							</thead>
							<tbody>';
						
						if (sizeof($user_packs)>0) foreach ($user_packs as $pack) :
						
							if (!$pack->jobs_limit) :
								$pack->jobs_count = __('Unlimited', 'appthemes');
							else :
								$pack->jobs_count = $pack->jobs_limit - $pack->jobs_count;
							endif;
							
							if ($pack->pack_expires>0) $pack->pack_expires = mysql2date(get_option('date_format'), $pack->pack_expires).'.'; else $pack->pack_expires = '';

							echo '<tr>
								<td>'.$pack->pack_name.'</td>
								<td>'.$pack->jobs_count.'</td>
								<td>'.$pack->job_duration.'</td>
								<td>'.$pack->pack_expires.'</td>
								<td><input type="checkbox" name="delete_pack[]" value="'.$pack->id.'" /></td>
							</tr>';
							
						endforeach;
						
						echo '</tbody></table>';
					
					else :
						?><p><?php _e('No active packs found.', 'appthemes'); ?></p><?php
					endif;
				?>
			</td>
		</tr>
		<tr>
			<th><label><?php _e('Assign job pack', 'appthemes'); ?></label></th>
			<td>
				<select name="give_job_pack"><option value=""><?php _e('Choose a pack...', 'appthemes'); ?></option>
				<?php
					$packs = jr_get_job_packs();
					if (sizeof($packs)>0) foreach ($packs as $pack) :
						
						echo '<option value="'.$pack->id.'">'.$pack->pack_name.'</option>';

					endforeach;
				?>
				</select>
			</td>
		</tr>
 
	</table>

	<h3><?php _e('Permissions', 'appthemes'); ?></h3>
 
	<table class="form-table">
 
		<tr>
			<th><label for="twitter"><?php _e('Valid resume subscription?', 'appthemes'); ?></label></th>
 
			<td>
				<select name="view_resumes">
					<option value=""><?php _e('No', 'appthemes'); ?></option>
					<option value="1" <?php selected($can_view_resumes, 1); ?>><?php _e('Yes', 'appthemes'); ?></option>
				</select>
				<span class="description"><?php _e('Define whether or not this user has a valid, active resume subscription.', 'appthemes'); ?></span>
			</td>
		</tr>
 
	</table>
	
<?php }

if (current_user_can('manage_options')) :
	add_action( 'show_user_profile', 'jr_profile_fields', 10 );
	add_action( 'edit_user_profile', 'jr_profile_fields', 10 );
	add_action( 'personal_options_update', 'jr_save_profile_fields' );
	add_action( 'edit_user_profile_update', 'jr_save_profile_fields' );
endif;
 
function jr_save_profile_fields( $user_id ) {
 
	if ( !current_user_can( 'edit_user', $user_id ) ) return false;

 	global $wpdb;
 	
	if (get_user_meta($user_id, '_valid_resume_subscription', true)) $can_view_resumes = 1; else $can_view_resumes = 0;		
	
	if ( (int)$_POST['view_resumes'] != $can_view_resumes) {
	
		if ($_POST['view_resumes']==1) :
			// start subscription
			do_action('user_resume_subscription_started', $user_id);
		else :
			// end subscription
			do_action('user_resume_subscription_ended', $user_id);
		endif;
		
	}
 	
 	if ($_POST['give_job_pack']>0) :
 		
 		// Give the user the chosen job pack
 		$pack = new jr_pack( (int) $_POST['give_job_pack'] );
		// Job count starts with 0
		$pack->give_to_user( $user_id, $jobs_count = 0 );
 		
 	endif;

 	if (isset($_POST['delete_pack']) && is_array($_POST['delete_pack']) && sizeof($_POST['delete_pack'])>0) :

		foreach ($_POST['delete_pack'] as $pack)
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->jr_customer_packs WHERE id = %s AND user_id = %d LIMIT 1;", $pack, $user_id ) );

 	endif;
}

 
/**
 * Init User Roles
 */
function jr_init_roles() {
	global $wp_roles;

	if (class_exists('WP_Roles')) 	
		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();	
	
	if (is_object($wp_roles)) :
		$wp_roles->add_cap( 'administrator', 'can_submit_job' );
		$wp_roles->add_cap( 'administrator', 'can_view_resumes' );
		//$wp_roles->remove_cap( 'administrator', 'can_view_resumes' );
		$wp_roles->add_cap( 'editor', 'can_submit_job' );
		$wp_roles->add_cap( 'contributor', 'can_submit_job' );
		$wp_roles->add_cap( 'author', 'can_submit_job' );
	endif;
	
	//$wp_roles->remove_role('job_seeker');
	//$wp_roles->remove_role('job_lister');
	
	$wp_roles->add_role( 'job_seeker', __('Job Seeker', 'appthemes'), array(
	    'read' => true,
	    'edit_posts' => false,
	    'delete_posts' => false,
	    'can_submit_resume' => true
	));
	
	$wp_roles->add_role( 'job_lister', __('Job Lister', 'appthemes'), array(
	    'read' => true,
	    'edit_posts' => false,
	    'delete_posts' => false,
	    'can_submit_job' => true
	));
	
	$wp_roles->add_role( 'recruiter', __('Recruiter', 'appthemes'), array(
	    'read' => true,
	    'edit_posts' => false,
	    'delete_posts' => false,
	    'can_submit_job' => true,
	    'can_view_resumes' => true
	));
	
}

add_action('init', 'jr_init_roles');

/**
 * Track User Job Views
 */
function jr_viewed_jobs() {
	global $post;
	if( is_single() && is_user_logged_in() && get_post_type() == 'job_listing' ) :
		
		$_viewed_jobs = get_user_meta(get_current_user_id(), '_viewed_jobs', true);
		if (!is_array($_viewed_jobs)) $_viewed_jobs = array();
		
		if (!in_array($post->ID, $_viewed_jobs)) $_viewed_jobs[] = $post->ID;
		
		$_viewed_jobs = array_reverse($_viewed_jobs);
		$_viewed_jobs = array_slice($_viewed_jobs, 0, 5);
		$_viewed_jobs = array_reverse($_viewed_jobs);
		
		update_user_meta(get_current_user_id(), '_viewed_jobs', $_viewed_jobs);
	endif;
}

add_action('appthemes_before_post', 'jr_viewed_jobs');

/**
 * Star Jobs
 */
function jr_star_jobs() {
	global $post;
	if( isset($_GET['star']) && is_single() && is_user_logged_in() && get_post_type() == 'job_listing' ) :
		
		$_starred_jobs = get_user_meta(get_current_user_id(), '_starred_jobs', true);
		if (!is_array($_starred_jobs)) $_starred_jobs = array();
		
		if ($_GET['star']=='true') :
			if (!in_array($post->ID, $_starred_jobs)) : $_starred_jobs[] = $post->ID; endif;
		else :
			$_starred_jobs = array_diff($_starred_jobs, array($post->ID));
		endif;

		update_user_meta(get_current_user_id(), '_starred_jobs', $_starred_jobs);
	endif;
}

add_action('appthemes_before_post', 'jr_star_jobs');


/**
 * Get job seeker prefs table
 */
function jr_seeker_prefs( $user_id ) {
	
	$prefs = '<table cellspacing="0" class="user_prefs">';
	
	$availability_month 	= get_user_meta($user_id, 'availability_month', true);
	$availability_year 	= get_user_meta($user_id, 'availability_year', true);
	//$your_location			= get_user_meta($user_id, 'your_location', true);
	$career_status 			= get_user_meta($user_id, 'career_status', true);
	$willing_to_relocate 	= get_user_meta($user_id, 'willing_to_relocate', true);
	$willing_to_travel 		= get_user_meta($user_id, 'willing_to_travel', true);
	$where_you_can_work 	= get_user_meta($user_id, 'where_you_can_work', true);
	
	if ($career_status) :
		$prefs .= '<tr><th>' . __('Career Status:', 'appthemes') . '</th><td>';
		switch ($career_status) :
			case "looking" :
				$prefs .= __('Actively looking', 'appthemes');
			break;
			case "open" :
				$prefs .= __('Open to new opportunities', 'appthemes');
			break;
			case "notlooking" :
				$prefs .= __('Not actively looking', 'appthemes');
			break;
		endswitch;
		echo '</td></tr>';
	endif;
	
	//if ($your_location) $prefs .= '<tr><th>' . __('Location:', 'appthemes') . '</th><td>' . wptexturize($your_location) . '</td></tr>';
	
	if ($availability_month && $availability_year) :
		$prefs .= '<tr><th>' . __('Availability:', 'appthemes') . '</th><td>' .  jr_translate_months( date('F', mktime(0, 0, 0, $availability_month, 11, $availability_year)) ). ' ' . date('Y', mktime(0, 0, 0, $availability_month, 11, $availability_year)). '</td></tr>';
	else :
		$prefs .= '<tr><th>' . __('Availability:', 'appthemes') . '</th><td>' .  __('Immediate', 'appthemes') . '</td></tr>';
	endif;
	
	if ($willing_to_relocate=='yes') $prefs .= '<tr><th>' . __('Willing to relocate:', 'appthemes') . '</th><td><img class="load" src="'.get_bloginfo('template_url').'/images/check.png" alt="yes" /></td></tr>';

	if ($willing_to_travel) :
		$prefs .= '<tr><th>' . __('Willingness to travel:', 'appthemes') . '</th><td>';
		switch ($willing_to_travel) :
			case "100" :
				$prefs .= __('Willing to travel', 'appthemes');
			break;
			case "75" :
				$prefs .= __('Fairly willing to travel', 'appthemes');
			break;
			case "50" :
				$prefs .= __('Not very willing to travel', 'appthemes');
			break;
			case "25" :
				$prefs .= __('Local opportunities only', 'appthemes');
			break;
			case "0" :
				$prefs .= __('Not willing to travel/working from home', 'appthemes');
			break;
		endswitch;
		$prefs .='</td></tr>';
	endif;
	
	$prefs .= '</table>';
	return $prefs;
}
