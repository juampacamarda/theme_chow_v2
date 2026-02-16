<?php
/**
 * Demo Pastelería — Funciones Específicas
 * 
 * Carga cambios de UI/UX, JS específico y configuraciones
 * Solo se ejecuta si el demo está activo
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Inicialización del demo Pastelería
 * Se ejecuta automáticamente desde demos/loader.php
 */
function chow_demo_pasteleria_init() {
    // Enqueuer JS específico del demo
    add_action( 'wp_enqueue_scripts', 'chow_demo_pasteleria_enqueue_scripts', 20 );
    
    // Agregar clase específica al carrusel
    add_filter( 'chow_slide_prod_classes', 'chow_demo_pasteleria_carrusel_class' );
}

/**
 * Enqueuer scripts específicos del demo
 */
function chow_demo_pasteleria_enqueue_scripts() {
    $js_url = get_template_directory_uri() . '/assets/js/demo-pasteleria.js';
    $js_path = get_template_directory() . '/assets/js/demo-pasteleria.js';
    
    wp_enqueue_script(
        'chow-demo-pasteleria',
        $js_url,
        array( 'jquery' ),
        file_exists( $js_path ) ? filemtime( $js_path ) : '1.0',
        true
    );
}

/**
 * Agregar clase específica al carrusel del demo
 */
function chow_demo_pasteleria_carrusel_class( $classes ) {
    return $classes . ' carrusel-pasteleria';
}
