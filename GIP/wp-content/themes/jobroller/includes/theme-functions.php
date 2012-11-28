<?php
/**
 * JobRoller core theme functions
 * This file is the backbone and includes all the core functions
 * Modifying this will void your warranty and could cause
 * problems with your instance of JR. Proceed at your own risk!
 *
 *
 * @package JobRoller
 * @author AppThemes
 * @url http://www.appthemes.com
 *
 */

load_theme_textdomain('appthemes');

define('THE_POSITION', 3);
define('FAVICON', get_bloginfo('template_directory').'/images/job_icon.png');

// setup the custom post types and taxonomies as constants
// do not modify this after installing or it will break your theme!
// started using in places in 1.4. slowly migrate over with the next version
define('APP_POST_TYPE', 'job_listing');
define('APP_POST_TYPE_RESUME', 'resume');
define('APP_TAX_CAT', 'job_cat');
define('APP_TAX_TAG', 'job_tag');
define('APP_TAX_TYPE', 'job_type');
define('APP_TAX_SALARY', 'job_salary');
define('APP_TAX_RESUME_SPECIALITIES', 'resume_specialities');
define('APP_TAX_RESUME_GROUPS', 'resume_groups');
define('APP_TAX_RESUME_LANGUAGES', 'resume_languages');
define('APP_TAX_RESUME_CATEGORY', 'resume_category');
define('APP_TAX_RESUME_JOB_TYPE', 'resume_job_type');

// Define the db tables we use
$jr_db_tables = array('jr_job_packs', 'jr_customer_packs', 'jr_orders', 'jr_alerts');

// register the db tables
foreach ( $jr_db_tables as $jr_db_table ) 
	scb_register_table($jr_db_table);

// Classes
get_template_part('includes/classes/packs.class');
get_template_part('includes/classes/orders.class');

// Include functions

// Payment
get_template_part('includes/gateways/paypal');

// Logging
get_template_part('includes/theme-log');
$jr_log = new jrLog();

// Framework functions
get_template_part('includes/theme-hooks');
get_template_part('includes/appthemes-functions');

// Theme functions
get_template_part('includes/theme-sidebars');
get_template_part('includes/theme-support');
get_template_part('includes/theme-security');
get_template_part('includes/theme-comments');
get_template_part('includes/theme-header');
get_template_part('includes/theme-footer');
get_template_part('includes/theme-widgets');
get_template_part('includes/theme-emails');
get_template_part('includes/theme-geolocation');
get_template_part('includes/theme-actions');
require( get_template_directory() . '/includes/theme-alerts.php' );
get_template_part('includes/theme-cron');
get_template_part('includes/indeed/theme-indeed');
get_template_part('includes/theme-enqueue');
get_template_part('includes/theme-stats');
get_template_part('includes/theme-users');
get_template_part('includes/theme-resumes');

// include the new custom post type and taxonomy declarations.
// must be included on all pages to work with site functions
get_template_part('includes/admin/admin-post-types');

// Front-end includes
if (!is_admin()) :
    get_template_part('includes/countries');
    get_template_part('includes/theme-login');
    get_template_part('includes/forms/submit-job/submit-job-process');
    get_template_part('includes/forms/submit-job/submit-job-form');
    get_template_part('includes/forms/edit-job/edit-job-process');
    get_template_part('includes/forms/edit-job/relist-job-process');
    get_template_part('includes/forms/edit-job/edit-job-form');
    get_template_part('includes/forms/confirm-job/confirm-job-process');
    get_template_part('includes/forms/confirm-job/confirm-job-form');
    get_template_part('includes/forms/preview-job/preview-job-form');
    get_template_part('includes/forms/application/application-process');
    get_template_part('includes/forms/application/application-form');
    get_template_part('includes/forms/filter/filter-process');
    get_template_part('includes/forms/filter/filter-form');
    get_template_part('includes/forms/share/share-form');
    get_template_part('includes/forms/login/login-form');
    get_template_part('includes/forms/login/login-process');
    get_template_part('includes/forms/register/register-form');
    get_template_part('includes/forms/register/register-process');
    get_template_part('includes/forms/forgot-password/forgot-password-form');
    get_template_part('includes/forms/submit-resume/submit-resume-process');
    get_template_part('includes/forms/submit-resume/submit-resume-form');
    get_template_part('includes/forms/resume/edit_parts');
	get_template_part('includes/forms/resume/contact_parts');
    get_template_part('includes/forms/seeker-prefs/seeker-prefs-form');
    get_template_part('includes/forms/seeker-prefs/seeker-prefs-process');
	get_template_part('includes/forms/seeker-alerts/seeker-alerts-form');
	require( get_template_directory() . '/includes/forms/seeker-alerts/seeker-alerts-process.php' );
	get_template_part('includes/forms/subscribe-resumes/subcribe-resumes-form');
	get_template_part('includes/forms/lister-packs/lister-packs-form');		
	require( get_template_directory() . '/includes/forms/lister-packs/lister-packs-process.php' );	
endif;

// Admin Only Functions
if (is_admin()) :
    get_template_part('includes/admin/admin-enqueue');
    get_template_part('includes/admin/admin-options');
    get_template_part('includes/admin/write-panel');
    get_template_part('includes/admin/install-script');
endif;


// return the translated role display name
function jr_translate_role( $role ) {
	global $wp_roles;
	
	$roles = $wp_roles->get_names();
	
	$translated_roles = array(
		'job_lister' => __('Job Lister','appthemes'),
		'job_seeker' => __('Job Seeker','appthemes'),
		'recruiter'  => __('Recruiter','appthemes'),
	);

	if ( !array_key_exists($role, $translated_roles) ) return $roles[ $role ];
	
	return $translated_roles[ $role ];
	
}

################################################################################
// Fix paging on author page
################################################################################

function custom_post_author_archive( &$query )
{
    if ( $query->is_author )
        $query->set( 'post_type', array('resume', 'job_listing') );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' );
}
add_action( 'pre_get_posts', 'custom_post_author_archive' );

################################################################################
// Fix location encoding in urls
################################################################################

function location_query_arg( $link ) {

	if (isset($_GET['location']) && $_GET['location']) :

		$link = add_query_arg('location', urlencode( utf8_uri_encode( $_GET['location'] ) ), $link);

	endif;

	return $link;
}
add_filter('get_pagenum_link', 'location_query_arg');

################################################################################
// Check theme is installed correctly
################################################################################

add_action('admin_notices', 'check_jr_environment');

function check_jr_environment() {
	$errors = array();

	$files = array(
		'includes/gateways/paypal.php',
		'includes/theme-cron.php',
		'includes/theme-login.php',
		'includes/theme-sidebars.php',
		'includes/theme-support.php',
		'includes/theme-comments.php',
		'includes/forms/application/application-process.php',
		'includes/forms/application/application-form.php',
		'includes/forms/filter/filter-process.php',
		'includes/forms/filter/filter-form.php',
		'includes/forms/share/share-form.php',
		'includes/theme-actions.php',
		'includes/admin/admin-options.php',
		'includes/admin/write-panel.php'
	);

	foreach ($files as $file) {
		if (!is_readable(TEMPLATEPATH.'/'.$file)) $errors[] = $file.__(' is not readable or does not exist - check file permissions.','appthemes');
	}

	if (isset($errors) && sizeof($errors)>0) {
		echo '<div class="error" style="padding:10px"><strong>'.__('JobRoller theme errors:','appthemes').'</strong>';
		foreach ($errors as $error) {
			echo '<p>'.$error.'</p>';
		}
		echo '</div>';
	}
}



// Buffer the output so headers work correctly
add_action('init', 'buffer_the_output');

function buffer_the_output() {
	ob_start();
}

// count taxonomy terms
function jr_tax_has_terms( $taxonomy ) {
	return (int) get_terms( $taxonomy, array( 'hide_empty' => false, 'fields' => 'count' ) );
}

// Add custom post types to the Main RSS feed
function jr_rss_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type'])) :
		$qv['post_type'] = array('post', 'job_listing');
	endif;
	return $qv;
}
add_filter('request', 'jr_rss_request');

function jr_rss_pre_get_posts($query) {
	if ($query->is_feed) $query->set('post_status','publish');
	return $query;
}
add_filter('pre_get_posts', 'jr_rss_pre_get_posts');



// get the custom taxonomy array and loop through the values
function jr_get_custom_taxonomy($post_id, $tax_name, $tax_class) {
    $tax_array = get_terms( $tax_name, array( 'hide_empty' => '0' ) );
    if ($tax_array && sizeof($tax_array) > 0) {
        foreach ($tax_array as $tax_val) {
            if ( is_object_in_term( $post_id, $tax_name, array( $tax_val->term_id ) ) ) {
                echo '<span class="'.$tax_class . ' '. $tax_val->slug.'">'.$tax_val->name.'</span>';
                break;
            }
        }
    }
}

// deletes all the database tables
function jr_delete_db_tables() {
    global $wpdb, $jr_db_tables;

    foreach ($jr_db_tables as $key => $value) :

		scb_uninstall_table($value);

        printf(__("Table '%s' has been deleted.", 'appthemes'), $value);
        echo '<br/>';

    endforeach;
	
	scb_uninstall_table('app_pop_daily');
	_e("Table 'app_pop_daily' has been deleted.", 'appthemes');
	
	scb_uninstall_table('app_pop_total');
	_e("Table 'app_pop_total' has been deleted.", 'appthemes');
	
}

// deletes all the theme options from wp_options
function jr_delete_all_options() {
    global $wpdb;

    $sql = "DELETE FROM ". $wpdb->options
          ." WHERE option_name like 'jr_%'";
    $wpdb->query($sql);

    echo __("All JobRoller options have been deleted.", 'appthemes');
}

// Define Nav Bar Locations
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
	'top' => __( 'Top Bar Navigation' ),
) );

// Function to output pagination
if (!function_exists('jr_paging')) {
function jr_paging() {

	?>
	<div class="clear"></div>
    <div class="paging">
        <?php if(function_exists('wp_pagenavi')) {
                wp_pagenavi();
        } else { ?>

            <div style="float:left; margin-right:10px"><?php previous_posts_link(__('&laquo; Previous page', 'appthemes')) ?></div>
            <div style="float:left;"><?php next_posts_link(__('Next page &raquo;', 'appthemes')) ?></div>

        <?php } ?>
        <div class="top"><a href="#top" title="<?php _e('Back to top', 'appthemes'); ?>"><?php _e('Top &uarr;', 'appthemes'); ?></a></div>
    </div>
    <?php
}
}


// Function to get theme image directory (so we can support sub themes)
if (!function_exists('get_template_image_url')) {
function get_template_image_url($image = '') {
    $theme = str_replace('.css','', get_option('jr_child_theme'));
    if ($theme && $theme!=='style-default') return get_bloginfo('template_url').'/images/'.$theme.'/'.$image;
    else return get_bloginfo('template_url').'/images/'.$image;
}
}


// Remaining days function
if (!function_exists('jr_remaining_days')) {
function jr_remaining_days($post) {
    $date = get_post_meta($post->ID, '_expires', true);

    if ($date) :

    $days = ceil(($date-strtotime('NOW'))/86400);

	if ($days==1) return $days.' '.__('day','appthemes');
	if ($days<1) return __('Expired', 'appthemes');
	return $days.' '.__('days','appthemes');

	endif;

	return '-';
}
}

// Expiry check function
if (!function_exists('jr_check_expired')) {
function jr_check_expired($post) {
    $date = get_post_meta($post->ID, '_expires', true);

   	if ($date) if ( $date < strtotime('NOW') ) return true;

    return false;
}
}


// Expired Message
if (!function_exists('jr_expired_message')) {
function jr_expired_message($post) {
	$expired = jr_check_expired($post);
	if ($expired) :
		?><p class="expired"><?php _e('<strong>NOTE:</strong> This job listing has expired and may no longer be relevant!','appthemes'); ?></p><?php
	endif;
}
}

// Filter out expired posts
function jr_job_not_expired($where = '') {

	global $wpdb;

	// First we need to find posts that are expired by looking at the custom value
	$exlude_ids = $wpdb->get_col($wpdb->prepare("
		SELECT      postmeta.post_id
		FROM        $wpdb->postmeta postmeta
		WHERE       postmeta.meta_key = '_expires'
		            and postmeta.meta_value < '%s'
	", strtotime('NOW')));

	if (sizeof($exlude_ids)>0) $where .= " AND ID NOT IN (".implode(',', $exlude_ids).") ";

	return $where;
}
function remove_jr_job_not_expired() {
	remove_filter('posts_where', 'jr_job_not_expired');
}
add_action('get_footer', 'remove_jr_job_not_expired');


// Get Page URL
if ( !function_exists('jr_get_current_url') ) {
function jr_get_current_url($url = '') {

	if (is_front_page() || is_search() || is_front_page()) :
		return trailingslashit(get_bloginfo('wpurl'));
	elseif (is_category()) :
		return trailingslashit(get_category_link(get_cat_id(single_cat_title("", false))));
	elseif (is_tax()) :

		$job_cat = get_query_var('job_cat');
		$job_type = get_query_var('job_type');

		if (isset($job_cat) && $job_cat) :
			$slug = $job_cat;
			return trailingslashit(get_term_link( $slug, 'job_cat' ));
		elseif (isset($job_type) && $job_type) :
			$slug = $job_type;
			return trailingslashit(get_term_link( $job_type, 'job_type' ));
		endif;

	endif;
	return trailingslashit($url);
}
}

// Get currency symbol function
if (!function_exists('jr_get_currency_symbol')) {
function jr_get_currency_symbol( $currency='' ) {

	if (!$currency)	$currency = get_option('jr_jobs_paypal_currency');
    $currency_symbol = '';

    switch ($currency) :
        case 'GBP':
           $currency_symbol = '&pound;';
        break;
        case 'JPY':
            $currency_symbol = '&yen;';
        break;
        case 'EUR':
            $currency_symbol = '&euro;';
        break;
        case 'PLN' :
        	$currency_symbol = 'zł';
        break;
		case 'THB' :
			$currency_symbol = 'บาท';
		break;
		case 'SEK':
            $currency_symbol = 'kr;';
        break;
        default:
            $currency_symbol = '$';
        break;
    endswitch;

    return $currency_symbol;
}
}
// Get currency function
if (!function_exists('jr_get_currency')) {
function jr_get_currency( $amount = '' ) {
    $currency = get_option('jr_jobs_paypal_currency');
    $currency_pos = get_option('jr_curr_symbol_pos');
	$currency_symbol = jr_get_currency_symbol($currency);

    if ($amount) :

    	$amount_string = '';

    	$thousands = (get_option('jr_curr_thousands_separator')=='decimal') ? '.' : ',';
    	$decimal = (get_option('jr_curr_decimal_separator')=='comma') ? ',' : '.';

    	$amount = number_format($amount, 2, $decimal, $thousands);

    	switch ($currency_pos) :
    		case 'left_space' :
    			$amount_string = '{currency} '.$amount;
    		break;
    		case 'right' :
    			$amount_string = $amount.'{currency}';
    		break;
    		case 'right_space' :
    			$amount_string = $amount.' {currency}';
    		break;
    		default:
    			$amount_string = '{currency}'.$amount;
    		break;
    	endswitch;

    	return str_replace('{currency}', $currency_symbol, $amount_string);

    else :
    	return $currency_symbol;
    endif;

    return;
}
function jr_get_currency_in_position( $position = 'left' ) {
    $currency = get_option('jr_jobs_paypal_currency');
    $currency_pos = get_option('jr_curr_symbol_pos');
	$currency_symbol = jr_get_currency_symbol($currency);

    switch ($currency_pos) :
		case 'left_space' :
			if ($position=='left') return $currency_symbol.' ';
		break;
		case 'right' :
			if ($position=='right') return $currency_symbol;
		break;
		case 'right_space' :
			if ($position=='right') return ' '.$currency_symbol;
		break;
		default:
			if ($position=='left') return $currency_symbol;
		break;
	endswitch;

    return $currency_symbol;
}
}


// get the visitor IP so we can include it with the job submission
if (!function_exists('jr_getIP')) {
function jr_getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

	$ip = ( $ip == '::1' ? '127.0.0.1' : $ip );

    return $ip;
}
}

// tinyMCE text editor
if (!function_exists('jr_tinymce')) {
function jr_tinymce($width='', $height='') {
?>
<script type="text/javascript">
    <!--

	tinyMCEPreInit = {
		base : "<?php echo includes_url('js/tinymce'); ?>",
		suffix : "",
		mceInit : {
			mode : "specific_textareas",
			editor_selector : "mceEditor",
			theme : "advanced",
			plugins: "paste",
			skin : "default",
	        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
	        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,cleanup,code,|,forecolor,backcolor,|,media",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			content_css : "<?php echo get_bloginfo('stylesheet_directory'); ?>/style.css",
			languages : 'en',
			disk_cache : true,
			width : "<?php echo $width; ?>",
			height : "<?php echo $height; ?>",
			language : 'en'
		},
		load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
	};

	(function(){var t=tinyMCEPreInit,sl=tinymce.ScriptLoader,ln=t.mceInit.language,th=t.mceInit.theme;sl.markDone(t.base+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'_dlg.js');})();
	tinyMCE.init(tinyMCEPreInit.mceInit);

    -->
</script>
<?php
}
}

// get the date/time of the post
if (!function_exists('jr_ad_posted')) {
function jr_ad_posted($m_time) {
    //$t_time = get_the_time(__('Y/m/d g:i:s A'));
    $time = get_post_time('G', true);
    $time_diff = time() - $time;

    if ( $time_diff > 0 && $time_diff < 24*60*60 )
            $h_time = sprintf( __('%s ago', 'appthemes'), human_time_diff( $time ) );
    else
            $h_time = mysql2date(get_option('date_format'), $m_time);
    echo $h_time;
}
}

// Filters
function custom_excerpt($text) {
	global $post;
	return str_replace(' [...]', '&hellip; <a href="'. get_permalink($post->ID) . '" class="more">' . __('read more','appthemes') . '</a>', $text);
}
add_filter('the_excerpt', 'custom_excerpt');

// search on custom fields
function custom_search_join($join) {
    if ( is_search() && isset($_GET['s'])) {
        global $wpdb;
       $join = " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
    }
    return($join);
}
// search on custom fields
function custom_search_groupby($groupby) {
    if ( is_search() && isset($_GET['s'])) {
        global $wpdb;
        $groupby = " $wpdb->posts.ID ";
    }
    return($groupby);
}
// search on custom fields
function custom_search_where($where) {
    global $wpdb;
    $old_where = $where;
    if (is_search() && isset($_GET['s']) && !isset($_GET['resume_search'])) {
		// add additional custom fields here to include them in search results
        $customs = array('_Company', 'geo_address', '_CompanyURL', 'geo_short_address', 'geo_country', 'geo_short_address_country');
        $query = '';
        $var_q = stripslashes($_GET['s']);
        preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
        $search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);

        $n = '%';
        $searchand = '';
        foreach((array)$search_terms as $term) {
            $term = addslashes_gpc($term);
            $query .= "{$searchand}(";
            $query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
            foreach($customs as $custom) {
                $query .= " OR (";
                $query .= "($wpdb->postmeta.meta_key = '$custom')";
                $query .= " AND ($wpdb->postmeta.meta_value  LIKE '{$n}{$term}{$n}')";
                $query .= ")";
            }
            $query .= ")";
            $searchand = ' AND ';
        }
        $term = $wpdb->escape($var_q);
        $where .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $where .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";

        if (!empty($query)) {
            $where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->posts.post_type = 'job_listing')";
        }
    } else if (is_search() && isset($_GET['s'])) {
    	// add additional custom fields here to include them in search results
        $customs = array(
        	'_desired_position',
        	'_resume_websites',
        	'_experience',
        	'_education',
        	'_skills',
        	'_desired_salary',
        	'_email_address',
        	'geo_address',
        	'geo_country'
        );
        $query = '';
        $var_q = stripslashes($_GET['s']);
        preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
        $search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);

        $n = '%';
        $searchand = '';
        foreach((array)$search_terms as $term) {
            $term = addslashes_gpc($term);
            $query .= "{$searchand}(";
            $query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
            foreach($customs as $custom) {
                $query .= " OR (";
                $query .= "($wpdb->postmeta.meta_key = '$custom')";
                $query .= " AND ($wpdb->postmeta.meta_value  LIKE '{$n}{$term}{$n}')";
                $query .= ")";
            }
            $query .= ")";
            $searchand = ' AND ';
        }
        $term = $wpdb->escape($var_q);
        $where .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $where .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";

        if (!empty($query)) {
            $where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->posts.post_type = 'resume')";
        }
    }
    return($where);
}
if (!is_admin()) :
	// search on custom fields
	add_filter('posts_join', 'custom_search_join');
	add_filter('posts_where', 'custom_search_where');
	add_filter('posts_groupby', 'custom_search_groupby');
endif;

// redirects a user to my jobs
if (!function_exists('redirect_myjobs')) {
function redirect_myjobs( $query_string = '' ) {
	$url = get_permalink(get_option('jr_dashboard_page_id'));
	if (is_array($query_string)) $url = add_query_arg( $query_string, $url );
    wp_redirect($url);
    exit();
}
}

// redirects a user to my profile
if (!function_exists('redirect_profile')) {
function redirect_profile( $query_string = '' ) {
	$url = get_permalink(get_option('jr_user_profile_page_id'));
	if (is_array($query_string)) $url = add_query_arg( $query_string, $url );
    wp_redirect($url);
    exit();
}
}

// Output errors
if (!function_exists('jr_show_errors')) {
function jr_show_errors( $errors, $id = '' ) {
	if ($errors && sizeof($errors)>0 && $errors->get_error_code()) :
		echo '<ul class="errors" id="'.$id.'">';
		foreach ($errors->errors as $error) {
			echo '<li>'.$error[0].'</li>';
		}
		echo '</ul>';
	endif;
}
}

if (!function_exists('let_to_num')) {
	function let_to_num($v){
		$l = substr($v, -1);
	    $ret = substr($v, 0, -1);
	    switch(strtoupper($l)){
	    case 'P':
	        $ret *= 1024;
	    case 'T':
	        $ret *= 1024;
	    case 'G':
	        $ret *= 1024;
	    case 'M':
	        $ret *= 1024;
	    case 'K':
	        $ret *= 1024;
	        break;
	    }
	    return $ret;
	}
}

// Check if current user can select free packs
if (!function_exists('jr_can_select_free_job_packs')):
function jr_can_select_free_job_packs($user_id) {
	global $wpdb, $app_abbr;

	if ( !get_option($app_abbr.'_packs_free_limit') ) return true;

	$limit = get_option($app_abbr.'_packs_free_limit');
	$total = $wpdb->get_var( $wpdb->prepare("SELECT count(1) as total FROM $wpdb->jr_job_packs packs, $wpdb->jr_customer_packs user_packs WHERE packs.id = user_packs.pack_id AND pack_cost = '' and user_id = %d", $user_id) );

	return ($total < $limit);
}
endif;

// Get job packs
if (!function_exists('jr_get_job_packs')):
function jr_get_job_packs( $pack_types = array(), $filter_by_job_cat = 'yes' ) {
	global $wpdb, $posted, $user_ID;

	$where = '';
	// block free packs if the use limit is reached - ignore limit with backend job packs
	if ( ( sizeof($pack_types) > 0 && !in_array('free', $pack_types) ) || ( !jr_can_select_free_job_packs($user_ID) && !is_admin() ) )
		$where = " WHERE pack_cost <> ''";

	$results = $wpdb->get_results("SELECT * FROM $wpdb->jr_job_packs {$where} ORDER by pack_order ASC");

	$results_by_cat = array();
	if ( $results && isset($posted['job_term_cat']) && $filter_by_job_cat == 'yes' ) :
	
			foreach ( $results as $result )
				if  ( empty($result->job_cats) || (!empty($result->job_cats) && in_array( $posted['job_term_cat'], explode(',', $result->job_cats) ) ) )
					 $results_by_cat[] = $result;
				
			// only output packs by category if there's at least one pack returned. Fallback and return all the packs, otherwise 	
			if ( isset($results_by_cat) && sizeof($results_by_cat) > 0 ) $results = $results_by_cat;
				
	endif;		
	
	return $results;
}
endif;

// Get user job packs
if (!function_exists('jr_get_user_job_packs')):
function jr_get_user_job_packs( $user_id = 0, $filter_by_job_cat = 'yes' ) {
	global $wpdb, $posted;

	if (!$user_id)
		$user_id = get_current_user_id();

	$results_by_cat = array();
	if ($user_id>0) {
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->jr_customer_packs WHERE user_id = %d AND (jobs_count+job_offers_count < jobs_limit+job_offers OR jobs_limit = 0) AND (pack_expires > NOW() OR pack_expires = NULL OR pack_expires = '0000-00-00 00:00:00') ORDER BY pack_order ASC", $user_id ) );

		if ( $results && isset($posted['job_term_cat']) && $filter_by_job_cat == 'yes' ) :
		
				foreach ( $results as $result )
					if  ( empty($result->job_cats) || ( !empty($result->job_cats) && in_array( $posted['job_term_cat'], explode(',', $result->job_cats) ) ) )
					 $results_by_cat[] = $result;
	
				$results = $results_by_cat;
				
		endif;		
			
		return $results;
	}
}
endif;


if (!function_exists('jr_get_user_job_packs_access')):
function jr_get_user_job_packs_access( $user_id = 0 ) {
	global $wpdb;

	if (!$user_id)
		$user_id = get_current_user_id();

	if ($user_id>0) : 
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT distinct pack_expires, access FROM $wpdb->jr_customer_packs WHERE user_id = %d AND (pack_expires > NOW() OR pack_expires = NULL OR pack_expires = '0000-00-00 00:00:00')", $user_id ) );
		 
		$pack_access = array();
		$pack_expires = 0;
		foreach ($results as $result):
			if (!empty($result->access)):
				$pack_access = array_merge( $pack_access, explode(',', $result->access) );
				if ($pack_expires < $result->pack_expires) $pack_expires = $result->pack_expires;
			endif;
		endforeach;

	else:
		return;
	endif;
	
	$pack = array ( 'access' => array_unique($pack_access), 'expires' => $pack_expires );
	return $pack;
}
endif;

/**
 * Display or return a single Job Pack on the backend/frontend
 *
 * @since 1.6.0
 * 
 * @param string $pack_type The job pack types to display. Possible types are: user|new 
 * @param object $pack The pack object 
 * @param string $default Values 1|null. Sets the current Pack as the default
 * @param boolean $echo Whether to echo or just return the Pack html string
 * @param array $display_options Array with the visible options on the Pack. Accepted values are 'class' => '<any class name>', categories' => 'no|multiple|all', 'order' => 'no|yes', 'selectable' => 'no|yes'
 * 								'no' 	   = never display the categories
 * 								'multiple' = always display the categories
 * 								'all' 	   = same as 'multiple' but displays 'All' instead of all the categories, when all selected  
 * @return html The job pack html output				
 */
if (!function_exists('jr_display_pack')):
function jr_display_pack( $pack_type, $pack, $default='', $echo = FALSE, $display_options = array() ) {
	global $app_abbr;

	$echo_pack_jobs = $echo_pack_duration_expire = $echo_pack_offers = $echo_pack_access = $echo_pack_job_cats = '';
	$select_pack_html = $categories_html = $operations_html = $pack_order_html = '';

	$display_defaults = array (
		'class'				=> '',	  																		// additional CSS class for the pack
		'categories'		=> get_option($app_abbr.'_packs_job_categories', 'no') == 'no' ? 'no' : 'all',	// the categories display type
		'order'				=> 'no',  																		// if set to 'yes' displays the Pack order
		'selectable'		=> 'yes', 																		// should the job pack be user selectable
	);
	$display_options = wp_parse_args( $display_options, $display_defaults);

	$echo_pack_class[] = $display_options['class'];
	$echo_pack_class[] = 'pack-id-' . $pack->id;

	switch ($pack_type): 
		case 'user':
			
			$echo_pack_class[] = 'user-pack';
			
			$pack->pack_description = '';
			$pack_remain_job_offers = $pack->job_offers - $pack->job_offers_count;
			$pack_remain_feat_job_offers = $pack->feat_job_offers - $pack->feat_job_offers_count;

			if ( !$pack->jobs_limit ):
				$jobs_count = __('Unlimited', 'appthemes');
				$pack->jobs_count = 9999;
			else :
				$jobs_count = $pack->jobs_limit - $pack->jobs_count . ( $pack_remain_job_offers > 0 ? ' (+'.$pack_remain_job_offers.__(' Free','appthemes').')' : '');
				$pack->jobs_count = $pack->jobs_limit - ($pack->jobs_count+$pack_remain_job_offers);
			endif;
			if ($pack_remain_feat_job_offers > $jobs_count) $pack_remain_feat_job_offers = $jobs_count;

			if ($pack->job_duration) $echo_pack_jobs .= sprintf(' %s <small>%s</small>', __(' lasting ', 'appthemes'), $pack->job_duration.__(' days' ,'appthemes') );					
			if ($pack->pack_expires) $echo_pack_duration_expire = sprintf('%s <small>%s</small>',__('Usable before ', 'appthemes'), mysql2date(get_option('date_format'), $pack->pack_expires));				

			$echo_pack_cost = __('Purchased','appthemes');
			$echo_pack_jobs = sprintf( '<small>%s</small> %s', $jobs_count , _n(' Job Remaining', ' Jobs Remaining', (int)$pack->jobs_count, 'appthemes'));
						
			$select_pack_html = sprintf(
					'<div class="job-pack-choose">
						<label>%s<input type="radio" name="job_pack" value="user_%s" %s /></label>
		    	  	 </div>', __('Choose This Pack!','appthemes'), esc_attr($pack->id), checked($default,1, FALSE)
			);

			break;

		default:

			$echo_pack_class[] = 'new-pack';

			$echo_pack_cost = $pack->pack_cost ? jr_get_currency($pack->pack_cost) : __('Free','appthemes');

			if ($pack->pack_duration) $echo_pack_duration_expire = sprintf('%s <small>%s</small>',__(' usable within ', 'appthemes'),$pack->pack_duration.__(' days', 'appthemes') );
			else $echo_pack_duration_expire =  __('Unlimited', 'appthemes');

			if ($pack->job_duration)$echo_pack_jobs .= sprintf('%s <small>%s</small>',__(' lasting ', 'appthemes'),$pack->job_duration.__(' days' ,'appthemes') );												

			$echo_pack_jobs = (!$pack->job_count ? __('Unlimited', 'appthemes') : sprintf( '<small>%s</small>', $pack->job_count . __(' Jobs', 'appthemes')) );

			$select_pack_html = sprintf(
					'<div class="job-pack-choose">
						<label>%s<input type="radio" name="job_pack" value="%s" %s /></label>
		    	  	 </div>',($pack->pack_cost>0?__('Buy This Pack!','appthemes'):__('Choose This Pack!','appthemes')), esc_attr($pack->id), checked($default,1, FALSE)
			);

		endswitch;


	### categories (optional)
	
	if ( $display_options['categories'] != 'no' ) {

		$job_cats = '';		
		$args = array (
			'fields'	 => 'ids',
			'hide_empty' => 0,
	 		'exclude'    => get_option('jr_featured_category_id'),
		);		
		$job_categories = get_terms( APP_TAX_CAT, $args); 
		
		if ( empty($pack->job_cats) ) $pack->job_cats = $job_categories;
		else if ( !is_array($pack->job_cats) ) $pack->job_cats = explode(',', $pack->job_cats );
		
		if ( $pack->job_cats && count($pack->job_cats) == count($job_categories) &&  $display_options['categories'] == 'all' ) 
			$echo_pack_job_cats = __('All', 'appthemes');
		else
			if ($pack->job_cats) foreach ( $pack->job_cats as $job_cat ) 
				$echo_pack_job_cats .= ($echo_pack_job_cats?', ':'') . '<a href="'.get_term_link( (int)$job_cat, APP_TAX_CAT ).'">'.get_term_by('id', (int)$job_cat, APP_TAX_CAT)->name.'</a>';

		$categories_html = sprintf(
				'<ul class="job-pack-details categories-list">
		        			<li class="job-pack-categories"><strong>%s</strong> %s</li>
		    	</ul>', __('Categories:','appthemes'), $echo_pack_job_cats 
		);

	}


	### offers
	
	$pack_offers = array (
					
		'job' 		=> array ( 
							'name'  	=>  __('Job Offers', 'appthemes'),
							'value' 	=>  $pack_type == 'user' ? $pack_remain_job_offers : $pack->job_offers,
							'limit' 	=>  $pack->job_offers ? $pack->job_offers : 0,
						),
						
		'feat_job' 	=> array (
							'name'  	=>  __('Feature', 'appthemes'),
							'value' 	=>  $pack_type == 'user' ? $pack_remain_feat_job_offers : $pack->feat_job_offers,
							'limit' 	=>  $pack->feat_job_offers ? $pack->feat_job_offers : 0,
					   )
	);
	
	$remain_offers = 0;
	foreach ($pack_offers as $key => $p_offer):

		$offer_used_class = $remain_text = '';
		if ( $p_offer['limit'] > 0 ):

			if ($p_offer['value'] == 0) { $offer_used_class = 'pack-offer used'; $p_offer['value'] = $p_offer['limit']; }
			elseif ($pack_type=='user') $remain_text = __(' Remaining','appthemes');

			if ( $key != 'feat_job' )
				$echo_pack_offers .= sprintf('%s <span class="pack-offer %s"><small>%s</small> %s%s</span>', ($echo_pack_offers?', ':''), $offer_used_class, $p_offer['value'], $p_offer['name'], $remain_text);
			else
				$echo_pack_offers .= sprintf('%s <span class="pack-offer %s">%s <small>%s</small> %s%s</span>', ($echo_pack_offers?', ':''), $offer_used_class, $p_offer['name'], $p_offer['value'], _n('Job for Free','Jobs for Free',$p_offer['value'],'appthemes'), $remain_text);

		endif;

		$remain_offers += $p_offer['value'];

	endforeach;

	if (!$echo_pack_offers) $echo_pack_offers = __('None','appthemes');

	### access

	// set the default access to job_lister (add/edit/delete jobs)
	$default_access = array('job_lister');

	if (!is_array($pack->access)) $pack->access = array_merge($default_access, explode(',', $pack->access ));

	$access = array (
					  'job_lister'     => get_option($app_abbr.'_allow_editing') == 'yes' ?
					  					  __('Add/Edit/Delete Jobs', 'appthemes') : __('Add/Delete Jobs', 'appthemes'),
					  'resume_view'    => __('View Resumes', 'appthemes'),
					  'resume_browse'  => __('Browse Resumes', 'appthemes'),
			 );
	// hook into this filter to add custom access options - these must match the admin values set within the 'jr_admin_job_pack_access_options' hook
	$access = apply_filters('jr_job_pack_access_options', $access);
	
	foreach ($access as $key => $value)
		if ( in_array($key, $pack->access) ) $echo_pack_access .= ($echo_pack_access?', ':'').$value;
	
	
	### output
	
	// display admin only pack options
	if ( is_admin() ) :

		$operations_html = sprintf(
				'<div class="job-pack-operations"><a href="admin.php?page=jobpacks&amp;edit=%d">%s</a>
  		   		   <a href="admin.php?page=jobpacks&amp;delete=%d" class="deletepack">%s</a>
				 </div>', $pack->id, __('Edit this Pack','appthemes'), $pack->id, __('Delete this pack','appthemes')
		);

		if ( $display_options['order'] == 'yes' )
			$pack_order_html = sprintf(
					'<div class="pack-order" title="%s"><small>#%s</small></div>',
					esc_attr__('Pack Order','appthemes'), $pack->pack_order.($default==1?'<br/>'.__('default','appthemes'):'')
			);

	endif;

	$pack_output = sprintf(
			'<div class="job-pack %s">'.
				$pack_order_html.
				'<div class="job-pack-title">
			    	<h2>%s : <span class="job-pack-price">%s</span></h2>
			    </div>'.
				 ( $display_options['selectable'] == 'yes' ?$select_pack_html:'').
			    '<p class="job-pack-description">%s</p>
			    <ul class="job-pack-details">
			    	<li class="job-pack-duration"><strong>%s</strong> '.$echo_pack_duration_expire.'</li>
					<li class="job-pack-jobs-duration"><strong>%s</strong> '.$echo_pack_jobs.'</li>
			    </ul>
			    <ul class="job-pack-details">
					<li class="job-pack-offers"><strong>%s</strong> '.$echo_pack_offers.'</li>
					<li class="job-pack-resume"><strong>%s</strong> '.$echo_pack_access.'</li>
			    </ul>'
				 .$categories_html.$operations_html.
			'</div><!-- job-pack -->',
			esc_attr(implode(' ', $echo_pack_class)), $pack->pack_name, $echo_pack_cost, $pack->pack_description, 
			__('Duration:','appthemes'), __('Jobs:','appthemes'), __('Offers:','appthemes'), __('Access:','appthemes')
	);
			  
	if ($echo) echo $pack_output;
	else return $pack_output;			  
	  
}
endif;

/**
 * Show the Job Packs selection list
 * The $page parameter is used to style the headings by page context
 *
 * @since 1.5.4
 * @param string $page The page where the Job Pack is displayed
 * @param array  $pack_types The job pack types to display. Possible types are: user|paid|free
 */
if (!function_exists('jr_job_pack_select')):
function jr_job_pack_select( $page = 'preview', $pack_types = array ( 'user', 'paid', 'free' ) ) {

	$pack_select = $pack_list = '';

	// do not filter packs by job category when relisting jobs (edit page)
	$filter_by_job_cat = ($page != 'edit' ? 'yes' : 'no');

	$packs = array();
	if (empty($pack_types) || ( in_array('free', $pack_types) || in_array('paid', $pack_types) ))
		$packs = jr_get_job_packs( $pack_types, $filter_by_job_cat );

	$user_packs = array();
	if (empty($pack_types) || ( in_array('user', $pack_types) ))
		$user_packs = jr_get_user_job_packs( 0, $filter_by_job_cat );

	// display packs if any, or the no active packs message
	if (sizeof($packs) > 0 || sizeof($user_packs)>0 || $page == 'dashboard' ) :

		$posted_job_pack = !empty($_POST['job_pack']) ? (int)$_POST['job_pack'] : 0;
		$default_pack=1;

		switch ( $page ):
			case 'dashboard':
				
				$title = __('My Packs', 'appthemes');
				if ( sizeof($user_packs) > 0 ) $sub_title = __('Below you will find a list of active packs you have purchased.', 'appthemes');
				else $sub_title = __('No active packs found.', 'appthemes');

				break;
			default:
			
				$title =  __('Select a Job Pack:', 'appthemes');
				$sub_title = '';
					
		endswitch;

		$pack_select = '<h2 class="pack_select '.$page.'">'. $title .'</h2><p>'.$sub_title.'</p>';

		// iterate through the user packs
		if ( sizeof($user_packs)>0 ):

			$pack_list = '';
			foreach ($user_packs as $pack) :
			
				if ($pack->id == $posted_job_pack) $default_pack = 1;
				$pack_list .= jr_display_pack( 'user', $pack, $default_pack, $echo = FALSE, ($page=='dashboard'?array( 'selectable' => 'no' ):'') );							
				$default_pack = '';

			endforeach;

			$pack_select .= '<div class="job-packs-user">'.$pack_list.'</div>';

		endif;

		// iterate through the job packs
		if ( sizeof($packs)>0 ):

			$pack_list = '';
			foreach ($packs as $pack) :

				if ($pack->id == $posted_job_pack) $default_pack = 1;
				$pack_list .= jr_display_pack( 'new', $pack, $default_pack, $echo = FALSE );
				$default_pack = '';

			endforeach;

			$title = __('Buy a Job Pack:', 'appthemes');
			$sub_title = __('Below you will find a list of all the job packs available for purchase.', 'appthemes');

			$pack_select .= '<div class="job-packs-new">'.$pack_list.'</div>';

		endif;

	endif;

	echo $pack_select;

	return sizeof($packs) + sizeof($user_packs);
}
endif;


// Radial location search
function jr_radial_search($location, $radius, $address_array = '') {
	global $wpdb, $app_abbr;
	if (function_exists('json_decode') && isset($location)) :

		if (!$radius) $radius = 50;

		// KM/Miles
		if (get_option($app_abbr.'_distance_unit')=='km') $radius = $radius / 1.609344;

		$jr_gmaps_lang = get_option('jr_gmaps_lang');
		$jr_gmaps_region = get_option('jr_gmaps_region');

		$location = strtolower(trim($location));

		// If address is not given, find it via Google Maps API or Cache
		if (!is_array($address_array)) {

			$address = "http://maps.google.com/maps/geo?q=".urlencode($location)."&output=json&language=".$jr_gmaps_lang."&sensor=false&gl=".$jr_gmaps_region."&region=".$jr_gmaps_region."";

			$cached = get_transient( 'jr_geo_'.sanitize_title($location) );

			if ($cached) :
				$address = $cached;
			else :
				$address = json_decode((file_get_contents($address)), true);
				if (is_array($address)) :
					set_transient( 'jr_geo_'.sanitize_title($location), $address, 60*60*24*7 ); // Cache for a week
				endif;
			endif;

			if (isset($address['Placemark'])) :
				// Put address info into a nice array format
				$address_array = array(
					'north' 	=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['north'],
					'south' 	=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['south'],
					'east' 		=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['east'],
					'west' 		=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['west'],
					'longitude' => $address['Placemark'][0]['Point']['coordinates'][0],
					'latitude' 	=> $address['Placemark'][0]['Point']['coordinates'][1]
				);

				$address_array['full_address'] = $address['Placemark'][0]['address'];

			endif;

		}

	   	if (is_array($address_array)) :

	   		if (isset($address_array['longitude']) && isset($address_array['latitude'])) :

	   			$lng_min = 0;
	   			$lng_max = 0;
	   			$lat_min = 0;
	   			$lat_max = 0;

	   			if (isset($address_array['north'])) {
	   				// Box
	   				$lng_max = $address_array['east'] + ($radius / 69);
					$lng_min = $address_array['west'] - ($radius / 69);
					$lat_min = $address_array['south'] - ($radius / 69);
					$lat_max = $address_array['north'] + ($radius / 69);
				} elseif ($address_array['north_east_lng']) {
					// Box
					$lng_max = $address_array['north_east_lng'] + ($radius / 69);
					$lng_min = $address_array['south_west_lng'] - ($radius / 69);
					$lat_min = $address_array['south_west_lat'] - ($radius / 69);
					$lat_max = $address_array['north_east_lat'] + ($radius / 69);
	   			} else {
	   				// Point (fallback)
	   				$lng_min = $address_array['longitude'] - $radius / abs(cos(deg2rad($address_array['latitude'])) * 69);
					$lng_max = $address_array['longitude'] + $radius / abs(cos(deg2rad($address_array['latitude'])) * 69);
					$lat_min = $address_array['latitude'] - ($radius / 69);
					$lat_max = $address_array['latitude'] + ($radius / 69);
	   			}

	   			$results1 = $wpdb->get_col("
	   				SELECT ID
	   				FROM $wpdb->posts
	   				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
	   				WHERE meta_key = '_jr_geo_latitude'
	   				AND (meta_value+0) between ('$lat_min') AND ('$lat_max') AND $wpdb->posts.post_status = 'publish';
	   			");
	   			$results2 = $wpdb->get_col("
	   				SELECT ID
	   				FROM $wpdb->posts
	   				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
	   				WHERE meta_key = '_jr_geo_longitude'
	   				AND (meta_value+0)  between ('$lng_min') AND ('$lng_max') AND $wpdb->posts.post_status = 'publish';
	   			");
	   			// Anywhere
	   			$anywhere = $wpdb->get_col("
	   				SELECT ID FROM $wpdb->posts
	   				WHERE ID NOT IN (
	   					SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE meta_key = 'geo_short_address'
	   				) AND $wpdb->posts.post_status = 'publish';
	   			");

	   			$posts = array_merge($anywhere, array_intersect($results1, $results2));

				return array('address' => $address_array['full_address'], 'posts' => $posts);
	   		endif;
	   	endif;

	endif;
	return false;
}

// Shows the map on single job listings
function jr_job_map() {
	global $post;

	$title = str_replace('"', '&quot;', wptexturize($post->post_title));
	$long 	= get_post_meta($post->ID, '_jr_geo_longitude', true);
	$lat 	= get_post_meta($post->ID, '_jr_geo_latitude', true);

	if (!$long || !$lat) return;
	?>

	<div id="job_map" style="height: 300px; display:none;"></div>
	<script type="text/javascript">
	/* <![CDATA[ */
		jQuery.noConflict();
		(function($) {

			$(function() {

				// Map global vars
				var map;
				var marker;
				var center;

				// initialize Google Maps API
				function initMap() {

					// Define Map center
					center = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);

					// Define Map options
					var myOptions = {
					  'zoom': 10,
					  'center': center,
					  'mapTypeId': google.maps.MapTypeId.ROADMAP
					};

					// Load Map
					map = new google.maps.Map(document.getElementById('job_map'), myOptions);

					// Marker
					marker = new google.maps.Marker({ position: center, map: map, title: "<?php echo $title; ?>" });

				}

				// Slide Toggle
				jQuery('a.toggle_map').click(function(){
			    	jQuery('#share_form').slideUp();
			        jQuery('#apply_form').slideUp();
					if (!map) initMap();
			        jQuery('#job_map').slideToggle(function(){
						google.maps.event.trigger(map, 'resize');
						map.setCenter(center);
			        });
			        jQuery('a.apply_online').removeClass('active');
			        jQuery(this).toggleClass('active');
			        return false;
			    });

			});
		})(jQuery);
	/* ]]> */
	</script>
	<?php

}

// creates the charts on the dashboard
function jr_dashboard_charts() {
	global $wpdb;

	$sql = "SELECT COUNT(post_title) as total, post_date FROM ". $wpdb->posts ." WHERE post_type = 'job_listing' AND post_date > '" . date('Y-m-d', strtotime('-30 days')) . "' GROUP BY DATE(post_date) DESC";
	$results = $wpdb->get_results($sql);

	$listings = array();

	// put the days and total posts into an array
	foreach ($results as $result) {
		$the_day = date('Y-m-d', strtotime($result->post_date));
		$listings[$the_day] = $result->total;
	}

	// setup the last 30 days
	for($i = 0; $i < 30; $i++) {
		$each_day = date('Y-m-d', strtotime('-'. $i .' days'));

		// if there's no day with posts, insert a goose egg
		if (!in_array($each_day, array_keys($listings))) $listings[$each_day] = 0;
	}

	// sort the values by date
	ksort($listings);

	// print_r($listings);

	// Get sales - completed orders with a cost
	$sql = "SELECT SUM(cost) as total, order_date FROM $wpdb->jr_orders WHERE status = 'completed' AND order_date > '" . date('Y-m-d', strtotime('-30 days')) . "' GROUP BY DATE(order_date) DESC";
	$results = $wpdb->get_results($sql);

	$sales = array();

	// put the days and total posts into an array
	foreach ($results as $result) {
		$the_day = date('Y-m-d', strtotime($result->order_date));
		$sales[$the_day] = $result->total;
	}

	// setup the last 30 days
	for($i = 0; $i < 30; $i++) {
		$each_day = date('Y-m-d', strtotime('-'. $i .' days'));

		// if there's no day with posts, insert a goose egg
		if (!in_array($each_day, array_keys($sales))) $sales[$each_day] = 0;
	}

	// sort the values by date
	ksort($sales);
?>

<div id="placeholder"></div>

<script language="javascript" type="text/javascript">
// <![CDATA[
jQuery(function () {

    var posts = [
		<?php
		foreach ($listings as $day => $value) {
			$sdate = strtotime($day);
			$sdate = $sdate * 1000; // js timestamps measure milliseconds vs seconds
			$newoutput = "[$sdate, $value],\n";
			//$theoutput[] = $newoutput;
			echo $newoutput;
		}
		?>
	];

	var sales = [
		<?php
		foreach ($sales as $day => $value) {
			$sdate = strtotime($day);
			$sdate = $sdate * 1000; // js timestamps measure milliseconds vs seconds
			$newoutput = "[$sdate, $value],\n";
			//$theoutput[] = $newoutput;
			echo $newoutput;
		}
		?>
	];


	var placeholder = jQuery("#placeholder");

	var output = [
		{
			data: posts,
			label: "<?php _e('New Job Listings', 'appthemes') ?>",
			symbol: ''
		},
		{
			data: sales,
			label: "<?php _e('Total Sales', 'appthemes') ?>",
			symbol: '<?php echo jr_get_currency(); ?>',
			yaxis: 2
		}
	];

	var options = {
       series: {
		   lines: { show: true },
		   points: { show: true }
	   },
	   grid: {
		   tickColor:'#f4f4f4',
		   hoverable: true,
		   clickable: true,
		   borderColor: '#f4f4f4',
		   backgroundColor:'#FFFFFF'
	   },
       xaxis: { mode: 'time',
				timeformat: "%m/%d"
	   },
	   yaxis: { min: 0 },
	   y2axis: { min: 0, tickFormatter: function (v, axis) { return "<?php echo jr_get_currency_in_position('left'); ?>" + v.toFixed(axis.tickDecimals) + "<?php echo jr_get_currency_in_position('right'); ?>" }},
	   legend: { position: 'nw' }
    };

	jQuery.plot(placeholder, output, options);

	// reload the plot when browser window gets resized
	jQuery(window).resize(function() {
		jQuery.plot(placeholder, output, options);
	});

	function showChartTooltip(x, y, contents) {
		jQuery('<div id="charttooltip">' + contents + '</div>').css( {
		position: 'absolute',
		display: 'none',
		top: y + 5,
		left: x + 5,
		opacity: 1
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	jQuery("#placeholder").bind("plothover", function (event, pos, item) {
		jQuery("#x").text(pos.x.toFixed(2));
		jQuery("#y").text(pos.y.toFixed(2));
		if (item) {
			if (previousPoint != item.datapoint) {
                previousPoint = item.datapoint;

				jQuery("#charttooltip").remove();
				var x = new Date(item.datapoint[0]), y = item.datapoint[1];
				var xday = x.getDate(), xmonth = x.getMonth()+1; // jan = 0 so we need to offset month
				showChartTooltip(item.pageX, item.pageY, xmonth + "/" + xday + " - <b>" + item.series.symbol + y + "</b> " + item.series.label);
			}
		} else {
			jQuery("#charttooltip").remove();
			previousPoint = null;
		}
	});
});
// ]]>
</script>

<?php
}

function jr_radius_dropdown() {
	global $app_abbr;

?>
			<div class="radius">
				<label for="radius"><?php _e('Radius:', 'appthemes'); ?></label>
				<select name="radius" class="radius">
<?php
				$selected_radius = isset( $_GET['radius'] ) ? absint( $_GET['radius'] ) : 0;

				if ( !$selected_radius )
					$selected_radius = 50;

				foreach ( array( 1, 5, 10, 50, 100, 1000, 5000 ) as $radius ) {
?>
					<option value="<?php echo $radius; ?>" <?php selected( $selected_radius, $radius ); ?>><?php echo number_format_i18n( $radius ) . ' ' . get_option( $app_abbr.'_distance_unit' ) ?></option>
<?php
				}
?>
				</select>
			</div><!-- end radius -->
<?php
}

function jr_job_author() {
	global $post;

	$company_name = wptexturize(strip_tags(get_post_meta($post->ID, '_Company', true)));

	if ( $company_name ) {
		if ( $company_url = esc_url( get_post_meta( $post->ID, '_CompanyURL', true ) ) ) {
?>
			<a href="<?php echo $company_url; ?>" rel="nofollow"><?php echo $company_name; ?></a>
<?php
		} else {
			echo $company_name;
		}

		$format = __(' &ndash; Posted by <a href="%s">%s</a>', 'appthemes');
	} else {
		$format = '<a href="%s">%s</a>';
	}

	$author = get_user_by('id', $post->post_author);
	if ( $author && $link = get_author_posts_url( $author->ID, $author->user_nicename ) )
		echo sprintf( $format, $link, $author->display_name );
}

function jr_location( $with_comma = false ) {
	global $post;

	$address = get_post_meta($post->ID, 'geo_short_address', true);

	if ( !$address )
		$address = __( 'Anywhere', 'appthemes' );

	echo "<strong>$address</strong>";

	$country = strip_tags(get_post_meta($post->ID, 'geo_short_address_country', true));

	if ( $country ) {
		if ( $with_comma )
			echo ', ';

		echo $country;
	}
}

// Outputs a single or plural textual date unit
function jr_format_date_unit ($unit, $length=1){

	$plural = ($length > 1?'s':'');

	$text = array (
		'm' => 'month',
		'd' => 'day',
		'w' => 'week',
		'y' => 'year'
	);

	return $text[strtolower($unit)].$plural;
}

// calculate and return the week start date
function jr_week_start_date($week, $year, $format = "d-m-Y") { 
	
	$first_day_year = date("N", mktime(0,0,0,1,1,$year)); 
	if ($first_day_year < 5) 
		$shift =-($first_day_year-1)*86400; 
	else 
		$shift=(8-$first_day_year)*86400; 
	if ($week > 1) $week_seconds = ($week-1)*604800; else $week_seconds = 0; 
	$timestamp = mktime(0,0,0,1,1,$year) + $week_seconds + $shift; 
	
	return date($format, $timestamp); 
} 

// get the server country
function jr_get_server_country() {

	// Get user country 
	if(isset($_SERVER['HTTP_X_FORWARD_FOR'])) $ip = $_SERVER['HTTP_X_FORWARD_FOR']; else $ip = $_SERVER['REMOTE_ADDR'];

	$ip = strip_tags($ip);
	$country = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 
	
	$result = wp_remote_get('http://api.hostip.info/country.php?ip='.$ip);
	if (!is_wp_error($result) && strtolower($result['body']) != 'xx') $country = $result['body'];

	return strtolower($country);
}

// returns the translated month
function jr_translate_months( $month ) {

	$translated_months = array (
		'january'  	=> __('January', 'appthemes'),
		'february' 	=> __('February', 'appthemes'),
		'march' 	=> __('March', 'appthemes'),
		'april' 	=> __('April', 'appthemes'),
		'may' 		=> __('May', 'appthemes'),
		'june' 		=> __('June', 'appthemes'),
		'july' 		=> __('July', 'appthemes'),
		'august' 	=> __('August', 'appthemes'),		
		'september' => __('September', 'appthemes'),		
		'october' 	=> __('October', 'appthemes'),		
		'november' 	=> __('November', 'appthemes'),						
		'december' 	=> __('December', 'appthemes'),		
	);

	return $translated_months[ strtolower(trim($month)) ];
	
}

// output sponsored listings 
if ( !function_exists('jr_display_sponsored_results') ):
function jr_display_sponsored_results( $search_results, $params, $is_ajax = false, $page = 1 ) {

	$defaults = array (
		'link_class'  => array('more_sponsored_results', 'front_page'),
		'tax'		  => '',
		'term'		  => ''
	);	
	$params = wp_parse_args( $params, $defaults );
	
	$alt = 1;
	$first = true;
	
	if (!$is_ajax) :
		echo sprintf('<div class="section"><h2 class="pagetitle">%s</h2>', esc_html($params['title']));
   		echo sprintf('<ol class="jobs sponsored_results" source="%s">', esc_attr($params['source']));
	endif;

	foreach ($search_results as $job) :
	
		$post_class = array('job');
		if ($alt==1) $post_class[] = 'job-alt';
		
		// check for the special sponsored job types (i.e: paid, sponsored or organic) and add them as classes 
		if ( isset($job['type']) && $job['type'] ) $post_class[] = 'ty_' . strtolower( $params['source'] ) . '_' . $job['type'];
			 
		// check for the additional classes to add
		if ( isset($job['class']) && $job['class'] ) $post_class[] = $job['class'];
 
		?>
		
		<li class="<?php esc_attr_e( implode(' ', $post_class) ); ?>" <?php if ($is_ajax && $first) echo 'id="more-'.$page.'"'; ?>><dl>
	            
	            <dt><?php _e('Type','appthemes'); ?></dt>
	            <dd class="type"><span class="ftype <?php esc_attr_e($job['jobtype']); ?>"><?php echo ucwords(esc_html($job['jobtype_name'])); ?></span></dd>
	            
	            <dt><?php _e('Job', 'appthemes'); ?></dt>
	            <dd class="title">
				<strong><a href="<?php echo esc_url($job['url']); ?>" target="_blank" rel="nofollow"><?php echo esc_html($job['jobtitle']); ?></a></strong>
				<?php echo wptexturize($job['company']); ?>
	            </dd>
	
	            <dt><?php _e('Location', 'appthemes'); ?></dt>
	            <dd class="location"><strong><?php echo esc_html($job['location']); ?></strong> <?php echo esc_html($job['country']); ?></dd>
	
	            <dt><?php _e('Date Posted', 'appthemes'); ?></dt>
	            <dd class="date"><strong><?php echo date_i18n('j M', strtotime($job['date'])); ?></strong> <span class="year"><?php echo date_i18n('Y', strtotime($job['date'])); ?></span></dd>
	            
	    </dl></li>
	    
		<?php		
	endforeach;
	
	if (!$is_ajax) :

		echo '</ol>
		<div class="paging sponsored_results_paging">
	        <div style="float:left;"><a href="#more" source="'. $params['source'] .'" callback="' . $params['callback'] . '" class="'.implode(' ', $params['link_class']).'" tax="'.$params['tax'].'" term="'.$params['term'].'" rel="2" >Load More &raquo;</a></div>
			<p class="attribution"><a href="http://www.simplyhired.com/">jobs</a> by <a href="http://www.simplyhired.com/" title="Job Search"><img src="' . $params['jobs_by_img'] . '" alt="' . $params['source'] . ' job search" /></a></p>
	    </div></div>';			
		
    endif;      
        	
}
endif;

// displays notices
if ( !function_exists('jr_notices') ):
function jr_notices() {
	global $post, $message, $errors;

	if ( $errors && sizeof($errors)>0 && $errors->get_error_code() ) { jr_show_errors($errors); return; }
	elseif ( !empty($message) )	{ appthemes_display_notice( 'success', strip_tags(stripslashes($message)) ); return; }

	if (isset($post)):

		// dashboard notices
		if ( $post->ID == get_option('jr_dashboard_page_id') ) {

				if ( isset($_GET['relist_success']) && is_numeric($_GET['relist_success']) )
					appthemes_display_notice( 'success', __('Job relisted successfully','appthemes') );
				else
					if ( isset($_GET['edit_success']) && is_numeric($_GET['edit_success']) )
						appthemes_display_notice( 'success', __('Job edited successfully','appthemes') );
				else
					if ( isset($_GET['give_pack_success']) && is_numeric($_GET['give_pack_success']) )
						if ($_GET['give_pack_success'])
							appthemes_display_notice( 'success', __('New Job Pack added successfully','appthemes') );
						else
							appthemes_display_notice( 'error', __('Error purchasing Job Pack','appthemes') );	
				else
					if ( isset($_POST['payment_status']) && strtolower($_POST['payment_status'])=='completed' )
						appthemes_display_notice( 'success', __('Thank you for your Order!','appthemes') );											

		// single resume notices
		} else {

			if ( is_singular(APP_POST_TYPE_RESUME) ):

				if ( isset($_GET['resume_contact']) && is_numeric($_GET['resume_contact']) )
					if ( $_GET['resume_contact']>0 )
						appthemes_display_notice( 'success', __('Your message was sent', 'appthemes') );
					else
						appthemes_display_notice( 'error', __('Could not send message at this time. Please try again later', 'appthemes') );

			endif;

		}

	endif;
	
}
endif;

add_action('appthemes_notices', 'jr_notices');

// run the appthemes_init() action hook
appthemes_init();

?>
