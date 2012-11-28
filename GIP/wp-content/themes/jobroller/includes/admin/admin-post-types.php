<?php
/**
 * Custom post types and taxonomies
 *
 *
 * @version 1.2
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */



// create the custom post type and category taxonomy for ad listings
// Define the custom post types
function jr_post_type() {
	global $app_abbr;

    // get the slug value for the ad custom post type & taxonomies
    if(get_option($app_abbr.'_job_permalink')) $post_type_base_url = get_option($app_abbr.'_job_permalink'); else $post_type_base_url = 'jobs';
    if(get_option($app_abbr.'_job_cat_tax_permalink')) $cat_tax_base_url = get_option($app_abbr.'_job_cat_tax_permalink'); else $cat_tax_base_url = 'job-category';
	if(get_option($app_abbr.'_job_type_tax_permalink')) $type_tax_base_url = get_option($app_abbr.'_job_type_tax_permalink'); else $type_tax_base_url = 'job-type';
    if(get_option($app_abbr.'_job_tag_tax_permalink')) $tag_tax_base_url = get_option($app_abbr.'_job_tag_tax_permalink'); else $tag_tax_base_url = 'job-tag';
	if(get_option($app_abbr.'_job_salary_tax_permalink')) $sal_tax_base_url = get_option($app_abbr.'_job_salary_tax_permalink'); else $sal_tax_base_url = 'salary';	
	if(get_option($app_abbr.'_resume_permalink')) $resume_post_type_base_url = get_option($app_abbr.'_resume_permalink'); else $resume_post_type_base_url = 'resumes';


    // create the custom post type and category taxonomy for job listings
    register_post_type( APP_POST_TYPE,
				array('labels' => array(
					'name' => __( 'Jobs', 'appthemes' ),
					'singular_name' => __( 'Jobs', 'appthemes' ),
					'add_new' => __( 'Add New', 'appthemes' ),
					'add_new_item' => __( 'Add New Job', 'appthemes' ),
					'edit' => __( 'Edit', 'appthemes' ),
					'edit_item' => __( 'Edit Job', 'appthemes' ),
					'new_item' => __( 'New Job', 'appthemes' ),
					'view' => __( 'View Jobs', 'appthemes' ),
					'view_item' => __( 'View Job', 'appthemes' ),
					'search_items' => __( 'Search Jobs', 'appthemes' ),
					'not_found' => __( 'No jobs found', 'appthemes' ),
					'not_found_in_trash' => __( 'No jobs found in trash', 'appthemes' ),
					'parent' => __( 'Parent Job', 'appthemes' ),
                    ),
                    'description' => __( 'This is where you can create new job listings on your site.', 'appthemes' ),
                    'public' => true,
                    'show_ui' => true,
                    'capability_type' => 'post',
                    'publicly_queryable' => true,
                    'exclude_from_search' => false,
                    'menu_position' => 8,
                    'menu_icon' => get_template_directory_uri() . '/images/job_icon.png',
                    'hierarchical' => false,
                    'rewrite' => array( 'slug' => $post_type_base_url, 'with_front' => false ), /* Slug set so that permalinks work when just showing post name */
                    'query_var' => true,
                    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky' ),
            )
    );

    // register the new job category taxonomy
    register_taxonomy( APP_TAX_CAT,
            array( APP_POST_TYPE ),
            array('hierarchical' => true,                    
                    'labels' => array(
                            'name' => __( 'Job Categories', 'appthemes'),
                            'singular_name' => __( 'Job Category', 'appthemes'),
                            'search_items' =>  __( 'Search Job Categories', 'appthemes'),
                            'all_items' => __( 'All Job Categories', 'appthemes'),
                            'parent_item' => __( 'Parent Job Category', 'appthemes'),
                            'parent_item_colon' => __( 'Parent Job Category:', 'appthemes'),
                            'edit_item' => __( 'Edit Job Category', 'appthemes'),
                            'update_item' => __( 'Update Job Category', 'appthemes'),
                            'add_new_item' => __( 'Add New Job Category', 'appthemes'),
                            'new_item_name' => __( 'New Job Category Name', 'appthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => $cat_tax_base_url, 'hierarchical' => true ),
            )
    );

    // register the new job type taxonomy
    register_taxonomy( APP_TAX_TYPE,
            array( APP_POST_TYPE ),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Job Types', 'appthemes'),
                            'singular_name' => __( 'Job Type', 'appthemes'),
                            'search_items' =>  __( 'Search Job Types', 'appthemes'),
                            'all_items' => __( 'All Job Types', 'appthemes'),
                            'parent_item' => __( 'Parent Job Type', 'appthemes'),
                            'parent_item_colon' => __( 'Parent Job Type:', 'appthemes'),
                            'edit_item' => __( 'Edit Job Type', 'appthemes'),
                            'update_item' => __( 'Update Job Type', 'appthemes'),
                            'add_new_item' => __( 'Add New Job Type', 'appthemes'),
                            'new_item_name' => __( 'New Job Type Name', 'appthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
					'update_count_callback' => '_update_post_term_count',
                    'rewrite' => array( 'slug' => $type_tax_base_url, 'hierarchical' => true ),
            )
    );

    // register the new job tag taxonomy
    register_taxonomy( APP_TAX_TAG,
            array( APP_POST_TYPE ),
            array('hierarchical' => false,
                    'labels' => array(
                            'name' => __( 'Job Tags', 'appthemes'),
                            'singular_name' => __( 'Job Tag', 'appthemes'),
                            'search_items' =>  __( 'Search Job Tags', 'appthemes'),
                            'all_items' => __( 'All Job Tags', 'appthemes'),
                            'parent_item' => __( 'Parent Job Tag', 'appthemes'),
                            'parent_item_colon' => __( 'Parent Job Tag:', 'appthemes'),
                            'edit_item' => __( 'Edit Job Tag', 'appthemes'),
                            'update_item' => __( 'Update Job Tag', 'appthemes'),
                            'add_new_item' => __( 'Add New Job Tag', 'appthemes'),
                            'new_item_name' => __( 'New Job Tag Name', 'appthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => $tag_tax_base_url ),
                    'update_count_callback' => '_update_post_term_count'
            )
    );

    // register the salary taxonomy
    register_taxonomy( APP_TAX_SALARY,
            array( APP_POST_TYPE ),
            array('hierarchical' => true,
                    'labels' => array(
                            'name' => __( 'Salary', 'appthemes'),
                            'singular_name' => __( 'Salary', 'appthemes'),
                            'search_items' =>  __( 'Search Salaries', 'appthemes'),
                            'all_items' => __( 'All Salaries', 'appthemes'),
                            'parent_item' => __( 'Parent Salary', 'appthemes'),
                            'parent_item_colon' => __( 'Parent Salary:', 'appthemes'),
                            'edit_item' => __( 'Edit Salary', 'appthemes'),
                            'update_item' => __( 'Update Salary', 'appthemes'),
                            'add_new_item' => __( 'Add New Salary', 'appthemes'),
                            'new_item_name' => __( 'New Salary', 'appthemes')
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array( 'slug' => $sal_tax_base_url ),
            )
    );
    
    if (get_option('jr_allow_job_seekers')=='yes') $show_ui = true; else $show_ui = false;
    
    register_post_type( APP_POST_TYPE_RESUME,
				array('labels' => array(
					'name' => __( 'Resumes', 'appthemes' ),
					'singular_name' => __( 'Resume', 'appthemes' ),
					'add_new' => __( 'Add New', 'appthemes' ),
					'add_new_item' => __( 'Add New Resume', 'appthemes' ),
					'edit' => __( 'Edit', 'appthemes' ),
					'edit_item' => __( 'Edit Resume', 'appthemes' ),
					'new_item' => __( 'New Resume', 'appthemes' ),
					'view' => __( 'View Resumes', 'appthemes' ),
					'view_item' => __( 'View Resume', 'appthemes' ),
					'search_items' => __( 'Search Resumes', 'appthemes' ),
					'not_found' => __( 'No Resumes found', 'appthemes' ),
					'not_found_in_trash' => __( 'No Resumes found in trash', 'appthemes' ),
					'parent' => __( 'Parent Resume', 'appthemes' ),
                ),
                'description' => __( 'Resumes are created and edited by job_seekers.', 'appthemes' ),
                'public' => true,
                'show_ui' => $show_ui,
                'capability_type' => 'post',
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'menu_position' => 8,
                'menu_icon' => get_template_directory_uri() . '/images/resume_icon.png',
                'hierarchical' => false,
                'rewrite' => array( 'slug' => $resume_post_type_base_url, 'with_front' => false ), /* Slug set so that permalinks work when just showing post name */
                'query_var' => true,
                'has_archive' => $resume_post_type_base_url,
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' ),
            )
    );
    register_taxonomy( APP_TAX_RESUME_SPECIALITIES,
        array( APP_POST_TYPE_RESUME ),
        array('hierarchical' => false,                    
                'labels' => array(
                        'name' => __( 'Resume Specialities', 'appthemes'),
                        'singular_name' => __( 'Resume Speciality', 'appthemes'),
                        'search_items' =>  __( 'Search Resume Specialities', 'appthemes'),
                        'all_items' => __( 'All Resume Specialities', 'appthemes'),
                        'parent_item' => __( 'Parent Resume Speciality', 'appthemes'),
                        'parent_item_colon' => __( 'Parent Resume Speciality:', 'appthemes'),
                        'edit_item' => __( 'Edit Resume Speciality', 'appthemes'),
                        'update_item' => __( 'Update Resume Speciality', 'appthemes'),
                        'add_new_item' => __( 'Add New Resume Speciality', 'appthemes'),
                        'new_item_name' => __( 'New Resume Speciality Name', 'appthemes')
                ),
                'show_ui' => $show_ui,
                'rewrite' => array( 'slug' => 'resume/speciality', 'with_front' => false ),
                'query_var' => true,
                 'update_count_callback' => '_update_post_term_count'
        )
	);
	register_taxonomy( APP_TAX_RESUME_GROUPS,
        array( APP_POST_TYPE_RESUME ),
        array('hierarchical' => false,                    
                'labels' => array(
                        'name' => __( 'Groups/Associations', 'appthemes'),
                        'singular_name' => __( 'Resume Group/Association', 'appthemes'),
                        'search_items' =>  __( 'Search Groups/Associations', 'appthemes'),
                        'all_items' => __( 'All Groups/Associations', 'appthemes'),
                        'parent_item' => __( 'Parent Group/Association', 'appthemes'),
                        'parent_item_colon' => __( 'Parent Group/Association:', 'appthemes'),
                        'edit_item' => __( 'Edit Group/Association', 'appthemes'),
                        'update_item' => __( 'Update Group/Association', 'appthemes'),
                        'add_new_item' => __( 'Add New Group/Association', 'appthemes'),
                        'new_item_name' => __( 'New Group/Association Name', 'appthemes')
                ),
                'show_ui' => $show_ui,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'resume/group', 'with_front' => false ),
                 'update_count_callback' => '_update_post_term_count'
        )
	);
	register_taxonomy( APP_TAX_RESUME_LANGUAGES,
        array( APP_POST_TYPE_RESUME ),
        array('hierarchical' => false,                    
                'labels' => array(
                        'name' => __( 'Spoken Languages', 'appthemes'),
                        'singular_name' => __( 'Spoken Langauge', 'appthemes'),
                        'search_items' =>  __( 'Search Spoken Languages', 'appthemes'),
                        'all_items' => __( 'All Spoken Languages', 'appthemes'),
                        'parent_item' => __( 'Parent Spoken Language', 'appthemes'),
                        'parent_item_colon' => __( 'Parent Spoken Language:', 'appthemes'),
                        'edit_item' => __( 'Edit Spoken Language', 'appthemes'),
                        'update_item' => __( 'Update Spoken Language', 'appthemes'),
                        'add_new_item' => __( 'Add New Spoken Language', 'appthemes'),
                        'new_item_name' => __( 'New Spoken Language Name', 'appthemes')
                ),
                'show_ui' => $show_ui,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'resume/language', 'with_front' => false ),
                 'update_count_callback' => '_update_post_term_count'
        )
	);
	register_taxonomy( APP_TAX_RESUME_CATEGORY,
        array( APP_POST_TYPE_RESUME ),
        array('hierarchical' => true,                    
                'labels' => array(
                        'name' => __( 'Resume Categories', 'appthemes'),
                        'singular_name' => __( 'Resume Category', 'appthemes'),
                        'search_items' =>  __( 'Search Resume Categories', 'appthemes'),
                        'all_items' => __( 'All Resume Categories', 'appthemes'),
                        'parent_item' => __( 'Parent Resume Category', 'appthemes'),
                        'parent_item_colon' => __( 'Parent Resume Category:', 'appthemes'),
                        'edit_item' => __( 'Edit Resume Category', 'appthemes'),
                        'update_item' => __( 'Update Resume Category', 'appthemes'),
                        'add_new_item' => __( 'Add New Resume Category', 'appthemes'),
                        'new_item_name' => __( 'New Resume Category Name', 'appthemes')
                ),
                'show_ui' => $show_ui,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'resume/category', 'with_front' => false ), 
                'update_count_callback' => '_update_post_term_count'
        )
	);
	register_taxonomy( APP_TAX_RESUME_JOB_TYPE,
        array( APP_POST_TYPE_RESUME ),
        array('hierarchical' => false,                    
                'labels' => array(
                        'name' => __( 'Resume Job Types', 'appthemes'),
                        'singular_name' => __( 'Resume Job Type', 'appthemes'),
                        'search_items' =>  __( 'Search Resume Job Types', 'appthemes'),
                        'all_items' => __( 'All Resume Job Types', 'appthemes'),
                        'parent_item' => __( 'Parent Resume Job Type', 'appthemes'),
                        'parent_item_colon' => __( 'Parent Resume Job Type:', 'appthemes'),
                        'edit_item' => __( 'Edit Resume Job Type', 'appthemes'),
                        'update_item' => __( 'Update Resume Job Type', 'appthemes'),
                        'add_new_item' => __( 'Add New Resume Job Type', 'appthemes'),
                        'new_item_name' => __( 'New Resume Job Type Name', 'appthemes')
                ),
                'show_ui' => $show_ui,
                'rewrite' => array( 'slug' => 'resume/job-type', 'with_front' => false ),
                'query_var' => true,
                 'update_count_callback' => '_update_post_term_count'
        )
	);

}

add_action( 'init', 'jr_post_type', 0 );


function jr_edit_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Job Name', 'appthemes'),
		'author' => __('Job Author', 'appthemes'),
		'job_cat' => __('Job Category', 'appthemes'),
		'job_type' => __('Job Type', 'appthemes'),
		'job_salary' => __('Salary', 'appthemes'),
		'company' => __('Company', 'appthemes'),
		'location' => __('Location', 'appthemes'),
		'date' => __('Date', 'appthemes'),
	);
	return $columns;
}
add_filter('manage_edit-job_listing_columns', 'jr_edit_columns');

function jr_custom_columns($column){
	global $post;
	$custom = get_post_custom();
	switch ($column) {
		case 'company':
			if ( isset($custom['_Company'][0]) && !empty($custom['_Company'][0]) ) :
				if ( isset($custom['_CompanyURL'][0]) && !empty($custom['_CompanyURL'][0]) ) echo '<a href="'.$custom['_CompanyURL'][0].'">'.$custom['_Company'][0].'</a>';
				else echo $custom['_Company'][0];
			endif;
		break;
		case 'location':
			if ( isset($custom['geo_address'][0]) && !empty($custom['geo_address'][0]) ) :
				echo $custom['geo_address'][0];
			else :
				_e('Anywhere', 'appthemes');
			endif;
		break;
		case APP_TAX_TYPE :
			echo get_the_term_list($post->ID, APP_TAX_TYPE, '', ', ','');
		break;
		case APP_TAX_SALARY :
			echo get_the_term_list($post->ID, APP_TAX_SALARY, '', ', ','');
		break;
		case APP_TAX_CAT :
			echo get_the_term_list($post->ID, APP_TAX_CAT, '', ', ','');
		break;
	}
}
add_action('manage_posts_custom_column',  'jr_custom_columns');

// add a logo column to the edit jobs screen
function jr_job_logo_column($cols) {
    $cols['logo'] = __('Logo', 'appthemes');
    return $cols;
}
add_filter('manage_edit-'.APP_POST_TYPE.'_columns', 'jr_job_logo_column');


// add a thumbnail column to the edit posts screen
function jr_post_thumbnail_column($cols) {
    $cols['thumbnail'] = __('Thumbnail', 'appthemes');
    return $cols;
}
add_filter('manage_posts_columns', 'jr_post_thumbnail_column');


// go get the attached images for the logo and thumbnail columns
function jr_thumbnail_value($column_name, $post_id) {

    if (('thumbnail' == $column_name) || ('logo' == $column_name)) {

        if (has_post_thumbnail($post_id)) echo get_the_post_thumbnail($post_id, 'sidebar-thumbnail');

    }
}
add_action('manage_posts_custom_column', 'jr_thumbnail_value', 10, 2);





// add a drop-down post type selector to the edit post/ads admin pages
function jr_post_type_changer() {
    global $post;

    // disallow things like attachments, revisions, etc
    $safe_filter = array('public' => true, 'show_ui' => true);

    // allow this to be filtered
    $args = apply_filters('jr_post_type_changer', $safe_filter);

    // get the post types
    $post_types = get_post_types((array)$args);

    // get the post_type values
    $cur_post_type_object = get_post_type_object($post->post_type);
    
    $cur_post_type = $cur_post_type_object->name;

    // make sure the logged in user has perms
    $can_publish = current_user_can($cur_post_type_object->cap->publish_posts);
?>

<?php if ( $can_publish ) : ?>

<div class="misc-pub-section misc-pub-section-last post-type-switcher">

	<label for="pts_post_type"><?php _e('Post Type:', 'appthemes'); ?></label>

	<span id="post-type-display"><?php echo $cur_post_type_object->label; ?></span>

	<a href="#pts_post_type" class="edit-post-type hide-if-no-js"><?php _e('Edit', 'appthemes'); ?></a>
	<div id="post-type-select" class="hide-if-js">

		<select name="pts_post_type" id="pts_post_type">
            <?php foreach ( $post_types as $post_type ) {
			$pt = get_post_type_object( $post_type );

			if ( current_user_can( $pt->cap->publish_posts ) ) : ?>

				<option value="<?php echo $pt->name; ?>"<?php if ( $cur_post_type == $post_type ) : ?>selected="selected"<?php endif; ?>><?php echo $pt->label; ?></option>

			<?php
			endif;
		}
            ?>
		</select>

		<input type="hidden" name="hidden_post_type" id="hidden_post_type" value="<?php echo $cur_post_type; ?>" />

		<a href="#pts_post_type" class="save-post-type hide-if-no-js button"><?php _e('OK', 'appthemes'); ?></a>
		<a href="#pts_post_type" class="cancel-post-type hide-if-no-js"><?php _e('Cancel', 'appthemes'); ?></a>
	</div>	
	
</div>

<?php
	endif;
}

// add this option to the edit post submit box
add_action('post_submitbox_misc_actions', 'jr_post_type_changer');



// jquery and css for the post type changer
function jr_post_type_changer_head() {
?>

<script type='text/javascript'>
    jQuery(document).ready(function(){
        jQuery('#post-type-select').siblings('a.edit-post-type').click(function() {
            if (jQuery('#post-type-select').is(":hidden")) {
                jQuery('#post-type-select').slideDown("normal");
                jQuery(this).hide();
            }
            return false;
        });

        jQuery('.save-post-type', '#post-type-select').click(function() {
            jQuery('#post-type-select').slideUp("normal");
            jQuery('#post-type-select').siblings('a.edit-post-type').show();
            pts_updateText();
            return false;
        });

        jQuery('.cancel-post-type', '#post-type-select').click(function() {
            jQuery('#post-type-select').slideUp("normal");
            jQuery('#pts_post_type').val(jQuery('#hidden_post_type').val());
            jQuery('#post-type-select').siblings('a.edit-post-type').show();
            pts_updateText();
            return false;
        });

        function pts_updateText() {
            jQuery('#post-type-display').html( jQuery('#pts_post_type :selected').text() );
            jQuery('#hidden_post_type').val(jQuery('#pts_post_type').val());
            jQuery('#post_type').val(jQuery('#pts_post_type').val());
            return true;
        }
    });
</script>

<style type="text/css">
    #post-type-select { line-height: 2.5em; margin-top: 3px; }
    #post-type-display { font-weight: bold; }
    div.post-type-switcher { border-top: 1px solid #eee; }
</style>

<?php
}

// activate this function to load in the admin head
add_action('admin_head', 'jr_post_type_changer_head');


// custom user page columns
function jr_manage_users_columns( $columns ) {
    $columns['jr_jobs_count'] = __('Jobs', 'appthemes');
	$columns['jr_resumes_count'] = __('Resumes', 'appthemes');
	$columns['jr_resume_subscription'] = __('Resume Subscription', 'appthemes');
	$columns['last_login'] = __('Last Login', 'appthemes');
	$columns['registered'] = __('Registered', 'appthemes');
    return $columns;
}
add_action('manage_users_columns', 'jr_manage_users_columns');


// display the coumn values for each user
function jr_manage_users_custom_column( $r, $column_name, $user_id ) {

	// count the total jobs for the user
	if ( 'jr_jobs_count' == $column_name ) {
		global $jobs_counts;

		if ( !isset( $jobs_counts ) )
			$jobs_counts = jr_count_custom_post_types( APP_POST_TYPE );

		if ( !array_key_exists( $user_id, $jobs_counts ) )
			$jobs_counts = jr_count_custom_post_types( APP_POST_TYPE );

		if ( $jobs_counts[$user_id] > 0 ) {
			$r .= "<a href='edit.php?post_type=" . APP_POST_TYPE . "&author=$user_id' title='" . esc_attr__( 'View jobs by this author', 'appthemes' ) . "' class='edit'>";
			$r .= $jobs_counts[$user_id];
			$r .= '</a>';
		} else {
			$r .= 0;
		}
	}
	
	// count the total resumes for the user
	if ( 'jr_resumes_count' == $column_name ) {
		global $resumes_counts;

		if ( !isset( $resumes_counts ) )
			$resumes_counts = jr_count_custom_post_types( APP_POST_TYPE_RESUME );

		if ( !array_key_exists( $user_id, $resumes_counts ) )
			$resumes_counts = jr_count_custom_post_types( APP_POST_TYPE_RESUME );

		if ( $resumes_counts[$user_id] > 0 ) {
			$r .= "<a href='edit.php?post_type=" . APP_POST_TYPE_RESUME . "&author=$user_id' title='" . esc_attr__( 'View resumes by this author', 'appthemes' ) . "' class='edit'>";
			$r .= $resumes_counts[$user_id];
			$r .= '</a>';
		} else {
			$r .= 0;
		}
	}
	
	// get the user last login date
	if ('last_login' == $column_name)
		$r = get_user_meta($user_id, 'last_login', true);
	
	// get the user registration date	
	if ('registered' == $column_name) {
		$user_info = get_userdata($user_id);
		$r = $user_info->user_registered;
		//$r = appthemes_get_reg_date($reg_date);
	}
	
	if ('jr_resume_subscription' == $column_name) {
		$status = get_user_meta($user_id, '_valid_resume_subscription', true);
		if ($status=='1') {
			$r = __('Yes', 'appthemes');
		} else {
			$r = '&ndash;';
		}
	}

	return $r;
}
//Display the custom column data for each user
add_action( 'manage_users_custom_column', 'jr_manage_users_custom_column', 10, 3 );


// count the number of job listings & resumes for the user
function jr_count_custom_post_types( $post_type ) {
	global $wpdb, $wp_list_table;

	$users = array_keys( $wp_list_table->items );
	$userlist = implode( ',', $users );
	$result = $wpdb->get_results( "SELECT post_author, COUNT(*) FROM $wpdb->posts WHERE post_type = '$post_type' AND post_author IN ($userlist) GROUP BY post_author", ARRAY_N );
	foreach ( $result as $row ) {
		$count[ $row[0] ] = $row[1];
	}

	foreach ( $users as $id ) {
		if ( ! isset( $count[ $id ] ) )
			$count[ $id ] = 0;
	}

	return $count;
}

?>