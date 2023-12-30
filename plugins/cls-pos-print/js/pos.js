

$("a.button.tips.nazmul-pos").on("click",function (e) {
    e.preventDefault();
    var order_id  =$(this).attr("data-id");

    $.ajax({
        url: global_pos_url,
        data: {'order':order_id},
        dataType : "json",
        type: 'POST',
        success: function(data){
            console.log(data);
            if(data.msg ==="ok"){
                alert("Drucken ist erledigt ");
            }
            else{
                alert("Irgendwas stimmt nicht ");
            }
        },
        error: function () {
              alert("Irgendwas stimmt nicht ");

        }
    });

});