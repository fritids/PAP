<?php
/*
Template Name: My Dashboard Template
*/

get_header();

if (current_user_can('can_submit_job')) :
	get_template_part('tpl-myjobs');
else :
	get_template_part('tpl-job-seeker-dashboard');
endif;