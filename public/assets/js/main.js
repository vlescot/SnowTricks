$( document ).ready(function() {
    /**
     *        AJAX FUNCTIONS
     **/

    // Adding Thumbnail Tricks
    if ($(".main-tricks").length > 0)
    {
        $(window).scroll(function () {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
                $.post(
                    window.location.href,
                    "nb=5",
                    addingTricks,
                    "text"
                );
            }
        });
    }

    function addingTricks(data) {
        $(".main-tricks").append(data);
        trickThumbnailHeight();
        arrowUp();
    }


    /**
     * Height's Thumbnail Adjusting
     */
    function trickThumbnailHeight() {
        var trickHeight = [];
        $(".main-trick-header").each(function () {
            trickHeight.push($(this).height());
        });
        var maxHeight = Math.max.apply(Math, trickHeight);
        $(".main-trick-header").height(maxHeight);
    }
    trickThumbnailHeight();


    /**
     * 		AJAX LOADING SPINNER
     **/
    var $spinner = $('.fa-spinner').hide();
    $(document)
        .ajaxStart(function () { $spinner.show(); })
        .ajaxStop(function () { $spinner.hide(); });
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
    function arrowUp() {
        if ($(".main-trick").length >= 30) {
            $(".fa-arrow-up").css("display", "block");
        }
    }

    // Scrolling animation for the fa-arrow-up element
    $(".fa-arrow-up").click(function () {
        $('html, body').animate({scrollTop: $("#top").offset().top - 20}, 500);
    });
});