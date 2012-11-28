<?php
add_action('jr_resume_footer', 'jr_contact_resume_parts');

function jr_contact_resume_parts($post) {
	$resume = $post->ID;
	?>
	<div style="display:none">
		
		<form id="contact" action="<?php echo get_permalink($resume); ?>" class="submit_form main_form contact_form modal_form" method="post">
			<h2><?php echo sprintf(__('Contact %s', 'appthemes'), wptexturize(get_the_author_meta('display_name'))); ?></h2>
			<p><?php echo sprintf(__('Please fill in the following form to contact %s', 'appthemes'),wptexturize(get_the_author_meta('display_name'))); ?></p>
			
			<?php wp_nonce_field('contact-resume-author_' . $post->post_author) ?>
			<p><label for="contact_name"><?php _e('Your Name', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text required" name="contact_name" id="contact_name" /></p>
			<p><label for="contact_name"><?php _e('Your Email', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text required" name="contact_email" id="contact_email" /></p>
			<p><label for="contact_subject"><?php _e('Subject', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text required" name="contact_subject" id="contact_subject" /></p>
			<p><label for="contact_message"><?php _e('Message', 'appthemes'); ?> <span title="required">*</span></label> <textarea class="required" name="contact_message" id="contact_message" ></textarea></p>
			
			<p><input type="submit" class="submit" name="send_message" value="<?php _e('Send', 'appthemes'); ?>" /></p>
		</form>
		
		<script type="text/javascript">
		/* <![CDATA[ */
						
			// Validation
			jQuery('input[name=send_message]').click(function(event){
			
				jQuery('.required').each( function() {
									
					if (jQuery(this).val() == '') {					
						jQuery('#fancybox-content form ul.errors').remove();					
						jQuery('#fancybox-content form h2').after('<ul class="errors"><li><?php _e('All fields are required.', 'appthemes'); ?></li></ul>');						
						event.preventDefault();

					} else {

						if 	( jQuery(this).attr('name') == 'contact_email' ) {
						
							function isValidEmailAddress(emailAddress) {
								var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
								return pattern.test(emailAddress);
							}									
							
							if ( !isValidEmailAddress( jQuery(this).val() ) ) {
								jQuery('#fancybox-content form ul.errors').remove();																
								jQuery('#fancybox-content form h2').after('<ul class="errors"><li><?php _e('Invalid email address.', 'appthemes'); ?></li></ul>');
								event.preventDefault();
							}						
							
						}
						
					}
				});

			});
				
		/* ]]> */
		</script>
		
	</div>
	<?php
}
