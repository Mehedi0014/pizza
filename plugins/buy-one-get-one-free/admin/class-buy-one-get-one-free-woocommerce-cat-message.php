<?php

class Buy_One_Get_One_Free_Woocommerce_Cat_Message{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'cat_msg';

    private $tab_name = "Category Message (PRO)";

    private $setting_key = 'pisol_bogo_cat_msg';
    
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
           
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Message template based on the deal type in category",'buy-one-get-one-free-woocommerce'), 'type'=>"setting_category"),
            
            array('field'=>'pisol_show_category_page_message', 'label'=>__('Show deal message on category page','buy-one-get-one-free-woocommerce'),'type'=>'switch', 'default'=> 1,   'desc'=>__('If enabled this will show the category level deal description on the respective category page','buy-one-get-one-free-woocommerce'), 'pro'=>true),

            array('field'=>'pisol_cat_page_msg_aa', 'label'=>__('Message template used when category deal type is <strong class="text-primary">Buy product A and get product A Free</strong>','buy-one-get-one-free-woocommerce'),'type'=>'textarea', 'default'=>"Buy [buy_quantity], unit of any product from this category and get [free_quantity] unit free of the same product",   'desc'=>__("you can use this codes [buy_quantity] [free_quantity] [limit]",'buy-one-get-one-free-woocommerce'), 'pro'=>true),

            array('field'=>'pisol_cat_page_msg_ab', 'label'=>__('Message template used when category deal type is <strong class="text-primary">Buy product A and get product B Free</strong>','buy-one-get-one-free-woocommerce'),'type'=>'textarea', 'default'=>"Buy [buy_quantity], unit of any product from this category and get [free_quantity] unit free of [free_name] product",   'desc'=>__("you can use this codes [buy_quantity] [free_quantity] [free_name] [limit]",'buy-one-get-one-free-woocommerce'), 'pro'=>true),
            
            array('field'=>'pisol_cat_page_msg_cab', 'label'=>__('Message template used when category deal type is <strong class="text-primary">Buy product from category get product B Free</strong>','buy-one-get-one-free-woocommerce'),'type'=>'textarea', 'default'=>"Buy [buy_quantity], unit of any product from this category and get [free_quantity] unit free of [free_name] product",   'desc'=>__("you can use this codes [buy_quantity] [free_quantity] [free_name] [limit]",'buy-one-get-one-free-woocommerce'), 'pro'=>true),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Message shown on category page describing the offer",'buy-one-get-one-free-woocommerce'), 'type'=>"setting_category", 'pro'=>true),
            
            array('field'=>'pisol_bogo_cat_page_message_bg_color', 'label'=>__('Message background color','buy-one-get-one-free-woocommerce'), 'default'=>'#cccccc','type'=>'color', 'pro'=>true),
            array('field'=>'pisol_bogo_cat_page_message_text_color', 'label'=>__('Message text color','buy-one-get-one-free-woocommerce'), 'default'=>'#000000','type'=>'color', 'pro'=>true),
           
            /*
            array('field'=>'pisol_global_before_offer_msg', 'label'=>__('Message shown before the order start time'),'type'=>'text', 'default'=>"Buy [buy_quantity], get [free_quantity] of [free_name] free offer start on [start_time]",   'desc'=>__('Message shown on the product page before the offer start time, if it offer free product, use this short codes, [buy_quantity] => quantity of product you have to buy, [free_quantity]=> quantity that you will get free, [free_name] => Free product title, [free_price] => free product original price, [end_date_time]=> offer end date and time, [start_date_time] => offer start date and time')),
            */
        );
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

       
        $this->register_settings();

        if(PISOL_BOGO_DELETE_SETTING){
            $this->delete_settings();
        }
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
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
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
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    

    
}

new Buy_One_Get_One_Free_Woocommerce_Cat_Message($this->plugin_name);