<?php
/**
 * JobRoller Edit Job form
 * Function outputs the job edit form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_edit_job_form( $relisting = false ) {
	
	global $post, $posted, $job_details;
	
	jr_geolocation_scripts();

	?>
	<form action="<?php echo get_permalink( $post->ID ); ?>?edit=<?php echo $job_details->ID; ?>" method="post" enctype="multipart/form-data" id="submit_form" class="submit_form main_form">
		<fieldset>
			<legend><?php _e('Company Details', 'appthemes'); ?></legend>
			<p><?php _e('Fill in the company section to provide details of the company listing the job. Leave this section blank to show your display name and profile page link instead.', 'appthemes'); ?></p>
			<p class="optional"><label for="your_name"><?php _e('Your Name/Company Name', 'appthemes'); ?></label> <input type="text" class="text" name="your_name" id="your_name" value="<?php if (isset($posted['your_name'])) echo $posted['your_name']; else echo get_post_meta($job_details->ID, '_Company', true); ?>" /></p>
			<p class="optional"><label for="website"><?php _e('Website', 'appthemes'); ?></label> <input type="text" class="text" name="website" value="<?php if (isset($posted['website'])) echo $posted['website']; else echo get_post_meta($job_details->ID, '_CompanyURL', true); ?>" placeholder="http://" id="website" /></p>
			<p class="optional"><label for="company-logo"><?php _e('Logo (.jpg, .gif or .png)', 'appthemes'); ?></label> <input type="file" class="text" name="company-logo" id="company-logo" /></p>
		</fieldset>	
		<fieldset>
			<legend><?php _e('Job Details', 'appthemes'); ?></legend>
			<p><?php _e('Enter details about the job below. Be as descriptive as possible so that potential candidates can find your job listing easily.', 'appthemes'); ?></p>
			<p><label for="job_title"><?php _e('Job title', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text" name="job_title" id="job_title" value="<?php if (isset($posted['job_title'])) echo $posted['job_title']; else echo $job_details->post_title; ?>" /></p>
			<p><label for="job_type"><?php _e('Job type', 'appthemes'); ?> <span title="required">*</span></label> <select name="job_term_type" id="job_type">
				<?php
				$job_types = get_terms( 'job_type', array( 'hide_empty' => '0' ) );
				if ($job_types && sizeof($job_types) > 0) {
					foreach ($job_types as $type) {
						?>
						<option value="<?php echo $type->slug; ?>" <?php 
							if (isset($posted['job_term_type'])) :
								if ($posted['job_term_type']==$type->slug) : echo 'selected="selected"'; endif;
							else :
								$job_has_terms = array();
								$get_terms = wp_get_post_terms( $job_details->ID, 'job_type' );
								foreach ($get_terms as $term) :
									$job_has_terms[] = $term->slug;
								endforeach;
								if (in_array($type->slug, $job_has_terms)) : echo 'selected="selected"'; endif;
							endif;
							?>><?php echo $type->name; ?></option>
						<?php
					}
				}
				?>
			</select></p>
			<p class="<?php if (get_option('jr_submit_cat_required')!=='yes') : echo 'optional'; endif; ?>"><label for="job_cat"><?php _e('Job Category', 'appthemes'); ?> <?php if (get_option('jr_submit_cat_required')=='yes') : ?><span title="required">*</span><?php endif; ?></label> <?php
				$sel = 0;
				global $featured_job_cat_id;
				if (isset($posted['job_term_cat']) && $posted['job_term_cat']>0) :
					$sel = $posted['job_term_cat']; 
				else :
					$get_terms = wp_get_post_terms( $job_details->ID, 'job_cat' );
					foreach ($get_terms as $term) :
						if ($term->term_id!==$featured_job_cat_id) $sel = $term->term_id;
					endforeach;
				endif;
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
				$dropdown = str_replace('class=\'job_cat\' >','class=\'job_cat\' ><option value="">Select a category&hellip;</option>',$dropdown);
				echo $dropdown;
			?></p>
			<?php if (get_option('jr_enable_salary_field')!=='no') : ?><p class="optional"><label for="job_term_salary"><?php _e('Job Salary', 'appthemes'); ?></label> <?php
				$sel = 0;
				if (isset($posted['job_term_salary']) && $posted['job_term_salary']>0) :
					$sel = $posted['job_term_salary']; 
				else :
					$get_terms = wp_get_post_terms( $job_details->ID, 'job_salary' );
					foreach ($get_terms as $term) :
						$sel = $term->term_id;
					endforeach;
				endif;
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
				$dropdown = str_replace('class=\'job_salary\' >','class=\'job_salary\' ><option value="">Select a salary&hellip;</option>', $dropdown);
				echo $dropdown;
			?></p><?php endif; ?>
			<p class="optional"><label for="tags"><?php _e('Tags (comma separated)', 'appthemes'); ?></label> <input type="text" class="text" name="tags" value="<?php 
			if (isset($posted['tags'])) :
				echo $posted['tags']; 
			else :
				$job_tags = array();
				$get_terms = wp_get_post_terms( $job_details->ID, 'job_tag' ); 
				foreach ($get_terms as $term) :
					$job_tags[] = $term->name;
				endforeach;
				echo implode(', ', $job_tags ); 
			endif;
			?>" id="tags" /></p>				
		</fieldset>
		<fieldset>
			<legend><?php _e('Job Location', 'appthemes'); ?></legend>								
			<p><?php _e('Leave blank if the location of the applicant does not matter e.g. the job involves working from home.', 'appthemes'); ?></p>
			
			<div id="geolocation_box">
			
				<p><label><input id="geolocation-load" type="button" class="button geolocationadd" value="<?php _e('Find Address/Location', 'appthemes'); ?>" /></label> <input type="text" class="text" name="jr_address" id="geolocation-address" value="<?php if (isset($posted['jr_address'])) echo $posted['jr_address']; else echo get_post_meta($job_details->ID, 'geo_address', true); ?>" /><input type="hidden" class="text" name="jr_geo_latitude" id="geolocation-latitude" value="<?php if (isset($posted['jr_geo_latitude'])) echo $posted['jr_geo_latitude']; else echo get_post_meta($job_details->ID, '_jr_geo_latitude', true); ?>" /><input type="hidden" class="text" name="jr_geo_longitude" id="geolocation-longitude" value="<?php if (isset($posted['jr_geo_longitude'])) echo $posted['jr_geo_longitude']; else echo get_post_meta($job_details->ID, '_jr_geo_longitude', true); ?>" /></p>
	
				<div id="map_wrap" style="border:solid 2px #ddd;"><div id="geolocation-map" style="width:100%;height:350px;"></div></div>
			
			</div>
			
		</fieldset>	
		<fieldset>
			<legend><?php _e('Job Description', 'appthemes'); ?></legend>
			<p><?php _e('Give details about the position, such as responsibilities &amp; salary.', 'appthemes'); ?><?php if (get_option('jr_html_allowed')=='no') : ?><?php _e(' HTML is not allowed.', 'appthemes'); ?><?php endif; ?></p>
		
			<p><textarea rows="5" cols="30" name="details" id="details" class="mceEditor"><?php if (isset($posted['details'])) echo $posted['details']; else echo $job_details->post_content; ?></textarea></p>
		
		</fieldset>
		<?php if (get_option('jr_submit_how_to_apply_display')=='yes') : ?><fieldset>
			<legend><?php _e('How to apply', 'appthemes'); ?></legend>
			<p><?php _e('Tell applicants how to apply &ndash; they will also be able to email you via the &ldquo;apply&rdquo; form on your job listing\'s page.', 'appthemes'); ?><?php if (get_option('jr_html_allowed')=='no') : ?><?php _e(' HTML is not allowed.', 'appthemes'); ?><?php endif; ?></p>
			<p><textarea rows="5" cols="30" name="apply" id="apply" class="how mceEditor"><?php if (isset($posted['apply'])) echo $posted['apply']; else echo get_post_meta($job_details->ID, '_how_to_apply', true); ?></textarea></p>	
					
		</fieldset><?php endif; ?>
		
		
		
		<?php
		$show_payment_options = 0;
		$active_job_packs = 0;
		if ($relisting) :

			// if enabled, let the user select a Job Pack
			$active_job_packs = jr_job_pack_select('edit');					
			
			// if there are active Job Packs show payment options
			$show_payment_options = $active_job_packs;

			echo '<input type="hidden" name="relist" value="true" />';
		?>

		<?php
			$featured_cost = get_option('jr_cost_to_feature');
			if ($featured_cost && is_numeric($featured_cost) && $featured_cost > 0) :
				
				$show_payment_options = 1;
				
				// Featuring is an option
				echo '<h2>'.__('Feature your listing for ', 'appthemes').jr_get_currency($featured_cost).__('?', 'appthemes').'</h2>';
				
				echo '<p>'.__('Featured listings are displayed on the homepage and are also highlighted in all other listing pages.', 'appthemes').'</p>';
				
				echo '<p><input type="checkbox" name="featureit" id="featureit" '.checked(!empty($_POST['featureit']),1, FALSE).'/> <label for="featureit" style="float:none">'.__('Yes please, feature my listing.', 'appthemes').'</label></p>';
				
			endif;
			
		endif;
		?>
		<?php if ( isset($_POST['job_submit']) && isset($_POST['relist']) && !empty($featured_cost) ): ?>

			<fieldset class="pack_offers" style="display: none">
				<h2><?php _e('Feature your Job as part of the Pack offer?','appthemes'); ?></h2>
				<p><?php _e('To feature this job for Free, please make sure you\'ve selected the previous option.','appthemes'); ?></p>
				<p>
				   <input type="radio" name="featured_offer" value="yes" ><?php _e('Yes', 'appthemes'); ?>
				   <input type="radio" name="featured_offer" value="no" checked><?php _e('No','appthemes'); ?>
				</p>
			</fieldset>

		<?php endif; ?>

		<?php if ($show_payment_options) : ?>
			<fieldset class="gateways_fieldset" style="display: none">
				<legend><?php _e('Payment Gateway', 'appthemes'); ?></legend>	
				<select name="gateway" id="gateway" class="gateway">				
				
					<?php jr_order_gateway_options(); ?>
					
				</select>	
			</fieldset>
		<?php endif; ?>
		
		
		<p><input type="submit" class="submit" name="job_submit" value="<?php if ($relisting) _e('Relist &rarr;', 'appthemes'); else _e('Save &rarr;', 'appthemes'); ?>" /></p>
		<?php 
			$relisting_cost = get_option('jr_jobs_relisting_cost');
			if ( $relisting && !$active_job_packs && $relisting_cost && is_numeric($relisting_cost) && $relisting_cost > 0 ):
				echo '<p>'.sprintf(__('( Relist fee: %s )', 'appthemes'), jr_get_currency($relisting_cost) );'</p>';
			endif; 
		?>
		<div class="clear"></div>		
	</form>
	<?php
	if (get_option('jr_html_allowed') == 'yes')
	    jr_tinymce();
	?>

	<?php if (isset($_POST['job_submit']) && isset($_POST['relist']) ): ?>

		<script type="text/javascript">
			/* <![CDATA[ */
			jQuery.noConflict();
			(function($) {

				if (!$('.errors').text()) {
					var $elem = $('.section_content');

					$('pack_offers').show();
					 // display the fetured offer reminder and scroll to screen bottom
					$('html, body').animate({scrollTop: $elem.height()}, 800);
				 }

				 $('input[name="featured_offer"]').live( 'click', function() {

					if ( $(this).val() == 'yes' ) $('#featureit').attr('checked','checked');

				 });

			})(jQuery);
			/* ]]> */
		</script>

	<?php endif ?>

	<?php

}