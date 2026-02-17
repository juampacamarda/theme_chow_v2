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
    add_action( 'wp_enqueue_scripts', 'chow_demo_pasteleria_shop_grid_styles', 30 );
    
    // Agregar clase específica al carrusel
    add_filter( 'chow_slide_prod_classes', 'chow_demo_pasteleria_carrusel_class' );

    // Grilla de tienda: 3 columnas solo para demo Pastelería
    add_filter( 'loop_shop_columns', 'chow_demo_pasteleria_shop_columns', 1000 );

    // Corregir orden del menú al importar este demo
    add_action( 'chow_demo_imported', 'chow_demo_pasteleria_fix_menu_order', 20 );
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

/**
 * Forzar 3 columnas en shop para demo Pastelería
 */
function chow_demo_pasteleria_shop_columns( $columns ) {
    return 3;
}

/**
 * Forzar 3 columnas visuales en shop/categorías para demo Pastelería
 */
function chow_demo_pasteleria_shop_grid_styles() {
    if ( ! function_exists( 'is_shop' ) ) {
        return;
    }

    if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
        return;
    }

    $css = '
    .woocommerce ul.products,
    .woocommerce-page ul.products {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 20px !important;
    }

    .woocommerce ul.products li.product,
    .woocommerce-page ul.products li.product {
        width: 100% !important;
        margin: 0 !important;
    }

    @media (max-width: 991px) {
        .woocommerce ul.products,
        .woocommerce-page ul.products {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 575px) {
        .woocommerce ul.products,
        .woocommerce-page ul.products {
            grid-template-columns: 1fr !important;
        }
    }
    ';

    wp_add_inline_style( 'theme-wc', $css );
}

/**
 * Asegurar orden de menú para demo Pastelería
 * Orden esperado: Inicio, Tienda, Sobre Nosotros, Preguntas Frecuentes, Contacto
 */
function chow_demo_pasteleria_fix_menu_order( $imported_demo_id = null ) {
    if ( null !== $imported_demo_id && 'pasteleria' !== $imported_demo_id ) {
        return;
    }

    $locations = get_nav_menu_locations();
    $menu_id = 0;

    if ( isset( $locations['superior'] ) ) {
        $menu_id = (int) $locations['superior'];
    }

    if ( ! $menu_id ) {
        $menu = get_term_by( 'name', 'Menú Principal', 'nav_menu' );
        if ( $menu && ! is_wp_error( $menu ) ) {
            $menu_id = (int) $menu->term_id;
        }
    }

    if ( ! $menu_id ) {
        return;
    }

    $items = wp_get_nav_menu_items( $menu_id );
    if ( empty( $items ) ) {
        return;
    }

    $map = array(
        'home' => array( 'inicio', 'home' ),
        'shop' => array( 'tienda' ),
        'about' => array( 'sobre nosotros' ),
        'faq' => array( 'preguntas frecuentes' ),
        'contact' => array( 'contacto' ),
    );

    $target_ids = array();

    foreach ( $items as $item ) {
        $title = trim( wp_strip_all_tags( $item->title ) );
        $title = function_exists( 'mb_strtolower' ) ? mb_strtolower( $title, 'UTF-8' ) : strtolower( $title );

        foreach ( $map as $slot => $labels ) {
            if ( isset( $target_ids[ $slot ] ) ) {
                continue;
            }

            if ( in_array( $title, $labels, true ) ) {
                $target_ids[ $slot ] = (int) $item->ID;
                break;
            }
        }
    }

    $ordered_ids = array();
    foreach ( array( 'home', 'shop', 'about', 'faq', 'contact' ) as $slot ) {
        if ( isset( $target_ids[ $slot ] ) ) {
            $ordered_ids[] = $target_ids[ $slot ];
        }
    }

    if ( empty( $ordered_ids ) ) {
        return;
    }

    $position = 1;
    foreach ( $ordered_ids as $menu_item_id ) {
        wp_update_post(
            array(
                'ID' => $menu_item_id,
                'menu_order' => $position,
            )
        );
        $position++;
    }

    // Mantener el resto de ítems sin perderlos, colocándolos después
    foreach ( $items as $item ) {
        if ( in_array( (int) $item->ID, $ordered_ids, true ) ) {
            continue;
        }

        wp_update_post(
            array(
                'ID' => (int) $item->ID,
                'menu_order' => $position,
            )
        );
        $position++;
    }
}
