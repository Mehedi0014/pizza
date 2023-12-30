<?php
global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
$wpdb->query(
    $wpdb->query( "DELETE FROM ".$wpdb->prefix."options WHERE option_name IN ('POS Printer IP1','POS Printer PORT1','Enable Print When Create Order','POS Printer IP2','POS Printer PORT2'")
);
$wpdb->flush();