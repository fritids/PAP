<?php
/**
 * JobRoller Submit Job form
 * Function outputs the job submit form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_submit_job_form() {
	
	global $post, $posted;
	
	jr_geolocation_scripts();

	?>
	<form action="<?php echo get_permalink( $post->ID ); ?>" method="post" enctype="multipart/form-data" id="submit_form" class="submit_form main_form">

		<fieldset>
			<legend><?php _e('Company Details', 'appthemes'); ?></legend>
			<p><?php _e('Fill in the company section to provide details of the company listing the job. Leave this section blank to show your display name and profile page link instead.', 'appthemes'); ?></p>
			<p class="optional"><label for="your_name"><?php _e('Your Name/Company Name', 'appthemes'); ?></label> <input type="text" class="text" name="your_name" id="your_name" value="<?php if (isset($posted['your_name'])) echo esc_html($posted['your_name']); ?>" /></p>
			<p class="optional"><label for="website"><?php _e('Website', 'appthemes'); ?></label> <input type="text" class="text" name="website" value="<?php if (isset($posted['website'])) echo esc_html($posted['website']); ?>" placeholder="http://" id="website" /></p>
			<p class="optional"><label for="company-logo"><?php _e('Logo (.jpg, .gif or .png)', 'appthemes'); ?></label> <input type="file" class="text" name="company-logo" id="company-logo" /></p>
		</fieldset>	
		<fieldset>
			<legend><?php _e('Job Details', 'appthemes'); ?></legend>
			<p><?php _e('Enter details about the job below. Be as descriptive as possible so that potential candidates can find your job listing easily.', 'appthemes'); ?></p>
			<p><label for="job_title"><?php _e('Job title', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text" name="job_title" id="job_title" value="<?php if (isset($posted['job_title'])) echo esc_html($posted['job_title']); ?>" /></p>
			<p><label for="job_type"><?php _e('Job type', 'appthemes'); ?> <span title="required">*</span></label> <select name="job_term_type" id="job_type">
				<?php
				$job_types = get_terms( 'job_type', array( 'hide_empty' => '0' ) );
				if ($job_types && sizeof($job_types) > 0) {
					foreach ($job_types as $type) {
						?>
						<option <?php if (isset($posted['job_term_type']) && $posted['job_term_type']==$type->slug) echo 'selected="selected"'; ?> value="<?php echo $type->slug; ?>"><?php echo $type->name; ?></option>
						<?php
					}
				}
				?>
			</select></p>
			<p class="<?php if (get_option('jr_submit_cat_required')!=='yes') : echo 'optional'; endif; ?>"><label for="job_cat"><?php _e('Job Category', 'appthemes'); ?> <?php if (get_option('jr_submit_cat_required')=='yes') : ?><span title="required">*</span><?php endif; ?></label> <?php
				$sel = 0;
				if (isset($posted['job_term_cat']) && $posted['job_term_cat']>0) $sel = $posted['job_term_cat']; 
				global $featured_job_cat_id;
				$args = array(
				    'orderby'            => 'name', 
				    'exclude'			 => $featured_job_cat_id,
				    'order'              => 'ASC',
				    'name'               => 'job_term_cat',
				    'hierarchical'       => 1, 
				    'echo'				 => 0,
				    'class'              => 'job_cat',
				    'selected'			 => $sel,
				    'taxonomy'			 => 'job_cat',
				    'hide_empty'		 => false
				);
				$dropdown = wp_dropdown_categories( $args );
				$dropdown = str_replace('class=\'job_cat\' >','class=\'job_cat\' ><option value="">'.__('Select a category&hellip;', 'appthemes').'</option>',$dropdown);
				echo $dropdown;
			?></p>	
			<?php if (get_option('jr_enable_salary_field')!=='no') : ?><p class="optional"><label for="job_term_salary"><?php _e('Job Salary', 'appthemes'); ?></label> <?php
				$sel = 0;
				if (isset($posted['job_term_salary']) && $posted['job_term_salary']>0) $sel = $posted['job_term_salary']; 
				$args = array(
				    'orderby'            => 'ID', 
				    'order'              => 'ASC',
				    'name'               => 'job_term_salary',
				    'hierarchical'       => 1, 
				    'echo'				 => 0,
				    'class'              => 'job_salary',
				    'selected'			 => $sel,
				    'taxonomy'			 => 'job_salary',
				    'hide_empty'		 => false
				);
				$dropdown = wp_dropdown_categories( $args );
				$dropdown = str_replace('class=\'job_salary\' >','class=\'job_salary\' ><option value="">'.__('Select a salary&hellip;', 'appthemes').'</option>', $dropdown);
				echo $dropdown;
			?></p><?php endif; ?>
			<p class="optional"><label for="tags_input"><?php _e('Tags (comma separated)', 'appthemes'); ?></label> <input type="text" class="text" name="tags" value="<?php if (isset($posted['tags'])) echo $posted['tags']; ?>" id="tags_input" /></p>
		</fieldset>
		<fieldset>
			<legend><?php _e('Job Location', 'appthemes'); ?></legend>								
			<p><?php _e('Leave blank if the location of the applicant does not matter e.g. the job involves working from home.', 'appthemes'); ?></p>	
			<div id="geolocation_box">
			
				<p><label><input id="geolocation-load" type="button" class="button geolocationadd" value="<?php _e('Find Address/Location', 'appthemes'); ?>" /></label> <input type="text" class="text" name="jr_address" id="geolocation-address" value="<?php if (isset($posted['jr_address'])) echo $posted['jr_address']; ?>" autocomplete="off" />
				
				<input type="hidden" class="text" name="jr_geo_latitude" id="geolocation-latitude" value="<?php if (isset($posted['jr_geo_latitude'])) echo $posted['jr_geo_latitude']; ?>" />
				<input type="hidden" class="text" name="jr_geo_longitude" id="geolocation-longitude" value="<?php if (isset($posted['jr_geo_longitude'])) echo $posted['jr_geo_longitude']; ?>" />
				
				<input type="hidden" class="text" name="jr_geo_country" id="geolocation-country" value="<?php if (isset($posted['jr_geo_country'])) echo $posted['jr_geo_country']; ?>" />
				<input type="hidden" class="text" name="jr_geo_short_address" id="geolocation-short-address" value="<?php if (isset($posted['jr_geo_short_address'])) echo $posted['jr_geo_short_address']; ?>" />
				<input type="hidden" class="text" name="jr_geo_short_address_country" id="geolocation-short-address-country" value="<?php if (isset($posted['jr_geo_short_address_country'])) echo $posted['jr_geo_short_address_country']; ?>" />
				
				</p>
	
				<div id="map_wrap" style="border:solid 2px #ddd;"><div id="geolocation-map" style="width:100%;height:350px;"></div></div>
			
			</div>
			
		</fieldset>	
		<fieldset>
			<legend><?php _e('Job Description', 'appthemes'); ?></legend>	
			<p><?php _e('Give details about the position, such as responsibilities &amp; salary.', 'appthemes'); ?><?php if (get_option('jr_html_allowed')=='no') : ?><?php _e(' HTML is not allowed.', 'appthemes'); ?><?php endif; ?></p>				
			<p><textarea rows="5" cols="30" name="details" id="details" class="mceEditor"><?php if (isset($posted['details'])) echo $posted['details']; ?></textarea></p>
		</fieldset>
		<?php if (get_option('jr_submit_how_to_apply_display')=='yes') : ?><fieldset>
			<legend><?php _e('How to apply', 'appthemes'); ?></legend>
			<p><?php _e('Tell applicants how to apply &ndash; they will also be able to email you via the &ldquo;apply&rdquo; form on your job listing\'s page.', 'appthemes'); ?><?php if (get_option('jr_html_allowed')=='no') : ?><?php _e(' HTML is not allowed.', 'appthemes'); ?><?php endif; ?></p>
			<p><textarea rows="5" cols="30" name="apply" id="apply" class="how mceEditor"><?php if (isset($posted['apply'])) echo $posted['apply']; ?></textarea></p>			
		</fieldset><?php endif; ?>

		<p><input type="submit" class="submit" name="job_submit" value="<?php _e('Next &rarr;', 'appthemes'); ?>" /></p>
			
		<div class="clear"></div>
			
	</form>
	<script type="text/javascript">
		/* <![CDATA[ */
		jQuery.noConflict();
		(function($) { 
			<?php get_template_part('includes/countries'); ?>
			var availableCountries = [
				<?php
					global $countries;
					$countries_array = array();
					if ($countries) foreach ($countries as $code=>$country) {
						$countries_array[] = '"'.$country.'"';
					}
					echo implode(',', $countries_array);
				?>
			];
			var availableStates = [
				<?php
					global $states;
					echo implode(',', $states);
				?>
			];
			$("input#job_country").autocomplete({
				source: availableCountries,
				minLength: 2
			});
			$("input#job_city").autocomplete({
				source: availableStates,
				minLength: 1,
				search: function(){
					var c_val = $("input#job_country").val();
					if (c_val=='United States' || c_val.val()=='USA' || c_val=='US') return true; else return false;
				}
			});
			
			$("#submit_form").submit(function() {
			    $('input#job_city, input#job_country').removeAttr('autocomplete');
			});
			
		})(jQuery);
		/* ]]> */
	</script>
	<?php
	if (get_option('jr_html_allowed') == 'yes')
	    jr_tinymce();
	?>
	<?php

}