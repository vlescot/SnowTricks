$(".badge").on("click", function () {
    localStorage.setItem("badge", $(this).html());
    window.location.replace(window.location.origin + "#tricks");
});
$('.imageGallery1 a').simpleLightbox();

function setPaginationDisabled(currentPage){
    let paginationPrevious = $("#pagination-previous");
    switch (currentPage) {
        case 1:     $(paginationPrevious).attr("class", "page-item disabled");      break;
        default:    $(paginationPrevious).attr("class", "page-item able-link");
    }

    let paginationNext = $("#pagination-next");
    switch (currentPage) {
        case nbPage:    $(paginationNext).attr("class", "page-item disabled");  break;
        default:    $(paginationNext).attr("class", "page-item able-link");
    }

    let pageItem = $(".page-item");

    pageItem.each(function () {
        let value = $(this).find("a").html();
        let pageItemId = $(this).attr('id');

        if (pageItemId !== "pagination-previous" && pageItemId !== "pagination-next") {
            switch (parseInt(value)) {
                case currentPage    :   $(this).attr("class", "page-item num-page disabled"); break;
                default             :   $(this).attr("class", "page-item num-page able-link");
            }
        }
    });
}

function setPagination (){
    let paginationList = $(".pagination");
    let numPage = $(".num-page");
    for (i = 2; i <= nbPage; i++) {
        let addNumPage = numPage.clone();
        addNumPage.find("a").html(i);
        $(paginationList).find("li:nth-last-child(2)").after(addNumPage);
    }
    setPaginationDisabled(currentPage);
}

function showPage(currentPage) {
    let commentContainers = $(".comment-container");
    commentContainers.hide();
    commentContainers.each(function(n) {
        if (n >= commentsByPage * (currentPage - 1) && n < commentsByPage * currentPage)
            $(this).show();
    });
    setPaginationDisabled(currentPage)
}

$(document).on("click", " .able-link", function () {
    if ($(this).attr("id") === "pagination-previous") {
        currentPage--;
    } else if ($(this).attr("id") === "pagination-next") {
        currentPage++;
    } else {
        currentPage = parseInt($(this).find("a").html());
    }
    showPage(currentPage);
});


$(document).ready(function () {
    let commentContainers = $(".comment-container");
    currentPage = 1;
    commentsByPage = 5;
    nbPage = Math.ceil(commentContainers.length / commentsByPage);

    setPagination();
    showPage(currentPage);

    $("#comments textarea").val('');
});