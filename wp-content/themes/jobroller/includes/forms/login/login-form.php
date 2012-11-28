<?php
/**
 * JobRoller Login Form
 * Function outputs the login form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_login_form( $action = '', $redirect = '' ) {

	global $posted;
	
	if (!$action) $action = site_url('wp-login.php');
	if (!$redirect) $redirect = get_permalink(get_option('jr_dashboard_page_id'));
	?>

	<h2><?php _e('Already have an account?', 'appthemes'); ?></h2>

	<form action="<?php echo $action; ?>" method="post" class="account_form">
		
            <p>
                <label for="login_username"><?php _e('Username', 'appthemes'); ?></label><br/>
                <input type="text" class="text" name="log" id="login_username" value="<?php if (isset($posted['login_username'])) echo $posted['login_username']; ?>" />
            </p>

            <p>
                <label for="login_password"><?php _e('Password', 'appthemes'); ?></label><br/>
                <input type="password" class="text" name="pwd" id="login_password" value="" />
            </p>

            <p>
                <input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>" />
                <input type="hidden" name="rememberme" value="forever" />
                <input type="submit" class="submit" name="login" value="<?php _e('Login &rarr;', 'appthemes'); ?>" />
                <a class="lostpass" href="<?php echo site_url('wp-login.php?action=lostpassword', 'login') ?>" title="<?php _e('Password Lost and Found', 'appthemes'); ?>"><?php _e('Lost your password?', 'appthemes'); ?></a>
            </p>

	</form>

<?php
}
?>