<?php

	/*
	==============================================================================
      >>>> Enqueue scripts and styles.
	==============================================================================
	*/

	function mone_scripts_child() {
		$parenthandle = 'mone-style';
		$theme = wp_get_theme();

		wp_enqueue_style( 'fontAwesome-min-css', get_template_directory_uri() . '/assets/fontAwesome/css/font-awesome.min.css',false,'4.7.1','all');
		wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300;400;600;700;800&display=swap', array() );
		wp_enqueue_style( 'bootstrap-min-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', false, '4.3.1', 'all');
		wp_enqueue_style( 'owl-carousel-min-css', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', false, '4.3.1', 'all');
		wp_enqueue_style( 'animate-min-css', get_template_directory_uri() . '/assets/css/animate.min.css', false, '4.3.1', 'all');

		wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', array(), _S_VERSION );
		wp_enqueue_style( 'mone-style-child', get_stylesheet_uri(), array( $parenthandle ), $theme->get('Version') );
		wp_style_add_data( 'mone-style', 'rtl', 'replace' );

		wp_enqueue_script( 'jquery-min-js', get_template_directory_uri() . '/assets/js/jquery.min.js', false, 1.1, true);
		wp_enqueue_script( 'jquery-cookie-min-js', get_template_directory_uri() . '/assets/js/jquery.cookie.min.js',  array ( 'jquery-min-js' ), 1.4, true);
		wp_enqueue_script( 'popper-min-js', get_template_directory_uri() . '/assets/js/popper.min.js', array ( 'jquery-min-js' ), 1.14, true);
		wp_enqueue_script( 'bootstrap-min-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array ( 'jquery-min-js' ), 3.3, true);
		wp_enqueue_script( 'owl-carousel-min-js', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array ( 'jquery-min-js' ), 3.3, true);
		wp_enqueue_style( 'custom-google-fonts', 'https://maps.google.com/maps/api/js?key=AIzaSyAqBzIK21scZM9Td-uICzwQCwQkl_n1TcY', array() );
		wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.js', array ( 'jquery-min-js' ), 1.0, true);
		wp_enqueue_script( 'child-main-js', get_stylesheet_directory_uri() . '/assets/js/child-main.js', array ( 'jquery-min-js' ), 1.0, true);

	}
	add_action( 'wp_enqueue_scripts', 'mone_scripts_child' );


	/*
	==============================================================================
	>>>> Add plus and minus quantity buttons in Woocommerce
	==============================================================================
	*/

	// Minimum CSS to remove +/- default buttons on input field type number
	add_action( 'wp_head' , 'custom_quantity_fields_css' );
	function custom_quantity_fields_css(){
	    ?>
		    <style>
			    .quantity input::-webkit-outer-spin-button,
			    .quantity input::-webkit-inner-spin-button {
			        display: none;
			        margin: 0;
			    }
			    .quantity input.qty {
			        appearance: textfield;
			        -webkit-appearance: none;
			        -moz-appearance: textfield;
			    }
		    </style>
	    <?php
	}

	add_action( 'wp_footer' , 'custom_quantity_fields_script' );
	function custom_quantity_fields_script(){
	    ?>
	    <script type='text/javascript'>
		    jQuery( function( $ ) {
		        if ( ! String.prototype.getDecimals ) {
		            String.prototype.getDecimals = function() {
		                var num = this,
		                    match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
		                if ( ! match ) {
		                    return 0;
		                }
		                return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
		            }
		        }
		        // Quantity "plus" and "minus" buttons
		        $( document.body ).on( 'click', '.plus, .minus', function() {
		            var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
		                currentVal  = parseFloat( $qty.val() ),
		                max         = parseFloat( $qty.attr( 'max' ) ),
		                min         = parseFloat( $qty.attr( 'min' ) ),
		                step        = $qty.attr( 'step' );

		            // Format values
		            if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
		            if ( max === '' || max === 'NaN' ) max = '';
		            if ( min === '' || min === 'NaN' ) min = 0;
		            if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

		            // Change the value
		            if ( $( this ).is( '.plus' ) ) {
		                if ( max && ( currentVal >= max ) ) {
		                    $qty.val( max );
		                } else {
		                    $qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
		                }
		            } else {
		                if ( min && ( currentVal <= min ) ) {
		                    $qty.val( min );
		                } else if ( currentVal > 0 ) {
		                    $qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
		                }
		            }

		            // Trigger change event
		            $qty.trigger( 'change' );
		        });
		    });
	    </script>
	    <?php
	}


	/*
	==============================================================================
	>>>> Repalce single page short description
	==============================================================================
	*/
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
	add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6);


	/** 
	 ===> change [add to cart] text all page without single page 
	*/
    function mehedi_add_to_cart_text_change(){
    	global $product;
    	if($product->is_type( 'variable' )) {
        	return "Wähle Eine Option";
        }else{
        	return "BESTELLEN";
        }
    }
    add_filter('woocommerce_product_add_to_cart_text', 'mehedi_add_to_cart_text_change');


	/** 
	 ===> change [add to cart] text all only single page. 
	*/
    function mehedi_add_to_cart_text_change_single_page(){    	
        return "BESTELLEN";
    }
    add_filter('woocommerce_product_single_add_to_cart_text', 'mehedi_add_to_cart_text_change_single_page');


	/**
	 ===> Remove default add to cart and create custom add to cart button ============
	*/

	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	function mehedi_add_custom_add_to_cart_link(){
	    $button_permalink = get_permalink();
	    global $product;

	    if($product->is_type( 'variable' )) {
	        woocommerce_template_loop_add_to_cart();
	    }else{
	        $price = $product->get_price();
	        echo '
	            <a class="btn button" href="' . $button_permalink . '">BESTELLEN</a>
	        ';
	    }
	}
	add_action('woocommerce_after_shop_loop_item', 'mehedi_add_custom_add_to_cart_link', 11);






	/**
	 ===> Change Number of Related Products
	 */ 
	add_filter( 'woocommerce_output_related_products_args', 'bbloomer_change_number_related_products', 9999 );

	function bbloomer_change_number_related_products( $args ) {
		$args['posts_per_page'] = 4; // # of related products
		$args['columns'] = 4; // # of columns per row
		return $args;
	}


	/**
	===> Change Related Products Heading, with adding the product name
	*/
	add_filter(  'gettext',  'change_related_products_title', 10, 3 );
	function change_related_products_title( $translated, $text, $domain  ) {
	    if( $text === 'Related products' && $domain === 'woocommerce' ){
	        $translated = esc_html__( 'These go well with: ', $domain ) . ' ' . esc_html( get_the_title() );
	    }
	    return $translated;
	}


	/**
	 ===> Change "You may also like..." In upsell products
	 */  
	add_filter( 'woocommerce_product_upsells_products_heading', 'bbloomer_translate_may_also_like' ); 
	function bbloomer_translate_may_also_like() {
	   //return 'Customers also purchased...';
	   $translated = esc_html__( 'Customers also purchased... ');
	   return $translated;
	}


	/**
	===> change the number of columns in archive page
	*/
    function mehedi_shop_columns($columns){
        return 4;
    }
    add_filter('loop_shop_columns', 'mehedi_shop_columns', 20);


    /**
	 ===> Change number of products that are displayed per page (shop page)
	 */
	add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
	function new_loop_shop_per_page( $cols ) {
	  $cols = 12;
	  return $cols;
	}


    /**
	===> Show short description in archive/shop page and close link (title, short description, price theke anchore tag er link remove kore diachi).
	*/
	function mehedi_shop_page_short_desc_with_close_link(){
			add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 9);
			add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_single_excerpt');
			add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_open', 4);
	}
	add_action('init', 'mehedi_shop_page_short_desc_with_close_link');


	/**
	===> Remove default add to cart, price and sale flash in archive page.
	*/
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	//Remove Sales Flash in single product page
	add_filter('woocommerce_sale_flash', 'woo_custom_hide_sales_flash');
	function woo_custom_hide_sales_flash() {
	    return false;
	}


	/**
	===> Remove cross-sells at cart page
	*/ 
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );



	/**
	===> Remove variation stock data from product page display (In stock or out of stock ai lekha gulo single page theke remove korer poddoti)
	*/
	function sww_wc_remove_variation_stock_display( $data ) {
	    unset( $data['availability_html'] );
	    return $data;
	}
	add_filter( 'woocommerce_available_variation', 'sww_wc_remove_variation_stock_display', 99 );

	// nicher filter only WooCommerce 2.6 er uporer version e kaj kore.
	//add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );





	/**
	===> Remove default pagination and make a new one for archive page.
	*/
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

	function mehedi_pagination(){
	    global $wp_query;

	    if ( $wp_query->max_num_pages <= 1 ) return;

	    $big = 999999999; // need an unlikely integer

	    $pages = paginate_links( array(
	        'base'        => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	        'format'      => '?paged=%#%',
	        'current'     => max( 1, get_query_var('paged') ),
	        'total'       => $wp_query->max_num_pages,
	        'type'        => 'array',
	        'prev_next' => true,
	        'prev_text' => __('<i class="fa fa-chevron-left"></i>'),
	        'next_text' => __('<i class="fa fa-chevron-right"></i>'),
	    ) );
	    
	    if( is_array( $pages ) ) {
	        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

	        	echo '<div class="row"><div class="col-12"><div id="paginationContainer" class="mt-4 mb-5 d-flex justify-content-center">';
	        		echo '<ul class="list-group list-group-horizontal">';
				        foreach ( $pages as $page ) {
				            echo "<li class='list-group-item'>";
				            	echo "$page";
				            echo "</li>";
				        }
	       			echo '</ul>';
		       	echo '</div></div></div>';
	    }
	}
	add_action( 'init_pagination', 'mehedi_pagination', 10 );
	// ai function ta woocommerce -> template -> archive-product.php te call kora hoyeche.
	// Infinity scroll add korar karone ai function ti comment kore deya holo.




	/**
	===> After Place order page/Checkout (Checkout theke order conform korar por j page ase tar jonno coding)
	===> Add div for desing, Before/Top part of Order details 
	*/

	function top_part_of_order_details_open_container(){
		echo '
			<div id="topPartOfOrderDetails" class="container mb-5">
		';
	}
	add_action('woocommerce_before_thankyou', 'top_part_of_order_details_open_container', 9);

	function top_part_of_order_details_close_container(){
		echo '
			</div>
		';
	}
	add_action('woocommerce_thankyou_cod', 'top_part_of_order_details_close_container', 5);




	/**
	===> Disable Variable Product Price Range on the WooCommerce Single Product Pages Only
	*/

	add_action( 'woocommerce_before_single_product', 'my_remove_variation_price' );
	function my_remove_variation_price() {
	  global $product;
	  if ( $product->is_type( 'variable' ) ) {
	    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );
	  }
	}


	/*
	===> Disable Variable Product Price Range on the WooCommerce Single Product Pages Only
	*/

	function move_variation_price() {
	    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
	    add_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation', 10 );
	}
	add_action( 'woocommerce_before_add_to_cart_form', 'move_variation_price' );



/*
==============================================================================
>>>> Start Woocommerce product categories
==============================================================================
*/
add_action( 'woocommerce_archive_description', 'shopPageAjaxCategories', 25 );
function shopPageAjaxCategories(){
	echo "<div id='shopPageAjaxCategories'>";
		echo do_shortcode( '[product_categories parent="0" columns="0" hide_empty="0" ids="97,98,44,42,18,43,17,21,16,19,41,20"]' );
	echo "</div>";
}


// Hide category product count in product archives
add_filter( 'woocommerce_subcategory_count_html', '__return_false' );


// Remove Product Category Images
// remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );


/*
==============================================================================
	<===== End Woocommerce product categories
==============================================================================
*/



/**
===> Show cart contents / total Ajax (Cart e kotogulo porduct add holo ta menu te show kora)
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
    ob_start();
    ?>
    <span class="topMenuCartItemsShow"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['span.topMenuCartItemsShow'] = ob_get_clean();
    return $fragments;
}

/**
 ===> Change the breadcrumb separator ============================================
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' <span class="px-2"> &gt; </span> ';
	return $defaults;
}

/**
 ===> Rename "My Account" If Logged Out @ Nav Menu ===============================
*/ 
add_filter( 'wp_nav_menu_items', 'bbloomer_dynamic_menu_item_label', 9999, 2 ); 
function bbloomer_dynamic_menu_item_label( $items, $args ) { 
   if ( ! is_user_logged_in() ) { 
      $items = str_replace( "My account", "Login", $items ); 
   } 
   return $items; 
}


/**
 ===> Due to base country and billing or shipping country are same, country aren't show in cart page shipping to area. Forcely Display it
*/
add_filter( 'woocommerce_formatted_address_force_country_display', '__return_true' );


/**
 >>> bivainno dese address bivinno base show hoy jemon germany te state show hoy na. address custom vabe show korer jonno.
*/
add_filter( 'woocommerce_localisation_address_formats', 'custom_address_display_formate_for_germany');
function custom_address_display_formate_for_germany(){
	$stateDisplayFormate = array( 'DE' => "{company}\n{name}\n{address_1}\n{address_2}\n{city} {postcode}\n{state}, {country}" );
	return $stateDisplayFormate;

}


add_filter( 'woocommerce_checkout_fields' , 'quadlayers_remove_checkout_fields' );
function quadlayers_remove_checkout_fields( $fields ) {
	unset($fields['billing']['billing_city']);
	unset($fields['shipping']['shipping_city']);
	return $fields; 

}


add_filter( 'default_checkout_billing_state', 'nazmul_change_default_checkout_state' );
function nazmul_change_default_checkout_state() {
	$state = WC()->customer->get_shipping_state();
	return $state; // state code
}