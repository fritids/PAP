<?php
/**
 * JobRoller Registration Form
 * Function outputs the registration form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_register_form( $action = '', $role = 'job_lister' ) {
	
    global $posted;

    if ( get_option('users_can_register') ) :

        if (!$action) $action = site_url('wp-login.php?action=register');
    ?>

            <h2><?php _e('Create a free account', 'appthemes'); ?></h2>

            <form action="<?php echo $action; ?>" method="post" class="account_form">
				
				<?php 
					if (get_option('jr_allow_job_seekers')=='yes') :
						if (!$role) :
							?>
							<p class="role">
								<label><input type="radio" name="role" value="job_lister" <?php if (isset($posted['role']) && $posted['role']=='job_lister') echo 'checked="checked"'; ?> /> <?php _e('I am an <strong>Employer</strong>', 'appthemes'); ?></label>
								<label class="alt"><input type="radio" name="role" value="job_seeker" <?php if (isset($posted['role']) && $posted['role']=='job_seeker') echo 'checked="checked"'; ?> /> <?php _e('I am a <strong>Job Seeker</strong>', 'appthemes'); ?></label>
							</p>
							<?php
						elseif ($role=='job_lister') :
							echo '<div><input type="hidden" name="role" value="job_lister" /></div>';
						elseif ($role=='job_seeker') :
							echo '<div><input type="hidden" name="role" value="job_seeker" /></div>';
						endif;
					endif;
				?>
				
				<div class="account_form_fields">
				
	                <p>
	                    <label for="user_login"><?php _e('Username', 'appthemes'); ?></label><br/>
	                    <input type="text" class="text" tabindex="1" name="user_login" id="user_login" value="<?php if (isset($posted['user_login'])) echo $posted['user_login']; ?>" />
	                </p>
	
	                <p>
	                    <label for="user_email"><?php _e('Email', 'appthemes'); ?></label><br/>
	                    <input type="text" class="text" tabindex="2" name="user_email" id="user_email" value="<?php if (isset($posted['user_email'])) echo $posted['user_email']; ?>" />
	                </p>
					
					<?php if (get_option('jr_allow_registration_password')=='yes') : ?>
	                <p>
	                    <label for="your_password"><?php _e('Enter a password', 'appthemes'); ?></label><br/>
	                    <input type="password" class="text" tabindex="3" name="your_password" id="your_password" value="" />
	                </p>
	
	                <p>
	                    <label for="your_password_2"><?php _e('Enter password again', 'appthemes'); ?></label><br/>
	                    <input type="password" class="text" tabindex="4" name="your_password_2" id="your_password_2" value="" />
	                </p>
	                <?php endif; ?>       
	                
	                <?php
	                // include the spam checker if enabled
	                appthemes_recaptcha();
	                ?>
	                
					<?php if (get_option('jr_terms_page_id')>0) : ?><p>
						<input type="checkbox" name="terms" tabindex="6" value="yes" id="terms" <?php if (isset($_POST['terms'])) echo 'checked="checked"'; ?> /> <label for="terms"><?php _e('I accept the ', 'appthemes'); ?><a href="<?php echo get_permalink(get_option('jr_terms_page_id')); ?>" target="_blank"><?php _e('terms &amp; conditions', 'appthemes'); ?></a>.</label>
					</p><?php endif; ?>
					
					<?php do_action('register_form'); ?>
	                
					<p>
	                    <input type="submit" class="submit" tabindex="7" name="register" value="<?php _e('Create Account &rarr;', 'appthemes'); ?>" />
	                </p>

                </div>

            </form>
<?php endif; ?>

<?php } ?>