<?php

class Buy_One_Get_One_Free_Woocommerce_Menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));

        add_action( 'wp_ajax_pisol_bogo_search_product', array( $this, 'search_product' ) );
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'BOGO Deal','buy-one-get-one-free-woocommerce'),
            __( 'BOGO Deal','buy-one-get-one-free-woocommerce'),
            'manage_options',
            'pisol-bogo-deal',
            array($this, 'menu_option_page'),
            'dashicons-cart',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

        
        
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
        wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'selectWoo' );
        wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buy-one-get-one-free-woocommerce-admin.js', array( 'jquery', 'selectWoo' ), $this->version, false );
		
	}

    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div class="bootstrap-wrapper">
        <div class="container mt-2">
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                                <a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-primary mr-0 ml-auto font-weight-bold" href="https://www.piwebsolution.com/buy-one-get-one-free-offer-maker-plugin-for-woocommerce/">
                                    User Guide
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
        ?>
        <div class="col-12 col-sm-12 col-md-4 pt-3">

        <a class="btn btn-block btn-lg mb-2 btn-primary" href="http://websitemaintenanceservice.in/bogo_demo/" target="_blank">Try All Features of Pro</a>

            <div class="bg-primary text-light text-center mb-3">
                <a class="" href="<?php echo PI_BOGO_BUY_URL; ?>" target="_blank">
                <?php new pisol_promotion('pisol_bogo_installation_date'); ?>
                </a>
            </div>

            <div class="bg-dark p-3 text-light text-center mb-3">
                <h2 class="text-light "><span>Get Pro for<br><h1 class="h1 font-weight-bold text-light my-1"><?php echo PI_BOGO_PRICE; ?></h1> <h4 class="text-light">Buy Now !!</h4></h2>
                <div class="inside">
                    PRO version offers more features:<br><br>
                    <ul class="text-left pisol-pro-feature-list">
                        <li class="border-top py-2 font-weight-light h6">Offer the BOGO Deal product as <strong class="font-weight-bold text-primary">Free or At Discounted price</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Set BOGO offer on the <strong class="font-weight-bold text-primary">category level</strong></li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="font-weight-bold text-primary">Overwrite</strong> category level offer on product level</li>
                        <li class="border-top py-2 font-weight-light h6">Set category level <strong class="font-weight-bold text-primary">max amount</strong> restriction</li>
                        <li class="border-top py-2 font-weight-light h6">Show category level offer message on the respective category pages</li>
                        <li class="border-top py-2 font-weight-light h6">You can offer <strong class="font-weight-bold text-primary">other product as free product</strong>, or you can offer same product as free product, for each of the product</li>
                        <li class="border-top py-2 font-weight-light h6">You can set <strong class="font-weight-bold text-primary">different free product</strong> with each of the product</li>
                        <li class="border-top py-2 font-weight-light h6">Create custom <strong class="font-weight-bold text-primary">minimum buy quantity</strong> for each product</li>
                        <li class="border-top py-2 font-weight-light h6">Create custom <strong class="font-weight-bold text-primary">free product quantity</strong> for each product</li>
                        <li class="border-top py-2 font-weight-light h6">Set offer <strong class="font-weight-bold text-primary">start date and end date</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Set a <strong class="font-weight-bold text-primary">different message</strong> to show before offer start, and once offer has started</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="font-weight-bold text-primary">Craft offer message in your own word</strong> for each product by using short code for offer details</li>
                        <li class="border-top py-2 font-weight-light h6">You can <strong class="font-weight-bold text-primary">run offer for all the variation</strong> of variable product</li>
                        <li class="border-top py-2 font-weight-light h6">Restrict the <strong class="font-weight-bold text-primary">offer to run for some specific variation</strong> for a variable product</li>
                        <li class="border-top py-2 font-weight-light h6">Offer info will only show when the buyer select a variation that has the offer, if he select some other variation that don't have <strong class="font-weight-bold text-primary">offer the message box will disappear</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Offer <strong class="font-weight-bold text-primary">different product as Free product with each of the variation</strong> in a variable product</li>
                        <li class="border-top py-2 font-weight-light h6">Set different <strong class="font-weight-bold text-primary">buy quantity, free quantity, and max restriction</strong> for each of the variation</li>
                        <li class="border-top py-2 font-weight-light h6">Set <strong class="font-weight-bold text-primary">different start time and end time</strong> for the variation</li>
                        <li class="border-top py-2 font-weight-light h6">Set <strong class="font-weight-bold text-primary">different message</strong> for each variation</li>
                        <li class="border-top py-2 font-weight-light h6">Allow customer to <strong class="font-weight-bold text-primary">remove the Free product</strong> from cart</li>
                        <li class="border-top py-2 font-weight-light h6">If the free product is a variable product then allow customer to <strong class="font-weight-bold text-primary">change the variation of the free product</strong> on cart or checkout page</li>
                        <li class="border-top py-2 font-weight-light h6">Make category offer to run on <strong class="font-weight-bold text-primary">specific days of the week</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Give choice to customer to <strong class="font-weight-bold text-primary">select a different free product</strong> from multiple free product</li>
                        <li class="border-top py-2 font-weight-light h6">Customer can change the free product from alternate free product on <strong class="font-weight-bold text-primary">cart or checkout page</strong></li>
                        </ul>
                        <a class="btn btn-light" href="<?php echo PI_BOGO_BUY_URL; ?>" target="_blank">Click to Buy Now</a>
                </div>
            </div>
            
        </div>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

    public function search_product( $x = '', $post_types = array( 'product' ) ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

        ob_start();
        
        if(!isset($_GET['keyword'])) die;

		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );
				$cat_ids  = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

				/* remove grouped product or external product */
				if($prd->is_type('grouped') || $prd->is_type('external')){
					continue;
				}
				

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					
				} else {
					$product_id    = get_the_ID();
					$product_title = get_the_title();
					$the_product   = new WC_Product( $product_id );
					if ( ! $the_product->is_in_stock() ) {
						$product_title .= ' (Out of stock)';
					}
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
        }
		wp_send_json( $found_products );
		die;
    }

}