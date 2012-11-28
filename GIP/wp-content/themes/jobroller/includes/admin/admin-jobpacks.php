<?php

// show admin options for job packs
function jr_job_packs_options_form() {
	global $options_job_packs;

	if ($_POST && isset($_POST['job_packs_options'])) :
		appthemes_update_options($options_job_packs);
	endif;	
    ?>

    <div class="wrap jobroller">
           
        <form method="post" id="optionsform" action="">       
            <?php appthemes_admin_fields($options_job_packs); ?>

            <p class="submit bbot"><input name="save" type="submit" value="<?php esc_attr(_e('Save changes','appthemes')); ?>" /></p>
            <input name="job_packs_options" type="hidden" value="yes" />
			<input name="submitted" type="hidden" value="yes" />
        </form>
    </div>
	
<?php
}

// display the job packs form
function jr_job_packs_form( $job_pack = array(), $type = 'edit' ) {
?>	
	
	<form method="post" id="mainform" action="">
		
		<table class="widefat fixed" id="tblspacer" style="width:850px;">

                <thead>
                    <tr>
                        <th scope="col" width="200px"><?php _e('Job Pack Details','appthemes')?></th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                </thead>

		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a name for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Name','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_name" id="pack_name" type="text" value="<?php if (isset($job_pack['pack_name'])) echo esc_attr($job_pack['pack_name']); ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a description for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Description','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_description" id="pack_description" type="text" style="width:550px" value="<?php if (isset($job_pack['pack_description'])) echo esc_attr($job_pack['pack_description']); ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter the number of days','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_duration" id="pack_duration" type="text" value="<?php if (isset($job_pack['pack_duration'])) echo esc_attr($job_pack['pack_duration']); ?>" /><br/><small><?php _e('Days this pack remains valid to use. Leave blank if it never expires.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value, do not include currency values.','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Cost','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_cost" id="pack_cost" type="text" value="<?php if (isset($job_pack['pack_cost'])) echo esc_attr($job_pack['pack_cost']); ?>"  /><br/><small><?php _e('Pack cost. Leave blank if free.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Count','appthemes') ?>:</td>
                <td class="forminp"><input name="job_count" id="job_count" type="text" value="<?php if (isset($job_pack['job_count'])) echo esc_attr($job_pack['job_count']); ?>"  /><br/><small><?php _e('How many jobs can the user list with this pack? Leave blank for an <em>unlimited</em> amount.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="job_duration" id="job_duration" type="text" value="<?php if (isset($job_pack['job_duration'])) echo esc_attr($job_pack['job_duration']); ?>" class="required" /><br/><small><?php _e('How long do jobs last? e.g. <code>30</code> for 30 days. Leave blank for endless jobs.','appthemes') ?></small></td>
		    </tr>
		    <thead>
	            <tr>
	                 <th scope="col" width="200px"><?php _e('Job Pack Offers','appthemes')?></th>
	                 <th scope="col">&nbsp;</th>
	            </tr>	
            </thead>	    
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Job offers are added to the Pack Job count. For example, a Pack with 2 jobs and 1 job offer will have 2(+1 Free) jobs available.','appthemes'); ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Offers Count','appthemes') ?>:</td>
                <td class="forminp"><input name="job_offers" id="job_offers" type="text" value="<?php if (isset($job_pack['job_offers'])) echo esc_attr($job_pack['job_offers']); ?>" class="" /><br/><small><?php _e('Promote your Packs by offering additional jobs. Enter a numeric value or leave blank.<br/>Not used on Free Job Packs.','appthemes') ?></small></td>
		    </tr>
    	    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Only available if you set a price for <em>Featured</em> jobs. Featured offers can only be used for the remaining Pack jobs. If job listers skip featured offers, the total featured jobs will always be equal or inferior to the total available jobs. For example, if a job lister skips one featured offer for a pack with 2 jobs and 2 featured offers, he will have only one featured offer left.','appthemes'); ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Featured Offers Count','appthemes') ?>:</td>
                <td class="forminp"><input name="feat_job_offers" id="feat_job_offers" type="text" value="<?php if (isset($job_pack['feat_job_offers'])) echo esc_attr($job_pack['feat_job_offers']); ?>" class="" <?php  echo esc_attr( !get_option('jr_cost_to_feature') ?'readonly' : '' ); ?> /><br/><small><?php _e('Promote your Packs by allowing job listers to feature jobs from the Pack, for Free. Enter a numeric value or leave blank.<br/>Not used on Free Job Packs.','appthemes') ?></small></td>
		    </tr>
			<thead>
	            <tr>
	                 <th scope="col" width="200px"><?php _e('Job Pack Additional Access','appthemes')?></th>
	                 <th scope="col">&nbsp;</th>
	            </tr>	
            </thead>

			<?php do_action('jr_admin_job_pack_access_options', $job_pack); ?>

		    <thead>
	            <tr>
	                 <th scope="col" width="200px"><?php _e('Job Pack Visibility','appthemes')?></th>
	                 <th scope="col">&nbsp;</th>
	            </tr>	
            </thead>			    
            <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('When creating Packs for specific job categories make sure you don\'t leave orphaned categories. JobRoller will display all the Job Packs in these cases.<br/><br/>To use this option make sure you set the job category has a required field','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Categories','appthemes') ?>:</td>
                <td class="forminp">
                <?php
                	$args = array (
                		'id'	     => 'job_cats',
                		'name'	     => 'job_cats',
                		'taxonomy'   => APP_TAX_CAT,
                		'exclude'    => get_option('jr_featured_category_id'),
                		'hide_empty' => 0	
                	); 
                	$job_cats = get_categories( $args );
                	
                	if ( !empty($job_pack['job_cats']) )  
                	    $job_pack['job_cats'] = explode(',', $job_pack['job_cats']);
                	else     
                	   	$job_pack['job_cats'] = array( 'all' );
                	   	                	 
                	$cat_required = get_option('jr_submit_cat_required');	
				?>
				<table class="multiple-checkbox">
  				<tr>
  				  <td colspan="4"> 					
						<input type="checkbox" name="job_cats_all" value="all" <?php  echo esc_attr( $cat_required == 'no' ?'readonly' : '' ); ?> <?php checked( $job_pack['job_cats'][0], 'all');  ?>><span><?php _e('All','appthemes') ?></span>
				   </td>
				 </tr>
				 <tr>  	
			   	   <td>												
						<?php
		                	$break = count( $job_cats) / 4;
		                						
							$i = 1;							
		                	foreach ( $job_cats as $job_cat ):
		                	?>
		                		<p><input type="checkbox" name="job_cats[]" value="<?php echo esc_attr($job_cat->term_id); ?>" <?php  echo esc_attr( $cat_required == 'no' ?'readonly' : '' ); ?> <?php checked( $cat_required == 'no' || in_array( (int)$job_cat->term_id, $job_pack['job_cats']), 1 ); ?>><span><?php echo $job_cat->cat_name; ?></span>                		
		                	<?php	
		                		// break the categories in columns
		                		if ( ($i % round($break)) == 0) 
		                			echo "</td><td>"; 
		                		$i++;
		                	endforeach; 
		                ?>
		                	
                	</td>
                  </tr>	
                </table>
                <br/><small><?php _e('The job categories where this Pack will be available. These will only be displayed to job listers when buying packs on the dashboard.','appthemes') ?></small></td>
		    </tr>
    		<tr>
                <td class="titledesc"><a href="#" tip="<?php _e('The order in which this Pack will be displayed. Packs are sorted in ascending order. The Pack with the lowest order will be the default.','appthemes'); ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Order','appthemes') ?>:</td>
                <td class="forminp"><input style="width: 50px" name="pack_order" id="pack_order" type="text" value="<?php if (isset($job_pack['pack_order'])) echo esc_attr($job_pack['pack_order']); else 1; ?>" class="" /><br/><small><?php _e('Enter a numeric value. Leaving blank will default to 1.','appthemes') ?></small></td>
		    </tr>
	    </table>
	    
	    <input name="submitted" type="hidden" value="yes" /></p>
	    
		<?php if  ($type == 'edit' ) :?>
		
	        <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save Pack','appthemes') ?>" />	    
	        
		<?php else: ?>
		
	        <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Create Job Pack','appthemes') ?>" />
			<input name="add_job_pack" type="hidden" value="yes" />
			
		<?php endif; ?>     
		   
    </form>
	<script type="text/javascript">
		/* <![CDATA[ */
		jQuery.noConflict();
		(function($) {

			function check_sel_cats() {

				var checked = 0;
				var size = $('input[name="job_cats[]"]').size();
						
				$('input[name="job_cats[]"]:checked').each( function() {				
					checked++;
				});

				if  ( checked == 0 ) $('input[name="job_cats_all"]').trigger('click');

				$('input[name="job_cats_all"]').attr('checked', checked == size || checked == 0);
				
			}
			
			$('input[name="job_cats_all"]').live('click', function() {

				if ( $(this).attr('checked') ) 					
					$('input[name="job_cats[]"]:not(:checked)').each( function() {
						$(this).attr('checked',true);						
					});						
				else
					check_sel_cats();	
				
			});
			
			$('input[name="job_cats[]"]').live('click', function() {			
				check_sel_cats();												
			});					

			check_sel_cats();
			
		})(jQuery);
		/* ]]> */
	</script>    	
<?php	
}

// job pack options admin page
function jr_job_packs_admin() {    
?>
    <div class="wrap jobroller">
        <div class="icon32" id="icon-options-general"><br/></div>
        <h2><?php _e('Job Packs','appthemes') ?></h2>

        <?php 
        	global $wpdb;
        	
        	if ( isset($_GET['edit']) ) :
				jr_edit_job_pack();
			else :
				if ( isset($_GET['delete']) ) :
					$deletepack = (int) $_GET['delete'];
					if ($deletepack > 0) :
						$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->jr_job_packs WHERE id = %d", $deletepack));
						echo '<p class="success">'.__('Pack Deleted','appthemes').'</p>';
					endif;
				endif;
				jr_job_packs();
			endif;
		?>	
    </div>	
	<?php
}

function jr_job_packs() {
	global $message, $errors, $posted;

	$errors = new WP_Error();
	$message = '';

	jr_add_job_pack(); 

	$packs = jr_get_job_packs();
	if (sizeof($packs)>0) :
	
		$i=1;
		$default = '1';
		foreach ($packs as $pack) :

			if ($i==1) echo "<div class='job-packs-wrapper'>";

			$display_args = array(
								'class' 	 => ($i==2?'job-pack-float-right':'job-pack-float-left'),
								'categories' => 'all',
								'order' 	 => 'yes',
								'selectable' => 'no'
							);

			jr_display_pack( 'paid', $pack, $default, $echo = TRUE, $display_args );

			if ($i==2) { echo "</div>"; $i=1; }
			else $i++;

			$default = '';
		endforeach;
		
	endif;
    ?>

	<div class="clear"></div>

    <script type="text/javascript">
    /* <![CDATA[ */
    	jQuery('a.deletepack').click(function(){
    		var answer = confirm ("<?php _e('Are you sure you want to delete this pack? This action cannot be undone...', 'appthemes'); ?>")
			if (answer)
				return true;
			return false;
    	});
    /* ]]> */
    </script>
	
	<?php jr_job_packs_options_form(); ?>
	
	<h3><?php _e('Create a New Job Pack','appthemes') ?></h3>
	<p><?php _e('Job Packs let you define packages that customers can purchase in order to post multiple/single jobs for varying durations. Once you add a pack the values on the "pricing" page will no longer be used.', 'appthemes'); ?></p>
	
	<?php
		do_action( 'appthemes_notices' );

		// display the job packs form
		jr_job_packs_form( $posted, 'create' );
}

function jr_edit_job_pack() {

	global $wpdb, $message, $errors;
	$errors = new WP_Error();
	$message = '';
	
	$edited_pack = (int) $_GET['edit'];
	
	if (!$edited_pack) :
		_e('Pack not found!', 'appthemes');
		exit;
	endif;
	
	// Get Job details
	$job_pack = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->jr_job_packs WHERE id = %d", $edited_pack));
	
	if (!$job_pack) :
		_e('Pack not found!', 'appthemes');
		exit;
	endif;
	
	if ($_POST) :
		
		$posted = array();
		
		$fields = array(
			'pack_name',
			'pack_description',
			'pack_duration',
			'pack_cost',
			'job_count',
			'job_duration',
			'job_offers',
			'feat_job_offers',
			'access',
			'pack_order'
		);

		foreach ($fields as $field) :
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
			else $posted[$field] = '';
		endforeach;
		
		$required = array(
			'pack_name' 		=> __('Pack name', 'appthemes'),
			'pack_description' 	=> __('Pack Description', 'appthemes'),
		);
		
		foreach ($required as $field=>$name)
			if (empty($posted[$field]))
				$errors->add('submit_error', __('<strong>ERROR</strong>: &ldquo;', 'appthemes').$name.__('&rdquo; is a required field.', 'appthemes'));

		$total_jobs = 0;
		if (!empty($posted['job_count'])) $total_jobs = $posted['job_count'];
		if (!empty($posted['job_offers'])) $total_jobs += $posted['job_offers'];

		if ( !empty($posted['job_count']) && !empty($posted['feat_job_offers']) && $posted['feat_job_offers'] > $total_jobs )
			$errors->add('submit_error', __('<strong>ERROR</strong>: ', 'appthemes').sprintf(__('Featured offers must be inferior to the Pack total jobs (Job Count+Job Offers).','appthemes'), $total_jobs, $posted['feat_job_offers']));

		// ignore the job categories if set to 'All'
		$job_cats = array();
		if ( isset($_POST['job_cats']) && !isset($_POST['job_cats_all']) ) foreach ( $_POST['job_cats'] as $term )  
			if ( term_exists( (int)$term, APP_TAX_CAT ) ) $job_cats[] = $term;		
							
		if ( !is_array($posted['access']) ) 
			$posted['access'] = array($posted['access']);
					
		// for FREE job packs ignore job offers 
		if ( !$posted['pack_cost'] )
			$posted['job_offers'] = $posted['feat_job_offers'] = '';
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			$wpdb->update( $wpdb->jr_job_packs, array( 
				'pack_name' 			=> $posted['pack_name'],
				'pack_description' 		=> $posted['pack_description'],
				'pack_duration' 		=> $posted['pack_duration'],
				'pack_cost' 			=> $posted['pack_cost'],
				'job_count' 			=> $posted['job_count'],
				'job_duration'			=> $posted['job_duration'],			
				'job_offers'			=> $posted['job_offers'],				
				'feat_job_offers'		=> $posted['feat_job_offers'],
				'access'				=> implode(',', $posted['access']),
				'job_cats'				=> implode(',', $job_cats),
				'pack_order'			=> ($posted['pack_order']>0?$posted['pack_order']:1),
			), array( 'id' => $edited_pack ), array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' ) );
			
			$message = __('Pack updated successfully', 'appthemes');
			
			$job_pack = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->jr_job_packs WHERE id = %d", $edited_pack));
		
		}
		
	endif;
    ?>
	
	<h3><?php _e('Edit Job Pack','appthemes') ?></h3>
	
	<?php
		do_action( 'appthemes_notices' );

		// display the job packs form				
		jr_job_packs_form( get_object_vars($job_pack), 'edit' );

}

function jr_add_job_pack() {	
	global $wpdb, $errors, $message, $posted;
	
	if ($_POST && isset($_POST['add_job_pack'])) :
		
		$posted = array();
		
		$fields = array(
			'pack_name',
			'pack_description',
			'pack_duration',
			'pack_cost',
			'job_count',
			'job_duration',
			'job_offers',
			'feat_job_offers',
			'access',
			'pack_order'					
		);	
		
		foreach ($fields as $field) :
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
			else $posted[$field] = '';
		endforeach;
		
		$required = array(
			'pack_name' => __('Pack name', 'appthemes'),
			'pack_description' => __('Pack Description', 'appthemes'),
		);
		
		foreach ($required as $field=>$name)
			if (empty($posted[$field]))
				$errors->add('submit_error', __('<strong>ERROR</strong>: &ldquo;', 'appthemes').$name.__('&rdquo; is a required field.', 'appthemes'));

		$total_jobs = 0;
		if (!empty($posted['job_count'])) $total_jobs = $posted['job_count'];
		if (!empty($posted['job_offers'])) $total_jobs += $posted['job_offers'];

		if ( !empty($posted['job_count']) && !empty($posted['feat_job_offers']) && $posted['feat_job_offers'] > $total_jobs )
			$errors->add('submit_error', __('<strong>ERROR</strong>: ', 'appthemes').sprintf(__('Featured offers must be inferior to the Pack total jobs (Job Count+Job Offers).','appthemes'), $total_jobs, $posted['feat_job_offers']));

		// ignore the job categories if set to 'All'
		$job_cats = array();
		if ( isset($_POST['job_cats']) && !isset($_POST['job_cats_all']) ) foreach ( $_POST['job_cats'] as $term )  
			if ( term_exists( (int)$term, APP_TAX_CAT ) ) $job_cats[] = $term;		
							
		if ( !is_array($posted['access']) ) 
			$posted['access'] = array($posted['access']);
		
		// for FREE job packs ignore offers 
		if ( !$posted['pack_cost'] )
			$posted['job_offers'] = $posted['feat_job_offers'] = '';
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			$wpdb->insert( $wpdb->jr_job_packs, array( 
				'pack_name' 			=> $posted['pack_name'],
				'pack_description' 		=> $posted['pack_description'],
				'pack_duration' 		=> $posted['pack_duration'],
				'pack_cost' 			=> $posted['pack_cost'],
				'job_count' 			=> $posted['job_count'],
				'job_duration'			=> $posted['job_duration'],
				'job_offers'			=> $posted['job_offers'],				
				'feat_job_offers'		=> $posted['feat_job_offers'],
				'access'				=> implode(',', $posted['access']),
				'job_cats'				=> implode(',', $job_cats),
				'pack_order'			=> ($posted['pack_order']>0?$posted['pack_order']:1),	
			), array( '%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s','%s' ) );
			
			$message = __('Pack added successfully', 'appthemes');
		
		}
		
	endif;
}

// display resume access options
function jr_job_pack_access_resumes( $job_pack ) {
	global $app_abbr;
?>
    <tr>
    	<?php 
		if ( isset($job_pack['access']) ) $pack_access = array($job_pack['access']); else $pack_access = array();
    	?>
    	
        <td class="titledesc"><a href="#" tip="<?php _e('Only avaialable if job seeker registrations are enabled. Choose if you want to allow Employers to browse and/or view Resumes for the Pack duration.','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Resumes','appthemes') ?>:</td>
        <td class="forminp"><select name="access" id="access" class="" style="min-width: 150px;" <?php  echo esc_attr( get_option('jr_allow_job_seekers') == 'no' ?'readonly' : '' );?>/>
        	<option value="none" <?php selected($pack_access, 'none'); ?>><?php _e('None', 'appthemes'); ?></option>
			<option value="resume_browse" <?php selected(in_array('resume_browse', $pack_access), TRUE); ?>><?php _e('Browse', 'appthemes'); ?></option>
			<option value="resume_view"  <?php selected(in_array('resume_view',$pack_access),TRUE);  ?>><?php _e('View', 'appthemes'); ?></option>										               
			<option value="resume_browse,resume_view" <?php selected(in_array('resume_browse,resume_view', $pack_access),TRUE); ?>><?php _e('Browse/View', 'appthemes'); ?></option>
        </select>
        <br/><small><?php _e('If enabled, Employers will have temporary access to View or/and Browse Resumes until the Pack expires.','appthemes') ?></small></td>
    </tr>
<?php
}

add_action('jr_admin_job_pack_access_options','jr_job_pack_access_resumes');

?>
