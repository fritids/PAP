<?php
/*
Template Name: Submit Job Template
*/
?>
<?php
	### Prevent Caching
	nocache_headers();
    
    jr_load_form_scripts();
	 	
	global $post, $posted;
	
	$submitID = $post->ID;
	
	$posted = array();
	$errors = new WP_Error();
	
	if (!is_user_logged_in()) :
		$step = 1; 
	else :
		$step = 2;
		if (!current_user_can('can_submit_job')) :
			redirect_myjobs();
		endif;
	endif;

	if (isset($_POST['register']) && $_POST['register']) {
	
		$result = jr_process_register_form( get_permalink($submitID) );
		
		$errors = $result['errors'];
		$posted = $result['posted'];

	}
	elseif (isset($_POST['login']) && $_POST['login']) {
	
		$errors = jr_process_login_form();

	}
	elseif (isset($_POST['job_submit']) && $_POST['job_submit']) {	

		$result = jr_process_submit_job_form();
		
		$errors = $result['errors'];
		$posted = $result['posted'];
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) $step = 2; else $step = 3;

	}
	elseif (isset($_POST['preview_submit']) && $_POST['preview_submit']) {
		
		$step = 4;
		
		$posted = json_decode($_POST['posted']);
		
	}
	elseif (isset($_POST['confirm']) && $_POST['confirm']) {
		
		$step = 4;
		
		jr_process_confirm_job_form();
		
	}
	elseif (isset($_POST['goback']) && $_POST['goback']) {
		$posted = json_decode(stripslashes($_POST['posted']), true);
	}
	
	if( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )	
		$message = __('Thank you for registering! An email has been sent to you containing your password.','appthemes');
	
?>

<?php get_header(); ?>

	<div class="section">
	
		<div class="section_content">
		
			<h1><?php _e('Submit a Job', 'appthemes'); ?></h1>

			<?php 
				echo '<ol class="steps">';
				for ($i = 1; $i <= 4; $i++) :
					echo '<li class="';
					if ($step==$i) echo 'current ';
					if (($step-1)==$i) echo 'previous ';
					if ($i<$step) echo 'done';
					echo '"><span class="';
					if ($i==1) echo 'first';
					if ($i==4) echo 'last';
					echo '">';
					switch ($i) :
						case 1 : _e('Create account', 'appthemes'); break;
						case 2 : _e('Enter Job Details', 'appthemes'); break;
						case 3 : _e('Preview/Job Options', 'appthemes'); break;
						case 4 : _e('Confirm', 'appthemes'); break;
					endswitch;
					echo '</span></li>';
				endfor;
				echo '</ol><div class="clear"></div>';
				
				do_action( 'appthemes_notices' );
				
				switch ($step) :
					
					case 1 :
						jr_before_step_one(); // do_action hook
						?>
						<p><?php _e('You must login or create an account in order to post a job &mdash; this will enable you to view, remove, or relist your listing in the future.', 'appthemes'); ?></p>
				
						<div class="col-1">
							<?php jr_register_form( get_permalink($submitID), 'job_lister' ); ?>
						</div>
						<div class="col-2">			
							<?php jr_login_form( get_permalink($submitID), get_permalink($submitID) ); ?>
						</div>
						<div class="clear"></div>
						<?php						
						jr_after_step_one(); // do_action hook						
						break;
					case 2 :	
						jr_before_step_two(); // do_action hook
						jr_submit_job_form(); 						
						jr_after_step_two(); // do_action hook	
						break;
					case 3 :	
						jr_before_step_three(); // do_action hook
						jr_preview_job_form();
						jr_after_step_three(); // do_action hook
						break;
					case 4 :
						jr_before_step_four(); // do_action hook
						jr_confirm_job_form();	
						jr_after_step_four(); // do_action hook
						break;
					
				endswitch;	
			?>

		</div><!-- end section_content -->

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('submit'); ?>

<?php get_footer(); ?>