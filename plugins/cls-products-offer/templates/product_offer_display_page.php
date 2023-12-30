<?php
get_header();
require_once( CLS_OFFER_DIR . 'Model/Offer.php');
$my_offer = new Offer();


//var_dump($my_offer->get_assign_offer(true));
//exit;*/

$template = true;
if(isset($_GET['offer_type'])){
    $template = false;
    $offer_id=$_GET['offer_type'];
    $current_offer =  $my_offer->load_offer($offer_id,OBJECT);
    $offer_type = $current_offer->offer_type;
    $conditions = $my_offer->get_conditions($offer_id,OBJECT);
    $condition_products=[];
    $qty=0;
    $free_qty=0;
    foreach ($conditions as $condition){
        $condition_products[]=$my_offer->get_condition_products($condition,$offer_type);
        $qty+= (int)$condition->minimum_product_qty;
        $free_qty+= (int)$condition->free_items_qty ;
    }
   // $my_offer->assign_offer($current_offer);
}
else{
    $data = "offer_id,name,des,image,image_alt,expire_system,expire_date,local_pickup,home_delivery";
    $offerlists = $my_offer->get_all_offer($data);

}
?>
<script>
<?php if(isset($_GET['offer_type'])): ?>
     var offers = <?php echo json_encode($my_offer->get_assign_offer(true)) ?>;
     var current_offer =<?php echo json_encode($current_offer) ?>;
     var conditions = <?php echo json_encode($condition_products) ?>;
     var global_post_url="<?=admin_url('admin-ajax.php?action=assign_offer')?>";
     var max_qty =<?=$qty?>;
     var max_free =<?=$free_qty?>;
     var base_url="<?=get_option( 'siteurl' )?>";
     var cart =<?php echo json_encode($my_offer->get_cart_items()) ?>;
<?php else:?>
    var offers = null;
    var current_offer =null;
    var conditions = null;
    var max_qty =0;
    var max_free =0;
    var global_post_url="<?=admin_url('admin-ajax.php')?>";
    var base_url="<?=get_option( 'siteurl' )?>";
    var cart =[];

<?php endif ?>

</script>

    <div class="page_loader" id="page_loader">
        <div class="loader"></div>
    </div>

<section  class="page_wrapper">
    <div class="container">

<?php if ($template):?>

  <?php  if(count($offerlists) > 0):?>

    <main id="productOfferDisplay" class="mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center text-uppercase">
                    <h1 class="pizza_headline">Angebote in <b>COLOSSEO PIZZERIA </b></h1>
                </div>
            </div>
            <div class="row pt-5 mt-3 bg-secondary alpha">
                <?php
                foreach ($offerlists as $offerlist): ?>
                    <a href="?offer_type=<?=$offerlist['offer_id']?>" data-id="<?=$offerlist['offer_id']?>" class="col-sm-6 col-md-3 specialBox offerBoxWrapper text-center px-2  special-offer block">
                        <div class="offer-service-method">
                            <?php if($offerlist['home_delivery']==1):?> Lieferung<?php endif ?>
                            <br>
                            <?php if($offerlist['local_pickup']==1):?>   oder Abholung<?php endif ?>
                        </div>
                        <div class="special-offer-name offer-title" style="display: table;">
                            <?php echo $offerlist['name'] ?>
                        </div>
                        <div class="special-offer-des" >
                            <?php echo $offerlist['des'] ?>
                        </div>

                        <button class="red-button">Jetzt bestellen</button>
                    </a>

                    <?php endforeach ?>

            </div>
        </div>
    </main>
    <?php else:  // offer not found ?>

        <main id="productOfferDisplay" class="mt-5 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-uppercase">
                        <h1 class="pizza_headline">Kein Angebot verfügbar </h1>
                    </div>
                </div>
            </div>
        </main>
    <?php endif ?>

    <?php else :?>


    <main id="OfferApp"   class="mt-5 mb-5">
        <div  class="container">
            <div class="row">
                <div class="col-12 text-center text-uppercase">
                    <h1 class="pizza_headline">{{current_offer.name}}</h1>
                    <p> {{current_offer.des}}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9" >

                    <div class="mb-5 m-0 " v-for="condition in conditions" :key="conditions">
                        <div class="offer-step-box">
                            <h2>  <span style="color:royalblue">Wählen Sie Produkte </span> aus der  {{ condition.category_name}}  </h2>

                        <div class="row alphax">
                            <div class=" col-md-3 col-sm-6 bobo" v-for="item in condition.items" :key="condition.items">
                                <div class="product-box">
                                    <div class="image-box" v-html="item.image"></div>
                                    <strong class="p-name">{{ item.name}}</strong>
                                    <p class="p-des">{{item.des}}</p>
                                    <p class="p-price">{{convert_currency(item.price)}}</p>
                                    <button aria-hidden="true" class="btn btn-primary add-to-order" @click="add_offer_item(item.id,'regular',condition.condition_id)"> Hinzufügen </button>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="Free_items offer-step-box" v-if="condition.Free.length  > 0">
                            <h2> <span style="color:royalblue">Ihre Gratisartikel  </span> aus der {{condition.free_cat}}  : ( {{ condition.free_qty}} )</h2>
                            <div class="row alphax"  >
                                <div class=" col-md-3 col-sm-6 bobo" v-for="free in condition.Free" :key="condition.Free">
                                    <div class="product-box">
                                        <div class="image-box" v-html="free.image"></div>
                                        <strong class="p-name">{{ free.name}}</strong>
                                        <p class="p-des">{{free.des}}</p>
                                        <button aria-hidden="true" class="btn btn-primary add-to-order" @click="add_offer_item(free.id,'free',condition.condition_id)"> Hinzufügen </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
                <div class="col-sm-3">
                    <div class="offerCartWrapper">
                        <h3 class="text-left py-2 special-h3">Bestelldetails</h3>
                            <div v-for="offer in offers"  v-bind:class="offer.offer_id==current_offer.offer_id?'selected':''">
                                <div  class="offerDicWrapper">
                                    <div class="offerDic bgColorOne">
                                        <p class="m-0 offer-name" ><strong >{{offer.name}} : </strong> {{offer.des}} </p>
                                        <div class="row">
                                            <div class="col-6 removeOfferDic">
                                                <a class="offerButtonRemove main" href="#"  @click="remove_offer(offer.token,$event)">Entfernen </a>
                                            </div>
                                            <div class="col-6 " style="text-align: right">
                                                <span v-if="offer.price_amount==0">Nicht berechnen </span>
                                                <span v-else>  {{convert_currency(offer.price_amount)}}</span>
                                            </div>
                                        </div>
                                        <div class="product-items-w" v-for="pro in offer.items">
                                            <div class="product-s-name">
                                                1 x <strong>{{pro.name}}</strong>
                                            <br/> <span>{{pro.des}}</span>
                                            </div>
                                            <div class="row ">
                                            <div class="col-6 removeOfferDic">
                                                <a class="offerButtonRemove" @click="remove_offer_item(pro.token,'regular',offer.offer_id,$event)" href="#">Entfernen</a>
                                            </div>
                                            <div class="col-6" style="text-align: right">
                                                <span class="price">  {{convert_currency(pro.price)}}</span>
                                             </div>
                                            </div>
                                        </div>

                                        <div class="product-items-w" v-for="pro in offer.free">
                                            <div class="product-s-name">
                                                1 x <strong>{{pro.name}}</strong>
                                                <br/> <span>{{pro.des}}</span>
                                            </div>
                                            <div class="row ">
                                                <div class="col-6 removeOfferDic">
                                                    <a class="offerButtonRemove" @click="remove_offer_item(pro.token,'free',offer.offer_id,$event)" href="#">Entfernen</a>
                                                </div>
                                                <div class="col-6" style="text-align: right">
                                                    <span class="price free">  {{convert_currency(pro.price)}}</span>
                                                </div>
                                            </div>
                                        </div>



                                        <button  @click="go_item_link(offer.offer_id)" aria-hidden="true" v-if="offer.complete!=true" class="btn btn-primary add-to-offer-pro"> Füge Artikel hinzu
                                            ({{(offer.max+offer.free_max) - offer.free_slot}})
                                        </button>
                                        <span v-else class="complete-status">Komplett</span>
                                    </div>
                                </div>
                             </div>
                        <div class="product-items-w" v-for="cat in cart.data">
                            <div class="product-s-name">
                                {{cat.qty}} x <strong>{{cat.name}}</strong>
                                <br/><span>{{ cat.s_des}}</span>
                            </div>
                            <div class="row ">
                                <div class="col-6 removeOfferDic">
                                    <a class="offerButtonRemove" @click="remove_cart_item(cat.id,$event)" href="#">Remove</a>
                                </div>
                                <div class="col-6" style="text-align: right">
                                  <span class="price">  {{convert_currency(cat.subtotal)}}</span>
                                </div>
                            </div>
                        </div>
                            <div class="row barka">
                                <sapn class="col-6 summe_f">Summe</sapn>
                                <sapn class="col-6 amount_f">{{convert_currency(total)}}</sapn>
                            </div>

                        <button @click="go_item_checkout()" id="basket-next" v-if="offers.length > 0 " class="btn next medium btn-lg right">Weiter</button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php endif ?>

        </div>



    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" role="alert" id="msgType">
                    <span id="msgContent"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
    </div>


</section>

    <style>


        #msgModal .alert{
            margin: 0;
        }
        #msgModal .modal-dialog {
            margin-top: 50px;
        }
        #attCondition .modal-dialog{
            margin-top: 45px;
        }


        .specialBox{
            padding-right: 5px;
            padding-left: 5px;
            cursor: pointer;
        }
        .red-button {
            background-color: #e21836;
            border-color: #e21836;
            display: inline-block;
            min-width: 0;
            max-width: 100%;
            padding: 0px 40px 0px 15px;
            margin: 2px;
            text-transform: uppercase;
            text-align: left;
            letter-spacing: 1px;
            font-size: 12px;
            border-radius: 3px;
            padding-right: 0;
            cursor: pointer;
            color: #fff;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            position: relative;
            width: 170px;
        }
        .red-button:hover{
            text-decoration: underline;
        }
        .btn.next::after, .blue-button::after, .red-button::after {
            content: "";
            width: 0;
            height: 0;
            position: absolute;
            right: 6px;
            top: 50%;
            margin-top: -6px;
            border: 6px solid transparent;
            border-left: 6px solid #454545;
            border-left: 6px solid #fff;
            -webkit-box-sizing: inherit;
            -moz-box-sizing: inherit;
            box-sizing: inherit;
        }
        .bg-secondary.alpha {
            padding: 10px;
            padding-top: 10px !important;
            background: #ffd800 !important;
            border-radius: 5px;
        }
        .pizza_headline{
            font-size: 20px;
        }
        .pizza_headline b {
            font-size: 21px;
            background: #333;
            color: white;
            padding: 5px 10px;
            margin-left: 7px;
            border-radius: 10px;
        }
        .special-offer {
            padding: 0 10px;
            box-sizing: border-box;
            text-align: center;
            position: relative;
            -webkit-flex: 1 0 200px;
            min-width: 240px;
            margin: .5%;
            background-color: #fff;
            height: 330px;
            margin-bottom: 10px;
            border-radius: 3px;
            -webkit-box-shadow: -1px 6px 11px -4px rgba(0,0,0,.36);
            -moz-box-shadow: -1px 6px 11px -4px rgba(0,0,0,.36);
            box-shadow: -1px 6px 11px -4px rgba(0,0,0,.36);
            display: block;
        }

        .special-offer .offer-service-method {
            text-transform: uppercase;
            font-size: 16px;
            padding: 15px 0;
            color: #007aaf;
            height: 20px;
            min-height: 75px;
            line-height: 20px;
        }
        .special-offer .offer-title {
            height: 93px;
            padding: 10px;
            box-sizing: border-box;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            width: 100%;
        }
        .special-offer-des {
            height: 93px;
            padding: 10px;
            box-sizing: border-box;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 17px;
            font-weight: bold;
            color: #6a6b6c;
            display: block;
        }
        .offerButtonRemove {
            background: black;
            color: white;
            padding: 4px 6px;
            font-size: 12px;
        }

        .offerButtonRemove.main{
            background: #1d0800;
            font-size: 14px;
            font-weight: bold;
        }

        .offerCartWrapper {
            background: #fff;
            padding: 10px 10px 0 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .special-h3{
            text-transform: uppercase;
            font-weight: bold;
            font-size: 21px;
        }
        .offerDicWrapper {
            margin: 10px 0;
            padding: 10px 10px 5px 10px;
            background: #dedede;
            position: relative;
        }
        .offer-name strong{
            color: #006991;
        }
        .offer-name {
            font-weight: 400;
            line-height: 22px;
        }
        .product-box{
            padding:10px;
            background: white;
            margin: 6px 0;
            transition: transform .3s ease,box-shadow .3s ease;
        }
        .product-box:hover{
            box-shadow: 0 15px 35px -5px rgba(0,0,0,.25);
        }
        .image-box{
            height: 190px;
            text-align: center;
        }

        .image-box img{
            object-fit: contain;
            max-width: 190px;
            margin: 0 auto;
            max-height:190px;
        }

        .offer-step-box{
            background: #EEE;
            padding:10px 5px;
        }


        .offer-step-box h2 {
            font-size: 20px;
            padding-left: 15px;
            margin: 12px 0;
        }

        .alphax{
            margin-right: 0;
            margin-left: 0;
        }
   .alphax .bobo{
       padding-right: 5px;
       padding-left: 5px;
   }

   .p-name {
            height: 40px;
            display: block;
            overflow: hidden;
            line-height: 20px;
        }

        .p-des {
            font-size: 12px;
            color: #4f4e4e;
            line-height: 16px;
            margin: 10px 0;
            height: 80px;
            overflow: hidden;
        }

        .btn.btn-primary.add-to-order {
            width: 100%;
            margin-bottom: 10px;
        }

        .Free_items.offer-step-box {
            margin-top: 25px;
            background: #ffffac;
        }

        .product-items-w {
            margin: 10px 0;
            border-top: 1px solid #CCC;
            padding-bottom: 3px;
            padding-top: 15px;
        }

        .product-s-name {
            color: #2d2dd7;
        }

        .product-s-name span{
            color: #4f4e4e;
            font-size: 12px;
            line-height: 18px;
            display: block;
            margin-bottom: 10px;
        }

        .add-to-offer-pro{
            background: #862d2f;
            border: none;
            margin: 10px 0;
            width: 100%;
        }

        .add-to-offer-pro:hover{
            background: #5e1f20;

        }

        .selected .offerDicWrapper {
                background: #bdbdbd;
            }
        .price{
            color: #5e1f20;
            font-weight: bold;
        }

        .row.barka {
            background: #CCC;
            margin-left: 0px;
            margin-right: 0px;
            margin-bottom: 20px;
            padding: 10px 0;
            text-transform: uppercase;
            font-weight: bold;
            text-align: left;
            font-size: 17px;
        }


        .col-6.amount_f {
            color: #9f2f05;
            text-align: right;
            margin-right: -14px;
            padding-right: 5px;
        }


        #basket-next{
            color: #fff;
            background-color: #e41837;
            border-color: #e41837;
            padding-right: 18px;
            position: relative;
            padding-right: 29px;
            margin: 10px;
            margin-top: 0px;
            margin-left: 2px;;
        }

        .p-price {
            text-align: right;
            padding-right: 10px;
            color: #862d2f;
            font-weight: bold;
        }

        .complete-status {
            text-align: center;
            background: #bafdba;
            color: #078807;
            width: 100%;
            display: block;
            font-weight: bold;
            opacity: 0.9;
            margin-bottom: 10px;
        }


        .price.free{
            color: #333;
            text-decoration:line-through;
        }



        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


        #page_loader{
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 9999;
            opacity: 0.7;
            background: black;
           display: none;
            top: 0;
            left: 0;
        }


        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            position: absolute;
            margin: 23% 47%;
        }



    </style>

<?php
get_footer();
?>