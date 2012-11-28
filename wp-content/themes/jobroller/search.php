<?php if (isset($_GET['resume_search']) && $_GET['resume_search']) : get_template_part('search-resume'); exit; endif; ?>

<?php if (isset($_GET['location'])) $_GET['location'] = urldecode(utf8_uri_encode($_GET['location'])); ?>

<?php get_header('search'); ?>
	
<?php do_action('jobs_will_display'); ?>

	<div class="section">

		<?php
		// Global so we can pass it on to the filter-process.php file
		global $find_posts_in, $search_result_ids, $wp_query, $query_string;

		query_posts($query_string . '&posts_per_page=-1');

		$term_heading = '';
		$location_heading = '';

		$search = get_search_query();
		$location = isset( $_GET['location'] ) ? trim($_GET['location']) : '';
		$radius = isset( $_GET['radius'] ) ? absint( $_GET['radius'] ) : 0;
		if ( !$radius )
			$radius = 50;

		if ($search) :
			$search_result_ids[] = 0;
		
			if ($wp_query->posts) foreach ($wp_query->posts as $p) : $search_result_ids[] = $p->ID.''; endforeach;
			
			$term_heading = __('Searching for ','appthemes').'&ldquo;'.$search.'&rdquo; ';
		endif;
		
		if ($location) :
			// Get address from post data
			$address_array = '';
			
			if (isset($_REQUEST['latitude']) && $_REQUEST['latitude'] && $_REQUEST['longitude'] && $_REQUEST['full_address']) :
				$address_array = array(
					'north_east_lng' 	=> trim(stripslashes( $_REQUEST['north_east_lng'] )),
					'south_west_lng'	=> trim(stripslashes( $_REQUEST['south_west_lng'] )),
					'north_east_lat' 	=> trim(stripslashes( $_REQUEST['north_east_lat'] )),
					'south_west_lat'	=> trim(stripslashes( $_REQUEST['south_west_lat'] )),
					'longitude' 	=> trim(stripslashes( $_REQUEST['longitude'] )),
					'latitude' 		=> trim(stripslashes( $_REQUEST['latitude'] )),
					'full_address' 	=> trim(stripslashes( $_REQUEST['full_address'] ))
				);
			endif;

			// Do radial search
			$radial_result = jr_radial_search($location, $radius, $address_array);
			if (is_array($radial_result)) :
				if ($radial_result['address']) $location = $radial_result['address'];
				$find_posts_in = $radial_result['posts'];
			endif;
			
			if ( get_option('jr_distance_unit') == 'km' )
				$format = __( 'Jobs within %s kilometers of %s', 'appthemes' );
			else
				$format = __( 'Jobs within %s miles of %s', 'appthemes' );

			$location_heading = sprintf( $format, $radius, $location );
		
		endif;

		if ( !$term_heading && !$location_heading )
			$term_heading = __('Search Results', 'appthemes');
		?>

		<h1 class="pagetitle"><?php echo $term_heading.$location_heading; ?> <?php if ( $paged>1 ) : ?>(<?php _e('page','appthemes'); ?> <?php echo number_format_i18n( $paged ); ?>)<?php endif; ?></h1>

		<?php   
		$args = jr_filter_form();
		query_posts($args);
		?>

		<?php get_template_part( 'loop', 'job' ); ?>

		<?php jr_paging(); ?>

		<div class="clear"></div>

	</div><!-- end section -->
	
	<?php do_action('after_search_results'); ?>

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>
