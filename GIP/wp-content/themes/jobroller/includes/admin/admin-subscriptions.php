<?php
function jr_subscriptions() {
    global $wpdb, $message;
    
    $message = '';
            
    if (isset($_GET['start'])) :
    	
    	$user_id = (int) $_GET['start'];
    	
    	if ($user_id>0) :
    	
			do_action('user_resume_subscription_started', $user_id);
			
    		$message = __('Resume subscription started.','appthemes');

    	endif;
    	
    endif;
    
   if (isset($_GET['trial'])) :
    	
    	$user_id = (int) $_GET['trial'];
    	
    	if ($user_id>0) :
    	
			do_action('user_resume_trial_started', $user_id);
			do_action('user_resume_subscription_started', $user_id);
			
    		$message = __('Resume trial started.','appthemes');

    	endif;
    	
    endif;
	
    if (isset($_GET['end'])) :
    	
    	$user_id = (int) $_GET['end'];
    	
    	if ($user_id>0) :
    	
			do_action('user_resume_subscription_ended', $user_id);
	
    		$message = __('Resume Subscription ended.','appthemes');
    	  		
    	endif;
    	
    endif;
	
    if (isset($_GET['reset_trial'])) :
    	
    	$user_id = (int) $_GET['reset_trial'];
    	
    	if ($user_id>0) :
    	
			delete_user_meta($user_id, '_valid_resume_trial');	
	
    		$message = __('Resume Trial reset.','appthemes');
    	  		
    	endif;
    	
    endif;	
?>
<div class="wrap jobroller">
    <div class="icon32" id="icon-themes"><br/></div>
    <h2><?php _e('Subscriptions','appthemes') ?></h2>

    <?php do_action( 'appthemes_notices' );	?>

	<?php		
		if (isset($_GET['p'])) $page = $_GET['p']; else $page = 1;
		
		$dir = 'ASC';
		$sort = 'ID';
		
		$per_page = 20;
		$total_pages = 1;
			
		$show = 'pending_payment';
		
		$totals = jr_get_count_subscriptions();
		
		if (isset($_GET['show'])) :
		
			switch ($_GET['show']) :
				case "active" :
					$show = 'active';
					$total_pages = ceil($totals['active']/$per_page);
				break;
				case "inactive" :
					$show = 'inactive';
					$total_pages = ceil($totals['inactive']/$per_page);
				break;
				default :
					$total_pages = ceil($totals['pending']/$per_page);
				break;
			endswitch;
			
		else :
			$_GET['show'] = '';
		endif;	
		
		if (isset($_GET['dir'])) $posteddir = $_GET['dir']; else $posteddir = '';
		if (isset($_GET['sort'])) $postedsort = $_GET['sort']; else $postedsort = '';	

		$subcriptions = jr_list_subscriptions($show, $per_page*($page-1), $per_page, $postedsort, $posteddir);	
		
	?>
	<div class="tablenav">
		<div class="tablenav-pages alignright">
			<?php
				if ($total_pages>1) {
				
					echo paginate_links( array(
						'base' => 'admin.php?page=subscriptions&show='.$_GET['show'].'%_%&sort='.$postedsort.'&dir='.$posteddir,
						'format' => '&p=%#%',
						'prev_text' => __('&laquo; Previous'),
						'next_text' => __('Next &raquo;'),
						'total' => $total_pages,
						'current' => $page,
						'end_size' => 1,
						'mid_size' => 5,
					));
				}
			?>	
	    </div> 
	    
	    <ul class="subsubsub">
			<li><a href="admin.php?page=subscriptions" <?php if ($show == 'pending_payment') echo 'class="current"'; ?>><?php _e('Pending' ,'appthemes'); ?> <span class="count">(<?php echo $totals['pending']; ?>)</span></a> |</li>
			<li><a href="admin.php?page=subscriptions&show=active" <?php if ($show == 'active') echo 'class="current"'; ?>><?php _e('Active' ,'appthemes'); ?> <span class="count">(<?php echo $totals['active']; ?>)</span></a> |</li>
			<li><a href="admin.php?page=subscriptions&show=inactive" <?php if ($show == 'inactive') echo 'class="current"'; ?>><?php _e('Inactive' ,'appthemes'); ?> <span class="count">(<?php echo $totals['inactive']; ?>)</span></a></li>
		</ul>
	</div>
	
	<div class="clear"></div>

    <table class="widefat fixed">

        <thead>
            <tr>
                <th scope="col" style="width:20%;"><a href="<?php echo jr_echo_subscription_link('user_id', 'ASC'); ?>"><?php _e('User','appthemes') ?></a></th>
				<?php if ($show!=='inactive') : ?>
					<th scope="col"><a href="<?php echo jr_echo_subscription_link('order_date', 'DESC'); ?>"><?php _e('Order Date','appthemes') ?></a></th>
				<?php endif; ?>	
                
                <?php if ($show!=='pending_payment') : ?>				
						
						<th scope="col"><a href="<?php echo jr_echo_subscription_link('trial', 'ASC'); ?>"><?php _e('* Trial?','appthemes') ?></a></th>				
					
	                <th scope="col"><a href="<?php echo jr_echo_subscription_link('start_date', 'DESC'); ?>"><?php _e('Start Date','appthemes') ?></a></th>
					<th scope="col"><a href="<?php echo jr_echo_subscription_link('end_date', 'ASC'); ?>"><?php _e('End Date','appthemes') ?></a></th>
                <?php endif; ?>
                
                <th scope="col"  style="width:15%;"><?php _e('Actions','appthemes') ?></th>
            </tr>
        </thead>
	<?php if (sizeof($subcriptions) > 0) :
            $rowclass = '';
            ?>
            <tbody id="list">
            <?php
                foreach( $subcriptions as $subscription ) :

                $rowclass = 'even' == $rowclass ? 'alt' : 'even';
                
                if ($subscription->user_id) $user_info = get_userdata($subscription->user_id);
				
					// get meta data
					$recurr_type_manual = jr_resume_is_active_manual_subscr();
					$active = get_user_meta( $subscription->user_id, '_valid_resume_subscription', true );
					$trial = get_user_meta( $subscription->user_id, '_valid_resume_trial', true );
					
					$trial_expired = 0;
					
					if ( $trial == '1' ): 
						$trial = __( 'Active','appthemes');					
					else:
						if ($trial == '0'):
							$trial = '<del>'.__('Used', 'appthemes').'</del>';
							$trial_expired = 1;
						else:
							$trial = __('Not Used', 'appthemes');;						
						endif; 							
					endif;			
					
					$start_date = get_user_meta( $subscription->user_id, '_valid_resume_subscription_start', true );
					$end_date = get_user_meta( $subscription->user_id, '_valid_resume_subscription_end', true );
					$order_date = get_user_meta( $subscription->user_id, '_valid_resume_subscription_order', true );
					
				?>
                <tr class="<?php echo $rowclass ?>">
                    <td><?php if ($user_info) : ?>#<?php echo $user_info->ID; ?> &ndash; <strong><?php echo $user_info->first_name ?> <?php echo $user_info->last_name ?></strong> (<?php echo $user_info->display_name ?>)<br/><a href="mailto:<?php echo $user_info->user_email ?>"><?php echo $user_info->user_email ?></a><?php endif; ?></td>                    
					<?php if ($show!=='inactive') : ?>
						<td><?php if ($order_date) echo date_i18n(__('F j, Y  g:i:s a','appthemes'), $order_date);  else  echo __('N/A','appthemes'); ?></td>
					<?php endif; ?>
                    
                    <?php if ($show!=='pending_payment') : ?>
									
							<td><?php if ($recurr_type_manual) echo $trial; else echo __('N/A', 'appthemes');  ?></td>                    
						
	                    <td><?php if ($start_date) echo date_i18n(__('F j, Y g:i:s a','appthemes'), $start_date); else echo __('N/A','appthemes'); ?></td>
						<td><?php if ($end_date) echo date_i18n(__('F j, Y g:i:s a','appthemes'), $end_date); else echo __('N/A','appthemes'); ?></td>
                    
                    <?php endif; ?>
                    
                    <td>
                    	<?php if (!$active) : ?>
                    		<a href="admin.php?page=subscriptions&amp;start=<?php echo $subscription->user_id; ?>" class="button button-primary start-subscription"><?php _e('Start Subscription?','appthemes'); ?></a> 
							<a href="admin.php?page=subscriptions&amp;trial=<?php echo $subscription->user_id; ?>" class="button button-primary start-trial"><?php _e('Start Trial?','appthemes'); ?></a> 
							<?php if ($recurr_type_manual && $trial_expired && $show!=='pending_payment') : ?>
								<a href="admin.php?page=subscriptions&amp;reset_trial=<?php echo $subscription->user_id; ?>" class="button button-secondary reset-trial"><?php _e('Reset Trial?','appthemes'); ?></a> 						
							<?php endif; ?>									
                    	<?php else : ?>
							<?php if ($active) : ?>
								<a href="admin.php?page=subscriptions&amp;end=<?php echo $subscription->user_id; ?>" class="button button-primary end-subscription"><?php _e('End Subscription?','appthemes'); ?></a> 
							<?php else: ?>
								<?php echo __('N/A', 'appthemes'); ?>
							<?php endif; ?>		
                    	<?php endif; ?>
                    </td>
                </tr>
              <?php endforeach; ?>

              </tbody>

        <?php else : ?>
            <tr><td colspan="<?php if ($show!=='pending_payment' && $show!=='inactive') : ?>6<?php else : ?>3<?php endif; ?>"><?php _e('No subscriptions found.','appthemes') ?></td></tr>
        <?php endif; ?>        
    </table>
	<?php if ($show!=='pending_payment') : ?>
	<p>* <?php echo __('Information only available for manual recurring payments.', 'appthemes'); ?></p>
	<?php endif; ?>
    <br />
    <script type="text/javascript">
    /* <![CDATA[ */
    	jQuery('a.end-subscription').click(function(){
    		var answer = confirm ("<?php _e('Are you sure you want to end this subscription?', 'appthemes'); ?>");
			if (answer) return true;
			return false;
    	});		
    	jQuery('a.reset-trial').click(function(){
    		var answer = confirm ("<?php _e('This will allow this user to start a new Trial period. Continue?', 'appthemes'); ?>");
			if (answer) return true;
			return false;
    	});				
    /* ]]> */
    </script>
</div><!-- end wrap -->
<?php
}

function jr_echo_subscription_link( $sort = 'id', $dir = 'ASC' ) {
	
	if (isset($_GET['show'])) $show = $_GET['show']; else $show = 'pending_payment';
	if (isset($_GET['p'])) $page = $_GET['p']; else $page = 1;
	if (isset($_GET['dir'])) $posteddir = $_GET['dir']; else $posteddir = '';
	if (isset($_GET['sort'])) $postedsort = $_GET['sort']; else $postedsort = '';
	
	echo 'admin.php?page=subscriptions&amp;show='.$show.'&amp;p='. $page .'&amp;sort='.$sort.'&amp;dir=';
	
	if ($sort==$postedsort) :
		if ($posteddir==$dir) :
			if ($posteddir=='ASC') echo 'DESC';
			else echo 'ASC';
		else :
			echo $dir;
		endif;
	else :
		echo $dir;
	endif;
}

// Returns the subscriptions list
function jr_list_subscriptions ( $show = '', $offset = 0, $limit = 20, $orderby = 'user_id', $order = 'ASC' ) {
	global $wpdb;

	if (!$orderby) $orderby = 'user_id';
	if (!$order) $order = 'ASC';
	
	// subscription meta to allow sorting
	$sql_subscr_meta = 
		" 	
			(
				SELECT distinct ID AS user_id,
					( SELECT meta_value AS order_date FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription_order' AND users.ID = user_id) AS order_date,
					( SELECT meta_value AS start_date FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription_start' AND users.ID = user_id) AS start_date,
					( SELECT meta_value AS end_date FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription_end' AND users.ID = user_id) AS end_date,
					( SELECT meta_value AS trial FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_trial' AND users.ID = user_id) AS trial
				FROM ".$wpdb->prefix."users AS users
			) AS user_subscr_meta
			WHERE user_subscr_meta.user_id = user_meta.user_id
		";
	
	switch ($show):
		case 'active':
			$sql = "SELECT distinct user_meta.user_id, order_date, start_date, end_date, trial FROM ".$wpdb->prefix."usermeta AS user_meta, "
					. $sql_subscr_meta .
				   " AND meta_key = '_valid_resume_subscription' AND meta_value = '1' ";
		break;
		case 'inactive':
			$sql = "SELECT distinct user_meta.user_id, start_date, end_date, trial FROM ".$wpdb->prefix."usermeta AS user_meta, "
					. $sql_subscr_meta .
				    " AND user_meta.user_id NOT IN ( SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription' AND meta_value = '1' OR ( meta_key = '_valid_resume_subscription_order' ) )";				
		break;
		default;	
			// pending
			$sql = "SELECT distinct user_meta.user_id FROM ".$wpdb->prefix."usermeta AS user_meta, "
					. $sql_subscr_meta . 
					" AND meta_key = '_valid_resume_subscription_order'
					  AND user_meta.user_id NOT IN ( SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription' AND meta_value = '1' )";
	endswitch;

	$query = $wpdb->prepare( $sql . " ORDER BY ".$orderby." ".$order." LIMIT $offset, $limit;" );						
	$results = $wpdb->get_results($query);

	return $results;	

}

// Returns the subscriptions list
function jr_get_count_subscriptions () {
	global $wpdb;

	$sql = 
		"
			SELECT active, inactive, pending FROM
			(
				SELECT COUNT(DISTINCT user_id) AS active FROM ".$wpdb->prefix."usermeta 
				WHERE meta_key = '_valid_resume_subscription' AND meta_value = '1'
			) c_active,
			(
			SELECT count(distinct user_id) AS inactive FROM 
				".$wpdb->prefix."usermeta 
				WHERE user_id NOT IN ( SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription' AND meta_value = '1'  OR ( meta_key = '_valid_resume_subscription_order' ) )
			) c_inactive,
			(
				SELECT COUNT(DISTINCT user_id) AS pending FROM
				".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription_order' AND user_id NOT IN ( SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = '_valid_resume_subscription' AND meta_value = '1' )
			) c_pending			
		";		
	
	$query = $wpdb->prepare( $sql );
	$totals = $wpdb->get_row($query, ARRAY_A);

	return $totals;	

}