<?php

/**
 * Reserved for any theme-specific hooks
 * For general AppThemes hooks, see appthemes-hooks.php
 *
 * @since 1.3
 * @uses add_action() calls to trigger the hooks.
 *
 */


/**
 * called in tpl-submit.php before step one page content is displayed
 *
 * @since 1.3
 */
function jr_before_step_one() { 
	do_action('jr_before_step_one');
} 

/**
 * called in tpl-submit.php after step one page content is displayed
 *
 * @since 1.3
 */
function jr_after_step_one() { 
	do_action('jr_after_step_one');
} 

/**
 * called in tpl-submit.php before step two page content is displayed
 *
 * @since 1.3
 */
function jr_before_step_two() { 
	do_action('jr_before_step_two');
} 

/**
 * called in tpl-submit.php after step two page content is displayed
 *
 * @since 1.3
 */
function jr_after_step_two() { 
	do_action('jr_after_step_two');
} 

/**
 * called in tpl-submit.php before step three page content is displayed
 *
 * @since 1.3
 */
function jr_before_step_three() { 
	do_action('jr_before_step_three');
} 

/**
 * called in tpl-submit.php after step three page content is displayed
 *
 * @since 1.3
 */
function jr_after_step_three() { 
	do_action('jr_after_step_three');
}

/**
 * called in tpl-submit.php before step four page content is displayed
 *
 * @since 1.3
 */
function jr_before_step_four() { 
	do_action('jr_before_step_four');
} 

/**
 * called in tpl-submit.php after step four page content is displayed
 *
 * @since 1.3
 */
function jr_after_step_four() { 
	do_action('jr_after_step_four');
}

/**
 * called in tpl-add-new-submit.php after returning from payment gateway
 * but before the order is actually processed in the log db table
 * make sure to return $_POST['custom'] in your function 
 *
 * @since 1.3.x
 * @param array $_POST['custom'] Gateway return posted elements
 *@todo already included in files but need to test first. Not sure if will work since theme-actions.php isn't directly included
 */
// function jr_before_gateway_process($_POST['custom']) { 
	// do_action('jr_before_gateway_process', $_POST['custom']);
// }

/**
 * called in tpl-add-new-submit.php after returning from payment gateway
 * and after the order is actually processed in the log db table
 *
 * @since 1.3.x
 * @param array $_POST['custom'] Gateway return posted elements
 *@todo already included in files but need to test first. Not sure if will work since theme-actions.php isn't directly included
 */
// function jr_after_gateway_process($newjobid) { 
	// do_action('jr_after_gateway_process', $newjobid);
// }
 
 
/**
 * called before the new job is created
 * make sure to return $data in your function
 *
 * @since 1.3
 * @param array $data Post array before running wp_insert_post 
 */
function jr_before_insert_job($data) { 
	do_action('jr_before_insert_job', $data);
}

/**
 * called after the new job is created
 *
 * @since 1.3
 * @param string $post_id Passes in newly created job id
 */
function jr_after_insert_job($post_id) { 
	do_action('jr_after_insert_job', $post_id);
}

/**
 * called above a single resume
 *
 * @since 1.4
 */
function jr_resume_header($post) { do_action('jr_resume_header', $post); }

/**
 * called below a single resume
 *
 * @since 1.4
 */
function jr_resume_footer($post) { do_action('jr_resume_footer', $post); }

/**
 * called in tpl-job-seeker-dashboard.php before dashboard jobs
 *
 * @since 1.4
 */
function jr_before_job_seeker_dashboard() { 
	do_action('jr_before_job_seeker_dashboard');
} 

/**
 * called in tpl-job-seeker-dashboard.php after dashboard jobs
 *
 * @since 1.4
 */
function jr_after_job_seeker_dashboard() { 
	do_action('jr_after_job_seeker_dashboard');
} 

/**
 * called in sidebar-nav.php after filters
 *
 * @since 1.4
 */
function jr_sidebar_nav_browseby() { 
	do_action('jr_sidebar_nav_browseby');
} 

/**
 * called in sidebar-resume-nav.php after filters
 *
 * @since 1.4
 */
function jr_sidebar_resume_nav_browseby() { 
	do_action('jr_sidebar_resume_nav_browseby');
} 

/**
 * called before a new order is inserted in the database
 * make sure to return $data in your function
 *
 * @since 1.5.3
 * @param object $data Order data
 */
function jr_before_insert_order($data) { 
	do_action('jr_before_insert_order', $data);
}

/**
 * called after a new order is inserted in the database
 * make sure to return $data in your function
 *
 * @since 1.5.3
 * @param object $data Order data
 */
function jr_after_insert_order($data) { 
	do_action('jr_after_insert_order', $data);
}

/**
 * redirects user to the payment gateway after the order is inserted in the database
 * make sure to return $description and $data in your function
 *
 * @since 1.5.4
 * @param object $data Order data
 * @param string $description Order description
 */
function jr_order_gateway_redirect($description, $data) { 
	do_action('jr_order_gateway_redirect', $description, $data);
}

/**
 * called in confirm-job-form.php and edit-job-form.php to add new gateway options
 *
 * @since 1.5.4
 */
function jr_order_gateway_options(){
	do_action('jr_order_gateway_options');
}
