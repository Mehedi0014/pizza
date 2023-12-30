$(document).ready(function() {

    /*
	==============================================================================
    >>>> Add owl carousel in single product page image
    ==============================================================================
	*/

    $(".owl-carousel.singlePageThumbnailCarousel").owlCarousel({
        loop: 1,
        autoplay: 1,
        margin: 0,
        nav: 0,
        dots: 0,
        items: 3,
        lazyLoad: 1,
        margin: 0
    });

    /*
	==============================================================================
    >>>> Add a class in active category for desing the active category
    ==============================================================================
	*/

   $("a").each(function(){
       if ($(this).attr("href") == window.location.href){
            $(this).addClass("active");
       }
   });


    /*
    ==============================================================================
    >>>> Cart page e jodi local pickup hoy tobe shipping address and shipping address change button show korbe na.
    ==============================================================================
    */
    if($('#shipping_method_0_local_pickup2').is(':checked')){
        $(".woocommerce-shipping-destination").css({'display' : 'none'})
        $(".woocommerce-shipping-calculator").css({'display' : 'none'})
    }
	
	

}); // Close document dot ready =================================================