
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
        items: 3,
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
              items:3
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


    /* ==============================================================================
    Pass Min and Max selected value in single page option for select Item.
    ============================================================================== */

    $(".minPrice").click(function(){
      var one = "small";
      var one = Cookies.set('size', one);
    });

    $(".maxPrice").click(function(){
      var one = "large"
      var one = Cookies.set('size', one);
    });

    function minMaxPrice(){
      var one = Cookies.get('size');
      if(one == 'small'){
        $('table.variations select#pa_size option[value="small"]').prop('selected', true);
        Cookies.remove('size');
      }else if(one == 'large'){
        $('table.variations select#pa_size option[value="large"]').prop('selected', true);
        Cookies.remove('size');
      }
    }

    minMaxPrice();
    
}); // Close document dot ready.




/* ==============================================================================
  Shop / Archive page - Shot by customization.
============================================================================== */

var x, i, j, l, ll, selElmnt, a, b, c;
/*look for any elements with the class "customOrdering":*/
x = document.getElementsByClassName("customOrdering");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);











