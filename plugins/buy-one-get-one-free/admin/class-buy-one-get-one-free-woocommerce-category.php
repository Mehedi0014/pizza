<?php

class Buy_One_Get_One_Free_Woocommerce_Category{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'bogo_category_rule';

    private $tab_name = "Category rules";

    private $setting_key = 'pisol_bogo_category_rule';
    
    public $default = array(
		"enabled" => "off",
		"deal_type" => "aa",
		"product" => "",
		"buy_qt" => "",
		"free_qt" => "",
		"free_qt_limit" => ""
	);

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->settings = array(
           
            array('field'=>'pisol_bogo_category_rules')

        );
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        add_action( 'wp_ajax_pi_search_product', array( $this, 'search_product' ) );
       
        $this->register_settings();

        if(PISOL_BOGO_DELETE_SETTING ){
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
	   <div class="alert alert-info my-2 text-center">This feature is only available in the PRO version</div>
        <img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/category.png">
		<?php include 'partials/explain.php'; ?>
       <?php
    }

    function getCategoryTable(){
        include 'partials/buy-one-get-one-free-woocommerce-category-table.php';
    }

    function getCategories($parent = 0, $level = 0){
        $categories = get_terms( 'product_cat', array(
			'hierarchical' => false,
			'hide_empty'   => false,
			'parent'       => $parent,
        ) );
        
        $settings = get_option( 'pisol_bogo_category_rules', array() );
        //print_r($settings);
        include 'partials/buy-one-get-one-free-woocommerce-category-row.php';
	}
	
	function storedValue($saved_settings, $category){
		if(isset($saved_settings[$category->term_id])){
			return $saved_settings[$category->term_id];
		}

		return $this->default;
	}

	function savedProduct( $product_id, $category_id ){
		if($product_id == ""){
			$html = '<select class="form-control pisol_bogo_category_product_selection" name="pisol_bogo_category_rules[' . $category_id . '][product]" id="pisol_bogo_category_rules_' . $category_id . '_product">';
			$html .='</select>';
			echo $html;
			return;
		}

		if ( pisol_wc_version_check() ) {
			$product_name = str_replace("&#8211;",">",get_the_title( $product_id  ));

		} else {
			$child_wc  = wc_get_product( $product_id );
			$get_atts  = $child_wc->get_variation_attributes();
			$attr_name = array_values( $get_atts )[0];
			$product_name   = get_the_title() . ' - ' . $attr_name;
		}

		$html = '<select class="form-control pisol_bogo_category_product_selection" name="pisol_bogo_category_rules[' . $category_id . '][product]" id="pisol_bogo_category_rules_' . $category_id . '_product">';
		$html .= '<option value="'.$product_id.'">'.$product_name.'</option>';
		$html .='</select>';
		echo $html;
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

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							if ( pisol_wc_version_check() ) {
								$product = array(
									'id'   => $product_child,
									'text' => str_replace("&#8211;",">",get_the_title( $product_child ))
								);

							} else {
								$child_wc  = wc_get_product( $product_child );
								$get_atts  = $child_wc->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$product   = array(
									'id'   => $product_child,
									'text' => get_the_title() . ' - ' . $attr_name
								);

							}
							$found_products[] = $product;
						}

					}
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

new Buy_One_Get_One_Free_Woocommerce_Category($this->plugin_name);

