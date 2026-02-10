
jQuery(document).ready(function ($) {

    AOS.init();

    $('.carousel').carousel({
        interval: 5000
    })
    //fin script carousels
    $(document).ready(function() {
        var $navbar = $("#menu-theme");
        

        
        AdjustHeader(); // Incase the user loads the page from halfway down (or something);
        $(window).scroll(function() {
            AdjustHeader();
        });
        
        function AdjustHeader(){
            if ($(window).scrollTop() > 160) {
            if (!$navbar.hasClass("fixed-top")) {
                $navbar.addClass("fixed-top animated fadeInDown");
                
                
            }
            } else {
            $navbar.removeClass("fixed-top animated fadeInDown");
            
            }
        }

    });

    $('#slide-prod').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots:true,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },
            1000:{
                items:5
            }
        }
    });
    $('#slide-clientes').owlCarousel({
        loop:true,
        margin:10,
        nav:false,
        dots:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },
            1000:{
                items:7
            }
        }
    });

    $(".btn-down").on("click", function (e) {
    // 1
    e.preventDefault();
    // 2
    const href = $(this).attr("href");
    // 3
    $("html, body").animate({ scrollTop: $(href).offset().top }, 800);
    });

    /*
    if ( !defined( 'WPCF7_LOAD_JS' ) ) {
        define( 'WPCF7_LOAD_JS', false );
    }
    */

    // Code that uses jQuery's $ can follow here.
})
