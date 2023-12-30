<?php

$siteRootUrl = get_site_url( null, '/', null );
global $wpdb;

if (isset($_POST['updatePrice'])) {

    $smallPrice =           $_POST['smallPrice'];
    $largePrice =           $_POST['largePrice'];
    $partyPrice =           $_POST['partyPrice'];
    $partyPriceFreeCount =  $_POST['partyPriceFreeCount'];


    if(! empty($smallPrice) && ! empty($largePrice) && ! empty($partyPrice) && ! empty($partyPriceFreeCount)){
        update_option( 'Small Pizza Price', $smallPrice );
        update_option( 'Large Pizza Price', $largePrice );
        update_option( 'Party Pizza Price', $partyPrice );
        update_option( 'Party Price Free Count', $partyPriceFreeCount );
    }

}



$smallPrice = get_option('Small Pizza Price');
$largePrice = get_option('Large Pizza Price');
$partyPrice = get_option('Party Pizza Price');
$partyPriceFreeCount = get_option('Party Price Free Count');

?>


<form action="<?php $siteRootUrl ?>" method="POST">
    <div class="container-fluid" style="width: 50%; margin-top: 25px">
        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="smallPrice" style="line-height: 30px">Small Pizza Extra Toppings Price</label>
            <input type="text" id="smallPrice" name="smallPrice" value="<?php echo $smallPrice ?>">
        </div>


        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="largePrice" style="line-height: 30px">Large Pizza Extra Toppings Price</label>
            <input type="text" id="largePrice" name="largePrice" value="<?php echo $largePrice ?>">
        </div>
        


        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="partyPrice" style="line-height: 30px">Party Pizza Extra Toppings Price</label>
            <input type="text" id="partyPrice" name="partyPrice" value="<?php echo $partyPrice ?>">

            <label for="partyPriceFreeCount" style="line-height: 30px">Number of Party Pizza Free Toppings</label>
            <input type="text" id="partyPriceFreeCount" name="partyPriceFreeCount" value="<?php echo $partyPriceFreeCount ?>">
        </div>

        <div>
            <input type="submit" value="Update Price" name="updatePrice">
        </div>
    </div>
</form>

