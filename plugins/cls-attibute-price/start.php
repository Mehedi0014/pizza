<?php

/*
 * Plugin Name: CLS Attribute Price
 * Version: 2.4.12
 * Plugin URI: https://cls-computer.de
 * Description: CLS Attribute Price Plugins fo Restaurant Theme.
 * Author URI: https://nazmul-alam.com
 * Author: Mohammad Nazmul Alam
 * Requires at least: 1.0
 * Tested up to: 7.*
 * Requires PHP: 7.*
 * Text Domain: woo-custom-product-addons
 * WC requires at least: 3.3.0
 * WC tested up to: 4.9.0
 */

if (!defined('ABSPATH')) exit;

define( 'CLS_AP_VERSION', '2.4.12' );
define( 'CLS_AP_NAME', 'CLS Attribute Price' );
define( 'CLS_AP_DIR', plugin_dir_path( __FILE__ ) );
define( 'CLS_AP_URL', plugins_url( '/', __FILE__ ) );
define( 'CLS_AP_SLUG', plugin_basename( __FILE__ ) );
define( 'CLS_AP_FILE', __FILE__ );

function cls_check_active(){
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        add_action('admin_notices', function () {
            echo '<div class="notice  notice-error is-dismissible">
                 <p><strong  style="color:#aa0000">' . CLS_AP_NAME . '</strong> Plugin need WooCommerce Plugin.</p>
                 </div>';
        });
    }
}

add_action( 'admin_init', 'cls_check_active' );





register_activation_hook( CLS_AP_FILE, function(){
    require_once CLS_AP_DIR. 'src/active.php';
});

register_deactivation_hook( CLS_AP_FILE, function(){
    require_once CLS_AP_DIR . 'src/inactive.php';
}); //direct use call back function

function edit_custom_terms($term) {
    ?>
    <tr class="form-field term-price-wrap">
        <th scope="row"><label for="price"><?php _e('Extra Price') ?></label></th>
        <td><input name="ex_price" id="ex_price" type="text" value="<?php echo $term->price?>" size="40" aria-required="true">
            <p class="description">Extra Price Add with Product Price.</p></td>
    </tr>
    <?php
}

function add_custom_terms() {
    ?>
    <tr class="form-field term-price-wrap">
        <th scope="row"><label for="ex_price"><?php _e('Extra Price') ?></label></th>
        <td><input name="ex_price" id="ex_price" type="text" value="" size="40" aria-required="true">
            <p class="description">Extra Price Add with Product Price.</p></td>
    </tr>
    <?php
}

function custom_terms_save( $term_id ) {
    if ( trim(esc_attr( $_POST[ 'ex_price' ])) !='' && $_POST[ 'ex_price' ]!=null ) {
        $term_price = esc_attr($_POST['ex_price']);
        global $wpdb;
        $terms_table = $wpdb->prefix .'terms';
        $t_sql  = "UPDATE $terms_table SET `price` = $term_price WHERE $terms_table.`term_id` = $term_id";
        $wpdb->query($t_sql);
    }
}


function add_post_tag_columns($columns){
    $columns['price'] = '<span style="color: #0073aa">Price</span>';
    return $columns;
}

function texo(){
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    foreach ($attribute_taxonomies as $attribute_taxonomy) {
        // Build taxonomy name

        $taxonomy = 'pa_' . $attribute_taxonomy->attribute_name;
        // Hook into 'new' and 'edit' term panels
        add_action("{$taxonomy}_add_form_fields",'add_custom_terms', 10, 2);
        add_action("{$taxonomy}_edit_form_fields", 'edit_custom_terms', 10, 2);
        // Hook save function into both the 'new' and 'edit' functions
        add_action("created_{$taxonomy}",  'custom_terms_save', 10, 2);
        add_action("edited_{$taxonomy}", 'custom_terms_save', 10, 2);

        //Hook The table Column
        add_filter("manage_edit-{$taxonomy}_columns", 'add_post_tag_columns');
        add_filter("manage_{$taxonomy}_custom_column", 'custom_columns',10,3);
    }
}
add_action( 'woocommerce_init', 'texo');


function custom_columns($output, $column_id, $id) {
    global $wpdb;
    $terms_table = $wpdb->prefix .'terms';
    $row = $wpdb->get_results(  "SELECT `price` FROM $terms_table WHERE $terms_table.`term_id` = $id" );
    $price = $row[0]->price;
    if($price!=null && $price !=''){
        return $output.=$price;
    }
    else{
        return $output.='—';
    }
}

/* ============================== Terms Update Complete ============================= */

function add_custom_attribute() {
    if(isset($_POST['display_type']) && isset($_POST['attribute_label'])){
        global $wpdb;
        $type = $_POST['display_type'];
        $name =  $_POST['attribute_label'];
        $attribute_table =$wpdb->prefix .'woocommerce_attribute_taxonomies';
        $sql = "UPDATE $attribute_table SET `special` = '$type' WHERE $attribute_table.`attribute_name` = '$name'";
        $wpdb->query($sql);
    }
    ?>
    <div class="form-field">
        <label for="display_type"><?php _e('Display Type', 'cls-attribute-price')?></label>
        <select name="display_type" id="display_type">
            <option value="0">Default Attribute System</option>
            <option value="1">Checkbox Attribute System</option>
            <option value="2">Pizza Extra Toppings</option>
            <option value="3">Extra Toppings System</option>
            <option value="4">Main Switch System</option>
        </select>
    </div>
    <p class="description" style="margin-bottom: 20px">Determines the sort order of the terms on the frontend shop product pages. If using custom ordering, you can drag and drop the terms in this attribute.</p>
    <?php
}

/* =================================== CODE OK ======================================= */

add_filter( 'woocommerce_get_price_html', 'cls_price_formatting' );
function cls_price_formatting($price){
    return "<span class='clsPriceDora'>$price</span>";
}

function edit_custom_attribute() {
    if(isset($_POST['display_type']) && isset($_POST['attribute_label'])){
        global $wpdb;
        $type = $_POST['display_type'];
        $name =  $_POST['attribute_label'];
        $attribute_table =$wpdb->prefix .'woocommerce_attribute_taxonomies';
        $sql = "UPDATE $attribute_table SET `special` = '$type' WHERE $attribute_table.`attribute_label` = '$name'";
        $wpdb->query($sql);
    }

    if(isset($_GET['edit'])&&$_GET['page']=='product_attributes'){
        global $wpdb;
        $id = $_GET['edit'];
        $attribute_table =$wpdb->prefix .'woocommerce_attribute_taxonomies';
        $row1 = $wpdb->get_results(  "SELECT `special` FROM $attribute_table WHERE `attribute_id` = '$id' " );
        if($row1){
            $data =  $row1[0]->special;
            if($data==null|| $data=='')$data=0;
        }
        else $data = 0;
    }

    $att_display_type = [
        "Default Attribute System",
        "Checkbox Attribute System",
        "Pizza Extra Toppings",
        "Extra Toppings System",
        "Main Switch System"
    ];
    ?>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="display_type"><?php _e('Display Type', 'cls-attribute-price')?></label>
        </th>
        <td>
            <select name="display_type" id="display_type">
                <?php  foreach ($att_display_type as $key=>$val):?>
                    <?php if($data == $key)$selected ='selected';
                    else $selected =''?>
                    <option value="<?= $key ?>" <?= $selected ?>> <?= $val ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <?php
}
add_action( 'woocommerce_after_edit_attribute_fields', 'edit_custom_attribute', 10, 1 );
add_action( 'woocommerce_after_add_attribute_fields', 'add_custom_attribute', 10, 1 );

/* ================ Attribute CODE Complete =====================================  */



//#######################################################################################################


/* Frontend CODE Start here -------------------------------------------------------------------  */


add_action( 'woocommerce_before_add_to_cart_button', 'additional_info_wrapper_start', 33 );
function additional_info_wrapper_start(){
    echo '<div class="additional_information_wrapper">';
}
add_action( 'woocommerce_before_add_to_cart_button', 'additional_info_wrapper_end', 36 );
function additional_info_wrapper_end(){
    echo "</div>";
}

add_action( 'woocommerce_before_add_to_cart_button', 'additional_info_before_add_to_cart_size', 34 );
function additional_info_before_add_to_cart_size(){
    global $product;
    $product_attributes = $product->get_attributes(); // Get the product attributes

    echo '<div class="productSizeWrapper"><ul class="list-unstyled">';

    foreach( $product_attributes as $attr_name => $attr ){
        $att_name =  wc_attribute_label($attr_name);  // Mainly Attribute Label
        $att_type  = get_cls_wc_attribute_type($att_name);

        if($att_type ==4) {
            echo "<h5 class='title forApplyRule'>$att_name</h5>";
                foreach ($attr->get_terms() as $term) {
                    if($term->price ==0){
                        $class ='defaultRadio';
                    }
                    else{
                        $class ='';
                    }
                    ?>
                        <li>
                            <input class="main_bu <?php echo $class ?>" type="radio" id="<?php echo $term->name; ?>" name="main_group[]"
                                   value="<?php echo $term->term_id ?>" data-type="main" <?php echo($term->price ==0)?"checked":""?> data-price="<?php echo $term->price ?>" />
                            <label for="main_group"><?php echo $term->name ?> : + <?php echo wc_price($term->price)?></label><br>
                        </li>
                    <?php
                }
        }
    }
    echo '</ul></div>';
}

add_action( 'woocommerce_before_add_to_cart_button', 'additional_info_before_add_to_cart', 35 );
function additional_info_before_add_to_cart(){
    global $product;

    if ( get_post_type( $product ) === 'product' && ! is_a($product, 'WC_Product') ) {
        $product = wc_get_product( get_the_id() ); // Get the WC_Product Object
    }

    $sale_price = trim($product->get_sale_price());

    if ($sale_price == '' || $sale_price == null) {
        echo "<script> var pizza_dat = false; </script>";

        echo "<script> var single_pizza = false; </script>";

    } else{
        echo "<script> var pizza_dat = true; </script>";
        echo "<script> var single_pizza = false; </script>";
    }

    $product_attributes = $product->get_attributes(); // Get the product attributes

    $k = [];

    echo '<div class="extraToppingWrapper"><ul class="list-unstyled">';
    foreach( $product_attributes as $attr_name => $attr ){
        $att_name =  wc_attribute_label($attr_name);  // Mainly Attribute Label
        $att_type  = get_cls_wc_attribute_type($att_name);



        if($att_type ==3) {

            echo "<script> var single_product =true; </script>";

            echo "<h5 class='title forApplyRule'>Extra Toppings</h5>";
            foreach ($attr->get_terms() as $term) {

                $k[] = $attrFreeData = $term->name;
                ?>
                <li>
                    <input type="checkbox" id="<?php echo $term->name; ?>" name="free_group[]"
                           value="<?php echo $term->term_id; ?>" data-type="free" checked>
                    <label for="free_group"><?php echo $attrFreeData; ?> (No Charge)</label><br>
                </li>
                <?php
            }

            echo "<h4 id='Extra' class='d-none' data-product='" . $product->get_price() . "'>Zusätzliche Beläge </h4>";

            $attr_terms = get_cls_wc_attribute_terms($attr['name']);

            foreach ($attr_terms as $key => $value) {
                if (in_array($value->name, $k)) {
                    continue;
                }
                if ((int)$value->price <= 0) continue;
                $attrPaidData = $value->name . ' ' . wc_price($value->price);
                ?>
                <li>
                    <input type="checkbox" id="<?php echo $value->name; ?>" name="paid_group[]" class="ex_topping" data-price="<?php echo $value->price ?>" value="<?php echo $value->term_id; ?>">
                    <label for="paid_group"><?php echo $attrPaidData; ?></label><br>
                </li>
                <?php
            }
        }



        if($att_type ==2) {
            echo "<script>";
            $data_p = [
                    'small'=>get_option('Small Pizza Price'),
                    'large'=>get_option('Large Pizza Price'),
                    'medium'=>get_option('Medium Pizza Price'),
                    'party'=>get_option('Party Pizza Price')
            ];
            $fee_count= get_option('Party Price Free Count');
            echo "var free ='$fee_count' ;";
            echo 'var pizza_dat ='.json_encode($data_p);
            echo "</script>";
            echo "<h5 class='title forApplyRule'>Pizza Extra Toppings</h5>";
            echo "<p> Party Pizza first $fee_count topping are free. </p>";
            foreach ($attr->get_terms() as $term) {
                $k[] = $attrFreeData = $term->name;
                ?>
                <li>
                    <input type="checkbox" id="<?php echo $term->name; ?>" name="pizza_paid_group[]" class="ex2_topping"
                           value="<?php echo $term->term_id; ?>" data-type="free" >
                    <label for="pizza_paid_group"><?php echo $attrFreeData; ?> <span class="charge_att">0</span></label><br>
                </li>
                <?php
            }
        }
        if($att_type ==1){
            $color = $product->get_attribute('pa_size');

            if($color!=null){
                $color=explode(",",$color);
                $size= $color[0];
            }
            else{
                $size=null;
            }

            if($size!=null||$size!=''){
                $size=strtolower($size);
                echo "<script>";
                echo "var size_k = '$size'";
                echo "</script>";
            }


            if($size!=null||$size!=''){
                $size= strtolower($size);
                echo "<input type='hidden' name='attribute_pa_size' value='".$size."'/>";
            }


            echo "<script> var single_pizza =true; </script>";
            echo "<script>";
            $data_p = [
                'small'=>get_option('Small Pizza Price'),
                'large'=>get_option('Large Pizza Price'),
                'party'=>get_option('Party Pizza Price')
            ];

            $fee_count= get_option('Party Price Free Count');
            echo "var free ='$fee_count' ;";
            echo 'var pizza_dat ='.json_encode($data_p);
            echo "</script>";
            echo "<h5  class='title forApplyRule' id='single_pizza' data-price='". $product->get_price()."'>Pizza Zusätzliche Beläges</h5>";
            foreach ($attr->get_terms() as $term) {
                $k[] = $attrFreeData = $term->name;
                ?>
                <li>
                    <input type="checkbox" id="<?php echo $term->name; ?>" name="pizza_paid_group[]" class="ex2_topping"
                           value="<?php echo $term->term_id; ?>" data-type="free" >
                    <label for="pizza_paid_group"><?php echo $attrFreeData; ?> <span class="charge_att">0</span></label><br>
                </li>
                <?php
            }


        }
    }
    echo '</ul></div>';
}

function get_cls_wc_attribute_terms($attr_name){
    global $wpdb;
    $terms_table = $wpdb->prefix .'terms';
    $taxonomy_table = $wpdb->prefix .'term_taxonomy';
    $sql = "SELECT * FROM $taxonomy_table JOIN $terms_table ON $taxonomy_table.`term_id` = $terms_table.`term_id` WHERE $taxonomy_table.`taxonomy` = '$attr_name'";
    $data = $wpdb->get_results($sql);
    if($data == null || count($data) <= 0 ){
        return false;
    } else {
        return $data;
    }
}

function get_cls_wc_attribute_type($attr_name){
    global $wpdb;
    $att_table = $wpdb->prefix . 'woocommerce_attribute_taxonomies';
    $sql = "SELECT `special` FROM $att_table  WHERE $att_table.`attribute_label` = '$attr_name'";
    $data = $wpdb->get_results($sql);
    if ($data == null || count($data) <= 0) {
        return false;
    } else {
        if($data[0]->special==null)return false;
        else return $data[0]->special;
    }
}


add_filter( 'woocommerce_add_to_cart_validation', 'cls_security_check', 60, 3 );
 function cls_security_check($is_allow, $product_id, $quantity){

     $product = wc_get_product( $product_id );

         $product_attributes = $product->get_attributes(); // Get the product attributes
         foreach ($product_attributes as $attr_name => $attr) {
             $att_name = wc_attribute_label($attr_name);  // Mainly Attribute Label
             $att_type = get_cls_wc_attribute_type($att_name);
             if($att_type =='3') {
                 $terms = [];
                 foreach ($attr->get_terms() as $att) {
                     $terms[] = $att->term_id;
                 }

                 if (isset($_POST['free_group'])) {
                     $free_items = $_POST['free_group'];

                     foreach ($free_items as $item) {
                         if (!in_array($item, $terms)) {
                             wc_clear_notices();
                             wc_add_notice(__("Sorry We Cannot Add this Product Right Now", "wcfm-ecogear"), 'error');
                             return false;
                         }

                     }
                 }

                 if (isset($_POST['pizza_paid_group'])) {
                     $pizza_paid_items = $_POST['pizza_paid_group'];
                     foreach ($pizza_paid_items as $item) {
                         if (!in_array($item, $terms)) {
                             wc_clear_notices();
                             wc_add_notice(__("Sorry We Cannot Add this Product Right Now", "wcfm-ecogear"), 'error');
                             return false;
                         }
                     }
                 }


                 if (isset($_POST['paid_group'])) {
                     $paid_items = $_POST['paid_group'];
                     $all_terms = [];
                     foreach (get_cls_wc_attribute_terms($attr['name']) as $td) {
                         $all_terms[] = $td->term_id;
                     }
                     $final_terms = array_diff($all_terms, $terms);
                     foreach ($paid_items as $p_item) {
                         if (!in_array($p_item, $final_terms)) {
                             wc_clear_notices();
                             wc_add_notice(__("Sorry We Cannot Add this Product Right Now", "wcfm-ecogear"), 'error');
                             return false;
                         }
                     }
                 }
             }
             if($att_type =='4') {
                 $m_terms = [];
                 foreach ($attr->get_terms() as $att) {
                     $m_terms[] = $att->term_id;
                 }
                 if (isset($_POST['main_group'])) {
                     $main_group = $_POST['main_group']; //array
                     foreach ($main_group as $main) {
                         if (!in_array($main, $main_group)) {
                             wc_clear_notices();
                             wc_add_notice(__("Sorry We Cannot Add this Product Right Now", "wcfm-ecogear"), 'error');
                             return false;
                         }
                     }
                 }
             }
         }

      return $is_allow;
 }

 // Validation and Security Check Clear.....................

/* Set Price ////////////////////////////////////////////////////////////////////////// */
function set_custom_price($cartItemData, $porductId){


if( $_POST['attribute_pa_size']){
    $cartItemData['size'] = $_POST['attribute_pa_size'];
    }

   $product =  wc_get_product($porductId);

    $cartItemData['product']['name'] = $product->get_name();
    $cartItemData['product']['price'] = $product->get_price();

    if(isset($_POST['free_group'] ) ){
        $free_group = $_POST['free_group']; //array
        foreach ($free_group as $free){
            $f_item = get_term($free);
            $cartItemData['free'][]= $f_item->name;
        }

    }
    if(isset($_POST['paid_group'] ) ){
        $paid_group = $_POST['paid_group']; //array
        $cartItemData['paid'] = $paid_group;
    }


    if(isset($_POST['pizza_paid_group'] ) ){
        $pizza_paid_group = $_POST['pizza_paid_group']; //array
        $cartItemData['pizza_paid_group'] = $pizza_paid_group;
    }


    if(isset($_POST['main_group'] ) ){
        $main_group = $_POST['main_group']; //array
        $cartItemData['main_paid'] = $main_group;
    }

    return $cartItemData;
}
add_filter('woocommerce_add_cart_item_data', 'set_custom_price', 59, 2);

/* Price Update ================================================= */
function apply_custom_price($cart){
    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;
    // Loop through cart items
    $ex_top_price = 0;

    foreach ( $cart->get_cart() as $item ) {
           if(isset($item['paid'])){
           $sum=0;
           foreach ($item['paid'] as $paid){
               $f_item = get_term($paid);
                $sum+= (float)$f_item->price;
           }
        $item['data']->set_price( (float)$item['data']->price+(float)$sum );
        $ex_top_price =$sum;
        }

        if(isset($item['main_paid'])){
            $sum=0;
            foreach ($item['main_paid'] as $paid){
                $m_item = get_term($paid);
                $sum+= (float)$m_item->price;
            }

            $ex_top_price = $sum+$ex_top_price;
            $item['data']->set_price( (float)$item['data']->price+(float)($ex_top_price) );
        }

        if(isset($item['pizza_paid_group'])){
            $sum=0;
            if(isset($item['size'])){
                switch ($item['size']){
                    case 'small': $size_price =(float) get_option('Small Pizza Price');break;
                    case 'large': $size_price = (float)get_option('Large Pizza Price');break;
                    case 'party': $size_price = (float)get_option('Party Pizza Price');break;
                    default:  $size_price = 0;
                }

                if($item['size']=='party'){
                    $free_count =  get_option('Party Price Free Count');
                }
                else $free_count =0;
            }
            else {
                $size_price = 0;
                $free_count =0;
            }

            $t_items = count($item['pizza_paid_group']);

            if($t_items >= $free_count){
                $t_items = (int)$t_items- (int)$free_count;
            }
            $sum = $t_items * $size_price;

           if($ex_top_price==0){
               $ex_top_price = (float)$item['data']->price;
           }

            $ex_top_price = $sum+$ex_top_price;

            $item['data']->set_price( (float)($ex_top_price) );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'apply_custom_price', 99, 1 );

/* Display Text in Product --------------------------------------------------- */

function display_custom_item_data($cart_item_data, $cart_item) {


  // $name  = $cart_item['product']['name'];    # Product Name
 //  $price  =  $cart_item['product']['price']; # Product Base Price

    if (isset($cart_item['free'])|| isset($cart_item['paid'])|| isset($cart_item['main_paid'])) {
    /*  $cart_item_data[] = array(
            'name' =>$name." Base Price",
            'value' =>  wc_price($price)
        );*/
        if(isset($cart_item['free'])){
            foreach ($cart_item['free'] as $free){
                $cart_item_data[] = array(
                    'name' => __($free, "woocommerce"),
                    'value' => "No Charge"
                );
            }
        }




        if(isset($cart_item['paid'])){
            foreach ($cart_item['paid'] as $paid){
                $f_item = get_term($paid);
                $cart_item_data[] = array(
                    'name' => __($f_item->name, "woocommerce"),
                    'value' => strip_tags( '+ ' . wc_price( wc_get_price_to_display( $cart_item['data'], array('price' => $f_item->price ) ) ) )
                );
            }
        }


        if(isset($cart_item['main_paid'])){
            foreach ($cart_item['main_paid'] as $paid){
                $m_item = get_term($paid);
                $cart_item_data[] = array(
                    'name' => __($m_item->name, "woocommerce"),
                    'value' => strip_tags( '+ ' . wc_price( wc_get_price_to_display( $cart_item['data'], array('price' => $m_item->price ) ) ) )
                );
            }
        }

    }


    if(isset($cart_item['pizza_paid_group'])){

                if(isset($cart_item['size'])){
                    switch ($cart_item['size']){
                        case 'small': $size_price =(float) get_option('Small Pizza Price');break;
                        case 'large': $size_price = (float)get_option('Large Pizza Price');break;
                        case 'party': $size_price = (float)get_option('Party Pizza Price');break;
                        default:  $size_price = 0;
                    }

                    if($cart_item['size']=='party'){
                        $free_count =  get_option('Party Price Free Count');
                    }
                    else $free_count =0;

                }

                else{
                    $free_count =0;
                    $size_price =0;
                }
        foreach ($cart_item['pizza_paid_group'] as $pizza_paid){
            $f_item = get_term($pizza_paid);


                            if($free_count!=0){
                                $item_price  = 0;
                                $free_count--;
                            }
                            else{
                                $item_price =$size_price;
                            }
             $cart_item_data[] = array(
                'name' => __($f_item->name, "woocommerce"),
                'value' => strip_tags( '+ ' . wc_price( wc_get_price_to_display( $cart_item['data'], array('price' => $item_price ) ) ) )
            );

        }
    }




    return $cart_item_data;
}

add_filter('woocommerce_get_item_data', 'display_custom_item_data', 10, 2);


/* ================== END of CART FUNCTION ==========================================  */



/* =============== ADD Order Custom Attribute ==================================================== */

//add_filter( 'woocommerce_checkout_create_order', 'mbm_alter_shipping', 10, 1 );

add_action('woocommerce_checkout_create_order_line_item','cls_save_custom_data_order', 10, 4 );

function cls_save_custom_data_order( $item, $cart_item_key, $values, $order ) {
    $meta_key = 'PR CODE';

   // $name  = $item['name'];    # Product Name
   // $price  =  $values['product']['price']; # Product Base Price

    if (isset($values['free'])|| isset($values['paid'])|| isset($values['main_paid'])) {

        if(isset($values['free'])){
            foreach ($values['free'] as $free){
                $item->update_meta_data( $free,"No Charge" );
            }
        }

        if(isset($values['paid'])){
            foreach ($values['paid'] as $paid){
                $f_item = get_term($paid);
                $item->update_meta_data( $f_item->name,wc_price($f_item->price) );
            }
        }





        if(isset($values['main_paid'])){
            foreach ($values['main_paid'] as $paid){
                $m_item = get_term($paid);
                $item->update_meta_data( $m_item->name,wc_price($m_item->price) );
            }
        }

    }



    if(isset($values['pizza_paid_group'])){

        if(isset($values['size'])){
            switch ($values['size']){
                case 'small': $size_price =(float) get_option('Small Pizza Price');break;
                case 'large': $size_price = (float)get_option('Large Pizza Price');break;
                case 'party': $size_price = (float)get_option('Party Pizza Price');break;
                default:  $size_price = 0;
            }

            if($values['size']=='party'){
                $free_count =  get_option('Party Price Free Count');
            }
            else $free_count =0;
        }

        else{
            $free_count =0;
            $size_price =0;
        }


        foreach ($values['pizza_paid_group'] as $pizza_paid){
            $f_item = get_term($pizza_paid);



                            if($free_count!=0){

                                $item_pirce  = 'free';
                                $free_count--;
                            }
                            else{
                                $item_pirce =wc_price($size_price);
                            }

            $item->update_meta_data( $f_item->name,$item_pirce);

        }
    }




}


/* add custom JS in WooCommerce Template Single Page ==========================  */

function cls_attribute(  ) {
    if ( is_single(  )) {

        /*
        wp_register_style( 'Font_Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
        wp_enqueue_style('Font_Awesome');

        wp_register_script( 'tiger', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', null, null, true );
        wp_enqueue_script('tiger');
        */

        wp_enqueue_style( 'cls_style', plugins_url( 'css/style.css', CLS_AP_FILE ) );

        wp_enqueue_script( 'cls_jquery', plugins_url( 'js/jquery-3.5.1.min.js', CLS_AP_FILE ) );
        wp_enqueue_script( 'cls_attribute', plugins_url( 'js/cls-attribute.js', CLS_AP_FILE ),'jquery-core-js' );
    }
}
add_action( 'wp_enqueue_scripts', 'cls_attribute' );




/*
==================================================================================
>>>> Add menu for set extra topping price
==================================================================================
*/

/*Make a menu in admin backing panel ===========================*/
function extraToppingsPrice(){
    add_menu_page(
        'Extra Toppings Price', // page Title
        'Extra Toppings Price', // menu title
        'manage_options', // user access capability
        'cls_extra_toppings_pizza_price_page', // page menu slug
        'cls_extra_toppings_pizza_price_dashboard', // callback function
        'dashicons-chart-line', // icon image url
        null // position, where the menu display
    );
}
add_action('admin_menu', 'extraToppingsPrice');


/*callback function  ===========================================*/
function cls_extra_toppings_pizza_price_dashboard (){
    require_once CLS_AP_DIR . 'price-setting-page.php';
}






























































