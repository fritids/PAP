<?php
/*
Template Name: User Profile
*/

nocache_headers();

appthemes_auth_redirect_login();

$userdata = wp_get_current_user(); // grabs the user info and puts into vars

// check to see if the form has been posted. If so, validate the fields
if(!empty($_POST['submit'])) {

    require_once(ABSPATH . 'wp-admin/includes/user.php');
    require_once(ABSPATH . WPINC . '/registration.php');

    check_admin_referer('update-profile_' . get_current_user_id());

    $errors = edit_user($user_ID);

	$errmsg = '';	
    if ( is_wp_error( $errors ) ) foreach( $errors->get_error_messages() as $message ) $errmsg = $message;

    // if there are no errors, then process the ad updates
    if($errmsg == '') {
        // update the user fields
        do_action('personal_options_update', $user_ID);

        // update the custom user fields
		foreach ( array( 'twitter_id', 'facebook_id', 'linkedin_profile' ) as $field )
			update_user_meta($user_ID, $field, strip_tags(stripslashes($_POST[$field])));

        $d_url = $_POST['dashboard_url'];
        wp_redirect( './?updated=true&d='. $d_url );

    } else {
        $errmsg = '<ul class="errors"><li>' . $errmsg . '</li></ul>';
    }
}	

get_header(); 

?>
	<div class="section">
		
		<div class="section_content">

			<h1><?php printf( __('%s\'s Profile', 'appthemes'), ucwords( $userdata->user_login )); ?></h1>

			<?php 
			if ( isset($_GET['updated']) ) :
				$d_url = $_GET['d'];
				echo '<p class="success">'.__('Your profile has been updated.','appthemes').'</p>';
			endif;
			
			if (isset($errmsg)) echo $errmsg;
			?>

				<form name="profile" id="your-profile" action="" method="post" class="main_form" autocomplete="off">
					
					<div>
						<?php wp_nonce_field('update-profile_' . $user_ID) ?>
						<input type="hidden" name="from" value="profile" />
						<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
						<input type="hidden" id="user_login" name="user_login" value="<?php echo $userdata->user_login ?>" />
					</div>
					
					<fieldset>
						<legend><?php _e('Your Details', 'appthemes'); ?></legend>
			
						<p><label for="first_name"><?php _e('First Name','appthemes') ?></label> <input type="text" name="first_name" class="text regular-text" id="first_name" value="<?php echo $userdata->first_name ?>" maxlength="100" /></p>
						
						<p><label for="last_name"><?php _e('Last Name','appthemes') ?></label> <input type="text" name="last_name" class="text regular-text" id="last_name" value="<?php echo $userdata->last_name ?>" maxlength="100" /></p>
						
						<p><label for="nickname"><?php _e('Nickname/Company Name','appthemes') ?></label> <input type="text" name="nickname" class="text regular-text" id="nickname" value="<?php echo $userdata->nickname ?>" maxlength="100" /></p>
						
						<p><label for="display_name"><?php _e('Display Name','appthemes') ?></label> <select name="display_name" class="select" id="display_name">
						<?php
								$public_display = array();
								$public_display['display_displayname'] = $userdata->display_name;
								$public_display['display_nickname'] = $userdata->nickname;
								$public_display['display_username'] = $userdata->user_login;
								$public_display['display_firstname'] = $userdata->first_name;
								$public_display['display_firstlast'] = $userdata->first_name.' '.$userdata->last_name;
								$public_display['display_lastfirst'] = $userdata->last_name.' '.$userdata->first_name;
								$public_display = array_unique(array_filter(array_map('trim', $public_display)));
								foreach($public_display as $id => $item) {
						?>
								<option id="<?php echo $id; ?>" value="<?php echo $item; ?>"><?php echo $item; ?></option>
						<?php
								}
						?>
						</select></p>
						
						<p><label for="email"><?php _e('Email','appthemes') ?></label> <input type="text" name="email" class="text regular-text" id="email" value="<?php echo $userdata->user_email ?>" maxlength="100" /></p>
					
					</fieldset>
					
					<fieldset>
						<legend><?php _e('Websites &amp; Social media', 'appthemes'); ?></legend>
					
						<p><label for="url"><?php _e('Website','appthemes') ?></label> <input type="text" name="url" class="text regular-text" id="url" value="<?php echo $userdata->user_url ?>" maxlength="100" /></p>
						
						<p><label for="twitter_id"><?php _e('Twitter ID','appthemes') ?></label> <input type="text" name="twitter_id" class="text regular-text" id="twitter_id" value="<?php echo get_user_meta($user_ID, 'twitter_id', true); ?>" maxlength="100" /></p>
						
						<p><label for="facebook_id"><?php _e('Facebook ID','appthemes') ?></label> <input type="text" name="facebook_id" class="text regular-text" id="facebook_id" value="<?php echo get_user_meta($user_ID, 'facebook_id', true); ?>" maxlength="100" /></p>
						
						<p><label for="linkedin_profile"><?php _e('LinkedIn profile URL','appthemes') ?></label> <input type="text" name="linkedin_profile" class="text regular-text" id="linkedin_profile" value="<?php echo get_user_meta($user_ID, 'linkedin_profile', true); ?>" maxlength="100" /></p>

					</fieldset>
					
					<fieldset>
						<legend><?php _e('Profile', 'appthemes'); ?></legend>
						
						<p><?php _e('Enter a description below; this information will appear on your profile.', 'appthemes'); ?></p>
					
						<p><label for="description"><?php _e('Profile content','appthemes'); ?></label> <textarea name="description" class="text regular-text" id="description" rows="10" cols="50"><?php echo $userdata->description ?></textarea></p>
						
					</fieldset>
					
					<?php
					$show_password_fields = apply_filters('show_password_fields', true);
					if ( $show_password_fields ) :
					?>
					<fieldset>
						<legend><?php _e('Change password', 'appthemes'); ?></legend>
						<p><?php _e('Leave this field blank unless you would like to change your password.','appthemes'); ?> <?php _e('Your password should be at least seven characters long.','appthemes'); ?></p>
					
						<p><label for="pass1"><?php _e('New Password','appthemes'); ?></label> <input type="password" name="pass1" class="text regular-text" id="pass1" maxlength="50" value="" /></p>
						
						<p><label for="pass1"><?php _e('Password Again','appthemes'); ?></label> <input type="password" name="pass2" class="text regular-text" id="pass2" maxlength="50" value="" /></p>
						
						<div id="pass-strength-result"><?php _e('Strength indicator','appthemes'); ?></div>
						
					</fieldset>
					<?php endif; ?>
					
					<?php
						do_action('profile_personal_options', $userdata);
						do_action('show_user_profile', $userdata);
					?>
					<p>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_ID; ?>" />
						<input type="submit" class="submit" name="submit" value="<?php _e('Update Profile &raquo;', 'appthemes')?>" />
					</p>
				</form>

		</div><!-- end section_content -->

	</div><!-- end section -->
	
	<script type='text/javascript' src='<?php echo get_bloginfo('wpurl'); ?>/wp-admin/js/password-strength-meter.js?ver=20081210'></script>
	<script type="text/javascript">
	// <![CDATA[
		(function($){
		
			 $(document).ready( function() {
	
				function check_pass_strength () {
		
					var pass = $('#pass1').val();
					var pass2 = $('#pass2').val();
					var user = $('#user_login').val();
		
					$('#pass-strength-result').removeClass('short bad good strong');
					if ( ! pass ) {
						$('#pass-strength-result').html( pwsL10n.empty );
						return;
					}

					var strength = passwordStrength(pass, user, pass2);
		
					if ( 2 == strength )
						$('#pass-strength-result').addClass('bad').html( pwsL10n.bad );
					else if ( 3 == strength )
						$('#pass-strength-result').addClass('good').html( pwsL10n.good );
					else if ( 4 == strength )
						$('#pass-strength-result').addClass('strong').html( pwsL10n.strong );
					else if ( 5 == strength )
						 $('#pass-strength-result').addClass('short').html( pwsL10n.mismatch );
					else
						$('#pass-strength-result').addClass('short').html( pwsL10n.short );
		
				}
	
				$('#pass1, #pass2').val('').keyup( check_pass_strength );
			});
		})(jQuery);

		pwsL10n = {
			empty: "<?php _e('Strength indicator','appthemes') ?>",
			short: "<?php _e('Very weak','appthemes') ?>",
			bad: "<?php _e('Weak','appthemes') ?>",
			good: "<?php _e('Medium','appthemes') ?>",
			strong: "<?php _e('Strong','appthemes') ?>",
			mismatch: "<?php _e('Mismatch','appthemes') ?>"
		}
		try{convertEntities(pwsL10n);}catch(e){};
	// ]]>
	</script>

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('user'); ?>

<?php get_footer(); ?>
