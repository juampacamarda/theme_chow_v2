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

    // === CARD FLIP: Soporte táctil, hover y accesibilidad ===
    (function() {
        var isTouch = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
        var flippedCards = {};

        $(document).on('click', '.chow-product-card-03', function(e) {
            var $card = $(this);
            var cardId = $card.attr('data-card-id') || $card.index();
            var $target = $(e.target);
            
            if ($target.is('a') || $target.is('button') || $target.closest('a, button, .add_to_cart_button').length) {
                return;
            }

            if (isTouch) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!flippedCards[cardId]) {
                    $card.addClass('is-flipped');
                    flippedCards[cardId] = true;
                    
                    $(document).one('click.cardFlip' + cardId, function(ev) {
                        if (!$(ev.target).closest('.chow-product-card-03').length) {
                            $card.removeClass('is-flipped');
                            flippedCards[cardId] = false;
                        }
                    });
                }
            }
        });

        $(document).on('keydown', '.chow-product-card-03', function(e) {
            var key = e.which || e.keyCode;
            if (key === 13 || key === 32) {
                e.preventDefault();
                e.stopPropagation();
                $(this).toggleClass('is-flipped');
            }
        });
    })();
    // === FIN CARD FLIP ===/

    /*
    if ( !defined( 'WPCF7_LOAD_JS' ) ) {
        define( 'WPCF7_LOAD_JS', false );
    }
    */

    // Code that uses jQuery's $ can follow here.

    // Inicializar carrusel de bloques de productos dinámicos
$('.productos-carousel').owlCarousel({
    loop: true,
    margin: 20,
    nav: true,
    dots: false,
    autoplay: false,
    responsive: {
        0: {
            items: 2,
            margin: 2
        },
        768: {
            items: 3
        },
        992: {
            items: 4
        }
    }
});

})
