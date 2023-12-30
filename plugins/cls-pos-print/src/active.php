<?php
global $wpdb;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
$wpdb->insert(" INSERT INTO ".$wpdb->prefix."options (option_name, option_value) VALUES ('POS Printer IP1', '' ),('POS Printer PORT1',9100), ('Enable Print When Create Order', 1), ('POS Printer IP2', ''),('POS Printer PORT2',9100)");

$wpdb->flush();