<?php

class Buy_One_Get_One_Free_Woocommerce_Product{

    public function __construct( ) {
		add_action( 'woocommerce_product_data_tabs', array($this,'productTab') );
		/** Adding order preparation days */
		add_action( 'woocommerce_product_data_panels', array($this,'order_preparation_days') );
		add_action( 'woocommerce_process_product_meta', array($this,'order_preparation_days_save') );
    }

    function productTab($tabs){
        $tabs['pisol_bogo'] = array(
            'label'    => 'BOGO',
            'target'   => 'pisol_bogo',
            'priority' => 21,
            'class' => 'hide_if_grouped hide_if_external'
        );
        return $tabs;
    }
    
    function order_preparation_days() {
		echo '<div id="pisol_bogo" class="panel woocommerce_options_panel hidden">';
		woocommerce_wp_checkbox( array(
            'label' => __("Enable BOGO"), 
            'id' => 'pisol_enable_bogo', 
            'name' => 'pisol_enable_bogo', 
            'description' => __("Enable BOGO for this product")
          ) );
          echo '<div id="pisol-bogo-extra">';
          echo '<div id="pisol-promotion">';
          echo '<div class="pisol-alert">'.__('Buy PRO version of BOGO plugin to access more advanced features.','buy-one-get-one-free-woocommerce').'</div>';
          echo '<a class="" href="'.PI_BOGO_BUY_URL.'" target="_blank">';
           new pisol_promotion('pisol_bogo_installation_date');
          echo '</a>';
          echo '</div>';
          echo '<div class="free-version">';
          echo '<img style="max-width:100%;" src="'.plugin_dir_url( __FILE__ ).'img/single-product.png">';
          echo '</div>';
          echo '</div>';
		echo '</div>';
    }
    
    function order_preparation_days_save( $post_id ) {
        $product = wc_get_product( $post_id );
        $value = isset($_POST['pisol_enable_bogo']) ? 'yes' : 'no';
        $product->update_meta_data( 'pisol_enable_bogo', sanitize_text_field( $value ) );
        $product->save();
   }
    
}

new Buy_One_Get_One_Free_Woocommerce_Product();