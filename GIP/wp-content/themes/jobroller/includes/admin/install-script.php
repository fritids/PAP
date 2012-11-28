<?php
/**
* Install script to insert default data.
* Only run if theme is being activated
* for the first time.
*
*/
global $app_theme, $app_version, $jr_log, $wp_rewrite;
global $pagenow;

// check if theme is activated by admin.
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
    
    function install_jobroller() {
    
    	// define vars
    	global $app_theme, $app_version, $jr_log, $wp_rewrite;
    	
    	$jr_log->clear_log();
    	
    	// Clear cron
    	wp_clear_scheduled_hook('get_indeed_jobs');
    	wp_clear_scheduled_hook('check_indeed_jobs');
    	wp_clear_scheduled_hook('jr_get_indeed_jobs');
    	wp_clear_scheduled_hook('jr_check_indeed_jobs');
    	wp_clear_scheduled_hook('check_if_jobs_have_expired');
    	wp_clear_scheduled_hook('jr_check_jobs_expired');
    	wp_clear_scheduled_hook('get_indeed_jobs');
    	wp_clear_scheduled_hook('get_indeed_jobs');
    	wp_clear_scheduled_hook('appthemes_update_check');

    	update_option('jr_get_indeed_jobs', 'no');
    	update_option('jr_check_jobs_expired', 'no');
    	update_option('jr_indeed_xml_index', '0');

    	// insert the default values
    	jr_default_options();
        
        // insert additional default values
        jr_default_values();

        // run the table install script
    	jr_tables_install();

        // create pages and assign templates
    	jr_create_pages();

        // insert the default job types
    	jr_create_cats();

		// insert the default links
		jr_insert_links();

		//flush the rewrite rules so the new custom post types will automatically work
		$wp_rewrite->flush_rules();
    	
    }
    add_action('init', 'install_jobroller');
	
	if ( function_exists('delete_site_transient') ) {
		$theme_name = strtolower($app_theme);
		delete_site_transient($theme_name.'_update_theme');
    }
    
}

// Set default options
function jr_default_options() {
	global $options_settings, $options_gateways, $options_pricing;

	foreach ($options_settings as $value) {
        if (isset($value['std'])) {
        	add_option($value['id'], $value['std']);
        }
    }
    //print_r($options_pricing);
    foreach ($options_pricing as $value) {
        if (isset($value['std'])) {
        	add_option($value['id'], $value['std']);
        }
    }
    
	//print_r($options_gateways);
	if(isset($options_gateways)) {
		foreach ($options_gateways as $value) {
			if (isset($value['std'])) add_option($value['id'], $value['std']);
		}
	}
}

// Create the JobRoller db tables
function jr_tables_install() {
	
	// create the orders table - used to track orders and completed payments
	scb_register_table( 'jr_orders' );	

    $sql = "
	        id mediumint(9) NOT NULL AUTO_INCREMENT,
	        user_id bigint(20) NULL,
	        status varchar(255) NOT NULL DEFAULT 'pending_payment',
	        cost varchar(255) NULL DEFAULT '',
	        job_id bigint(20) NULL,
	        pack_id bigint(20) NULL,
	        featured int(1) NOT NULL DEFAULT '0',
	        order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	        payment_date TIMESTAMP NULL,        
	        payer_first_name longtext NULL,
	        payer_last_name longtext NULL,
	        payer_email longtext NULL,
	        payment_type longtext NULL,
	        approval_method varchar(255) NULL,
	        payer_address longtext NULL,
	        transaction_id longtext NULL,        
	        order_key varchar(255) NULL,        
	        PRIMARY KEY  (id)";
	
	scb_install_table('jr_orders', $sql);
    	
	// create the job packs table		
	scb_register_table( 'jr_job_packs' );
	
	$sql = "
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		pack_name varchar(255) NOT NULL,
		pack_description LONGTEXT NOT NULL,
		job_count varchar(255) NULL DEFAULT '',
		pack_duration varchar(255) NULL DEFAULT '',
		job_duration varchar(255) NULL DEFAULT '',
		pack_cost varchar(255) NULL DEFAULT '',
		job_offers varchar(3) NULL DEFAULT '',
		feat_job_offers varchar(3) NULL DEFAULT '',
		access varchar(255) NULL DEFAULT 'none',
		job_cats varchar(255) NULL DEFAULT '',
		pack_order smallint(3) NULL DEFAULT '1',
		pack_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
		  ";
		
	scb_install_table('jr_job_packs', $sql);
	
	// create the customer packs table - stores pack data in case a pack is edited/deleted
	scb_register_table( 'jr_customer_packs' );		
		
    $sql = "
	        id mediumint(9) NOT NULL AUTO_INCREMENT,
	        pack_id mediumint(9) NOT NULL,
	        user_id bigint(20) NULL,
	        pack_name varchar(255) NOT NULL,
	        job_duration varchar(255) NULL DEFAULT '',        
	        pack_expires TIMESTAMP NULL,
	        jobs_count INT(9) NULL,        
	        jobs_limit INT(9) NULL,	        
	        job_offers_count INT(9) NULL,
	        job_offers INT(9) NULL,
	        feat_job_offers_count INT(9) NULL,
	        feat_job_offers INT(9) NULL,
	        access varchar(255) NULL DEFAULT '',
	        job_cats varchar(255) NULL DEFAULT '',
	        pack_order smallint(3) NULL DEFAULT '1',	        
	        pack_purchased TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	        PRIMARY KEY  (id)
		   ";
	
	scb_install_table('jr_customer_packs', $sql);	   

	// create the daily page view counter table
	scb_register_table( 'jr_counter_daily' );		
	
	$sql = "
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time date DEFAULT '0000-00-00' NOT NULL,
			postnum int NOT NULL,
			postcount int DEFAULT '0' NOT NULL,
			UNIQUE KEY  (id)
		   ";
	
	scb_install_table('jr_counter_daily', $sql);	   	 
		
	// create the all-time page view counter table
	scb_register_table( 'jr_counter_total' );		
	
    $sql = "
	        id mediumint(9) NOT NULL AUTO_INCREMENT,
			postnum int NOT NULL,
			postcount int DEFAULT '0' NOT NULL,
			UNIQUE KEY  (id)
		   ";
	
	scb_install_table('jr_counter_total', $sql);	 	 
		
    // create the job alerts table
	scb_register_table( 'jr_alerts' );		
	
	$sql = "
			post_id bigint(20) NOT NULL,
			alert_type varchar(1024) NOT NULL,
			last_user_id bigint(20) DEFAULT NULL,
			last_activity timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY  (post_id),
			INDEX  (alert_type)
		  ";		 		 				
	
	scb_install_table('jr_alerts', $sql);	  
	
}

// Create the JobRoller pages and assign the templates to them
function jr_create_pages() {
    global $wpdb;
	
	// first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'date' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'date',
        'post_title' => 'Job Date Archive'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        update_post_meta($page_id, '_wp_page_template', 'tpl-jobs-by-date.php');

        update_option('jr_date_archive_page_id', $page_id);

    } else {
    	update_option('jr_date_archive_page_id', $pagefound);
    }
    
    // first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'blog' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'blog',
        'post_title' => 'Blog'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        update_post_meta($page_id, '_wp_page_template', 'tpl-blog.php');

        update_option('jr_blog_page_id', $page_id);

    } else {
    	update_option('jr_blog_page_id', $pagefound);
    }
    
    // first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'profile' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'profile',
        'post_title' => 'My Profile'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        update_post_meta($page_id, '_wp_page_template', 'tpl-profile.php');

        update_option('jr_user_profile_page_id', $page_id);

    } else {
    	update_option('jr_user_profile_page_id', $pagefound);
    }
    
     // first check and make sure this page doesn't already exist
	$sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'resume' LIMIT 1";
	
	$pagefound = $wpdb->get_var($sql);
	
	if($wpdb->num_rows == 0) {
	
	   // then create the edit item page
	   $my_page = array(
	   'post_status' => 'publish',
	   'post_type' => 'page',
	   'post_author' => 1,
	   'post_parent' => get_option('jr_user_profile_page_id'),
	   'post_name' => 'resume',
	   'post_title' => 'Edit Resume'
	   );
	
	   // Insert the page into the database
	   $page_id = wp_insert_post($my_page);
	
	   // Assign the page template to the new page
	   update_post_meta($page_id, '_wp_page_template', 'tpl-edit-resume.php');
	   
	   update_option('jr_job_seeker_resume_page_id', $page_id);
	
	} else {
    	update_option('jr_job_seeker_resume_page_id', $pagefound);
    }
    
    // first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'contact' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'contact',
        'post_title' => 'Contact'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        update_post_meta($page_id, '_wp_page_template', 'tpl-contact.php');
    }
    
    // update_option('show_on_front', 'page');
    
    // first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'dashboard' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'dashboard',
        'post_title' => 'My Dashboard'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        // Assign the page template to the new page
        update_post_meta($page_id, '_wp_page_template', 'tpl-dashboard.php');
        
        update_option('jr_dashboard_page_id', $page_id);

    } else {
    	update_option('jr_dashboard_page_id', $pagefound);
    	update_post_meta($pagefound, '_wp_page_template', 'tpl-dashboard.php');
    }
    
    // first check and make sure this page doesn't already exist
    $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'addnewconfirm' LIMIT 1";

    $pagefound = $wpdb->get_var($sql);

    if($wpdb->num_rows == 0) {

        // then create the edit item page
        $my_page = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'addnewconfirm',
        'post_title' => 'Submit Confirmation'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($my_page);

        // Assign the page template to the new page
        update_post_meta($page_id, '_wp_page_template', 'tpl-add-new-confirm.php');
        
        update_option('jr_add_new_confirm_page_id', $page_id);

    } else {
    	update_option('jr_add_new_confirm_page_id', $pagefound);
    }
       
	// first check and make sure this page doesn't already exist
	$sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'submit' LIMIT 1";
	
	$pagefound = $wpdb->get_var($sql);
	
	if($wpdb->num_rows == 0) {
	
	   // then create the edit item page
	   $my_page = array(
	   'post_status' => 'publish',
	   'post_type' => 'page',
	   'post_author' => 1,
	   'post_name' => 'submit',
	   'post_title' => 'Submit'
	   );
	
	   // Insert the page into the database
	   $page_id = wp_insert_post($my_page);
	
	   // Assign the page template to the new page
	   update_post_meta($page_id, '_wp_page_template', 'tpl-submit.php');
	   
	   update_option('jr_submit_page_id', $page_id);
	
	} else {
    	update_option('jr_submit_page_id', $pagefound);
    }
    
    
    // first check and make sure this page doesn't already exist
	$sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = 'edit-job' LIMIT 1";
	
	$pagefound = $wpdb->get_var($sql);
	
	if($wpdb->num_rows == 0) {
	
	   // then create the edit item page
	   $my_page = array(
	   'post_status' => 'publish',
	   'post_type' => 'page',
	   'post_author' => 1,
	   'post_name' => 'edit-job',
	   'post_title' => 'Edit Job'
	   );
	
	   // Insert the page into the database
	   $page_id = wp_insert_post($my_page);
	
	   // Assign the page template to the new page
	   update_post_meta($page_id, '_wp_page_template', 'tpl-edit-job.php');
	   
	   update_option('jr_edit_job_page_id', $page_id);
	
	} else {
    	update_option('jr_edit_job_page_id', $pagefound);
    }
}

// create job cats
function jr_create_cats() {
	global $featured_job_cat_id, $wpdb;

	$listings = get_posts( array(
		'post_type' => APP_POST_TYPE,
		'posts_per_page' => 1
	) );

	if ( !empty( $listings ) )
		return;
		
	$job_types = array(
		'Full-Time',
		'Part-Time',
		'Freelance',
		'Temporary',
		'Internship'
	);
	// Finally theres a featured jobs category
	$featured_job_cat_name = 'Featured';
		
	wp_insert_term($featured_job_cat_name, 'job_cat');
	
	$featured_job_cat_id = get_term_by( 'slug', sanitize_title($featured_job_cat_name), 'job_cat')->term_id;
	
	update_option('jr_featured_category_id', $featured_job_cat_id);
	
	if ($job_types) foreach($job_types as $type)
			$ins_id = wp_insert_term($type,'job_type');
	
	// Default Salaries
	$salaries = array(
		'Less than 20,000',
		'20,000 - 40,000',
		'40,000 - 60,000',
		'60,000 - 80,000',
		'80,000 - 100,000',
		'100,000 and above'
	);
	if ($salaries) foreach($salaries as $salary) 	
		$ins_id = wp_insert_term($salary,'job_salary');
	
	// Default Resume Languages
	$languages = array(
		'Mandarin',
		'English',
		'Spanish',
		'Arabic',
		'Hindi/Urdu',
		'Bengali',
		'Portuguese',
		'Russian',
		'Japanese',
		'German',
		'French',
		'Italian'
	);
	if ($languages) foreach($languages as $lang)
		$ins_id = wp_insert_term($lang,'resume_languages');
	
	// Default resume categories for job industry
	$resume_category = array(
		'Admin', 'Accounting', 'Agriculture', 'Aviation', 'Automotive', 'Architecture', 'Advertising', 'Banking', 'Building', 'Construction', 'Catering', 'Charity', 'Childcare', 'Customer Service', 'Driving', 'Design', 'Defence', 'Engineering', 'Executive', 'Education', 'Electronics', 'Environmental', 'Finance', 'Government', 'Hospitality', 'Health', 'IT', 'Industrial', 'Insurance', 'Leisure', 'Law', 'Logistics', 'Marketing', 'Medical', 'Manufacturing', 'Media', 'Mechanical', 'Nursing', 'Public Sector', 'Pharmaceutical', 'Retail', 'Recruitment', 'Social Care', 'Security', 'Secretarial', 'Scientific', 'Sports', 'Surveying', 'Travel', 'Telecommunications', 'Tourism', 'Other'
	);
	if ($resume_category) foreach($resume_category as $cat)
		wp_insert_term($cat,'resume_category');
	
	// Default desired job types for resumes
	$resume_job_types = array(
		'Full-Time',
		'Part-Time',
		'Freelance',
		'Temporary',
		'Internship'
	);
	if ($resume_job_types) foreach($resume_job_types as $resume_job_type)
		wp_insert_term($resume_job_type, 'resume_job_type');
	
}


// Create a default blogroll link
function jr_insert_links() {
	global $wpdb, $app_abbr;

	// first check and make sure this link doesn't already exist
	$wpdb->get_row("SELECT link_id FROM $wpdb->links WHERE link_url = 'http://www.appthemes.com/'");

    if($wpdb->num_rows == 0) {

		// get the correct link category id
		$results = $wpdb->get_row("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'link_category' LIMIT 1");

		if($results) $blogroll_id = $results->term_taxonomy_id; else $blogroll_id = '2';

		$default_links = array();
		$default_links[] = array('link_url' => 'http://www.appthemes.com/','link_name' => 'Premium WordPress Themes','link_rss' => '','link_notes' =>'');

		foreach ($default_links as $link) :
			$wpdb->insert($wpdb->links, $link);
			$wpdb->insert($wpdb->term_relationships, array('term_taxonomy_id' => $blogroll_id, 'object_id' => $wpdb->insert_id));
		endforeach;

	}
}


function jr_default_values() {
   global $wpdb, $app_abbr, $app_version;

	// set the paypal ipn option to no
	if(get_option($app_abbr.'_enable_paypal_ipn') == false) update_option($app_abbr.'_enable_paypal_ipn', 'no');

	// set the default currency symbol location
	if(get_option($app_abbr.'_curr_symbol_pos') == false) update_option($app_abbr.'_curr_symbol_pos', 'left');

	// set the default new WP user role only if it's currently subscriber
	if(get_option('default_role') == 'subscriber') update_option('default_role', 'contributor');

	// check the "membership" box to enable wordpress registration
	if(get_option('users_can_register') == 0) update_option('users_can_register', 1);

	// turn off Google CDN
	if(get_option($app_abbr.'_google_jquery') == false) update_option($app_abbr.'_google_jquery', 'no');

	// turn off debug mode
	if(get_option($app_abbr.'_debug_mode') == false) update_option($app_abbr.'_debug_mode', 'no');
	
	// enable the WP version meta tag
	if(get_option($app_abbr.'_remove_wp_generator') == false) update_option($app_abbr.'_remove_wp_generator', 'no');
	
	// enable the WP 3.1 toolbar
	if(get_option($app_abbr.'_remove_admin_bar') == false) update_option($app_abbr.'_remove_admin_bar', 'no');

	// set the default Google Maps values
	if(get_option($app_abbr.'_gmaps_lang') == false) update_option($app_abbr.'_gmaps_lang', 'en');
	if(get_option($app_abbr.'_gmaps_region') == false) update_option($app_abbr.'_gmaps_region', 'US');
	if(get_option($app_abbr.'_distance_unit') == false) update_option($app_abbr.'_distance_unit', 'mi');

	if(get_option($app_abbr.'_nu_admin_email') == false) update_option($app_abbr.'_nu_admin_email', 'yes');

	// set the default custom post type and taxomoy base urls
	if(get_option($app_abbr.'_job_permalink') == false) update_option($app_abbr.'_job_permalink', 'jobs');
	if(get_option($app_abbr.'_job_cat_tax_permalink') == false) update_option($app_abbr.'_job_cat_tax_permalink', 'job-category');
	if(get_option($app_abbr.'_job_type_tax_permalink') == false) update_option($app_abbr.'_job_type_tax_permalink', 'job-type');
	if(get_option($app_abbr.'_job_tag_tax_permalink') == false) update_option($app_abbr.'_job_tag_tax_permalink', 'job-tag');
	if(get_option($app_abbr.'_job_salary_tax_permalink') == false) update_option($app_abbr.'_job_salary_tax_permalink', 'salary');
	if(get_option($app_abbr.'_resume_permalink') == false) update_option($app_abbr.'_resume_permalink', 'resumes');
	
	if(get_option($app_abbr.'_jobs_default_expires') == false) update_option($app_abbr.'_jobs_default_expires', '30');

	// set default new user registration email values
	if(get_option($app_abbr.'_nu_custom_email') == false) update_option($app_abbr.'_nu_custom_email', 'no');
	if(get_option($app_abbr.'_nu_from_name') == false) update_option($app_abbr.'_nu_from_name', wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));
	if(get_option($app_abbr.'_nu_from_email') == false) update_option($app_abbr.'_nu_from_email', get_option('admin_email'));
	if(get_option($app_abbr.'_nu_email_subject') == false) update_option($app_abbr.'_nu_email_subject', 'Thank you for registering, %username%');
	if(get_option($app_abbr.'_nu_email_type') == false) update_option($app_abbr.'_nu_email_type', 'text/plain');

	if(get_option($app_abbr.'_nu_email_body') == false) update_option($app_abbr.'_nu_email_body', '
Hi %username%,

Welcome to %blogname%!

Below you will find your username and password which allows you to login to your user account.

--------------------------
Username: %username%
Password: %password%

%loginurl%
--------------------------

If you have any questions, please just let us know.

Best regards,


Your %blogname% Team
%siteurl%
');

// set default job pack options
if(get_option($app_abbr.'_packs_dashboard_buy') == false) update_option($app_abbr.'_packs_dashboard_buy', 'no');

// set default alert email values
if(get_option($app_abbr.'_job_alerts') == false) update_option($app_abbr.'_job_alerts', 'no');
if(get_option($app_abbr.'_job_alerts_feed') == false) update_option($app_abbr.'_job_alerts_feed', 'no');

if(get_option($app_abbr.'_job_alerts_from_name') == false) update_option($app_abbr.'_job_alerts_from_name', wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));
if(get_option($app_abbr.'_job_alerts_from_email') == false) update_option($app_abbr.'_job_alerts_from_email', get_option('admin_email'));
if(get_option($app_abbr.'_job_alerts_email_subject') == false) update_option($app_abbr.'_job_alerts_email_subject', 'Job Alerts');
if(get_option($app_abbr.'_job_alerts_email_type') == false) update_option($app_abbr.'_job_alerts_email_type', 'text/html');
if(get_option($app_abbr.'_job_alerts_email_template') == false) update_option($app_abbr.'_job_alerts_email_template', 'standard');
	
if(get_option($app_abbr.'_job_alerts_email_body') == false) update_option($app_abbr.'_job_alerts_email_body', "

%joblist%

You're receiving this email because you're subscribed to %blogname% job alerts. To unsubscribe please change the alert settings on your dashboard.

Best regards,
Your %blogname% Team
%siteurl%

");	

if(get_option($app_abbr.'_job_alerts_job_body') == false) update_option($app_abbr.'_job_alerts_job_body', "

%jobtitle%
by: %author% @ %jobtime%
Job Type: %jobtype% | Job Category: %jobcat% | Company: %company% | Location: %location%

%jobdetails%

%permalink%

<hr/>

");	

	// reCaptcha default values
	if(get_option($app_abbr.'_captcha_enable') == false) update_option($app_abbr.'_captcha_enable', 'no');
	if(get_option($app_abbr.'_captcha_theme') == false) update_option($app_abbr.'_captcha_theme', 'red');

	// Indeed cache default values
	if(get_option($app_abbr.'_indeed_frontpage_cache') == false) update_option($app_abbr.'_indeed_frontpage_cache', '3600');

}

?>