<?php 
	jr_resume_page_auth(); 
	
	if (!jr_resume_is_visible()) :
		wp_redirect( get_post_type_archive_link('resume') ); 
		exit;
	endif;
	
	if (isset($_GET['location'])) $_GET['location'] = urldecode(utf8_uri_encode($_GET['location']));
?>
<?php get_header('resume-search'); ?>

	<div class="section">

		<?php
		global $wp_query, $query_string;
		
		$term_heading = '';
		$find_posts_in = '';

		$search = get_search_query();
		$location = isset( $_GET['location'] ) ? trim($_GET['location']) : '';
		$radius = isset( $_GET['radius'] ) ? absint( $_GET['radius'] ) : 0;
		if ( !$radius )
			$radius = 50;
		
		if ($search) :
			$term_heading = sprintf( __('Searching resumes for &ldquo;%s&rdquo; ', 'appthemes'), get_search_query());
		else :
			$term_heading = __('Searching resumes ', 'appthemes');
		endif;
		
		if ($location) :
			
			$radial_result = jr_radial_search($location, $radius);
			if (is_array($radial_result)) :
				if ($radial_result['address']) $location = $radial_result['address'];
				$find_posts_in = $radial_result['posts'];
			endif;
			
			$term_heading .= __('within ','appthemes').' '.$radius;
			if (get_option('jr_distance_unit')=='km') $term_heading .= 'km '; else $term_heading .= ' Miles '; 
			$term_heading .= __('of','appthemes').' '.ucwords($location);
			
			$find_posts_in[] = 0;
			
		endif;
		
 		if (is_array($find_posts_in)) :
	 		$args = array_merge( $wp_query->query,
		 		array(
					'post_type' => 'resume',
					'post__in' => $find_posts_in
				)
			);
		else :
			 $args = array_merge( $wp_query->query,
		 		array(
					'post_type' => 'resume'
				)
			);
		endif;
		query_posts( $args );
		?>
		
		<h1 class="pagetitle"><?php echo $term_heading; ?> <?php if ($paged>1) : ?>(<?php _e('page','appthemes'); ?> <?php echo number_format_i18n( $paged ); ?>)<?php endif; ?></h1>

		<?php get_template_part( 'loop', 'resume' ); ?>

        <?php jr_paging(); ?>

        <div class="clear"></div>

    </div><!-- end section -->

    <div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('resume'); ?>

<?php get_footer(); ?>
