<?php
/**
 * Chow Theme — Demo Loader
 * 
 * Carga condicionalmente funciones específicas de cada demo
 * Solo se cargan si la demo está marcada como activa en la BD
 * 
 * Contrato: cada demo debe tener un archivo `demo-{id}-functions.php`
 * y una función `chow_demo_{id}_init()` que se ejecuta al cargar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Lista de demos disponibles
 * Añadir aquí nuevos demos cuando se creen
 */
$chow_available_demos = array(
    'libreria',
    'pasteleria',
    'yoga',
    'consultorio',
    'academia',
    'restaurante',
);

/**
 * Cargar archivos de demos activos y ejecutar sus hooks de inicialización
 */
foreach ( $chow_available_demos as $demo_id ) {
    $is_active = get_option( 'chow_demo_' . $demo_id . '_active' );
    
    if ( $is_active ) {
        $demo_functions_file = get_template_directory() . '/demos/demo-' . $demo_id . '-functions.php';
        
        if ( file_exists( $demo_functions_file ) ) {
            require_once $demo_functions_file;
            
            // Ejecutar función init si existe
            $init_function = 'chow_demo_' . $demo_id . '_init';
            if ( function_exists( $init_function ) ) {
                call_user_func( $init_function );
            }
        }
    }
}
