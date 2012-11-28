<?php
/**
 * JobRoller Forgot Password Form
 * Function outputs the forgotten password form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_forgot_password_form() {
	?>
    <p><?php _e('Please enter your username or email address. A new password will be emailed to you.', 'appthemes') ?></p>
    <form action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post" class="main_form">

        <p><label for="login_username"><?php _e('Username/Email', 'appthemes'); ?></label><input type="text" class="text" name="user_login" id="login_username" /></p>

        <p><?php do_action('lostpassword_form'); ?><input type="submit" class="submit" name="login" value="<?php _e('Get New Password','appthemes'); ?>" /></p>

    </form>
	<?php
}