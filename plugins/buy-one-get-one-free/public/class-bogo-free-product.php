<?php

class Pi_Bogo_Free_Product{

    function __construct(){

    }

    public static function addFreeProduct($free_product_id, $free_product_quantity, $parent_key){
        $free_product_key = WC()->cart->add_to_cart(
            $free_product_id,
            $free_product_quantity,
            0,
            array(),
            array('parent_key'=>$parent_key)
        );
        return $free_product_key;
    }

    public static function removeFreeProduct($free_product_key){
        if(isset(WC()->cart->cart_contents[ $free_product_key ])){
            WC()->cart->remove_cart_item( $free_product_key );
        }
    }

    public static function updateQuantity($free_product_key, $quantity, $parent_item_key = false){
        if(isset(WC()->cart->cart_contents[$free_product_key])){
            WC()->cart->cart_contents[$free_product_key]['quantity'] = $quantity;
        }else{
            if($parent_item_key){
                Pi_Bogo_Cart::addProductToCart($parent_item_key);
            }
        }
    }

    public static function isFreeProduct($cart_item_key){
        if(is_string($cart_item_key)){
            if(isset(WC()->cart->cart_contents[ $cart_item_key ]['parent_key'])){
                return true;
            }
        }else{
            /**
             * If cart_item_key is object and not the key
             */
            if(isset($cart_item_key['parent_key'])){
                return true;
            }
        }
        return false;
    }

    public static function setPriceZero(&$product){
        $product->set_price( 0 );
		$product->set_sale_price( 0 );
		$product->set_regular_price( 0 );
		$product->is_free_product = true;
    }

    public static function getParent($free_product_key){
        if(isset(WC()->cart->cart_contents[$free_product_key])){
            return WC()->cart->cart_contents[$free_product_key]['parent_key'];
        }
    }
}