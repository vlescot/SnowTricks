$(".badge").on("click", function () {
    localStorage.setItem("badge", $(this).html());
    window.location.replace(window.location.origin + "#tricks");
});
$('.imageGallery1 a').simpleLightbox();

function setPaginationDisabled(currentPage){
    paginationPrevious = $("#pagination-previous");
    switch (currentPage) {
        case 1:     $(paginationPrevious).attr("class", "page-item disabled");      break;
        default:    $(paginationPrevious).attr("class", "page-item able-link");
    }

    paginationNext = $("#pagination-next");
    switch (currentPage) {
        case nbPage:    $(paginationNext).attr("class", "page-item disabled");  break;
        default:    $(paginationNext).attr("class", "page-item able-link");
    }

    pageItem = $(".page-item");
    pageItem.each(function () {
        value = $(this).find("a").html();
        pageItemId = $(this).attr('id');
        if (pageItemId !== "pagination-previous" && pageItemId !== "pagination-next") {
            switch (parseInt(value)) {
                case currentPage    :   $(this).attr("class", "page-item num-page disabled"); break;
                default             :   $(this).attr("class", "page-item num-page able-link");
            }
        }
    });
}

function setPagination (){
    paginationList = $(".pagination");
    numPage = $(".num-page");
    for (i = 2; i <= nbPage; i++) {
        addNumPage = numPage.clone();
        addNumPage.find("a").html(i);
        $(paginationList).find("li:nth-last-child(2)").after(addNumPage);
    }
    setPaginationDisabled(currentPage);
}

function showPage(currentPage) {
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
    commentContainers = $(".comment-container");
    currentPage = 1;
    commentsByPage = 5;
    nbPage = Math.ceil(commentContainers.length / commentsByPage);

    setPagination();
    showPage(1);

    $("#comments textarea").val('');
});