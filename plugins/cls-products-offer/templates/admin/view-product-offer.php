
<?php


if (!class_exists($clsProductsOffer)){
    require_once( CLS_OFFER_DIR . 'Model/Offer.php');
    $my_offer = new Offer();
}
else{
    $my_offer = $clsProductsOffer->$offerModel;
}

$data = "offer_id,name,des,image,image_alt,offer_type,offer_type,expire_system,expire_date";
$offerlists = $my_offer->get_all_offer($data);

?>


<div class="wrap" id="appT">
    <h1 class="wp-heading-inline">Porducts Offers</h1> <a href="<?php echo admin_url('admin.php?page=cls-create-products-offer'); ?>" class="page-title-action">Add New</a>
    <hr class="wp-header-end">

<div class="container-fluid">
  <div id="my_offer">
            <table id="showOfferProductsTable" class="table nowrape table-striped table-bordered display nowrap dataTable dtr-inline collapsed">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Offer Type</th>
                    <th>Expire Date</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($offerlists)) :

                    foreach ($offerlists as $offerlist):
                        ?>
                        <tr>
                            <th><?php echo $offerlist['offer_id'] ?></th>
                            <th><?php echo $offerlist['name'] ?></th>
                            <td><?php echo $offerlist['des'] ?></td>
                            <td>
                                <img style="width: 100px; height: 100px;" src="<?php echo $offerlist['image'] ?>" alt="<?php echo $offerlist['image_alt'] ?>">
                            </td>
                            <td><?php echo $offerlist['offer_type'] ?></td>
                            <td><?php echo $offerlist['expire_date'] ?></td>

                            <!--th>
                                        <a class="btn btn-info" href="<?php echo admin_url('admin.php?page=cls-view-products-offer&offer_id='.$offerlist['offer_id']); ?>">Edit</a>
                                    </th-->
                            <th>
                                <a href="<?php echo admin_url('admin.php?page=cls-create-products-offer&offer_id=').$offerlist['offer_id'] ?>" name="editOffer" id="editOffer" value="<?php echo $offerlist['offer_id'] ?>" data-id="<?php echo $offerlist['offer_id'] ?>" class="btn btn-primary">Edit</a>
                                <button name="deleteOffer" value="<?php echo $offerlist['offer_id'] ?>" data-id="<?php echo $offerlist['offer_id'] ?>" class="btn btn-danger deleteOffer">Delete</button>
                            </th>
                        </tr>
                    <?php endforeach; endif  ?>
                </tbody>
            </table>



  </div>
</div>

</div>


<script>

    var delete_post_url="<?=admin_url('admin-ajax.php?action=delete_offer')?>";
</script>



<style>


    #my_offer {
        background: #FFF;
        padding: 20px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #eee;
        padding-bottom: 30px;
    }

    .wrap h1.wp-heading-inline {
        display: inline-block;
        margin-right: 5px;
        padding: 10px 15px;
    }
    #showOfferProductsTable thead {
        background: #1d2327;
        color: white;
    }

    #showOfferProductsTable img {
        width: 100px;
        max-width: 40px;
        height: auto !important;
    }

    .dataTables_wrapper .dataTables_length select {
        padding: 0 16px!important;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 0 5px !important;
    }

    .container-fluid{
     margin-top: 25px;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        padding: 5px 8px;
        font-size: 12px;
        text-align: center;
    }

</style>