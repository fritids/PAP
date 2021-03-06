<?php
/**
 * Job Seeker Preferences form
 * Function outputs theJob Seeker Preferences form
 *
 *
 * @version 1.4
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_job_seeker_prefs_form() {
	
	global $post, $posted;

	$career_status 			= get_user_meta(get_current_user_id(), 'career_status', true);
	$willing_to_relocate 	= get_user_meta(get_current_user_id(), 'willing_to_relocate', true);
	$willing_to_travel 		= get_user_meta(get_current_user_id(), 'willing_to_travel', true);
	//$where_you_can_work 	= get_user_meta(get_current_user_id(), 'where_you_can_work', true);
	$keywords 				= get_user_meta(get_current_user_id(), 'keywords', true);
	$search_location 		= get_user_meta(get_current_user_id(), 'search_location', true);
	$job_types 				= get_user_meta(get_current_user_id(), 'job_types', true);
	
	//$your_location			= get_user_meta(get_current_user_id(), 'your_location', true);
	//$your_job_title			= get_user_meta(get_current_user_id(), 'your_job_title', true);
	
	$availability_month 	= get_user_meta(get_current_user_id(), 'availability_month', true);
	$availability_year 		= (int) get_user_meta(get_current_user_id(), 'availability_year', true);
	?>
	<form action="<?php echo get_permalink( $post->ID ); ?>" method="post" id="submit_form" class="submit_form main_form">

		<fieldset>
		
			<legend><?php _e('Publicly visible details', 'appthemes'); ?></legend>
			<p><?php _e('These options control what is shown publicly on your resumes.', 'appthemes'); ?></p>
			
			<?php /*<p><label for="your_location"><?php _e('Location', 'appthemes'); ?></label> 
			<input type="text" class="tags text" name="your_location" id="your_location" placeholder="<?php _e('e.g. London, United Kingdom', 'appthemes'); ?>" value="<?php echo $your_location; ?>" /></p>*/ ?>
			
			<p class="optional"><label for="availability_month"><?php _e('Your Availability <small>(Leave blank for immediate availability)</small>', 'appthemes'); ?></label> 
				<span class="date_field_wrap">
					<select name="availability_month" id="availability_month">
						<option value=""><?php _e('Month&hellip;', 'appthemes'); ?></option>
						<?php
							for($i=1; $i<=12; $i++) :
								$month = date('F', mktime(0, 0, 0, $i, 11, 1978));
								echo '<option value="'.$i.'"';
								if (isset($availability_month) && $availability_month==$i) echo ' selected="selected"';
								echo '>'.jr_translate_months($month).'</option>';
							endfor;
						?>
					</select>
					<input type="text" class="text" name="availability_year" maxlength="4" size="4" placeholder="<?php _e('YYYY','appthemes'); ?>" value="<?php if (isset($availability_year)) echo $availability_year; ?>" id="availability_year" />
				</span>
			</p>
			
		</fieldset>
		
		<fieldset>
			<legend><?php _e('Your Career', 'appthemes'); ?></legend>
			<p><label for="career_status"><?php _e('Career status', 'appthemes'); ?></label> <select name="career_status" id="career_status">
				<option <?php if ($career_status=='looking') echo 'selected="selected"'; ?> value="looking"><?php _e('Actively looking', 'appthemes'); ?></option>
				<option <?php if ($career_status=='open') echo 'selected="selected"'; ?> value="open"><?php _e('Open to new opportunities', 'appthemes'); ?></option>
				<option <?php if ($career_status=='notlooking') echo 'selected="selected"'; ?> value="notlooking"><?php _e('Not actively looking', 'appthemes'); ?></option>
			</select></p>
			<p><label for="willing_to_relocate"><?php _e('Are you willing to relocate?', 'appthemes'); ?></label> <select name="willing_to_relocate" id="willing_to_relocate">
				<option <?php if ($willing_to_relocate=='yes') echo 'selected="selected"'; ?> value="yes"><?php _e('Yes', 'appthemes'); ?></option>
				<option <?php if ($willing_to_relocate=='no') echo 'selected="selected"'; ?> value="no"><?php _e('No', 'appthemes'); ?></option>
			</select></p>
			<p><label for="willing_to_travel"><?php _e('Are you willing to travel?', 'appthemes'); ?></label> <select name="willing_to_travel" id="willing_to_travel">
				<option <?php if ($willing_to_travel=='100') echo 'selected="selected"'; ?> value="100"><?php _e('100% willing to travel', 'appthemes'); ?></option>
				<option <?php if ($willing_to_travel=='75') echo 'selected="selected"'; ?> value="75"><?php _e('Fairly willing to travel', 'appthemes'); ?></option>
				<option <?php if ($willing_to_travel=='50') echo 'selected="selected"'; ?> value="50"><?php _e('Not very willing to travel', 'appthemes'); ?></option>
				<option <?php if ($willing_to_travel=='25') echo 'selected="selected"'; ?> value="25"><?php _e('Interested in local opportunities only', 'appthemes'); ?></option>
				<option <?php if ($willing_to_travel=='0') echo 'selected="selected"'; ?> value="0"><?php _e('Not willing to travel/working from home', 'appthemes'); ?></option>
			</select></p>
			<!--<p><label for="where_you_can_work"><?php _e('Where are you authorised to work?', 'appthemes'); ?></label> 
			<input type="text" class="tags text" name="where_you_can_work" id="where_you_can_work" placeholder="<?php _e('e.g. United States, United Kingdom', 'appthemes'); ?>" value="<?php echo $where_you_can_work; ?>" /></p>-->
			
		</fieldset>
		
		<fieldset>
			<legend><?php _e('Other Information', 'appthemes'); ?></legend>
			<p><?php _e('These options control what job recommendations your receive on your dashboard.', 'appthemes'); ?></p>
			
			<p><label for="keywords"><?php _e('Key Words <small>(comma separated)</small>', 'appthemes'); ?></label> <input type="text" class="tags text" name="keywords" id="keywords" placeholder="<?php _e('e.g. Web Design, Designer', 'appthemes'); ?>" value="<?php echo $keywords; ?>" /></p>
			
			<p><label for="search_location"><?php _e('Search location', 'appthemes'); ?></label> 
			<input type="text" class="tags text" name="search_location" id="search_location" placeholder="<?php _e('e.g. London, United Kingdom', 'appthemes'); ?>" value="<?php echo $search_location; ?>" /></p>
			
			<div class="optional prefs_job_types"><label><?php _e('Types of Job', 'appthemes'); ?></label> 
				<ul>
				<?php
				$all_job_types = get_terms( 'job_type', array( 'hide_empty' => '0' ) );
				if ($all_job_types && sizeof($all_job_types) > 0) {
					foreach ($all_job_types as $type) {
						?>
						<li><label for="<?php echo $type->slug; ?>"><input type="checkbox" name="prefs_job_types[<?php echo $type->slug; ?>]" id="<?php echo $type->slug; ?>" <?php 
							if (is_array($job_types) && in_array($type->slug.'', $job_types)) echo 'checked="checked"'; 
							
						?> value="show" /> <?php echo $type->name; ?></label></li>
						<?php
					}
				}
				?>
				</ul>
			</div>

		</fieldset>

		<p><input type="submit" class="submit" name="save_prefs" value="<?php _e('Save &rarr;', 'appthemes'); ?>" /></p>
			
		<div class="clear"></div>
			
	</form>
	<?php
}