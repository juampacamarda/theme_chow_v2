/**
 * Demo Pastelería — JS Específico
 * 
 * Configuración de los carruseles Owl con 3 items para el demo
 * Solo se ejecuta si el demo está activo
 */

jQuery(document).ready(function ($) {
    // Carrusel de Tentaciones (#slide-prod)
    if ($('#slide-prod').hasClass('carrusel-pasteleria')) {
        // Si el carrusel ya estaba inicializado, destruir la instancia anterior
        if ($('#slide-prod').hasClass('owl-loaded')) {
            $('#slide-prod').trigger('destroy.owl.carousel');
        }
        
        // Reinitializar Owl Carousel con 5 items en desktop (tentaciones)
        $('#slide-prod').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 5  // 5 items en desktop para carrusel de tentaciones
                }
            }
        });
    }
    
    // Carrusel de Productos Dinámicos (.productos-carousel)
    if ($('.productos-carousel').hasClass('productos-carousel-pasteleria')) {
        // Si el carrusel ya estaba inicializado, destruir la instancia anterior
        if ($('.productos-carousel').hasClass('owl-loaded')) {
            $('.productos-carousel').trigger('destroy.owl.carousel');
        }
        
        // Reinitializar Owl Carousel con 3 items en desktop (productos dinámicos)
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
                    items: 3  // 3 items en desktop para pastelería
                }
            }
        });
    }
});
