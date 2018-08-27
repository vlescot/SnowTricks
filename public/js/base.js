"use strict";

/**
 *        Modal Screen Call
 **/
function displayAuthenticationModal(modalName) {
    $(".modal-backdrop").remove();
    $("#modal-container").load( '/authentication/' + modalName, function(){
        $("#" + modalName).modal({show:true});
    });
}

/**
 * 		AJAX LOADING SPINNER
 **/
let $spinner = $('.fa-spinner').hide();
$(document)
    .ajaxStart(function () { $spinner.show(); })
    .ajaxStop(function () { $spinner.hide(); });

/**
 *      Hide Flash messages container after a while
 */
let flash = $("#flash-container");
if (flash.length > 0) {
    setTimeout(function(){
            flash.fadeOut(600)
        },
        5000
    );
}
