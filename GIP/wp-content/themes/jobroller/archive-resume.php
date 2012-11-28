<?php
	jr_resume_page_auth(); 
	jr_resume_subscr_process(); 
	
	get_header('resume-search'); 
?>
    <div class="section">    
		
		<?php
			global $message;
			
			// TODO: add error placeholder
			if (isset($message) && !empty($message)) {
				echo '<p class="success">'.$message.'</p>';
			}			
						
		?>
		
		<?php if (jr_resume_is_visible()) : ?>
		
	        <h1 class="pagetitle"><?php 
	        	_e('Resumes', 'appthemes'); 
	
	        	if ( is_tax( 'resume_category' ) ) :
	        		
	        		$slug = get_query_var('resume_category');
			  		$term = get_term_by( 'slug', $slug, 'resume_category');
			  		echo sprintf( __(' in the %s category.', 'appthemes'), $term->name);
	        		
	        	elseif ( is_tax( 'resume_languages' ) ) :
	        	
	        		$slug = get_query_var('resume_languages');
			  		$term = get_term_by( 'slug', $slug, 'resume_languages');
			  		echo sprintf( __(' of people who speak %s.', 'appthemes'), $term->name);
	        	
	        	elseif ( is_tax( 'resume_interests' ) ) :
	        	
	        		$slug = get_query_var('resume_interests');
			  		$term = get_term_by( 'slug', $slug, 'resume_interests');
			  		echo sprintf( __(' of people interested in %s.', 'appthemes'), $term->name);
	        	
	        	elseif ( is_tax( 'resume_groups' ) ) :
	        	
	        		$slug = get_query_var('resume_groups');
			  		$term = get_term_by( 'slug', $slug, 'resume_groups');
			  		echo sprintf( __(' of members of %s.', 'appthemes'), $term->name);
	        	
	        	elseif ( is_tax( 'resume_specialities' ) ) :
	        	
	        		$slug = get_query_var('resume_specialities');
			  		$term = get_term_by( 'slug', $slug, 'resume_specialities');
			  		echo sprintf( __(' of people specialising in %s.', 'appthemes'), $term->name);
	        	
	        	elseif ( is_tax( 'resume_job_type' )) :
	        		
	        		$slug = get_query_var('resume_job_type');
			  		$term = get_term_by( 'slug', $slug, 'resume_job_type');
			  		echo sprintf( __(' of people wanting a %s job.', 'appthemes'), $term->name);
	        		
	        	endif;
	        ?></h1>
	
	        <?php get_template_part( 'loop', 'resume' ); ?>
	
	        <?php jr_paging(); ?>
        
        <?php else : ?>
        	
        	<h1 class="pagetitle"><?php _e('Resumes', 'appthemes'); ?></h1>
        	
        	<?php if ( jr_viewing_resumes_require_subscription() && jr_current_user_can_subscribe_for_resumes() ) :
        		
        		if ($notice = get_option('jr_resume_subscription_notice')) echo '<p>'.wptexturize($notice).'</p>';
        		        		
				jr_subscribe_resumes_form();
        		
        	else :
        		
        		echo '<p>'.sprintf(__('Sorry, you do not have permission to browse and view resumes. Please <a href="%s">login or register</a>.', 'appthemes'), home_url('wp-login.php')).'</p>';
        		
        	endif; ?>
        	
        <?php endif; ?>

        <div class="clear"></div>

    </div><!-- end section -->

    <div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('resume'); ?>

<?php get_footer(); ?>