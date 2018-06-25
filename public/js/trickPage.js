$( document ).ready(function() {
    /**
     *        Trick Hover Image Animation
     **/
    $(".container-fluid").css("padding-left", "+=20px").css("padding-right", "+=20px");
    $(".media img").hover(
        function () {
            $(this).closest(".media").animate({
                padding: '0px'
            });
            $( this).animate({
                top: '-15px',
                left: '-15px',
                height: '+=30px',
                width: '+=30px',
            });
        },
        function () {
            $(this).closest(".media").animate({
                padding: '15px'
            });
            $( this).animate({
                top: '0px',
                left: '0px',
                height: '-=30px',
                width: '-=30px',
            });
        }
    );

    // Redirects to home page with local variable use to display the tricks with this group
    $(".badge").on("click", function () {
        localStorage.setItem("badge", $(this).html());
        window.location.replace(window.location.origin);
    });
});
