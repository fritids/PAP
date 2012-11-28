<?php
/*
Formulário de registo
*/
function jr_register_form( $action = '', $role = 'job_lister' ) {

?>
  <fieldset class="at_fieldset">
   <legend><?php _e('Personal Information (required)', 'appthemes'); ?></legend>
   <p>
     <label for="at_name"><?php _e('Name', 'appthemes'); ?></label><br/>
     <input class="text" type="text" name="at_name" id="at_name" value=<?php if (isset($_POST['at_name'])) echo esc_attr($_POST['at_name']); ?>>		
   </p>
   <p>
     <input type="checkbox" name="at_age" id="at_age" value="yes" <?php echo checked( ! empty( $_POST['at_age'] ) )?> /> 
     <label for="age"><?php _e('I\'m over 18 ', 'appthemes'); ?></label>		
  </p>		
  </fieldset>
  <br/>
  <fieldset class="at_fieldset">
   <legend><?php _e('Additional Information', 'appthemes'); ?></legend>		
   <p>
     <label for="at_referral"><?php _e( 'How did you find us?', 'appthemes' ); ?></label><br/>
     <select name="at_referral" id="at_referral">
       <option value=""></option>
       <option value="Friend" <?php echo selected ( isset( $_POST['at_referral'] ) && 'Friend' == $_POST['at_referral'] ); ?> ><?php _e('Friend', 'appthemes'); ?></option>
       <option value="Search Engine" <?php echo selected ( isset( $_POST['at_referral'] ) && 'Search Engine' == $_POST['at_referral'] ); ?> ><?php _e('Search Engine', 'appthemes'); ?></option>
       <option value="Advertising" <?php echo selected ( isset( $_POST['at_referral'] ) && 'Advertising' == $_POST['at_referral'] ); ?> ><?php _e('Avertising', 'appthemes'); ?></option>
     </select>
   </p>
   <p>
     <label for="at_referral_id"><?php _e('Referral ID', 'appthemes'); ?></label><br/>
     <input type="text" class="text" class="at_number" name="at_referral_id" id="at_referral_id" value=<?php if (isset($_POST['at_referral_id'])) echo esc_attr($_POST['at_referral_id']); ?>>
     <p class="at_referral_note"><?php _e('If you have a Referral ID please insert in the field above. It will give you access to special discounts to you and the referral.', 'appthemes'); ?></p>
    </p>
     <input type="submit" class="submit" tabindex="7" name="register" value="<?php _e('Create Account &rarr;', 'appthemes'); ?>" />
    </fieldset>	
    <style type="text/css">
	.at_fieldset { border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
	.at_referral_note { font-size: 12px; color: #ccc; }
	.at_number { width: 100px }
    </style>
<?php
}
