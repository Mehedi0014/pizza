<?php
global $wpdb;
$terms_table = $wpdb->prefix .'terms';
$attribute_table = $wpdb->prefix .'woocommerce_attribute_taxonomies';

$t_sql  = "ALTER TABLE $terms_table ADD `price` TEXT NULL AFTER `term_group`";
$a_sql  = "ALTER TABLE $attribute_table ADD `special` TEXT NULL AFTER `attribute_public`";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//dbDelta($sql); ==== New Table
$wpdb->query($t_sql );  // Add Column
$wpdb->query($a_sql );  // Add Column
flush_rewrite_rules(); // not important

$wpdb->get_results(" INSERT INTO ".$wpdb->prefix."options (option_name, option_value) VALUES ('Small Pizza Price', .50),('Medium Pizza Price',.75), ('Large Pizza Price', 1), ('Party Pizza Price', 2), ('Party Price Free Count', 4) ");
$wpdb->flush();