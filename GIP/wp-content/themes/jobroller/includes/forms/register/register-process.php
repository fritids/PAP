<?php
/**
 * AppThemes Register Process
 * Processes the registration forms and returns errors/redirects to a page
 *
 *
 * @version 1.0
 * @author AppThemes
 * @copyright 2010 all rights reserved
 *
 */

function jr_process_register_form( $success_redirect = '' ) {

        // if there's no redirect posted, send them to their job dashboard
	if (!$success_redirect)
            $success_redirect = get_permalink(get_option('jr_dashboard_page_id'));

	
	if ( get_option('users_can_register') ) :
		
		global $posted, $app_abbr;
		
		$posted = array();
		$errors = new WP_Error();
		$user_pass = wp_generate_password();
		
		if (isset($_POST['register']) && $_POST['register']) {

                        // include the WP registration core
			require_once( ABSPATH . WPINC . '/registration.php');

                        // process the reCaptcha request if it's been enabled
			if (get_option($app_abbr.'_captcha_enable') == 'yes') {
                            require_once (TEMPLATEPATH . '/includes/lib/recaptchalib.php');
                            $resp = null;
                            $error = null;

                            // check and make sure the reCaptcha values match
                            $resp = recaptcha_check_answer(
                                get_option($app_abbr.'_captcha_private_key'),
                                $_SERVER['REMOTE_ADDR'],
                                $_POST['recaptcha_challenge_field'],
                                $_POST['recaptcha_response_field']
                            );
			}
		
			// Get (and clean) data
			$fields = array(
				'user_login',
				'user_email',
				'your_password',
				'your_password_2',
				'role'
			);
			foreach ($fields as $field) {
				if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
			}
		
			$user_login = sanitize_user( $posted['user_login'] );
			$user_email = apply_filters( 'user_registration_email', $posted['user_email'] );
			$user_role = 'job_lister';
			
			// Check terms acceptance
			if (get_option('jr_terms_page_id')>0) :
				if (!isset($_POST['terms'])) $errors->add('empty_terms', __('<strong>Notice</strong>: You must accept our terms and conditions in order to register.', 'appthemes'));
			endif;
			
			// Check Role
			if (get_option('jr_allow_job_seekers')=='yes') :
				if (!isset($_POST['role'])) $errors->add('empty_role', __('<strong>Notice</strong>: Please select a role.', 'appthemes'));
				
				if (isset($_POST['role'])) :
					if ($posted['role']!='job_lister' && $posted['role']!='job_seeker')
						$errors->add('empty_role', __('<strong>Notice</strong>: Invalid Role!', 'appthemes'));
					else $user_role = $posted['role'];
				endif;
			else :
			
			endif;
			
			// Check the username
			if ( $posted['user_login'] == '' )
				$errors->add('empty_username', __('<strong>ERROR</strong>: Please enter a username.', 'appthemes'));
			elseif ( !validate_username( $posted['user_login'] ) ) {
				$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.', 'appthemes'));
				$posted['user_login'] = '';
			} elseif ( username_exists( $posted['user_login'] ) )
				$errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered, please choose another one.', 'appthemes'));
		
			// Check the e-mail address
			if ($posted['user_email'] == '') {
				$errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.', 'appthemes'));
			} elseif ( !is_email( $posted['user_email'] ) ) {
				$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'appthemes'));
				$posted['user_email'] = '';
			} elseif ( email_exists( $posted['user_email'] ) )
				$errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.', 'appthemes'));
			
			if (get_option('jr_allow_registration_password')=='yes') :
				// Check Passwords match
				if ($posted['your_password'] == '')	
					$errors->add('empty_password', __('<strong>ERROR</strong>: Please enter a password.', 'appthemes'));
				elseif ($posted['your_password_2'] == '')
					$errors->add('empty_password', __('<strong>ERROR</strong>: Please enter password twice.', 'appthemes'));
				elseif ($posted['your_password'] !== $posted['your_password_2'])
					$errors->add('wrong_password', __('<strong>ERROR</strong>: Passwords do not match.', 'appthemes'));
				
				$user_pass = $posted['your_password'];
			endif;
			
            // display the reCaptcha error msg if it's been enabled
			if (get_option($app_abbr.'_captcha_enable') == 'yes') {
                            // Check reCaptcha  match
                            if (!$resp->is_valid)
                                $errors->add('invalid_captcha', __('<strong>ERROR</strong>: The reCaptcha anti-spam response was incorrect.', 'appthemes'));
                                //$error = $resp->error;
			}
			
			do_action('register_post', $posted['user_login'], $posted['user_email'], $errors);
			$errors = apply_filters( 'registration_errors', $errors, $posted['user_login'], $posted['user_email'] );
		
                        // if there are no errors, let's create the user account
			if ( !$errors->get_error_code() ) {

                           
                            $user_id = wp_create_user(  $posted['user_login'], $user_pass, $posted['user_email'] );
                            if ( !$user_id ) {
                                    $errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'appthemes'), get_option('admin_email')));
                                    return array( 'errors' => $errors, 'posted' => $posted);
                            }

                            // Change role
                            wp_update_user( array ('ID' => $user_id, 'role' => $user_role) ) ;

                            // send the user a confirmation and their login details
                            app_new_user_notification($user_id, $user_pass);

							if (get_option('jr_allow_registration_password')=='yes') :
							
								// set the WP login cookie
								$secure_cookie = is_ssl() ? true : false;
								wp_set_auth_cookie($user_id, true, $secure_cookie);

								// redirect
								wp_redirect($success_redirect);
								exit;
							
							else :
							
								//create own password option is turned off so show a message that it's been emailed instead
								$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : '?checkemail=newpass';
								wp_safe_redirect( $redirect_to );
								exit();
							
							endif;

			} else {

                            // there were errors so go back and display them without creating new user
                            return array( 'errors' => $errors, 'posted' => $posted);

			}
		}
		
	endif;

}