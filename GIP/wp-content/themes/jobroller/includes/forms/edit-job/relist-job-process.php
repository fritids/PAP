<?php
/**
 * JobRoller Relist Job Process
 * Processes a job edit/relist.
 *
 *
 * @version 1.3
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */
 
function jr_process_relist_job_form() {
	
	global $user_ID, $post, $posted, $job_details, $wpdb, $jr_log;
	
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
			'job_pack',
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
		
		if (isset($_POST['featureit']) && $_POST['featureit']) :
			$posted['featureit'] = 'yes';
		else :
			$posted['featureit'] = '';
		endif;
		
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
			
			// Calc costs/get packs
				$cost = 0;
				$job_pack = '';
				$user_pack = '';
				$jobs_last = '';
				
				$offer_featured_job = 'no';
				if ( isset($_POST['featured_offer']) && FALSE !== strpos('yes, no', $_POST['featured_offer'] )  )   		
					$offer_featured_job = $_POST['featured_offer'];

				// Get Pack from previous step
				if (isset($posted['job_pack']) && !empty($posted['job_pack'])) : 
					if (strstr($posted['job_pack'], 'user_')) :
						$user_pack_id = (int) str_replace('user_', '', $posted['job_pack']);
						$user_pack = new jr_user_pack( $user_pack_id );
						if (!$user_pack->get_valid_pack()) {
							wp_die( __('Error: Invalid Pack.', 'appthemes'));
						}
						$jobs_last = $user_pack->job_duration;
						$feat_job_offer_remain= $user_pack->feat_job_offer_remain;
						$pack_cats = $user_pack->job_cats;
					else :
						$job_pack = new jr_pack( $posted['job_pack'] );
						$cost += $job_pack->pack_cost;
						$jobs_last = $job_pack->job_duration;
						$feat_job_offer_remain= $job_pack->feat_job_offers;
						$pack_cats = $job_pack->job_cats;
					endif;
				else :
					// No Packs
					$listing_cost = get_option('jr_jobs_relisting_cost');
					$cost += $listing_cost;
				endif;

				# check if the job category can be used with the selected Pack
				if  ( !empty($posted['job_term_cat']) && !empty($pack_cats) && !in_array( $posted['job_term_cat'], $pack_cats ) ) 
					$errors->add('submit_error', __('<strong>ERROR</strong>: ', 'appthemes').__('The Pack you\'ve selected cannot be used with the selected job category.', 'appthemes'));

				if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {
						// invalid job categories for the selected pack
				} else {
				
					// Caculate expirey date from today
					if (!$jobs_last) $jobs_last = 30; // 30 day default
					$date = strtotime('+'.$jobs_last.' day', current_time('timestamp'));

					// Get Featured from previous step
					if ($posted['featureit']=='yes') :
						$featured_cost = get_option('jr_cost_to_feature');
						if ($offer_featured_job == 'no') $cost += $featured_cost;
					endif;

					// inform job lister about the featured job offer, if any
					if ( !isset($_POST['featured_offer']) && $feat_job_offer_remain > 0 && !empty($featured_cost) )
						return;

				### Approval needed?

					$status = 'publish';

					if ($cost > 0 && !$user_pack) :
						$status = 'pending';
					endif;

				### Update Post

					$data = array(
						'ID' => $job_details->ID
						, 'post_content' => $wpdb->escape($posted['details'])
						, 'post_title' => $wpdb->escape($posted['job_title'])
						, 'post_status' => $status
						, 'post_date' => date('Y-m-d H:i:s')
						, 'post_date_gmt' => get_gmt_from_date(date('Y-m-d H:i:s'))
					);
					wp_update_post($data);

				### Update meta data and category

					update_post_meta($job_details->ID, '_Company', $posted['your_name']);
					update_post_meta($job_details->ID, '_CompanyURL', $posted['website']);
					update_post_meta($job_details->ID, '_how_to_apply', $posted['apply']);
					update_post_meta($job_details->ID, '_expires', $date);

					$post_into_cats = array();
					$post_into_types = array();

					if ($posted['job_term_cat']>0) $post_into_cats[] = get_term_by( 'id', $posted['job_term_cat'], 'job_cat')->slug;

				### Set Categories

					if ($posted['featureit']=='yes') :
						global $featured_job_cat_id;
						$featured_job_cat_name = get_term_by('id', $featured_job_cat_id, 'job_cat')->name;
						$post_into_cats[] = sanitize_title($featured_job_cat_name);
					endif;

					if (sizeof($post_into_cats)>0) wp_set_object_terms($job_details->ID, $post_into_cats, 'job_cat');

					$post_into_types[] = get_term_by( 'slug', sanitize_title($posted['job_term_type']), 'job_type')->slug;

					if (sizeof($post_into_types)>0) wp_set_object_terms($job_details->ID, $post_into_types, 'job_type');

				### Salary

					$salary = array();

					if (!empty($posted['job_term_salary'])) $salary[] = get_term_by( 'id', $posted['job_term_salary'], 'job_salary')->slug;

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
					
				### If paying with pack, update the customers pack totals
					
					if ($user_pack) :

						$inspack = '';
						$user_pack->inc_job_count();

						### update job offers
						$user_pack->inc_offers_count('job');

						### update featured job offers
						if ($offer_featured_job == 'yes') { $user_pack->inc_offers_count('featured'); };

					elseif( !empty($posted['job_pack']) ) :
					
						$inspack = $posted['job_pack'];
					
					endif;
					
					if ($posted['featureit']=='yes') $insfeatured = 1; else $insfeatured = 0;

				### Create the order in the database so it can be confirmed by user/IPN before going live
					if ($cost > 0) :

						$jr_order = new jr_order( 0, $user_ID, $cost, $job_details->ID, $inspack, $insfeatured );

						$jr_order = apply_filters('jr_order', $jr_order);

						jr_before_insert_order( $jr_order );

						$jr_order->insert_order();

						jr_after_insert_order( $jr_order );

						$order_description = __('Relisting ', 'appthemes').$posted['job_title'];

						if ($insfeatured):
							$order_description .= __(' (Featured) ', 'appthemes');
						endif;

						if (!empty($job_pack)):
							$order_description .= __(' with Job Pack ', 'appthemes'). $job_pack->pack_name;
						endif;

						// Apply filter to the Order description
						$order_description = apply_filters('jr_order_description', $order_description);

						### Redirect to payment page (if paid listing)

						// Redirect user to a payment gateway
						jr_order_gateway_redirect( $order_description, $jr_order );

						exit;

					else :
					
						### Relisting was free

						if (!empty($job_pack)):
							// Add free pack to user's account
							$job_pack->give_to_user( $user_ID );
						endif;

						wp_mail(get_option('admin_email'), __('Job Re-Listed ', 'appthemes').'['.get_bloginfo('name').']', __('A job has been re-listed called  ', 'appthemes').'"'.$posted['job_title'].'" ('.__('ID', 'appthemes').': '.$job_details->ID.")\n\nEdit post link: ".admin_url("post.php?action=edit&post=".$job_details->ID."")."\nView Post: ".get_permalink($job_details->ID) );

						### Redirect to my jobs
						$args = array('relist_success' => 1);
						redirect_myjobs($args);

					endif;

			} // endif invalid pack

		} // endif errors

	}	

	$form_results = array(
		'errors' => $errors,
		'posted' => $posted
	);

	return $form_results;

}