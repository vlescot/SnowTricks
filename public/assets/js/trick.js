$( document ).ready(function() {
    /**
     *        Trick Hover Image Animation
     **/
    $(".media").css("padding", "15px"); // Because this does't work with CSS for really unexpected reasons
    $(".container-fluid").css("padding-left", "+=20px").css("padding-right", "+=20px");
    $(".media img").hover(
        function () {
            $(this).closest(".media").animate({
                padding: '0px'
            }),
                $("img", this).animate({
                    top: '-15px',
                    left: '-15px',
                    height: '+=30px',
                    width: '+=30px',
                });
        },
        function () {
            $(this).closest(".media").animate({
                padding: '15px'
            }),
                $("img", this).animate({
                    top: '0px',
                    left: '0px',
                    height: '-=30px',
                    width: '-=30px',
                });
        }
    );
});