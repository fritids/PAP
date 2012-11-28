<?php
/*
Template Name: Add New Listing Confirm
*/

/**
 * This script is the landing page after payment has been processed
 * by PayPal or other gateways. It is used to add order information to the orders table and approve the job.
 *
 * @package JobRoller
 * @author AppThemes
 * @version 1.2
 *
 */

global $wpdb, $jr_log;

$order_type='';

// get the order id and activate the job/pack
if(!empty($_POST['custom'])) :

	// jr_before_gateway_process($_POST['custom']); // do_action hook
    
	$jr_order = new jr_order( $_POST['custom'] );

    if ($jr_order->order_key==$_POST['item_number'] || $_POST['test_ipn'] == '1') :

		if ($jr_order->job_id) $order_type = 'job';
		elseif ($jr_order->pack_id)	$order_type= 'pack';

		$jr_order->complete_order(__('Return URL','appthemes'));
        
        $jr_log->write_log('Publishing job/pack submission (#'.$jr_order->job_id.') via tpl-add-new-confirm.php');
        
        $payment_data = array();
        $payment_data['payment_date'] 		= date("Y-m-d H:i:s");
        $payment_data['payer_first_name'] 	= stripslashes(trim($_POST['first_name']));
        $payment_data['payer_last_name'] 	= stripslashes(trim($_POST['last_name']));
        $payment_data['payer_email'] 		= stripslashes(trim($_POST['payer_email']));
        $payment_data['payment_type'] 		= 'PayPal';
        $payment_data['payer_address']		= stripslashes(trim($_POST['residence_country']));
        $payment_data['transaction_id']		= stripslashes(trim($_POST['txn_id']));
        $payment_data['approval_method'] 	= __('Return URL','appthemes'); 
        
        $jr_order->add_payment( $payment_data );
		
		// jr_after_gateway_process($newjobid); // do_action hook
    
    endif;

endif;

get_header(); ?>
	
	<div class="section">

		<div class="section_content">

			<?php
			// see if the job id is valid
			if ($order_type) { ?>

			  <h1><?php _e('Thank You!','appthemes'); ?></h1>

			  <div class="thankyou">

				<?php if ($order_type=='job'): ?>

					<p><?php _e('Your payment has been processed and your job listing should now be live.','appthemes'); ?></p>
					<p><?php _e('Visit your Dashboard to view or make any changes to your job listing.','appthemes') ?></p>

				<?php else: ?>

					<p><?php _e('Your payment has been processed and your pack should now be available to use.','appthemes'); ?></p>

				<?php endif; ?>

			 </div>

		<?php } else { ?>

			  <h1><?php _e('An Error Has Occurred','appthemes') ?></h1>

			  <div class="thankyou">

				  <p><?php _e('This job has already been published or you do not have permission to activate this job. Please contact the site admin if you are experiencing any issues.','appthemes') ?></p>

			 </div>

		<?php } ?>
		
		</div><!-- end section_content -->

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>

