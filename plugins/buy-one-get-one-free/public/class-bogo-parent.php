<?php

class Pi_Bogo_Parent{

    protected $cart_item_key;

    function __construct($cart_item_key){
        $this->cart_item_key = $cart_item_key;

        $rule_obj = new Pi_Bogo_Rule($this->cart_item_key);
        $this->rule = $rule_obj->rule();
    }

    function addFreeProduct(){
        if($this->rule['enable'] == 'yes'){

            $free_product_id = $this->getFreeProductId();
            
            if(Pi_Bogo_Rule::productExistInSite($free_product_id) === false) return;

            $free_product_quantity = $this->getFreeProductQuantity();
            
            $free_product_key = Pi_Bogo_Free_Product::addFreeProduct($free_product_id, $free_product_quantity, $this->cart_item_key);
            
            $this->addFreeProductKey($free_product_key);
        }
    }

    function updateQuantity($quantity, $old_quantity){
        $free_product_key = $this->getChildKey();
        $free_product_quantity = $this->getFreeProductQuantity();
        Pi_Bogo_Free_Product::updateQuantity($free_product_key,  $free_product_quantity, $this->cart_item_key);
    }

    function removeFreeProduct(){
        if($this->isParent()){
            $child_key = $this->getChildKey();
            Pi_Bogo_Free_Product::removeFreeProduct($child_key);
        }
    }

    function addFreeProductKey($child_key){
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ])){
            WC()->cart->cart_contents[ $this->cart_item_key ]['child_key'] = $child_key;
        }
    }

    function isParent(){
        $child_key = $this->getChildKey();
        if($child_key){
            return true;
        }
        return false;
    }

    /**
     * since now we are adding same product as free, we give the same product id or * same product variation id, later on when we will have rules we will give the * product id based on that rule
     */
    function getFreeProductId(){
        $different_free_product = self::differentFreeProduct();
        if(!empty($different_free_product)) return $different_free_product;

        $parent_id = $this->getProductID();
        $parent_variation_id = $this->getVariationID();
        $free_product_id = 0;
        if($parent_variation_id == 0){
            $free_product_id = $parent_id;
        }else{
            $free_product_id = $parent_variation_id;
        }
        /* as we want to add same product as free product */
        return $free_product_id;
    }

    static function differentFreeProduct(){
        $free_product_id = get_option('pisol_free_product',"");
        if( empty($free_product_id )) return false;

        return  $free_product_id;
    }

    function getFreeProductQuantity(){
        $parent_quantity = $this->getQuantity();

        $min_parent_quantity = $this->rule['product_quantity'];

        $free_product_to_give = $this->rule['free_product_quantity'];

        $free_product_quantity = floor($parent_quantity /  $min_parent_quantity ) * $free_product_to_give;
        
        return $free_product_quantity;
    }

    function getProductID(){
        $product_id = 0;
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ]['product_id'])){
            $product_id = WC()->cart->cart_contents[ $this->cart_item_key ]['product_id'];
        }
        return $product_id;
    }

    function getVariationID(){
        $variation_id = 0;
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ]['variation_id'])){
            $variation_id = WC()->cart->cart_contents[ $this->cart_item_key ]['variation_id'];
        }
        return $variation_id;
    }

    function getQuantity(){
        $quantity = 0;
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ]['quantity'])){
            $quantity = WC()->cart->cart_contents[ $this->cart_item_key ]['quantity'];
        }
        return $quantity;
    }

    function getChildKey(){
        $child_key = false;
        if(isset(WC()->cart->cart_contents[ $this->cart_item_key ]['child_key'])){
            $child_key = WC()->cart->cart_contents[ $this->cart_item_key ]['child_key'];
        }
        return $child_key;
    }
}