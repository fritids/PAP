<?php
/**
 * JobRoller Geoloaction functions
 * This file controls code for the Geolocation features.
 * Geolocation adapted from 'GeoLocation' plugin by Chris Boyd - http://geo.chrisboyd.net
 *
 *
 * @version 1.1
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */
 
define('JR_DEFAULT_ZOOM', 1);

function jr_clean_coordinate($coordinate) {
	//$pattern = '/^(\-)?(\d{1,3})\.(\d{1,15})/';
	$pattern = '/^(\-)?(\d{1,3}).(\d{1,15})/';
	preg_match($pattern, $coordinate, $matches);
	if (isset($matches[0])) return $matches[0];
}

function jr_reverse_geocode($latitude, $longitude) {

    $jr_gmaps_lang = get_option('jr_gmaps_lang');
	$jr_gmaps_region = get_option('jr_gmaps_region');
	$http = (is_ssl()) ? 'https' : 'http';
	
	$url = "http://maps.google.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&language=".$jr_gmaps_lang."&region=".$jr_gmaps_region."&gl=".$jr_gmaps_region."&sensor=false";

	$result = wp_remote_get($url);
	
	if( is_wp_error( $result ) ) :
		global $jr_log;
		$jr_log->write_log( __('Could not access Google Maps API. Your server may be blocking the request.', 'appthemes') ); 
		return false;
	endif;
	$json = json_decode($result['body']);
	$city = '';
	$country = '';
	$short_country = '';
	$state = '';

	foreach ($json->results as $result)
	{
		foreach($result->address_components as $addressPart) {
			if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
	    		$city = $addressPart->long_name;
	    	else if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types)))
	    		$state = $addressPart->long_name;
	    	else if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
	    		$country = $addressPart->long_name;
	    		$short_country = $addressPart->short_name;
	    	}
		}
		if(($city) && ($state) && ($country)) break;
	}
			
	if(($city != '') && ($state != '') && ($country != ''))
		$address = $city.', '.$state.', '.$country;
	else if(($city != '') && ($state != ''))
		$address = $city.', '.$state;
	else if(($state != '') && ($country != ''))
		$address = $state.', '.$country;
	// fix for countries with no valid state
	else if(($city != '') && ($country !=''))
		$address = $city . ', ' . $country;							
	//		
	else if($country != '')
		$address = $country;
		
	if ($country=='United Kingdom') $short_country = 'UK';
		
	if(($city != '') && ($state != '') && ($country != '')) {
		$short_address = $city;
		$short_address_country = $state.', '.$country;
	} else if(($city != '') && ($state != '')) {
		$short_address = $city;
		$short_address_country = $state;
	} else if(($state != '') && ($country != '')) {
		$short_address = $state;
		$short_address_country = $country;
	// fix for countries with no valid state
	} else if(($city != '') && ($country != '')){
		$short_address = $city;
		$short_address_country = $country;
	//		
	} else if($country != '') {
		$short_address = $country;
		$short_address_country = '';
	}
	
	return array(
		'address' => $address,
		'country' => $country,
		'short_address' => $short_address,
		'short_address_country' => $short_address_country
	);
}

function jr_geolocation_scripts() {
	$zoom = JR_DEFAULT_ZOOM;
	$http = (is_ssl()) ? 'https' : 'http';
	$google_maps_api = (is_ssl()) ? 'https://maps-api-ssl.google.com/maps/api/js' : 'http://maps.google.com/maps/api/js';
	?>
	<script type="text/javascript">
		
		function initialize_map() {
			
			var hasLocation = false;
			var center = new google.maps.LatLng(0.0,0.0);
			
			var postLatitude =  '<?php global $posted, $job_details, $post; if (isset($posted['jr_geo_latitude'])) echo $posted['jr_geo_latitude']; elseif (isset($job_details->ID)) echo get_post_meta($job_details->ID, '_jr_geo_latitude', true); elseif (isset($post->ID)) echo get_post_meta($post->ID, '_jr_geo_latitude', true); ?>';
			var postLongitude =  '<?php global $posted, $job_details; if (isset($posted['jr_geo_longitude'])) echo $posted['jr_geo_longitude']; elseif (isset($job_details->ID)) echo get_post_meta($job_details->ID, '_jr_geo_longitude', true); elseif (isset($post->ID)) echo get_post_meta($post->ID, '_jr_geo_longitude', true); ?>';

			if((postLatitude != '') && (postLongitude != '') ) {
				center = new google.maps.LatLng(postLatitude, postLongitude);
				hasLocation = true;
				jQuery("#geolocation-latitude").val(center.lat());
				jQuery("#geolocation-longitude").val(center.lng());
				reverseGeocode(center);
			}
				
		 	var myOptions = {
		      zoom: <?php echo $zoom; ?>,
		      center: center,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
		    
		    var geocoder = new google.maps.Geocoder();
		       
		    var map = new google.maps.Map(document.getElementById('geolocation-map'), myOptions);	
			var marker = '';
			
			if(!hasLocation) {
		    	map.setZoom(<?php echo $zoom; ?>);
		    } else {
		    	map.setZoom(9);
		    }
			
			google.maps.event.addListener(map, 'click', function(event) {
				reverseGeocode(event.latLng);
			});
			
			var currentAddress;
			var customAddress = false;
			
			jQuery("#geolocation-load").click(function(){
				if(jQuery("#geolocation-address").val() != '') {
					customAddress = true;
					currentAddress = jQuery("#geolocation-address").val();
					geocode(currentAddress);
					return false;
				} else {
					marker.setMap(null);
					marker = '';
					jQuery("#geolocation-latitude").val('');
					jQuery("#geolocation-longitude").val('');
					return false;
				}
			});
			
			jQuery("#geolocation-address").keyup(function(e) {
				if(e.keyCode == 13)
					jQuery("#geolocation-load").click();
			});

							
			function placeMarker(location) {
				if (marker=='') {
					marker = new google.maps.Marker({
						position: center, 
						map: map, 
						title:'Job Location'
					});
				}
				marker.setPosition(location);
				map.setCenter(location);
				if((location.lat() != '') && (location.lng() != '')) {
					jQuery("#geolocation-latitude").val(location.lat());
					jQuery("#geolocation-longitude").val(location.lng());
				}
			}
			
			function geocode(address) {
				var geocoder = new google.maps.Geocoder();
			    if (geocoder) {
					geocoder.geocode({"address": address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							placeMarker(results[0].geometry.location);
							reverseGeocode(results[0].geometry.location);
							if(!hasLocation) {
						    	map.setZoom(9);
						    	hasLocation = true;
							}
							jQuery("#geodata").html(results[0].geometry.location.lat() + ', ' + results[0].geometry.location.lng());
						}
					});
				}
			}

			function reverseGeocode(location) {
				var geocoder = new google.maps.Geocoder();
			    if (geocoder) {
					geocoder.geocode({"latLng": location}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						var address, country, state, short_address, short_address_country;
						
						var city = [];

						for ( var i in results ) {
						    
						    var address_components = results[i]['address_components'];
						    //alert(results[i]['formatted_address']);
						    for ( var j in address_components ) {
											
						    	var types = address_components[j]['types'];
						    	var long_name = address_components[j]['long_name'];
						    	var short_name = address_components[j]['short_name']; 
						    	
						    	if ( jQuery.inArray('locality', types)>=0 && jQuery.inArray('political', types)>=0 ) {									
									if (jQuery.inArray(long_name, city)<0) city.push(long_name);
						    	}
						    	else if ( jQuery.inArray('administrative_area_level_1', types)>=0 && jQuery.inArray('political', types)>=0 ) {
						    		state = long_name;
						    	}
						    	else if ( jQuery.inArray('country', types)>=0 && jQuery.inArray('political', types)>=0 ) {
						    		country = long_name;
						    	}
						    } 
							
						    if((city) && (state) && (country)) break;
						}
						
						// fix for countries with no valid state
						if (!state) 
							city = city[0];
						else
							city = city.join(", ");

						if((city) && (state) && (country))
							address = city + ', ' + state + ', ' + country;
						else if((city) && (state))
							address = city + ', ' + state;
						else if((state) && (country))
							address = state + ', ' + country;
						// fix for countries with no valid state
						else if((city) && (country)) {
							address = city + ', ' + country;							
						}	
						//
						else if(country)
							address = country;
							
						if((city) && (state) && (country)) {
							short_address = city;
							short_address_country = state + ', ' + country;
						} else if((city) && (state)) {
							short_address = city;
							short_address_country = state;
						} else if((state) && (country)) {
							short_address = state;
							short_address_country = country;
						// fix for countries with no valid state
						} else if((city) && (country)) {
							short_address = city;
							short_address_country = country;
						//							
						} else if(country) {
							short_address = country;
							short_address_country = '';
						}

						// Set address field
						jQuery("#geolocation-address").val(address);
						
						// Set hidden address fields
						jQuery("#geolocation-short-address").val(short_address);
						jQuery("#geolocation-short-address-country").val(short_address_country);
						jQuery("#geolocation-country").val(country);
						
						// Place Marker
						placeMarker(location);
						
						return true;
					} 
					
					});
				}
				return false;
			}

		}

		function loadScript() {
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "<?php echo $google_maps_api; ?>?v=3&sensor=false&language=<?php echo get_option('jr_gmaps_lang') ?>&region=<?php echo get_option('jr_gmaps_region') ?>&gl=<?php echo get_option('jr_gmaps_region') ?>&callback=initialize_map";
			document.body.appendChild(script);
		}
		  
		jQuery(function(){
			// Prevent form submission on enter key
			jQuery("#submit_form").submit(function(e) {
				if (jQuery("input:focus").attr("id")=='geolocation-address') return false;
			});
			loadScript();
		});  
		

	</script>
	<?php
}