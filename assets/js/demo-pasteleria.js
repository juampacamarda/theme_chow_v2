/**
 * Demo Pastelería — JS Específico
 * 
 * Configuración del carrusel Owl con 3 items para el demo
 * Solo se ejecuta si el demo está activo
 */

jQuery(document).ready(function ($) {
    // Detectar si el carrusel tiene la clase pasteleria
    if ($('#slide-prod').hasClass('carrusel-pasteleria')) {
        // Si el carrusel ya estaba inicializado, destruir la instancia anterior
        if ($('#slide-prod').hasClass('owl-loaded')) {
            $('#slide-prod').trigger('destroy.owl.carousel');
        }
        
        // Reinitializar Owl Carousel con 3 items en desktop
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
                1000: {
                    items: 3  // 3 columnas en desktop para pastelería
                }
            }
        });
    }
});
