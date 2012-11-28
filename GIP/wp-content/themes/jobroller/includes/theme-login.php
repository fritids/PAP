<?php
/**
 *
 * This controls how the login, logout,
 * registration, and forgot your password pages look.
 * It overrides the default WP pages by intercepting the request.
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

global $pagenow;

// check to prevent php "notice: undefined index" msg
if(isset($_GET['action'])) $theaction = $_GET['action']; else $theaction ='';

// if the user is on the login page, then let the games begin
if ($pagenow == 'wp-login.php' && $theaction != 'logout' && !isset($_GET['key'])) :
	add_action('init', 'jr_login_init', 98);
	add_filter('wp_title', 'jr_title');
endif;

// main function that routes the request
function jr_login_init() {

	nocache_headers();
	
    if (isset($_REQUEST['action'])) :
        $action = $_REQUEST['action'];
    else :
        $action = 'login';
    endif;
    switch($action) :
        case 'lostpassword' :
        case 'retrievepassword' :
            jr_show_password();
        break;
        case 'register':
        case 'login':
        default:
            jr_show_login();
        break;
    endswitch;
    exit;
}

// display the meta page title based on the current page
function jr_title($title) {
    global $pagenow;
	
    if ($pagenow == "wp-login.php") :
    	if (isset($_GET['action'])) $action = $_GET['action']; else $action='';
        switch($action) :
            case 'lostpassword':
                $title = __('Retrieve your lost password for ','appthemes');
            break;
            case 'login':
            case 'register':
            default:
                $title = __('Login/Register at ','appthemes');
            break;
        endswitch;

    elseif ($pagenow == "profile.php") :
        $title = __('Your Profile at ','appthemes');
    endif;
    return $title . get_bloginfo('name');
}

// Show login and registation forms
function jr_show_login() {

	global $posted;
	
	if (isset($_POST['register']) && $_POST['register']) {
		
		$result = jr_process_register_form();
		
		$errors = $result['errors'];
		$posted = $result['posted'];
		
	} elseif (isset($_POST['login']) && $_POST['login']) {

		$errors = jr_process_login_form();
	}

	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) ) $errors = new WP_Error();

	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
			$errors->add('test_cookie', __('Cookies are blocked or not supported by your browser. You must enable cookies to continue.','appthemes'));
	
	if ( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
			$message = __('You are now logged out.','appthemes');

	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )	
			$errors->add('registerdisabled', __('User registration is currently not allowed.','appthemes'));

	elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )	
			$message = __('Check your email for the confirmation link.','appthemes');

	elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )	
			$message = __('Check your email for your new password.','appthemes');

	elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
			$message = __('Registration complete. Please check your e-mail.','appthemes');

	get_template_part('header');
	?>
	<div class="section">

    	<div class="section_content">

			<h1><?php _e('Login/Register', 'appthemes'); ?></h1>
			
			<?php 
				if (isset($message) && !empty($message)) {
					echo '<p class="success">'.$message.'</p>';
				}
			?>
			<?php 
			if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
				echo '<ul class="errors">';
				foreach ($errors->errors as $error) {
					echo '<li>'.$error[0].'</li>';
				}
				echo '</ul>';
			endif; 
			?>
			
			<?php if (get_option('jr_allow_job_seekers')=='yes') : ?>
		    	
		    	<p><?php _e('You must login or create an account in order to post a job or submit your resume.', 'appthemes'); ?></p>
		    	
		    	<ul>
		    	<li><?php _e('As a <strong>Job Seeker</strong> you\'ll be able to submit your profile, post your resume, and be found by employers.', 'appthemes'); ?></li>
		    	
		    	<li><?php _e('As an <strong>employer</strong> you will be able to submit, relist, view and remove your job listings.', 'appthemes'); ?></li>
		    	</ul>
		    	
			<?php else : ?>
				<p><?php _e('You must login or create an account in order to post a job &ndash; this will enable you to view, remove, or relist your listing in the future.', 'appthemes'); ?></p>
			<?php endif; ?>
			
		    <div class="col-1">
				
		        <?php jr_register_form( '', '' ); ?>

		    </div>

		    <div class="col-2">

		        <?php jr_login_form(); ?>

		    </div>

			<div class="clear"></div>

    	</div><!-- end section_content -->
	
		<div class="clear"></div>
	
	</div><!-- end section -->

        <div class="clear"></div>

</div><!-- end main content -->
			

	<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('page'); ?>

			


<?php 

	get_template_part('footer');

}



// show the forgot your password page
function jr_show_password() {
    $errors = new WP_Error();

    if ( isset($_POST['user_login']) && $_POST['user_login'] ) {
        $errors = retrieve_password();

        if ( !is_wp_error($errors) ) {
            wp_redirect('wp-login.php?checkemail=confirm');
            exit();
        }

    }

    if ( isset($_GET['error']) && 'invalidkey' == $_GET['error'] ) $errors->add('invalidkey', __('Sorry, that key does not appear to be valid.','appthemes'));

    do_action('lost_password');
    do_action('lostpassword_post');

    get_template_part('header');
	?>
	<div class="section">

    	<div class="section_content">
			
			<h1><?php _e('Password Recovery', 'appthemes'); ?></h1>
	   		
	   		<?php 
				if (isset($message) && !empty($message)) {
					echo '<p class="success">'.$message.'</p>';
				}
			?>
	   		<?php 
			if ($errors && sizeof($errors)>0 && $errors->get_error_code()) :
				echo '<ul class="errors">';
				foreach ($errors->errors as $error) {
					echo '<li>'.$error[0].'</li>';
				}
				echo '</ul>';
			endif; 
			?>
	
	        <?php jr_forgot_password_form(); ?>

			<div class="clear"></div>
			
    	</div><!-- end section_content -->
	
		<div class="clear"></div>
	
	</div><!-- end section -->

        <div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('page'); ?>


<?php 
	
	get_template_part('footer');
}

?>