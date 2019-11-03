function detailsModal(id){
    var data = {"id":id};
    jQuery.ajax({
        url : "<?=BASEURL;?>+'indexstructure/detailsModal(lightbox).php'",
        method : "POST",
        data : data,
        success : function(data) {
            jQuery('body').append(data);
            jQuery('#details-modal').modal('toggle');
        },
        error : function() {
            alert('Something went wrong somewhere');
        }
    });
}