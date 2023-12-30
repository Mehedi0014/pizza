<?php

/**
 * Fired during plugin activation
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Buy_One_Get_One_Free_Woocommerce
 * @subpackage Buy_One_Get_One_Free_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Buy_One_Get_One_Free_Woocommerce
 * @subpackage Buy_One_Get_One_Free_Woocommerce/includes
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Buy_One_Get_One_Free_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('pisol_bogo_redirect', true);
	}

}
