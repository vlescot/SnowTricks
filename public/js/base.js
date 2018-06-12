/**
 *        Modal Screen Call
 **/
function displayModal(modalName) {
    $(".modal-backdrop").remove();
    $("#modal-container").load("/auth/" + modalName ,function(response){
        $("#" + modalName).modal({show:true});
    });
}


/**
 * 		AJAX LOADING SPINNER
 **/
let $spinner = $('.fa-spinner').hide();
$(document)
    .ajaxStart(function () { $spinner.show(); })
    .ajaxStop(function () { $spinner.hide(); })
;