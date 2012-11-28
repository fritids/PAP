<?php

//jr_geolocation_scripts();

global $meta_boxes, $key;

$key = 'job_meta';
$meta_boxes = array(
	'Expire' => array(
		'name' => '_expires',
		'title' => __('Job Expiry date', 'appthemes'),
		'description' => __('The date/time the job expires.', 'appthemes'),
		'type' => 'datetime'
	),
	'Company' => array(
		'name' => '_Company',
		'title' => __('Your Name/Company Name', 'appthemes'),
		'description' => __('The name of the company advertising the job.', 'appthemes')
	),
	'CompanyURL' => array(
		'name' => '_CompanyURL',
		'title' => __('Website', 'appthemes'),
		'description' => __('Website URL of the company advertising the job.', 'appthemes')
	)
);

if (get_option('jr_submit_how_to_apply_display')=='yes') :
	$apply_meta_boxes = array('How to Apply' => array(
		'name' => '_how_to_apply',
		'title' => __('How to Apply', 'appthemes'),
		'description' => __('Details on how to apply to the job.', 'appthemes'),
		'type' => 'textarea'
	));
	$meta_boxes = array_merge($meta_boxes, $apply_meta_boxes);
endif;

function jr_create_meta_box() {
	global $key;

	if( function_exists( 'add_meta_box' ) ) add_meta_box( 'new-meta-boxes', __('Job Meta', 'appthemes'), 'jr_display_meta_box', 'job_listing', 'normal', 'high' );
	if( function_exists( 'add_meta_box' ) ) add_meta_box( 'location-meta-boxes', __('Job Location', 'appthemes'), 'jr_display_location_meta_box', 'job_listing', 'side', 'high' );
}

function jr_display_meta_box() {
	global $post, $meta_boxes, $key;
	?>
	
<div class="panel-wrap">	
	<div class="form-wrap">
	
		<p><?php _e('These fields control parts of job listings. Rememeber also that: <code>title</code> = Job title, <code>content</code> = Job description, and Post thumbnail/image is used for the company logo.', 'appthemes'); ?></p>
	
		<?php
		wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true );
		
		foreach($meta_boxes as $meta_box) {
			$data = get_post_meta($post->ID, $meta_box[ 'name' ], true);
			?>
			
			<div class="form-field form-required" style="margin:0; padding: 0 8px">
				<label for="<?php echo $meta_box[ 'name' ]; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box[ 'title' ]; ?></label>
				<?php
					if (!isset($meta_box[ 'type' ])) $meta_box[ 'type' ] = 'input';
					
					switch($meta_box[ 'type' ]) :
						case "datetime" :
							if ($post->post_status<>'publish') :
								echo '<p>'.__('Post is not yet published','appthemes').'</p>';
							else :
								if ($data) $date = $data;
								//if (!$data) {
									// Date is 30 days after publish date (this is for backwards compatibility)
									//$date = strtotime('+30 day', strtotime($post->post_date));
								//}
								?>							
								<div style="float:left; margin-right: 10px; min-width: 320px;"><select name="<?php echo $meta_box[ 'name' ]; ?>_month">
									<option value=""></option>
									<?php
									for ($i = 1; $i <= 12; $i++) :
										echo '<option value="'.str_pad($i, 2, '0',STR_PAD_LEFT).'" ';
										if (isset($date) && date_i18n( 'F', $date)==date_i18n( 'F', strtotime('+'.$i.' month', mktime(0,0,0,12,1,2010)) )) echo 'selected="selected"';
										echo '>'. date_i18n( 'F', strtotime('+'.$i.' month', mktime(0,0,0,12,1,2010)) ) .'</option>';
									endfor;
									?>
								</select>
								<select name="<?php echo $meta_box[ 'name' ]; ?>_day">
									<option value=""></option>
									<?php
									for ($i = 1; $i <= 31; $i++) :
										echo '<option value="'.str_pad($i, 2, '0',STR_PAD_LEFT).'" ';
										if (isset($date) && date_i18n( 'd', $date)==str_pad($i, 2, '0',STR_PAD_LEFT)) echo 'selected="selected"';
										echo '>'. str_pad($i, 2, '0',STR_PAD_LEFT) .'</option>';
									endfor;
									?>
								</select>
								<select name="<?php echo $meta_box[ 'name' ]; ?>_year">
									<option value=""></option>
									<?php
									for ($i = 2010; $i <= 2020; $i++) :
										echo '<option value="'.$i.'" ';
										if (isset($date) && date_i18n( 'Y', $date)==$i) echo 'selected="selected"';
										echo '>'. $i .'</option>';
									endfor;
									?>
								</select> @ <input type="text" name="<?php echo $meta_box[ 'name' ]; ?>_hour" size="2" maxlength="2" style="width:2.5em" value="<?php if (isset($date)) echo date_i18n( 'H', $date) ?>" />:<input type="text" name="<?php echo $meta_box[ 'name' ]; ?>_min" size="2" maxlength="2" style="width:2.5em" value="<?php if(isset($date)) echo date_i18n( 'i', $date) ?>" /></div><?php if ($meta_box[ 'description' ]) echo wpautop(wptexturize($meta_box[ 'description' ])); ?>
								<?php
							endif;
						break;
						case "textarea" :
							?>
							<textarea rows="4" cols="40" name="<?php echo $meta_box[ 'name' ]; ?>" style="width:98%; height:75px; margin-right: 10px; none"><?php echo htmlspecialchars( $data ); ?></textarea><?php if ($meta_box[ 'description' ]) echo wpautop(wptexturize($meta_box[ 'description' ])); ?>
							<?php
						break;
						default :
							?>
							<input type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $meta_box[ 'name' ]; ?>" value="<?php echo htmlspecialchars( $data ); ?>" /><?php if ($meta_box[ 'description' ]) echo wpautop(wptexturize($meta_box[ 'description' ])); ?>
							<?php
						break;
					endswitch;
				?>				
				<div class="clear"></div>
			</div>
		
		<?php } ?>
	
	</div>
</div>	
	<?php
}

function jr_save_meta_box( $post_id ) {
	global $post, $meta_boxes, $key;
	
	if ( !isset($_POST[ $key . '_wpnonce' ] ) ) return $post_id;
	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) ) return $post_id;
	
	if ( !current_user_can( 'edit_post', $post_id )) return $post_id;

	foreach( $meta_boxes as $meta_box ) {
		if ($meta_box[ 'name' ]=='_jr_geo_latitude' || $meta_box[ 'name' ]=='_jr_geo_longitude') {
			update_post_meta( $post_id, $meta_box[ 'name' ], jr_clean_coordinate($_POST[ $meta_box[ 'name' ] ]) );
		
		} elseif ($meta_box['type']=='datetime') {
		
			$year = $_POST[ $meta_box[ 'name' ] . '_year' ];
			$month = $_POST[ $meta_box[ 'name' ] . '_month' ];
			$day = $_POST[ $meta_box[ 'name' ] . '_day' ];
			$hour = $_POST[ $meta_box[ 'name' ] . '_hour' ];
			$min = $_POST[ $meta_box[ 'name' ] . '_min' ];
			
			if (!$hour) $hour = '00';
			if (!$min) $min = '00';
			
			if ( checkdate($month, $day, $year) ) :
			
				$date = $year.$month.$day.' '.$hour.':'.$min;
				update_post_meta( $post_id, $meta_box[ 'name' ], strtotime( $date ) );
			
			// Only if fields were posted; dont delete if they are not shown
			elseif (isset($_POST[ $meta_box[ 'name' ] . '_year' ])) :
			
				delete_post_meta( $post_id, $meta_box[ 'name' ] );
			
			endif;
			
		} else {
			update_post_meta( $post_id, $meta_box[ 'name' ], $_POST[ $meta_box[ 'name' ] ] );
		}
	}
	
	// Update location
	
	if (!empty($_POST['jr_address'])) :
		
		$latitude = jr_clean_coordinate($_POST['jr_geo_latitude']);
		$longitude = jr_clean_coordinate($_POST['jr_geo_longitude']);
		
		update_post_meta($post_id, '_jr_geo_latitude', $latitude);
		update_post_meta($post_id, '_jr_geo_longitude', $longitude);
		
		if ($latitude && $longitude) :
		
			// If we don't have address data, do a look-up
			if ( $_POST['jr_address'] && $_POST['jr_geo_country'] && $_POST['jr_geo_short_address'] && $_POST['jr_geo_short_address_country'] ) :
				
				update_post_meta($post_id, 'geo_address', $_POST['jr_address']);
				update_post_meta($post_id, 'geo_country', $_POST['jr_geo_country']);
				update_post_meta($post_id, 'geo_short_address', $_POST['jr_geo_short_address']);
				update_post_meta($post_id, 'geo_short_address_country', $_POST['jr_geo_short_address_country']);
			
			else :
		
				$address = jr_reverse_geocode($latitude, $longitude);
				
				update_post_meta($post_id, 'geo_address', $address['address']);
				update_post_meta($post_id, 'geo_country', $address['country']);
				update_post_meta($post_id, 'geo_short_address', $address['short_address']);
				update_post_meta($post_id, 'geo_short_address_country', $address['short_address_country']);
			endif;
		endif;
	
	else :
	
		// They left the field blank so we assume the job is for 'anywhere'
		delete_post_meta($post_id, '_jr_geo_latitude');
		delete_post_meta($post_id, '_jr_geo_longitude');
		delete_post_meta($post_id, 'geo_address');
		delete_post_meta($post_id, 'geo_country');
		delete_post_meta($post_id, 'geo_short_address');
		delete_post_meta($post_id, 'geo_short_address_country');
	
	endif;

}

function jr_display_location_meta_box() {
	global $post, $meta_boxes, $key;
	
	jr_geolocation_scripts();
	?>
<div class="">	
	<?php wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true ); ?>
						
	<p><?php _e('Leave blank if the location of the applicant does not matter e.g. the job involves working from home.', 'appthemes'); ?></p>
	
	<div id="geolocation_box">
	
		<?php 
			$jr_geo_latitude = get_post_meta($post->ID, '_jr_geo_latitude', true);
			$jr_geo_longitude = get_post_meta($post->ID, '_jr_geo_longitude', true);
			
			if ($jr_geo_latitude && $jr_geo_longitude) :
				
				//$jr_address = jr_reverse_geocode($jr_geo_latitude, $jr_geo_longitude);
				//$jr_address = $jr_address['address'];
				
				$jr_address = get_post_meta($post->ID, 'geo_address', true);
				$jr_geo_country = get_post_meta($post->ID, 'geo_country', true);
				$jr_geo_short_address = get_post_meta($post->ID, 'geo_short_address', true);
				$jr_geo_short_address_country = get_post_meta($post->ID, 'geo_short_address_country', true);
			else :
				$jr_address = 'Anywhere';
			endif;
		?>
	
		<div>
		<input type="text" class="text" name="jr_address" id="geolocation-address" style="width: 180px;" autocomplete="off" value="" /><label><input id="geolocation-load" type="button" class="button geolocationadd" value="<?php _e('Find', 'appthemes'); ?>" /></label>
		<input type="hidden" class="text" name="jr_geo_latitude" id="geolocation-latitude" value="<?php echo $jr_geo_latitude; ?>" />
		<input type="hidden" class="text" name="jr_geo_longitude" id="geolocation-longitude" value="<?php echo $jr_geo_longitude; ?>" />
		
		<input type="hidden" class="text" name="jr_geo_country" id="geolocation-country" value="<?php echo $jr_geo_country; ?>" />
		<input type="hidden" class="text" name="jr_geo_short_address" id="geolocation-short-address" value="<?php echo $jr_geo_short_address; ?>" />
		<input type="hidden" class="text" name="jr_geo_short_address_country" id="geolocation-short-address-country" value="<?php echo $jr_geo_short_address_country; ?>" />
		</div>

		<div id="map_wrap" style="margin-top:5px; border:solid 2px #ddd;"><div id="geolocation-map" style="width:100%;height:200px;"></div></div>
	
	</div>
	
	<p><strong><?php _e('Current location:', 'appthemes'); ?></strong><br/><?php echo $jr_address; ?><?php
		if ($jr_geo_latitude && $jr_geo_longitude) :
			echo '<br/><em>Latitude:</em> '.$jr_geo_latitude;
			echo '<br/><em>Longitude:</em> '.$jr_geo_longitude;
		endif;
	?></p>
</div>	
	<?php
}

add_action( 'admin_menu', 'jr_create_meta_box' );
add_action( 'save_post', 'jr_save_meta_box' );
?>
