<?php
/**
 * JobRoller Resumes Subscription form
 * Function outputs the resumes subscription form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_subscribe_resumes_form() {		

		$user_id = get_current_user_id();
		
		// Get subscription options
		$recurring_type = get_option('jr_resume_subscr_recurr_type'); 		
		$allow_trial = get_option('jr_resume_allow_trial');
		$trial_cost = get_option('jr_resume_trial_cost');
		$trial_length = (int) get_option('jr_resume_trial_length');
		$trial_unit = get_option('jr_resume_trial_unit');
		$access_cost = get_option('jr_resume_access_cost');
		$access_length = (int) get_option('jr_resume_access_length');
		$access_unit = get_option('jr_resume_access_unit');
		
		if (!$access_cost) $access_cost = '0';
		if (!$trial_cost) $trial_cost = '0';
		if (!$trial_unit) $trial_unit = 'M';
		if (!$access_unit) $access_unit = 'M';
		
		// 0 = expired trial; 1 = active trial
		$expired_trial = (get_user_meta($user_id, '_valid_resume_trial', true) == '0');
		$show_options = ($recurring_type == 'manual' && $allow_trial == 'yes');		
	?>
			
		<form action="" method="post" class="main_form">
			<?php wp_nonce_field('subscribe-resumes_' . $user_id) ?>
			<input type="hidden" name="gateway" value="paypal">										
			<input type="hidden" name="recurring_type" value="<?php echo $recurring_type; ?>">										
			<input type="hidden" name="access_cost" value="<?php echo $access_cost; ?>">
			<input type="hidden" name="access_length" value="<?php echo $access_length; ?>">
			<input type="hidden" name="access_unit" value="<?php echo $access_unit; ?>">
			<input type="hidden" name="trial_cost" value="<?php echo $trial_cost; ?>">
			<input type="hidden" name="trial_length" value="<?php echo $trial_length; ?>">
			<input type="hidden" name="trial_unit" value="<?php echo $trial_unit; ?>">										
			<?php if ( $show_options ): ?>
			
				<fieldset>			
					<legend><?php _e('Subscription Options', 'appthemes'); ?></legend>					
					<select name="subscription_type" name="subscription_type">
						<option value="access"><?php echo sprintf (__('Subscribe for %s %s (%s)','appthemes'),$access_length, jr_format_date_unit($access_unit, $access_length), jr_get_currency($access_cost)); ?></option>			
						<option value="trial" <?php echo ($expired_trial?"disabled":""); ?> class="<?php echo ($expired_trial?"expired_trial":""); ?>"><?php echo sprintf (__('Trial for %s %s (%s)','appthemes'),$trial_length, jr_format_date_unit($trial_unit,$trial_length), ($trial_cost>0?jr_get_currency($trial_cost):__('Free','appthemes'))); ?></option>
					</select>					
				</fieldset>
				
			<?php else: ?>
			
				<input type="hidden" value="access" name="subscription_type">							
				
			<?php endif; ?>
			
			<p><input type="submit" class="submit" name="resume_subscr_submit" value="<?php _e('Subscribe &rarr;', 'appthemes'); ?>" /></p>
			
			<div class="clear"></div>			
			
		</form>
	
	<?php
}