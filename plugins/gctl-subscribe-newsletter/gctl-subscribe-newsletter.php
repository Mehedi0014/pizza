<?php
/**
 * Plugin Name:       Gctl Subscribe Newsletter
 * Plugin URI:        https://gctlled.com/
 * Description:       This plugin save all email address in your database.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            GCTL
 * Author URI:        https://gctlled.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages

 {Plugin Name} is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.
 
 {Plugin Name} is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with {Plugin Name}. If not, see {URI to Plugin License}.
**/

	if( ! defined( 'ABSPATH' ) ) {
		die;
	}

	if ( !class_exists( 'GctlSubscribeNewsletter' ) ) {
		class GctlSubscribeNewsletter
		{
			public $errorMsg;
			public $pluginBasename;



			function __construct(){
				$this->pluginBasename = plugin_basename( __FILE__ );
			}


			function autoRunRegister(){
				add_action('admin_menu', array( $this, 'gctlSubscribeNewsletterMenu') );
				add_action( 'wp_head', array( $this, 'gctlSubscribeNewsletterFormCapture') );
				add_shortcode( 'gctlSubscribeNewsletter', array( $this, 'gctlSubscribeNewsletterFunction') );
				add_filter( "plugin_action_links_$this->pluginBasename", array( $this, 'settings_link') );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style_admin_panel_script') );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_user_panel_script') );
			}


			function activate(){
				require_once plugin_dir_path( __FILE__ ) . 'active.php';
			}


			function deactivate(){
				require_once plugin_dir_path( __FILE__ ) . 'deactive.php';
			}


			/*Make a menu in admin backing panel ===========================*/
			function gctlSubscribeNewsletterMenu(){
				add_menu_page( 
					'Subscribe Newsletter', // page Title
					'Subscribe Newsletter', // menu title
					'manage_options', // user access capability
					'gctl_subscribe_newsletter_page', // page menu slug
					array($this, 'gctl_subscribe_newsletter_dashboard'), // callback function
					'dashicons-email-alt2', // icon image url
					null // position, where the menu display
				);
			}


			/*callback function  ===========================*/
			function gctl_subscribe_newsletter_dashboard(){
				require_once plugin_dir_path( __FILE__ ) . 'admin-table.php';
			}


			function settings_link( $links ){
				$settings_link = '<a href="admin.php?page=gctl_subscribe_newsletter_page">Settings</a>';
				array_push( $links, $settings_link);
				return $links;
			}


			/* Sent data in database table from Subscribetion form ===================================*/
			function gctlSubscribeNewsletterFormCapture(){			
				global $wpdb;
				if (array_key_exists('submitForm', $_POST)) {
				$message = $_POST['emailAddress'];				
					if (empty($message)) {
						//echo "<script type='text/javascript'>window.location.href = 'subscribe-to-newsletter-failed-for-blank';</script>";
						//echo "<script type='text/javascript'>alert('Email is required');</script>";
						//echo "<script type='text/javascript'>window.location.href = '".home_url()."';</script>";
						$this->errorMsg = "Email is required";
					} else {
						if (!filter_var($message, FILTER_VALIDATE_EMAIL)) {
							$this->errorMsg = "Invalide email formate";
						}else{
							$subscribersEmails = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gctl_subscribe_newsletter WHERE email = '".$message."'");
							if (empty($subscribersEmails)) {
								$wpdb->get_results(" INSERT INTO ".$wpdb->prefix."gctl_subscribe_newsletter (email) VALUES ('".$message."') ");
								$this->errorMsg = "Thank you for subscribe";
							}else{
								$this->errorMsg = "You are already Subscribed";
							}
						}
					}
				}
			}


			/*Subscribetion form ===================================*/
			function gctlSubscribeNewsletterFunction(){
				require_once plugin_dir_path( __FILE__ ) . 'subscribe-form.php';
			}


			/*Add css and js in plugin for admin panel ===========================*/
			function enqueue_style_admin_panel_script(){
				require_once plugin_dir_path( __FILE__ ) . 'enqueue-css-js-for-admin.php';
			}


			/*Add css and js in plugin for User/Front End ===========================*/
			function enqueue_style_user_panel_script(){
				require_once plugin_dir_path( __FILE__ ) . 'enqueue-css-js-for-user.php';
			}

		} // end class GctlSubscribeNewsletter ===============================
	} // end if condition of class GctlSubscribeNewsletter ===================


	if (class_exists('GctlSubscribeNewsletter')){
		$gctlSubscribeNewsletter = new GctlSubscribeNewsletter();
		$gctlSubscribeNewsletter->autoRunRegister();
	}



	// activation
	register_activation_hook( __FILE__, array( $gctlSubscribeNewsletter, 'activate') );
	// deactivation
	register_deactivation_hook( __FILE__, array( $gctlSubscribeNewsletter, 'deactivate') );