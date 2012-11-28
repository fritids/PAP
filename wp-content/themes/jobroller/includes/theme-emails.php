<?php
/**
 *
 * Emails that get called and sent out for JobRoller
 * @package JobRoller
 * @author Mike Jolley
 * @version 1.3
 * For wp_mail to work, you need the following:
 * settings SMTP and smtp_port need to be set in your php.ini
 * also, either set the sendmail_from setting in php.ini, or pass it as an additional header.
 *
 */
 
if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");

function jr_new_order( $order ) {
    global $jr_log;
	
    $ordersurl = admin_url("admin.php?page=orders");
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('A new order has just been submitted on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    
    $message .= __('Order Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Order Cost: ', 'appthemes') . jr_get_currency($order->cost) . PHP_EOL;
    $message .= __('User ID: ', 'appthemes') . $order->user_id . PHP_EOL;
    $message .= __('Pack ID: ', 'appthemes') . $order->pack_id . PHP_EOL;
    $message .= __('Job ID: ', 'appthemes') . $order->job_id . PHP_EOL;

    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View orders: ', 'appthemes') . $ordersurl . PHP_EOL . PHP_EOL;
    
	if ($order->job_id) :
            $job_info = get_post($order->job_id);
		
        $job_title = stripslashes($job_info->post_title);
	    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
	    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	    $job_status = stripslashes($job_info->post_status);
	    $job_slug = stripslashes($job_info->guid);
	    $adminurl = admin_url("post.php?action=edit&post=".$order->job_id."");
	    
	    $message .= __('Job Details', 'appthemes') . PHP_EOL;
	    $message .= __('-----------------') . PHP_EOL;
	    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
	    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
	    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
	    $message .= __('Preview Job: ', 'appthemes') . $job_slug . PHP_EOL;
	    $message .= sprintf(__('Edit Job: %s', 'appthemes'), $adminurl) . PHP_EOL . PHP_EOL . PHP_EOL;
	endif;
    
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;
	
	$mailto = get_option('admin_email');
	$headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
	$subject = __('New Order', 'appthemes').' ['.$blogname.']';
	
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: New Order ('.$order->id.')'); 
}

function jr_order_complete( $order ) {
    global $jr_log;

    $ordersurl = admin_url("admin.php?page=orders&show=completed");
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Order number %s has just been completed on your %s website.', 'appthemes'), $order->id, $blogname) . PHP_EOL . PHP_EOL;
    
    $message .= __('Order Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Order Date: ', 'appthemes') . $order->order_date . PHP_EOL;
    $message .= __('Order Cost: ', 'appthemes') . jr_get_currency($order->cost) . PHP_EOL;
    $message .= __('User ID: ', 'appthemes') . $order->user_id . PHP_EOL;
    $message .= __('Pack ID: ', 'appthemes') . $order->pack_id . PHP_EOL;
    $message .= __('Job ID: ', 'appthemes') . $order->job_id . PHP_EOL;
    
    if ($order->payment_date)  $message .= __('Payment Date: ', 'appthemes') . $order->payment_date . PHP_EOL;
    if ($order->payment_type)  $message .= __('Payment Type: ', 'appthemes') . $order->payment_type . PHP_EOL;
    if ($order->approval_method)  $message .= __('Approval Method: ', 'appthemes') . $order->approval_method . PHP_EOL;
    if ($order->payer_first_name)  $message .= __('First name: ', 'appthemes') . $order->payer_first_name . PHP_EOL;
    if ($order->payer_last_name)  $message .= __('Last name: ', 'appthemes') . $order->payer_last_name . PHP_EOL;
    if ($order->payer_email)  $message .= __('Email: ', 'appthemes') . $order->payer_email . PHP_EOL;
    if ($order->payer_address)  $message .= __('Address: ', 'appthemes') . $order->payer_address . PHP_EOL;
    if ($order->transaction_id)  $message .= __('Txn ID: ', 'appthemes') . $order->transaction_id . PHP_EOL;

    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View completed orders: ', 'appthemes') . $ordersurl . PHP_EOL . PHP_EOL;
    
	if ($order->job_id) :
		$job_info = get_post($order->job_id);

		$job_title = stripslashes($job_info->post_title);
	    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
	    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	    $job_status = stripslashes($job_info->post_status);
	    $job_slug = stripslashes($job_info->guid);
	    $adminurl = admin_url("post.php?action=edit&post=".$order->job_id."");
	    
	    $message .= __('Job Details', 'appthemes') . PHP_EOL;
	    $message .= __('-----------------') . PHP_EOL;
	    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
	    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
	    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
	    $message .= __('Preview Job: ', 'appthemes') . $job_slug . PHP_EOL;
	    $message .= sprintf(__('Edit Job: %s', 'appthemes'), $adminurl) . PHP_EOL . PHP_EOL . PHP_EOL;
	endif;
    
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;
	
    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('Order Complete', 'appthemes').' ['.$blogname.']';
	
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: Order Complete ('.$order->id.')'); 
}

function jr_order_cancelled( $order ) {
    global $jr_log;

    $ordersurl = admin_url("admin.php?page=orders&show=cancelled");
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Order number %s has just been cancelled on your %s website.', 'appthemes'), $order->id, $blogname) . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View cancelled orders: ', 'appthemes') . $ordersurl . PHP_EOL . PHP_EOL;
    
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;
	
    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('Order Cancelled', 'appthemes').' ['.$blogname.']';
	
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: Order Cancelled ('.$order->id.')'); 
}
 
// Jobs that require moderation (non-paid)
function jr_admin_new_job_pending( $post_id ) {
    global $jr_log;

    $job_info = get_post($post_id);

    $job_title = stripslashes($job_info->post_title);
    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
    $job_status = stripslashes($job_info->post_status);
    $job_slug = stripslashes($job_info->guid);
    $adminurl = admin_url("post.php?action=edit&post=$post_id");
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('New Job Pending Approval', 'appthemes').' ['.$blogname.']';

    // Message

    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('The following job listing has just been submitted on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('Job Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('Preview Job: ', 'appthemes') . $job_slug . PHP_EOL;
    $message .= sprintf(__('Edit Job: %s', 'appthemes'), $adminurl) . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: New Job Pending Approval ('.$job_title.')'); 
}

// Edited Jobs that require moderation
function jr_edited_job_pending( $post_id ) {
    global $jr_log;

    $job_info = get_post($post_id);

    $job_title = stripslashes($job_info->post_title);
    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
    $job_status = stripslashes($job_info->post_status);
    $job_slug = stripslashes($job_info->guid);
    $adminurl = admin_url("post.php?action=edit&post=$post_id");
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('Edited Job Pending Approval', 'appthemes').' ['.$blogname.']';

    // Message

    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('The following job listing has just been edited on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('Job Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('Preview Job: ', 'appthemes') . $job_slug . PHP_EOL;
    $message .= sprintf(__('Edit Job: %s', 'appthemes'), $adminurl) . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: Edited Job Pending Approval ('.$job_title.')');
}


// Jobs that don't require moderation (non-paid)
function jr_admin_new_job( $post_id ) {	
    global $jr_log;

    $job_info = get_post($post_id);

    $job_title = stripslashes($job_info->post_title);
    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
    $job_status = stripslashes($job_info->post_status);
    $job_slug = stripslashes($job_info->guid);
    $adminurl = admin_url("post.php?action=edit&post=$post_id");
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('New Job Submitted', 'appthemes').' ['.$blogname.']';

    // Message

    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('The following job listing has just been submitted on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('Job Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View Job: ', 'appthemes') . $job_slug . PHP_EOL;
    $message .= sprintf(__('Edit Job: %s', 'appthemes'), $adminurl) . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: New Job Submitted ('.$job_title.')');
}


// New Job Posted (owner) - pending
function jr_owner_new_job_pending( $post_id ) {
    global $jr_log;

    $job_info = get_post($post_id);

    $job_title = stripslashes($job_info->post_title);
    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
    $job_status = stripslashes($job_info->post_status);
    $job_slug = stripslashes($job_info->guid);
    
    $siteurl = trailingslashit(get_option('home'));
    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = $job_author_email;
    $subject = sprintf(__('Your Job Submission on %s','appthemes'), $blogname);
    $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
    // Message
    $message  = sprintf(__('Hi %s,', 'appthemes'), $job_author) . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Thank you for your recent submission! Your job listing has been submitted for review and will not appear live on our site until it has been approved. Below you will find a summary of your job listing on the %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('Job Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('You may check the status of your job(s) at anytime by logging into the "My Jobs" page.', 'appthemes') . PHP_EOL;
    $message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
    $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to author ('.$job_author.'): Your Job Submission ('.$job_title.') on...');
}

// New Pack ordered
function jr_owner_new_order($order) {
	global $jr_log;

	$jr_pack = new jr_pack($order->pack_id);

	$job_info = get_post($order->job_id);
	$buyer_info = get_userdata($order->user_id);

	$buyer_login = stripslashes($buyer_info->user_login);
	$buyer_email = stripslashes($buyer_info->user_email);

	$order_message ='';
	if ($jr_pack->id>0):
		$pack_name = stripslashes($jr_pack->pack_name);
		$pack_description = stripslashes($jr_pack->pack_description);

		$order_message .= __('Pack Details', 'appthemes') . PHP_EOL;
		$order_message .= __('-----------------') . PHP_EOL;
		$order_message .= __('Name: ', 'appthemes') . $pack_name . PHP_EOL;
		$order_message .= __('Pack ID: ', 'appthemes') . $jr_pack->id . PHP_EOL;
		$order_message .= __('Description: ', 'appthemes') . $pack_description . PHP_EOL . PHP_EOL;
	endif;

	if ($job_info):
		$job_title = stripslashes($job_info->post_title);
	    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));

		$order_message .= __('Job Details', 'appthemes') . PHP_EOL;
		$order_message .= __('-----------------') . PHP_EOL;
		$order_message .= __('Job Title: ', 'appthemes') . $job_title . PHP_EOL;
		$order_message .= __('Job Author: ', 'appthemes') .  $job_author . PHP_EOL . PHP_EOL;
	endif;

	$siteurl = trailingslashit(get_option('home'));
	$dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$mailto = $buyer_email;
	$subject = sprintf(__('Your Order on %s','appthemes'), $blogname);
	$headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;

	// Message
	$message  = sprintf(__('Hi %s,', 'appthemes'), $buyer_login) . PHP_EOL . PHP_EOL;
	$message .= sprintf(__('Your order has been submitted with success and will be available as soon as the payment clears. Below you will find a summary of the order on the %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
	$message .= $order_message;
	$message .= __('---------------------------------------------') . PHP_EOL;
	$message .= __('Order #ID: ', 'appthemes') . $order->id . PHP_EOL;
	$message .= __('Order Cost: ', 'appthemes') . jr_get_currency($order->cost) . PHP_EOL;
	$message .= __('Order Date: ', 'appthemes') . date_i18n('Y-m-d h:i:s', time()) . PHP_EOL;
	$message .= __('---------------------------------------------') . PHP_EOL . PHP_EOL;
	$message .= __('You may check the status of your Pack(s) at anytime by logging into the "My Jobs" page.', 'appthemes') . PHP_EOL;
	$message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
	$message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
	$message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
	$message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

	// ok let's send the email
	wp_mail($mailto, $subject, $message, $headers);

	$jr_log->write_log('Email Sent to buyer ('.$buyer_login.'): Your Order #ID ('.$order->id.') on...');
}


// Job will expire soon
function jr_owner_job_expiring_soon( $post_id, $days_remaining ) {
    global $jr_log;

    $job_info = get_post($post_id);

    $days_text = '';

    if ($days_remaining==1) $days_text = '1'.__(' day', 'appthemes');
        else $days_text = $days_remaining.__(' days', 'appthemes');

    $job_title = stripslashes($job_info->post_title);
    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
    $job_status = stripslashes($job_info->post_status);
    $job_slug = stripslashes($job_info->guid);
    
    $siteurl = trailingslashit(get_option('home'));
    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = $job_author_email;
    $subject = sprintf(__('Your Job Submission on %s expires in %s','appthemes'), $blogname, $days_text);
    $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
    // Message
    $message  = sprintf(__('Hi %s,', 'appthemes'), $job_author) . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your job listing is set to expire in %s', 'appthemes'), $days_text) . PHP_EOL . PHP_EOL;
    $message .= __('Job Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Title: ', 'appthemes') . $job_title . PHP_EOL;
    $message .= __('Author: ', 'appthemes') . $job_author . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('You may check the status of your job(s) at anytime by logging into the "My Jobs" page.', 'appthemes') . PHP_EOL;
    $message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
    $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to author ('.$job_author.'): Your Job Submission ('.$job_title.') on...expires in '.$days_text);
}

// when a job's status changes, send the job owner an email
function jr_notify_job_owner_email($new_status, $old_status, $post) {   
    global $wpdb, $jr_log;

    $job_info = get_post($post->ID);
    
    if ($job_info->post_type=='job_listing') :

	    $job_title = stripslashes($job_info->post_title);
	    $job_author_id = $job_info->post_author;
	    $job_author = stripslashes(get_the_author_meta('user_login', $job_info->post_author));
	    $job_author_email = stripslashes(get_the_author_meta('user_email', $job_info->post_author));
	    $job_status = stripslashes($job_info->post_status);
	    $job_slug = stripslashes($job_info->guid);
	    
	    $mailto = $job_author_email;
	    
	    $siteurl = trailingslashit(get_option('home'));
	    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
		
	    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
	    // we want to reverse this for the plain text arena of emails.
	    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
	    // make sure the admin wants to send emails
	    $send_approved_email = get_option('jr_new_job_email_owner');
	    $send_expired_email = get_option('jr_expired_job_email_owner');
	
	    // if the job has been approved send email to ad owner only if owner is not equal to approver
	    // admin approving own jobs or job owner pausing and reactivating ad on his dashboard don't need to send email
	    if ($old_status == 'pending' && $new_status == 'publish' && get_current_user_id() != $job_author_id && $send_approved_email == 'yes') {

	        $subject = __('Your Job Has Been Approved','appthemes');
	        $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
	        $message  = sprintf(__('Hi %s,', 'appthemes'), $job_author) . PHP_EOL . PHP_EOL;
	        $message .= sprintf(__('Your job listing, "%s" has been approved and is now live on our site.', 'appthemes'), $job_title) . PHP_EOL . PHP_EOL;
	
	        $message .= __('You can view your job by clicking on the following link:', 'appthemes') . PHP_EOL;
	        $message .= get_permalink($post->ID) . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
	        $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
	        $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
	        $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
	
	        // ok let's send the email
	        wp_mail($mailto, $subject, $message, $headers);
	        
	        $jr_log->write_log('Email Sent to author ('.$job_author.'): Your Job Has Been Approved ('.$job_title.')');
	
	
	    // if the job has expired, send an email to the job owner only if owner is not equal to approver. This will only trigger if the 30 day option is hide
	    } elseif ($old_status == 'publish' && $new_status == 'private' && $send_expired_email == 'yes') {
	
	        $subject = __('Your Job Has Expired','appthemes');
	        $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
	        $message  = sprintf(__('Hi %s,', 'appthemes'), $job_author) . PHP_EOL . PHP_EOL;
	        $message .= sprintf(__('Your job listing, "%s" has expired.', 'appthemes'), $job_title) . PHP_EOL . PHP_EOL;
	
	        if (get_option('jr_allow_relist') == 'yes') {
	            $message .= __('If you would like to relist your job, please visit the "My Jobs" page and click the "relist" link.', 'appthemes') . PHP_EOL;
	            $message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
	        }
	
	        $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
	        $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
	        $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
	
	        // ok let's send the email
	        wp_mail($mailto, $subject, $message, $headers);
	        
	        $jr_log->write_log('Email Sent to author ('.$job_author.'): Your Job Has Expired ('.$job_title.')');
	
	    }
	endif;
}

add_action('transition_post_status', 'jr_notify_job_owner_email', 10, 3);

// email that gets sent out to new users once they register
function app_new_user_notification($user_id, $plaintext_pass = '') {
    global $app_abbr;

    $user = new WP_User($user_id);

    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);
    //$user_email = 'tester@127.0.0.1'; // USED FOR TESTING

    // variables that can be used by admin to dynamically fill in email content
    $find = array('/%username%/i', '/%password%/i', '/%blogname%/i', '/%siteurl%/i', '/%loginurl%/i', '/%useremail%/i');
    $replace = array($user_login, $plaintext_pass, get_option('blogname'), get_option('siteurl'), get_option('siteurl').'/wp-login.php', $user_email);

    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    // send the site admin an email everytime a new user registers
    if (get_option($app_abbr.'_nu_admin_email') == 'yes') {
        $message  = sprintf(__('New user registration on your site %s:'), $blogname) . PHP_EOL . PHP_EOL;
        $message .= sprintf(__('Username: %s'), $user_login) . PHP_EOL . PHP_EOL;
        $message .= sprintf(__('E-mail: %s'), $user_email) . PHP_EOL;

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);
    }

    if ( empty($plaintext_pass) )
        return;

    // check and see if the custom email option has been enabled
    // if so, send out the custom email instead of the default WP one
    if (get_option($app_abbr.'_nu_custom_email') == 'yes') {

        // email sent to new user starts here
        $from_name = strip_tags(get_option($app_abbr.'_nu_from_name'));
        $from_email = strip_tags(get_option($app_abbr.'_nu_from_email'));

        // search and replace any user added variable fields in the subject line
        $subject = stripslashes(get_option($app_abbr.'_nu_email_subject'));
        $subject = preg_replace($find, $replace, $subject);
        $subject = preg_replace("/%.*%/", "", $subject);

        // search and replace any user added variable fields in the body
        $message = stripslashes(get_option($app_abbr.'_nu_email_body'));
        $message = preg_replace($find, $replace, $message);
        $message = preg_replace("/%.*%/", "", $message);

        // assemble the header
        $headers = "From: $from_name <$from_email>" . PHP_EOL;
        $headers .= "Reply-To: $from_name <$from_email>" . PHP_EOL;
        //$headers .= "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-Type: ". get_option($app_abbr.'_nu_email_type') . PHP_EOL;

        // ok let's send the new user an email
        wp_mail($user_email, $subject, $message, $headers);

    // send the default email to debug
    } else {

        $message  = sprintf(__('Username: %s', 'appthemes'), $user_login) . PHP_EOL;
        $message .= sprintf(__('Password: %s', 'appthemes'), $plaintext_pass) . PHP_EOL;
        $message .= wp_login_url() . PHP_EOL;

        wp_mail($user_email, sprintf(__('[%s] Your username and password', 'appthemes'), $blogname), $message);

    }

}

// Email sent to users when starting subscriptions
add_action('user_resume_subscription_started', 'jr_user_resume_subscription_started_email');

function jr_user_resume_subscription_started_email( $user_id ) {
    global $jr_log;
    
    $user_name = stripslashes(get_the_author_meta('user_login', $user_id));
    $user_email = stripslashes(get_the_author_meta('user_email', $user_id));
    
    $siteurl = trailingslashit(get_option('home'));
    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = $user_email;
    $subject = sprintf(__('Your resume access subscription is now active on %s','appthemes'), $blogname);
    $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
    // Message
    $message  = sprintf(__('Hi %s,', 'appthemes'), $user_name) . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your resume access subscription has just been activated. You can now browse resumes on %s.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
    $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to user ('.$user_name.'): Your resume access subscription is now active');
}

// New user subscription
add_action('user_resume_subscription_started', 'jr_admin_resume_subscription_started_email');

function jr_admin_resume_subscription_started_email( $user_id ) {	
    global $jr_log;

    $user_name = stripslashes(get_the_author_meta('user_login', $user_id));
    $user_email = stripslashes(get_the_author_meta('user_email', $user_id));
    
    $user_admin_url = admin_url('user-edit.php?user_id='.$user_id);
    
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('New Resume Subscription', 'appthemes').' ['.$blogname.']';

    // Message
    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('The following user has just been granted resume access on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('User Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Name: ', 'appthemes') . $user_name . PHP_EOL;
    $message .= __('Email: ', 'appthemes') . $user_email . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View User: ', 'appthemes') . $user_admin_url . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: New Resume Subscription');
}

// Expired user subscription
add_action('user_resume_subscription_ended', 'jr_user_resume_subscription_ended_email');

function jr_user_resume_subscription_ended_email( $user_id ) {
    global $jr_log;
    
	// don't send email with automatic recurring payments
	if ( get_option('jr_resume_subscr_recurr_type') == 'auto' ) return;
	
    $user_name = stripslashes(get_the_author_meta('user_login', $user_id));
    $user_email = stripslashes(get_the_author_meta('user_email', $user_id));
    
    $siteurl = trailingslashit(get_option('home'));
    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = $user_email;
    $subject = sprintf(__('Your resume access subscription has expired on %s','appthemes'), $blogname);
    $headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
	
    // Message
    $message  = sprintf(__('Hi %s,', 'appthemes'), $user_name) . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your resume access subscription has just expired. To continue browsing resumes on %s you need to subscribe.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= $dashurl . PHP_EOL . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
    $message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to user ('.$user_name.'): Your resume access subscription has expired');
}

// Expired user subscription - admin email
add_action('user_resume_subscription_ended', 'jr_admin_resume_subscription_ended_email');

function jr_admin_resume_subscription_ended_email( $user_id ) {	
    global $jr_log;
	
	// don't send email with automatic recurring payments
	if ( get_option('jr_resume_subscr_recurr_type') == 'auto' ) return;	

    $user_name = stripslashes(get_the_author_meta('user_login', $user_id));
    $user_email = stripslashes(get_the_author_meta('user_email', $user_id));
    
    $user_admin_url = admin_url('user-edit.php?user_id='.$user_id);
    
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $mailto = get_option('admin_email');
    $headers = 'From: '. __('JobRoller Admin', 'appthemes') .' <'. get_option('admin_email') .'>' . PHP_EOL;
    $subject = __('Expired Resume Subscription', 'appthemes').' ['.$blogname.']';

    // Message
    $message  = __('Dear Admin,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= sprintf(__('The resume subscription for the following user has just expired on your %s website.', 'appthemes'), $blogname) . PHP_EOL . PHP_EOL;
    $message .= __('User Details', 'appthemes') . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL;
    $message .= __('Name: ', 'appthemes') . $user_name . PHP_EOL;
    $message .= __('Email: ', 'appthemes') . $user_email . PHP_EOL;
    $message .= __('-----------------') . PHP_EOL . PHP_EOL;
    $message .= __('View User: ', 'appthemes') . $user_admin_url . PHP_EOL . PHP_EOL;
    $message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
    $message .= __('JobRoller', 'appthemes') . PHP_EOL . PHP_EOL;

    // ok let's send the email
    wp_mail($mailto, $subject, $message, $headers);
    
    $jr_log->write_log('Email Sent to Admin: Expired Resume Subscription');
}

// Send email to resume authors from the contact form
add_action('jr_resume_header', 'jr_resume_contact_author_email');

function jr_resume_contact_author_email() {
    global $jr_log, $post, $message;
		
	if (isset($_POST['_wpnonce']) && !wp_verify_nonce($_POST['_wpnonce'], 'contact-resume-author_' . $post->post_author)) :
		
		$arr_params = array ( 
			'resume_contact'=> 0,
		);
		
		$log_message = 'Invalid security tocken while sending email to resume author ('.$resume_author.'): Reply to Resume - ' . $resume_title;	
	
	else:	
	
		if (isset($_POST['send_message'])) :
		
			$siteurl = trailingslashit(get_option('home'));
			$resume_title = $post->post_title;
			
			$resume_author = stripslashes(get_the_author_meta('user_login', $post->post_author));
			$resume_email = stripslashes(get_the_author_meta('user_email', $post->post_author));	
			
			$contact_name = stripslashes( $_POST['contact_name'] );
			$contact_email = stripslashes( $_POST['contact_email'] );		
			$contact_subject = stripslashes( $_POST['contact_subject'] );
			$contact_message = strip_tags( $_POST['contact_message'] );
			
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			$mailto = $resume_email;
			$subject = sprintf(__('Reply to your resume \'%s\' on %s','appthemes'), $resume_title, $blogname);
			$headers = 'From: '. sprintf(__('%s Admin', 'appthemes'), $blogname) .' <'. get_option('admin_email') .'>' . PHP_EOL;
			
			// Message
			$message  = sprintf(__('Hi %s,', 'appthemes'), $resume_author) . PHP_EOL . PHP_EOL;
			$message .= sprintf(__('%s has just sent you a message in reply to your resume \'%s\'. Please read details below:', 'appthemes'), $contact_name, $resume_title) . PHP_EOL . PHP_EOL;
			$message .= sprintf(__('From: %s <%s>.', 'appthemes'), $contact_name, $contact_email) . PHP_EOL;
			$message .= sprintf(__('Subject: %s.', 'appthemes'), $contact_subject) . PHP_EOL;
			$message .= sprintf(__('Message: %s.', 'appthemes'), $contact_message) . PHP_EOL . PHP_EOL. PHP_EOL;
			$message .= __('Regards,', 'appthemes') . PHP_EOL . PHP_EOL;
			$message .= sprintf(__('Your %s Team', 'appthemes'), $blogname) . PHP_EOL;
			$message .= $siteurl . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
			
			// ok let's send the email
			$result = wp_mail($mailto, $subject, $message, $headers);
		
			if ($result) :
				$notify_message = 1;
				$log_message = 'Email Sent to user ('.$resume_author.'): Reply to Resume - ' . $resume_title;
			else:
				$notify_message = 0;
				$log_message = 'Error sending email to user ('.$resume_author.'): Reply to Resume - ' . $resume_title;
			endif;

			$arr_params = array ( 
				'resume_contact' => $notify_message,
			);

		endif;	
		
	endif;
	
	if ( isset($arr_params) && is_array($arr_params) ):
			
		$redirect = add_query_arg( $arr_params );						
		
		$jr_log->write_log($log_message);

		wp_redirect($redirect);
		exit;	
			
	endif;
	
}

// Send the job alerts email
function jr_job_alerts_send_email( $user_id, $jobs ) {
    global $jr_log, $app_abbr;

    $subscriber = get_userdata($user_id);
    
    if (!$subscriber) { 
    	$jr_log->write_log("User ID #'{$user_id}' not found!");
    	return false;
    }
    
    $user_login = stripslashes($subscriber->user_login);
    $user_email = stripslashes($subscriber->user_email);
    
    $siteurl = trailingslashit(get_option('home'));
    $dashurl = trailingslashit(get_permalink(get_option('jr_dashboard_page_id')));
	
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text area of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $content_type = get_option($app_abbr.'_job_alerts_email_type');

   //email sent to new user starts here
    $from_name = strip_tags(get_option($app_abbr.'_job_alerts_from_name'));
    $from_email = strip_tags(get_option($app_abbr.'_job_alerts_from_email'));
    
    // assemble the header
    $headers = "From: $from_name <$from_email>" . PHP_EOL;
    $headers .= "Reply-To: $from_name <$from_email>" . PHP_EOL;
    $headers .= "Content-Type: ". $content_type . PHP_EOL;
	 	
	// check if user is using an alert html template
	$template = get_option($app_abbr.'_job_alerts_email_template');
	 if ($template != 'standard')
	 	$job_body_template = jr_job_alerts_read_template( $template );	 

	// fallback to default template if the template	fails to load
	if (empty($job_body_template))
    	$job_body_template = get_option($app_abbr.'_job_alerts_job_body');        

	// template clean	
	$job_body_template = stripslashes($job_body_template);        
    $job_body_template = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $job_body_template);

	// change double line-breaks in the text into HTML paragraphs for standard templates
	if ($template == 'standard') $job_body_template = wpautop($job_body_template);

    // check if user is using dynamic job details length
    $jobdetails_length = explode('_', $job_body_template);    
    if ( !empty($jobdetails_length[1]) ):

    	$jobdetails_length = (int) $jobdetails_length[1];
		$dynamic_find_replace = array(
				'jobdetails_find' => '/%jobdetails_'.$jobdetails_length.'%/i',
				'jobdetails_length' => $jobdetails_length,
		);
	else:
		$dynamic_find_replace['jobdetails_find'] = '/%jobdetails%/i';    	
    endif;

    $job_body = '';
    foreach ( $jobs as $job ):
	
    	$job_info = get_post($job);

    	// check if the job exists 
    	if ($job_info) {
    		
	    	// get the find/replace valus for the current job
	    	$job_find_replace = jr_job_alerts_joblist_find_replace( $job_info, $dynamic_find_replace ); 
	    	
		    // allow changing the job list body using a filter    	
		    $job_body_template = apply_filters('jr_job_alerts_format_joblist', $job_body_template, $job_info );   	    
		    		    	   
	    	$job_body .= preg_replace(array_keys($job_find_replace), $job_find_replace, $job_body_template);
	    	
    	}
    
	endforeach;

	// store the last job title to allow using it on the email subject - useful for single job emails
	$job_title = $job_info->post_title;
	
	// if the job body is null return true to consider email as sent and delete it from the list
	if (!$job_body) return true;
	
	if  ( $content_type == 'text/html' ) $job_body = sprintf('<html><body>%s</body></html>', $job_body);
	
	// variables that can be used by admin to dynamically fill in email content
    $find = array('/%username%/i', '/%joblist%/i', '/%jobtitle%/i', '/%blogname%/i', '/%siteurl%/i', '/%loginurl%/i', '/%useremail%/i', '/%dashboardurl%/i');
    $replace = array($user_login, $job_body, $job_title, get_option('blogname'), get_option('siteurl'), get_option('siteurl').'/wp-login.php', $user_email, $dashurl);
	        
    // search and replace any user added variable fields in the subject line
    $subject = stripslashes(get_option($app_abbr.'_job_alerts_email_subject'));
    $subject = preg_replace($find, $replace, $subject);
    $subject = preg_replace("/%.*%/", "", $subject);
	
    // search and replace any user added variable fields in the body
    $message = stripslashes(get_option($app_abbr.'_job_alerts_email_body'));
    $message = preg_replace($find, $replace, $message);    
    $message = preg_replace("/%.*%/", "", $message);  

    if  ( $content_type != 'text/html' ) 
		$message = wp_strip_all_tags($message);
    
    // ok let's send the new user an email
	$result = wp_mail($user_email, $subject, $message, $headers);
   
	if (!$result) $jr_log->write_log("Job alert error sending email to '$user_login' ('{$user_email}') / subject: '{$subject}' / message: '{$message}' / headers: '{$headers}'" );
    
   	return $result;    
}

// returns the job alerts job list find/replace array 
if (!function_exists('jr_job_alerts_joblist_find_replace')) :
function jr_job_alerts_joblist_find_replace( $job_info, $dynamic_fr ) {
	global $app_abbr;
	
	### truncate the job details if needed
	if ( !empty($dynamic_fr['jobdetails_length']) ) {

		$jobdetails_length =  $dynamic_fr['jobdetails_length'];
		$jobdetails_replace = substr($job_info->post_content, 0, $jobdetails_length);		
		$pos = strrpos($jobdetails_replace, " ");
		if ($pos > 0) $jobdetails_replace = substr($jobdetails_replace, 0, $pos) . '...';		
		    		
	} else {	

		$jobdetails_length = strlen($job_info->post_content);
		$jobdetails_replace = $job_info->post_content;
		   		
	};	

	### taxonomies
	$jobtype_replace = '';
	$jobtypes = get_the_terms($job_info->ID, APP_TAX_TYPE);
	if ($jobtypes) foreach ($jobtypes as $jobtype) {
		if ($jobtype_replace) $jobtype_replace .= ',';
		$jobtype_replace .=  $jobtype->name;
	}	
	
	$jobcat_replace = '';
	$jobcats = get_the_terms($job_info->ID, APP_TAX_CAT);		
	if ($jobcats) foreach ($jobcats as $jobcat) {
		if ($jobcat_replace) $jobcat_replace .= ',';
		$jobcat_replace .=  $jobcat->name;
	}	
			
	### location
	$address = get_post_meta($job_info->ID, 'geo_short_address', true);
	if ( !$address ) $address = __( 'Anywhere', 'appthemes' );

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($job_info->ID), 'thumbnail');
	if ( !empty($thumb[0])) $thumb = $thumb[0];
	else $thumb = ''; 
	
	$job_find_replace = array (
		'/%jobtitle%/i' 				=> $job_info->post_title,	
		'/%jobtime%/i' 					=> $job_info->post_date,
		$dynamic_fr['jobdetails_find']  => $jobdetails_replace,
		'/%company%/i'  				=> get_post_meta($job_info->ID, '_Company', true),    		
		'/%location%/i'  				=> $address,
		'/%jobtype%/i' 					=> $jobtype_replace,
		'/%jobcat%/i' 					=> $jobcat_replace,
		'/%author%/i' 					=> get_the_author_meta('user_login',$job_info->post_author),
		'/%permalink%/i'				=> get_permalink($job_info->ID),
		'/%thumbnail%/i'				=> wp_get_attachment_image($job_info->ID),
			'/%thumbnail_url%/i'			=> $thumb,
	    );  

    // allow changing the find/replace values using a filter  
    $job_find_replace = apply_filters('jr_job_alerts_joblist_find_replace', $job_find_replace, $job_info);    	    
    	    
    return $job_find_replace;
	    
}
endif;

?>