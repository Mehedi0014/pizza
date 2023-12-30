$(document).ready(function() {

    $('#showOfferProductsTable').DataTable();
} );


$(".deleteOffer").on('click',function(){


    var id =$(this).attr("data-id");
    $.ajax({
        url: delete_post_url,
        data: {'offer':id},
        dataType : "json",
        type: 'POST',
        success: function(data){
            console.log(data);
            if(data.msg ==="ok"){
                window.location.reload();
            }
            else{
                alert("Delete Problem");
            }
        },
        error: function () {
            alert("Error");
        }
    });
});
