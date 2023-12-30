<?php

class Buy_One_Get_One_Free_Woocommerce_Option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Basic setting";

    private $setting_key = 'pisol_bogo_basic';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->free_product = $this->getSavedProductArray();

        $this->settings = array(
           
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Buy X Get Y Free",'buy-one-get-one-free-woocommerce'), 'type'=>"setting_category"),
            array('field'=>'pisol_global_disable', 'label'=>__('Disable BOGO','buy-one-get-one-free-woocommerce'),'type'=>'switch', 'default'=>0,   'desc'=>__('Disable BOGO rule globally','buy-one-get-one-free-woocommerce')),
            array('field'=>'pisol_product_quantity', 'label'=>__('Quantity of product to buy','buy-one-get-one-free-woocommerce'),'type'=>'number', 'default'=>1, 'min'=>1, 'step'=>1,   'desc'=>__('Buy X quantity of product to get Y quantity free','buy-one-get-one-free-woocommerce')),
            array('field'=>'pisol_free_product_quantity', 'label'=>__('Quantity of product given free','buy-one-get-one-free-woocommerce'),'type'=>'number', 'default'=>1, 'min'=>1, 'step'=>1,   'desc'=>__('How much quantity of the product will be given free','buy-one-get-one-free-woocommerce')),
            
            array('field'=>'pisol_free_product', 'label'=>__('Product given for free','buy-one-get-one-free-woocommerce'),'type'=>'select', 'default'=>"",   'desc'=>__('If left blank same product will be given free in bogo offer, (You can only select simple product)','buy-one-get-one-free-woocommerce'), 'value'=>$this->free_product),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>"Change variation of free product", 'type'=>"setting_category"),

            array('field'=>'pisol_bogo_change_variation', 'label'=>__('Allow user to change the variation of the free product'),'type'=>'switch', 'default'=> 1,   'desc'=>__('If you are offering variable product as free and this option is enabled then user will be able to change the variation of the free product, this will not work for the offer of type Buy A and Get A free '), 'pro'=>true),

            array('field'=>'pisol_bogo_change_variation_text', 'label'=>__('Change variation text shown next to the variable free product'),'type'=>'text', 'default'=>"Change option",   'desc'=>__('This text will be shown below the free product so user can change the free product variation on cart and checkout page'), 'pro'=>true),

            array('field'=>'pisol_bogo_option_to_remove_free_product2', 'label'=>__('Allow user to remove free product from the cart'),'type'=>'switch', 'default'=> 0,   'desc'=>__('If you enable this, then the customer will have the option to remove the free product from the cart if they don\'t want it'), 'pro'=>true),
            array('field'=>'pisol_max_free_product_quantity', 'label'=>__('Maximum Quantity that will be given free','buy-one-get-one-free-woocommerce'),'type'=>'number', 'default'=>1, 'min'=>1, 'step'=>1,   'desc'=>__('Say 1 unit of Product A gives 1 unit of B and max quantity is 2 then, when you adds 3 unit of A the quantity of Free B will be kept at 2 only as Max quantity is 2 (without max quantity restriction it would have been 3)','buy-one-get-one-free-woocommerce'), 'pro'=>true),
            array('field'=>'pisol_global_msg', 'label'=>__('Message shown once the offer has started','buy-one-get-one-free-woocommerce'),'type'=>'text', 'default'=>"Buy [buy_quantity], get [free_quantity] of [free_name] free ",   'desc'=>__('Message shown on the product page if it offer free product, use this short codes, [free_name], [buy_quantity], [free_quantity], [free_price], [start_date_time], [end_date_time], [start_date], [end_date], [start_time], [end_time]','buy-one-get-one-free-woocommerce'), 'pro'=>true),
            array('field'=>'pisol_global_before_offer_msg', 'label'=>__('Message shown before the order start time','buy-one-get-one-free-woocommerce'),'type'=>'text', 'default'=>"Buy [buy_quantity], get [free_quantity] of [free_name] free offer start on [start_time]",   'desc'=>__('Message shown on the product page before the offer start time, if it offer free product, use this short codes, [free_name], [buy_quantity], [free_quantity], [free_price], [start_date_time], [end_date_time], [start_date], [end_date], [start_time], [end_time]','buy-one-get-one-free-woocommerce'), 'pro'=>true),
            array('field'=>'pisol_bogo_message_bg_color', 'label'=>__('Message background color','buy-one-get-one-free-woocommerce'), 'default'=>'#cccccc','type'=>'color'),
            array('field'=>'pisol_bogo_message_text_color', 'label'=>__('Message text color','buy-one-get-one-free-woocommerce'), 'default'=>'#000000','type'=>'color'),
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Category level rules",'buy-one-get-one-free-woocommerce'), 'type'=>"setting_category"),
            array('field'=>'pisol_show_category_rule_message', 'label'=>__('Show deal message generated by category','buy-one-get-one-free-woocommerce'),'type'=>'switch', 'default'=> 0,   'desc'=>__('If there is a deal available on a product based on the category level BOGO rule then you can show that deal as well on the single product page','buy-one-get-one-free-woocommerce'), 'pro'=>true),
            array('field'=>'pisol_global_msg', 'label'=>__('Message shown once the offer has started','buy-one-get-one-free-woocommerce'),'type'=>'text', 'default'=>"Buy [buy_quantity], get [free_quantity] of [free_name] free ",   'desc'=>__('Message shown on the product page if it offer free product, [free_name], [buy_quantity], [free_quantity], [free_price], [start_date_time], [end_date_time], [start_date], [end_date], [start_time], [end_time]','buy-one-get-one-free-woocommerce'), 'pro'=>true),

        );
        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

       
        $this->register_settings();

        if(PISOL_BOGO_DELETE_SETTING){
            $this->delete_settings();
        }
    }

    function getSavedProductArray(){
        $free_product_id = get_option('pisol_free_product',"");
        if( empty($free_product_id )) return array();

        $product_title = get_the_title($free_product_id);
        $product = array( $free_product_id => $product_title );
        return $product;
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_sn($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 mb-3 btn btn-primary btn-md" value="Save Option" />
        <h2>Advanced option available in the pro version, for each product</h2>
        <img class="img-fluid ml-2 thumbnail" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/product-page.png">
        <img class="img-fluid ml-2 thumbnail" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/product-variation.png">
        </form>
       <?php
    }

    
}

new Buy_One_Get_One_Free_Woocommerce_Option($this->plugin_name);