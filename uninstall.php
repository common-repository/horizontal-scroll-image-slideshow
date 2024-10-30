<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('hsis_title');
delete_option('hsis_width');
delete_option('hsis_height');
delete_option('hsis_bgcolor');
delete_option('hsis_speed');
 
// for site options in Multisite
delete_site_option('hsis_title');
delete_site_option('hsis_width');
delete_site_option('hsis_height');
delete_site_option('hsis_bgcolor');
delete_site_option('hsis_speed');
delete_site_option('hsis_dir');