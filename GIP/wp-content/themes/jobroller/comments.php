<?php
/**
 * @package AppThemes
 * @subpackage JobRoller
 * Comments for the job posts
 */


// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die (__('Please do not load this page directly. Thanks!', 'appthemes'));

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','appthemes'); ?></p>
<?php
	return;
}
?>

<div class="section">

	<div class="section_content section_content_comments">

		<?php appthemes_before_pings(); ?>

		<?php if(!empty($comments_by_type['pings'])) : // if have pings ?>

			<h2><?php _e('Trackbacks/Pingbacks', 'appthemes'); ?></h2>

			<ol id="comment-list" class="commentlist">

				<?php appthemes_list_pings(); ?>

			</ol>

		<?php endif; ?>

		<?php appthemes_after_pings(); ?>
		
		

		<?php appthemes_before_comments(); ?>

		<?php if ( have_comments() ) : ?>

			<h2><?php comments_number(__('No Responses', 'appthemes'), __('One Response', 'appthemes'), __('% Responses', 'appthemes') );?> <?php _e('to', 'appthemes'); ?> &#8220;<?php the_title(); ?>&#8221;</h2>

			<ol id="comment-list" class="commentlist">

				<?php appthemes_list_comments(); ?>

			</ol>

			<div class="comment-paging">

				<?php paginate_comments_links(); ?>

			</div><!-- end comment-paging -->

		<?php endif; ?>

		<?php appthemes_after_comments(); ?>
		


		<?php if ( ! comments_open() && have_comments() ) : ?>

			<p><?php _e('Sorry, the comment form is closed at this time.', 'appthemes'); ?></p>

		<?php endif; ?>		
		


		<?php if ('open' == $post->comment_status) : ?>

			<?php appthemes_before_respond(); ?>

			<div id="respond">

				<?php if(get_option('comment_registration') && !$user_ID) : ?>

					<h3><?php _e('Leave a Reply', 'appthemes'); ?></h3>
					<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'appthemes'), wp_login_url( get_permalink() )); ?></p>

				<?php else : ?>
				
					<?php appthemes_before_comments_form(); ?>

					<?php appthemes_comments_form(); ?>
					
					<?php appthemes_after_comments_form(); ?>

				<?php endif; ?>
				
			</div><!-- end respond -->
			
			<?php appthemes_after_respond(); ?>

		<?php endif; ?>

	</div><!-- end section_content -->

</div><!-- end section -->

