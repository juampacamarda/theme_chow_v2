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
function chow_fix_demo_menu_links( $imported_demo_id = null ) {
    if ( null !== $imported_demo_id && 'libreria' !== $imported_demo_id ) {
        return;
    }

    if ( ! function_exists( 'wc_get_page_id' ) || ! function_exists( 'wc_get_cart_url' ) || ! function_exists( 'wc_get_checkout_url' ) ) {
        return;
    }

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
                    if ( empty( $new_url ) || empty( $item->url ) ) {
                        continue;
                    }

                    // Comparar URL exacta o parcial
                    if ( $item->url === $old_url || strpos( $item->url, $old_url ) !== false ) {
                        $item_type = ! empty( $item->type ) ? $item->type : 'custom';

                        $item_args = array(
                            'menu-item-title' => $item->title,
                            'menu-item-position' => isset( $item->menu_order ) ? (int) $item->menu_order : 0,
                            'menu-item-parent-id' => isset( $item->menu_item_parent ) ? (int) $item->menu_item_parent : 0,
                            'menu-item-status' => 'publish',
                        );

                        if ( 'post_type' === $item_type ) {
                            $item_args['menu-item-type'] = 'post_type';
                            $item_args['menu-item-object'] = ! empty( $item->object ) ? $item->object : 'page';
                            $item_args['menu-item-object-id'] = isset( $item->object_id ) ? (int) $item->object_id : 0;
                        } else {
                            $item_args['menu-item-type'] = 'custom';
                            $item_args['menu-item-url'] = $new_url;
                        }

                        wp_update_nav_menu_item(
                            $menu->term_id,
                            $item->ID,
                            $item_args
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
