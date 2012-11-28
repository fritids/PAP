<?php

/**
 * Check if resumes are enabled or not
 */
function jr_resumes_are_disabled() {
	if (get_option('jr_allow_job_seekers')=='no') return true;
	return false;
}

/**
 * Check if resumes are visible or not
 */
function jr_resume_is_visible( $single = '' ) {

	/* Support keys so logged out users can view a resume if they are sent the link via email (apply form) */
	if (is_single()) :
		
		if (isset($_GET['key']) && $_GET['key']) :
			
			global $post;
			
			$key = get_post_meta( $post->ID, '_view_key', true );
			
			if ($key==$_GET['key']) :
				return true;
			endif;
			
		endif;
		
	endif;

	/* Check user has access */
	if (get_option('jr_resume_require_subscription')=="yes") :
		// check for a valid subscription
		if ( jr_resume_valid_subscr(get_current_user_id()) ) return true;
		return false;
	endif;
	
	/* Normal visibility checking */
	if (!$single)
		$visibility = get_option('jr_resume_listing_visibility');
	else
		$visibility = get_option('jr_resume_visibility');
		
	switch ($visibility) :
		
		case "public" :
			return true;
		break;
		case "members" :
			if (!is_user_logged_in()) :
				return false;
			endif;
		break;
		case "recruiters" :
			if (!current_user_can('can_view_resumes')) :
				return false;
			endif;
		break;
		case "listers" :
		default :
			if (!current_user_can('can_submit_job')) :
				return false;
			endif;
		break;
		
	endswitch;
	
	return true;
}

/**
 * Check if resumes require subscription
 */
function jr_viewing_resumes_require_subscription() {
	
	if (get_option('jr_resume_require_subscription')=="yes") return true;
	
	return false;
}

/**
 * Check if current user can actually subscribe
 */
function jr_current_user_can_subscribe_for_resumes() {
	
	if (!is_user_logged_in()) return false;
	
	$visibility = get_option('jr_resume_listing_visibility');
	
	switch ($visibility) :
		
		case "public" :
			return true;
		break;
		case "members" :
			return true;
		break;
		case "recruiters" :
			if (current_user_can('can_view_resumes')) :
				return true;
			endif;
		break;
		case "listers" :
		default :
			if (current_user_can('can_submit_job')) :
				return true;
			endif;
		break;
		
	endswitch;
	
	return false;
	
}

/**
 * Check if resumes are disabled/visible and redirect
 */
function jr_resume_page_auth() {
	
	## Enabled/Disabled
	if (jr_resumes_are_disabled()) :
		wp_redirect(get_bloginfo('url'));
		exit;
	endif;
	
}

/**
 * Process requested resume subscriptions and redirect user to the payment gateway
 */
function jr_resume_subscr_process() {	
	global $message;
	
	$user_id = get_current_user_id();
	
	if (isset($_POST['resume_subscr_submit'])) :

		// TODO: post error message
		if (isset($_POST['_wpnonce']) && !wp_verify_nonce($_POST['_wpnonce'], 'subscribe-resumes_' . $user_id)) return;		
	
		// Add field to a nice array
		$fields = array(
			'gateway',
			'recurring_type',
			'subscription_type',
			'access_cost',
			'access_length',
			'access_unit',
			'trial_cost',
			'trial_length',
			'trial_unit',				
		);				
		
		// allow fields filtering
		$fields = apply_filters('jr_resume_subscribe_form_fields', $fields);
				
		// Get (and clean) post data
		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
		}		
		
		// Store posted data based on the subscription type (trial/access)
		$type = $posted['subscription_type']; 				
		$posted['amount'] = $posted[$type . '_cost'];
		$posted['length'] = $posted[$type . '_length'];
		$posted['unit']	= $posted[$type . '_unit'];		
		$unit_text = jr_format_date_unit($posted['unit'], $posted['length']);
		
		// Check for auto recurring payments or paying subscriptions to redirect to payment gateway
		if ( $posted['amount'] > 0 || $posted['recurring_type'] == 'auto' ):
			// redirect user to the selected payment gateway
			jr_resume_subscr_redirect_gateway($posted);
		else:
			// trial offered for manual payments option
			// don't redirect user - activate the free trial		
			do_action('user_resume_trial_started', $user_id);
			do_action('user_resume_subscription_started', $user_id);
			
			$trial_ends = get_user_meta('_valid_resume_subscription_end',$user_id);
			$trial_end_date = date_i18n(__('j M Y','appthemes'), strtotime($trial_ends));
			$message = sprintf (__('Your free %s %s Trial has started. Ends, %s.','appthemes'),$posted['length'], $unit_text, $trial_end_date);
		endif;			

	endif;	
}

/**
 * Redirects resumes subscribers to the selected payment gateway
 */
function jr_resume_subscr_redirect_gateway( $posted ) {

		if (!isset($posted['gateway'])) return;
	
		$user_id = get_current_user_id();
	
		// Add the subscription order date
		update_user_meta( $user_id, '_valid_resume_subscription_order', strtotime("now") );		
	
		$gateway = $posted['gateway'];		
		switch ($gateway) :
		
			case "paypal" :
				// redirect to paypal
				do_action('jr_resume_subscr_redirect_paypal', $posted);				
				break;
				
			default:
				// redirect to other gateways	
				do_action('jr_resume_subscr_redirect_other', $posted);
				break;
				
		endswitch;

}

/**
 * Checks for valid subscriptions (auto/manual) and ends expired manual subscriptions
 */
function jr_resume_valid_subscr( $user_id ) {
	
	$active_subscr = get_user_meta( $user_id, '_valid_resume_subscription', true );
	
	// check for job packs with resume acccess
	$pack = jr_get_user_job_packs_access( $user_id );
	
	if ( !empty($pack['access']) &&
		( ( in_array('resume_browse', $pack['access']) && !is_single() ) || ( in_array('resume_view', $pack['access']) && is_single() )  ) )
		return true;
	
	// Return earlier for auto recurring payments as it uses IPN for ending subcriptions
	if ( get_option('jr_resume_subscr_recurr_type') == 'auto' && $active_subscr ) 
		return true;

	// Grab the stored subscription end date	
	$end_date = get_user_meta( $user_id, '_valid_resume_subscription_end', true );

	if ($end_date && $active_subscr) :
	
		$days = ceil(($end_date-strtotime('NOW'))/86400);
		//subscription ended
		if ( $days < 1 ):			
		
			// end subscription
			do_action('user_resume_subscription_ended', $user_id);
			
		else:
			return true;
		endif;
		
	endif;
	
	return false;

}

/**
 * Update resume subscriptions user meta for ending subscriptions
 */
function jr_user_resume_subscription_end_meta( $user_id ) {

	update_user_meta($user_id, '_valid_resume_subscription', '0');
	update_user_meta($user_id, '_valid_resume_trial', '0');
	delete_user_meta($user_id, '_valid_resume_subscription_order');	
	
}
add_action('user_resume_subscription_ended', 'jr_user_resume_subscription_end_meta', 1);

/**
 * Update resume subscriptions user meta for new subscriptions
 */
function jr_user_resume_subscription_start_meta($user_id) {	

	update_user_meta( $user_id, '_valid_resume_subscription', '1' );

	if ( get_option('jr_resume_subscr_recurr_type') == 'auto' ):	
		// delete subscription date user meta if exists
		// in cases where the recurring payment type is changed on the admin panel
		delete_user_meta($user_id, '_valid_resume_subscription_end');
		return;	
	endif;
	
	$start_date = strtotime("now");
	update_user_meta( $user_id, '_valid_resume_subscription_start', $start_date );
	
	$end_date = jr_resume_calc_end_date();
	update_user_meta( $user_id, '_valid_resume_subscription_end', $end_date );
	
}
add_action('user_resume_subscription_started', 'jr_user_resume_subscription_start_meta');

/**
 * Update resume subscriptions trial user meta
 */
function jr_user_resume_trial_update_meta($user_id) {	

	update_user_meta( $user_id, '_valid_resume_trial', '1' );		
	
}
add_action('user_resume_trial_started', 'jr_user_resume_trial_update_meta');

/**
 * Check if manual recurring payments are active 
 */
function jr_resume_is_active_manual_subscr() {
		
	$is_active = get_option('jr_resume_require_subscription')=="yes" && get_option('jr_resume_subscr_recurr_type') == 'manual';
	
	return $is_active;
	
}

/**
 * Calculate and return new resume subcription dates
 */
function jr_resume_calc_end_date() {

	// for manual recurring payments set the subscription end date
	$length = (int) get_option('jr_resume_access_length');
	$unit = get_option('jr_resume_access_unit');
	$unit_text = jr_format_date_unit($unit, $length);

	$date = strtotime('+'.$length.' '.$unit_text, current_time('timestamp'));
	
	return $date;
	
}
