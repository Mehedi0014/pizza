<?php
/**
 * Shipping Calculator
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/shipping-calculator.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_shipping_calculator' ); ?>

<form class="woocommerce-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php printf( '<a href="#" class="shipping-calculator-button">%s</a>', esc_html( ! empty( $button_text ) ? $button_text : __( 'Calculate shipping', 'woocommerce' ) ) ); ?>

	<section class="shipping-calculator-form" style="display:none;">

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) ) : ?>
			<div class="d-flex justify-content-between">
				<p class="p-0 m-0 text-left forWidth">Land</p>
				<p class="form-row form-row-wide text-left" id="calc_shipping_country_field">
					<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select" rel="calc_shipping_state">
						<option value="default"><?php esc_html_e( 'Select a country / region&hellip;', 'woocommerce' ); ?></option>
						<?php
						foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
							echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
						}
						?>
					</select>
				</p>
			</div>
		<?php endif; ?>

		
		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) ) : ?>
			<div class="d-flex justify-content-between">
				<p class="p-0 m-0 text-left forWidth">Stadt</p>
				<p class="form-row form-row-wide text-left" id="calc_shipping_state_field">
					<?php
					$current_cc = WC()->customer->get_shipping_country();
					$current_r  = WC()->customer->get_shipping_state();
					$states     = WC()->countries->get_states( $current_cc );

					if ( is_array( $states ) && empty( $states ) ) {
						?>
						<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" />
						<?php
					} elseif ( is_array( $states ) ) {
						?>
						<span class="w-100">
							<select name="calc_shipping_state" class="state_select" id="calc_shipping_state" data-placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>">
								<option value=""><?php esc_html_e( 'Select an option&hellip;', 'woocommerce' ); ?></option>
								<?php
								foreach ( $states as $ckey => $cvalue ) {
									echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
								}
								?>
							</select>
						</span>
						<?php
					} else {
						?>
						<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
						<?php
					}
					?>
				</p>
			</div>
		<?php endif; ?>


<!--		<?php //if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) ) : ?>
			<p class="p-0 m-0 text-left">City</p>
			<p class="form-row form-row-wide" id="calc_shipping_city_field">
				<input type="text" class="input-text w-100" value="<?php //echo esc_attr( WC()->customer->get_shipping_city() ); ?>" placeholder="<?php //esc_attr_e( 'City', 'woocommerce' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
			</p>
		<?php //endif; ?> -->


		<!-- <?php //if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
			<div class="d-flex justify-content-between">
				<p class="p-0 m-0 text-left forWidth">PLZ</p>
				<p class="form-row form-row-wide mb-2" id="calc_shipping_postcode_field">
					<input type="text" class="input-text w-100" value="<?php //echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php //esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
				</p>
			</div>
		<?php //endif; ?> -->

		<!-- 
		=========================================================================
		Uporer input field k niche dorpdown kora hoyeche......................
		=========================================================================
		-->

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
			<div class="d-flex justify-content-between">
				<p class="p-0 m-0 text-left forWidth">PLZ</p>
				<p class="form-row form-row-wide mb-2" id="calc_shipping_postcode_field">

					<select class="w-100" name="calc_shipping_postcode" id="calc_shipping_postcode">
						<option value="69502">69502</option>
						<option value="74538">74538</option>
						<option value="68642">68642</option>
						<option value="65719">65719</option>
						<option value="68623">68623</option>
						<option value="68307">68307</option>
					</select>

				</p>
			</div>
		<?php endif; ?>





		<p><button type="submit" name="calc_shipping" value="1" class="button"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button></p>
		<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
	</section>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
