<?php
/**
 * JobRoller Submit Job Process
 * Processes a job submission.
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_process_submit_job_form() {
	
	global $post, $posted;
	
	$errors = new WP_Error();
	if (isset($_POST['job_submit']) && $_POST['job_submit']) :
	
		// Get (and clean) data
		$fields = array(
			'your_name',
			'website',
			'job_title',
			'job_term_type',
			'job_term_cat',
			'job_term_salary',
			'jr_address',
			'jr_geo_latitude',
			'jr_geo_longitude',
			'details',
			'apply',
			'tags',
			
			'jr_geo_country',
			'jr_geo_short_address',
			'jr_geo_short_address_country'
		);
		foreach ($fields as $field) {
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
		}
		
		### Strip html
		
		if (get_option('jr_html_allowed')=='no') :
			
			$posted['details'] = strip_tags($posted['details']);
			$posted['apply'] = strip_tags($posted['apply']);
			
		endif;

		### Website
	
		if (!empty($posted['website']) && !strstr($posted['website'], 'http')) :
			$posted['website'] = 'http://' . $posted['website'];
		endif;
		
		### Feature it
		
		$posted['featureit'] = 'no';

		// Check required fields
		$required = array(
			//'your_name' => __('Your name', 'appthemes'),
			'job_title' => __('Job title', 'appthemes'),
			'job_term_type' => __('Job type', 'appthemes'),
			//'job_country' => __('Country', 'appthemes'),
			'details' => __('Job description', 'appthemes'),
			//'apply' => __('How to apply', 'appthemes'),
		);
		
		if (get_option('jr_submit_cat_required')=='yes') :
			$submit_cat = array('job_term_cat' => __('Job category', 'appthemes'));
			$required = array_merge($required, $submit_cat);
		endif;
		
		foreach ($required as $field=>$name) {
			if (empty($posted[$field])) {
				$errors->add('submit_error', __('<strong>ERROR</strong>: &ldquo;', 'appthemes').$name.__('&rdquo; is a required field.', 'appthemes'));
			}
		}
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			if(isset($_FILES['company-logo']) && !empty($_FILES['company-logo']['name'])) {
				
				$posted['company-logo-name'] = $_FILES['company-logo']['name'];
				
				// Check valid extension
				$allowed = array(
					'png',
					'gif',
					'jpg',
					'jpeg'
				);
				
				//$extension = strtolower(pathinfo($_FILES['company-logo']['name'], PATHINFO_EXTENSION));
				$extension = strtolower(substr(strrchr($_FILES['company-logo']['name'], "."), 1));
				
				if (!in_array($extension, $allowed)) {
					$errors->add('submit_error', __('<strong>ERROR</strong>: Only jpg, gif, and png images are allowed.', 'appthemes'));
				} else {
						
					/** WordPress Administration File API */
					include_once(ABSPATH . 'wp-admin/includes/file.php');					
					/** WordPress Media Administration API */
					include_once(ABSPATH . 'wp-admin/includes/media.php');
		
					function company_logo_upload_dir( $pathdata ) {
						$subdir = '/company_logos'.$pathdata['subdir'];
					 	$pathdata['path'] = str_replace($pathdata['subdir'], $subdir, $pathdata['path']);
					 	$pathdata['url'] = str_replace($pathdata['subdir'], $subdir, $pathdata['url']);
						$pathdata['subdir'] = str_replace($pathdata['subdir'], $subdir, $pathdata['subdir']);
						return $pathdata;
					}
					
					add_filter('upload_dir', 'company_logo_upload_dir');
					
					$time = current_time('mysql');
					$overrides = array('test_form'=>false);
					
					$file = wp_handle_upload($_FILES['company-logo'], $overrides, $time);
					
					remove_filter('upload_dir', 'company_logo_upload_dir');
					
					if ( !isset($file['error']) ) {					
						$posted['company-logo'] = $file['url'];
						$posted['company-logo-type'] = $file['type'];
						$posted['company-logo-file'] = $file['file'];
					} 
					else {
						$errors->add('submit_error', __('<strong>ERROR</strong>: ', 'appthemes').$file['error'].'');
					}
						
				}		
			}	
		}		
		
	endif;
	
	$submit_form_results = array(
		'errors' => $errors,
		'posted' => $posted
	);
	
	return $submit_form_results;

}