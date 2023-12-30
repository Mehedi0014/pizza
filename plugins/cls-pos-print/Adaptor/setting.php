<?php
$siteRootUrl = get_site_url( null, '/', null );
global $wpdb;

if (isset($_POST['port1'])&& isset($_POST['updatePrinter'])&& isset($_POST['ip2']) ) {
    $port1 =           $_POST['port1'];
    $ip1 =             $_POST['ip1'];
    $port2 =         isset($_POST['port2']) ? $_POST['port2']:'';
    $ip2 =             isset($_POST['ip2'])?$_POST['ip2']:'';
    $enable_Oder_print = isset($_POST['enable_order_print'])?$_POST['enable_order_print']:0;
        update_option( 'Enable Print When Create Order', $enable_Oder_print );
        update_option( 'POS Printer IP1', $ip1 );
        update_option( 'POS Printer IP2', $ip2 );
        update_option( 'POS Printer PORT1', $port1 );
        update_option( 'POS Printer PORT2', $port2 );

}

$enable_Oder_print = get_option('Enable Print When Create Order');
$ip1 = get_option('POS Printer IP1');
$port1 = get_option('POS Printer PORT1');
$ip2 = get_option('POS Printer IP2');
$port2 = get_option('POS Printer PORT2');


?>

<div id="appT" class="wrap">
    <h1 class="wp-heading-inline"> CLS POS-Druckereinstellung </h1> <hr class="wp-header-end">

<form action="<?php $siteRootUrl ?>" method="POST">
    <div class="container-fluid" style="width: 50%; margin-top: 25px">


        <div class="checkbox">
            <label><input type="checkbox" name="enable_order_print" value="1" <?php echo ( ($enable_Oder_print == '1')? 'checked':'')?>>Druck beim Erstellen der Bestellung aktivieren</label>
        </div>

        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="ip1" style="line-height: 30px">Geben Sie die IP-Adresse des ersten POS-Druckers ein </label>
            <input type="text" id="ip1" name="ip1" value="<?php echo $ip1 ?>">
        </div>


        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="port1" style="line-height: 30px">Geben Sie den ersten POS-Druckeranschluss für den ersten Drucker ein </label>
            <input type="text" id="port1" name="port1" value="<?php echo $port1 ?>">
        </div>

        <div class="form-field" style="margin: 20px 0 20px 0 ">
            <label for="ip2" style="line-height: 30px">Geben Sie die IP des ersten POS-Druckers für den zweiten Drucker ein </label>
            <input type="text" id="ip2" name="ip2" value="<?php echo $ip2 ?>">

            <label for="port2" style="line-height: 30px">Geben Sie den ersten POS-Druckeranschluss für den zweiten Drucker ein </label>
            <input type="text" id="port2" name="port2" value="<?php echo $port2 ?>">
        </div>

        <div>
            <input type="submit" value="Einstellung sichern" name="updatePrinter">
        </div>
    </div>
</form>

</div>