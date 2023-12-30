
var ConTable = new Vue({
    el: '#appT',
    data: {
        selected: '',
        offer_type:[
            { name:'Fixed', title:'Festpreis' } ,
            { name:'Discount', title:'Reduzierter Preis' } ,
            { name:'FreeItem', title:'Gratisartikel' }
        ],

        cond_selected: '',
        cond_type:{'Fixed':[
            { name:'category_product', title:'Kategorie Produktartikel ' } ,
            { name:'custom_product', title:'Benutzerdefiniertes Produkt (SKU) ' } ,
        ],
        'Discount':[
            { name:'category_product', title:'Kategorie Produktrabattartikel' } ,
            { name:'custom_product', title:'Rabatt auf kundenspezifisches Produkt (SKU)' } ,
            //{ name:'minimum_price', title:'Discount on Minimum Price'},
            //{ name:'minimum_products', title:'Discount on Product Qty'}
        ],
        'FreeItem':[
            { name:'category_product', title:'Kategorie weise Gratisartikel' } ,
            { name:'custom_product', title:'Benutzerdefinierte Produktbasierte kostenlose Artikel' } ,
           // { name:'minimum_price', title:'Free Item Minimum Price'},
           // { name:'minimum_products', title:'Free Item Product Qty'}
        ]
},
        conditions:[],
        rule:[ ],
        rules: {
            'A':[
                { name:'category_product',rule: ['category_id','minimum_products_qty' ]} ,
                { name:'custom_product',rule: ['minimum_products_qty','custom_products_sku']},
                { name:'minimum_price',rule: ['minimum_price']},
                { name:'minimum_products',rule: ['minimum_products']}
            ],
            'B':[
                { name:'category_product',rule: ['category_id','minimum_products_qty','free_items_qty' ]} ,
                { name:'custom_product',rule: ['minimum_products_qty','custom_products_sku','free_items_qty']},
                { name:'minimum_price',rule: ['minimum_price','free_items_qty']},
                { name:'minimum_products',rule: ['minimum_products','free_items_qty']}
            ]
        },
        msgModal:"#msgModal",
        discount_type :'Fixed',
        discount_type_select:[{name:'Fixed'},{name:'Percentage'}],
        free_item_type :'category',
        free_item_type_select:[{name:'Kostenloser Artikel aus der Kategorie ',value:'category'},{name:'Benutzerdefinierte kostenlose Artikel ',value:'custom'}],
        mode:'',
        new_cond:true,
        cond_index:0,
        current_cond:[],
    },

    created: function () {
        // `this` points to the vm instance



        this.rule = this.rules.A;
        this.selected = g_selected;

        if(g_conditons!=false) {
            this.conditions = g_conditons;
            this.cond_index = g_conditons.length -1;
            console.log(this.conditions);
            console.log(this.cond_index)
        }


        this.mode=mode;


        console.log(g_selected);
        $(function () {
            $("#offerType").on("change",function () {
                ConTable.conditions=[];
            });
        });

    },

    methods: {
       swap_rule:function () {
            if(this.selected === 'FreeItem'){
                this.rule = this.rules.B;
            }
            else{
                this.rule = this.rules.A;
            }

       } ,
      add_condition: function (item) {
          (this.conditions).push(item);
      },
      get_condition:function () {
         return  JSON.stringify(this.conditions);
      },
       openModalcond:function () {
         if(this.selected==''){
            this.show_msg("Wählen Sie mindestens einen Angebotstyp aus","error")
             return;
         }
         else  if(ConTable.conditions.length>0){
               ConTable.show_msg("Sorry We are support only one condition in this version.","error");
             return;
           }
           else{

             this.new_cond =true;
             $('#attCondition').modal('show');
         }
       },
      show_msg:function (msg,type) {
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
          setTimeout(function(){ $(this.msgModal).modal('hide'); }, 3000);
      } ,

       delete_item:function (index) {
            this.conditions=[];
          // this.conditions.splice(parseInt(index), 1);
       },
      form_object :function (data) {
          var obj={};
          for(var i in data){
              obj[data[i].name] = data[i].value;
          }
        return obj;
      },
        update_con:function(){
            var data = $('#ConditionForm').serializeArray();
            data = ConTable.form_object(data);
            if(ConTable.condition_check(data,"")){
                this.conditions[this.cond_index] = data;
                console.log(this.conditions);
                $('#attCondition').modal('hide');
            }
            else{
                ConTable.show_msg("Bitte überprüfen Sie alle Anforderungen","error");
            }
        },

        editCondition:function(i){
            this.new_cond =false;
            this.cond_index=i;
            this.cond_selected = this.conditions[this.cond_index].offerConditionType;
           // console.log(this.cond_selected);
            this.current_cond =  this.conditions.i;
            $('#attCondition').modal('show');
           // console.log(this.current_cond[i])
          //  this.new_cond =false;
        },

      condition_check:function (data,rule) {
            var system=false;
            if(rule === "" ){
                rule = this.rule;
                system = true;
            }
            if(system) {
                var condType = (data.offerConditionType).trim();
                if (condType === "") {
                    $("#offerConditionType").addClass('error');
                    this.show_msg("Muss Angebotskonditionstyp auswählen","error");
                    return false;
                }
                else {
                    $("#offerConditionType").removeClass('error');
                }
            }
            var flag =true;
            for( var i in rule ){
                if( rule[i].name === condType){
                    for( var j in rule[i].rule){
                        var k = rule[i].rule[j];
                        if(data[k] === ""){
                            flag=false;
                            $('#'+k).addClass('error');
                        }
                        else{
                            $('#'+k).removeClass('error');
                        }
                    }
                    break;
                }
            }
            if(!flag){
                return false;
            }
            return true;
        }
    }
});


$("#AddCondition").on("click",function () {
    var data = $('#ConditionForm').serializeArray();
    data = ConTable.form_object(data);
    if(ConTable.condition_check(data,"")){
        ConTable.add_condition(data);
        $('#attCondition').modal('hide');
    }
    else{
        ConTable.show_msg("Bitte überprüfen Sie alle Anforderungen","error");
    }
});



$("#my_offer").on("submit",function (e) {
    e.preventDefault();

    var fd = new FormData();

    var files = $('#offerUploadImage')[0].files;
    fd.append('file',files[0]);
    var other_data = $(this).serializeArray();
    var formatted_data = ConTable.form_object(other_data);
    if(formatted_data.offerName === ""){
        $("#offerName").addClass('error');
        ConTable.show_msg("Angebotsname muss benötigt werden","error");
        return false;
    }
    else{
        $("#offerName").removeClass('error');
    }

    if(formatted_data.offerTitle === ""){
        $("#offerTitle").addClass('error');
        $("msgModal").modal('');
        ConTable.show_msg("Angebotsbeschreibung Muss benötigt werden ","error");
        return false;
    }
    else{
        $("#offerTitle").removeClass('error');
    }
    var offerType = $("#offerType").val();

    if(offerType ===''|| offerType === null) {
        ConTable.show_msg("Bitte wählen Sie eine Angebotsart ","error");
        return false;
    }
   if(offerType ==='Fixed'){
      if(formatted_data.fixed_price_amount === ""){
            $("#fixed_price_amount").addClass('error');
             ConTable.show_msg("Festpreisbetrag muss benötigt werden","error");
             return false;
        }
        else{
            $("#fixed_price_amount").removeClass('error');
        }
      }
   if(offerType ==='Discount'){
        if(formatted_data.discount_amount === ""){
            $("#discount_amount").addClass('error');
                ConTable.show_msg("Rabattbetrag muss benötigt werden ","error");
            return false;
        }
        else{
            $("#discount_amount").removeClass('error');
        }
    }
    $.each(other_data,function(key,input){
        fd.append(input.name,input.value);
    });
    fd.append('conditions',JSON.stringify(ConTable.conditions));
    fd.append('mode',mode);
    fd.append('offer_id',offer_id);

    $("#page_loader").show();

    $.ajax({

        url: global_post_url,
        data: fd,
        contentType: false,
        processData: false,
        dataType : "json",
        type: 'POST',
        success: function(data){
            $("#page_loader").hide();
            if(data.msg ==="ok"){
                ConTable.show_msg(data.content,"sucess");
                    $("form").each(function(){
                    $(this).find(':input[type=text],:input[type=number]').val("");
                });
                window.location.replace(global_redirect_url);
            }
            else{
                ConTable.show_msg(data.content,"error");
            }
        },
        error: function () {
            $("#page_loader").hide();
            ConTable.show_msg("System Error","success");
        }
    });

});



