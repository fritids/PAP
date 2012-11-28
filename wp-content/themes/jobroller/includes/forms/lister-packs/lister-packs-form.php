<?php
/**
 * Job Lister Dashboard Packs form
 * Function outputs the Job Lister Dashboard Packs form
 *
 *
 * @version 1.5
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_lister_packs_form() {
		global $user_ID;

		$jr_orders = new jr_orders();

		if (sizeof($jr_orders)>0){
			$jr_orders->get_orders( $args = array('status'=>'pending_payment', 'type'=>'packs', 'user_id'=>$user_ID) );
			if (sizeof($jr_orders->orders)>0):
?>
				<h4><?php _e('Pending Payment Packs','appthemes'); ?></h4>
				<table cellpadding="0" cellspacing="0" class="data_list">
					<thead>
						<tr>
							<th><?php _e('Job Pack','appthemes'); ?></th>
							<th class="pack-order-date"><?php _e('Order Date','appthemes'); ?></th>
						</tr>
					</thead>
					<tbody>
<?php
					foreach ($jr_orders->orders as $jr_order):
						$jr_pack = new jr_pack($jr_order->pack_id);
?>
						<tr>
							<td><?php echo $jr_pack->pack_name; ?></td>
							<td class="date"><strong><?php echo date_i18n(__('j M','appthemes'), strtotime($jr_order->order_date)); ?></strong> <span class="year"><?php echo date_i18n(__('Y','appthemes'), strtotime($jr_order->order_date)); ?></span></td>
						</tr>
<?php
					endforeach;
?>
					</tbody>
				</table>
<?php
			endif;
		}
?>
		<form action="" method="post" id="submit_form" class="submit_form main_form" >
		<?php 		
			// display the packs selection (display only 'Paid' Job Packs)
			$paid_job_packs = jr_job_pack_select('dashboard-purchase', array('paid','free'));
		
			if ($paid_job_packs):	
		?>			
				<fieldset class="gateways_fieldset" style="display: none">				
					<legend><?php _e('Payment Gateway', 'appthemes'); ?></legend>	
					<select name="gateway" id="gateway" class="gateway">				
					
						<?php jr_order_gateway_options(); ?>
						
					</select>	
				</fieldset>							
				<p>
					<input type="submit" class="submit buy_job_pack" name="buy_job_pack" value="<?php _e('Continue to Payment &rarr;', 'appthemes'); ?>" />
				</p>
				<div class="clear"></div>
		<?php 
			endif; 
		?>			
		</form>		
<?php
}