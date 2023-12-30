<?php

/**
 * Created Nazmul
 * User: Nazmul
 * Date: 11-May-21
 * Time: 7:07 AM
 */
Final class CLSofferMain{

    private $offerModel;

    private $rules = [
        ['name' => 'category_product', 'rule' => ['category_id', 'minimum_products_qty']],
        ['name' => 'custom_product', 'rule' => ['minimum_products_qty', 'custom_products_sku']],
        ['name' => 'minimum_price', 'rule' => ['minimum_price']],
        ['name' => 'minimum_products', 'rule' => ['minimum_products']],
    ];


    function __construct (){
        require_once(CLS_OFFER_DIR . 'Lib/vendor/autoload.php');
        require_once( CLS_OFFER_DIR . 'Model/Offer.php');
        $this->offerModel = new Offer;
        $this->init();
    }
    private function init(){
        register_activation_hook( CLS_OFFER_FILE,[$this,'Active']);
        register_deactivation_hook( CLS_OFFER_FILE,[$this,'Dactive']);
        add_action('admin_menu', [ $this, 'create_menu'] );
        register_activation_hook( CLS_OFFER_FILE, array( $this, 'offerPageCreator') );
        add_filter ('page_template', array( $this, 'setTemplateforOfferPage') );
        add_action("wp_ajax_my_offer", [$this,"my_offer"]);
        add_action("wp_ajax_delete_offer", [$this,"delete_offer"]);
        add_action("template_redirect",[$this,"add_offer_cart"]);

        add_action( 'woocommerce_before_calculate_totals', [$this,'apply_offer_price'], 99, 1 );
        add_action('woocommerce_checkout_create_order_line_item',[$this,'add_order_attribute'], 20, 4 );
        add_filter('woocommerce_get_item_data', [$this,'add_cart_attribute'], 40, 2);
    }



    function apply_offer_price($cart){
        foreach ( $cart->get_cart() as $item ) {
            if (isset($item['model'])&&isset($item['products'])) {
                $item['data']->set_price((float)$item['price']);
            }
        }

    }


    function add_order_attribute($item, $cart_item_key, $values, $order){
        if(isset($values['model']) && isset($values['products'])){
            $item->update_meta_data('Angebote',$values['des']);
            foreach($values['products'] as $product) {
                $item->update_meta_data($product['name']."-".$product['sku'], wc_price( $product['price']));
            }

           if(count($values['free_products'])>0){
                foreach($values['free_products'] as $product) {
                    $item->update_meta_data($product['name']."-".$product['sku'], 0);
                }
            }

        }
    }


    function add_cart_attribute($cart_item_data, $cart_item){


        if(isset($cart_item['model']) && isset($cart_item['products'])){
            $cart_item_data[] = array(
                'name' => 'Angebote',
                'value' => __( $cart_item['des'], "woocommerce")
            );

            foreach($cart_item['products'] as $product) {
                $cart_item_data[] = array(
                    'name' => $product['name']."(".$product['sku'].")",
                    'value' =>strip_tags( '+ ' . wc_price( wc_get_price_to_display( $cart_item['data'], array('price' =>$product['price']) ) ) )
                );
            }

          if(count($cart_item['free_products'])>0){
                foreach($cart_item['free_products'] as $product) {
                    $cart_item_data[] = array(
                        'name' => $product['name'] . "(" . $product['sku'] . ")",
                        'value' => 0

                    );
                }
            }


        }

        return $cart_item_data;
    }



    public function add_offer_cart(){

        if( is_cart() || is_checkout() ) {
            if (isset($_SESSION['$offer'])) {
                foreach($_SESSION['$offer'] as $key=>$offerNew){
                    if($offerNew['complete']==false) $this->throw_offer_error();
                }
                foreach($_SESSION['$offer'] as $key=>$offerNew){
                    $cartItemData['id'] = $offerNew['offer_id'];
                    $cartItemData['price']=$offerNew['price_amount'];
                    $cartItemData['type']=$offerNew['type'];
                    $cartItemData['products']=$offerNew['items'];
                    $cartItemData['free_products'] = $offerNew['free'];
                    $cartItemData['model'] ='offer';
                    $offer = $this->offerModel->load_offer( $cartItemData['id'],OBJECT);
                    $R_product=  $offer->ref_product_id;
                    $cartItemData['des'] = $offer->des;
                    //$_pf = new WC_Product_Factory();
                  /*  if ( ! $product->is_purchasable() ) {
                        exit("Offer Stock Problem");
                    }*/

                    global $woocommerce;
                    $woocommerce->cart->add_to_cart($R_product,1,0,[],$cartItemData);
                }
                unset($_SESSION['$offer']);
            }

        }
    }

    function throw_offer_error(){
        $template = CLS_OFFER_DIR . '/templates/before_cart.php';
        include_once($template);
        exit;
    }


    public function Active(){
        $this->offerModel->create_table()->create_condition_table()->flush_DB();

    }
    public function Dactive(){
        $this->offerModel->delete_all_table()->flush_DB();
        $this->remove_product_template();
    }
    public function create_menu(){
        add_menu_page(
            'Produktangebot', // page Title
            'Produktangebot', // menu title
            'manage_options', // user access capability
            'cls-create-products-offer', // page menu slug
            array($this, 'cls_products_offer_create'), // callback function
            'dashicons-welcome-widgets-menus', // icon image url
            null // position, where the menu display
        );
        add_submenu_page(
            'cls-create-products-offer',
            'Produktangebot erstellen ',
            'Produktangebot erstellen ',
            'manage_options',
            'cls-create-products-offer'
        );
        add_submenu_page(
            'cls-create-products-offer',
            'Produktangebot anzeigen',
            'Produktangebot anzeigen',
            'manage_options',
            'cls-view-products-offer',
            array($this, 'cls_products_offer_view'),
            null
        );
    }
    public function cls_products_offer_create(){
        require_once CLS_OFFER_DIR. 'templates/admin/create-product-offer.php';
        wp_enqueue_style( 'bootstrap_offer', CLS_OFFER_PATH. 'assets/css/bootstrap.min.css' );
        wp_enqueue_style( 'cls_offer_create_css', CLS_OFFER_PATH. 'assets/css/offer.css' );

        wp_enqueue_script( 'jquery_min_js_offer', CLS_OFFER_PATH . 'assets/js/jquery-3.6.0.min.js', array(), '1.2');
        wp_enqueue_script( 'pooper_min_js_offer', CLS_OFFER_PATH . 'assets/js/popper.min.js', array(), '1.2' );
        wp_enqueue_script( 'bootstrap_min_js_offer', CLS_OFFER_PATH .'assets/js/bootstrap.min.js', array(), '1.2' );

        wp_enqueue_script( 'add_vue_js', CLS_OFFER_PATH . 'assets/js/vue.min.js', array(), '1.2' );
        wp_enqueue_script( 'create_offer_script', CLS_OFFER_PATH. 'assets/js/offer.js', array(), '1.0' );
    }

    public function cls_products_offer_view(){
        require_once CLS_OFFER_DIR. 'templates/admin/view-product-offer.php';
        wp_enqueue_style( 'bootstrap_offer', CLS_OFFER_PATH. 'assets/css/bootstrap.min.css' );
        wp_enqueue_style( 'datatable_boot_css', CLS_OFFER_PATH. 'assets/css/dataTables.bootstrap4.min.css' );
        wp_enqueue_style( 'datatable_css', CLS_OFFER_PATH. 'assets/css/dataTables.min.css' );

        wp_enqueue_script( 'jquery_min_js_offer', CLS_OFFER_PATH . 'assets/js/jquery-3.6.0.min.js', array(), '1.2');
        wp_enqueue_script( 'pooper_min_js_offer', CLS_OFFER_PATH . 'assets/js/popper.min.js', array(), '1.2' );
        wp_enqueue_script( 'bootstrap_min_js_offer', CLS_OFFER_PATH .'assets/js/bootstrap.min.js', array(), '1.2' );
        wp_enqueue_script( 'datatabke_min_js_offer', CLS_OFFER_PATH .'assets/js/dataTables.min.js', array(), '1.2' );
        wp_enqueue_script( 'boot4data_min_js_offer', CLS_OFFER_PATH .'assets/js/dataTables.bootstrap4.min.js', array(), '1.2' );
        wp_enqueue_script( 'nazmul_min_js_offer', CLS_OFFER_PATH .'assets/js/nazmul.js', array(), '1.2' );

    }

    function offerPageCreator(){
        $page_title = 'Angebote';
        if ( get_page_by_title( $page_title) == NULL ) {
            $productsOfferPage = array(
                'post_title'        => $page_title,
                'post_name'         => 'angebote',
                'post_content'      => '',
                'post_status'       => 'publish',
                'post_type'         => 'page',
                'page_template'     => 'product_offer_display_page.php'
            );
            $insert_page = wp_insert_post( $productsOfferPage );
        }
    }
    public function setTemplateforOfferPage( $page_template ) {
        if ( is_page( 'Angebote' ) ) {
            $page_template = CLS_OFFER_DIR . '/templates/product_offer_display_page.php';

            wp_enqueue_script( 'jquery_min_js_offer', CLS_OFFER_PATH . 'assets/js/jquery-3.6.0.min.js', array(), '1.2',true);
            wp_enqueue_script( 'pooper_min_js_offer', CLS_OFFER_PATH . 'assets/js/popper.min.js', array(), '1.2',true );
            wp_enqueue_script( 'bootstrap_min_js_offer', CLS_OFFER_PATH .'assets/js/bootstrap.min.js', array(), '1.2' ,true);
            wp_enqueue_script( 'add_vue_js', CLS_OFFER_PATH . 'assets/js/vue.min.js', array(), '1.2',true );
            wp_enqueue_script( 'nazmul2_js', CLS_OFFER_PATH . 'assets/js/nazmul2.js', array(), '1.2',true );
        }
        return $page_template;
    }
    private function remove_product_template(){
        $page_slug = 'angebote';
        $page = get_page_by_path($page_slug);
        if ($page) {
            $pageId = $page->ID;
        } else {
            return NULL;
        }
        wp_delete_post($pageId, true);
    }



    public  function my_offer() {





        if (isset($_POST['offerName'])&& isset($_POST["offerTitle"])) {

            $name = $_POST['offerName'];
            $title = $_POST["offerTitle"];
            $offerType = $_POST["offerType"];
            $discount_amount = $_POST['discount_amount'];
            $offerDiscountType = $_POST['offerDiscountType'];
            $fixed_price_amount = $_POST['fixed_price_amount'];
            $homeDelivery = $_POST['homeDelivery'];
            $LocalPickup = $_POST['LocalPickup'];
            $enableExpire = $_POST['enableExpire'];
            $offerExpireDate = $_POST['offerExpireDate'];
            $rule_condition = $_POST['rule_condition'];
            $conditions = json_decode(stripslashes($_POST['conditions']), true);
            $mode = $_POST['mode'];
            $offer_id = $_POST['offer_id'];
            if (trim($name) == "" || trim($title == "")) {
                echo json_encode(['msg' => "error", 'content' => "Name oder Beschreibung Problem "]);
                exit;
            }
            if ($offerType == 'Fixed') {
                if (trim($fixed_price_amount) == "") {
                    echo json_encode(['msg' => "error", 'content' => "Festpreis Betrag muss benötigt werden "]);
                    die();
                }
            }
            if ($offerType == 'Discount') {
                if (trim($discount_amount) == "" || (int)$discount_amount == 0) {
                    echo json_encode(['msg' => "error", 'content' => "Rabatt Betrag muss benötigt werden "]);
                    die();
                }
            }
            if (!empty($conditions)) {
                $rules = $this->rules;
                foreach ($conditions as $condition) {
                    foreach ($rules as $rule) {
                        if ($rule['name'] === $condition['offerConditionType']) {
                            foreach ($rule['rule'] as $item) {
                                if ($condition[$item] == "") {
                                    echo json_encode(['msg' => "error", 'content' => "$item Artikel darf nicht leer sein "]);
                                    die();
                                }
                            }
                            break;
                        }
                    }
                }
            } else {
                echo json_encode(['msg' => "error", 'content' => "Muss One Condition ausgewählt sein"]);
                die();
            }
            $photo = $_FILES["file"]["tmp_name"];
            $image = null;
            if($photo){
                $UploadDir =CLS_OFFER_DIR .'uploads/';
                $uploadImageName = basename($_FILES["file"]["name"]);
                $extension=end(explode(".", $uploadImageName));
                $img_name = explode(".", $uploadImageName)[0] ."_".time();
                $imagePath = $UploadDir . $img_name . "." . $extension;
                $file_type = $_FILES["file"]["type"];
                $size = $_FILES["file"]["size"];
                $media_path_with_image =  CLS_OFFER_PATH."/uploads/". $img_name . "." . $extension;;

                // Check file size
                if ($size > 500000) {
                    echo json_encode(['msg'=>"error",'content'=>"Entschuldigung, Ihre Datei ist zu groß ."]);
                    die();
                }

                $type_array =['image/png','image/jpeg','image/gif','image/webp'];

                if(!in_array($file_type,$type_array)){
                    echo json_encode(['msg'=>"error",'content'=>"Ungültiger Bildtyp."]);
                    die();
                }

                try {
                    move_uploaded_file($photo, $imagePath);
                }
                catch (\Exception $e){
                    echo json_encode(['msg'=>"error",'content'=>$e->getMessage()]);
                    die();
                }
                $image=$media_path_with_image;
            }


            try{




                if($mode!='edit') {
                    $post_id = $this->create_product_post($name, $title, $fixed_price_amount, $image);
                }
               $offerData =[
                    'name'=>$name,
                    'des'=>$title,
                    'image'=>$image,
                    'image_alt'=>$name,
                    'offer_type'=>$offerType,
                    'price_amount'=>$fixed_price_amount,
                    'discount_type'=>$offerDiscountType,
                    'discount_amount'=>$discount_amount,
                    'condition_rule'=>$rule_condition,
                    'expire_system'=>$enableExpire,
                    'expire_date'=>($offerExpireDate!=null)?date('Y-m-d', strtotime($offerExpireDate)):null,
                    'local_pickup'=>$LocalPickup,
                    'home_delivery'=>$homeDelivery,
                    'ref_product_id'=>$post_id,
                    'create_date'=>date('Y-m-d'),
                    'edit_date'=>date('Y-m-d')
                ];

                if($mode!='edit') {

                    $offer_id = $this->offerModel->create_offer($offerData);
                }
                else{


                    $offerData =[
                        'name'=>$name,
                        'des'=>$title,
                        'image'=>$image,
                        'image_alt'=>$name,
                        'offer_type'=>$offerType,
                        'price_amount'=>$fixed_price_amount,
                        'discount_type'=>$offerDiscountType,
                        'discount_amount'=>$discount_amount,
                        'condition_rule'=>$rule_condition,
                        'expire_system'=>$enableExpire,
                        'expire_date'=>($offerExpireDate!=null)?date('Y-m-d', strtotime($offerExpireDate)):null,
                        'local_pickup'=>$LocalPickup,
                        'home_delivery'=>$homeDelivery,
                        'edit_date'=>date('Y-m-d')
                    ];


                   $k= $this->offerModel->update_offer($offer_id,$offerData);

                }

                if(!empty($conditions)){
                    foreach ($conditions as $condition){
                        try {
                            $offer_cond = [
                                'offer_id' => $offer_id,
                                'condition_type' => $condition['offerConditionType'],
                                'category_id' => (isset($condition['category_id'])) ? $condition['category_id'] : null,
                                'minimum_product_qty' => (isset($condition['minimum_products_qty'])) ? $condition['minimum_products_qty'] : null,
                                'custom_product_sku' => (isset($condition['custom_products_sku'])) ? $condition['custom_products_sku'] : null,
                                'minimum_product_price' => (isset($condition['minimum_price'])) ? $condition['minimum_price'] : null,
                                'free_items_qty' => (isset($condition['free_items_qty'])) ? $condition['free_items_qty'] : null,
                                'free_item_category' => (isset($condition['free_item_category'])) ? $condition['free_item_category'] : null,
                                'fixed_free_items_sku' => (isset($condition['fixed_free_items_sku'])) ? $condition['fixed_free_items_sku'] : null,
                                 'total_amount' => (isset($condition['total_amount'])) ? $condition['total_amount'] : null,
                                'free_item_type' => (isset($condition['free_item_type'])) ? $condition['free_item_type'] : 'category ',
                            ];
                            if($mode!='edit') {
                                $this->offerModel->create_offer_condition($offer_cond);
                            }
                            else{
                                $this->offerModel->update_offer_condition($offer_id,$offer_cond);
                            }
                        }
                        catch (\Exception $e){
                            echo $e->getMessage();
                        }
                    }
                }
            }
            catch (\Exception $e){
                echo json_encode(['msg'=>"error",'content'=>$e->getMessage()]);
                die();
            }


            $data = [
                'msg'=>"ok",
                'content'=>"Fügen Sie erfolgreich ein Angebot in Ihr System ein "
            ] ;
            echo json_encode($data);

            die();
        }




        die();
    }



    private function create_product_post($name,$title,$fixed_price_amount,$image) : int{

        $post_id = wp_insert_post([
            'post_title' => $name,
            'post_content' => $title,
            'post_status' => 'publish',
            'post_type' => 'product'
        ]);
        $sku = "OF-".date('ymdhms');
        $price =((float)$fixed_price_amount !=0 )? $fixed_price_amount : 100;
        update_post_meta($post_id, '_sku', $sku );
        update_post_meta( $post_id, '_regular_price', $price);
        update_post_meta( $post_id, '_price', $price );
        update_post_meta( $post_id, '_manage_stock', false );
        update_post_meta( $post_id, '_stock', 1000 );
        update_post_meta( $post_id, '_weight', 1 );
        update_post_meta( $post_id, '_stock_status', 'instock');
        update_post_meta( $post_id, '_visibility', 'visible' );


        if($image!=null){

            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');


                $media = media_sideload_image($image, $post_id);
                if (!empty($media) && !is_wp_error($media)) {
                    $args = array(
                        'post_type' => 'attachment',
                        'posts_per_page' => -1,
                        'post_status' => 'any',
                        'post_parent' => $post_id
                    );
                    // reference new image to set as featured
                    $attachments = get_posts($args);
                    if (isset($attachments) && is_array($attachments)) {
                        foreach ($attachments as $attachment) {
                            // grab source of full size images (so no 300x150 nonsense in path)
                            $image = wp_get_attachment_image_src($attachment->ID, 'full');
                            // determine if in the $media image we created, the string of the URL exists
                            if (strpos($media, $image[0]) !== false) {
                                // if so, we found our image. set it as thumbnail
                                set_post_thumbnail($post_id, $attachment->ID);
                                // only want one image
                                break;
                            }
                        }
                    }
                }
            }

            return $post_id;

        }


        public function delete_offer()   {

            if (isset($_POST['offer'])) {
                $id = $_POST['offer'];

                if ($this->offerModel->delete_offer($id)) {

                    $data = [
                        "msg" => "ok",
                        "content" => "success"
                    ];
                } else {
                    $data = [
                        "msg" => "error",
                        "content" => "Delete Problem"

                    ];
                }
                echo json_encode($data);
                exit;
            } else {
                $data = [
                    "msg" => "error",
                    "content" => "Delete Problem"

                ];
                echo json_encode($data);
            }

        exit;
        }
}

