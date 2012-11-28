<?php
/*
Template Name: Edit Job Template
*/
?>
<?php
	### Prevent Caching
	nocache_headers();
	
	appthemes_auth_redirect_login();
	
	if (isset($_REQUEST['relist'])) $relisting = true; else $relisting = false;
	
	if (!$relisting && get_option('jr_allow_editing')=='no') :
		redirect_myjobs();
	endif; 
	
	if ($relisting && get_option('jr_allow_relist')=='no') :
		redirect_myjobs();
	endif;
	
	$message = '';
	
	global $post, $job_details;
    
    jr_load_form_scripts();

	global $posted;
	$posted = array();
	$errors = new WP_Error();
	
	### Get job details and check the user has permission to edit the listing
	
	$jobID = (int) $_GET['edit'];
	
	if ($jobID>0) :
	
		// Get job details
		$job_details = get_post($jobID);
	
		// Permission?
		if (get_current_user_id() == $job_details->post_author) :
		
			// We have permission to edit this!
		
		else : redirect_myjobs(); endif;
	
	else : redirect_myjobs(); endif;
	
	### Process Forms
	
	if ($relisting) $result = jr_process_relist_job_form();
	else $result = jr_process_edit_job_form();
	
	$errors = $result['errors'];
	$posted = $result['posted'];
	
?>

<?php get_header(); ?>

	<div class="section">
	
		<div class="section_content">
		
			<h1><?php if ($relisting) _e('Relisting', 'appthemes'); else _e('Editing', 'appthemes'); ?> &ldquo;<?php echo $job_details->post_title; ?>&rdquo;</h1>

			<?php do_action( 'appthemes_notices' ); ?>

			<?php jr_edit_job_form( $relisting ); ?>

		</div><!-- end section_content -->

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('submit'); ?>

<?php get_footer(); ?>