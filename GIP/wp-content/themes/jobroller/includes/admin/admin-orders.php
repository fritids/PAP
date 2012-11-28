<?php
function jr_orders() {
    global $wpdb, $message;
    
    $message = '';
    
    if (isset($_GET['export'])) :
    	
    	ob_end_clean();
    	header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=jobroller_export_".date('Ymd').".csv");

    	$jr_orders = new jr_orders();
    	
    	$csv = array();
    	
    	$row = array("ID","User","Job","Pack","Featured","Cost","Order Date","Payment Date","Payer","Payment type","Txn ID","Approval Method","Order Status");
    	
    	$csv[] = '"'.implode('","', $row).'"';
	            
	    $row = array();

        if (sizeof($jr_orders->orders) > 0) :
        
            foreach( $jr_orders->orders as $order ) :

            $user_info = get_userdata($order->user_id);

                $row[] = $order->id;

                $row[] = '#'.$user_info->ID.' - '.$user_info->first_name.' '.$user_info->last_name.' ('.$user_info->user_email.')';
                 
            	if ($order->job_id>0) :
            		$job_post = get_post( $order->job_id );
            		$row[] = '#'.$order->job_id.' - '.$job_post->post_title;
            	else :
            		$row[] = '';
            	endif;
  
                if ($order->pack_id>0) $row[] = $order->pack_id; else $row[] = '';
                
                if ($order->featured) $row[] = __('Yes','appthemes'); else $row[] = __('No','appthemes');
                
                if ($order->cost) $row[] = jr_get_currency($order->cost); else $row[] = __('Free', 'appthemes');
                
                $row[] = mysql2date(get_option('date_format') .' '. get_option('time_format'), $order->order_date);
                    
                if ($order->payment_date) $row[] = mysql2date(get_option('date_format') .' '. get_option('time_format'), $order->payment_date); else $row[] = '';
	            
	            if ($order->payer_first_name || $order->payer_last_name) $row[] = trim($order->payer_first_name.' '.$order->payer_last_name).', '.trim($order->payer_address); else $row[] = '';
	            
	            if ($order->payment_type) $row[] = trim($order->payment_type); else $row[] = '';
	            
	            if ($order->transaction_id) $row[] = trim($order->transaction_id); else $row[] = '';
	                    
	            if ($order->approval_method) $row[] = trim($order->approval_method); else $row[] = '';
	            
	            $row[] = $order->status;
	            
	            $row = array_map('trim', $row);
	            $row = array_map('html_entity_decode', $row);
	            $row = array_map('addslashes', $row);
	            
	            $csv[] = '"'.implode('","', $row).'"';
	            
	            $row = array();
                    
			endforeach;
              
		endif;
		
		echo implode("\n", $csv);
		exit;
    	
    endif;
    
    if (isset($_GET['paid'])) :
    	
    	$paid_listing = (int) $_GET['paid'];
    	
    	if ($paid_listing>0) :
    	
    		$order = new jr_order( $paid_listing );

    		$order->complete_order( __('Manual', 'appthemes') );
    		
    		$message = __('Order complete.','appthemes');

    	endif;
    	
    endif;
    
    if (isset($_GET['cancel'])) :
    	
    	$cancelled_listing = (int) $_GET['cancel'];
    	
    	if ($cancelled_listing>0) :
    	
    		$order = new jr_order( $cancelled_listing );
    		
    		$order->cancel_order();
    		
    		$message = __('Order cancelled.','appthemes');
    	  		
    	endif;
    	
    endif;
?>
<div class="wrap jobroller">
    <div class="icon32" id="icon-themes"><br/></div>
    <h2><?php _e('Orders','appthemes') ?> <a href="admin.php?page=orders&amp;export=true" class="button" title=""><?php _e('Export CSV', 'appthemes'); ?></a></h2>

    <?php do_action( 'appthemes_notices' ); ?>

	<?php
		$jr_orders = new jr_orders();
		
		if (isset($_GET['p'])) $page = $_GET['p']; else $page = 1;
		
		$dir = 'ASC';
		$sort = 'ID';
		
		$per_page = 20;
		$total_pages = 1;
			
		$show = 'pending_payment';
		
		if (isset($_GET['show'])) :
			switch ($_GET['show']) :
				case "completed" :
					$show = 'completed';
					$total_pages = ceil($jr_orders->completed_count/$per_page);
				break;
				case "cancelled" :
					$show = 'cancelled';
					$total_pages = ceil($jr_orders->cancelled_count/$per_page);
				break;
				default :
					$total_pages = ceil($jr_orders->pending_count/$per_page);
				break;
			endswitch;
		else :
			$_GET['show'] = '';
			$total_pages = ceil($jr_orders->pending_count/$per_page);
		endif;	
		
		if (isset($_GET['dir'])) $posteddir = $_GET['dir']; else $posteddir = '';
		if (isset($_GET['sort'])) $postedsort = $_GET['sort']; else $postedsort = '';
	
		$order_args = array(
			'status'  => $show,
			'offset'  => $per_page*($page-1),
			'limit'	  => $per_page,
			'orderby' => $postedsort,
			'order'   => $posteddir,
		);
		$jr_orders->get_orders( $order_args );
	?>
	<div class="tablenav">
		<div class="tablenav-pages alignright">
			<?php
				if ($total_pages>1) {
				
					echo paginate_links( array(
						'base' => 'admin.php?page=orders&show='.$_GET['show'].'%_%&sort='.$postedsort.'&dir='.$posteddir,
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
			<li><a href="admin.php?page=orders" <?php if ($show == 'pending_payment') echo 'class="current"'; ?>><?php _e('Pending' ,'appthemes'); ?> <span class="count">(<?php echo $jr_orders->pending_count; ?>)</span></a> |</li>
			<li><a href="admin.php?page=orders&show=completed" <?php if ($show == 'completed') echo 'class="current"'; ?>><?php _e('Completed' ,'appthemes'); ?> <span class="count">(<?php echo $jr_orders->completed_count; ?>)</span></a> |</li>
			<li><a href="admin.php?page=orders&show=cancelled" <?php if ($show == 'cancelled') echo 'class="current"'; ?>><?php _e('Cancelled' ,'appthemes'); ?> <span class="count">(<?php echo $jr_orders->cancelled_count; ?>)</span></a></li>
		</ul>
	</div>
	
	<div class="clear"></div>

    <table class="widefat fixed">

        <thead>
            <tr>
                <th scope="col" style="width:3em;"><a href="<?php echo jr_echo_ordering_link('id', 'DESC'); ?>"><?php _e('ID','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('user_id', 'ASC'); ?>"><?php _e('User','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('job_id', 'ASC'); ?>"><?php _e('Job','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('pack_id', 'ASC'); ?>"><?php _e('Pack','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('featured', 'DESC'); ?>"><?php _e('Featured','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('cost', 'DESC'); ?>"><?php _e('Total Cost','appthemes') ?></a></th>
                <th scope="col"><a href="<?php echo jr_echo_ordering_link('order_date', 'DESC'); ?>"><?php _e('Order Date','appthemes') ?></a></th>
                
                <?php if ($show!=='pending_payment' && $show!=='cancelled') : ?>
	                <th scope="col"><a href="<?php echo jr_echo_ordering_link('payment_date', 'DESC'); ?>"><?php _e('Payment Date','appthemes') ?></a></th>
	                <th scope="col"><?php _e('Payer','appthemes') ?></th>
	                <th scope="col"><a href="<?php echo jr_echo_ordering_link('payment_type', 'ASC'); ?>"><?php _e('Payment type','appthemes') ?></a></th>
	                <th scope="col"><a href="<?php echo jr_echo_ordering_link('transaction_id', 'ASC'); ?>"><?php _e('Txn ID','appthemes') ?></a></th>
	                <th scope="col"><a href="<?php echo jr_echo_ordering_link('approval_method', 'ASC'); ?>"><?php _e('Approval Method','appthemes') ?></a></th>
                <?php endif; ?>
                
                <th scope="col"><?php _e('Actions','appthemes') ?></th>
            </tr>
        </thead>
	<?php if (sizeof($jr_orders->orders) > 0) :
            $rowclass = '';
            ?>
            <tbody id="list">
            <?php
                foreach( $jr_orders->orders as $order ) :

                $rowclass = 'even' == $rowclass ? 'alt' : 'even';
                
                if ($order->user_id) $user_info = get_userdata($order->user_id);
				?>
                <tr class="<?php echo $rowclass ?>">
                    <td><?php echo $order->id ?></td>

                    <td><?php if ($user_info) : ?>#<?php echo $user_info->ID; ?> &ndash; <strong><?php echo $user_info->first_name ?> <?php echo $user_info->last_name ?></strong><br/><a href="mailto:<?php echo $user_info->user_email ?>"><?php echo $user_info->user_email ?></a><?php endif; ?></td>
                    <td>
                    	<?php 
                    	if ($order->job_id>0) :
                    		$job_post = get_post( $order->job_id );
                    		if ($job_post) :
                    			echo '<a href="post.php?action=edit&post='.$order->job_id.'">';
                    			echo '#'.$order->job_id.' &ndash; '.$job_post->post_title;
                    			echo '</a>';
                    		else :
                    			echo '#'.$order->job_id;
                    		endif;
                    	else :
                    		_e('N/A', 'appthemes');
                    	endif;
                    ?>
                    </td>
                    <td><?php if ($order->pack_id>0) echo $order->pack_id; else _e('N/A', 'appthemes'); ?></td>
                    <td><?php if ($order->featured) echo __('Yes','appthemes'); else echo __('No','appthemes'); ?></td>
                    <td><?php if ($order->cost) echo jr_get_currency($order->cost); else _e('Free', 'appthemes'); ?></td>
                    <td><?php echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $order->order_date) ?></td>
                    
                    <?php if ($show!=='pending_payment' && $show!=='cancelled') : ?>
                    
	                    <td><?php if ($order->payment_date) echo mysql2date(get_option('date_format') .' '. get_option('time_format'), $order->payment_date); else echo __('N/A','appthemes'); ?></td>
	                    <td><?php if ($order->payer_first_name || $order->payer_last_name) echo trim($order->payer_first_name.' '.$order->payer_last_name).'<br/>'.trim($order->payer_address); else echo __('N/A','appthemes'); ?></td>
	                    <td><?php if ($order->payment_type) echo trim($order->payment_type); else echo __('N/A','appthemes'); ?></td>
	                    <td><?php if ($order->transaction_id) echo trim($order->transaction_id); else echo __('N/A','appthemes'); ?></td>
	                    
	                    <td><?php if ($order->approval_method) echo trim($order->approval_method); else echo __('N/A','appthemes'); ?></td>
                    
                    <?php endif; ?>
                    
                    <td>
                    	<?php if ($order->status=='pending_payment') : ?>
							<a href="admin.php?page=orders&amp;paid=<?php echo $order->id; ?>" class="button button-primary"><?php _e('Mark as paid','appthemes'); ?></a>
							<a href="admin.php?page=orders&amp;cancel=<?php echo $order->id; ?>" class="button cancel"><?php _e('Cancel','appthemes'); ?></a>
                    	<?php else : ?>
                    		<?php _e('N/A', 'appthemes'); ?>
                    	<?php endif; ?>
                    </td>
                </tr>
              <?php endforeach; ?>

              </tbody>

        <?php else : ?>
            <tr><td colspan="<?php if ($show!=='pending_payment' && $show!=='cancelled') : ?>15<?php else : ?>8<?php endif; ?>"><?php _e('No orders found.','appthemes') ?></td></tr>
        <?php endif; ?>        
    </table>
    <br />
    <script type="text/javascript">
    /* <![CDATA[ */
    	jQuery('a.cancel').click(function(){
    		var answer = confirm ("<?php _e('Are you sure you want to cancel this order? The order will be cancelled and the Job Post will be deleted from the system.', 'appthemes'); ?>");
			if (answer) return true;
			return false;
    	});
    /* ]]> */
    </script>
</div><!-- end wrap -->
<?php
}

function jr_echo_ordering_link( $sort = 'id', $dir = 'ASC' ) {
	
	if (isset($_GET['show'])) $show = $_GET['show']; else $show = 'pending_payment';
	if (isset($_GET['p'])) $page = $_GET['p']; else $page = 1;
	if (isset($_GET['dir'])) $posteddir = $_GET['dir']; else $posteddir = '';
	if (isset($_GET['sort'])) $postedsort = $_GET['sort']; else $postedsort = '';
	
	echo 'admin.php?page=orders&amp;show='.$show.'&amp;p='. $page .'&amp;sort='.$sort.'&amp;dir=';
	
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