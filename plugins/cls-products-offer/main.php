<?php

/*
 * Plugin Name: CLS Products Offer
 * Version: 1.2.0
 * Plugin URI: https://cls-computer.de
 * Description: CLS Offer Customization.With this module you can create offer easily. Discout offer ,  Free Offer , Fixed Price Offer with so many options.
 * Author URI: https://nazmul-alam.com
 * Author: Mohammad Nazmul Alam
 * Requires at least: PHP version 7.*
 * Tested up to: 7.*
 * Requires PHP: 7.*
 * Text Domain: woo-custom-product-addons
 * WC requires at least: 4.3.0
 * WC tested up to: 4.9.0
 */

if( ! defined( 'ABSPATH' ) ) {
    die;
}

 define( 'CLS_OFFER_DIR', plugin_dir_path( __FILE__ ) );
 define( 'CLS_OFFER_PATH', plugins_url( '/', __FILE__ ) );
 define( 'CLS_OFFER_FILE', __FILE__ );

 require_once(CLS_OFFER_DIR . 'Main/CLSofferMain.php');

    $clsProductsOffer = new CLSofferMain;


