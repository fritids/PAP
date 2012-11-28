<?php
/**
 * JobRoller Confirm Job Process
 * Once a job has been confirmed, this takes the posted data and adds it to WordPress.
 *
 *
 * @version 1.3
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */
function jr_process_confirm_job_form() {
	
	global $user_ID, $post, $posted, $wpdb, $jr_log;
	
	$posted = json_decode(stripslashes($_POST['posted']), true);
	
	$offer_featured_job = 'no';
	if ( isset($_POST['featured_offer']) && FALSE !== strpos('yes, no', $_POST['featured_offer'] )  )   		
		$offer_featured_job = $_POST['featured_offer'];
	
	// Calc costs/get packs
	$cost = 0;
	$job_pack = '';
	$user_pack = '';
	$inspack = '';
	$insfeatured ='';
	$jobs_last = null;
	
	// Get Pack from previous step
	if (isset($posted['job_pack']) && !empty($posted['job_pack'])) : 
		if (strstr($posted['job_pack'], 'user_')) :
			$user_pack_id = (int) str_replace('user_', '', $posted['job_pack']);
			$user_pack = new jr_user_pack( $user_pack_id );
			if (!$user_pack->get_valid_pack()) {
				wp_die( __('Error: Invalid Pack.', 'appthemes'));
			}
			$jobs_last = $user_pack->job_duration;
		else :
			$job_pack = new jr_pack( $posted['job_pack'] );
			$cost += $job_pack->pack_cost;
			$jobs_last = $job_pack->job_duration;
		endif;
	else :
		// No Packs
		$listing_cost = get_option('jr_jobs_listing_cost');
		$cost += $listing_cost;
	endif;
	
	// Caculate expirey date from today
	if (!isset($jobs_last)) $jobs_last = get_option('jr_jobs_default_expires'); // 30 day default
	$date = strtotime('+'.$jobs_last.' day', current_time('timestamp'));
	
	// Get Featured from previous step
	if ( $posted['featureit']=='yes' ) :
		$featured_cost = get_option('jr_cost_to_feature');
		// charge featured jobs if not part of the Pack offer
		if ($offer_featured_job == 'no') $cost += $featured_cost;
	endif;	
	
	### Validate required again - just in case
		$required = array(
			//'your_name' => __('Your name', 'appthemes'),
			'job_title' => __('Job title', 'appthemes'),
			'job_term_type' => __('Job type', 'appthemes'),
			'details' => __('Job description', 'appthemes'),
		);

		foreach ($required as $field=>$name) {
			if (empty($posted[$field])) {
				wp_die( __('Error: Unable to create entry.', 'appthemes'));
			}
		}
	
	### Approval needed?
	
		$status = 'publish'; 
		
		if ($cost > 0) :
			$status = 'pending';
		else :
			// Free listing
			if (get_option('jr_jobs_require_moderation')=='yes') :
				$status = 'pending';
			endif;
		endif;
		
		if ($user_pack) :
			$status = 'publish'; 
		endif;
	
	### Create Post	
		
		$data = array(
			'post_content' => $wpdb->escape($posted['details'])
			, 'post_title' => $wpdb->escape(strip_tags($posted['job_title']))
			, 'post_status' => $status
			, 'post_author' => $user_ID
			, 'post_type' => 'job_listing'
		);		
		
		jr_before_insert_job($data); // do_action hook
		
		$post_id = wp_insert_post($data);		
		
		jr_after_insert_job($post_id); // do_action hook
	
		// Was the post created?
		
		if ($post_id==0 || is_wp_error($post_id)) wp_die( __('Error: Unable to create entry.', 'appthemes') );
	
	### Add meta data and category

		add_post_meta($post_id, '_Company', strip_tags($posted['your_name']), true);
		add_post_meta($post_id, '_CompanyURL', $posted['website'], true);
		add_post_meta($post_id, '_how_to_apply', $posted['apply'], true);
		add_post_meta($post_id, 'user_IP', jr_getIP(), true);
		add_post_meta($post_id, '_expires', $date);
		
	### GEO
	
		if (!empty($posted['jr_address'])) :
		
			$latitude = jr_clean_coordinate($posted['jr_geo_latitude']);
			$longitude = jr_clean_coordinate($posted['jr_geo_longitude']);
			
			add_post_meta($post_id, '_jr_geo_latitude', $posted['jr_geo_latitude'], true);
			add_post_meta($post_id, '_jr_geo_longitude', $posted['jr_geo_longitude'], true);
			
			if ($latitude && $longitude) :
			
				// If we don't have address data, do a look-up
				if ( $posted['jr_geo_short_address'] && $posted['jr_geo_country'] && $posted['jr_geo_short_address'] && $posted['jr_geo_short_address_country'] ) :
					add_post_meta($post_id, 'geo_address', $posted['jr_geo_short_address'], true);
					add_post_meta($post_id, 'geo_country', $posted['jr_geo_country'], true);
					add_post_meta($post_id, 'geo_short_address', $posted['jr_geo_short_address'], true);
					add_post_meta($post_id, 'geo_short_address_country', $posted['jr_geo_short_address_country'], true);
				else :
					$address = jr_reverse_geocode($latitude, $longitude);
				
					add_post_meta($post_id, 'geo_address', $address['address'], true);
					add_post_meta($post_id, 'geo_country', $address['country'], true);
					add_post_meta($post_id, 'geo_short_address', $address['short_address'], true);
					add_post_meta($post_id, 'geo_short_address_country', $address['short_address_country'], true);
				endif;

			endif;
		
		else :
			// They left the field blank so we assume the job is for 'anywhere'
		endif;		
		
	### Set terms
		
		$post_into_cats = array();
		$post_into_types = array();
		
		if ($posted['job_term_cat']>0) $post_into_cats[] = get_term_by( 'id', $posted['job_term_cat'], 'job_cat')->slug;

		if (!empty($featured_cost) && is_numeric($featured_cost) && $featured_cost > 0 && $posted['featureit']=='yes') :
			global $featured_job_cat_id;
			$featured_job_cat_name = get_term_by('id', $featured_job_cat_id, 'job_cat')->name;
			$post_into_cats[] = sanitize_title($featured_job_cat_name);
		endif;
		
		if (sizeof($post_into_cats)>0) wp_set_object_terms($post_id, $post_into_cats, 'job_cat');
		
		$post_into_types[] = get_term_by( 'slug', sanitize_title($posted['job_term_type']), 'job_type')->slug;
		
		if (sizeof($post_into_types)>0) wp_set_object_terms($post_id, $post_into_types, 'job_type');
	
	### Salary
			
		$salary = array();
	
		if ($posted['job_term_salary']>0) $salary[] = get_term_by( 'id', $posted['job_term_salary'], 'job_salary')->slug;
		
		if (sizeof($salary)>0) wp_set_object_terms($post_id, $salary, 'job_salary');
		
	### Tags
	
		if ($posted['tags']) :
			
			$thetags = explode(',', $posted['tags']);
			$thetags = array_map('trim', $thetags);
			$thetags = array_map('strtolower', $thetags);
			
			if (sizeof($thetags)>0) wp_set_object_terms($post_id, $thetags, 'job_tag');
			
		endif;
				
	## Load APIs and Link to company image
	
		include_once(ABSPATH . 'wp-admin/includes/file.php');			
		include_once(ABSPATH . 'wp-admin/includes/image.php');			
		include_once(ABSPATH . 'wp-admin/includes/media.php');

		if ( isset($posted['company-logo-name']) ) {
				
			$name_parts = pathinfo($posted['company-logo-name']);
			$name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );
			
			$url = $posted['company-logo'];
			$type = $posted['company-logo-type'];
			$file = $posted['company-logo-file'];
			$title = $posted['company-logo-name'];
			$content = '';
			
			if ($file) :
			
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
					'post_parent' => $post_id,
					'post_title' => $title,
					'post_content' => $content,
				), array() );
		
				// Save the data
				$id = wp_insert_attachment($attachment, $file, $post_id);
				if ( !is_wp_error($id) ) {
					wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
				}
				
				update_post_meta( $post_id, '_thumbnail_id', $id );
			
			endif;
		
		}	
	
	### If paying with user pack, update the customers pack totals
	
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
	
	if ($posted['featureit']=='yes') $insfeatured = 1;
	
	### Create the order in the database so it can be confirmed by user/IPN before going live
	
	if ($cost > 0) :
	
		$jr_order = new jr_order( 0, $user_ID, $cost, $post_id, $inspack, $insfeatured );
		
		$jr_order = apply_filters('jr_order', $jr_order);
		
		jr_before_insert_order( $jr_order );
		
		$jr_order->insert_order();
		
		jr_after_insert_order( $jr_order );
		
		$order_description = __('Job Listing ', 'appthemes').$posted['job_title'];
		
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
			
		### FREE LISTING / LISTING PAID WITH USER PACK (no additional cost)
		
		if (!empty($job_pack)):		
			// Add free pack to user's account		
			$job_pack->give_to_user( $user_ID );	
		endif;			
	
		if (get_option('jr_jobs_require_moderation')=='yes') {
			
			if (get_option('jr_new_ad_email')!=='no') jr_admin_new_job_pending($post_id);
			jr_owner_new_job_pending($post_id);
				
		} elseif (get_option('jr_new_ad_email')!=='no') jr_admin_new_job($post_id);
	
		redirect_myjobs();
			
	endif;

}