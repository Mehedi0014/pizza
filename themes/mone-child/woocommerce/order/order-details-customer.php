<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="container woocommerce-customer-details">

	<?php if ( $show_shipping ) : ?>

	<section class="row mt-5 woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses addresses">
		<div class="col-md-6 woocommerce-column woocommerce-column--1 woocommerce-column--billing-address">
			<div class="orderPageBillingWrapper p-4 h-100">
				<?php endif; ?>
				<h2 class="woocommerce-column__title"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>
				<address>
					<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

					<?php if ( $order->get_billing_phone() ) : ?>
						<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
					<?php endif; ?>

					<?php if ( $order->get_billing_email() ) : ?>
						<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
					<?php endif; ?>
				</address>
				<?php if ( $show_shipping ) : ?>
			</div>
		</div><!-- /.col-md-6 -->

		<div class="col-md-6 mt-md-0 mt-5 woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address">
			<div class="orderPageShippingWrapper p-4 h-100">
				<h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
				<address>
					<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
				</address>
			</div>
		</div><!-- /.col-md-6 -->
	</section><!-- /.row -->

	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>