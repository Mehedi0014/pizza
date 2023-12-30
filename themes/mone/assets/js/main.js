$(document).ready(function() {

    $(".owl-carousel.bannerCarousel").owlCarousel({
        loop: 1,
        autoplay: 1,
        margin: 0,
        nav: 0,
        dots: 0,
        items: 1,

        autoplayTimeout: 5000,
        animateIn: 'animate__fadeIn',        
        animateOut: 'animate__fadeOut'
    });

    $(".owl-carousel.specialOfferCarousel").owlCarousel({
        loop: 1,
        autoplay: 1,
        margin: 0,
        nav: 1,
        dots: 0,
        //items: 3,
        lazyLoad: 1,
        navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right"></i>'],
        margin: 40,
        responsive:{
          0:{
              items:1
          },
          767:{
              items:2
          },
          991:{
              items:4
          }
        }
    });

    $(".owl-carousel.ourItems").owlCarousel({
        loop: 1,
        autoplay: 1,
        margin: 0,
        nav: 1,
        dots: 0,
        items: 3,
        lazyLoad: 1,
        navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right"></i>'],
        margin: 40,
        responsive:{
          0:{
              items:2
          },
          767:{
              items:3
          },
          991:{
              items:5
          }
        }
    });



    /* ==============================================================================
    After click footer google map, page scroll down in bottom. 
    ============================================================================== */

    $('.googleMapToggleBtn').click(function(){
      $('#google_map').toggle('slow', 'linear', function(){
        $('html, body').animate({ scrollTop: $(document).height()-$(window).height() })
      });
    });




  $(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 220) {
      $(".mastheadNav").addClass("mastheadfixed");
      $("#mega-menu-wrap-menu-2 #mega-menu-menu-2 li.mega-menu-item.ct_btnOne").css({"border" : "1px solid #fff"});
      $(".navLogo img").css({"max-width" : "50%"});
      $("#site-navigation").css({"margin-top" : "17px"});
      $("#navbarPackeg .rightMenu").css({"margin-top" : "17px"});
    }else{
      $(".mastheadNav").removeClass("mastheadfixed");
      $("#mega-menu-wrap-menu-2 #mega-menu-menu-2 li.mega-menu-item.ct_btnOne").css({"border" : "2px solid #862D2F"});
      $(".navLogo img").css({"max-width" : "100%"});
      $("#site-navigation").css({"margin-top" : "70px"});
      $("#navbarPackeg .rightMenu").css({"margin-top" : "70px"});
    }
  });


    
}); // Close document dot ready.