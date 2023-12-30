<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             1.9.12
 * @package           Buy_One_Get_One_Free_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Buy one get one free
 * Plugin URI:        piwebsolution.com/bogo-deals-woocommerce
 * Description:       Buy X and Get Y unit of product free kind for Deals in WooCommerce
 * Version:           1.9.12
 * Author:            PI Websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buy-one-get-one-free-woocommerce
 * Domain Path:       /languages
 * WC tested up to: 5.3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* 
    Making sure woocommerce is there 
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active( 'buy-one-get-one-free-pro/buy-one-get-one-free-woocommerce.php')){
    function pisol_bogo_pro_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'You have the PRO version of <strong>Buy one get one free</strong> plugin active, deactivate it then you can use free version', 'buy-one-get-one-free-woocommerce'); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_bogo_pro_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}else{

if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pisol_bogo_deal() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'buy-one-get-one-free-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_bogo_deal' );
    return;
}

/**
 * Currently plugin version.
 * Start at version 1.9.12 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BUY_ONE_GET_ONE_FREE_WOOCOMMERCE_VERSION', '1.9.12' );

define( 'PISOL_BOGO_DELETE_SETTING', false );
define( 'PI_BOGO_BUY_URL', 'https://www.piwebsolution.com/checkout/?add-to-cart=2231&variation_id=2232' );
define( 'PI_BOGO_PRICE', '$25' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buy-one-get-one-free-woocommerce-activator.php
 */
function activate_buy_one_get_one_free_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buy-one-get-one-free-woocommerce-activator.php';
	Buy_One_Get_One_Free_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buy-one-get-one-free-woocommerce-deactivator.php
 */
function deactivate_buy_one_get_one_free_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buy-one-get-one-free-woocommerce-deactivator.php';
	Buy_One_Get_One_Free_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buy_one_get_one_free_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_buy_one_get_one_free_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buy-one-get-one-free-woocommerce.php';

function pisol_bogo_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-bogo-deal' ) ) . '">' . __( 'Settings' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="' . esc_url(PI_BOGO_BUY_URL) . '">' . __( 'Buy PRO Version','buy-one-get-one-free-woocommerce' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pisol_bogo_plugin_link' );

if(!function_exists('pisol_wc_version_check')){
function pisol_wc_version_check( $version = '3.0' ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}
	}
	return false;
}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.7
 */
function run_buy_one_get_one_free_woocommerce() {

	$plugin = new Buy_One_Get_One_Free_Woocommerce();
	$plugin->run();

}
run_buy_one_get_one_free_woocommerce();

}