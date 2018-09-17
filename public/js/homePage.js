"use strict";

let search = $("#search");
let tricks = listing($(".main-trick-title"));
let groups = listing($(".badge"));

$( document ).on("click", "#remove-trick", function () {
    let removeTrickModal = $("#remove-trick-modal");
    let trickId = removeTrickModal.find("input[type=hidden]").val();

    $.post("/figure/supprimer", {id: trickId}, function (data, status) {
        removeTrickModal.modal("hide");
        $("html,body").animate({ scrollTop: 0 }, 500);

        let flashContainer = $("#flash-container");

        let alert = "";
        let message = "";

        switch (status) {
            case "success" :
                alert = "success";
                message = "La figure a bien été supprimée";
                break;
            default:
                alert = "danger";
                message = "La figure n'a pas pu être supprimée... ";
        }

        flashContainer.html(
            '         <div class="alert alert-'+ alert +' alert-dismissible col-12 text-center">\n' +
            '            <strong>'+ message +'</strong>\n' +
            '            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
            '        </div>');

        flashContainer.show();
        setTimeout(function() {flashContainer.fadeOut(600)}, 5000);
        $("#" + trickId).parent().remove();
    });
});

$( document ).on("click", "#cancel-remove", function () {
    $(".modal").modal("hide");
});


function displayRemoveTrickModal(id, title) {
    $(".modal-backdrop").remove();
    let removeTrickModal = $("#remove-trick-modal");

    removeTrickModal.find(".modal-title span").html(title);
    removeTrickModal.find("input[type=hidden]").val(id);
    removeTrickModal.modal({show:true});
}
/**
 * Height's Thumbnail Adjusting
 */
function trickThumbnailHeight() {
    let trickHeight = [];
    let thumbnailTrickHeader = $(".main-trick-header");

    thumbnailTrickHeader.each(function () {
        trickHeight.push($(this).height());
    });

    let maxHeight = Math.max.apply(Math, trickHeight);

    thumbnailTrickHeader.height(maxHeight);
}


/**
 * Effects on trick hover Thumbnail
 **/
$(document).on({
    mouseenter() {
        $(".img", this).animate({
            left: "-10px",
            top: "-10px",
            marginBottom: "-20px",
            height: "+=20px",
            width: "+=20px",
        });
    },
    mouseleave() {
        $(".img", this).animate({
            left: "0px",
            top: "0px",
            marginBottom: "0px",
            height: "-=20px",
            width: "-=20px",
        });
    }
}, ".main-trick a");


function redirectToTrick(){
    let input = $("#search");
    let search = input.val().trim().toLowerCase();

    search = search[0].toUpperCase() + search.substr(1);

    if ($.inArray(search, tricks) !== -1){
        window.location.replace(window.location.origin + "/" + search);
    } else {
        input.val("");
        $("#search-warning").css("opacity", "1").show(400);
        window.setTimeout(function() {
            $("#search-warning").hide(500, 0);
        }, 4000);
    }
}


/**
 * Display the fa-arrow-up element if >= 15 tricks shown
 **/
function displayArrowUp() {
    if ($(".main-trick").length >= 30) {
        $(".fa-arrow-up").css("display", "block");
    }
}

/**
 * Toggle the groups after a click on badge
 */
function toggleGroup (toDisplay) {
    let groups = $(".main-trick-groups");

    // If click on badge with content "Tous" display all Thumbnail
    if (toDisplay === "Tous"){
        $(".main-tricks>div").show();
    }else {
        groups.each(function () {
            let display = false;
            let badge = $(".badge", this);
            badge.each(function () {
                let badgeContent = $(this).html();
                if (badgeContent === toDisplay) {
                    display = true;
                }
            });
            if (display){
                $(this).closest(".main-tricks > div").show();
            } else {
                $(this).closest(".main-tricks > div").hide();
            }
        });
    }
}


/**
 * Search
 */
function listing (element){
    let arr = [];
    element.each(function () {
        let obj = $(this).text().trim();
        if (obj !== "Tous" && $.inArray(obj, arr) === -1) {
            arr.push(obj);
        }
    });
    return arr;
}

search.autocomplete({
    source : tricks
});



$("#submit").on("click", function () {
    redirectToTrick();
});
search.on("keypress", function ( event ) {
    if ( event.which === 13 ) {
        event.preventDefault();
        redirectToTrick();
    }
});


// Display badge with each groups
groups.sort();
let i;
for (i = 0; i < groups.length; ++i) {

    let span = $("<span>", { class: "badge"});
    if (i % 8 === 0 && i !== 0) {
        let container = $(".main-groups div").first().clone().html("");
        $(".main-groups").append(container);
    }
    $(".main-groups div:last").append(span.text(groups[i]));
}

let toDisplay = localStorage.getItem("badge");
localStorage.removeItem("badge");
if (toDisplay) {
    toggleGroup(toDisplay);
}

$(document).on("click", ".badge", function () {
    let toDisplay = $(this).html();
    toggleGroup(toDisplay);
});


/**
 *        Trick Thumbnail Scroll Loading
 **/


let mainTrick = $(".main-trick");
// Adding Thumbnail Tricks
if (mainTrick.length > 0) // Define the concerning page
{
    // Show more thumbnails when scrolling at the bottom of the page
    let n = 16;
    let $spinner = $(".fa-spinner");

    mainTrick.slice(0, n).css("display", "block");

    $(window).scroll(function () {
        if (n < mainTrick.length && $(window).scrollTop() >= $(document).height() - $(window).height() - 1) {
            $spinner.show();
            setTimeout(function () {
                n += 4;
                $(".main-trick").slice(0, n).css("display", "block");
                trickThumbnailHeight();
                displayArrowUp();
                $spinner.hide();
            }, 200 );
        }
    });
}

$(function(){
    $("[data-hide]").on("click", function(){
        $("." + $(this).attr("data-hide")).hide(500, 0);
    });
});

// Scrolling animation for the fa-arrow-up element
$(".fa-arrow-up").click(function () {
    $("html, body").animate({scrollTop: $("#top").offset().top - 20}, 500);
});


$( document ).ready(function() {
    trickThumbnailHeight();
});
