<?php
/**
 * JobRoller Job Lister Dashboard Packs Process
 * Processes Job Lister Dashboard Packs form.
 *
 *
 * @version 1.5
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

add_action('jr_lister_dashboard_process', 'jr_process_lister_packs_form');

function jr_process_lister_packs_form() {
	global $user_ID;

	if  ( !empty($_POST['buy_job_pack']) && !empty($_POST['job_pack']) ) :
	 	
		$job_pack_id = stripslashes(trim($_POST['job_pack']));

		$job_pack = new jr_pack( $job_pack_id );
		$cost = $job_pack->pack_cost;
	
		if ($cost > 0):
			$jr_order = new jr_order( 0, $user_ID, $cost, 0, $job_pack_id );

			$jr_order = apply_filters('jr_order', $jr_order);

			jr_before_insert_order( $jr_order );

			$jr_order->insert_order();

			jr_after_insert_order( $jr_order );

			$order_description .= __('Job Pack ', 'appthemes'). $job_pack->pack_name;

			// Apply filter to the Order description
			$order_description = apply_filters('jr_order_description', $order_description);

			### Redirect to payment page

			// Redirect user to a payment gateway
			jr_order_gateway_redirect( $order_description, $jr_order );

			exit;

		else:

			### FREE LISTING / LISTING PAID WITH USER PACK (no additional cost)
			if (!empty($job_pack)):
				// Add free pack to user's account
				$result = $job_pack->give_to_user( $user_ID, $jobs_count=0 );
			endif;

			$args = array( 'give_pack_success' => $result );
			redirect_myjobs($args);

		endif;

	endif;
}
?>