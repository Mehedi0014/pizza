<?php

class Pi_Bogo_Cart{

    public static function init(){
        $global_disable = get_option('pisol_global_disable',0);

        if($global_disable != 1){
            add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'addProductToCart' ) );
            add_action( 'woocommerce_cart_item_restored', array( __CLASS__, 'addProductToCart' ) );

            add_filter( 'woocommerce_get_cart_item_from_session', array( __CLASS__, 'validateFreeItemInCart' ), 100, 3 );

            add_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'updateProductInCart' ), 100, 3 );
            add_action( 'woocommerce_cart_loaded_from_session', array( __CLASS__, 'cartValidate' ) );

            if(!pisol_wc_version_check( '3.6.9' )){
                add_action( 'woocommerce_before_cart_item_quantity_zero', array( __CLASS__, 'removeProductInCart' ) );
            }
            add_action( 'woocommerce_remove_cart_item', array( __CLASS__, 'removeProductInCart' ) );

            add_filter( 'woocommerce_cart_item_remove_link', array( __CLASS__, 'removeLink' ), 10, 2 );
            add_filter( 'woocommerce_cart_item_quantity', array( __CLASS__, 'removeQuantityEdit' ), 10, 3 );

            add_filter('woocommerce_product_get_price', array( __CLASS__, 'modifyGetPriceConflictFixer' ), PHP_INT_MAX, 2 );
        }
    }

    public static function cartValidate(){
        
        $cart_contents = WC()->cart->get_cart_contents();

		foreach ( $cart_contents as $key => $cart_item ) {
            if ( Pi_Bogo_Free_Product::isFreeProduct( $key ) ){
                $parent_key = Pi_Bogo_Free_Product::getParent($key);
                $parent = new Pi_Bogo_Parent($parent_key);
                $free_product_quantity = $parent->getFreeProductQuantity();
                if(isset(WC()->cart->cart_contents[ $key ])){
                    WC()->cart->cart_contents[ $key ]['quantity'] = $free_product_quantity;
                }
            }
        }
        
    }

    public static function addProductToCart($cart_item_key){
        $parent = new Pi_Bogo_Parent($cart_item_key);
        remove_action( 'woocommerce_add_to_cart', array( __CLASS__, 'addProductToCart' ) );
        $parent->addFreeProduct();
        add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'addProductToCart' ) );
    }

    public static function updateProductInCart($cart_item_key, $quantity, $old_quantity){
        if ( !Pi_Bogo_Free_Product::isFreeProduct( $cart_item_key ) ){
        $parent = new Pi_Bogo_Parent($cart_item_key);
        remove_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'updateProductInCart' ), 100, 3 );

        $parent->updateQuantity($quantity, $old_quantity);
        
        add_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'updateProductInCart' ), 100, 3 );
        
        }
    }

    public static function removeProductInCart($cart_item_key){
        if(isset($_GET['remove_item'])){
            $cart = WC()->cart->cart_contents;
            $parent = new Pi_Bogo_Parent($cart_item_key);
            $parent->removeFreeProduct();
         }
    }

    public static function undoProductRemoval(){

    }

    public static function validateFreeItemInCart($session_data, $values, $key){
        if ( Pi_Bogo_Free_Product::isFreeProduct( $session_data ) ){
             Pi_Bogo_Free_Product::setPriceZero($session_data['data']);
        }
        return $session_data;
    }

    public static function removeQuantityEdit( $product_quantity, $cart_item_key, $cart_item ) {
		if ( Pi_Bogo_Free_Product::isFreeProduct( $cart_item_key ) ) {
			$product_quantity = sprintf( '%s <input type="hidden" name="cart[%s][qty]" value="%s" />', $cart_item['quantity'], $cart_item_key, $cart_item['quantity'] );
		}
		return $product_quantity;
    }
    
    public static function removeLink( $remove_link, $cart_item_key ) {
		
		if ( Pi_Bogo_Free_Product::isFreeProduct( $cart_item_key ) ) {
			$remove_link = '';
		}
		return $remove_link;
	}

    
  /**
   * conflict fixing done for B2Bking and should also work for role based pricing
   */
  static function modifyGetPriceConflictFixer($price, $product){
    
    if(isset($product->is_free_product) && $product->is_free_product == true){
        return 0;
    }
    
    return $price;
  }

}