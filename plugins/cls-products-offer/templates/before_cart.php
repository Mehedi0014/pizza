<?php
get_header();
?>

<section>


    <div class="container">

        <div class="wrapper" style="text-align: center;padding: 30px 0">
            <p style="width: 100%" class="cart-empty woocommerce-info">Sie müssen das Angebot löschen löschen, bevor Sie zur Kasse oder zum Warenkorb gehen.</p>
            <a href="<?php echo get_home_url()."/angebote/"?>" id="myBtn" class="button wc-backward wcCtBtnThree">Zurück zur Angebotsseite </a>
        </div>

    </div>



</section>

<script>
//    document.getElementById("myBtn").addEventListener("click", function(){ history.go(-1)});
</script>

<?php
get_footer();
?>