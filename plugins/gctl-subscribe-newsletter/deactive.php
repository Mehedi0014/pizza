<?php

global $wpdb;
$subscribe_table = $wpdb->prefix . 'gctl_subscribe_newsletter';

$wpdb->query( "DROP TABLE IF EXISTS $subscribe_table" );


flush_rewrite_rules();