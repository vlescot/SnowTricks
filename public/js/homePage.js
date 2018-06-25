$( document ).ready(function() {

    /**
     * Search
     */
    function listing (element){
        let arr = [];
        // let firstObj = $(this).first().text();
        element.each(function () {
            let obj = $(this).text().trim();
            if (obj !== "Tous" && $.inArray(obj, arr) === -1) {
                arr.push(obj);
            }
        });
        return arr;
    }

    let tricks = listing($(".main-trick-title"));
    let groups = listing($(".badge"));



    $("#search").autocomplete({
        source : tricks
    });

    function redirectToTrick(){
        let search = $("#search").val().trim().toLowerCase();
        search = search[0].toUpperCase() + search.substr(1);
        if ($.inArray(search,tricks) !== -1){
            window.location.replace(window.location.origin + '/' + search);
        } else {
            $("#search").val("");
            $("#search-warning").show(400);
            window.setTimeout(function() {
                $("#search-warning").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        }
    }

    $("#submit").on("click", function () {
        redirectToTrick();
    });
    $("#search").on("keypress", function ( event ) {
        if ( event.which === 13 ) {
            event.preventDefault();
            redirectToTrick();
        }
    });



    // Display badge with each groups
    groups.sort();
    let i;
    for (i = 0; i < groups.length; ++i) {
        let span = $('<span>', { class: "badge"});
        $(".main-groups div").append(span.text(groups[i]));
    }

    // Toggle the groups after a clock on badge
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
                    }else {
                        if (display !== true)
                            display = false;
                    }
                });
                if (display === true){
                    $(this).closest(".main-tricks>div").show();
                }else {
                    $(this).closest(".main-tricks>div").hide();
                }
            });
        }
    }

    let toDisplay = localStorage.getItem("badge");
    localStorage.removeItem("badge");
    if (toDisplay) {
        toggleGroup(toDisplay);
    }


    $(".badge").on("click", function () {
        let toDisplay = $(this).html();
        toggleGroup(toDisplay);
    });


    /**
     *        Trick Thumbnail Scroll Loading
     **/

    // Adding Thumbnail Tricks
    if ($(".main-trick").length > 0) // Define the concerning page
    {
        // Show more thumbnails when scrolling at the bottom of the page
        let n = 16;
        let $spinner = $('.fa-spinner');

        $(".main-trick").slice(0, n).css("display", "block");

        $(window).scroll(function () {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 1) {
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
    trickThumbnailHeight();


    /**
     *        Effects on trick hover Thumbnail
     **/
    $(document).on({
        mouseenter: function () {
            $(".img", this).animate({
                left: '-10px',
                top: '-10px',
                marginBottom: '-20px',
                height: '+=20px',
                width: '+=20px',
            });
        },
        mouseleave: function () {
            $(".img", this).animate({
                left: '0px',
                top: '0px',
                marginBottom: '0px',
                height: '-=20px',
                width: '-=20px',
            });
        }
    }, '.main-trick a');

    /**
     *        ARROW UP
     **/
    // Display the fa-arrow-up element if >= 15 tricks shown
    function displayArrowUp() {
        if ($(".main-trick").length >= 30) {
            $(".fa-arrow-up").css("display", "block");
        }
    }

    // Scrolling animation for the fa-arrow-up element
    $(".fa-arrow-up").click(function () {
        $('html, body').animate({scrollTop: $("#top").offset().top - 20}, 500);
    });
});