$( document ).ready(function() {

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
                setTimeout(function () {
                    $spinner.show();
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