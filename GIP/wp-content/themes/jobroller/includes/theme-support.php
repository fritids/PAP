<?php
/**
 * JobRoller Theme Support
 * This file defines 'theme support' so wordpress knows what new features it can handle.
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

// activate support for thumbnails
if (function_exists('add_theme_support')) { // added in 2.9
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails', array( 'post', 'job_listing', 'resume' ) );
	set_post_thumbnail_size( 250, 250, false );
	add_image_size('blog-thumbnail', 150, 150, true); // blog post thumbnail size, box resize mode
	add_image_size('sidebar-thumbnail', 48, 48, true); // sidebar blog thumbnail size, box resize mode
	add_image_size('listing-thumbnail', 28, 28, true);
}

function default_primary_nav() {
	global $wp_query;
	echo '<ul>';
	echo '<li class="page_item ';
	if (is_front_page() && !isset($_GET['submit']) && !isset($_GET['myjobs'])) echo 'current_page_item';
	echo '"><a href="'.get_bloginfo('url').'">'.__('Latest Jobs', 'appthemes').'</a></li>';
	
	$args = array(
	    'hierarchical'       => false,
	    'parent'               => 0
	);
	$terms = get_terms( 'job_type', $args );
	if ($terms) foreach($terms as $term) :
		echo '<li class="page_item ';
		if ( isset($wp_query->queried_object->slug) && $wp_query->queried_object->slug==$term->slug ) echo 'current_page_item';
		echo '"><a href="'.get_term_link( $term->slug, 'job_type' ).'">'.$term->name.'</a></li>';
	endforeach;
	
	echo '</ul>';
}

function default_top_nav() {
	echo '<ul id="menu-top" class="menu">';
	
	$exclude_pages = array();
	
	$exclude_pages[] = get_option('page_on_front');
	$exclude_pages[] = get_option('jr_dashboard_page_id');
	$exclude_pages[] = get_option('jr_add_new_confirm_page_id');
	$exclude_pages[] = get_option('jr_submit_page_id');
	$exclude_pages[] = get_option('jr_user_profile_page_id');
	$exclude_pages[] = get_option('jr_edit_job_page_id');
	$exclude_pages[] = get_option('jr_date_archive_page_id');
	$exclude_pages[] = get_option('jr_job_seeker_register_page_id');
	
	if (get_option('jr_disable_blog')=='yes') $exclude_pages[] = get_option('jr_blog_page_id');
	
	$exclude_pages = implode(',', $exclude_pages);
	echo wp_list_pages('sort_column=menu_order&title_li=&echo=0&link_before=&link_after=&depth=1&exclude='.$exclude_pages);
	echo jr_top_nav_links();
	echo '</ul>';
}

/* Add items to top nav */

add_filter('wp_nav_menu_items', 'jr_top_nav_links', 2, 10);

function jr_top_nav_links( $items = '', $menu = null) {

	if( !empty($menu) && $menu->theme_location != 'top')
	    return $items;

	if (is_user_logged_in()) {
		$items .= '<li class="right"><a href="'.wp_logout_url( get_bloginfo('url') ).'">'.__('Logout', 'appthemes').'</a></li>';
		
		/*if (get_option('jr_user_profile_page_id')) :
		
			$items .= '<li class="right ';
			if (is_page(get_option('jr_user_profile_page_id'))) $items .= 'current_page_item';		
			$items .= '"><a href="'.get_permalink(get_option('jr_user_profile_page_id')).'">'.__('My Profile', 'appthemes').'</a></li>';
		
		endif;*/
		
		if (get_option('jr_dashboard_page_id') && is_user_logged_in()) :
		
			$items .= '<li class="right ';
			if (is_page(get_option('jr_dashboard_page_id'))) $items .= 'current_page_item';		
			$items .= '"><a href="'.get_permalink(get_option('jr_dashboard_page_id')).'">'.__('My Dashboard', 'appthemes').'</a></li>';
		
		endif;
		
	} else {
		global $pagenow;
		if(isset($_GET['action'])) $theaction = $_GET['action']; else $theaction ='';
		$items .= '<li class="right ';
		if ($pagenow == 'wp-login.php' && $theaction !=='lostpassword' && !isset($_GET['key'])) $items .= 'current_page_item';
		$items .= '"><a href="'.site_url('wp-login.php').'">'.__('Login/Register', 'appthemes').'</a></li>';					
	}
	
	if ( jr_resume_is_visible() || (is_user_logged_in() && jr_viewing_resumes_require_subscription()) ) :
		$items .= '<li class="right ';
		if (is_post_type_archive('resume')) $items .= 'current_page_item';	
		$items .= '"><a href="'.get_post_type_archive_link('resume').'">'.__('Browse Resumes', 'appthemes').'</a></li>';
	endif;
	
	if (get_option('jr_submit_page_id') && (!is_user_logged_in() || (is_user_logged_in() && current_user_can('can_submit_job')))) :
	
		$items .= '<li class="right ';
		if (is_page(get_option('jr_submit_page_id'))) $items .= 'current_page_item';	
		$items .= '"><a href="'.get_permalink(get_option('jr_submit_page_id')).'">'.__('Submit a Job', 'appthemes').'</a></li>';
		
	endif;
	
	
	$items .= '</ul>';	
	
	return $items;
	
}