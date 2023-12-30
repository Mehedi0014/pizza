<?php
/*
 * Plugin Name: CLS POS Print
 * Version: 1.1
 * Plugin URI: https://cls-computer.de
 * Description: CLS POS Print Plugins fo Restaurant Theme.
 * Author URI: https://nazmul-alam.com
 * Author: Mohammad Nazmul Alam
 * Requires at least: 1.0
 * Tested up to: 7.*
 * Requires PHP: 7.*
 * Text Domain: woo-custom-product-addons
 * WC requires at least: 3.3.0
 * WC tested up to: 4.9.0
 */

require __DIR__ . '/vendor/autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

define('CLS_PRINTER_DIR', plugin_dir_path( __FILE__ ) );

if (!defined('ABSPATH')) exit;
add_action( 'woocommerce_admin_order_actions_end', 'pos_print_button',30,1 );
function pos_print_button($order) {
    $order_id = trim(str_replace('#', '', $order->get_order_number()));
    ?><a href="#" class="button tips nazmul-pos" data-id="<?=$order_id?>" target="_blank" alt="POS-Drucker drucken" data-tip="POS Printer Print">
    <img src="<?=plugins_url()."/cls-pos-print/images/pos.png" ?>" width="30px" height="30px" alt="POS-Drucker drucken "/>
    </a>
    <script>
    var global_pos_url="<?=admin_url('admin-ajax.php?action=pos_print')?>";
    </script>
    <?php
    wp_enqueue_script( 'jquery_pos_script', plugin_dir_url( __FILE__ ) . 'js/jquery-3.5.1.min.js', array(), '1.0' );
    wp_enqueue_script( 'create_pos_script', plugin_dir_url( __FILE__ ) . 'js/pos.js', array(), '1.0' );
    wp_enqueue_style( 'cls_pos_print', plugins_url( 'css/style.css', __FILE__ ) );
}
function pos_print(){
    if(isset($_POST)){
        $order_id= $_POST['order'];
/*         echo $order_id;
        $order = wc_get_order( $order_id );*/
        try{
           if( print_order_from_POS($order_id)){

               $data= [
                   'msg'=>'ok',
                   'content'=>'Erfolgreich am POS drucken '
               ];

           }
            else{
                $data= [
                    'msg'=>'error',
                    'content'=>'Irgendwas stimmt nicht'
                ];
            }
            echo json_encode($data);
            exit;

        }
        catch(\Exception $e){

                $data= [
                    'msg'=>'error',
                    'content'=>'Irgendwas stimmt nicht'
                ];

            echo json_encode($data);
            exit;
        }

        exit;
    }

 die();
}
add_action("wp_ajax_pos_print", "pos_print");

function call_order_status_completed( $order_id ) {
        try{
            $enable_Oder_print = get_option('Enable Print When Create Order');
            if($enable_Oder_print == '1' ){
              try {
                  print_order_from_POS($order_id);
                  return true;
              }
              catch(\Exception $e){

                    return false;
              }
            }
            else return false;

        }
        catch(\Exception $e){
            return false;
        }

    return false;
};
add_action( 'woocommerce_checkout_order_processed', 'call_order_status_completed', 10, 1);
add_action( 'woocommerce_resume_order', 'call_order_status_completed', 10, 1);


function wp_get_attribute($Order_item_id){
    global $wpdb;
    $order_meta= $wpdb->prefix.'woocommerce_order_itemmeta';
    $data=  $wpdb->get_results("SELECT * FROM ".$order_meta." WHERE order_item_id = ".$Order_item_id);
    $array = [
        '_product_id',
        '_variation_id',
        '_qty',
        '_tax_class',
        '_line_subtotal',
        '_line_subtotal_tax',
        '_line_total',
        '_line_tax',
        '_line_tax_data',
        'pa_size',
        'pa_red_pizza_size',
        'pa_red_size',
        '_reduced_stock'
    ];

    if(!empty($data)){
        if(count($data)>0){
            $k=[];
            foreach ($data as $dat){
               if(!in_array($dat->meta_key,$array)){
                    $k[]=[
                        'name'=>$dat->meta_key,
                        'val'=>$dat->meta_value
                    ];
                }
            }
            return $k;
        }
    }
    return false;
}


function print_order_from_POS($order_id){

     $order = wc_get_order( $order_id );

     $customer_name =$order->get_formatted_billing_full_name();
     $customer_phone = $order->get_billing_phone();
     $customer_email = $order->get_billing_email();


    $items =$order->get_items();
    $paid = $order->get_transaction_id();
    if($paid!=null){
        $paid =number_format ( $order->get_total(), 2, ',', '.');
        $due="00,00";
    }
    else{
        $paid="00,00";
        $due=number_format ($order->get_total(), 2, ',', '.');
    }
    $total_tax=  number_format ($order->get_total_tax(), 2, ',', '.');
    $total =number_format ($order->get_total(), 2, ',', '.');
    $date =$order->get_date_created();
	
	
	    $shipping_method  = $order->get_shipping_method();

 
   $line[]= $order->get_shipping_address_1();
   $line[]= $order->get_shipping_postcode()." ".WC()->countries->get_states( $order->get_shipping_country() )[$order->get_shipping_state()] ;

	
	
    $products=[];

    $or_total= 0;
    foreach ( $order->get_items() as $item_id => $item ) {

        $products[]= [ "name"=>$item->get_name(),
                     "extra"=> wp_get_attribute($item->get_id()),
                    "price"=> number_format (((float)$item->get_subtotal()+ (float)$item->get_subtotal_tax()), 2, ',', '.'),
                    "qty"=>   $item->get_quantity()
                    ];
       	$or_total+=(float)$item->get_subtotal()+(float)$item->get_subtotal_tax();
    }

  
    $discount =number_format ($or_total-(float)$order->get_total(), 2, ',', '.');




      /* test  from windows Local
        $ip=$_SERVER['REMOTE_ADDR'];
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $connector = new WindowsPrintConnector("smb://$hostname/XP-80");
        */


        $ip1 = get_option('POS Printer IP1');
        $port1 = get_option('POS Printer PORT1');
        $ip2 = get_option('POS Printer IP2');
        $port2 = get_option('POS Printer PORT2');


	
    try {

        if ($ip1 != "" && $port1 != '') {
            $connector = new NetworkPrintConnector($ip1, $port1, 7);
            $printer = new Printer($connector);
           print_pos_final($shipping_method,$line,$order_id,$printer, $products, $total_tax, $total, $date, $paid, $due, $customer_name, $customer_email, $customer_phone,$discount);
        }


        return true;
    }
    catch(\Exception $e){
        return false;
    }
	 try {

        if (trim($ip2)!= "" && $port2 != '') {
            $connector = new NetworkPrintConnector($ip2, $port2, 7);
            $printer = new Printer($connector);
             print_pos_final($shipping_method,$line,$order_id,$printer, $products, $total_tax, $total, $date, $paid, $due, $customer_name, $customer_email, $customer_phone,$discount);
        }

        return true;
    }
    catch(\Exception $e){
        return false;
    }

	

}


function print_pos_final($shipping_method,$line,$order_id,$printer,$products, $total_tax,$total,$date,$paid,$due,$customer_name,$customer_email,$customer_phone,$discount){

    try{
    setlocale(LC_TIME, 'de_DE');
    $line_width = 47;
    $line1=$line2=$line3=$line4="";
    for($i=1;$i<=$line_width;$i++){ $line1.="-";$line2.="=";$line3.="*";$line4.="#";}
    $address_line1 = "Straße: Römerstr.160, Stadt: Lampertheim";
    $address_line2 = "PLZ: 68623, Email: colosseo@pizzeriacolosseo.de";
    $address_line3 = "Hotline: +49062069510554";



    $img = EscposImage::load(CLS_PRINTER_DIR. "/images/pos_logo.png",false);

    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer ->bitImage($img);   // print PNG image
    $printer -> feed();   // new Line Start
    $printer -> setTextSize(2, 2);   // 1 to  8 max
    $printer -> text("COLOSSEO PIZZERIA \n");
    $printer -> feed();
    $printer -> setTextSize(1, 1);
    $printer -> text("$address_line1\n");
    $printer -> text("$address_line2\n");
    $printer -> text("$address_line3\n");
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> text("$line1\n");
    $printer->setEmphasis(true);  // Set Text Bolder
    $printer -> text("Bestellnummer: #$order_id\n");
    $printer->setEmphasis(false);  // Remove Bolder
	$printer -> text("$date\n");	
    $printer -> text("Versandart: $shipping_method\n");
    $printer -> text("\n");


    $printer -> text("Kundenname: $customer_name\n");


        if($line){

            foreach($line as $lin){

                $printer -> text($lin. "\n");
            }

        }

    $printer -> text("Telefon: $customer_phone\n");
    $printer -> text("Email: $customer_email\n");		
		

    $printer -> text("$line2\n");


    $printer -> text( Draw_Headline($line_width)."\n");

    $printer->text("$line1\n");
    $i=1;

    foreach ($products  as $product){
        $printer -> text(Draw_Item($i,$product['name'],$product['price'],$product['qty'])."\n");

        if($product['extra'] && is_array($product['extra'])){

            foreach($product['extra'] as $extra){
                if(strlen($extra['name'])>32) {
                    $extra['name'] = substr($extra['name'], 0,32) . "..";
                }

                $printer -> text($extra['name']."\n");
            }
            //   $printer -> text("$line1\n");
        }
        $printer->feed();
        $i++;
    }

    $printer->text("$line1\n");

    $printer -> setJustification(Printer::JUSTIFY_RIGHT);

    $printer -> text(str_pad(" Zwischensumme(brutto):",33," ",STR_PAD_RIGHT)."$total "."\n");
    $printer -> text(str_pad("MwSt.:",33," ",STR_PAD_RIGHT)."$total_tax "."\n");
        $printer -> text(str_pad("Rabatt.:",31," ",STR_PAD_RIGHT)."- $discount "."\n");
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("$line1\n");

    $printer -> setJustification(Printer::JUSTIFY_RIGHT);
    $printer -> text(str_pad("Bezahlte Menge:",32," ",STR_PAD_RIGHT)."$paid "."\n");
    $printer -> text(str_pad("Fälliger Betrag:",33," ",STR_PAD_RIGHT)."$due "."\n");
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("$line2\n");

    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Vielen Dank für Ihre Bestellung bei uns \n");
    $printer->feed();
    $printer->feed();

    $printer -> cut();
    $printer -> close();
}
    catch(\Exception $e) {
        echo $e->getMessage();
    }

    return true;


}







function Draw_Headline($width){
    $name_pad = $width - 17;
    $str=str_pad("No.",1," ",STR_PAD_RIGHT);
    $str.=str_pad("  Artikel",$name_pad," ",STR_PAD_RIGHT);
    $str.=str_pad("Menge ",0," ",STR_PAD_RIGHT);
    $str.=str_pad("   Summe",3," ",STR_PAD_LEFT);
    return $str;
}

function Draw_Item($no,$name,$price,$qty=1,$extra='',$sign='',$width=47){
  //  $price = (float)$price*(int)$qty;
    if(strlen($name)>22) {
        $name = substr($name, 0,22) . "..";
    }
    $name_pad = $width - 20;
    $str=str_pad($no.".",1," ",STR_PAD_RIGHT);
    $str.=str_pad("   ".$name,$name_pad," ",STR_PAD_RIGHT);
    $str.=str_pad("   ".$qty,5," ",STR_PAD_LEFT);
    $str.=str_pad("   ".$price,13," ",STR_PAD_LEFT);
    return $str;
}


function CLSPosPrinterSetting(){
    add_menu_page(
        'CLS POS-Drucker', // page Title
        'CLS POS-Drucker', // menu title
        'manage_options', // user access capability
        'cls-pos-printer-setting', // page menu slug
        'cls_pos_printer_setting', // callback function
        'dashicons-align-wide', // icon image url
        null // position, where the menu display
    );
}
add_action('admin_menu', 'CLSPosPrinterSetting');
function cls_pos_printer_setting (){
    require_once CLS_PRINTER_DIR . 'Adaptor/setting.php';
}

