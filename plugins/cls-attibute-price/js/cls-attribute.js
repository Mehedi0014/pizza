/**
 * Created by Nazmul on 27-Feb-21.
 */

$(document).ready(function() {

    var dataF  = $(".variations_form").attr("data-product_variations");
    //   console.log(dataF);


   if(dataF!=null || dataF!=undefined){
        dataF  = JSON.parse(dataF);

       if(pizza_dat!=undefined && pizza_dat!=false ){
           customer_pizza_system();
       }
       else{
           variable_product();
       }

    }
    else{
        var P_Object  =$(".clsPriceDora");

       try {
           if (size_k != undefined) {

               party_pizza();
           }
       }

       catch (e){

       }

       try {
           if (single_product != undefined) {
               simple_product();
           }
       }
       catch (e){

       }
return false;
    }


  function customer_pizza_system() {



      $(".ex2_topping").prop( "checked", false );
      var p_update  = $(".charge_att");
      var size = $("#pa_size").val();
      p_update.html(get_price_with_pattan(parseFloat(pizza_price(size))));

      var size_price =get_size_price(size);


      $(document).on('change',"#pa_size",function () {
          size = $(this).val();
          size_price  = get_size_price(size);
          $(".charge_att").html(get_price_with_pattan(parseFloat(pizza_price(size))));
          update_price3('none',size_price,size);
      });

      $(".ex2_topping").change(function () {
          //  var price = parseFloat($(this).attr('data-price'));
          update_price3('none',size_price,size);
      });

      $(".input-text.qty.text").change(function () {
          update_price3('none',size_price,size);
      })

      $(".qty_button.plus").click(function () {
          update_price3('plus',size_price,size);
      })

      $(".qty_button.minus").click(function () {
          update_price3('minus',size_price,size);
      })

  }


    function update_price3(q,size_price,size){

        var attribute_price = 0;
        var price =0;
        var f_price =0;

        $(document).on('click',".clsPriceDora",function () {
            $(this).html(f_price);
        });

        var qty = parseInt($('.input-text.qty').val());
        if(q=='plus'&&qty!=99){
            qty++;
        }
        if(q=='minus'&&qty!=1){
            qty--;
        }

        var attribute_qty =0;

        $(".ex2_topping").each(function () {
            if (this.checked) {
                attribute_qty++;
            }
        });


        if(size === 'party'){
            if(attribute_qty > parseInt(free)){
                attribute_qty =  attribute_qty - parseInt(free)
                attribute_price = parseFloat(pizza_price(size)) * attribute_qty;
            }
            else {
               attribute_price =0;
            }
        }
        else{

            attribute_price = parseFloat(pizza_price(size)) * parseInt(attribute_qty);
        }

       // console.log(attribute_price) ;

        price = (attribute_price + size_price)*qty;
       //console.log(price);
        var f_price = get_price_with_pattan(parseFloat(price));

        $( ".clsPriceDora" ).trigger( "click" );
    }

    function pizza_price(size) {
        switch(size){
            case 'small': return  pizza_dat.small ; break;
            case 'large':  return  pizza_dat.large ;   break;
            case 'party':  return  pizza_dat.party;   break;
            default:     return 0;
        }
    }

    function  variable_product() {
        $(".ex_topping").prop( "checked", false );
        $(".defaultRadio").prop("checked", true);


        var size = $("#pa_size").val();
        var size_price =get_size_price(size);
        $(document).on('change',"#pa_size",function () {
            size = $(this).val();
            size_price  = get_size_price(size);
            update_price('none',size_price);
        });

        $(".ex_topping").change(function () {
            //  var price = parseFloat($(this).attr('data-price'));
            update_price('none',size_price);
        });

        $(".input-text.qty.text").change(function () {
            update_price('none',size_price);
        })

        $(".qty_button.plus").click(function () {
            update_price('plus',size_price);
        })

        $(".qty_button.minus").click(function () {
            update_price('minus',size_price);
        })
    }

    function simple_product(){

        $(".ex_topping").prop( "checked", false );
        $(".defaultRadio").prop("checked", true);
        $(".ex_topping").change(function () {
            //  var price = parseFloat($(this).attr('data-price'));
            update_price2('none');
        });
        $(".main_bu").change(function () {
            update_price2('none');
        });

        $(".input-text.qty.text").change(function () {
            update_price2('none');
        })

        $(".qty_button.plus").click(function () {
            update_price2('plus');
        })

        $(".qty_button.minus").click(function () {
            update_price2('minus');
        })


    }

    function update_price2(q){

        var PriceProduct = parseFloat($("#Extra").attr('data-product'));
        var attribute_price = 0;
        var size_price = 0;
        var qty = parseInt($('.input-text.qty').val());
        if(q=='plus'&&qty!=99){
            qty++;
        }
        if(q=='minus'&&qty!=1){
            qty--;
        }

        $(".ex_topping").each(function () {
            if (this.checked) {
                attribute_price+=parseFloat($(this).attr('data-price'));
            }
        });
        $(".main_bu").each(function () {
            if (this.checked) {
                size_price+=parseFloat($(this).attr('data-price'));
            }
        });

        price = (PriceProduct + attribute_price + size_price)* qty;
        P_Object.html(get_price_with_pattan(price));

    }


    function update_price(q,size_price){
        var attribute_price = 0;
        var price =0;
        var f_price =0;
        $(document).on('click',".clsPriceDora",function () {
            $(this).html(f_price);
        })
        var qty = parseInt($('.input-text.qty').val());
        if(q=='plus'&&qty!=99){
            qty++;
        }
        if(q=='minus'&&qty!=1){
            qty--;
        }
        $(".ex_topping").each(function () {
            if (this.checked) {
                attribute_price+=parseFloat($(this).attr('data-price'));
            }
        });
        price = (attribute_price + size_price)*qty;
//        console.log(price);
        var f_price = get_price_with_pattan(price);
        $( ".clsPriceDora" ).trigger( "click" );
    }
    
    function get_size_price(size) {
        for(i =0 ;i< dataF.length; i++){
           if(dataF[i].attributes.attribute_pa_size === size){
               return dataF[i].display_price;
           }
        }
        return false;
    }


    function get_price_with_pattan(price){
        //return '<bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>'+price.toFixed(2)+'</bdi>';
        return '<bdi><span class="woocommerce-Price-currencySymbol">€</span>'+price.toFixed(2)+'</bdi>';
    }

    function party_pizza(){



        var P_Object = $(".clsPriceDora");

        $(".ex_topping").prop("checked", false);
        $(".defaultRadio").prop("checked", true);
        var size = size_k;
        var size_price = parseFloat($("#single_pizza").attr("data-price"));
        $(".charge_att").html(get_price_with_pattan(parseFloat(pizza_price(size))));



        function pizza_price(size) {
            switch (size) {
                case 'small':
                    return pizza_dat.small;
                    break;
                case 'large':
                    return pizza_dat.large;
                    break;
                case 'medium':
                    return pizza_dat.medium;
                    break;
                case 'party':
                    return pizza_dat.party;
                    break;
                default:
                    return 0;
            }
        }

        function get_price_with_pattan(price) {
            //return '<bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>'+price.toFixed(2)+'</bdi>';
            return '<bdi><span class="woocommerce-Price-currencySymbol">€</span>' + price.toFixed(2) + '</bdi>';
        }



        $(".ex2_topping").change(function () {
            //  var price = parseFloat($(this).attr('data-price'));
            update_price3('none', size_price, size);
        });

        $(".input-text.qty.text").change(function () {
            update_price3('none', size_price, size);
        })

        $(".qty_button.plus").click(function () {
            update_price3('plus', size_price, size);
        })

        $(".qty_button.minus").click(function () {
            update_price3('minus', size_price, size);
        })


        function update_price3(q, size_price, size) {

            var attribute_price = 0;
            var price = 0;
            var f_price = 0;

            var qty = parseInt($('.input-text.qty').val());
            if (q == 'plus' && qty != 99) {
                qty++;
            }
            if (q == 'minus' && qty != 1) {
                qty--;
            }
            var attribute_qty = 0;
            $(".ex2_topping").each(function () {
                if (this.checked) {
                    attribute_qty++;
                }
            });

            if (size === 'party') {
                if (attribute_qty > parseInt(free)) {
                    attribute_qty = attribute_qty - parseInt(free)
                    attribute_price = parseFloat(pizza_price(size)) * attribute_qty;
                }
                else {
                    attribute_price = 0;
                }
            }
            else {

                attribute_price = parseFloat(pizza_price(size)) * parseInt(attribute_qty);
            }

            // console.log(attribute_price) ;

            price = (attribute_price + size_price) * qty;
            //console.log(price);
            var f_price = get_price_with_pattan(parseFloat(price));

            P_Object.html(get_price_with_pattan(price));
        }






    }




});

