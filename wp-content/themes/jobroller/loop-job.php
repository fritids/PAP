<?php
/**
 * Main loop for displaying jobs
 *
 * @package JobRoller
 * @author AppThemes
 *
 */
?>

<?php appthemes_before_loop( 'job_listing' ); ?>

<?php if (have_posts()) : $alt = 1; ?>

    <ol class="jobs">

        <?php while (have_posts()) : the_post(); ?>
		
			<?php appthemes_before_post( 'job_listing' ); ?>

            <?php
				global $featured_job_cat_id;

				$post_class = array('job');
				$expired = jr_check_expired($post);

				if ($expired) {
					$post_class[] = 'job-expired';
					$action = get_option('jr_expired_action');
					if ($action=='hide') :
						continue;
					endif;
				}
				$alt=$alt*-1;

				if ($alt==1) $post_class[] = 'job-alt';
				if ( is_object_in_term( $post->ID, 'job_cat', array($featured_job_cat_id) ) ) $post_class[] = 'job-featured';
            ?>

            <li class="<?php echo implode(' ', $post_class); ?>">

                <dl>

                    <dt><?php _e('Type','appthemes'); ?></dt>
                    <dd class="type"><?php jr_get_custom_taxonomy($post->ID, 'job_type', 'jtype'); ?></dd>

                    <dt><?php _e('Job', 'appthemes'); ?></dt>
					
					<?php appthemes_before_post_title( 'job_listing' ); ?>

                    <dd class="title">
						<strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
						<?php jr_job_author(); ?>
                    </dd>

					<?php appthemes_after_post_title( 'job_listing' ); ?>

                    <dt><?php _e('Location', 'appthemes'); ?></dt>
					<dd class="location"><?php jr_location(); ?></dd>

                    <dt><?php _e('Date Posted', 'appthemes'); ?></dt>
                    <dd class="date"><strong><?php echo date_i18n(__('j M','appthemes'), strtotime($post->post_date)); ?></strong> <span class="year"><?php echo date_i18n(__('Y','appthemes'), strtotime($post->post_date)); ?></span></dd>

                </dl>

            </li>
			
			<?php appthemes_after_post( 'job_listing' ); ?>

        <?php endwhile; ?>
		
		<?php appthemes_after_endwhile( 'job_listing' ); ?>

    </ol>

<?php else: ?>

	<?php appthemes_loop_else( 'job_listing' ); ?>        
	
<?php endif; ?>

<?php appthemes_after_loop( 'job_listing' ); ?>

