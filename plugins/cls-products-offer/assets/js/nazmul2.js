
function show_msg (msg,type) {
    var msgType="#msgType";
    var msgContent ="#msgContent";
    if(type ==="error"){
        $(msgType).addClass('alert-danger').removeClass('alert-success');
    }
    else {
        $(msgType).addClass('alert-success').removeClass('alert-danger');
    }
    $(msgContent).html("").html(msg);
    $(this.msgModal).modal('show');
    setTimeout(function(){ $("#msgModal").modal('hide'); }, 3000);
}




if(offers!=null) {
    var MyOfffer = new Vue({
        el: '#OfferApp',
        data: {
            offers: null,
            c_offer_price:0,
            c_offer_products:[],
            c_free_products:[],
            current_offer: [],
            conditions:null,
            c_complete:false,
            cart:[],
            total:0
        },
        created: function () {
              this.offers= offers.offers;
              this.current_offer = current_offer;
              this.conditions = conditions;
              this.price = this.current_offer.price_amount;
              this.id = this.current_offer.offer_id;
              this.max = max_qty;
              this.cart = cart;
              this.update_price();

              console.log(this.offers);

            // console.log(this.offers[0].items);

        },
        methods: {

            update_price:function(){

                var price = this.cart.total;
                for (var i in this.offers){
                    if( this.offers[i].complete === true){
                        price+=this.offers[i].price_amount;
                    }
                }

                this.total =price;

            },
            chcek_all_offer:function(){

                console.log(this.offers);
                for(i in this.offers){

                    if(this.offers[i].complete==false){
                        return false;
                    }

                }
                return true;

            },
            convert_currency: function (price,format) {
                format = (typeof format !== 'undefined') ?  format : 'EURO';
                var temp = "€ " + parseFloat(price).toFixed(2);
                if(format==="EURO"){
                    temp = temp.replace(".","#");
                    temp = temp.replace(",",".");
                    temp = temp.replace("#",",");
                }
                else{
                    temp = f_number.toFixed(2) + ' $';
                }
                return temp;
            },
            remove_offer: function (token,e) {
                e.preventDefault();
                $("#page_loader").show();
                $.ajax({
                    type:"POST",
                    url: global_post_url,
                    data: {
                        action: "setMyOfferAction",
                        token: token,
                        mode: 'delete'
                    },
                    dataType : "json",
                    success:function(data){
                        $("#page_loader").hide();
                        if(data.msg ==="ok"){
                            MyOfffer.offers = data.daten;
                            MyOfffer.update_price();
                            show_msg(data.content,"sucess");
                        }
                        else{
                            show_msg(data.content,"error");
                        }
                    },
                    error: function(errorThrown){
                        $("#page_loader").hide();
                        show_msg(errorThrown,"error");
                    }
                });
            },
            remove_cart_item: function(id,e){
                e.preventDefault();
                $("#page_loader").show();
                $.ajax({
                    type:"POST",
                    url: global_post_url,
                    data: {
                        action: "setMyOfferAction",
                        id:id,
                        mode: 'cart_delete'
                    },
                    dataType : "json",
                    success:function(data){
                        $("#page_loader").hide();
                        if(data.msg ==="ok"){
                            MyOfffer.cart = data.cart;
                            MyOfffer.update_price();
                            show_msg(data.content,"sucess");
                        }
                        else{
                            show_msg(data.content,"error");
                        }
                    },
                    error: function(errorThrown){
                        $("#page_loader").hide();
                        show_msg(errorThrown,"error");
                    }
                });

            },
            go_item_link:function(id){
                var link = base_url +"/cls-products-offer-page/?offer_type="+id;
                window.location.replace(link);
            },

            go_item_checkout:function(){

                if(this.chcek_all_offer()) {
                    var link = base_url + "/checkout/";
                    window.location.replace(link);
                }
                else{
                    show_msg("Sie müssen das Angebot löschen löschen, bevor Sie zur Kasse oder zum Warenkorb gehen.","error");
                }


            },

            remove_offer_item:function(token,type,offer_id,e){
                e.preventDefault();
                $("#page_loader").show();
                $.ajax({
                    type:"POST",
                    url: global_post_url,
                    data: {
                        action: "setMyOfferAction",
                        token:token,
                        mode: 'remove_offer_item',
                        type: type,
                        off_id:offer_id
                    },
                    dataType : "json",
                    success:function(data){
                        $("#page_loader").hide();
                        if(data.msg ==="ok"){
                            MyOfffer.offers = data.daten;
                            MyOfffer.update_price();
                            show_msg(data.content,"sucess");
                        }
                        else{
                            show_msg(data.content,"error");
                        }
                    },
                    error: function(errorThrown){
                        $("#page_loader").hide();
                        show_msg(errorThrown,"error");
                    }
                });

            },
            add_offer_item:function(id,type,condition_id){
                var off_id = this.current_offer.offer_id;
                $("#page_loader").show();
                    $.ajax({
                    type:"POST",
                    url: global_post_url,
                    data: {
                        action: "setMyOfferAction",
                        id:id,
                        mode: 'add_offer_item',
                        type: type,
                        con_id:condition_id,
                        off_id:off_id
                    },
                    dataType : "json",
                    success:function(data){
                        $("#page_loader").hide();
                        if(data.msg ==="ok"){
                            MyOfffer.offers = data.daten;
                            MyOfffer.update_price();
                            show_msg(data.content,"sucess");
                        }
                        else{
                            show_msg(data.content,"error");
                        }
                    },
                    error: function(errorThrown){
                        $("#page_loader").hide();
                        show_msg(errorThrown,"error");
                    }
                });
            }
        }
    })
}


$(".offerBoxWrapper").on("click",function(e){
    e.preventDefault();

    var href = window.location.href + $(this).attr('href');
    var id = $(this).attr("data-id");
    $("#page_loader").show();
    $.ajax({
        type:"POST",
        url: global_post_url,
        data: {
            action: "setMyOffer",
            id: id
        },
        dataType : "json",
        success:function(data){
            $("#page_loader").hide();
            if(data.msg ==="ok"){
                show_msg(data.content,"sucess");
                window.location.replace(href);
            }
            else{
                show_msg(data.content,"error");
            }
        },
        error: function(errorThrown){
            $("#page_loader").hide();
            show_msg(errorThrown,"error");
        }

    });

});


