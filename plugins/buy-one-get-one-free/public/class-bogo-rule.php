<?php

class Pi_Bogo_Rule{

    protected $cart_item_key;
    public $rule = array();

    function __construct($cart_item_key){
        $this->cart_item_key = $cart_item_key;
        $this->parent_product_id = $this->getProductID();
    }

    function rule(){
        $product_quantity = (int)get_option('pisol_product_quantity',1);
        $free_product_quantity = (int)get_option('pisol_free_product_quantity',1);
        $bogo_enable = get_post_meta($this->parent_product_id,'pisol_enable_bogo',true);

        $this->rule = array(
            'enable' => $bogo_enable,
            'product_quantity' => $product_quantity,
            'free_product_quantity' => $free_product_quantity
        );
        return $this->rule;
    }

    static function productExistInSite($product_id){
		$product = wc_get_product($product_id);
		if(is_object($product) && $product->is_purchasable()) return $product_id;

		return false;
	}

    function getProductID(){
        $product_id = 0;
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ]['product_id'])){
            $product_id = WC()->cart->cart_contents[ $this->cart_item_key ]['product_id'];
        }
        return $product_id;
    }

}