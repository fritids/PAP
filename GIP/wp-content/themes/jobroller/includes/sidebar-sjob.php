<?php if (get_option('jr_submit_page_id')) : ?>

    <li class="widget widget-submit">
		
		<?php if (!is_user_logged_in() || (is_user_logged_in() && current_user_can('can_submit_job'))) : ?>
        <div>

            <a href="<?php echo get_permalink(get_option('jr_submit_page_id')); ?>" class="button"><span><?php _e('Submit a Job','appthemes'); ?></span></a>
            <?php if ($text = get_option('jr_jobs_submit_text')) : echo wpautop(wptexturize($text)); else :
                $packs = jr_get_job_packs();
                    if (sizeof($packs) == 0) :
	                // display standard pricing
	                $amount = get_option('jr_jobs_listing_cost');
	                if ($amount && $amount>0) : echo '<p class="pricing"><em>'.jr_get_currency($amount).'</em> '.__('for','appthemes').' <em>30 '.__('days','appthemes').'</em></p>'; endif;
	            endif;
            endif; ?>

        </div>
        <?php endif; ?>
       
        <?php if (is_user_logged_in() && current_user_can('can_submit_resume')) : ?>
        
        	<?php if (get_option('jr_allow_job_seekers')=='yes') : ?>
        		<div>
		            <a href="<?php echo get_permalink(get_option('jr_dashboard_page_id')); ?>" class="button"><span><?php _e('My Dashboard','appthemes'); ?></span></a>
		            <?php if ($text = get_option('jr_my_profile_button_text')) echo wpautop(wptexturize($text)); ?>
		        </div>
        	<?php endif; ?>
        
        <?php endif; ?>

    </li>

<?php endif; ?>    