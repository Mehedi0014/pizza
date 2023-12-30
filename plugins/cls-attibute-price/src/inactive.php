<?php

global $wpdb;
$terms_table = $wpdb->prefix .'terms';
$t_sql = "ALTER TABLE $terms_table DROP COLUMN `price`";

$attribute_table = $wpdb->prefix .'woocommerce_attribute_taxonomies';
$a_sql = "ALTER TABLE $attribute_table DROP COLUMN `special`";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$row1 = $wpdb->get_results(  "SELECT `price` FROM $terms_table" );

if(!empty($row1)){
    $wpdb->query($t_sql);
}

$row2 = $wpdb->get_results(  "SELECT `special` FROM $attribute_table" );

if(!empty($row2)){
    $wpdb->query($a_sql);
}


$wpdb->query( 
    $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."options WHERE option_name IN ('Small Pizza Price','Medium Pizza Price','Large Pizza Price','Party Pizza Price','Party Price Free Count')")
);
$wpdb->flush();