<?php

/*
 * Plugin Name: CLS shipping zone & local pickup
 * Version: 0.0.1
 * Plugin URI: https://cls-computer.de
 * Description: CLS shipping zone & local pickup.
 * Author URI: https://nazmul-alam.com
 * Author: Mohammad Nazmul Alam
 * Requires at least: 1.0
 * Tested up to: 7.*
 * Requires PHP: 7.*
 * Text Domain: woo-custom-product-addons
 * WC requires at least: 3.3.0
 * WC tested up to: 4.9.0
 */

if( ! defined( 'ABSPATH' ) ) {
    die;
}


/*
=======================================================================================
>>> Add some zone under german country
=======================================================================================
*/
add_filter( 'woocommerce_states', 'custom_shipping_zone_for_germany');
function custom_shipping_zone_for_germany( $states ){
    $states['DE'] = array(
        'DECLS001' => 'Lampertheim',
        'DECLS002' => 'Rosengarten',
        'DECLS003' => 'Burstadt',
        'DECLS004' => 'Hofheim',
        'DECLS005' => 'Neuschloss',
        'DECLS006' => 'Scharhof',
        'DECLS007' => 'Schonau',
        'DECLS008' => 'Blumenau',
        'DECLS009' => 'Sandhofen'
    );

    return $states;
}


/*
=======================================================================================
>>> Set a minimum amount of order based on shipping zone before checking out
=======================================================================================
*/

function cw_min_num_products() {

    if( is_cart() || is_checkout() ) {
        global $woocommerce;
        // Set the minimum order amount and shipping zone before checking out
        // Need to Dynamic
        $shipping_city_all = array('DECLS001', 'DECLS002', 'DECLS003', 'DECLS004', 'DECLS005', 'DECLS006', 'DECLS007', 'DECLS008', 'DECLS009');
        $minimum0 = 0;
        $shipping_city0 = array('DECLS001');
        $minimum1 = 20;
        $shipping_city1 = array('DECLS003', 'DECLS005', 'DECLS006');
        $minimum2 = 35;
        $shipping_city2 = array('DECLS007', 'DECLS008', 'DECLS009');
        $minimum3 = 30;
        $shipping_city3 = array('DECLS002', 'DECLS004');
        // Defining var total amount
        $cart_total_order = WC()->cart->total;
        // get country Code
        $country_code = WC()->customer->get_shipping_country();

        $cart = WC()->cart;

        $percentage = 10; // <=== Discount percentage
        $chosen_shipping_method_id = WC()->session->get('chosen_shipping_methods')[0];
        $chosen_shipping_method = explode(':', $chosen_shipping_method_id)[0];

        if ($chosen_shipping_method !== 'local_pickup' ) {

            if (in_array(WC()->customer->get_shipping_state($country_code), $shipping_city_all) == "") {
                wc_add_notice(sprintf('<strong>Leider bieten wir derzeit keine Dienstleistungen in Ihrer Nähe an.</strong>'), 'error');
            } else {

                // Compare values and add an error in Cart's total amount. Happens to be less than the minimum required before checking out. Will display a message along the lines
                if ($cart_total_order < $minimum0 && in_array(WC()->customer->get_shipping_state($country_code), $shipping_city0)) {
                    wc_add_notice(sprintf('<span class="h5">Keine Mindestbestellmenge in Ihrer Zone.</span>'
                        . '<br />Aktueller Auftrag: €%s.',
                        $minimum0,
                        $cart_total_order),
                        'error');
                } elseif ($cart_total_order < $minimum2 && in_array(WC()->customer->get_shipping_state($country_code), $shipping_city2)) {
                    wc_add_notice(sprintf('<span class="h5">Vor dem Auschecken ist eine Mindestbestellmenge von €%s erforderlich.</span>'
                        . '<br />Aktueller Auftrag: €%s.',
                        $minimum2,
                        $cart_total_order),
                        'error');
                } elseif ($cart_total_order < $minimum1 && in_array(WC()->customer->get_shipping_state($country_code), $shipping_city1)) {
                    wc_add_notice(sprintf('<span class="h5">Vor dem Auschecken ist eine Mindestbestellmenge von €%s erforderlich.</span>'
                        . '<br />Aktueller Auftrag: €%s.',
                        $minimum1,
                        $cart_total_order),
                        'error');
                } elseif ($cart_total_order < $minimum3 && in_array(WC()->customer->get_shipping_state($country_code), $shipping_city3)) {
                    wc_add_notice(sprintf('<span class="h5">Vor dem Auschecken ist eine Mindestbestellmenge von €%s erforderlich.</span>'
                        . '<br />Aktueller Auftrag: €%s.',
                        $minimum3,
                        $cart_total_order),
                        'error');
                }
            }
        }
    } // end 1st if
} // end function

add_action( 'woocommerce_check_cart_items', 'cw_min_num_products' );




/*
=======================================================================================
>>> Set a limited number of zip code for shipping zone
=======================================================================================
*/

function checkZipCode(){
    $country_postcode_all = array('69502', '74538', '68642', '65719', '68623', '68307');
     $country_postcode_all_var = " 69502, 74538, 68642, 65719, 68623, 68307";
    $country_postcode = WC()->customer->get_shipping_postcode();

    $chosen_shipping_method_id = WC()->session->get('chosen_shipping_methods')[0];
    $chosen_shipping_method = explode(':', $chosen_shipping_method_id)[0];

    if ($chosen_shipping_method !== 'local_pickup' ) {
        if (empty($country_postcode)) {
            wc_add_notice(sprintf('<h6>PLZ-Code ist leer.</h6>', $country_postcode),'error');
        }elseif (!in_array($country_postcode, $country_postcode_all)) {
            wc_add_notice(sprintf('<h6>Falscher PLZ-Code: %s. Unser PLZ-Codebereich ist: %s</h6>', $country_postcode, $country_postcode_all_var),'error');
        }
    }
}
add_action( 'woocommerce_check_cart_items', 'checkZipCode' );




/*
=======================================================================================
>>> Set a custom discount amount for local pickup shipping zone
=======================================================================================
*/

function custom_discount_for_pickup_shipping_method( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $percentage = 10; // <=== Discount percentage
    $chosen_shipping_method_id = WC()->session->get( 'chosen_shipping_methods' )[0];
    $chosen_shipping_method = explode(':', $chosen_shipping_method_id)[0];

    // Only for Local pickup chosen shipping method
    if ( strpos( $chosen_shipping_method_id, 'local_pickup' ) !== false ) {

        // Calculate the discount
        $discount = $cart->get_subtotal() * $percentage / 100;
        // Add the discount
        $cart->add_fee( __('Rabatt') . ' (' . $percentage . '%)', -$discount );
    }
}

add_action('woocommerce_cart_calculate_fees', 'custom_discount_for_pickup_shipping_method', 10, 1 );