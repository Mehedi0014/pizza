<?php

class pi_bogo_woo{
    function __construct(){
        $global_disable = get_option('pisol_global_disable',0);
        if($global_disable != 1){
            add_action( 'woocommerce_after_add_to_cart_button', array($this,'freeProduct'),10);

            add_action( 'wp_enqueue_scripts', array($this,'style') );
        }
    }

    function freeProduct(){
        global $product;
        
        if(!$this->offerEnabled($product->get_ID())) return;

        $this->offerMessage($product);
    }

    function offerMessage($product){
        if($product->is_type('variable')){
            $variations = $product->get_available_variations();
            foreach($variations as $variation){
                $parent_id = $variation['variation_id'];
                $free_product_id = $this->getFreeProductId($parent_id);
                $this->freeProductMessage($parent_id, $free_product_id, 'variation');
            }
        }else{
            $parent_id = $product->get_ID();
            $free_product_id = $this->getFreeProductId($parent_id);
            $this->freeProductMessage($parent_id, $free_product_id);
        }
    }

    function getFreeProductId($parent_id){
        $different_free_product = Pi_Bogo_Parent::differentFreeProduct();

        if(empty($different_free_product)) return $parent_id;

        return $different_free_product;
    }
    
    function offerEnabled($product_id){
        $bogo_enable = get_post_meta($product_id,'pisol_enable_bogo',true);
        if($bogo_enable == 'yes') return true;

        return false;
    }

    function freeProductMessage($parent_id, $free_product_id, $type = "simple"){
        if(Pi_Bogo_Rule::productExistInSite($free_product_id) === false) return;
        $msg = $this->msg($parent_id, $free_product_id);
        $class = "";
        if($type != 'simple'){
            $class = " pisol-variation-handler pisol-hidden";
        }
        ?>
            <div class="pi-msg-container <?php echo $class; ?>" id="pisol-<?php echo $type; ?>-<?php echo $parent_id;?>">
                <?php if($msg != ""): ?>
                <div class="pi-msg-title">
                <?php echo $msg; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php
    }

    function msg($parent_id, $free_product_id){
        
        if($parent_id == $free_product_id){
            $msg = __("Buy [buy_quantity] get [free_quantity] free", 'buy-one-get-one-free-woocommerce');
        }else{
            $msg = __("Buy [buy_quantity] of [parent_name] get [free_quantity] of [free_name] free", 'buy-one-get-one-free-woocommerce');
        }
        
        $parent_product_obj = wc_get_product($parent_id);
        $free_product_obj = wc_get_product($free_product_id);
        
        $replace = array();

        $product_quantity = (int)get_option('pisol_product_quantity',1);
        $replace['buy_quantity'] =  $product_quantity == 0 ? 1 :  $product_quantity;

        $free_product_quantity = (int)get_option('pisol_free_product_quantity',1);
        $replace['free_quantity'] = $free_product_quantity == 0 ? 1 : $free_product_quantity;

        $replace['free_name'] = $free_product_obj->get_name();

        $replace['parent_name'] = $parent_product_obj->get_name();

        foreach($replace as $key => $value){
            $msg = str_replace('['.$key.']', $value, $msg);
        }

        return $msg;
    }

    function style(){
        wp_register_style( 'bogo-dummy-handle', false );
        wp_enqueue_style( 'bogo-dummy-handle' );
        $pisol_bogo_message_bg_color = get_option('pisol_bogo_message_bg_color','#cccccc');
        $pisol_bogo_message_text_color = get_option('pisol_bogo_message_text_color','#000000');
        $custom_css = "
            .pi-msg-title{
                background:{$pisol_bogo_message_bg_color};
                color:{$pisol_bogo_message_text_color};
            }

            .pisol-variation-handler.pisol-hidden{
                display:none;
            }
        ";
        wp_add_inline_style( 'bogo-dummy-handle', $custom_css );
    }
}

new pi_bogo_woo();