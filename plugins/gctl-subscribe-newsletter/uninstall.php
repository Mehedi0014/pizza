<?php

/**
*Trigger this file on plugin uninstall
*
*/



// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
//$option_name = 'wporg_option';
 
//delete_option($option_name);
 
// for site options in Multisite
//delete_site_option($option_name);
 
// drop a custom database table
global $wpdb;
$subscribe_table = $wpdb->prefix . 'gctl_subscribe_newsletter';
$wpdb->query( "DROP TABLE IF EXISTS $subscribe_table" );