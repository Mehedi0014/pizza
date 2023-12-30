<?php

/**
 * User: Nazmul
 * Date: 28-Apr-21
 * Time: 3:48 PM
 */
class Offer{
    const  OFFER_TABLE = "cls_offers";
    const  COND_TABLE ='cls_offer_conditions';

    private $offer_table_fields=[
        ['name'=>'offer_id', 'title'=>'Offer ID', 'type'=>'int'],
        ['name'=>'name','title'=>'Offer Name','type'=>'text'],
        ['name'=>'des','title'=>'Offer Description','type'=>'text'],
        ['name'=>'image', 'title'=>'Offer Image','type'=>'text'],
        ['name'=>'image_alt','title'=>'Image Alt','type'=>'text'],
        ['name'=>'offer_type','title'=>'offer Type','type'=>'enum','enum'=>['Fixed','Discount','Free Item']],
        ['name'=>'price_amount','title'=>'Offer Price','type'=>'float'],
        ['name'=>'discount_type','title'=>'Discount Type','type'=>'enum','enum'=>['Fixed','Percentage']],
        ['name'=>'discount_amount','title'=>'Discount Amount','type'=>'float'],
        ['name'=>'condition_rule','title'=>'Rule Condition','type'=>'enum','enum'=>['all_true','any_true']],
        ['name'=>'expire_system','title'=>'Enable Expire System','type'=>'bool'],
        ['name'=>'expire_date','title'=>'Expire Date','type'=>'date'],
        ['name'=>'local_pickup','title'=>'Local Pickup','type'=>'bool'],
        ['name'=>'home_delivery','title'=>'Local Pickup','type'=>'bool'],
        ['name'=>'ref_product_id','title'=>'Ref Product ID','type'=>'int']
    ];


    private $conditions_fields =[
    ['name'=>'id','title'=>'Id','type'=>'int'],
    ['name'=>'offer_id','title'=>'Offer Id','type'=>'int'],
    ['name'=>'condition_type','title'=>'Condition Type','type'=>'enum', 'enum' => ['category_product' ,'custom_product','minimum_price','minimum_product']],
    ['name'=>'category_id','title'=>'Category Id','type'=>'int'],
    ['name'=>'minimum_product_qty','title'=>'Minimum Product Qty','type'=>'int'],
    ['name'=>'custom_product_sku','title'=>'Custom Product Sku','type'=>'varchar'],
    ['name'=>'minimum_product_price','title'=>'Minimum Product Price','type'=>'float'],
    ['name'=>'free_items_qty','title'=>'Free Items Qty','type'=>'int'],
    ['name'=>'free_item_category','title'=>'Free Item Category','type'=>'varchar'],
    ['name'=>'fixed_free_items_sku','title'=>'Fixed Free Items Sku','type'=>'varchar'],
    ['name'=>'total_amount','title'=>'Total Amount','type'=>'float'],
    ['name'=>'free_item_type','title'=>'Free Item Type','type'=>'enum', 'enum'=>['category' ,'custom']],
    ];
    private $offer;
    private $offer_condition;
    private $DB;

    function __construct() {
        global $wpdb;
        $this->DB = $wpdb;
        $this->offer= $wpdb->prefix.self::OFFER_TABLE;
        $this->offer_condition = $wpdb->prefix.self::COND_TABLE;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        add_action('init', array($this,'register_my_session'));

        add_action('wp_ajax_nopriv_setMyOffer',  array( $this,'setMyOffer'));
        add_action('wp_ajax_setMyOffer',  array( $this,'setMyOffer'));


        add_action('wp_ajax_nopriv_setMyOfferAction',  array( $this,'setMyOfferAction'));
        add_action('wp_ajax_setMyOfferAction',  array( $this,'setMyOfferAction'));

    }

    function setMyOfferAction(){
          if($_POST['mode']=='remove_offer_item'){
              $token = $_POST['token'];
              $type = $_POST['type'];
              $offer_id =  $_POST['off_id'];

            if($type=='regular'){
                foreach($_SESSION['$offer'] as $key=>$offerNew){
                    if($offerNew['offer_id']==$offer_id) {
                        foreach($_SESSION['$offer'][$key]['items'] as $index=>$val){
                            if($val['token']==$token){
                                unset($_SESSION['$offer'][$key]['items'][$index]);
                                $_SESSION['$offer'][$key]['free_slot']--;
                                $_SESSION['$offer'][$key]['complete']=false;
                                if($offerNew['type']!='Fixed'){
                                    $_SESSION['$offer'][$key]['total'] =0;
                                    $_SESSION['$offer'][$key]['price_amount'] = $_SESSION['$offer'][$key]['total'];
                                }
                                $data = [
                                    'msg' => "ok",
                                    'content' => "Angebotsartikel erfolgreich entfernen ",
                                    'daten'=>$_SESSION['$offer']
                                ];
                                echo json_encode($data);
                                exit;


                            }
                        }
                    }
                }
            }
              else{

               $offer=$this->load_offer($offer_id,OBJECT);

               if($offer->offer_type!='FreeItem'){
                $this->throw_error("Ungültige Angebotsart ");
               }

                  foreach($_SESSION['$offer'] as $key=>$offerNew){
                      if($offerNew['offer_id']==$offer_id) {
                          foreach($_SESSION['$offer'][$key]['free'] as $index=>$val){
                              if($val['token']==$token){
                                  unset($_SESSION['$offer'][$key]['free'][$index]);
                                  $_SESSION['$offer'][$key]['free_product']--;
                                  $_SESSION['$offer'][$key]['complete']=false;
                                  $data = [
                                      'msg' => "ok",
                                      'content' => "Angebotsartikel erfolgreich entfernen ",
                                      'daten'=>$_SESSION['$offer']
                                  ];
                                  echo json_encode($data);
                                  exit;
                              }
                          }
                      }
                  }

              }
            $this->throw_error("Angebotsartikel können nicht entfernt werden ");
            exit;
        }


        if($_POST['mode']=='add_offer_item'){
            $product_id = $_POST['id'];
            $type = $_POST['type'];
            $condition_id= $_POST['con_id'];
            $offer_id =  $_POST['off_id'];
            $_pf = new WC_Product_Factory();
            $_product = $_pf->get_product($product_id);
            $condition = $this->load_conditions($condition_id,OBJECT);
            if(!empty($condition)){
                $condition = array_shift($condition);
                if($condition->offer_id != $offer_id)$this->throw_error();
                $offer = $this->load_offer($offer_id,OBJECT);
                if($type=='regular') {
                    if ($condition->condition_type == 'category_product') {
                        if (!in_array($condition->category_id, $_product->get_category_ids())) $this->throw_error();
                    }
                    elseif($condition->condition_type == 'custom_product') {
                        $custom_product = explode(',',$condition->custom_product_sku);
                        if (!in_array($_product->get_sku(),$custom_product)) $this->throw_error();
                    }
                    else {
                        $this->throw_error("Wir werden diese Art von Bedingung jetzt nicht unterstützen ");
                     }
                    // finally Assign Regular Product
                       foreach($_SESSION['$offer'] as $key=>$offerNew){
                                    if($offerNew['offer_id']==$offer_id){
                                        if($offerNew['free_slot']!=$offerNew['max']){
                                            $myProduct['token']=time();
                                            $myProduct['id']=$_product->get_id();
                                            $myProduct['name']=$_product->get_name();
                                            $myProduct['des']=$_product->get_description();
                                            $myProduct['S_des']=$_product->get_short_description();
                                            $myProduct['price']=$_product->get_price();
                                            $myProduct['qty']=1;
                                            $myProduct['sku']=$_product->get_sku();
                                            $_SESSION['$offer'][$key]['items'][]=$myProduct;
                                            $_SESSION['$offer'][$key]['free_slot']++;

                                            if(($offerNew['free_slot']+1)==$offerNew['max']) {

                                                if ($offer->offer_type != 'FreeItem') {
                                                    $_SESSION['$offer'][$key]['complete'] = true;
                                                } else {
                                                    if ($offerNew['free_max'] == $offerNew['free_product']) {
                                                        $_SESSION['$offer'][$key]['complete'] = true;
                                                    } else   $_SESSION['$offer'][$key]['complete'] = false;
                                                }
                                            }

                                             if($offerNew['type']=='Discount'){
                                                 $total=0;
                                                 foreach($_SESSION['$offer'][$key]['items'] as $item){
                                                     $total+=(float)$item['price'];
                                                 }
                                                 if($offer->discount_type=='Fixed'){
                                                     $discount = (float)$offer->discount_amount ;
                                                     $_SESSION['$offer'][$key]['total'] = (float) $total - $discount;
                                                     $_SESSION['$offer'][$key]['price_amount'] = $_SESSION['$offer'][$key]['total'];
                                                 }
                                                 else {
                                                     $discount = (float)$offer->discount_amount ;
                                                     $_SESSION['$offer'][$key]['total'] =(float)$total - (((float)$total * (float)$discount)/100);
                                                     $_SESSION['$offer'][$key]['price_amount'] = $_SESSION['$offer'][$key]['total'];
                                                 }
                                             }
                                              if($offerNew['type']=='FreeItem'){
                                                    $total=0;
                                                    foreach($_SESSION['$offer'][$key]['items'] as $item){
                                                        $total+=(float)$item['price'];
                                                    }
                                                    $_SESSION['$offer'][$key]['total'] =$total;
                                                    $_SESSION['$offer'][$key]['price_amount'] = $_SESSION['$offer'][$key]['total'];
                                              }

                                            $data = [
                                                'msg' => "ok",
                                                'content' => "Angebotsartikel erfolgreich hinzufügen ",
                                                'daten'=>$_SESSION['$offer']
                                            ];
                                            echo json_encode($data);
                                            exit;

                                        }
                                    } // id match

                          }// session loop offers
                        $this->throw_error("Angebot nicht gefunden ");

                 }
                 else{

                     $_pf = new WC_Product_Factory();
                     $_product = $_pf->get_product($product_id);
                     $condition = $this->load_conditions($condition_id,OBJECT);

                     if ($condition->free_item_category != null) {
                         if (!in_array($condition->category_id, $_product->get_category_ids())) $this->throw_error();
                     }
                     if($condition->fixed_free_items_sku != null) {
                         $custom_product = explode(',',$condition->fixed_free_items_sku );
                         if (!in_array($_product->get_sku(),$custom_product)) $this->throw_error();
                     }


                     foreach($_SESSION['$offer'] as $key=>$offerNew){
                         if($offerNew['offer_id']==$offer_id){
                             if($offerNew['free_product']!=$offerNew['free_max']){
                                 $myProduct['token']=time();
                                 $myProduct['id']=$_product->get_id();
                                 $myProduct['name']=$_product->get_name();
                                 $myProduct['des']=$_product->get_description();
                                 $myProduct['S_des']=$_product->get_short_description();
                                 $myProduct['price']=$_product->get_price();
                                 $myProduct['qty']=1;
                                 $myProduct['sku']=$_product->get_sku();
                                 $_SESSION['$offer'][$key]['free'][]=$myProduct;
                                 $_SESSION['$offer'][$key]['free_product']++;
                                 if(($offerNew['free_product']+1)==$offerNew['free_max']){
                                     if($offerNew['free_slot']==$offerNew['max']) {
                                         $_SESSION['$offer'][$key]['complete'] = true;
                                     }
                                     else{
                                         $_SESSION['$offer'][$key]['complete'] = false;
                                     }
                                 }
                                 $data = [
                                     'msg' => "ok",
                                     'content' => "Angebotsartikel erfolgreich hinzufügen ",
                                     'daten'=>$_SESSION['$offer']
                                 ];
                                 echo json_encode($data);
                                 exit;
                             }
                         } // id match

                     }// session loop offers
                     $this->throw_error("Angebot nicht gefunden");

                }

            }

            $this->throw_error();



        exit;

        }



        if($_POST['mode']=='cart_delete'){
            $id = $_POST['id'];

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                if ( $cart_item['product_id'] == $id ) {
                    WC()->cart->remove_cart_item( $cart_item_key );
                    $data = [
                        'msg' => "ok",
                        'content' => "Successfully Remove cart items",
                        'cart'=>$this->get_cart_items()
                    ];
                    echo json_encode($data);
                    exit;
                }
            }

            $data = [
                'msg' => "error",
                'content' => "Warenkorbartikel kann nicht entfernt werden ",
            ];
            echo json_encode($data);
            exit;
        }

        if($_POST['mode']=='delete'){
            $token = $_POST['token'];
            foreach($_SESSION['$offer'] as $key=>$offer){
                if($offer['token']==$token){
                    unset($_SESSION['$offer'][$key]);
                    $data = [
                        'msg' => "ok",
                        'content' => "Angebot erfolgreich entfernen ",
                        'daten'=>$_SESSION['$offer']
                    ];
                    echo json_encode($data);
                    exit;
                }
            }
            $data = [
                'msg' => "error",
                'content' => "Kann dieses Angebot nicht finden ",
            ];
            echo json_encode($data);
            exit;
        }

        exit();
    }


    public function throw_error($msg='error'){
        $data = [
            'msg' => "error",
            'content' => $msg,
        ];
        echo json_encode($data);
        exit;

    }


    function setMyOffer(){

        $id =(int)$_POST['id'];
        $current_offer= $this->load_offer($id,OBJECT);
        $k= $this->assign_offer($current_offer);
        if($k) {
            $data = [
                'msg' => "ok",
                'content' => "Angebot erfolgreich hinzufügen"
            ];
            echo json_encode($data);
        }
        else{
            $data = [
                'msg' => "error",
                'content' => "Irgendwas stimmt nicht"
            ];
            echo json_encode($data);
        }
        die();
    }

    function create_table(){
        $sql = "CREATE TABLE $this->offer (
		 `offer_id` int(11) NOT NULL AUTO_INCREMENT,
		 `name` varchar(255) DEFAULT NULL,
		 `des` varchar(255) DEFAULT NULL,
		 `image` text DEFAULT NULL,
		 `image_alt` varchar(255) DEFAULT NULL,
          `offer_type` enum('Fixed','Discount','FreeItem') NOT NULL DEFAULT 'Fixed',
		  `price_amount` float DEFAULT NULL,
		  `discount_type` enum('Fixed','Percentage') NOT NULL DEFAULT 'Fixed',
		  `discount_amount` float DEFAULT NULL,
		  `condition_rule` enum('all_true','any_true') NOT NULL DEFAULT 'all_true',
		  `expire_system` BOOLEAN NULL,
		  `expire_date` date DEFAULT NULL,
		  `local_pickup` BOOLEAN NULL,
		  `home_delivery` BOOLEAN NULL,
		  `create_date` date  NULL,
	      `edit_date` date NULL,
		  `ref_product_id` int(11) DEFAULT NULL,
 		 PRIMARY KEY (`offer_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4	";
        dbDelta( $sql );
        return $this;
    }

   function create_condition_table(){
       $sql = "CREATE TABLE $this->offer_condition (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		 `offer_id` int(11) DEFAULT NULL,
		 `condition_type` enum('category_product' ,'custom_product','minimum_price','minimum_product') NOT NULL DEFAULT 'category_product',
		 `category_id` int(11) DEFAULT NULL,
   		  `minimum_product_qty` int(11) DEFAULT NULL,
		  `custom_product_sku` varchar(255) DEFAULT NULL,
    	   `minimum_product_price` float DEFAULT NULL,
		   `free_items_qty`  int(11) DEFAULT NULL,
		   `free_item_category` varchar(255) DEFAULT NULL,
		   `fixed_free_items_sku` varchar(255) DEFAULT NULL,
		   `total_amount` float DEFAULT NULL,
		   `free_item_type` enum('category' ,'custom') NOT NULL DEFAULT 'category',
 		 PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
	";
       dbDelta( $sql );
       return $this;

   }

   function flush_DB(){
       flush_rewrite_rules();
       $this->DB->flush();
       return $this;
   }

    function delete_all_table(){
        $this->DB->query( "DROP TABLE IF EXISTS $this->offer" );
        $this->DB->query( "DROP TABLE IF EXISTS $this->offer_condition" );
        return $this;
    }

    function create_offer($dat) : int {
        try {
           $this->DB->insert($this->offer,$dat);
        }
        catch (\Exception $e ){
            echo $e->getMessage();
            exit;
        }
        return $this->DB->insert_id;
    }
    function create_offer_condition($dat) : int {
        try {
        $this->DB->insert($this->offer_condition,$dat);
         }
             catch (\Exception $e ){
             echo $e->getMessage();
        exit;
        }
        return $this->DB->insert_id;
    }
    function delete_offer($id){
       try {
           $this->DB->delete($this->offer, array('offer_id' => $id));
           $this->DB->delete($this->offer_condition, array('offer_id' => $id));
           return true;
       }
       catch(\Exception $e){
           return false;
       }

    }

    function update_offer($id,$data){
     return   $this->DB->update( $this->offer,$data,['offer_id'=>$id]);
    }


    function update_offer_condition($offer_id,$offer_cond){
        return $this->DB->update( $this->offer_condition,$offer_cond,['offer_id'=>$offer_id]);
    }

    function get_all_offer($data='*'){
       // OBJECT – result will be output as an object.
       // ARRAY_A – result will be output as an associative array.
       //  ARRAY_N – result will be output as a numerically indexed array.
       return $this->DB->get_results("SELECT $data FROM ".$this->offer, ARRAY_A);
    }

    public function  load_offer($offer_id,$output=ARRAY_A,$json=false){
        $offer_id = (int) $offer_id;
        try{
            if($json && $output==ARRAY_A) {
                $result= json_encode($this->DB->get_results("SELECT * FROM ".$this->offer." WHERE offer_id = ".$offer_id, $output));
                if(!empty($result)) {
                    return $result[0];
                }

            }
           else {
               $result= $this->DB->get_results("SELECT * FROM ".$this->offer." WHERE offer_id = ".$offer_id, $output);
               return $result[0];

           }
        }
        catch(\Exception $e){
            return false;
        }

        return false;
    }


    public function  get_conditions($offer_id,$output=ARRAY_A,$json=false){
        $offer_id = (int) $offer_id;
        try{
            if($json) return json_encode($this->DB->get_results("SELECT * FROM ".$this->offer_condition." WHERE offer_id = ".$offer_id, $output));
            else  return  $this->DB->get_results("SELECT * FROM ".$this->offer_condition." WHERE offer_id = ".$offer_id, $output);
        }
        catch(\Exception $e){
            return false;
        }
    }


    public function  load_conditions($condition_id,$output=ARRAY_A,$json=false){
        $condition_id = (int) $condition_id;
        try{
            if($json) return json_encode($this->DB->get_results("SELECT * FROM ".$this->offer_condition." WHERE id = ".$condition_id, $output));
            else  return  $this->DB->get_results("SELECT * FROM ".$this->offer_condition." WHERE id = ".$condition_id, $output);
        }
        catch(\Exception $e){
            return false;
        }
    }



    public function get_offer_item_qty($id){
        try {
          return  $result = $this->DB->get_row("SELECT SUM(`minimum_product_qty`) as qty , SUM(`free_items_qty`) as free  FROM $this->offer_condition WHERE `offer_id`=$id");
        }
        catch(\Exception $e){
            return false;
        }
    }


    public function get_condition_products($condition,$type){
        $data='';
        switch($type){
            case 'Fixed' :$data= $this->get_fixed_product($condition) ;break;
            case 'Discount' :$data= $this->get_discount_product($condition) ;break;
            case 'FreeItem' :$data= $this->get_free_item_product($condition) ; break;
            default : return false;

        }
        return $data;
    }


    private function get_fixed_product($condition){
        $condition_type = $condition->condition_type;
        if($condition_type == 'category_product'){
            return $this->load_category_products($condition);
        }
        elseif($condition_type=='custom_product'){
            return $this->load_custom_products($condition);
        }
        else{
            exit("Dieses System wird nicht unterstützt. Wir werden in Zukunft arbeiten ");
        }
    }


    private function get_discount_product($condition){
        $condition_type = $condition->condition_type;
        if($condition_type == 'category_product'){
            return $this->load_category_products($condition);
        }
        elseif($condition_type=='custom_product'){
            return $this->load_custom_products($condition);
        }
        else{
            exit("Dieses System wird nicht unterstützt. Wir werden in Zukunft arbeiten ");
        }

    }


    private function load_custom_products($condition){
        $skus= explode(',',$condition->custom_product_sku);
        $products=[
            'condition_id'=>$condition->id,
            'condition_type'=>$condition->condition_type,
            'category_name'=>'Ihre eigene Wahl',
            'category_id'=>null,
            'minimum'=>$condition->minimum_product_qty,
            'Free'=>[],
            'free_qty'=>0
        ];
        $items=[];
        $_pf = new WC_Product_Factory();
        $i=0;
        foreach ( $skus as $sku ) {
            $id = wc_get_product_id_by_sku($sku);
            $_product = $_pf->get_product($id);
            $items[$i]['id']= $_product->get_id();
            $items[$i]['name']= $_product->get_name();
            $items[$i]['slug']= $_product->get_slug();
            $items[$i]['des']=$_product->get_description();
            $items[$i]['des']=$_product->get_description();
            $items[$i]['short_des']=$_product->get_short_description();
            $items[$i]['sku']=$_product->get_sku();
            $items[$i]['price']=$_product->get_price();
            $items[$i]['sale_price']=$_product->get_sale_price();
            $items[$i]['regular_price']=$_product->get_regular_price();
            $items[$i]['qty']=$_product->get_stock_quantity();
            $items[$i]['image']=$_product->get_image();
            $items[$i]['rating']=$_product->get_average_rating();
            $i++;
        }
        $products['items']=$items;
        return $products;
    }

    private function load_category_products($condition){
        if( $category = get_term_by( 'id', $condition->category_id, 'product_cat' ) ){
            $category_name= $category->name;
        }
        else{
            $category_name='nicht gefunden';
        }
        $all_ids = get_posts( array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'fields' => ['ids'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => "$category_name",
                    'operator' => 'IN',
                )
            ),
        ) );
        $products=[
            'condition_id'=>$condition->id,
            'condition_type'=>$condition->condition_type,
            'category_name'=>$category_name,
            'category_id'=>$condition->category_id,
            'minimum'=>$condition->minimum_product_qty,
            'Free'=>[],
            'free_qty'=>0
        ];
        $items=[];
        $_pf = new WC_Product_Factory();
        $i=0;
        foreach ( $all_ids as $id ) {
            $_product = $_pf->get_product($id);
            $items[$i]['id']= $_product->get_id();
            $items[$i]['name']= $_product->get_name();
            $items[$i]['slug']= $_product->get_slug();
            $items[$i]['des']=$_product->get_description();
            $items[$i]['des']=$_product->get_description();
            $items[$i]['short_des']=$_product->get_short_description();
            $items[$i]['sku']=$_product->get_sku();
            $items[$i]['price']=$_product->get_price();
            $items[$i]['sale_price']=$_product->get_sale_price();
            $items[$i]['regular_price']=$_product->get_regular_price();
            $items[$i]['qty']=$_product->get_stock_quantity();
            $items[$i]['image']=$_product->get_image();
            $items[$i]['rating']=$_product->get_average_rating();
            $i++;
        }
        $products['items']=$items;
        return $products;
    }

    private function get_free_item_product($condition){
        $root=[];
        $condition_type = $condition->condition_type;
        if($condition_type == 'category_product'){
            $root= $this->load_category_products($condition);
        }
        elseif($condition_type=='custom_product'){
            $root= $this->load_custom_products($condition);
        }
        else{
            exit("Dieses System wird nicht unterstützt. Wir werden in Zukunft arbeiten ");
        }

        $free_item_type = $condition->free_item_type;

        if($free_item_type=='category'){
            $free_item_category= $condition->free_item_category;
            if( $category = get_term_by( 'id', $free_item_category, 'product_cat' ) ){
                $category_name = $category->name;
            }
            else{
                $category_name='nicht gefunden ';
            }
            $all_ids = get_posts( array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'numberposts' => -1,
                'fields' => ['ids'],
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => "$category_name",
                        'operator' => 'IN',
                    )
                ),
            ) );
            $items=[];
            $_pf = new WC_Product_Factory();
            $i=0;
            foreach ( $all_ids as $id ) {
                $_product = $_pf->get_product($id);
                $items[$i]['id']= $_product->get_id();
                $items[$i]['name']= $_product->get_name();
                $items[$i]['slug']= $_product->get_slug();
                $items[$i]['des']=$_product->get_description();
                $items[$i]['des']=$_product->get_description();
                $items[$i]['short_des']=$_product->get_short_description();
                $items[$i]['sku']=$_product->get_sku();
                $items[$i]['price']=$_product->get_price();
                $items[$i]['sale_price']=$_product->get_sale_price();
                $items[$i]['regular_price']=$_product->get_regular_price();
                $items[$i]['qty']=$_product->get_stock_quantity();
                $items[$i]['image']=$_product->get_image();
                $items[$i]['rating']=$_product->get_average_rating();
                $i++;
            }
            $root['Free']=$items;
            $root['free_cat']=$category_name;
        }
        else{
            $skus=expolde(",", $condition->fixed_free_items_sku);
            $items=[];
            $_pf = new WC_Product_Factory();
            $i=0;
            foreach ( $skus as $sku ) {
                $id = wc_get_product_id_by_sku($sku);
                $_product = $_pf->get_product($id);
                $items[$i]['id']= $_product->get_id();
                $items[$i]['name']= $_product->get_name();
                $items[$i]['slug']= $_product->get_slug();
                $items[$i]['des']=$_product->get_description();
                $items[$i]['des']=$_product->get_description();
                $items[$i]['short_des']=$_product->get_short_description();
                $items[$i]['sku']=$_product->get_sku();
                $items[$i]['price']=$_product->get_price();
                $items[$i]['sale_price']=$_product->get_sale_price();
                $items[$i]['regular_price']=$_product->get_regular_price();
                $items[$i]['qty']=$_product->get_stock_quantity();
                $items[$i]['image']=$_product->get_image();
                $items[$i]['rating']=$_product->get_average_rating();
                $i++;
            }
            $root['Free']=$items;
            $root['free_cat']='Deine Entscheidung';
        }

        $root['free_qty']=$condition->free_items_qty;

        return $root;
    }




    public function assign_offer($current_offer){

       try {
           if (!isset($_SESSION['$offer'])) {
               $_SESSION['$offer'] = [];
           }

           $Q = $this->get_offer_item_qty($current_offer->offer_id);

           $data = [
               'offer_id' => $current_offer->offer_id,
               'name' => $current_offer->name,
               'des' => $current_offer->des,
               'type' => $current_offer->offer_type,
               'price_amount' => ($current_offer->offer_type == 'Fixed') ? (float)$current_offer->price_amount : 0,
               'items' => [],
               'free' => [],
               'max'=>(int)$Q->qty,
               'free_max'=>(int)$Q->free,
               'free_slot'=>0,
               'free_product'=>0,
               'complete'=>false,
               'token' => time()
           ];
           array_push($_SESSION['$offer'], $data);
           // wp_send_json_success( __( 'Thanks for reporting!', 'reportabug' ) );
           return true;
       }
       catch(\Exception $e){
           return false;
       }
    }

    public function get_assign_offer($type=false){

        if($type){
            $data = [
                'offers' => $_SESSION['$offer']
            ];
            return $data;
        }
        else {
            return $_SESSION['$offer'];
        }

    }

    function register_my_session(){
        if( ! session_id() ) {
            session_start();
        }
    }

    function get_cart_items(){
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        $arr=[];
        $i=0;
        $total=0;
        foreach ($items as $item){
            $_product =  wc_get_product( $item['product_id']);
            $arr[$i]['name']=$item['product']['name'];
            $arr[$i]['price']=$item['product']['price'];
            $arr[$i]['qty']=$item['quantity'];
            $arr[$i]['id']= $item['product_id'];
            $arr[$i]['des']= $_product->get_description();
            $arr[$i]['s_des']= $_product->get_short_description();
            $arr[$i]['subtotal']= $item['line_subtotal'];
            $total+=(float)$item['line_subtotal'];
            $i++;
        }
        return ['total'=>$total,'data'=>$arr];
    }

}