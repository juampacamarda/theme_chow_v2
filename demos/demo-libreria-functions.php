<?php
/**
 * Chow Theme — Demo Librería Específico
 * 
 * Funciones y hooks solo para el demo Librería
 * Se carga condicionalmente desde demos/loader.php si la demo está activa
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Corregir URLs de menú después de importar demos
 * Mapea /shop, /cart, /checkout a las URLs correctas de WooCommerce
 * sin importar el slug (shop vs tienda según configuración)
 */
function chow_fix_demo_menu_links() {
    // Mapeo dinámico de URLs estándar
    $url_map = array(
        '/shop'     => get_permalink( wc_get_page_id('shop') ),
        '/cart'     => wc_get_cart_url(),
        '/checkout' => wc_get_checkout_url(),
    );
    
    // Obtener todos los menús y actualizar URLs
    $menus = wp_get_nav_menus();
    foreach ( $menus as $menu ) {
        $items = wp_get_nav_menu_items( $menu->term_id );
        if ( $items ) {
            foreach ( $items as $item ) {
                foreach ( $url_map as $old_url => $new_url ) {
                    // Comparar URL exacta o parcial
                    if ( $item->url === $old_url || strpos( $item->url, $old_url ) !== false ) {
                        wp_update_nav_menu_item(
                            $menu->term_id,
                            $item->ID,
                            array( 'menu-item-url' => $new_url )
                        );
                    }
                }
            }
        }
    }
}

/**
 * Inicializador del demo Librería
 * Engancha funciones específicas cuando se activa la demo
 */
function chow_demo_libreria_init() {
    // Ejecutar fix de URLs al cambiar tema o después de importar
    add_action( 'after_switch_theme', 'chow_fix_demo_menu_links' );
    add_action( 'chow_demo_imported', 'chow_fix_demo_menu_links' );
}
