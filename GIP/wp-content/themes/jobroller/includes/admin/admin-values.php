<?php
/**
*
* Here is where all the admin field data is stored
* All the data is stored in arrays and then looped though
* @author AppThemes
* @version 1.2
*
*
*
*/

global $options_settings, $options_pricing, $options_job_packs, $options_feeds, $options_emails,$options_alerts, $options_advertisments, $options_integration, $app_abbr;

$options_settings = array(

	array( 'type' => 'tab', 'tabname' => __('General', 'appthemes') ),

	array( 'name' => __('Site Configuration', 'appthemes'), 'type' => 'title', 'desc' => '', 'id' => ''),

	array(  
		'name' 		=> __('Color Scheme', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Select the color scheme you would like to use.','appthemes'),
		'id' 		=> $app_abbr.'_child_theme',
		'css' 		=> 'min-width:230px;',
		'std' 		=> 'style-default.css',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min'		=> '',
		'type' 		=> 'select',
		'options' 	=> jr_settings_theme_styles(),
	),                                            
	
	array(  
		'name' 		=> __('Enable Logo','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('If you do not have a logo to use, select no and this will display the title and description of your web site instead.','appthemes'),
		'id' 		=> $app_abbr.'_use_logo',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' 		=> __('Web Site Logo','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Paste the URL of your web site logo image here. It will replace the default JobRoller header logo.(i.e. http://www.yoursite.com/logo.jpg)','appthemes'),
		'id' 		=> $app_abbr.'_logo_url',
		'css' 		=> 'min-width:398px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'upload',
		'std' 		=> ''
	),
	

	array(  
		'name' 		=> __('Disable Blog','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turn this on to hide the blog pages.','appthemes'),
		'id' 		=> $app_abbr.'_disable_blog',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array( 
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes')
		)
	),

	array(  
		'name' 		=> __('Feedburner URL','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Feedburner account</a>.",'appthemes'), '<div class="feedburnerico"></div>', 'http://feedburner.google.com' ),
		'tip' 		=> __('Paste your Feedburner address here. It will automatically redirect your default RSS feed to Feedburner. You must have a Google Feedburner account setup first.','appthemes'),
		'id' 		=> $app_abbr.'_feedburner_url',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Twitter ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Twitter account</a>.",'appthemes'), '<div class="twitterico"></div>', 'http://twitter.com' ),
		'tip' 		=> __('Paste your Twitter ID here. It will be used in the Twitter sidebar widget. You must have a Twitter account setup first.','appthemes'),
		'id' 		=> $app_abbr.'_twitter_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Facebook Page ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Facebook account</a>.",'appthemes'), '<div class="facebookico"></div>', 'http://www.facebook.com' ),
		'tip' 		=> __('Paste your Facebook Page ID here. It will be used in the Facebook Like Box sidebar widget. You must have a Facebook account and page setup first.','appthemes'),
		'id' 		=> $app_abbr.'_facebook_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req'		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('ShareThis ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>ShareThis account</a>.",'appthemes'), '<div class="sharethisico"></div>', 'http://sharethis.com' ),
		'tip' 		=> __('Paste your ShareThis publisher ID here. It will show the ShareThis buttons on the blog post and job listings. You must have a ShareThis account and page setup first.','appthemes'),
		'id' 		=> $app_abbr.'_sharethis_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min'		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Tracking Code','appthemes'),
		'desc' 		=> sprintf('%s' . __("Sign up for a free <a target='_new' href='%s'>Google Analytics account</a>.",'appthemes'), '<div class="googleico"></div>', 'http://www.google.com/analytics/' ),
		'tip' 		=> __('Paste your analytics tracking code here. Google Analytics is free and the most popular but you can use other providers as well.','appthemes'),
		'id' 		=> $app_abbr.'_google_analytics',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),

	array(	'name' => __('Google Maps Settings', 'appthemes'), 'type' => 'title', 'id' => '' ),

	array(  
		'name' 		=> __('Google Maps Language','appthemes'),
		'desc' 		=> sprintf( __("Find the list of supported language codes <a target='_new' href='%s'>here</a>.",'appthemes'), 'http://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1' ),
		'tip' 		=> __('The Google Maps API uses the browsers language setting when displaying textual info on the map. In most cases, this is preferable and you should not need to override this setting. However, if you wish to change the Maps API to ignore the browsers language setting and force it to display info in a particular language, enter your two character region code here (i.e. Japanese is ja).','appthemes'),
		'id' 		=> $app_abbr.'_gmaps_lang',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Google Maps Region Biasing','appthemes'),
		'desc' 		=> sprintf( __("Find your two-letter ccTLD region code <a target='_new' href='%s'>here</a>.",'appthemes'), 'http://en.wikipedia.org/wiki/CcTLD' ),
		'tip' 		=> __("Enter your country's two-letter region code here to properly display map locations. (i.e. Someone enters the location &quot;Toledo&quot;, it's based off the default region (US) and will display &quot;Toledo, Ohio&quot;. With the region code set to &quot;ES&quot; (Spain), the results will show &quot;Toledo, Spain.&quot;)",'appthemes'),
		'id' 		=> $app_abbr.'_gmaps_region',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

        array(  'name'          => __('Distance Unit','appthemes'),
                'desc'          => '',
                'tip'           => __('Defines the radius unit for search.','appthemes'),
                'id'            => $app_abbr.'_distance_unit',
                'css'           => 'width:100px;',
                'std'           => '',
                'vis'           => '',
                'req'           => '',
                'js'            => '',
                'min'           => '',
                'type'          => 'select',
                'options'       => array(  'mi' => 'Miles',
                                           'km'  => 'Kilometers')),

	array( 'name' => __('General Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),
	
	array(  
		'name' => __('Enable password fields on registration form','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turning this off will send the user a password instead of letting them set it.','appthemes'),
		'id' 		=> $app_abbr.'_allow_registration_password',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Show Sidebar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turning off the sidebar will make the main content area wider and move the submit button for all main pages.','appthemes'),
		'id' 		=> $app_abbr.'_show_sidebar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Show Search Bar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Toggle the search bar on/off with this option.','appthemes'),
		'id' 		=> $app_abbr.'_show_searchbar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Show Filter Bar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Toggle the filter bar on/off with this option (shows checkboxes with Full-Time, Part-Time, etc.','appthemes'),
		'id' 		=> $app_abbr.'_show_filterbar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Show Empty Categories?','appthemes'),
		'desc' 		=> __('By default, empty categories or job types are not visible and cannot be filtered by users, using the category and job type filter widget. If you are pulling jobs from external sources, this option will enable users to filter jobs from any category or job type.', 'appthemes'),
		'tip' 		=> __('This option should only be enabled if you pull jobs from external sources (Indeed, etc...). It may show empty listings otherwise.','appthemes'),
		'id' 		=> $app_abbr.'_show_empty_categories',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '1',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'0' => __('Yes', 'appthemes'),
			'1'  => __('No', 'appthemes'),
		)
	),	

	array(  
		'name' => __('"Submit" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the Post a Job button. Leave it blank to automatically display pricing (if listings are paid).','appthemes'),
		'id' 		=> $app_abbr.'_jobs_submit_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Jobs', 'appthemes') ),

	array( 'name' => __('Job Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),

	array(
		'name' 		=> __('Default Expiration Days','appthemes'),
		'desc' 		=> __("Default number of days until a job offer expires"),
		'tip' 		=> __("Enter the default number of days until a job offer expires.",'appthemes'),
		'id' 		=> $app_abbr.'_jobs_default_expires',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Moderate Job Listings','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to control if new free job listings should be manually approved before they go live. Note: paid jobs will automatically be published regardless of this setting.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_require_moderation',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Allow Job Editing','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to control if job listings can be edited by the user.','appthemes'),
		'id' 		=> $app_abbr.'_allow_editing',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Edited Job Requires Approval','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to define whether or not you want to moderate edited jobs. The job will be marked as \'draft\' and admin will be notified via email.','appthemes'),
		'id' 		=> $app_abbr.'_editing_needs_approval',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Show Page Views Counter','appthemes'),
		'desc'		=> '',
		'tip'		=> __("This will show a 'total views' and 'today's views' at the bottom of each job listing and blog post.",'appthemes'),
		'id'		=> $app_abbr.'_ad_stats_all',
		'css'		=> 'min-width:100px;',
		'std'		=> '',
		'vis'		=> '',
		'req'		=> '',
		'js'		=> '',
		'min'		=> '',
		'type'		=> 'select',
		'options'	=> array(
			'yes' => __('Yes', 'appthemes'),
			 'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Display "How to Apply" Field?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job should the how to apply field be visible?','appthemes'),
		'id' 		=> $app_abbr.'_submit_how_to_apply_display',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Job Category Required','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job, is job category required? Make sure you have at least one job category before enabling this option. (Recommended)','appthemes'),
		'id' 		=> $app_abbr.'_submit_cat_required',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Enable Job Salary Field?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enable or disable the Salary field in the job submission form.','appthemes'),
		'id' 		=> $app_abbr.'_enable_salary_field',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Allow HTML in Job Descriptions?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job, is HTML allowed? Select no to have it automatically stripped out.','appthemes'),
		'id' 		=> $app_abbr.'_html_allowed',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Expired Jobs Action','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Choose what to do with expired jobs. Selecting \'display message\' will keep the job visible and display a \'job expired\' notice on it. Selecting \'hide\' will change the job post to private so only the job poster may view it..','appthemes'),
		'id' 		=> $app_abbr.'_expired_action',
		'css' 		=> 'min-width:150px;',
		'std' 		=> 'display_message',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'display_message' => __('Display Message', 'appthemes'),
			'hide'  => __('Hide', 'appthemes')
		)
	),
	
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Resumes', 'appthemes') ),

	array( 'name' => __('Job Seeker Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),
	
	array(  
		'name' => __('Enable Job Seeker Registration','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Allows Job Seekers to signup. Job Seekers cannot post jobs; they can only find jobs and submit their resume.','appthemes'),
		'id' 		=> $app_abbr.'_allow_job_seekers',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('"My Profile" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the My Profile button.','appthemes'),
		'id' 		=> $app_abbr.'_my_profile_button_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Submit your Resume, update your profile, and allow employers to find <em>you</em>!'
	),
	
	array(  
		'name' => __('"Submit your resume" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the Submit your resume button when browsing resumes.','appthemes'),
		'id' 		=> $app_abbr.'_submit_resume_button_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Register as a Job Seeker to submit your Resume.'
	),
	
	array( 'name' => __('Resume Options', 'appthemes'), 'type' => 'title', 'desc' 		=> __('Control who can view resumes', 'appthemes') ),
	
	array(  
		'name' => __('Resume Listings Visibility','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Lets you define who can browse through submitted resumes.','appthemes'),
		'id' 		=> $app_abbr.'_resume_listing_visibility',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'listers',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'public' => __('Public', 'appthemes'),
			'members'  => __('Members only', 'appthemes'),
			'listers'  => __('Job listers', 'appthemes'),
			'recruiters'  => __('Recruiters', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Resume Visibility','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Lets you define who can view submitted resumes.','appthemes'),
		'id' 		=> $app_abbr.'_resume_visibility',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'listers',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'public' => __('Public', 'appthemes'),
			'members'  => __('Members only', 'appthemes'),
			'listers'  => __('Job listers', 'appthemes'),
			'recruiters'  => __('Recruiters', 'appthemes')
		)
	),
	
	array( 'name' => __('Anti-Spam', 'appthemes'), 'type' => 'title', 'desc' 		=> __('Secure resumes contact details', 'appthemes') ),
	
	array(  
		'name' => __('Enable Contact Form','appthemes'),
		'desc' 		=> __('Choose whether you want show a contact form instead of the resume author contact details (email, mobile and telephone).'),
		'tip' 		=> __('To avoid spammers you can hide the resumes contact details and let employers contact resume authors using a popup contact form.','appthemes'),
		'id' 		=> $app_abbr.'_resume_show_contact_form',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'no' => __('No', 'appthemes'),
			'yes'  => __('Yes', 'appthemes'),
		)
	),	

	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Pages', 'appthemes') ),

	array( 'name' => __('Page/Category ID Configuration', 'appthemes'), 'type' => 'title', 'desc' => '' ),
	
	array(  
		'name' 		=> __('Featured Job Category ID','appthemes'),
		'desc' 		=> sprintf( __("Visit the <a target='_new' href='%s'>Job Categories</a> page to get the category ID.",'appthemes'), 'edit-tags.php?taxonomy=job_cat&post_type=job_listing' ),
		'tip' 		=> __('By default, your featured category ID is already included. To find the featured category ID in case you need to change it, click on the Job Categories link and then hover over the title of the Featured category. The status bar of your browser will display a URL with a numeric ID at the end. This is the category ID.','appthemes'),
		'id' 		=> $app_abbr.'_featured_category_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Submit Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Submit job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_submit_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Edit Job Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the edit job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_edit_job_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('My Dashboard Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the My Dashboard page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_dashboard_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('User Profile Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the user profile page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_user_profile_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array( 'name' => __('Confirmation Page ID','appthemes'),
		'desc' 		=> __('This is a page for non-IPN paypal transactions to go through.','appthemes'),
		'tip' 		=> __('Enter the page ID for the Confirmation job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_add_new_confirm_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Blog Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Blog page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_blog_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Jobs by date Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the jobs date archive page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_date_archive_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('Terms Page ID','appthemes'),
		'desc' 		=> __('Create a terms page and enter it\'s ID here; this will enable a checkbox on the registration page to confirm that the user accepts your terms and conditions.', 'appthemes'),
		'tip' 		=> __('Enter the page ID for the terms page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_terms_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Job Seeker Register Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Job Seeker Registration page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_job_seeker_register_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('Job Seeker Edit Resume Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Edit Resume page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_job_seeker_resume_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Security', 'appthemes') ),

	array(	'name' => __('Security Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Back Office Access','appthemes'),
		'desc' 		=> sprintf( __("View the WordPress <a target='_new' href='%s'>Roles and Capabilities</a> for more information.",'appthemes'), 'http://codex.wordpress.org/Roles_and_Capabilities' ),
		'tip' 		=> __('Allows you to restrict access to the WordPress Back Office (wp-admin) by specific role. Keeping this set to admins only is recommended. Select Disable if you have problems with this feature.','appthemes'),
		'id' 		=> $app_abbr.'_admin_security',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'manage_options' => __('Admins Only', 'appthemes'),
			'edit_others_posts' => __('Admins, Editors', 'appthemes'),
			'publish_posts' => __('Admins, Editors, Authors', 'appthemes'),
			'edit_posts' => __('Admins, Editors, Authors, Contributors', 'appthemes'),
			'read' => __('All Access', 'appthemes'),
			'disable' => __('Disable', 'appthemes')
		)
	),

	array( 'name' => __('reCaptcha Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Enable reCaptcha', 'appthemes'),
		'desc' 		=> sprintf(__("reCaptcha is a free anti-spam service provided by Google. Learn more about <a target='_new' href='%s'>reCaptcha</a>.", 'appthemes'), 'http://code.google.com/apis/recaptcha/'),
		'tip' 		=> __('Set this option to yes to enable the reCaptcha service that will protect your site against spam registrations. It will show a verification box on your registration page that requires a human to read and enter the words.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_enable',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('reCaptcha Public Key', 'appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Google reCaptcha</a> account.",'appthemes'), '<div class="captchaico"></div>', 'https://www.google.com/recaptcha/admin/create' ),
		'tip' 		=> __('Enter your public key here to enable an anti-spam service on your new user registration page (requires a free Google reCaptcha account). Leave it blank if you do not wish to use this anti-spam feature.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_public_key',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('reCaptcha Private Key', 'appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Google reCaptcha</a> account.",'appthemes'), '<div class="captchaico"></div>', 'https://www.google.com/recaptcha/admin/create' ),
		'tip' 		=> __('Enter your private key here to enable an anti-spam service on your new user registration page (requires a free Google reCaptcha account). Leave it blank if you do not wish to use this anti-spam feature.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_private_key',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Choose Theme', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Select the color scheme you wish to use for reCaptcha.', 'appthemes'),
		'id' 		=> $app_abbr.'_captcha_theme',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'red' => __('Red', 'appthemes'),
			'white' => __('White', 'appthemes'),
			'blackglass' => __('Black', 'appthemes'),
			'clean'  => __('Clean', 'appthemes')
		)
	),

	array( 'name' => __('Anti-Spam Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Anti-Spam Question', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Question asked before visitor can submit a new job listing.','appthemes'),
		'id' 		=> $app_abbr.'_antispam_question',
		'css' 		=> 'width:500px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> 'Is fire &ldquo;<em>hot</em>&rdquo; or &ldquo;<em>cold</em>&rdquo;?'
	),

	array(  
		'name' => __('Anti-Spam Answer', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the correct answer here.','appthemes'),
		'id' 		=> $app_abbr.'_antispam_answer',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> 'hot'
	),

	array( 'type' => 'tabend' ),


	array( 'type' => 'tab', 'tabname' => __('Advertising', 'appthemes') ),

	array(	'name' => __('Header banner (468x60)', 'appthemes'),
		'type' 		=> 'title',
		'desc' 		=> '',
		'id' 		=> ''
	),

	array(  
		'name' => __('Enable header banner spot?', 'appthemes'),
		'desc' 		=> __("Change this option to enable or disable the header banner spot.",'appthemes'),
		'tip' 		=> __('This will replace the header navigation.','appthemes'),
		'id' 		=> $app_abbr.'_enable_header_banner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Banner Code', 'appthemes'),
		'desc' 		=> __('Image/Link HTML or JavaScript for the banner.','appthemes'),
		'tip' 		=> __('This can be what you like; javascript, an image and a link, text.','appthemes'),
		'id' 		=> $app_abbr.'_header_banner',
		'css' 		=> 'width:500px;height:150px;',
		'type' 		=> 'textarea',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),

	array(	'name' => __('Job Listing Banner (468x60)', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> 'If you have the sidebar turned off you may fit in a 728x90 banner instead.' ),

	array(  
		'name' => __('Enable job listing banner spot?', 'appthemes'),
		'desc' 		=> __("Change this option to enable or disable the job listing banner spot.",'appthemes'),
		'tip' 		=> __('This banner appears in a job listing, usually between "Job description" and "How to Apply".','appthemes'),
		'id' 		=> $app_abbr.'_enable_listing_banner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Banner Code', 'appthemes'),
		'desc' 		=> 'Image/Link HTML or JavaScript for the banner.',
		'tip' 		=> __('This can be what you like; javascript, an image and a link, text.','appthemes'),
		'id' 		=> $app_abbr.'_listing_banner',
		'css' 		=> 'width:500px;height:150px;',
		'type' 		=> 'textarea',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),

	array( 'type' => 'tabend'),


	array( 'type' => 'tab', 'tabname' => __('Advanced', 'appthemes') ),

		array(	'name' => __('Advanced Options', 'appthemes'),
					'type' => 'title',
					'id' => ''),


				array(  'name' => __('Enable Debug Mode','appthemes'),
                        'desc' => '',
                        'tip' => __('This will print out the $wp_query->query_vars array at the top of your website. This should only be used for debugging.','appthemes'),
                        'id' => $app_abbr.'_debug_mode',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),

				array(  'name' => __('Enable Debug Log','appthemes'),
						'desc' => '',
						'tip' => __('Turn this on to log emails and transactions for debugging. Logs are stored in /themes/jobroller/log/. Delete them when you are finished since they contain info about jobs and transactions.','appthemes'),
						'id' => $app_abbr.'_enable_log',
						'css' => 'min-width:100px;',
						'std' => 'no',
						'vis' => '',
						'req' => '',
						'js' => '',
						'min' => '',
						'type' => 'select',
						'options' => array( 'no'  => __('No', 'appthemes'),
											'yes' => __('Yes', 'appthemes'))),

				array(  'name' => __('Use Google CDN jQuery','appthemes'),
                        'desc' => '',
                        'tip' => __("This will use Google's hosted jQuery files which are served from their global content delivery network. This will help your site load faster and save bandwidth.",'appthemes'),
                        'id' => $app_abbr.'_google_jquery',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),
											 
				array(  'name' => __('Disable WordPress Version Meta Tag','appthemes'),
                        'desc' => '',
                        'tip' => __("This will remove the WordPress generator meta tag in the source code of your site <code>< meta name='generator' content='WordPress 3.1' ></code>. It's an added security measure which prevents anyone from seeing what version of WordPress you are using. It also helps to deter hackers from taking advantage of vulnerabilities sometimes present in WordPress. (Yes is recommended)",'appthemes'),
                        'id' => $app_abbr.'_remove_wp_generator',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),
											 
				array(  'name' => __('Disable WordPress User Toolbar','appthemes'),
                        'desc' => '',
                        'tip' => __("This will remove the WordPress user toolbar at the top of your web site which is displayed for all logged in users. This feature was added in WordPress 3.1.",'appthemes'),
                        'id' => $app_abbr.'_remove_admin_bar',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),

		array( 'name' => __('Custom Post Type & Taxonomy URLs', 'appthemes'),
                'type' => 'title',
                'id' => ''),

				array(  'name' => __('Job Listing Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job listing urls. The default is jobs and will look like this: http://www.yoursite.com/jobs/ad-title-here/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Category Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job category urls. The default is job-category and will look like this: http://www.yoursite.com/job-category/category-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_cat_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Type Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job type urls. The default is job-type and will look like this: http://www.yoursite.com/job-type/type-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_type_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Tag Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job tag urls. The default is job-tag and will look like this: http://www.yoursite.com/job-tag/tag-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_tag_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Salary Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your salary urls. The default is salary and will look like this: http://www.yoursite.com/salary/salary-value/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_salary_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),
                        
               array(  'name' => __('Resume Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your resume urls. The default is resumes and will look like this: http://www.yoursite.com/resumes/resume-title-here/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_resume_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

	array( 'type' => 'tabend'),


);


$options_emails = array (

 	array( 'type' => 'tab', 'tabname' => __('General', 'appthemes') ),

	array(	'name' => __('Email Notifications', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '', 'id' 		=> '' ),

	array(
		'name' => __('New Job Email','appthemes'),
		'desc' 		=> sprintf(__("Emails will be sent to: %s. (<a target='_new' href='%s'>Change email address</a>)", 'appthemes'), get_option('admin_email'), 'options-general.php'),
		'tip' 		=> __('Send me an email once a new job has been submitted.','appthemes'),
		'id' 		=> $app_abbr.'_new_ad_email',
		'css' 		=> 'width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('Job Listers Email','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Send the job owner an email when buying a job listings or job pack.','appthemes'),
		'id' 		=> $app_abbr.'_new_order_email',
		'css' 		=> 'width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('Job Approved Email','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Send the job owner an email once their job has been approved either by you manually or after payment has been made (post status changes from pending to published).','appthemes'),
		'id' 		=> $app_abbr.'_new_job_email_owner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('Enable Reminder Emails','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Send the job owner an email 5/1 days before their job expires, and another once their job has expired (post status changes from published to draft).','appthemes'),
		'id' 		=> $app_abbr.'_expired_job_email_owner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('BCC on all Apply Emails','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enable this option to receive a copy of application emails.','appthemes'),
		'id' 		=> $app_abbr.'_bcc_apply_emails',
		'css' 		=> 'width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),


	array( 'type' => 'tabend'),


	array( 'type' => 'tab', 'tabname' => __('New User Email', 'appthemes') ),

	array(	'name' => __('New User Registration Email', 'appthemes'), 'type' 		=> 'title' ),
	
		array(  
			'name' => __('Enable Custom Email','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('Sends a custom new user notification email to your customers by using the fields you complete below. If this is set to &quot;No&quot;, the default WordPress new user notification email will be sent. This is useful for debugging if your custom emails are not being sent.','appthemes'),
			'id' 		=> $app_abbr.'_nu_custom_email',
			'css' 		=> 'width:100px;',
			'std' 		=> '',
			'vis' 		=> '',
			'req' 		=> '',
			'js' 		=> '',
			'min' 		=> '',
			'type' 		=> 'select',
			'options' => array(  
				'yes' => __('Yes', 'appthemes'),
				'no'  => __('No', 'appthemes')
			)
		),

		array(  
			'name' => __('From Name','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what your customers will see as the &quot;from&quot; when they receive the new user registration email. Use plain text only','appthemes'),
			'id' 		=> $app_abbr.'_nu_from_name',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('From Email','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what your customers will see as the &quot;from&quot; email address (also the reply to) when they receive the new user registration email. Use only a valid and existing email address with no html or variables.','appthemes'),
			'id' 		=> $app_abbr.'_nu_from_email',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('Email Subject','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is the subject line your customers will see when they receive the new user registration email. Use text and variables only.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_subject',
			'css' 		=> 'width:400px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('Allow HTML in Body', 'appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This option allows you to use html markup in the email body below. It is recommended to keep it set to &quot;No&quot; to avoid problems with delivery. If you turn it on, make sure to test it and make sure the formatting looks ok and gets delivered properly.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_type',
			'css' 		=> 'width:100px;',
			'vis' 		=> '',
			'std' 		=> '',
			'js' 		=> '',
			'type' 		=> 'select',
			'options' => array(  
				'text/html'   => __('Yes', 'appthemes'),
				'text/plain'  => __('No', 'appthemes')
			)
		),

		array(  
			'name' => __('Email Body','appthemes'),
			'desc' 		=> __('You may use the following variables within the email body and/or subject line.<br/><br/><strong>%username%</strong> - prints out the username<br/><strong>%useremail%</strong> - prints out the users email address<br/><strong>%password%</strong> - prints out the users text password<br/><strong>%siteurl%</strong> - prints out your website url<br/><strong>%blogname%</strong> - prints out your site name<br/><strong>%loginurl%</strong> - prints out your sites login url<br/><br/>Each variable MUST have the percentage signs wrapped around it with no spaces.<br/>Always test your new email after making any changes (register) to make sure it is working and formatted correctly. If you do not receive an email, chances are something is wrong with your email body.','appthemes'),
			'tip' 		=> __('Enter the text you would like your customers to see in the new user registration email. Make sure to always at least include the %username% and %password% variables otherwise they might forget later.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_body',
			'css' 		=> 'width:550px;height:250px;',
			'vis' 		=> '',
			'req' 		=> '',
			'min' 		=> '',
			'type' 		=> 'textarea',
			'std' 		=> ''
		),

	array( 'type' => 'tabend'),


);

$options_alerts = array (

 	array( 'type' => 'tab', 'tabname' => __('General', 'appthemes') ),

	array(	'name' => __('Job Alerts', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '', 'id' 		=> '' ),

	array(
		'name' => __('Enable Job Alerts Email','appthemes'),
		'desc' 		=> __('Job Seekers will be able to set job alerts based on specific criteria.','appthemes'),
		'tip' 		=> __('A new area will be available on the Job Seeker\'s dashboard where they can configure their alerts criteria.','appthemes'),
		'id' 		=> $app_abbr.'_job_alerts',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Batch Size','appthemes'),
		'desc' 		=> __('Set the maximum allowed emails to be sent at a given time. A value between 1 and 100 is recommended.', 'appthemes'),
		'tip' 		=> __('This is the maximum number of emails that will be sent at a given time.','appthemes'),
		'id' 		=> $app_abbr.'_job_alerts_batch_size',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '100'
	),

	array(  
		'name' => __('Job Limit','appthemes'),
		'desc' 		=> __('Set the maximum number of jobs that should be sent on each email.', 'appthemes'),
		'tip' 		=> __('Email alerts can contain a list of jobs or individual jobs.','appthemes'),
		'id' 		=> $app_abbr.'_job_alerts_jobs_limit',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '5'
	),

	array(
		'name' => __('Recurrence','appthemes'),
		'desc' 		=> __('Set how often you want to trigger the job alerts.
						   <br/><br/>Emails are sent in batches every <code>n</code> minutes. If the batch size is smaller then the total emails to be sent at a given time, the remaining emails will be included on the next batch.
						   <br/><br/>Example:
						   <br/><code>Batch size = 100</code> <code>Jobs Limit = 5</code> <code>Recurrence = Once Hourly</code>
						   <br/>Each hour, JobRoller will pick 5 new jobs and look for matching user alerts. It will then split the mailing list in chunks of 100 users that will receive the jobs list.
						    The remaining users will be included on the batch that will run one hour later.
						   <br/><br/><strong>Important:</strong> It\'s strongly recommended that you contact your host provider for more information related with mass emailing limitations.        	 
							','appthemes'),
		'tip' 		=> __('This value should be set depending on how much activity you have on your site. If you have many jobs being posted, you should check for updates more frequently (lower value).','appthemes'),
		'id' 		=> $app_abbr.'_job_alerts_cron',
		'css' 		=> 'width:200px;',
		'std' 		=> 'hourly',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type'		=> 'select',
		'options' => array(  
			'ten_minutes'    => __('Every Ten Minutes', 'appthemes'),
			'twenty_minutes' => __('Every Twenty Minutes', 'appthemes'),
			'thirty_minutes' => __('Every Thirty Minutes', 'appthemes'),
			'hourly' 		 => __('Once Hourly', 'appthemes'),
			'daily' 		 => __('Once Daily', 'appthemes'),
		)
	),

	array(	'name' => __('Job Alerts Feed', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '', 'id' 		=> '' ),

	array(
		'name' => __('Enable Job Alerts RSS Feed','appthemes'),
		'desc' 		=> sprintf( __('Job Seekers will have access to a unique feed URL representing their alert criteria. This feed can be used by job seekers to subscribe to emails alerts using a 3d party service like <a target="_new" href="%s">FeedBurner</a> or <a target="_new" href="%s">FeedMyInbox</a>.','appthemes'), 'http://www.feedburner.com','http://www.feedmyinbox.com/'),
		'tip' 		=> __('Enable this option to allow job seekers to receive job alerts notifications using their unique RSS feed using a 3rd party service.','appthemes'),
		'id' 		=> $app_abbr.'_job_alerts_feed',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),	

	array( 'type' => 'tabend'),

	
	array( 'type' => 'tab', 'tabname' => __('Email Format', 'appthemes') ),

	array(	'name' => __('Job Alert Email', 'appthemes'), 'type' 		=> 'title' ),
	
		array(  
			'name' => __('From Name','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what job seekers will see as the &quot;from&quot; when they receive email alerts. Use plain text only','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_from_name',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> get_bloginfo('name'),
		),

		array(  
			'name' => __('From Email','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what job seekers will see as the &quot;from&quot; email address (also the reply to) when they receive the email alerts. Use only a valid and existing email address with no html or variables.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_from_email',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('Email Subject','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is the subject line job seekers will see when they receive email alerts. Use text and variables only.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_email_subject',
			'css' 		=> 'width:400px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> __('Job Alerts','appthemes'),
		),
		
		array(  
			'name' => __('Allow HTML in Body', 'appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This option allows you to use html markup in the email body below. If you\'re having proglems with email delivery it is recommended to set this option to &quot;No&quot;. Make sure to test it and that the formatting looks ok and gets delivered properly.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_email_type',
			'css' 		=> 'width:100px;',
			'vis' 		=> '',
			'std' 		=> 'text/plain',
			'js' 		=> '',
			'type' 		=> 'select',
			'options' => array(  
				'text/html'   => __('Yes', 'appthemes'),
				'text/plain'  => __('No', 'appthemes')
			)
		),
		
		array(  
			'name' => __('Email Template', 'appthemes'),
			'desc' 		=> __('Choose how to send the alert emails. The <em>Standard</em> option will send the text as formatted on the <em>Email Body</em> and  <em>Job List Body</em> fields whereas <em>External</em> will use an external HTML file as the email template.','appthemes'),
			'tip' 		=> __('Email alerts can be formatted using the fields below or using an external HTML template. Both options use the variables presented below.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_email_template',
			'css' 		=> 'min-width:400px;',
			'vis' 		=> '',
			'std' 		=> 'standard',
			'js' 		=> '',
			'type' 		=> 'select',
			'options' 	=> array_merge( array('standard' => __('Standard','appthemes')), jr_job_alerts_get_templates() ),
		),		
	
	array(	'name' => __('Standard Email Format', 'appthemes'), 'type' 		=> 'title' ),
	
		array(  
			'name' => __('Email Body','appthemes'),
			'desc' 		=> __('You may use the following variables within the email body and/or subject line.<br/><br/><strong>%username%</strong> - prints out the username<br/><strong>%jobtitle%</strong> - prints out the job title for single job emails<br/><strong>%joblist%</strong> - prints out the jobs list<br/><strong>%siteurl%</strong> - prints out your website url<br/><strong>%blogname%</strong> - prints out your site name<br/><strong>%loginurl%</strong> - prints out your sites login url<br/><strong>%dashboardurl%</strong> - prints out the user dashboard url<br/><br/>Each variable MUST have the percentage signs wrapped around it with no spaces.<br/>Always test the email format after making any changes to make sure it is working and formatted correctly.','appthemes'),
			'tip' 		=> __('Enter the text you would like job seekers to see in the email alerts.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_email_body',
			'css' 		=> 'width:550px;height:250px;',
			'vis' 		=> '',
			'req' 		=> '',
			'min' 		=> '',
			'type' 		=> 'textarea',
			'std' 		=> ''
		),

		array(  
			'name' => __('Job List Body','appthemes'),
			'desc' 		=> __('You may use the following variables within the email job body.<br/><br/><strong>%jobtitle%</strong> - prints out the Job title<br/><strong>%jobtime%</strong> - prints out the Job time/date<br/><strong>%jobdetails%</strong> - prints out the full job details<br/><strong>%jobdetails_#%</strong> - prints out a cut version of the job details. Replace # for the aproximate lenght of the job details to display<br/><strong>%jobtype%</strong> - prints out the job type<br/><strong>%jobcat%</strong> - prints out the job category<br/><strong>%author%</strong> - prints out the job author<br/><strong>%company%</strong> - prints out the job company<br/><strong>%location%</strong> - prints out the job location<br/><strong>%permalink%</strong> - prints out the job permalink<br/><strong>%thumbnail%</strong> - prints out the job thumbnail<strong>%thumbnail_url%</strong> - prints only the job thumbnail url<br/><br/>Each variable MUST have the percentage signs wrapped around it with no spaces.<br/>Always test the email format after making any changes to make sure it is working and formatted correctly.','appthemes'),
			'tip' 		=> __('Enter the text you would like job seekers to see in the email job part.','appthemes'),
			'id' 		=> $app_abbr.'_job_alerts_job_body',
			'css' 		=> 'width:550px;height:250px;',
			'vis' 		=> '',
			'req' 		=> '',
			'min' 		=> '',
			'type' 		=> 'textarea',
			'std' 		=> ''
		),						
		
	array( 'type' => 'tabend'),	
	
);

// admin options for the pricing page
$options_pricing = array (

	array( 'type' 		=> 'tab', 'tabname' => __('Job Listings Pricing', 'appthemes') ),
	
	array(	'name' => __('Pricing Options', 'appthemes'), 'type' 		=> 'title','desc' 		=> '', 'id' 		=> '' ),

	array(  
		'name' => __('Job Listing Fee','appthemes'),
		'desc' 		=> sprintf(__('Default job listing fee. Not used if you define <a href="%s">job packs</a>','appthemes'), 'admin.php?page=jobpacks'),
		'tip' 		=> __('Enter a numeric value, do not include currency symbols. Leave blank to enable free listings.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_listing_cost',
		'css' 		=> 'min-width:75px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Allow Job Relisting','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This enables an option for your customers to relist their job posting when it has expired.','appthemes'),
		'id' 		=> $app_abbr.'_allow_relist',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes'),
		)
	),

	array(  
		'name' => __('Re-Listing Fee','appthemes'),
		'desc' 		=> 'Default re-listing fee. Not used if you define <a href="admin.php?page=jobpacks">job packs</a>',
		'tip' 		=> __('Enter a numeric value, do not include currency symbols. Leave blank to enable free re-listings.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_relisting_cost',
		'css' 		=> 'min-width:75px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Featured Job Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the additional amount you will charge visitors to post a featured job on your site. A featured job appears at the top of the category. Leave this blank if you do not want to offer featured ads.','appthemes'),
		'id' 		=> $app_abbr.'_cost_to_feature',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(
		'name' => __('Symbol Position', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies place the symbol on the right side vs the left. Select how you would like your currency symbol to be displayed.','appthemes'),
		'id' => $app_abbr.'_curr_symbol_pos',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => '',
		'type' => 'select',
		'options' => array(  'left'         => __('Left of Currency ($100)', 'appthemes'),
							 'left_space'   => __('Left of Currency with Space ($ 100)', 'appthemes'),
							 'right'        => __('Right of Currency (100$)', 'appthemes'),
							 'right_space'  => __('Right of Currency with Space (100 $)', 'appthemes'))),
							 
	array(
		'name' => __('Thousands separator', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies use a decimal point instead of a comma.','appthemes'),
		'id' => $app_abbr.'_curr_thousands_separator',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => 'comma',
		'type' => 'select',
		'options' => array(  'comma'         => __('Comma', 'appthemes'),
							 'decimal'   => __('Decimal', 'appthemes'),
		)),
	
	array(
		'name' => __('Decimal separator', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies use a comma instead of a decimal point.','appthemes'),
		'id' => $app_abbr.'_curr_decimal_separator',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => 'decimal',
		'type' => 'select',
		'options' => array(  'comma'         => __('Comma', 'appthemes'),
							 'decimal'   => __('Decimal', 'appthemes'),
		)),
		

	array(  
		'name' => __('Collect Payments in', 'appthemes'),
		'desc' 		=> sprintf( __("See the list of supported <a target='_new' href='%s'>PayPal currencies</a>.", 'appthemes'), 'https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_intro-outside' ),
		'tip' 		=> __('This is the currency you want to collect payments in. It applies mainly to PayPal payments since other payment gateways accept more currencies. If your currency is not listed then PayPal currently does not support it.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_paypal_currency',
		'css' 		=> 'min-width:200px;',
		'vis' 		=> '',
		'js' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array( 
			'USD' => __('US Dollars (&#36;)', 'appthemes'),
			'EUR' => __('Euros (&euro;)', 'appthemes'),
			'GBP' => __('Pounds Sterling (&pound;)', 'appthemes'),
			'AUD' => __('Australian Dollars (&#36;)', 'appthemes'),
			'BRL' => __('Brazilian Real (&#36;)', 'appthemes'),
			'CAD' => __('Canadian Dollars (&#36;)', 'appthemes'),
			'CZK' => __('Czech Koruna', 'appthemes'),
			'DKK' => __('Danish Krone', 'appthemes'),
			'HKD' => __('Hong Kong Dollar (&#36;)', 'appthemes'),
			'HUF' => __('Hungarian Forint', 'appthemes'),
			'ILS' => __('Israeli Shekel', 'appthemes'),
			'JPY' => __('Japanese Yen (&yen;)', 'appthemes'),
			'MYR' => __('Malaysian Ringgits', 'appthemes'),
			'MXN' => __('Mexican Peso (&#36;)', 'appthemes'),
			'NZD' => __('New Zealand Dollar (&#36;)', 'appthemes'),
			'NOK' => __('Norwegian Krone', 'appthemes'),
			'PHP' => __('Philippine Pesos', 'appthemes'),
			'PLN' => __('Polish Zloty (z&#321;)', 'appthemes'),
			'SGD' => __('Singapore Dollar (&#36;)', 'appthemes'),
			'SEK' => __('Swedish Krona', 'appthemes'),
			'CHF' => __('Swiss Franc', 'appthemes'),
			'TWD' => __('Taiwan New Dollars', 'appthemes'),
			'THB' => __('Thai Baht', 'appthemes')
		)
	),

	array( 'type' => 'tabend'),
	
	array(  'type' 		=> 'tab', 'tabname' => __('Browse Resumes Pricing', 'appthemes')),
	
	array(	'name' => __('Subscription Options', 'appthemes'), 'type' 		=> 'title','desc' 		=> __('Control subscriptions for resume access', 'appthemes'), 'id' 		=> '' ),
	
	array(  
		'name' => __('Require active subscription to view resumes?','appthemes'),
		'desc' 		=> __('Enabling this option will block access to the resume section if the user does not have a subscription. Access will still be determined by your visibility settings on the settings page, e.g. if set to \'recruiters\', only recruiters will be able to subscribe. To subscribe the user must be logged in.','appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_require_subscription',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes'),
		)
	),
	
	array(  
		'name' => __('Subscription notice', 'appthemes'),
		'desc' 		=> __('Notice to display above the subscription button.','appthemes'),
         'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_subscription_notice',
		'css' 		=> 'width:500px;height:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Sorry, you do not have permission to browse and view resumes. To access our resume database please subscribe using the button below.'
	),
	
	array(  
		'name' => __('Recurring Payments','appthemes'),
		'desc' 		=> sprintf( __('Please note that the \'Automatic\' option will only work if you own a <a href="%s">Business or Premier PayPal account</a>. <br/>Please check your PayPal account type before setting this option.','appthemes'), 'https://www.paypal.com/pdn-recurring?bn_r=o' ),
		'tip' 		=> __('<strong>Automatic:</strong> Subscriptions are managed automatically by PayPal (requires a Business or Premier Account).<br/><strong>Manual:</strong> Users make timed payments for the trial or subscription period.','appthemes'),
		'id' 		=> $app_abbr.'_resume_subscr_recurr_type',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'manual',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'manual' => __('Manual (Standard Accounts)', 'appthemes'),
			'auto'  => __('Automatic (Business/Premier Accounts)', 'appthemes'),			
		)
	),		

	array(  
		'name' => __('Resume Access Subscription Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the amount you want to charge job listers access to the resume database.','appthemes'),
		'id' 		=> $app_abbr.'_resume_access_cost',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Subscription Length', 'appthemes'),
		'desc' 		=> __('Enter an integer. This length is also affected by the unit below.', 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_access_length',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '1'
	),
	
	array(  
		'name' => __('Subscription Unit', 'appthemes'),
		'desc' 		=> __("Select a unit for the subscription period.",'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_access_unit',
		'css' 		=> 'width:100px;',
		'std' 		=> 'M',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'M' => __('Months', 'appthemes'),
			'D'  => __('Days', 'appthemes'),
			'W'  => __('Weeks', 'appthemes'),
			'Y'  => __('Years', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Allow trial?','appthemes'),
		'desc' 		=> __('Enabling a trial lets you charge more or less during the first billing period.','appthemes'),
		'tip' 		=> __('If using manual recurring payments this is an additional option that allow users to trial the subscription service before paying for a full subscription.','appthemes'),
		'id' 		=> $app_abbr.'_resume_allow_trial',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes'),
		)
	),
	
	array(  
		'name' => __('Resume Access Trial Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the amount you want to charge job listers access to the resume database for their first billing term. Leave blank for free trial.','appthemes'),
		'id' 		=> $app_abbr.'_resume_trial_cost',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Trial Length', 'appthemes'),
		'desc' 		=> __('Enter an integer. This length is also affected by the unit below.', 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_trial_length',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '1'
	),
	
	array(  
		'name' => __('Trial Unit', 'appthemes'),
		'desc' 		=> __("Select a unit for the trial period.",'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_trial_unit',
		'css' 		=> 'width:100px;',
		'std' 		=> 'M',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'M' => __('Months', 'appthemes'),
			'D'  => __('Days', 'appthemes'),
			'W'  => __('Weeks', 'appthemes'),
			'Y'  => __('Years', 'appthemes')
		)
	),
	
	array( 'type' => 'tabend')

);

$options_integration = array (

	array(  'type' 		=> 'tab', 'tabname' => __('Indeed.com', 'appthemes')),
	
	array(	'name' => __('Main Options', 'appthemes'), 'type' => 'title', 'desc' => '' ),
	
	array(  'name' => '<img src="'.get_bloginfo('template_directory').'/images/indeed-lg.png" />', 'type' 		=> 'logo' ),	
	
	array(  
		'name' => __('Publisher ID', 'appthemes'),
                'desc' 		=> sprintf( __("Sign up for a free <a target='_new' href='%s'>Indeed.com account</a> to get a publisher ID.",'appthemes'), 'https://ads.indeed.com/jobroll/' ),
		'tip' 		=> __('Enter your Indeed publisher ID (i.e. 4247835648699281).','appthemes'),
		'id' 		=> $app_abbr.'_indeed_publisher_id',
		'css' 		=> 'min-width:350px;',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),
	
	array(	'name' => __('Queries', 'appthemes'), 'type' => 'title', 'desc' => '' ),	
	
	array(  
		'name' => __('Pull x indeed jobs', 'appthemes'),
		'desc' 		=> __('Enter the aproximate number of indeed jobs you want to pull from Indeed.', 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_indeed_front_page_count',
		'css' 		=> 'width:75px;',
		'std' 		=> '5',
		'req' 		=> '',
		'vis' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text'
	),

	array(  
		'name' 		=> __('Site Type', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Choose whether to pull jobs from job boards, direct employers or both.', 'appthemes'),
		'id' 		=> $app_abbr.'_indeed_site_type',		
		'css' 		=> 'width:150px;',
		'std' 		=> 'relevance',
		'req' 		=> '',
		'vis' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'all'		=> __('All', 'appthemes'),
			'jobsite'	=> __('Job Sites', 'appthemes'),
			'employer' 	=> __('Direct Employers', 'appthemes'),
		)		
	),
		
	array(  
		'name' 		=> __('Sort by', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('The sort order of the organic jobs.', 'appthemes'),
		'id' 		=> $app_abbr.'_indeed_sort_order',
		'css' 		=> 'width:150px;',
		'std' 		=> 'relevance',
		'req' 		=> '',
		'vis' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'relevance' => __('Relevance (default)', 'appthemes'),
			'date' 		=> __('Date', 'appthemes'),
		)		
	),		
	
	array(  
		'name' => __('Job listings queries', 'appthemes'),
		'desc' 		=> sprintf( __("Setup your queries and category mappings to pull in Indeed.com job listings. Each query must be in the following format:<br/><code>keyword [ OR keyword... ]|country|job type|location (optional, post code or city)</code>.
									<br/><br/><strong>Examples:</strong>
									<br/><code>web designer|GB|fulltime</code> Retrieves Full-Time Web Design Jobs in the UK
									<br/><code>web designer OR web developer|GB|fulltime</code> Retrieves Full-Time Web Design OR Web Development Jobs in the UK									
									<br/><br/>One per line. By default all full-time and part-time jobs are shown from the US. For available country codes and other parameters, see the <a target='_new' href='%s'>Indeed.com XML Feed Guide</a>.
									<br/><br/>
									<strong>Note:</strong>
									<br/>For the best results, you should use the following job types: 
									<br/><code>fulltime, parttime, contract, internship, temporary</code>
									<br/><br/>Some job types may need to be mapped to match your JobRoller job types slugs. See more details on the <em>Mappings</em> option, below.																		
									",'appthemes'), 'https://ads.indeed.com/jobroll/xmlfeed' ),
		'tip' 		=> __('These queries are used to retrieve relevant jobs to your website and are used differently for frontpage, search and filters: 
					   <br/><br/><strong>Frontpage:</strong> All your queries data will be used to pull relevant jobs as there are no user search of filters criteria. 
					   <br/><br/><strong>Search:</strong> Dynamically uses your queries data depending on the user search. For example, if the user is searching jobs by keyword, your queries keywords will be skipped in favour of the user\'s. It will use all the other queries information like job type or location. 
					   <br><br/><strong>Filters:</strong> Dynamically uses your queries data depending on the user filter. For example, when users filter jobs by job type, your queries job types will be skipped in favour of the user selected job type. This means that even if you only set queries for two job types
					   users can get results from any filterable job type.
					   <br/><br/>Each query will be ran and job listings will be merged together and displayed. Do not add too many queries since this will slow your site down significantly.','appthemes'),
		'id' 		=> $app_abbr.'_front_page_indeed_queries',
		'css' 		=> 'width:500px;height:150px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),		

	
	array(	'name' => __('Mappings', 'appthemes'), 'type' => 'title', 'desc' => '' ),	
	
	array(  
		'name' => __('Job types mapping', 'appthemes'),
		'desc' 		=> __("Indeed reconizes the following job types (slugs): <code>fulltime, parttime, contract, internship, temporary</code>.
							</br><br/>If you use different slugs, map each one with the respective Indeed slug in the following format <code>your-slug|indeed-slug</code>.
							<br><br/>Examples: 
							<br/><code>freelance|contract</code>
							<br/><code>temps-partiel|parttime</code>
							<br/><code>tiempo-parcial|parttime</code>
							<br/><code>full-time|fulltime</code>
						",'appthemes'),
		'tip' 		=> __('Mappings are used to allow JobRoller to relate your job types with Indeed\'s job types, on each job query, or when users browse jobs by job type, on the sidebar widget.', 'appthemes'),
		'id' 		=> $app_abbr.'_indeed_jtypes_other',
		'css' 		=> 'width:500px;height:150px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),		
	
	array(	'name' => __('Styling', 'appthemes'), 'type' => 'title', 'desc' => '' ),

	array(  
		'name' 		=> __('Sponsored Jobs Class', 'appthemes'),
        'desc' 		=> __('Choose the CSS class that should be applied to sponsored jobs (these jobs generate revenue on a CPC basis).<br/>You can also style these types of jobs using the <code>ty_indeed_sponsored</code> class.','appthemes'),
		'tip' 		=> __('You can style these type of jobs to give them better visibility.','appthemes'),				
		'id' 		=> $app_abbr.'_indeed_job_type_sponsored',
		'css' 		=> 'min-width:150px;',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> 'job-featured',
		'vis' 		=> ''
	),	
	
	array(	'name' => __('Display', 'appthemes'), 'type' => 'title', 'desc' => '' ),
	
	array(  
		'name' => __('Show indeed results on the front-page?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This option will dynamically pull in jobs from indeed on your front page.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_front_page',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes'),
		)
	),
		
	array(  
		'name' => __('Show indeed results when browsing?','appthemes'),

		'desc' 		=> sprintf (__("Only jobs matching your job listings queries are returned when browsing with the sidebar widget. <br/><br/>Adding <code>Design|GB|fulltime</code> to your job listings queries will return <code>fulltime</code> / <code>design</code> jobs when users browse <strong>Design</strong> jobs.
						<br/><br/><strong>Note:</strong> If you want to allow your visitors to browse jobs from any category (Job Type, Job Category, Job Salary, Date), without yet having published jobs on those categories (usually hidden), you can enable the <em>Show Empty Categories</em> option on the <a href='%s'>General Options</a> page.
						",'appthemes'), 'admin.php?page=settings' ),									
		'tip' 		=> __('Enable this option to pull in jobs from Indeed when users browse jobs using the sidebar widget (Job Type, Job Category, Job Salary*, Post Date). <br/><br/><strong>(*) </strong> Indeed Job Salary browsing is ony available in some countries','appthemes'),
		'id' 		=> $app_abbr.'_indeed_all_listings',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes'),
		)
	),
		
	array(  
		'name' => __('Show indeed results when searching','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This option will dynamically pull in search results from indeed when your job board has no results.','appthemes'),
		'id' 		=> $app_abbr.'_dynamic_search_results',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' 		=> __('All the time', 'appthemes'),
			'noresults' => __('Only when no local results are found', 'appthemes'),
			'no' 		=> __('Never', 'appthemes'),
		)
	),

	array(	'name' => __('Caching', 'appthemes'), 'type' => 'title', 'desc' => '' ),

	array(  
		'name' 		=> __('Frontpage Results Duration','appthemes'),
		'desc' 		=> __('Only enter numeric values (in seconds).<code>i.e: 3600 = 1 hour</code>.<br/>Leave blank to disable caching.','appthemes'),
		'tip' 		=> __('To speed up Indeed frontpage loading, you can cache the results for a set period of time. Results will be refreshed when this period expires.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_frontpage_cache',
		'css' 		=> 'width:75px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
	),
	
	array( 'type' 		=> 'tabend')

);

// admin options for the job packs page
$options_job_packs = array (

	array( 'type' 		=> 'tab', 'tabname' => __('Job Packs', 'appthemes') ),
	
	array(	'name' => __('General Options', 'appthemes'), 'type' => 'title','desc' 	=> '', 'id' => '' ),

	array(  
		'name' => __('Enable Purchase from Dashboard','appthemes'),
		'desc' 		=> 'Enable this option to allow job listers to purchase job packs from their dashboard.',
		'tip' 		=> __('This option enables job listers to purchase job packs at anytime without submiting jobs, first. 
						  A new <em>Buy</em> button will optionally be available on the job packs widget.','appthemes'),
		'id' 		=> $app_abbr.'_packs_dashboard_buy',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		),		
		'std' 		=> 'no'
	),

	array(
		'name' => __('Display Job Categories','appthemes'),
		'desc' 		=> 'Enable this option to display the related job categories in each Job Pack.',
		'tip' 		=> __('You should enable this option if you create Job Packs for specific job categories. Job Listers will be able to see in which job categories the Packs will be available.','appthemes'),
		'id' 		=> $app_abbr.'_packs_job_categories',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		),
		'std' 		=> 'no'
	),

	array(
		'name' => __('Free Packs Use Limit','appthemes'),
		'desc' 		=> 'Set the maximum number of times a Free job pack can be selected by each job lister. Leave blank for unlimited times.',
		'tip' 		=> __('This option can be useful to enable job listers to trial the site features using a Free Pack.','appthemes'),
		'id' 		=> $app_abbr.'_packs_free_limit',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std'		=> '',
	),

	array( 'type' 		=> 'tabend'),
	
);	

// apply filters to allow add additional options 
$options_integration = apply_filters('jr_filter_integration_values', $options_integration );


// pull in the payment gateway options
// this is included separately so it's easy to drop in new payment
// plugins and add-ons without having to touch the core code
if (file_exists(TEMPLATEPATH . '/includes/gateways/admin-gateway-values.php')) include_once (TEMPLATEPATH . '/includes/gateways/admin-gateway-values.php');