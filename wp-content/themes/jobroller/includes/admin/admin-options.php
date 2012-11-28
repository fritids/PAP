<?php

// include all the core admin files
require_once ('admin-values.php');
require_once ('admin-orders.php');
require_once ('admin-subscriptions.php');
require_once ('admin-jobpacks.php');
require_once ('admin-alerts-subscribers.php');


################################################################################
// Set up menus within the wordpress admin sections
################################################################################

function appthemes_admin_menu() {
    add_menu_page(__('JobRoller'), __('JobRoller'), 'manage_options', basename(__FILE__) , 'jr_dashboard', FAVICON, THE_POSITION);
    add_submenu_page(basename(__FILE__), __('Dashboard','appthemes'), __('Dashboard','appthemes'), 'manage_options', basename(__FILE__), 'jr_dashboard');
    add_submenu_page(basename(__FILE__), __('General Settings', 'appthemes'),  __('Settings', 'appthemes') , 'manage_options', 'settings', 'jr_settings');
	add_submenu_page(basename(__FILE__), __('Emails','appthemes'), __('Emails','appthemes'), 'manage_options', 'emails', 'jr_emails');
	add_submenu_page(basename(__FILE__), __('Alerts','appthemes'), __('Alerts','appthemes'), 'manage_options', 'alerts', 'jr_alerts');

    add_submenu_page(basename(__FILE__), __('Pricing','appthemes'), __('Pricing','appthemes'), 'manage_options', 'pricing', 'jr_pricing');
    add_submenu_page(basename(__FILE__), __('Job Packs','appthemes'), __('Job Packs','appthemes'), 'manage_options', 'jobpacks', 'jr_job_packs_admin');
    add_submenu_page(basename(__FILE__), __('Integration','appthemes'), __('Integration','appthemes'), 'manage_options', 'integration', 'jr_integration');
	add_submenu_page(basename(__FILE__), __('Orders','appthemes'), __('Orders','appthemes'), 'manage_options', 'orders', 'jr_orders');
	add_submenu_page(basename(__FILE__), __('Subscriptions','appthemes'), __('Subscriptions','appthemes'), 'manage_options', 'subscriptions', 'jr_subscriptions');
	add_submenu_page(basename(__FILE__), __('Alerts Subscribers','appthemes'), __('Alerts Subscribers','appthemes'), 'manage_options', 'alerts_subscribers', 'jr_alerts_subscribers');	
	
    add_submenu_page(basename(__FILE__), __('System Info','appthemes'), __('System Info','appthemes'), 'manage_options', 'sysinfo', 'jr_system_info');

	do_action( 'appthemes_add_submenu_page' );
}

add_action('admin_menu', 'appthemes_admin_menu');






// update all the admin options on save
function appthemes_update_options($options) {

    if(isset($_POST['submitted']) && $_POST['submitted'] == 'yes') {

        foreach ($options as $value) {

            if(isset($value['id']) && isset($_POST[$value['id']])) {
                // echo $value['id'] . '<-- value ID | ' . $_POST[$value['id']] . '<-- $_POST value ID <br/><br/>'; // FOR DEBUGGING
                update_option($value['id'], appthemes_clean($_POST[$value['id']]));
            } else {
                @delete_option($value['id']);
            }
        }

        echo '<div id="message" class="updated fade"><p><strong>'.__('Your settings have been saved.','appthemes').'</strong></p></div>';

    }

}


// generates admin fields based on array params passed in
function appthemes_admin_fields($options) {
    global $shortname;
?>

<script type="text/javascript">
jQuery(function() {
    jQuery("#tabs-wrap").tabs({
        fx: {
            opacity: 'toggle',
            duration: 200
        }
    });
});
</script>

<div id="tabs-wrap">


<?php

    // first generate the page tabs
    $counter = 1;

    echo '<ul>'. "\n";
    foreach ($options as $value) {

        if (in_array('tab', $value)) :
            echo '<li><a href="#'.$value['type'].$counter.'">'.$value['tabname'].'</a></li>'. "\n";
            $counter = $counter + 1;
        endif;

    }
    echo '</ul>'. "\n\n";


     // now loop through all the options
    $counter = 1;
    foreach ($options as $value) {

        switch($value['type']) {

            case 'tab':

                echo '<div id="'.esc_attr($value['type'].$counter).'">'. "\n\n";
                echo '<table class="widefat fixed" style="width:850px; margin-bottom:20px;">'. "\n\n";

            break;
            
            case 'title':
            ?>

                <thead><tr><th scope="col" width="200px"><?php echo $value['name'] ?></th><th scope="col"><?php if (isset($value['desc'])) echo $value['desc'] ?>&nbsp;</th></tr></thead>

            <?php
            break;

            case 'text':
            ?>

                <tr <?php if ($value['vis'] == '0') { ?>id="<?php if ($value['visid']) { echo esc_attr($value['visid']); } else { echo 'drop-down'; } ?>" style="display:none;"<?php } ?>>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo esc_attr($value['tip']); ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']) ?>" type="<?php echo esc_attr($value['type']); ?>" style="<?php echo @$value['css'] ?>" value="<?php if (get_option( $value['id'])) echo esc_attr(get_option( $value['id'] )); else echo esc_attr($value['std']) ?>"<?php if (!empty($value['req'])) { ?> class="required" <?php } ?> <?php if ($value['min']) { ?> minlength="<?php echo esc_attr($value['min']); ?>"<?php } ?> /><?php echo isset($value['extra']) && $value['extra'] ? $value['extra'] : ''; ?><br /><small><?php echo $value['desc'] ?></small></td>
                </tr>


            <?php
            break;

            case 'select':
            ?>

                <tr>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo esc_attr($value['tip']); ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><select <?php if (isset($value['js']) && $value['js']) echo $value['js']; ?> name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" style="<?php echo esc_attr($value['css']); ?>"<?php if (!empty($value['req'])) { ?> class="required"<?php } ?>>

                        <?php
                        foreach ($value['options'] as $key => $val) {
                        ?>

                            <option value="<?php echo esc_attr($key); ?>" <?php selected(get_option($value['id']),$key); ?>><?php echo ucfirst($val) ?></option>

                        <?php
                        }
                        ?>

                       </select><?php isset($value['extra']) && $value['extra'] ? $value['extra'] : ''; ?><br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'checkbox':
            ?>

                <tr>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo esc_attr($value['tip']); ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp"><input type="checkbox" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" value="true" style="<?php echo esc_attr($value['css']);?>" <?php if(get_option($value['id'])) { ?>checked="checked"<?php } ?> />
                        <?php isset($value['extra']) && $value['extra'] ? $value['extra'] : ''; ?><br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

            case 'textarea':
            ?>
                <tr>
                    <td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo esc_attr($value['tip']); ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
                    <td class="forminp">
                        <textarea name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" style="<?php echo esc_attr($value['css']); ?>" <?php if (!empty($value['req'])) { ?> class="required" <?php } ?><?php if ($value['min']) { ?> minlength="<?php echo esc_attr($value['min']); ?>"<?php } ?>><?php if (get_option($value['id'])) echo stripslashes(get_option($value['id'])); else echo $value['std']; ?></textarea>
                        <?php isset($value['extra']) && $value['extra'] ? $value['extra'] : ''; ?><br /><small><?php echo $value['desc'] ?></small>
                    </td>
                </tr>

            <?php
            break;

			case 'upload':
			?>

				<tr>
					<td class="titledesc"><?php if ($value['tip']) { ?><a href="#" tip="<?php echo esc_attr($value['tip']); ?>" tabindex="99"><div class="helpico"></div></a><?php } ?><?php echo $value['name'] ?>:</td>
					<td class="forminp">
						<input id="<?php echo esc_attr($value['id']); ?>" class="upload_image_url" type="text" style="<?php echo esc_attr($value['css']); ?>" name="<?php echo esc_attr($value['id']); ?>" value="<?php if (get_option( $value['id'])) echo esc_attr(get_option( $value['id'] )); else echo esc_attr($value['std']) ?>" />
						<input id="upload_image_button" class="upload_button button" rel="<?php echo esc_attr($value['id']); ?>" type="button" value="<?php esc_attr_e('Upload Image', 'appthemes') ?>" />
						<br /><small><?php echo $value['desc'] ?></small>
						<div id="<?php echo esc_attr($value['id']); ?>_image" class="<?php echo esc_attr($value['id']); ?>_image upload_image_preview"><?php if (get_option( $value['id'])) echo '<img src="' .esc_attr(get_option( $value['id'] )) . '" />'; ?></div>
					</td>
                </tr>

			<?php
			break;

            case 'logo':
            ?>
                <tr>
                    <td class="titledesc"><?php echo $value['name'] ?></td>
                    <td class="forminp">&nbsp;</td>
                </tr>

            <?php
            break;

            case 'tabend':

                echo '</table>'. "\n\n";
                echo '</div> <!-- #tab'.$counter.' -->'. "\n\n";
                $counter = $counter + 1;

            break;
			
			case 'html':
				
				echo $value['html'];
			
			break;

        } // end switch

    } // end foreach
?>

</div> <!-- #tabs-wrap -->

<?php
}


do_action( 'appthemes_add_submenu_page_content' );


function jr_dashboard() {
	global $wpdb, $app_rss_feed, $app_twitter_rss_feed, $app_forum_rss_feed, $options_dashboard, $app_version;

    $date_today = date('Y-m-d');
    $date_yesterday = date('Y-m-d', strtotime('-1 days'));

	$job_count_live = $wpdb->get_var($wpdb->prepare("SELECT count(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = '".APP_POST_TYPE."'"));
	$resume_count_live = $wpdb->get_var($wpdb->prepare("SELECT count(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = '".APP_POST_TYPE_RESUME."'"));
	$job_count_pending = $wpdb->get_var($wpdb->prepare("SELECT count(ID) FROM $wpdb->posts WHERE post_status = 'pending' AND post_type = '".APP_POST_TYPE."'"));
	$job_rev_total = $wpdb->get_var($wpdb->prepare("SELECT sum(cost) FROM $wpdb->jr_orders WHERE status = 'completed'"));
	$job_seekers_today = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND ($wpdb->usermeta.meta_value LIKE '%job_seeker%') AND $wpdb->users.user_registered >= '$date_today'");
	$job_listers_today = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND ($wpdb->usermeta.meta_value LIKE '%job_lister%') AND $wpdb->users.user_registered >= '$date_today'");
	$job_seekers_yesterday = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND ($wpdb->usermeta.meta_value LIKE '%job_seeker%') AND $wpdb->users.user_registered BETWEEN '$date_yesterday' AND '$date_today'");
	$job_listers_yesterday = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND ($wpdb->usermeta.meta_value LIKE '%job_lister%') AND $wpdb->users.user_registered BETWEEN '$date_yesterday' AND '$date_today'");
	$total_users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
   ?>

        <div class="wrap jobroller">
        <div class="icon32" id="icon-themes"><br/></div>
        <h2><?php _e('JobRoller Dashboard', 'appthemes') ?></h2>

        <div class="dash-left metabox-holder">

		<div class="postbox">
			<div class="statsico"></div>
			<h3 class="hndle"><span><?php _e('JobRoller Info', 'appthemes') ?></span></h3>

			<div class="preloader-container">
				<div class="insider" id="boxy">

				<div class="stats-info">
					<ul>
						<li><?php _e('Total Live Jobs', 'appthemes')?>: <a href="edit.php?post_status=publish&post_type=job_listing"><strong><?php echo number_format_i18n($job_count_live); ?></strong></a></li>
						<li><?php _e('Total Pending Jobs', 'appthemes')?>: <a href="edit.php?post_status=pending&post_type=job_listing"><strong><?php echo number_format_i18n($job_count_pending); ?></strong></a></li>
						<li><?php _e('Total Live Resumes', 'appthemes')?>: <a href="edit.php?post_status=publish&post_type=resume"><strong><?php echo number_format_i18n($resume_count_live); ?></strong></a></li>
						<li><?php _e('Total Users', 'appthemes')?>: <a href="users.php"><strong><?php echo number_format_i18n($total_users); ?></strong></a></li>
						<li><?php _e('Total Revenue', 'appthemes')?>: <strong><?php echo jr_get_currency($job_rev_total); ?></strong></li>
						<li><?php _e('Product Version', 'appthemes'); ?>: <strong><?php echo $app_version; ?></strong></li>
						<li><?php _e('Product Support', 'appthemes')?>:  <a href="http://forums.appthemes.com/" target="_new"><?php _e('Forum','appthemes')?></a> | <a href="http://www.appthemes.com/support/docs/" target="_new"><?php _e('Documentation','appthemes')?></a></li>
					</ul>
				</div>


				<div class="stats_overview">
					<h3><?php _e('New Registrations', 'appthemes') ?></h3>
					<div class="overview_today">
						<p class="overview_day"><?php _e('Today', 'appthemes') ?></p>
						<p class="overview_count"><?php echo number_format_i18n($job_listers_today); ?></p>
						<p class="overview_type"><em><?php _e('Job Listers', 'appthemes') ?></em></p>
						<p class="overview_count"><?php echo number_format_i18n($job_seekers_today); ?></p>
						<p class="overview_type_seek"><em><?php _e('Job Seekers', 'appthemes') ?></em></p>
					</div>

					<div class="overview_previous">
						<p class="overview_day"><?php _e('Yesterday', 'appthemes') ?></p>
						<p class="overview_count"><?php echo number_format_i18n($job_listers_yesterday); ?></p>
						<p class="overview_type"><em><?php _e('Job Listers', 'appthemes') ?></em></p>
						<p class="overview_count"><?php echo number_format_i18n($job_seekers_yesterday); ?></p>
						<p class="overview_type_seek"><em><?php _e('Job Seekers', 'appthemes') ?></em></p>
					</div>
				</div>

				<div class="clear"></div>

				</div>
			</div>

		</div> <!-- postbox end -->



		<div class="postbox">
			<div class="newspaperico"></div><a target="_new" href="<?php echo $app_rss_feed ?>"><div class="rssico"></div></a>
			<h3 class="hndle" id="poststuff"><span><?php _e('Latest News', 'appthemes') ?></span></h3>

             <div class="preloader-container">

		<div class="insider" id="boxy">

			<?php appthemes_dashboard_appthemes(); ?>

		</div> <!-- inside end -->

             </div>


		</div> <!-- postbox end -->


	</div> <!-- dash-left end -->


	<div class="dash-right metabox-holder">


		<div class="postbox">
			<div class="statsico"></div>
			<h3 class="hndle" id="poststuff"><span><?php _e('Stats - Last 30 Days', 'appthemes') ?></span></h3>

            <div class="preloader-container">
		<div class="insider" id="boxy">

			<?php jr_dashboard_charts(); ?>

		</div> <!-- inside end -->
            </div>
		</div> <!-- postbox end -->


		<div class="postbox">
			<div class="twitterico"></div><a target="_new" href="<?php echo $app_twitter_rss_feed ?>"><div class="rssico"></div></a>
			<h3 class="hndle" id="poststuff"><span><?php _e('Latest Tweets', 'appthemes') ?></span></h3>

            <div class="preloader-container">
		<div class="insider" id="boxy">

			<?php appthemes_dashboard_twitter(); ?>

		</div> <!-- inside end -->
            </div>


		</div> <!-- postbox end -->


		<div class="postbox">
			<div class="forumico"></div><a target="_new" href="<?php echo $app_forum_rss_feed ?>"><div class="rssico"></div></a>
			<h3 class="hndle" id="poststuff"><span><?php _e('Support Forum', 'appthemes') ?></span></h3>

            <div class="preloader-container">
		<div class="insider" id="boxy">

			<?php appthemes_dashboard_forum(); ?>

		</div> <!-- inside end -->
            </div>


		</div> <!-- postbox end -->


	</div> <!-- dash-right end -->
</div> <!-- /wrap -->

<?php
}


// general settings admin page
function jr_settings() {
    global $options_settings;

    appthemes_update_options($options_settings);
    ?>

	<div class="wrap jobroller">
            <div class="icon32" id="icon-tools"><br/></div>
		<h2><?php _e('General Settings','appthemes'); ?></h2>

                <form method="post" id="mainform" action="">
                    <p class="submit btop"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>

                        <?php appthemes_admin_fields($options_settings); ?>

                    <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>
                    <input name="submitted" type="hidden" value="yes" />
		</form>
	</div>
	<?php
}

// theme styles
// populates the theme dropdown with the default styles and adds any custom .css styles found on the styles path
// styles must be placed under the child folder \styles\
// the resulting styles array is filterable to allow adding custom theme styles
function jr_settings_theme_styles() {

	$styles_path = get_stylesheet_directory().'/styles/';
	$styles_pattern = $styles_path . 'style*.css';

	$styles = array (
			'style-default.css'    => __('Default Theme', 'appthemes'),
			'style-pro-blue.css'   => __('Blue Pro Theme', 'appthemes'),
			'style-pro-green.css'  => __('Green Pro Theme', 'appthemes'),
			'style-pro-orange.css' => __('Orange Pro Theme', 'appthemes'),
			'style-pro-gray.css'   => __('Gray Pro Theme', 'appthemes'),
			'style-pro-red.css'    => __('Red Pro Theme', 'appthemes'),
			'style-basic.css'      => __('Basic Plain Theme', 'appthemes')
	);

	// get all the available theme styles and append them to the defaults
	foreach (glob($styles_pattern) as $filename)
		if ( !array_key_exists (basename($filename), $styles) )
			$styles[basename($filename)] = __('Custom Theme','appthemes') . ' (' . basename($filename) . ')';

	return apply_filters('jr_theme_styles', $styles);
}

// feed settings admin page
function jr_integration() {
    global $options_integration;

    appthemes_update_options($options_integration);
    ?>

	<div class="wrap jobroller">
            <div class="icon32" id="icon-tools"><br/></div>
		<h2><?php _e('3rd Party Integration','appthemes'); ?></h2>

                <form method="post" id="mainform" action="">
                    <p class="submit btop"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>

                        <?php appthemes_admin_fields($options_integration); ?>

                    <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>
                    <input name="submitted" type="hidden" value="yes" />
		</form>
	</div>
	<?php
}


function jr_emails() {
    global $options_emails;

    appthemes_update_options($options_emails);
    ?>

    <div class="wrap jobroller">
        <div class="icon32" id="icon-tools"><br/></div>
        <h2><?php _e('Email Settings','appthemes') ?></h2>

        <form method="post" id="mainform" action="">
            <p class="submit btop"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>

            <?php appthemes_admin_fields($options_emails); ?>

            <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>
            <input name="submitted" type="hidden" value="yes" />
        </form>
    </div>

<?php

}

function jr_alerts() {
    global $options_alerts, $error_email, $user_ID;

    appthemes_update_options($options_alerts);
    
    // validate test emails 
	if ( isset($_POST['testalerts']) ):		

		$args = array(
			'post_type'				=> APP_POST_TYPE,
			'post_status' 			=> 'publish',
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> 5,
		);
		$jobs = query_posts($args);		
		
		$errors = 0;
		$result = jr_job_alerts_send_email( $user_ID, $jobs );
		if (!$result) $errors++; 
					
		if ( $errors > 0 ) $notice = 'error|' . __('There were errors sending the test email. Please check your log file for more details.','appthemes');
		else $notice = 'updated|' . __('Test email sent succesfully!','appthemes');	
		
		$notice = explode('|',$notice);
    	echo '<div class="'.$notice[0].'">
    	   			<p>'.$notice[1].'</p>
    		 </div>';
    	    	
    endif;	
	
    ?>

    <div class="wrap jobroller">
        <div class="icon32" id="icon-tools"><br/></div>
        <h2><?php _e('Alert Settings','appthemes') ?></h2>

        <form method="post" id="mainform" action="">
            <p class="submit btop"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>

            <?php appthemes_admin_fields($options_alerts); ?>

            <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>
            <input name="submitted" type="hidden" value="yes" />
        </form>

		<table class="widefat fixed" style="width:850px;">

             <thead>
                <tr>
                    <th scope="col" width="200px"><?php _e('Test Job Alerts','appthemes')?></th>
                    <th scope="col">&nbsp;</th>
                </tr>
            </thead>
					               	
           <form method="post" id="mainform" action="">
                <tr>
                    <td class="titledesc"><?php _e('Test Email','appthemes'); ?></td>
                    <td class="forminp">
                        <input class="button"  style="float: none" name="save" type="submit" value="<?php _e('Send Test Email','appthemes'); ?>"/>
						<p><?php _e('Use this button to test your job alert emails and make any necessary tweaks or template changes. 
						The last 5 jobs will be sent to your email.','appthemes')?></p>							
                        <input name="testalerts" type="hidden" value="yes" />
                    </td>
                </tr>
           </form>	        
		</table>   
		
    </div>

<?php
}



// pricing options admin page
function jr_pricing() {
    global $options_pricing;

    appthemes_update_options($options_pricing);
    ?>

    <div class="wrap jobroller">
        <div class="icon32" id="icon-options-general"><br/></div>
        <h2><?php _e('Pricing &amp; Payment Settings','appthemes') ?></h2>

        <?php // jr_admin_info_box(); ?>

        <form method="post" id="mainform" action="">
            <p class="submit btop"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>

            <?php appthemes_admin_fields($options_pricing); ?>

            <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save changes','appthemes') ?>" /></p>
            <input name="submitted" type="hidden" value="yes" />
        </form>
    </div>

<?php
}

// system information page
function jr_system_info() {
    global $system_info, $wpdb, $app_version;
?>

    <div class="wrap jobroller">
        <div class="icon32" id="icon-options-general"><br/></div>
        <h2><?php _e('System Info','appthemes') ?></h2>

        <?php // jr_admin_info_box(); ?>

        <?php
        // delete all the db tables if the button has been pressed.
        if (isset($_POST['deletetables']))
            jr_delete_db_tables();

        // delete all the config options from the wp_options table if the button has been pressed.
        if (isset($_POST['deleteoptions']))
            jr_delete_all_options();
        ?>
		<script type="text/javascript">
		jQuery(function() {
		    jQuery("#tabs-wrap").tabs({
		        fx: {
		            opacity: 'toggle',
		            duration: 200
		        }
		    });
		});
		</script>
        <div id="tabs-wrap">
            <ul>
            	<li><a href="#tab1"><?php _e('Debug Info','appthemes')?></a></li>
            	<li><a href="#tab2"><?php _e('Cron Jobs','appthemes')?></a></li>
            	<li><a href="#tab3"><?php _e('Uninstall','appthemes')?></a></li>
            </ul>
			<div id="tab1">
                <table class="widefat fixed" style="width:850px;">

                    <thead>
                        <tr>
                            <th scope="col" width="200px"><?php _e('Debug Info','appthemes')?></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="titledesc"><?php _e('JobRoller Version','appthemes')?></td>
                            <td class="forminp"><?php echo $app_version; ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('WordPress Version','appthemes')?></td>
                            <td class="forminp"><?php if (appthemes_is_wpmu()) echo 'WPMU'; else echo 'WP'; ?> <?php if(function_exists('bloginfo')) echo bloginfo('version'); ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('PHP Version','appthemes')?></td>
                            <td class="forminp"><?php if(function_exists('phpversion')) echo phpversion(); ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('Server Software','appthemes')?></td>
                            <td class="forminp"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('UPLOAD_MAX_FILESIZE','appthemes')?></td>
                            <td class="forminp"><?php
                            	if(function_exists('phpversion')) //echo ini_get('upload_max_filesize');
                            		echo (let_to_num(ini_get('upload_max_filesize'))/(1024*1024))."MB";
                            ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('POST_MAX_SIZE','appthemes')?></td>
                            <td class="forminp"><?php
                            	if(function_exists('phpversion')) //echo ini_get('upload_max_filesize');
                            		echo (let_to_num(ini_get('post_max_size'))/(1024*1024))."MB";
                            ?></td>
                        </tr>

                         <tr>
                            <td class="titledesc"><?php _e('WordPress Memory Limit','appthemes')?></td>
                            <td class="forminp"><?php
                            	echo (let_to_num(WP_MEMORY_LIMIT)/(1024*1024))."MB";
                            ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('DISPLAY_ERRORS','appthemes')?></td>
                            <td class="forminp"><?php if(function_exists('phpversion')) echo ini_get('display_errors'); ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('FSOCKOPEN Check','appthemes')?></td>
                            <td class="forminp"><?php if(function_exists('fsockopen')) echo '<span style="color:green">' . __('Your server has fsockopen enabled which is needed for PayPal IPN to work.', 'appthemes'). '</span>'; else echo '<span style="color:red">' . __('Your server does not have fsockopen enabled so PayPal IPN will not work. Contact your host provider to have it enabled.', 'appthemes'). '</span>'; ?></td>
                        </tr>

						<tr>
                            <td class="titledesc"><?php _e('OPENSSL Check','appthemes')?></td>
                            <td class="forminp"><?php if(function_exists('openssl_open')) echo '<span style="color:green">' . __('Your server has Open SSL enabled which is needed for PayPal IPN to work. Also make sure port 443 is open on the firewall.', 'appthemes'). '</span>'; else echo '<span style="color:red">' . __('Your server does not have Open SSL enabled so PayPal IPN will not work. Contact your host provider to have it enabled.', 'appthemes'). '</span>'; ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('WP Remote Post Check','appthemes')?></td>
                            <td class="forminp"><?php
								$paypal_adr = 'https://www.paypal.com/cgi-bin/webscr';
								$params = array(
									'timeout' 	=> 30
								);
								$response = wp_remote_post( $paypal_adr, $params );

								// Retry
								if ( is_wp_error($response) ) {
									$params['sslverify'] = false;
									$response = wp_remote_post( $paypal_adr, $params );
								}

                            	if ( !is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) echo '<span style="color:green">' . __('wp_remote_post() was successful so PayPal IPN should work fine for you!', 'appthemes'). '</span>'; else echo '<span style="color:red">' . __('wp_remote_post() failed. Sorry, PayPal IPN won\'t work with your server.', 'appthemes'). '</span>';
                            ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('Log File Check','appthemes')?></td>
                            <td class="forminp">
                            	<?php
                            		$logging_enabled = get_option('jr_enable_log');

									if ($logging_enabled=='yes') :
										$fp = fopen(TEMPLATEPATH . '/log/jobroller_log.txt', 'a');
										if ($fp) :
											 echo '<span style="color:green">' . __('Log file is writable.', 'appthemes'). '</span>';
										else :
											echo '<span style="color:red">' . __('Log file is not writable. Edit file permissions (jobroller/log/jobroller_log.txt)', 'appthemes'). '</span>';
										endif;
										fclose($fp);
									else :
										echo 'Logging is disabled - <a href="admin.php?page=settings">' . __('(change this)', 'appthemes') . '</a>';
									endif;
								?>
                            </td>
                        </tr>



                        <tr>
                            <td class="titledesc"><?php _e('Theme Path','appthemes')?></td>
                            <td class="forminp"><?php if(function_exists('bloginfo')) { echo bloginfo('template_url'); } ?></td>
                        </tr>

                        <tr>
                            <td class="titledesc"><?php _e('Image Upload Path','appthemes')?></td>
                            <td class="forminp"><?php if(!get_option('upload_path')) echo 'wp-content/uploads'; else echo esc_attr(get_option('upload_path')); ?><?php printf( ' - <a href="%s">' . __('(change this)', 'appthemes') . '</a>', 'options-media.php' ); ?></td>                        </tr>

                </tbody>

                </table>
            </div>
            <div id="tab2">
				<table class="widefat fixed" style="width:850px;">
					<thead>
						<tr>
							<th scope="col"><?php _e('Next Run Date','appthemes')?></th>
							<th scope="col"><?php _e('Frequency','appthemes')?></th>
							<th scope="col"><?php _e('Hook Name','appthemes')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$cron = _get_cron_array();
							$schedules = wp_get_schedules();
							$date_format = _x( 'M j, Y @ G:i','appthemes');
							foreach ( $cron as $timestamp => $cronhooks ) {
								foreach ( (array) $cronhooks as $hook => $events ) {
									foreach ( (array) $events as $key => $event ) {
										$cron[ $timestamp ][ $hook ][ $key ][ 'date' ] = date_i18n( $date_format, $timestamp );
									}
								}
							}
						?>
						<?php foreach ( $cron as $timestamp => $cronhooks ) { ?>
							<?php foreach ( (array) $cronhooks as $hook => $events ) { ?>
								<?php foreach ( (array) $events as $event ) { ?>
									<tr>
										<th scope="row"><?php echo $event[ 'date' ]; ?></th>
										<td>
											<?php
												if ( $event[ 'schedule' ] ) {
													echo $schedules [ $event[ 'schedule' ] ][ 'display' ];
												} else {
													?><em><?php _e('One-off event','appthemes')?></em><?php
												}
											?>
										</td>
										<td><?php echo $hook; ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
            </div>
            <div id="tab3">
                <table class="widefat fixed" style="width:850px;">

                     <thead>
                        <tr>
                            <th scope="col" width="200px"><?php _e('Uninstall JobRoller','appthemes')?></th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                    </thead>

                <form method="post" id="mainform" action="">
                    <tr>
                        <td class="titledesc"><?php _e('Delete Database Tables','appthemes')?></td>
                        <td class="forminp">
                            <p class="submit"><input onclick="return confirmBeforeDeleteTbls();" name="save" type="submit" value="<?php _e('Delete JobRoller Database Tables','appthemes') ?>" /><br />
                        <?php _e('Do you wish to completely delete all JobRoller database tables? Once you do this you will lose any transaction data you have stored.','appthemes')?>
                            </p>
                            <input name="deletetables" type="hidden" value="yes" />
                        </td>
                    </tr>
                </form>

                <form method="post" id="mainform" action="">
                    <tr>
                        <td class="titledesc"><?php _e('Delete Config Options','appthemes')?></td>
                        <td class="forminp">
                            <p class="submit"><input onclick="return confirmBeforeDeleteOptions();" name="save" type="submit" value="<?php _e('Delete JobRoller Config Options','appthemes') ?>" /><br />
                        <?php _e('Do you wish to completely delete all JobRoller configuration options? This will delete all values saved on the settings, pricing, gateways, etc admin pages from the wp_options database table.','appthemes')?>
                            </p>
                            <input name="deleteoptions" type="hidden" value="yes" />
                        </td>
                    </tr>
                </form>

                </table>
            </div>
		</div>

    </div>

    <script type="text/javascript">
    /* <![CDATA[ */
        function confirmBeforeDeleteTbls() { return confirm("<?php _e('WARNING: You are about to completely delete all JobRoller database tables. Are you sure you want to proceed? (This cannot be undone)', 'appthemes'); ?>"); }
        function confirmBeforeDeleteOptions() { return confirm("<?php _e('WARNING: You are about to completely delete all JobRoller configuration options from the wp_options database table. Are you sure you want to proceed? (This cannot be undone)', 'appthemes'); ?>"); }
    /* ]]> */
    </script>


<?php
}
?>
