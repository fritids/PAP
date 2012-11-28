/*
 * JobRoller admin jQuery functions
 * Written by AppThemes
 *
 * Copyright (c) 2010 App Themes (http://appthemes.com)
 *
 * Built for use with the jQuery library
 * http://jquery.com
 *
 * Version 1.2
 *
 */

// <![CDATA[

/* initialize the tooltip feature */
jQuery(document).ready(function(){

	jQuery("td.titledesc a").easyTooltip();
	
	/* upload logo and images */
	jQuery('.jobroller .upload_button').click(function() {
		formfield = jQuery(this).attr('rel');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	/* send the uploaded image url to the field */
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src'); // get the image url
		imgoutput = '<img src="' + imgurl + '" />'; //get the html to output for the image preview
		jQuery('#' + formfield).val(imgurl);		
		jQuery('#' + formfield).siblings('.upload_image_preview').slideDown().html(imgoutput);
		tb_remove();
	}	
	
});

///* dashboard loader script */
//jQuery(function () {
//    jQuery('.insider').hide(); //hide all the content boxes on the page
//});
//
//var i = 0; //initialize
//var int = 0; //IE fix
//jQuery(window).bind("load", function() { //The load event will only fire if the entire page or document is fully loaded
//    var int = setInterval("doThis(i)",500); //500 is the fade in speed in milliseconds
//});
//
//function doThis() {
//    var item = jQuery('.insider').length; //count the number of elements on the page
//    if (i >= item) { // Loop through the elements
//        clearInterval(int); //When it reaches the last element the loop ends
//    }
//    jQuery('.insider:hidden').eq(0).fadeIn(500); //fades in the hidden elements one by one
//    i++; //add 1 to the count
//}
///* end dashboard loader script */

// ]]>
