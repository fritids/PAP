<?php
/**
 * JobRoller Edit Job Process
 * Processes a job edit.
 *
 *
 * @version 1.2
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_process_edit_job_form() {
	
	global $post, $posted, $job_details, $wpdb;
	
	$errors = new WP_Error();
	if (isset($_POST['job_submit']) && $_POST['job_submit']) {	
	
		include_once(ABSPATH . 'wp-admin/includes/file.php');			
		include_once(ABSPATH . 'wp-admin/includes/image.php');			
		include_once(ABSPATH . 'wp-admin/includes/media.php');

		// Get (and clean) data
		$fields = array(
			'your_name',
			'website',
			'job_title',
			'job_term_type',
			'job_term_cat',
			'job_term_salary',
			'jr_geo_latitude',
			'jr_geo_longitude',
			'jr_address',
			'details',
			'apply',
			'tags'
		);
		foreach ($fields as $field) {
			$posted[$field] = stripslashes(trim($_POST[$field]));
		}
		
		### Strip html
		
		if (get_option('jr_html_allowed')=='no') :
			
			$posted['details'] = strip_tags($posted['details']);
			$posted['apply'] = strip_tags($posted['apply']);
			
		endif;
		
		### End strip

		// Check required fields
		$required = array(
			//'your_name' => __('Your name', 'appthemes'),
			'job_title' => __('Job title', 'appthemes'),
			'job_term_type' => __('Job type', 'appthemes'),
			'details' => __('Job description', 'appthemes'),
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
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {
			
			// Do nothing - edit has failed
			
		} else {

			// So far, so good. Upload logo if set.
			
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
				} 
	
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
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {
			
			// Do nothing - edit has failed
			
		} else {
			
			// Good to go, lets save this bad boy and show a confirmation message
			
			### Approval needed?
			
			if ($job_details->post_status=='publish') :
			
				$status = 'publish'; 
				
				if ( get_option('jr_editing_needs_approval') == 'yes' ) :
					// Post is made pending
					$status = 'pending';
				endif;
			
			else :
				
				$status = $job_details->post_status;
				
			endif;
			
			### Update Post	
				
				$data = array(
					'ID' => $job_details->ID,
					'post_content' => $wpdb->escape($posted['details'])
					, 'post_title' => $wpdb->escape($posted['job_title'])
					, 'post_status' => $status
				);
				wp_update_post($data);
			
			### Update meta data and category
			
				update_post_meta($job_details->ID, '_Company', $posted['your_name']);
				update_post_meta($job_details->ID, '_CompanyURL', $posted['website']);
				update_post_meta($job_details->ID, '_how_to_apply', $posted['apply']);			
				
				$post_into_cats = array();
				$post_into_types = array();
				
				if ($posted['job_term_cat']>0) $post_into_cats[] = get_term_by( 'id', $posted['job_term_cat'], 'job_cat')->slug;
				
			### KEEP featured category if already featured
				
				global $featured_job_cat_id;
				$is_featured = false;
				
				$get_terms = wp_get_post_terms( $job_details->ID, 'job_cat' );
				foreach ($get_terms as $term) :
					if ($term->term_id==$featured_job_cat_id) $is_featured = true;
				endforeach;
						
				if ($is_featured) :
					$featured_job_cat_name = get_term_by('id', $featured_job_cat_id, 'job_cat')->name;
					$post_into_cats[] = sanitize_title($featured_job_cat_name);
				endif;
				
				if (sizeof($post_into_cats)>0) wp_set_object_terms($job_details->ID, $post_into_cats, 'job_cat');
				
				$post_into_types[] = get_term_by( 'slug', sanitize_title($posted['job_term_type']), 'job_type')->slug;
				
				if (sizeof($post_into_types)>0) wp_set_object_terms($job_details->ID, $post_into_types, 'job_type');
				
			### Salary
			
				$salary = array();
			
				if ($posted['job_term_salary']>0) $salary[] = get_term_by( 'id', $posted['job_term_salary'], 'job_salary')->slug;
				
				wp_set_object_terms($job_details->ID, $salary, 'job_salary');
			
			### Tags
	
				if ($posted['tags']) :
					
					$thetags = explode(',', $posted['tags']);
					$thetags = array_map('trim', $thetags);
					$thetags = array_map('strtolower', $thetags);
					
					if (sizeof($thetags)>0) wp_set_object_terms($job_details->ID, $thetags, 'job_tag');
					
				endif;
		
			### GEO
			
				if (!empty($posted['jr_address'])) :
		
					$latitude = jr_clean_coordinate($posted['jr_geo_latitude']);
					$longitude = jr_clean_coordinate($posted['jr_geo_longitude']);
					
					update_post_meta($job_details->ID, '_jr_geo_latitude', $latitude);
					update_post_meta($job_details->ID, '_jr_geo_longitude', $longitude);
					
					if ($latitude && $longitude) :
						$address = jr_reverse_geocode($latitude, $longitude);
						
						update_post_meta($job_details->ID, 'geo_address', $address['address']);
						update_post_meta($job_details->ID, 'geo_country', $address['country']);
						update_post_meta($job_details->ID, 'geo_short_address', $address['short_address']);
						update_post_meta($job_details->ID, 'geo_short_address_country', $address['short_address_country']);
			
					endif;
				
				else :
				
					// They left the field blank so we assume the job is for 'anywhere'
					delete_post_meta($job_details->ID, '_jr_geo_latitude');
					delete_post_meta($job_details->ID, '_jr_geo_longitude');
					delete_post_meta($job_details->ID, 'geo_address');
					delete_post_meta($job_details->ID, 'geo_country');
					delete_post_meta($job_details->ID, 'geo_short_address');
					delete_post_meta($job_details->ID, 'geo_short_address_country');
				
				endif;
			
			## Link to company image
				
				if (isset($posted['company-logo']) && $posted['company-logo']) :
				
					$name_parts = pathinfo($posted['company-logo-name']);
					$name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );
					
					$url = $posted['company-logo'];
					$type = $posted['company-logo-type'];
					$file = $posted['company-logo-file'];
					$title = $posted['company-logo-name'];
					$content = '';
			
					// use image exif/iptc data for title and caption defaults if possible
					if ( $image_meta = @wp_read_image_metadata($file) ) {
						if ( trim($image_meta['title']) )
							$title = $image_meta['title'];
						if ( trim($image_meta['caption']) )
							$content = $image_meta['caption'];
					}
			
					// Construct the attachment array
					$attachment = array_merge( array(
						'post_mime_type' => $type,
						'guid' => $url,
						'post_parent' => $job_details->ID,
						'post_title' => $title,
						'post_content' => $content,
					), array() );
		
					// Save the data
					$id = wp_insert_attachment($attachment, $file, $job_details->ID);
					if ( !is_wp_error($id) ) {
						wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
					}
					
					update_post_meta( $job_details->ID, '_thumbnail_id', $id );	
				
				endif;
					
				### Inform admin
				
				if ( get_option('jr_jobs_require_moderation') == 'yes' ) :
					
					jr_edited_job_pending($job_details->ID);
					
				endif;
				
				$args = array('edit_success' => 1);
				redirect_myjobs($args);
			
		} // endif errors

	}	
	
	$form_results = array(
		'errors' => $errors,
		'posted' => $posted
	);
	
	return $form_results;

}