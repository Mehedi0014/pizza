<?php

global $wpdb;
$subscribe_table = $wpdb->prefix . 'gctl_subscribe_newsletter';			

$sql = "
	CREATE TABLE `mpmihzzsa_gctl_subscribe_newsletter` (
	 `ID` int(11) NOT NULL AUTO_INCREMENT,
	 `email` varchar(255) NOT NULL,
	 `subscribe_at` timestamp NOT NULL DEFAULT current_timestamp(),
	 PRIMARY KEY (`ID`)
	) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4
";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

$this->gctlSubscribeNewsletterMenu();
flush_rewrite_rules();