<?php
/*
Template Name: Contact Page
*/
?>
<?php 

	$errors = new WP_Error();
	$message = '';
	
	// Form Processing Script	
	if (isset($_POST['submit-form'])) {		
		
		$required = array('your_name', 'email', 'message');
		
		// Identify exploits
		$head_expl = "/(bcc:|cc:|document.cookie|document.write|onclick|onload)/i";
		$inpt_expl = "/(content-type|to:|bcc:|cc:|document.cookie|document.write|onclick|onload)/i";
		
		// Get post data 
		$posted = array();
		
		$posted['your_name'] = $_POST['your_name'];
		$posted['email'] = $_POST['email'];
		$posted['message'] = $_POST['message'];
		$posted['spam-trap'] = $_POST['honeypot'];
		
		// Clean post data & validate fields
		foreach ($posted as $key => $val) {
			$val = strip_tags(stripslashes(trim($val)));
			
			if (in_array($key, $required)) {
				if (empty($val)) $errors->add('submit_error', __('Required field "','appthemes').$key.__('" missing.','appthemes'));
			}
			
			if ($key=='email') {
				if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $posted['email'])) {
					$errors->add('submit_error', __('Invalid email address.', 'appthemes'));
				}
			}
			
			if (!empty($posted['spam-trap'])) {
				$errors->add('submit_error', __('Possible spam: You filled the honeypot spam-trap field!', 'appthemes'));	
			}
			
			if(preg_match($inpt_expl, $val)) {
	 			$errors->add('submit_error', __('Injection Exploit Detected: It seems that you&#8217;re possibly trying to apply a header or input injection exploit in our form. If you are, please stop at once! If not, please go back and check to make sure you haven&#8217;t entered <strong>content-type</strong>, <strong>to:</strong>, <strong>bcc:</strong>, <strong>cc:</strong>, <strong>document.cookie</strong>, <strong>document.write</strong>, <strong>onclick</strong>, or <strong>onload</strong> in any of the form inputs. If you have and you&#8217;re trying to send a legitimate message, for security reasons, please find another way of communicating these terms.', 'appthemes'));	
	 		}	
		}
						
		// Show errors or continue
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			// Prepare email
			$subject = "[".get_bloginfo('name')."] ".__('Contact from','appthemes')." ".$posted['your_name']."";
			
			$sendto = get_option('admin_email'); 
				
    		$ltd = date("l, F jS, Y \\a\\t g:i a", time());
			$ip = getenv("REMOTE_ADDR");
			$hr = getenv("HTTP_REFERER");
			$hst = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
			$ua = $_SERVER['HTTP_USER_AGENT'];
			
			$email_header = 'From: '.get_bloginfo('name') . "\r\n";
			$email_header .= 'Reply-To: '.$posted['email'] . "\r\n";
			
			if(preg_match($head_expl, $email_header)) {
			
				$errors[] = 'Injection Exploit Detected: It seems that you&#8217;re possibly trying to apply a header or input injection exploit in our form. If you are, please stop at once! If not, please go back and check to make sure you haven&#8217;t entered <strong>content-type</strong>, <strong>to:</strong>, <strong>bcc:</strong>, <strong>cc:</strong>, <strong>document.cookie</strong>, <strong>document.write</strong>, <strong>onclick</strong>, or <strong>onload</strong> in any of the form inputs. If you have and you&#8217;re trying to send a legitimate message, for security reasons, please find another way of communicating these terms.';
				
			} else {

				$content = "Hello,\n\nYou are being contacted via ".get_bloginfo('name')." by ".$posted['your_name'].". ".$posted['your_name']." has provided the following information so you may contact them:\n\n   Email: ".$posted['email']."\n\nMessage:\n   ".$posted['message']."\n\n--------------------------\nOther Data and Information:\n   IP Address: $ip\n   Time Stamp: $ltd\n   Referrer: $hr\n   Host: $hst\n   User Agent: $ua\n\n";
	
				$content = stripslashes(strip_tags(trim($content)));	
				
				// Send email
				wp_mail( $sendto, $subject, $content, $email_header);
				
				// Show Thanks					
				$message = __('Thank you. Your message has been sent.','appthemes');
				
				unset($posted);
			
			}
		}
	}
?>
<?php get_header(); ?>

	<div class="section">

		<div class="section_content">

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>

					<h1><?php the_title(); ?></h1>

					<?php the_content(); ?>

					<?php do_action( 'appthemes_notices' );	?>

					<!-- Contact Form -->
					<form method="post" action="<?php echo get_permalink($post->ID); ?>" class="main_form">
						
						<p><label for="your_name"><?php _e('Your Name/Company Name', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text" name="your_name" id="your_name" value="<?php if (isset($posted['your_name'])) echo $posted['your_name']; ?>" /></p>
						<p><label for="email"><?php _e('Your email', 'appthemes'); ?> <span title="required">*</span></label> <input type="text" class="text" name="email" id="email" value="<?php if (isset($posted['email'])) echo $posted['email'];	 ?>" /></p>

						<p><label for="message"><?php _e('Message', 'appthemes'); ?> <span title="required">*</span></label> <textarea name="message" id="message" cols="60" rows="8"><?php if (isset($posted['message'])) echo $posted['message'];	 ?></textarea></p>
						
						<p class="button"><input type="submit" name="submit-form" class="submit" id="submit-form" value="<?php _e('Submit', 'appthemes'); ?>" /><input type="text" name="honeypot" value="" style="position: absolute; left: -999em;" title="" /></p>
					</form>

			<?php endwhile; ?>

			<?php endif; ?>

			<div class="clear"></div>

		</div><!-- end section_content -->

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('page'); ?>

<?php get_footer(); ?>
