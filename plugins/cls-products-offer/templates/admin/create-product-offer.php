<?php
if(isset($_REQUEST['offer_id'])){
    require_once( CLS_OFFER_DIR . 'Model/Offer.php');
    $offer_id = (int)$_REQUEST['offer_id'];
    $my_offer = new Offer();

    try {
        $offer = $my_offer->load_offer($offer_id,OBJECT);
       $conditions = $my_offer->get_conditions($offer_id,OBJECT);

        foreach($conditions as $condition){
            $f_conditions[]= ['offerConditionType'=>$condition->condition_type,
                    'minimum_products_qty'=>$condition->minimum_product_qty,
                    'category_id'=>$condition->category_id,
                    'custom_products_sku'=>$condition->custom_product_sku,
                    'free_item_type'=>$condition->free_item_type,
                    'free_item_category'=>$condition->free_item_category,
                    'free_items_qty'=>$condition->free_items_qty
                        ];
        }
        $delta =true;
        }
    catch(\Exception $e){

    }
}
else{
    $delta =false;
}

?>


<?php if($delta==true):?>

<script>
    var offer_id ="<?php echo $offer_id ?>";
    var mode ='edit';
    var g_selected ="<?php echo  $offer->offer_type ?>";
    var g_conditons =<?php echo json_encode($f_conditions) ?>

</script>

<?php else: ?>
<script>
    var offer_id = 0;
    var g_selected ='';
    var g_conditons= false;
    var g_offer= false;
    var mode ='new'
 </script>
<?php endif ?>


<div class="page_loader" id="page_loader">
    <div class="loader"></div>
</div>

<div class="Note">
    <p>    System ablaufen lassen und mehrere Bedingungen nicht funktionieren In dieser Version </p>
</div>

<div class="wrap" id="appT">
    <h1 class="wp-heading-inline"> Neues Produktangebot hinzufügen  </h1>
    <hr class="wp-header-end">


    <form id="my_offer" action="" method="POST" enctype="multipart/form-data">

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="offerName">Angebotsname</label>
                <input class="form-control" type="text" id="offerName" name="offerName" placeholder="Offer Name"
                       value="<?php echo  (isset($offer->name))?$offer->name:''?>">
            </div>


            <div class="form-group col-md-6">
                <label for="offerTitle">Angebotsbeschreibung</label>
                <input class="form-control" type="text" id="offerTitle" name="offerTitle" placeholder="Offer Title"
                value="<?php echo (isset($offer->des))?$offer->des:''?>">
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="offerType">Angebotsart</label>
                <select class="form-control" name="offerType" id="offerType" v-model="selected" >
                    <option disabled value="">Bitte wählen Sie eine aus </option>
                    <option v-for="offer in offer_type" :value="offer.name" v-select="offer.select" >{{ offer.title}} </option>

                </select>
            </div>

            <div class="form-group col-md-6" v-show="selected =='Fixed'" >
                <label for="fixed_price_amount">Festpreisbetrag </label>
                <input class="form-control" type="number" id="fixed_price_amount" name="fixed_price_amount" placeholder="fixed price amount"
                value="<?php echo (isset($offer->price_amount))?$offer->price_amount:''?>">
            </div>
        </div>


        <div class="form-row" id="DiscountRow" v-show="selected =='Discount'">
            <div class="col-md-6 mb-3">
                <label for="offerDiscountType">Rabattart </label>
                <select class="form-control" name="offerDiscountType" id="offerDiscountType" v-model="discount_type" :key="discount_type">
                    <option v-for="dis in discount_type_select" :value="dis.name" selected>{{dis.name}}</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="discount_amount">Rabattbetrag  {{discount_type}}</label>
                <input class="form-control" type="number" id="discount_amount" name="discount_amount" :placeholder="'Discount amount '+discount_type"
                value="<?php echo (isset($offer->discount_amount))?$offer->discount_amount:'discount_amount'?>">
            </div>
        </div>
        <div class="mb-2 col-md-12">
            <label class="d-block">Versandmethodenunterstützung </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="LocalPickup" id="LocalPickup" value="1" checked>
                <label class="form-check-label" for="localPickup">Abholung</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="homeDelivery" id="homeDelivery" value="1" checked>
                <label class="form-check-label" for="homeDelivery">Lieferung</label>
            </div>
        </div>

        <div class="form-row">
            <div class="form-check form-check-inline col-md-3 " >
                <input class="form-check-input" type="checkbox" name="enableExpire" id="enableExpire" checked value="1">
                <label class="form-check-label" for="enableExpire">Aktivieren Sie das Ablaufsystem </label>
            </div>
            <div class="col-md-4 mb-3">
                <label for="offerExpireDate">Ablaufdatum</label>
                <input class="form-control" type="date" id="offerExpireDate" name="offerExpireDate" value="<?php echo (isset($offer->expire_date))?trim($offer->expire_date):'discount_amount'?>">
            </div>
            <div class="form-group col-md-4">
                <label for="offerUploadImage">Offer Image</label>
                <input class="form-control-file" type="file" id="offerUploadImage" name="offerUploadImage">
                <?php if(isset($offer->image)):?>
                    <img src="<?php echo  $offer->image ?>" width="70px" height="auto"/>
                <?php endif ?>
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="rule_condition">Angebotsregelbedingung </label>
                <select class="form-control" name="rule_condition" id="rule_condition">
                    <option value="all" selected>Alles wahr </option>
                    <option value="any">Jeder wahr </option>
                </select>
            </div>
            <div class="col-md-6">
                <button class="form-control add_condition" type="button" @click="openModalcond()">+ Bedingung hinzufügen </button>
            </div>
        </div>

        <div  class="app">
            <div v-if="conditions.length > 0" :key="conditions" class="form-row" id="ConditionTableWrapper">
                <div class="col-md-6">
                    <table border="0" id="ConditionTable">
                        <thead>
                        <tr>
                            <td style="width:120px;">Zustands-ID </td>
                            <td style="cursor: pointer">Bedingung</td>
                            <td>Aktion</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,ind) in conditions" :key="conditions" >
                            <td>Cond-{{ind+1}}</td>
                            <td><span class="condition" :class="item.offerConditionType" @click="editCondition(ind)" :key="item.offerConditionType">{{item.offerConditionType}}</span></td>
                            <td><span class="dashicons dashicons-trash delete_con" @click="delete_item(ind)"></span></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- end of appT -->

        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input class="button button-primary button_ex" type="submit" value="Submit" name="submitOffer">
            </div>
        </div>

    </form>



    <div class="modal fade " id="attCondition" tabindex="-1" role="dialog" aria-labelledby="attCondition" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="new_cond==true" class="modal-title" id="exampleModalLabel">Add New Offer Condition</h5>
                    <h5 v-else class="modal-title" id="exampleModalLabel">Edit Offer Condition - {{cond_index+1}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ConditionForm">
                        <div class="New_form_box" v-if="new_cond==true">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="offerConditionType">Angebotskonditionstyp </label>
                                <select class="form-control" name="offerConditionType" id="offerConditionType" v-model="cond_selected" v-on:change="swap_rule()">
                                    <option value="" selected>Wählen Sie eine Konditionsart </option>
                                    <option v-if="selected == 'Fixed'" v-for="cond in cond_type.Fixed " :value="cond.name" >{{cond.title}}</option>
                                    <option v-if="selected == 'Discount'" v-for="cond in cond_type.Discount " :value="cond.name" >{{cond.title}}</option>
                                    <option v-if="selected == 'FreeItem'" v-for="cond in cond_type.FreeItem " :value="cond.name" >{{cond.title}}</option>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3"  v-if="cond_selected == 'category_product' || cond_selected == 'custom_product'">
                                <label for="minimum_products_qty">Mindestproduktmenge </label>
                                <input class="form-control" type="number" id="minimum_products_qty" name="minimum_products_qty" placeholder="Minimum Products Qty">
                            </div>
                        </div>

                        <div class="form-row"  >
                            <div class="col-md-6 mb-3" v-if="cond_selected == 'category_product'">
                                <label for="category_id">Kategorie ID </label>

                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="" selected>Kategorie wählen</option>
                                    <?php $product_categories = get_terms( 'product_cat', [ 'taxonomy'   => "product_cat",'orderby'    => 'ASC', ] )?>
                                    <?php foreach ($product_categories as $category):?>
                                        <option value="<?=$category->term_id ?>"><?=$category->name?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" v-if="cond_selected =='custom_product'">
                                <label for="custom_products_sku">Kundenspezifische Produkte Sku</label>
                                <input class="form-control" type="text" id="custom_products_sku" name="custom_products_sku" placeholder="Custom Products Sku">
                                <p class="hint">Muss durch Kommas (,) getrennt sein, z. B.: AE2012, BiC23 </p>
                            </div>
                        </div>

                        <div class="form-row" v-if="selected == 'Discount' || selected == 'FreeItem'">
                            <div class="col-md-6 mb-3" v-if="cond_selected == 'minimum_price'">
                                <label for="minimum_price">Minimum Price</label>
                                <input class="form-control" type="number" id="minimum_price" name="minimum_price" placeholder="Minimum Price">
                            </div>
                            <div class="col-md-6 mb-3" v-if="cond_selected == 'minimum_products'">
                                <label for="minimum_products">Minimum Products Qty</label>
                                <input class="form-control" type="text" id="minimum_products" name="minimum_products" placeholder="Minimum Products Amount">
                            </div>
                        </div>


                        <div class="form-row" v-if="selected =='FreeItem'">
                            <div class="col-md-4 mb-3">
                                <label for="free_item_type"> Kostenloser Artikeltyp </label>
                                <select class="form-control" name="free_item_type" id="free_item_type" v-model="free_item_type" :key="free_item_type">
                                    <option v-for="freeI in free_item_type_select" :value="freeI.value" selected>{{freeI.name}}</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3" v-show="free_item_type == 'category' ">
                                <label for="free_item_category"> Kostenlose Artikelkategorie </label>
                                <select class="form-control" name="free_item_category" id="free_item_category">
                                    <option value="" selected>Kategorie wählen</option>
                                    <?php $product_categories = get_terms( 'product_cat', [ 'taxonomy'   => "product_cat",'orderby'    => 'ASC', ] )?>
                                    <?php foreach ($product_categories as $category):?>
                                        <option value="<?=$category->term_id ?>"><?=$category->name?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>


                            <div class="col-md-4 mb-3"  v-show="free_item_type == 'custom' ">
                                <label for="fixed_free_items_sku">Benutzerdefinierte kostenlose Artikel (SKU) </label>
                                <input class="form-control" type="text" id="fixed_free_items_sku" name="fixed_free_items_sku" placeholder="Fixed Free item sku">
                                <p class="hint">Muss durch Kommas (,) getrennt sein, z. B.: AE2012, BiC23  </p>
                            </div>


                            <div class="col-md-4 mb-3">
                                <label for="free_items_qty">Kostenlose Artikelmenge </label>
                                <input class="form-control" type="number" id="free_items_qty" name="free_items_qty" placeholder="Free item Qty">
                            </div>

                        </div>

                    </div>



                        <div class="Edit_form_box" v-else>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="offerConditionType">Angebotskonditionstyp </label>
                                    <select class="form-control" name="offerConditionType" id="offerConditionType" v-model="cond_selected" v-on:change="swap_rule()">
                                        <option value="" selected>Wählen Sie eine Konditionsart </option>
                                        <option v-if="selected == 'Fixed'" v-for="cond in cond_type.Fixed " :value="cond.name" >{{cond.title}}</option>
                                        <option v-if="selected == 'Discount'" v-for="cond in cond_type.Discount " :value="cond.name" >{{cond.title}}</option>
                                        <option v-if="selected == 'FreeItem'" v-for="cond in cond_type.FreeItem " :value="cond.name" >{{cond.title}}</option>

                                    </select>
                                </div>

                                <div class="col-md-6 mb-3"  v-if="cond_selected == 'category_product' || cond_selected == 'custom_product'">
                                    <label for="minimum_products_qty">Mindestproduktmenge </label>
                                    <input class="form-control" type="text" id="minimum_products_qty" name="minimum_products_qty" placeholder="Minimum Products Qty"
                                    :value="conditions[cond_index].minimum_products_qty" v-model="conditions[cond_index].minimum_products_qty">
                                </div>
                            </div>

                            <div class="form-row"  >
                                <div class="col-md-6 mb-3" v-if="cond_selected == 'category_product'">
                                    <label for="category_id">Kategorie ID</label>

                                    <select class="form-control" name="category_id" id="category_id" v-model="conditions[cond_index].category_id">
                                        <option value="" selected>Kategorie wählen </option>
                                        <?php $product_categories = get_terms( 'product_cat', [ 'taxonomy'   => "product_cat",'orderby'    => 'ASC', ] )?>
                                        <?php foreach ($product_categories as $category):?>
                                            <option value="<?=$category->term_id ?>"><?=$category->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" v-if="cond_selected =='custom_product'">
                                    <label for="custom_products_sku">Kundenspezifische Produkte Sku </label>
                                    <input class="form-control" type="text" id="custom_products_sku" name="custom_products_sku" placeholder="Custom Products Sku"
                                           v-model="conditions[cond_index].custom_products_sku">
                                    <p class="hint">Muss durch Kommas (,) getrennt sein, z. B.: AE2012, BiC23  </p>
                                </div>
                            </div>

                            <div class="form-row" v-if="selected == 'Discount' || selected == 'FreeItem'">
                                <div class="col-md-6 mb-3" v-if="cond_selected == 'minimum_price'">
                                    <label for="minimum_price">Minimum Price</label>
                                    <input class="form-control" type="number" id="minimum_price" name="minimum_price" placeholder="Minimum Price"
                                           v-model="conditions[cond_index].minimum_price">
                                </div>
                                <div class="col-md-6 mb-3" v-if="cond_selected == 'minimum_products'">
                                    <label for="minimum_products">Minimum Products Qty</label>
                                    <input class="form-control" type="text" id="minimum_products" name="minimum_products" placeholder="Minimum Products Amount"
                                    v-model="conditions[cond_index].minimum_products">
                                </div>
                            </div>


                            <div class="form-row" v-if="selected == 'FreeItem'">
                                <div class="col-md-4 mb-3">
                                    <label for="free_item_type"> Kostenloser Artikeltyp </label>
                                    <select class="form-control" name="free_item_type" id="free_item_type"  v-model="conditions[cond_index].free_item_type" >
                                        <option v-for="freeI in free_item_type_select" :value="freeI.value" selected>{{freeI.name}}</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3" v-show="conditions[cond_index].free_item_type == 'category' ">
                                    <label for="free_item_category">Kostenlose Artikelkategorie </label>
                                    <select class="form-control" name="free_item_category" id="free_item_category"  v-model="conditions[cond_index].free_item_category" >
                                        <option value="" selected>Kategorie wählen </option>
                                        <?php $product_categories = get_terms( 'product_cat', [ 'taxonomy'   => "product_cat",'orderby'    => 'ASC', ] )?>
                                        <?php foreach ($product_categories as $category):?>
                                            <option value="<?=$category->term_id ?>"><?=$category->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>


                                <div class="col-md-4 mb-3"  v-show="free_item_type == 'custom' ">
                                    <label for="fixed_free_items_sku">Benutzerdefinierte kostenlose Artikel (SKU) </label>
                                    <input class="form-control" type="text" id="fixed_free_items_sku" name="fixed_free_items_sku" placeholder="Fixed Free item sku"  v-model="conditions[cond_index].fixed_free_items_sku">
                                    <p class="hint">Muss durch Kommas (,) getrennt sein, z. B.: AE2012, BiC23  </p>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="free_items_qty">Free item Qty</label>
                                    <input class="form-control" type="number" id="free_items_qty" name="free_items_qty" placeholder="Free item Qty"  v-model="conditions[cond_index].free_items_qty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button v-if="new_cond==true" type="button" class="btn btn-primary" id="AddCondition">Bedingung hinzufügen </button>
                    <a v-else @click="update_con()" class="btn btn-primary">Bedingung aktualisieren </a>
                </div>
            </div>
        </div>
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


</div>

<script>
    var global_post_url="<?=admin_url('admin-ajax.php?action=my_offer')?>";
    var global_redirect_url="<?=admin_url('admin.php?page=cls-view-products-offer')?>";
</script>

