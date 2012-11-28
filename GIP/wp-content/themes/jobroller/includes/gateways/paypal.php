<?php
/* Process Order Payment - PayPal IPN
 *
 * @author AppThemes
 * @version 1.2
 *
 *
 */
function jr_update_listing_after_payment($posted) {

	global $jr_log;
	
	$jr_log->write_log('Valid IPN response detected: '. print_r( $posted, true ) ); 
    
    // Custom holds post ID
    if ( !empty($posted['txn_type']) && !empty($posted['custom']) && is_numeric($posted['custom']) && $posted['custom']>0 ) {

        $accepted_types = array('cart', 'express_checkout', 'web_accept');

        // Check transation is what we want
        if (!in_array(strtolower($posted['txn_type']), $accepted_types)) exit;
		
		$jr_order = new jr_order( $posted['custom'] );

        if ($jr_order->order_key!==$posted['item_number']) exit;

        if ($posted['test_ipn']==1 && $posted['payment_status']=='Pending') $posted['payment_status'] = 'completed';

        // We are here so lets check status and do actions
        switch (strtolower($posted['payment_status'])) :
            case 'completed' :
            	// Payment was made so we can approve the job
                $jr_order->complete_order('IPN');

                $payment_data = array();
		        $payment_data['payment_date'] 		= date("Y-m-d H:i:s");
		        $payment_data['payer_first_name'] 	= stripslashes(trim($_POST['first_name']));
		        $payment_data['payer_last_name'] 	= stripslashes(trim($_POST['last_name']));
		        $payment_data['payer_email'] 		= stripslashes(trim($_POST['payer_email']));
		        $payment_data['payment_type'] 		= 'PayPal';
		        $payment_data['approval_method'] 	= 'IPN'; 
		        $payment_data['payer_address']		= stripslashes(trim($_POST['residence_country']));
		        $payment_data['transaction_id']		= stripslashes(trim($_POST['txn_id']));
		        
		        $jr_order->add_payment( $payment_data );
		        
		        $jr_log->write_log( 'IPN Transaction Completed for Order #'.$posted['custom'] );
            break;
            case 'denied' :
            case 'expired' :
            case 'failed' :
            case 'voided' :
                // In these cases the payment failed so we can trash the job
                $jr_order->cancel_order();
                $jr_log->write_log( 'IPN Transaction Failed for Order #'.$posted['custom'] );
            break;
            default:
            	// Default if action not recognised
            	$jr_log->write_log( 'IPN Transaction default action. Nothing done. Order #'.$posted['custom'] );
            break;
        endswitch;

    }

}
add_action('valid-paypal-ipn-request', 'jr_update_listing_after_payment');

function jr_handle_resume_subscriptions_ipn($posted) {

	global $jr_log;
	
	$jr_log->write_log('Valid IPN response detected: '. print_r( $posted, true ) ); 
    
    // Custom holds post ID
    if ( !empty($posted['txn_type']) && !empty($posted['custom']) && is_numeric($posted['custom']) && $posted['custom']>0 ) {

		$user_id = (int) $posted['custom'];
		
		// Check for manual subscriptions and change the transaction type accordingly
		if (isset($posted['manual_subscr'])) $posted['txn_type'] = $posted['manual_subscr'];
		
		switch (strtolower($posted['txn_type'])) :

			case "subscr_trial" :
				// do started trial actions (intersected with 'subscr_signup')
				do_action('user_resume_trial_started', $user_id);
			case "subscr_signup" :
				// do started subscriptions actions
				do_action('user_resume_subscription_started', $user_id);
				exit;
			break;			
			case "subscr_payment" :
				exit;
			break;
			case "subscr_cancel" :
			case "subscr_failed" :
			case "subscr_eot" :
				// do ended subscriptions actions
				do_action('user_resume_subscription_ended', $user_id);
				exit;
			break;
			
		endswitch;

    }

}
add_action('valid-paypal-resume-subscription-ipn-request', 'jr_handle_resume_subscriptions_ipn');

// Handle paypal resumes subscriptions
function jr_handle_resume_subscr_paypal( $posted ) {

		$allow_trial = get_option('jr_resume_allow_trial');
		$paypal_email = get_option('jr_jobs_paypal_email');
		$currency = get_option('jr_jobs_paypal_currency');
		$item_name = sprintf(__('Access to %s\'s resume database', 'appthemes'), get_bloginfo('name'));	

		if(get_option('jr_enable_paypal_ipn') == 'yes') :
		
			if (isset($posted['subscription_type']) && $posted['subscription_type'] == 'trial'):
				$paypal_listener = 'RESUME_TRIAL';
			else:
				$paypal_listener = 'RESUME_SUBSCRIPTION';
			endif;
			
			$notify_url = trailingslashit(get_bloginfo('wpurl')).'?paypalListener='.$paypal_listener; // FOR IPN - notify_url
			$return = home_url(); // Thank you page - return
			
		else :
			$notify_url = '';
			$return = home_url(); // Add new confirm page - return
		endif;
		
		// Common paypal args
		$paypal_args = array(
			'business'		=> $paypal_email,
			'item_name'		=> $item_name,
			'no_shipping'	=> '1',
			'no_note'		=> '1',
			'currency_code'	=> $currency,
			'charset'		=> 'UTF-8',
			'return'		=> $return,
			'rm'			=> '2',
			'custom'		=> get_current_user_id(),
			'notify_url'	=> $notify_url
		);			
			
		// Auto recurring payments (only for PayPal Business/Premier Accounts)
		if ( $posted['recurring_type'] == 'auto') :
		
			// Auto recurring billing args
			$paypal_args = array_merge($paypal_args, array(
					'cmd' 			=> 	'_xclick-subscriptions',
					'src' 			=>	'1',
					'sra'			=>	'1',
					'a3'			=> 	$posted['access_cost'],
					't3'			=>	$posted['access_unit'],
					'p3'			=>	$posted['access_length']
			));	
		
			if ($allow_trial == 'yes') :
			
				// Additional trial args
				$paypal_args = array_merge($paypal_args, array(
					'a1' => $posted['trial_cost'],
					'p1' => $posted['trial_length'],
					't1' => $posted['trial_unit']
				));
				
			endif;

		// Manual recurring payments (PayPal Standard Accounts)
		else:				
		
			$unit_text = jr_format_date_unit($posted['unit'], $posted['length']);
			$item_name = $paypal_args['item_name'] . sprintf (__(' (%s %s %s)','appthemes'),$posted['length'], $unit_text, $posted['subscription_type']); 
			
			// Standard payments args
			$paypal_args = array_merge($paypal_args, array(
				'cmd' 			=> 	'_xclick',
				'amount'		=>	$posted['amount'],
				'item_name'	    =>  $item_name 
			));			
																		
		endif;		
		
		if ( isset($paypal_args['cmd']) ):
		
			// Allow changing paypal args
			$paypal_args = apply_filters('jr_resume_subscribe_button_args', $paypal_args);		
			
			if (get_option('jr_use_paypal_sandbox')=='yes') :
				$paypal_link = 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&';
			else:
				$paypal_link = 'https://www.paypal.com/webscr?';
			endif;		
			
			// Params needing url encoding
			$url_encode = array ('notify_url', 'return', 'item_name');							
			
			foreach ($paypal_args as $key => $value) :
				$paypal_link .= '&' . $key . '=' . (array_key_exists($key,$url_encode)?urlencode($value):$value);
			endforeach;
			
			header('Location: '.$paypal_link);
			exit;
			
		endif;	
		
}
add_action('jr_resume_subscr_redirect_paypal','jr_handle_resume_subscr_paypal', 10, 1);

// Add PayPal as a payment option to the gateways dropdown 
function jr_order_gateway_options_add_paypal() {
	global $posted;

	echo '<option value="paypal" '. (isset($posted['gateway']) && $posted['gateway'] == 'paypal'?'selected':'') . '>'. __('Paypal','appthemes') .'</option>';

}
add_action('jr_order_gateway_options','jr_order_gateway_options_add_paypal');

// Handle Orders using PayPal
function jr_handle_order_paypal ( $description, $jr_order ) {
	global $user_ID, $jr_log, $posted;

	if (!isset($_POST['gateway']) && $_POST['gateway'] != 'paypal' )
		return;
	
	$link = $jr_order->generate_paypal_link( $description );

	$jr_log->write_log('Sending user (#'.$user_ID.') to paypal after job submission ('.$description.' - order#'.$jr_order->id.').'); 
	
	header('Location: '.$link);	
	
	// no need to exit here
		
}
add_action('jr_order_gateway_redirect','jr_handle_order_paypal', 10, 2);

function jr_process_payment() {

    function jr_ipn_request_is_valid() {
    
    	global $jr_log;
	
		$jr_log->write_log( 'Checking validity of IPN Request. '. print_r( $_POST, true ) ); 

        // add the paypal cmd to the post array
        $_POST['cmd'] = '_notify-validate';

        // send the message back to PayPal just as we received it
        $params = array( 
        	'body' => $_POST,
			'timeout' 	=> 30
        );

        // get the correct paypal url to post request to
        if (get_option('jr_use_paypal_sandbox')=='yes')
            $paypal_adr = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $paypal_adr = 'https://www.paypal.com/cgi-bin/webscr';
            
        // post it all back to paypal to get a response code
        $response = wp_remote_post( $paypal_adr, $params );
        
        // Retry
		if ( is_wp_error($response) ) {
			$params['sslverify'] = false;
			$response = wp_remote_post( $paypal_adr, $params );
		}
        
        // send debug email to see paypal ipn response array
        if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'PayPal IPN Response Debug - ' . $paypal_adr, "".print_r($response, true));
		
        // cleanup
        unset($_POST['cmd']);

        // check to see if the request was valid
        if ( !is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && (strcmp( $response['body'], "VERIFIED") == 0)) {
            return true;
        } else {
            // response was invalid so don't proceed and send email to admin
            wp_mail(get_option('admin_email'), 'PayPal IPN - Response', print_r($response, true)."\n\n\n".print_r($_REQUEST, true));
            return false;
        }

    }
    
    if (isset($_GET['paypalListener']) && $_GET['paypalListener'] == 'IPN') {

        $_POST = stripslashes_deep($_POST);

        if (jr_ipn_request_is_valid()) {
            do_action("valid-paypal-ipn-request", $_POST);
            // send debug email to see paypal ipn post vars
            if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'Valid IPN Message', "".print_r($_POST, true));
        } else {
        	global $jr_log;
			$jr_log->write_log( 'IPN Request was invalid :(' ); 
        }
        exit;

    }
    
    if (isset($_GET['paypalListener']) && ($_GET['paypalListener'] == 'RESUME_SUBSCRIPTION' || $_GET['paypalListener'] == 'RESUME_TRIAL')) {
    
    	$_POST = stripslashes_deep($_POST);
		
		// Check for the transaction type and store it on a new $_POST var for manual recurring payments
		// Paypal standard payments transaction type are returned on the param 'txn_type' with the value 'web_accept'
		if ($_POST['txn_type'] == 'web_accept') :
		
			if ($_GET['paypalListener'] == 'RESUME_TRIAL'):
				$_POST['manual_subscr'] = 'subscr_trial';
			else:
				$_POST['manual_subscr'] = 'subscr_signup';			
			endif;
			
		endif;

        if (jr_ipn_request_is_valid()) {
            do_action("valid-paypal-resume-subscription-ipn-request", $_POST);
            // send debug email to see paypal ipn post vars
            if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'Valid RESUME_SUBSCRIPTION IPN Message', "".print_r($_POST, true));
        } else {
        	global $jr_log;
			$jr_log->write_log( 'RESUME_SUBSCRIPTION IPN Request was invalid :(' ); 
        }
        exit;

    }
    
}

add_action('init', 'jr_process_payment');