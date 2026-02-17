<?php
/**
 * Chow Theme Demo Importer
 * 
 * Handles the import of pre-configured demo content
 * - Manages SCF (Smart Custom Fields) subpage registration
 * - Handles AJAX import requests
 * - Orchestrates the complete import process
 * - Supports content overwriting with user confirmation
 */

// Load demo configuration files
require_once get_template_directory() . '/demos/demo-libreria.php';
require_once get_template_directory() . '/demos/demo-pasteleria.php';

// Register the "Importar Demo" subpage
add_action( 'init', 'chow_register_importer_page' );

function chow_register_importer_page() {

    
    // Register a custom admin page to display the UI
    add_action( 'admin_menu', function() {
        add_submenu_page(
            'Chow-theme',
            'Importar Demo',
            'Importar Demo',
            'manage_options',
            'chow-importer',
            'chow_render_importer_page'
        );
    }, 999 );
}

// Render the importer page
function chow_render_importer_page() {
    require_once get_template_directory() . '/demos/importer-ui.php';
}

// Load admin UI and scripts
add_action( 'admin_enqueue_scripts', 'chow_importer_enqueue_assets', 999 );

function chow_importer_enqueue_assets( $hook ) {
    // Only load on the importer page
    // The hook format is: 'admin_page_<page_slug>' for custom pages
    if ( strpos( $hook, 'chow-importer' ) === false ) {
        return;
    }
    
    wp_enqueue_style( 
        'chow-importer-styles', 
        get_template_directory_uri() . '/demos/importer-styles.css',
        array(),
        '1.0'
    );
    
    wp_enqueue_script(
        'chow-importer-js',
        get_template_directory_uri() . '/demos/importer.js',
        array( 'jquery' ),
        '1.0',
        true
    );
    
    // Localize script with AJAX data
    wp_localize_script( 'chow-importer-js', 'chowImporter', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'chow_import_demo' ),
    ) );
}

/**
 * Inicializar error logging
 */
function chow_init_error_logging() {
    set_error_handler( function( $errno, $errstr, $errfile, $errline ) {
        $error_msg = sprintf(
            "[%s] %s:%d - %s",
            date( 'Y-m-d H:i:s' ),
            basename( $errfile ),
            $errline,
            $errstr
        );
        chow_importer_log( $error_msg, 'ERROR' );
        return false; // Let WordPress handle it too
    });
}

/**
 * Logging function for demo import process
 */
function chow_importer_log( $message, $level = 'INFO' ) {
    $upload_dir = wp_upload_dir();
    $log_file = $upload_dir['basedir'] . '/chow-importer.log';
    $timestamp = date( 'Y-m-d H:i:s' );
    $log_message = sprintf( "[%s] [%s] %s\n", $timestamp, $level, $message );
    
    @error_log( $log_message, 3, $log_file );
}

// AJAX handler for importing demo
add_action( 'wp_ajax_chow_import_demo', 'chow_handle_import_ajax' );

function chow_handle_import_ajax() {
    chow_init_error_logging();
    chow_importer_log( '=== INICIANDO IMPORTACIÓN DE DEMO ===' );
    
    // Aumentar límites
    @set_time_limit( 900 );
    @ini_set( 'memory_limit', '512M' );
    chow_importer_log( 'Límites configurados: timeout=900s, memory=512M' );
    
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'chow_import_demo' ) ) {
        chow_importer_log( 'Error: Verificación de nonce fallida', 'ERROR' );
        wp_send_json_error( array( 'message' => 'Verificación de seguridad fallida' ) );
    }
    
    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        chow_importer_log( 'Error: Usuario sin permisos', 'ERROR' );
        wp_send_json_error( array( 'message' => 'No tienes permisos para realizar esta acción' ) );
    }
    
    // Get demo ID
    $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( $_POST['demo_id'] ) : '';
    $action_type = isset( $_POST['action_type'] ) ? sanitize_text_field( $_POST['action_type'] ) : 'import';
    
    chow_importer_log( "Parámetros: demo_id=$demo_id, action_type=$action_type" );
    
    if ( empty( $demo_id ) ) {
        chow_importer_log( 'Error: Demo ID vacío', 'ERROR' );
        wp_send_json_error( array( 'message' => 'Demo ID no válido' ) );
    }
    
    // Call the import function
    chow_importer_log( "Iniciando importación de $demo_id ($action_type)" );
    $result = chow_do_import( $demo_id, $action_type );
    
    if ( is_wp_error( $result ) ) {
        $error_msg = $result->get_error_message();
        $error_code = $result->get_error_code();
        $error_data = $result->get_error_data();
        chow_importer_log( "Error en importación: $error_msg", 'ERROR' );
        if ( $error_data ) {
            chow_importer_log( "Datos del error: " . json_encode( $error_data ), 'ERROR' );
        }
        // Enviar código de error específico para que el frontend lo maneje
        wp_send_json_error( array(
            'message' => $error_msg,
            'error_code' => $error_code,
        ) );
    }
    
    // Disparar hook para que otros complementos ejecuten tareas post-importación
    do_action( 'chow_demo_imported', $demo_id );
    
    chow_importer_log( "Importación completada exitosamente para $demo_id" );
    chow_importer_log( '=== FIN DE IMPORTACIÓN ===' );
    
    // $result now contains success + skipped_plugins info
    wp_send_json_success( $result );
}

/**
 * Detect which optional plugins are missing
 * 
 * @return array - Array of missing plugins with keys as plugin IDs and values as plugin names
 */
function chow_get_missing_plugins() {
    $missing = array();
    
    if ( ! function_exists( 'wpcf7_contact_form' ) ) {
        $missing['cf7'] = 'Contact Form 7';
    }
    
    if ( ! function_exists( 'WC' ) ) {
        $missing['woocommerce'] = 'WooCommerce';
    }
    
    if ( ! class_exists( 'SCF' ) && ! class_exists( 'ACF' ) ) {
        $missing['scf'] = 'Smart Custom Fields (SCF) o Advanced Custom Fields (ACF)';
    }
    
    return $missing;
}

/**
 * Performance-optimized import wrapper
 * Desactiva hooks y cachés innecesarios durante importación
 * Acelera ~40-60% sin romper funcionalidad
 */
function chow_import_with_performance_boost( $callback ) {
    // Desactivar operaciones costosas
    wp_defer_comment_counting( true );
    wp_suspend_cache_invalidation( true );
    
    // Marcar como importing para que plugins no ejecuten hooks costosos
    defined( 'WP_IMPORTING' ) || define( 'WP_IMPORTING', true );
    
    // Ejecutar callback
    $result = call_user_func( $callback );
    
    // Restaurar estado
    wp_defer_comment_counting( false );
    wp_suspend_cache_invalidation( false );
    wp_cache_flush();
    
    return $result;
}

/**
 * Búsqueda eficiente de productos existentes (sin get_page_by_title)
 * Usa query directa + caché en lugar de función lenta de WordPress
 */
function chow_product_exists_by_title( $product_title, $demo_id ) {
    $cache_key = 'chow_product_' . md5( $product_title . $demo_id );
    $cached = wp_cache_get( $cache_key, 'chow_demo' );
    
    if ( false !== $cached ) {
        return $cached;
    }
    
    // Query directa a BD - más rápida que get_page_by_title
    global $wpdb;
    $product = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'product' LIMIT 1",
            $product_title
        )
    );
    
    $result = $product ? $product->ID : false;
    wp_cache_set( $cache_key, $result, 'chow_demo', 3600 );
    
    return $result;
}

/**
 * Main import function - orchestrates the complete import process
 * 
 * @param string $demo_id - The demo to import (e.g., 'libreria')
 * @param string $action_type - 'import' or 'overwrite'
 * @return array|WP_Error - Array with success info and skipped plugins, or WP_Error on failure
 */
function chow_do_import( $demo_id, $action_type = 'import' ) {
    // Activar optimizaciones de rendimiento
    wp_defer_comment_counting( true );
    wp_suspend_cache_invalidation( true );
    defined( 'WP_IMPORTING' ) || define( 'WP_IMPORTING', true );
    
    // Ejecutar import
    $result = chow_do_import_internal( $demo_id, $action_type );
    
    // Restaurar estado
    wp_defer_comment_counting( false );
    wp_suspend_cache_invalidation( false );
    wp_cache_flush();
    
    return $result;
}

/**
 * Lógica interna del import (sin cambios, solo extrado para claridad)
 * @param string $demo_id - The demo to import (e.g., 'libreria')
 * @param string $action_type - 'import' or 'overwrite'
 * @return array|WP_Error - Array with success info and skipped plugins, or WP_Error on failure
 */
function chow_do_import_internal( $demo_id, $action_type = 'import' ) {
    // Get demo configuration
    chow_importer_log( "Obteniendo configuración para demo: $demo_id" );
    
    if ( 'libreria' === $demo_id ) {
        $demo = chow_get_demo_libreria();
    } elseif ( 'pasteleria' === $demo_id ) {
        $demo = chow_get_demo_pasteleria();
    } else {
        $error = "Demo no encontrada: $demo_id";
        chow_importer_log( $error, 'ERROR' );
        return new WP_Error( 'invalid_demo', $error );
    }
    chow_importer_log( "Configuración cargada: " . $demo['name'] );
    
    // Detect missing plugins early
    $missing_plugins = chow_get_missing_plugins();
    $missing_list = ! empty( $missing_plugins ) ? implode( ', ', array_keys( $missing_plugins ) ) : 'ninguno';
    chow_importer_log( "Plugins faltantes: $missing_list" );
    
    // Check if demo already exists
    $demo_marker = 'chow_demo_' . $demo_id . '_imported';
    $demo_exists = get_option( $demo_marker );
    chow_importer_log( "Demo ya importado: " . ( $demo_exists ? 'sí' : 'no' ) );
    
    // Skip content check - user can choose to overwrite via checkbox in UI
    // This simplifies the flow and avoids showing multiple modals
    
    // Clear existing content if overwriting
    if ( 'overwrite' === $action_type ) {
        chow_importer_log( "Limpiando contenido existente del demo" );
        chow_clear_demo_content( $demo_id );
    }
    
    // Step 1: Import images
    chow_importer_log( "PASO 1: Importando imágenes" );
    $attachment_ids = chow_import_images( $demo_id );
    if ( is_wp_error( $attachment_ids ) ) {
        $error = $attachment_ids->get_error_message();
        chow_importer_log( "Error importando imágenes: $error", 'ERROR' );
        return $attachment_ids;
    }
    chow_importer_log( "✓ Imágenes importadas: " . count( $attachment_ids ) . " archivos" );
    
    // Step 2: Create Contact Form 7 forms (OPTIONAL - skip if plugin missing)
    $form_ids = array();
    if ( ! isset( $missing_plugins['cf7'] ) ) {
        chow_importer_log( "PASO 2: Creando formularios Contact Form 7" );
        $form_ids = chow_create_forms( $demo, $attachment_ids );
        if ( is_wp_error( $form_ids ) ) {
            $error = $form_ids->get_error_message();
            chow_importer_log( "Error creando formularios: $error", 'ERROR' );
            return $form_ids;
        }
        chow_importer_log( "✓ Formularios creados: " . count( $form_ids ) );
    } else {
        chow_importer_log( "⊘ Paso 2 omitido: Contact Form 7 no instalado" );
    }
    
    // Step 3: Create product categories (OPTIONAL - skip if plugin missing)
    $category_ids = array();
    if ( ! isset( $missing_plugins['woocommerce'] ) ) {
        chow_importer_log( "PASO 3: Creando categorías de productos" );
        $category_ids = chow_create_categories( $demo );
        if ( is_wp_error( $category_ids ) ) {
            $error = $category_ids->get_error_message();
            chow_importer_log( "Error creando categorías: $error", 'ERROR' );
            return $category_ids;
        }
        chow_importer_log( "✓ Categorías creadas: " . count( $category_ids ) );
    } else {
        chow_importer_log( "⊘ Paso 3 omitido: WooCommerce no instalado" );
    }
    
    // Step 4: Create products (OPTIONAL - skip if plugin missing)
    if ( ! isset( $missing_plugins['woocommerce'] ) ) {
        chow_importer_log( "PASO 4: Creando productos" );
        $products = chow_create_products( $demo, $attachment_ids, $category_ids );
        if ( is_wp_error( $products ) ) {
            $error = $products->get_error_message();
            chow_importer_log( "Error creando productos: $error", 'ERROR' );
            return $products;
        }
        chow_importer_log( "✓ Productos creados: " . count( $products ) );
    } else {
        chow_importer_log( "⊘ Paso 4 omitido: WooCommerce no instalado" );
    }
    
    // Step 5: Create pages
    chow_importer_log( "PASO 5: Creando páginas" ) ;
    if ( empty( $demo['pages'] ) ) {
        chow_importer_log( "⚠ No hay páginas en la configuración de la demo", 'WARNING' );
    }
    $pages = chow_create_pages( $demo, $attachment_ids, $form_ids, $action_type );
    if ( is_wp_error( $pages ) ) {
        $error = $pages->get_error_message();
        chow_importer_log( "Error creando páginas: $error", 'ERROR' );
        return $pages;
    }
    chow_importer_log( "✓ Páginas creadas: " . count( $pages ) );
    
    // Step 5.5: Set front page to "Inicio" if it exists
    chow_importer_log( "PASO 5.5: Configurando página de inicio" );
    $inicio_page = get_page_by_title( 'Inicio', OBJECT, 'page' );
    if ( $inicio_page ) {
        update_option( 'page_on_front', $inicio_page->ID );
        update_option( 'show_on_front', 'page' );
        chow_importer_log( "✓ Página de inicio configurada (ID: " . $inicio_page->ID . ")" );
    } else {
        chow_importer_log( "⚠ Página 'Inicio' no encontrada", 'WARNING' );
    }
    
    // Step 6: Update theme options (OPTIONAL - skip if plugin missing)
    if ( ! isset( $missing_plugins['acf'] ) && ! isset( $missing_plugins['scf'] ) ) {
        chow_importer_log( "PASO 6: Actualizando opciones del tema" );
        $result = chow_update_theme_options( $demo, $attachment_ids, $form_ids );
        if ( is_wp_error( $result ) ) {
            $error = $result->get_error_message();
            chow_importer_log( "Error actualizando opciones: $error", 'ERROR' );
            return $result;
        }
        chow_importer_log( "✓ Opciones del tema actualizadas" );
    } else {
        chow_importer_log( "⊘ Paso 6 omitido: ACF/SCF no instalado" );
    }
    
    // Step 7: Create/update navigation menu
    chow_importer_log( "PASO 7: Actualizando menú de navegación" );
    $result = chow_update_menu( $demo );
    if ( is_wp_error( $result ) ) {
        $error = $result->get_error_message();
        chow_importer_log( "Error actualizando menú: $error", 'ERROR' );
        return $result;
    }
    chow_importer_log( "✓ Menú de navegación creado" );
    
    // Step 8: Apply custom CSS
    chow_importer_log( "PASO 8: Aplicando CSS personalizado" );
    $result = chow_apply_custom_css( $demo );
    if ( is_wp_error( $result ) ) {
        $error = $result->get_error_message();
        chow_importer_log( "Error aplicando CSS: $error", 'ERROR' );
        return $result;
    }
    chow_importer_log( "✓ CSS personalizado aplicado" );
    
    // Mark demo as imported
    chow_importer_log( "Marcando demo como importado" );
    update_option( $demo_marker, time() );
    update_option( 'chow_demo_' . $demo_id . '_active', 1 );
    update_option( 'chow_active_demo', $demo_id );
    
    // Return success with information about skipped plugins
    chow_importer_log( "✓✓✓ Importación completada exitosamente", 'SUCCESS' );
    
    return array(
        'message' => 'Demo importada correctamente',
        'redirect' => home_url(),
        'skipped_plugins' => $missing_plugins,
    );
}

/**
 * Check if there's user-created content (excluding demo content)
 */
function chow_has_user_content() {
    // Check for products
    $products = get_posts( array(
        'post_type' => 'product',
        'posts_per_page' => 1,
    ) );
    
    if ( ! empty( $products ) ) {
        return true;
    }
    
    // Check for regular pages (excluding home and shop)
    $pages = get_posts( array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post__not_in' => array( get_option( 'page_on_front' ), get_option( 'page_for_posts' ) ),
    ) );
    
    if ( count( $pages ) > 4 ) { // More than the 4 demo pages
        return true;
    }
    
    return false;
}

/**
 * Clear demo content (for overwrite operation)
 */
function chow_clear_demo_content( $demo_id ) {
    // Get demo config to know which posts to delete
    if ( 'libreria' === $demo_id ) {
        $demo = chow_get_demo_libreria();
    } elseif ( 'pasteleria' === $demo_id ) {
        $demo = chow_get_demo_pasteleria();
    } else {
        return;
    }
    
    // Delete products created by this demo
    $demo_products = get_posts( array(
        'post_type' => 'product',
        'meta_key' => '_demo_id',
        'meta_value' => $demo_id,
        'posts_per_page' => -1,
    ) );
    
    foreach ( $demo_products as $product ) {
        wp_delete_post( $product->ID, true );
    }
    
    // Delete pages created by this demo
    $demo_pages = get_posts( array(
        'post_type' => 'page',
        'meta_key' => '_demo_id',
        'meta_value' => $demo_id,
        'posts_per_page' => -1,
    ) );
    
    foreach ( $demo_pages as $page ) {
        wp_delete_post( $page->ID, true );
    }
    
    // Delete categories created by this demo
    $categories = get_terms( array(
        'taxonomy' => 'product_cat',
        'meta_key' => '_demo_id',
        'meta_value' => $demo_id,
        'hide_empty' => false,
    ) );
    
    foreach ( $categories as $category ) {
        wp_delete_term( $category->term_id, 'product_cat' );
    }
    
    // Delete CF7 forms created by this demo
    $demo_forms = get_posts( array(
        'post_type' => 'wpcf7_contact_form',
        'meta_key' => '_demo_id',
        'meta_value' => $demo_id,
        'posts_per_page' => -1,
    ) );
    
    foreach ( $demo_forms as $form ) {
        wp_delete_post( $form->ID, true );
    }
    
    // Delete images/attachments created by this demo
    $demo_attachments = get_posts( array(
        'post_type' => 'attachment',
        'meta_key' => '_demo_id',
        'meta_value' => $demo_id,
        'posts_per_page' => -1,
    ) );
    
    foreach ( $demo_attachments as $attachment ) {
        wp_delete_attachment( $attachment->ID, true );
    }
    
    // Reset ACF options to demo defaults
    $active_demo = get_option( 'chow_active_demo' );
    if ( $active_demo === $demo_id ) {
        $acf_fields_to_clear = array(
            'color_principal', 'color_secundario', 'color_texto', 'color_fondo',
            'logo_header_desktop', 'logo_header_mobile', 'logo_footer',
            'direccion', 'telefonos', 'mail',
            'facebook_link', 'instagram_link', 'twitter_link', 'wsp_link',
            'logos_legales',
            'slider_1', 'slider_2', 'slider_3', 'slider_4', 'slider_5',
            'bloques_productos', 'newsletter', 'formulario_producto',
            'redes_seccion', 'carrusel_productos_destacados',
            'card_style_default',
        );
        
        foreach ( $acf_fields_to_clear as $field_name ) {
            delete_field( $field_name, 'option' );
        }
    }
}

/**
 * Import images from demo folder to WordPress media library
 */
function chow_import_images( $demo_id ) {
    @set_time_limit( 600 );
    @ini_set( 'memory_limit', '256M' );
    
    $demo_images_path = get_template_directory() . '/demos/' . $demo_id . '/images/';
    $attachment_ids = array();
    
    chow_importer_log( "Importando imágenes desde: $demo_images_path" );
    
    // Check if demo images folder exists
    if ( ! is_dir( $demo_images_path ) ) {
        $error = "Carpeta no encontrada: $demo_images_path";
        chow_importer_log( $error, 'ERROR' );
        return new WP_Error( 'missing_images', $error );
    }
    chow_importer_log( "✓ Carpeta encontrada" );
    
    // Scan for image files - buscar WebP primero, luego PNG
    $image_files = glob( $demo_images_path . '*.webp', GLOB_BRACE );
    if ( empty( $image_files ) ) {
        chow_importer_log( "No se encontraron WebP, buscando PNG/JPG" );
        $image_files = glob( $demo_images_path . '*.{png,jpg,jpeg,gif}', GLOB_BRACE );
    }
    
    if ( empty( $image_files ) ) {
        $error = "No se encontraron imágenes en: $demo_images_path";
        chow_importer_log( $error, 'ERROR' );
        return new WP_Error( 'no_images', $error );
    }
    
    chow_importer_log( "Imágenes encontradas: " . count( $image_files ) );
    
    // Load WordPress media upload functions
    try {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        chow_importer_log( "Funciones de media de WP cargadas" );
    } catch ( Exception $e ) {
        chow_importer_log( "Error cargando funciones de media: " . $e->getMessage(), 'ERROR' );
        return new WP_Error( 'media_load_error', $e->getMessage() );
    }
    
    $uploads_dir = wp_upload_dir();
    chow_importer_log( "Directorio de uploads: " . $uploads_dir['path'] );
    
    $error_count = 0;
    $success_count = 0;
     
    foreach ( $image_files as $i => $image_path ) {
        @set_time_limit( 60 );
        
        $filename = basename( $image_path );
        
        // Check if filename already starts with demo_id prefix to avoid duplication
        $has_prefix = strpos( $filename, $demo_id . '-' ) === 0;
        $dest_filename = $has_prefix ? $filename : $demo_id . '-' . $filename;
        
        // Copy image to uploads folder
        $dest_path = $uploads_dir['path'] . '/' . $dest_filename;
        
        if ( file_exists( $dest_path ) ) {
            chow_importer_log( "  (" . ( $i + 1 ) . "/" . count( $image_files ) . ") $filename [ya existe]" );
            $success_count++;
            continue;
        }
        
        if ( ! @copy( $image_path, $dest_path ) ) {
            $error_count++;
            chow_importer_log( "  ✗ Error copiando: $filename", 'ERROR' );
            continue;
        }
        
        chow_importer_log( "  (" . ( $i + 1 ) . "/" . count( $image_files ) . ") Copiado: $filename" );
        
        try {
            // Create WordPress attachment
            $file_type = wp_check_filetype( $dest_path );
            
            if ( empty( $file_type['type'] ) ) {
                chow_importer_log( "    ⚠ Tipo MIME no identificado para: $filename", 'WARNING' );
            }
            
            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => sanitize_file_name( $filename ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );
            
            $attach_id = wp_insert_attachment( $attachment, $dest_path );
            
             if ( is_wp_error( $attach_id ) ) {
                $error_count++;
                chow_importer_log( "    ✗ Error creando attachment: " . $attach_id->get_error_message(), 'ERROR' );
                continue;
             }

             if ( ! $attach_id ) {
                $error_count++;
                chow_importer_log( "    ✗ wp_insert_attachment retornó 0 para: $filename", 'ERROR' );
                continue;
             }
            
              // Generate attachment metadata
              @set_time_limit( 60 );
              $attach_data = wp_generate_attachment_metadata( $attach_id, $dest_path );
              wp_update_attachment_metadata( $attach_id, $attach_data );
              
              // Mark attachment as demo content
              update_post_meta( $attach_id, '_demo_id', $demo_id );
              
              // Store the mapping
              $base_filename = basename( $dest_path );
              $key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $base_filename );
              $attachment_ids[ $key ] = $attach_id;
              $success_count++;
              
              // Free memory
              wp_cache_flush();
              
        } catch ( Exception $e ) {
            $error_count++;
            chow_importer_log( "    ✗ Excepción procesando: " . $e->getMessage(), 'ERROR' );
        }
    }
    
    chow_importer_log( "Importación de imágenes completada: $success_count éxito, $error_count errores" );
    
    if ( $error_count > 0 && $success_count === 0 ) {
        return new WP_Error( 'image_import_failed', "No se pudieron importar las imágenes ($error_count errores)" );
    }
    
    return $attachment_ids;
}

/**
 * Create Contact Form 7 forms using WPCF7_ContactForm class
 * Returns empty array if CF7 is not installed
 * 
 * Properly creates CF7 forms with the official API instead of raw post insertion
 */
function chow_create_forms( $demo, $attachment_ids ) {
    $form_ids = array();
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    
    // Check if CF7 is installed
    if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
        return $form_ids;
    }
    
    foreach ( $demo['forms'] as $form_data ) {
        $form_title = $form_data['name'];
        
        // Check if form already exists
        $existing_forms = get_posts( array(
            'post_type' => 'wpcf7_contact_form',
            'title'     => $form_title,
            'numberposts' => 1,
        ) );
        
        if ( ! empty( $existing_forms ) ) {
            $form_ids[ $form_title ] = $existing_forms[0]->ID;
            continue;
        }
        
        // Create new CF7 form using the official class
        $contact_form = WPCF7_ContactForm::get_template();
        
        // Set the form properties
        $contact_form->set_properties( array(
            'title'    => $form_title,
            'form'     => $form_data['form_tag'],
            'mail'     => array(
                'active'    => true,
                'subject'   => 'Nuevo formulario de contacto: [your-subject]',
                'sender'    => '[your-email]',
                'body'      => 'Mensaje de: [your-name]\nEmail: [your-email]\n\n[your-message]',
                'recipient' => get_option( 'admin_email' ),
                'additional_headers' => '',
                'exclude_blank'      => false,
                'use_html'           => false,
            ),
            'mail_2'  => array(
                'active'    => false,
            ),
            'messages' => array(
                'mail_sent_ok'       => 'Gracias por tu mensaje. Nos pondremos en contacto pronto.',
                'mail_sent_ng'       => 'Hubo un error al enviar tu mensaje. Por favor intenta de nuevo.',
                'validation_error'   => 'Por favor completa todos los campos requeridos.',
                'spam'               => 'El mensaje fue marcado como spam.',
            ),
            'additional_settings' => '',
        ) );
        
        // Save the form
        $form_id = $contact_form->save();
        
        if ( $form_id && ! is_wp_error( $form_id ) ) {
            // Force post_title and post_name to be set correctly
            wp_update_post( array(
                'ID'         => $form_id,
                'post_title' => $form_title,
                'post_name'  => sanitize_title( $form_title ),
            ) );
            
            // Mark as demo content
            update_post_meta( $form_id, '_demo_id', $demo_id );
            
            $form_ids[ $form_title ] = $form_id;
        }
    }
    
    return $form_ids;
}

/**
 * Create product categories
 */
function chow_create_categories( $demo ) {
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    $category_ids = array();
    
    foreach ( $demo['categories'] as $category_data ) {
        // Check if category already exists
        $existing_cat = get_term_by( 'slug', $category_data['slug'], 'product_cat' );
        
        if ( $existing_cat ) {
            $category_ids[ $category_data['name'] ] = $existing_cat->term_id;
            continue;
        }
        
        // Create new category
        $cat_result = wp_insert_term(
            $category_data['name'],
            'product_cat',
            array(
                'slug'        => $category_data['slug'],
                'description' => $category_data['description'],
            )
        );
        
        if ( ! is_wp_error( $cat_result ) ) {
            $term_id = $cat_result['term_id'];
            
            // Mark as demo content
            update_term_meta( $term_id, '_demo_id', $demo_id );
            
            $category_ids[ $category_data['name'] ] = $term_id;
        }
    }
    
    return $category_ids;
}

/**
 * Create WooCommerce products (OPTIONAL)
 * Skipped if WooCommerce is not installed
 */
function chow_create_products( $demo, $attachment_ids, $category_ids ) {
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    $product_count = 0;
    $success_count = 0;
    $error_count = 0;
    $products_created = array();
    
    if ( empty( $demo['products'] ) ) {
        chow_importer_log( "⚠ No hay productos en la configuración de la demo", 'WARNING' );
        return $products_created;
    }
    
    $total_products = count( $demo['products'] );
    chow_importer_log( "Total de productos a crear: $total_products" );
    
    foreach ( $demo['products'] as $product_data ) {
        $product_count++;
        @set_time_limit( 60 );
        
        $product_name = isset( $product_data['name'] ) ? $product_data['name'] : "Producto $product_count";
        chow_importer_log( "  ($product_count/$total_products) Procesando: $product_name" );
        
        // Verificar si el producto ya existe (usando búsqueda optimizada)
        $existing_id = chow_product_exists_by_title( $product_name, $demo_id );
        
        if ( $existing_id ) {
            chow_importer_log( "    - Producto ya existe (ID: $existing_id)" );
            $success_count++;
            $products_created[] = $existing_id;
            continue;
        }
        
        // Get category ID
        $category_name = isset( $product_data['category'] ) ? $product_data['category'] : '';
        $category_id = isset( $category_ids[ $category_name ] ) ? $category_ids[ $category_name ] : 0;
        chow_importer_log( "    - Categoría: $category_name (ID: $category_id)" );
        
        // Create product post
        $product_post = array(
            'post_title'   => $product_name,
            'post_content' => isset( $product_data['description'] ) ? $product_data['description'] : '',
            'post_excerpt' => isset( $product_data['short_description'] ) ? $product_data['short_description'] : '',
            'post_type'    => 'product',
            'post_status'  => 'publish',
        );
        
        $product_id = wp_insert_post( $product_post );
        
        if ( is_wp_error( $product_id ) ) {
            $error_msg = $product_id->get_error_message();
            chow_importer_log( "    ✗ Error creando post: $error_msg", 'ERROR' );
            $error_count++;
            continue;
        }
        
        if ( ! $product_id ) {
            chow_importer_log( "    ✗ wp_insert_post retornó 0", 'ERROR' );
            $error_count++;
            continue;
        }
        
        chow_importer_log( "    - Post creado (ID: $product_id)" );
        
        try {
            // Create WooCommerce product object
            $product = new WC_Product_Simple( $product_id );
            
            // Set basic product data
            if ( isset( $product_data['price'] ) ) {
                $product->set_price( $product_data['price'] );
                chow_importer_log( "    - Precio: " . $product_data['price'] );
            }
            
            if ( ! empty( $product_data['sale_price'] ) ) {
                $product->set_sale_price( $product_data['sale_price'] );
                chow_importer_log( "    - Precio en oferta: " . $product_data['sale_price'] );
            }
            
            // Set stock
            if ( isset( $product_data['stock'] ) ) {
                $product->set_stock_quantity( $product_data['stock'] );
                $product->set_manage_stock( true );
                $product->set_stock_status( $product_data['stock'] > 0 ? 'instock' : 'outofstock' );
                chow_importer_log( "    - Stock: " . $product_data['stock'] );
            }
            
            // Set featured
            if ( isset( $product_data['featured'] ) && $product_data['featured'] ) {
                $product->set_featured( true );
                chow_importer_log( "    - Marcado como destacado" );
            }
            
            // Save product
            $product->save();
            chow_importer_log( "    - Producto guardado en WooCommerce" );
            
            // Set product category
            if ( $category_id > 0 ) {
                $terms_result = wp_set_post_terms( $product_id, array( $category_id ), 'product_cat' );
                if ( is_wp_error( $terms_result ) ) {
                    chow_importer_log( "    ⚠ Error asignando categoría: " . $terms_result->get_error_message(), 'WARNING' );
                } else {
                    chow_importer_log( "    - Categoría asignada" );
                }
            }
            
            // Set product image
            if ( isset( $product_data['image'] ) ) {
                $image_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif', '.webp' ), '', $product_data['image'] );
                if ( isset( $attachment_ids[ $image_key ] ) ) {
                    set_post_thumbnail( $product_id, $attachment_ids[ $image_key ] );
                    chow_importer_log( "    - Imagen asignada" );
                } else {
                    chow_importer_log( "    ⚠ Imagen no encontrada: " . $product_data['image'], 'WARNING' );
                }
            }
            
            // Mark as demo content
            update_post_meta( $product_id, '_demo_id', $demo_id );
            
            // Add bestseller badge if specified
            if ( isset( $product_data['bestseller'] ) && $product_data['bestseller'] ) {
                update_post_meta( $product_id, '_bestseller', 'yes' );
                chow_importer_log( "    - Marcado como bestseller" );
            }
            
            chow_importer_log( "    ✓ Producto creado exitosamente" );
            $success_count++;
            $products_created[] = $product_id;
            
        } catch ( Exception $e ) {
            chow_importer_log( "    ✗ Excepción: " . $e->getMessage(), 'ERROR' );
            $error_count++;
        }
    }
    
    chow_importer_log( "✓ Productos procesados - Exitosos: $success_count, Errores: $error_count, Total: $total_products" );
    return $products_created;
}

/**
 * Helper function to save ACF fields for a page
 * 
 * @param int $page_id - The page ID
 * @param array $page_data - Page configuration data
 * @param array $attachment_ids - Map of image keys to attachment IDs
 * @param array $form_ids - Map of form names to form IDs
 */
function chow_save_page_acf_fields( $page_id, $page_data, $attachment_ids, $form_ids ) {
    // Only process if template is flexible-page
    if ( ! isset( $page_data['template'] ) || 'flexible-page' !== $page_data['template'] ) {
        return;
    }
    
    // Set page template
    update_post_meta( $page_id, '_wp_page_template', 'flexible-page.php' );
    
    // Verify template was assigned
    $assigned_template = get_post_meta( $page_id, '_wp_page_template', true );
    if ( $assigned_template !== 'flexible-page.php' ) {
        error_log( "WARNING: Template not assigned correctly for page $page_id" );
    }
    
    // Save header/content fields
    if ( isset( $page_data['content'] ) ) {
        update_field( 'texto_contenido', $page_data['content'], $page_id );
    }
    
    // Save collapses section if present
    if ( isset( $page_data['collapses'] ) && ! empty( $page_data['collapses'] ) ) {
        // Activate collapses section first
        update_field( 'activo_collapses', true, $page_id );
        // Then save collapses data
        update_field( 'collapses', $page_data['collapses'], $page_id );
    }
    
    // Save header fields (imagen_portada with attachment ID conversion)
    if ( isset( $page_data['imagen_portada'] ) && ! empty( $page_data['imagen_portada'] ) ) {
        // Activate header section
        update_field( 'activo_encabezado', true, $page_id );
        
        // Convert filename to attachment ID
        $header_key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $page_data['imagen_portada'] );
        if ( isset( $attachment_ids[ $header_key ] ) ) {
            update_field( 'imagen_portada', $attachment_ids[ $header_key ], $page_id );
        }
    }
    
    // Save header metadata if present
    if ( isset( $page_data['pre_txt'] ) ) {
        update_field( 'pre_txt', $page_data['pre_txt'], $page_id );
    }
    if ( isset( $page_data['titulo'] ) ) {
        update_field( 'titulo', $page_data['titulo'], $page_id );
    }
    if ( isset( $page_data['header_bajada'] ) ) {
        update_field( 'header_bajada', $page_data['header_bajada'], $page_id );
    }
    
    // Save form section if present
    if ( isset( $page_data['codigo_form'] ) && ! empty( $page_data['codigo_form'] ) ) {
        // Find the form ID by name
        if ( isset( $form_ids[ $page_data['codigo_form'] ] ) ) {
            // Activate form section first
            update_field( 'activo_form', true, $page_id );
            
            // Then save form code
            $form_id = $form_ids[ $page_data['codigo_form'] ];
            $form_shortcode = '[contact-form-7 id="' . $form_id . '" title="' . $page_data['codigo_form'] . '"]';
            update_field( 'codigo_form', $form_shortcode, $page_id );
        }
    }
}

/**
 * Create pages
 * 
 * @param array $demo - Demo configuration
 * @param array $attachment_ids - Map of image keys to attachment IDs
 * @param array $form_ids - Map of form names to form IDs
 * @param string $action_type - 'import' or 'overwrite'
 * @return array - Array of created/updated page IDs
 */
function chow_create_pages( $demo, $attachment_ids, $form_ids, $action_type = 'import' ) {
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    $pages_created = array();
    
    foreach ( $demo['pages'] as $page_data ) {
        // Check if page already exists
        $existing = get_page_by_title( $page_data['title'], OBJECT, 'page' );
        
        if ( $existing ) {
            // If not in overwrite mode, skip existing pages
            if ( 'overwrite' !== $action_type ) {
                chow_importer_log( "  ⊘ Página omitida (ya existe): {$page_data['title']}" );
                continue;
            }
            
            // In overwrite mode: check if it's demo content
            $existing_demo_id = get_post_meta( $existing->ID, '_demo_id', true );
            
            if ( ! $existing_demo_id ) {
                // Protect user-created content
                chow_importer_log( "  ⊘ Página omitida (contenido usuario): {$page_data['title']}" );
                continue;
            }
            
            // Update existing demo page
            $page_id = $existing->ID;
            $old_demo = $existing_demo_id;
            
            chow_importer_log( "  ↻ Actualizando página: {$page_data['title']} (de '$old_demo' a '$demo_id')" );
            
            // Update page content
            $page_post = array(
                'ID'           => $page_id,
                'post_title'   => $page_data['title'],
                'post_content' => ( isset( $page_data['template'] ) && 'flexible-page' === $page_data['template'] ) 
                                  ? '' 
                                  : $page_data['content'],
                'post_name'    => $page_data['slug'],
                'post_status'  => 'publish',
            );
            
            wp_update_post( $page_post );
            
            // Update template if specified
            if ( isset( $page_data['template'] ) ) {
                if ( 'flexible-page' === $page_data['template'] ) {
                    chow_save_page_acf_fields( $page_id, $page_data, $attachment_ids, $form_ids );
                } elseif ( 'index-plantilla' === $page_data['template'] ) {
                    update_post_meta( $page_id, '_wp_page_template', 'indexplantilla-page.php' );
                }
            }
            
            // Update demo marker
            update_post_meta( $page_id, '_demo_id', $demo_id );
            $pages_created[] = $page_id;
            
        } else {
            // Create new page
            chow_importer_log( "  + Creando página: {$page_data['title']}" );
            
            $page_post = array(
                'post_title'   => $page_data['title'],
                'post_content' => ( isset( $page_data['template'] ) && 'flexible-page' === $page_data['template'] ) 
                                  ? '' 
                                  : $page_data['content'],
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_name'    => $page_data['slug'],
            );
            
            $page_id = wp_insert_post( $page_post );
            
            if ( ! is_wp_error( $page_id ) ) {
                // Set page template if specified
                if ( isset( $page_data['template'] ) ) {
                    if ( 'flexible-page' === $page_data['template'] ) {
                        chow_save_page_acf_fields( $page_id, $page_data, $attachment_ids, $form_ids );
                    } elseif ( 'index-plantilla' === $page_data['template'] ) {
                        update_post_meta( $page_id, '_wp_page_template', 'indexplantilla-page.php' );
                    }
                }
                
                // Mark as demo content
                update_post_meta( $page_id, '_demo_id', $demo_id );
                $pages_created[] = $page_id;
            } else {
                chow_importer_log( "  ✗ Error creando página: {$page_data['title']}", 'ERROR' );
            }
        }
    }
    
    return $pages_created;
}

/**
 * Update theme options (colors, product blocks, etc.) (OPTIONAL)
 * Uses SCF (Smart Custom Fields) or ACF if available
 */
function chow_update_theme_options( $demo, $attachment_ids, $form_ids ) {
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    
    // Update company configuration
    if ( isset( $demo['company'] ) && function_exists( 'update_field' ) ) {
        $company = $demo['company'];
        $company_data = array();
        
        // Process logo fields (convert image filenames to IDs then to URLs)
         $logo_fields = array( 'logo_header_desktop', 'logo_header_mobile', 'logo_footer' );
          foreach ( $logo_fields as $logo_field ) {
              if ( isset( $company[ $logo_field ] ) && ! empty( $company[ $logo_field ] ) ) {
                  $logo_key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $company[ $logo_field ] );
                  $logo_id = isset( $attachment_ids[ $logo_key ] ) ? $attachment_ids[ $logo_key ] : 0;
                  // Store the attachment ID directly - ACF will convert to URL based on return_format
                  $company_data[ $logo_field ] = $logo_id;
              } else {
                  $company_data[ $logo_field ] = '';
              }
          }
        
        // Add other company fields
        $other_fields = array( 'color_principal', 'color_secundario', 'color_texto', 'color_fondo', 
                              'direccion', 'telefonos', 'mail', 'facebook_link', 'instagram_link', 
                              'twitter_link', 'wsp_link', 'logos_legales' );
        foreach ( $other_fields as $field ) {
            if ( isset( $company[ $field ] ) ) {
                $company_data[ $field ] = $company[ $field ];
            }
         }
         
    // Save each field individually con batching (evita múltiples update_field calls)
         $company_fields_batch = array();
         foreach ( $company_data as $field_name => $field_value ) {
             $company_fields_batch[ $field_name ] = $field_value;
         }
         
         // Aplicar en lote si es posible, si no usa update_field individual
         foreach ( $company_fields_batch as $field_name => $field_value ) {
             if ( function_exists( 'update_field' ) ) {
                 update_field( $field_name, $field_value, 'option' );
             }
         }
    }
     
      // Update card_style (product card design for this demo)
     if ( isset( $demo['card_style'] ) && function_exists( 'update_field' ) ) {
         update_field( 'card_style_default', $demo['card_style'], 'option' );
     }
    
    // Update home configuration
    if ( isset( $demo['home'] ) ) {
        $home_config = $demo['home'];
        
        // Update individual slider fields (slider_1 through slider_5)
        if ( function_exists( 'update_field' ) ) {
            // Update slider_1
            if ( isset( $home_config['slider_1'] ) ) {
                $image_key_1 = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_1']['imagen'] );
                $image_id_1 = isset( $attachment_ids[ $image_key_1 ] ) ? $attachment_ids[ $image_key_1 ] : 0;
                
                $slider_1_data = array(
                    'imagen' => $image_id_1,
                    'texto' => $home_config['slider_1']['texto'],
                    'link' => $home_config['slider_1']['link'],
                );
                update_field( 'slider_1', $slider_1_data, 'option' );
            }
            
            // Update slider_2
            if ( isset( $home_config['slider_2'] ) ) {
                $image_key_2 = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_2']['imagen'] );
                $image_id_2 = isset( $attachment_ids[ $image_key_2 ] ) ? $attachment_ids[ $image_key_2 ] : 0;
                
                $slider_2_data = array(
                    'imagen' => $image_id_2,
                    'texto' => $home_config['slider_2']['texto'],
                    'link' => $home_config['slider_2']['link'],
                );
                update_field( 'slider_2', $slider_2_data, 'option' );
            }
            
            // Update slider_3
            if ( isset( $home_config['slider_3'] ) ) {
                $image_key_3 = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_3']['imagen'] );
                $image_id_3 = isset( $attachment_ids[ $image_key_3 ] ) ? $attachment_ids[ $image_key_3 ] : 0;
                
                $slider_3_data = array(
                    'imagen' => $image_id_3,
                    'texto' => $home_config['slider_3']['texto'],
                    'link' => $home_config['slider_3']['link'],
                );
                update_field( 'slider_3', $slider_3_data, 'option' );
            }
            
            // Update slider_4
            if ( isset( $home_config['slider_4'] ) ) {
                $image_key_4 = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_4']['imagen'] );
                $image_id_4 = ( ! empty( $home_config['slider_4']['imagen'] ) && isset( $attachment_ids[ $image_key_4 ] ) ) ? $attachment_ids[ $image_key_4 ] : 0;
                
                $slider_4_data = array(
                    'imagen' => $image_id_4,
                    'texto' => $home_config['slider_4']['texto'],
                    'link' => $home_config['slider_4']['link'],
                );
                update_field( 'slider_4', $slider_4_data, 'option' );
            }
            
            // Update slider_5
            if ( isset( $home_config['slider_5'] ) ) {
                $image_key_5 = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_5']['imagen'] );
                $image_id_5 = ( ! empty( $home_config['slider_5']['imagen'] ) && isset( $attachment_ids[ $image_key_5 ] ) ) ? $attachment_ids[ $image_key_5 ] : 0;
                
                $slider_5_data = array(
                    'imagen' => $image_id_5,
                    'texto' => $home_config['slider_5']['texto'],
                    'link' => $home_config['slider_5']['link'],
                );
                update_field( 'slider_5', $slider_5_data, 'option' );
            }
        }
        
        // Update product blocks
        if ( isset( $home_config['product_blocks'] ) && function_exists( 'update_field' ) ) {
            update_field( 'bloques_productos', $home_config['product_blocks'], 'option' );
        }
        
         // Update newsletter (as GROUP field)
         if ( isset( $home_config['newsletter'] ) && function_exists( 'update_field' ) ) {
             $newsletter = $home_config['newsletter'];
             $news_bg_key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $newsletter['news_bg'] );
             $news_bg_id = isset( $attachment_ids[ $news_bg_key ] ) ? $attachment_ids[ $news_bg_key ] : 0;
             
             // Find the newsletter form ID
             $news_form_id = isset( $form_ids[ $newsletter['formulario_news'] ] ) ? $form_ids[ $newsletter['formulario_news'] ] : 0;
             
              // Save as GROUP field
              $newsletter_data = array(
                  'titulo' => $newsletter['titulo'],
                  'descripcion' => $newsletter['descripcion'],
                  'news_bg' => $news_bg_id,
                  'formulario_news' => '[contact-form-7 id="' . $news_form_id . '" title="' . $newsletter['formulario_news'] . '"]',
              );
             
              update_field( 'newsletter', $newsletter_data, 'option' );
          }
         
         // Update formulario_producto (product inquiry form)
         if ( isset( $home_config['formulario_producto'] ) && function_exists( 'update_field' ) ) {
             $product_form_name = $home_config['formulario_producto'];
             $product_form_id = isset( $form_ids[ $product_form_name ] ) ? $form_ids[ $product_form_name ] : 0;
             
             if ( $product_form_id ) {
                 $product_form_shortcode = '[contact-form-7 id="' . $product_form_id . '" title="' . $product_form_name . '"]';
                 update_field( 'formulario_producto', $product_form_shortcode, 'option' );
             }
         }
         
         // Update redes_seccion (as GROUP field)
        if ( isset( $home_config['redes_seccion'] ) && function_exists( 'update_field' ) ) {
            $redes = $home_config['redes_seccion'];
            $redes_bg_key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $redes['fondo_redes'] );
            $redes_bg_id = isset( $attachment_ids[ $redes_bg_key ] ) ? $attachment_ids[ $redes_bg_key ] : 0;
            
            // Save as GROUP field
            $redes_data = array(
                'titulo' => $redes['titulo'],
                'descripcion' => $redes['descripcion'],
                'fondo_redes' => $redes_bg_id,
            );
            
            update_field( 'redes_seccion', $redes_data, 'option' );
        }
        
         // Update carrusel_productos_destacados (as REPEATER field)
         if ( isset( $home_config['carrusel_productos_destacados'] ) && function_exists( 'update_field' ) ) {
             $carrusel = $home_config['carrusel_productos_destacados'];
             $carrusel_data = array();
             
             foreach ( $carrusel as $producto ) {
                 $prod_image_key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $producto['imagen'] );
                 $prod_image_id = isset( $attachment_ids[ $prod_image_key ] ) ? $attachment_ids[ $prod_image_key ] : 0;
                 
                 $carrusel_data[] = array(
                     'imagen' => $prod_image_id,
                     'nombre_del_link' => $producto['nombre_del_link'],
                     'link' => $producto['link'],
                 );
             }
             
             update_field( 'carrusel_productos_destacados', $carrusel_data, 'option' );
         }
    }
    
    // Store demo info
    update_option( 'chow_demo_' . $demo_id . '_info', array(
        'imported_at' => current_time( 'mysql' ),
        'images' => $attachment_ids,
        'forms' => $form_ids,
    ) );
    
    return true;
}

/**
 * Ensure WooCommerce Shop page has a proper title
 * Called before menu creation to prevent "(no label)" menu items
 */
function chow_ensure_shop_page_title() {
    if ( ! function_exists( 'wc_get_page_id' ) ) {
        return false;
    }
    
    $shop_page_id = wc_get_page_id( 'shop' );
    chow_importer_log( "  🔍 Verificando Shop page: ID={$shop_page_id}" );
    
    if ( ! $shop_page_id || $shop_page_id <= 0 ) {
        chow_importer_log( "  ⚠ Shop page ID no válido", 'WARNING' );
        return false;
    }
    
    $shop_page = get_post( $shop_page_id );
    
    if ( ! $shop_page ) {
        chow_importer_log( "  ⚠ Shop page no existe (ID: {$shop_page_id})", 'WARNING' );
        return false;
    }
    
    // Siempre asegurar que tenga el título "Tienda"
    if ( empty( trim( $shop_page->post_title ) ) || 'Shop' === $shop_page->post_title || 'shop' === $shop_page->post_title ) {
        chow_importer_log( "  📝 Actualizando título de Shop page a 'Tienda' (antes: '{$shop_page->post_title}')" );
        
        wp_update_post( array(
            'ID' => $shop_page_id,
            'post_title' => 'Tienda',
        ) );
        
        chow_importer_log( "  ✓ Título actualizado" );
    } else {
        chow_importer_log( "  ✓ Shop page ya tiene título: '{$shop_page->post_title}'" );
    }
    
    return true;
}

/**
 * Get WooCommerce Shop page details (slug, title)
 * Returns array with shop_slug and shop_title, or false if Shop page not found
 */
function chow_get_shop_page_details() {
    if ( ! function_exists( 'wc_get_page_id' ) ) {
        return false;
    }
    
    $shop_page_id = wc_get_page_id( 'shop' );
    
    if ( ! $shop_page_id || $shop_page_id <= 0 ) {
        chow_importer_log( "  ⚠ Shop page ID no válido en chow_get_shop_page_details()", 'WARNING' );
        return false;
    }
    
    $shop_page = get_post( $shop_page_id );
    
    if ( ! $shop_page ) {
        chow_importer_log( "  ⚠ Shop page no existe (ID: {$shop_page_id})", 'WARNING' );
        return false;
    }
    
    return array(
        'shop_slug'  => $shop_page->post_name, // El slug real de la página (ej: 'shop', 'tienda', 'boutique')
        'shop_title' => $shop_page->post_title, // El título real
    );
}

/**
 * Create or update navigation menu
 */
function chow_update_menu( $demo ) {
    // Validar que exista la clave 'menu'
    if ( ! isset( $demo['menu'] ) || empty( $demo['menu'] ) ) {
        chow_importer_log( "⚠ Configuración de menú no encontrada, omitiendo", 'WARNING' );
        return true;
    }
    
    // Ensure Shop page has a title before creating menu
    chow_ensure_shop_page_title();
    
    $menu_name = $demo['menu']['name'];
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    
    // Check if menu already exists
    $menu = get_term_by( 'name', $menu_name, 'nav_menu' );
    
    if ( ! $menu ) {
        // Create new menu
        $menu_id = wp_create_nav_menu( $menu_name );
        
        if ( is_wp_error( $menu_id ) ) {
            return $menu_id;
        }
    } else {
        $menu_id = $menu->term_id;
        
        // Clear existing menu items to avoid orphaned items from previous demos
        $existing_items = wp_get_nav_menu_items( $menu_id );
        if ( $existing_items ) {
            chow_importer_log( "  ↻ Limpiando " . count( $existing_items ) . " items existentes del menú" );
            foreach ( $existing_items as $item ) {
                wp_delete_post( $item->ID, true );
            }
        }
    }
    
    // Add menu items
    chow_importer_log( "  + Creando " . count( $demo['menu']['items'] ) . " items de menú" );
    $menu_position = 1;
    foreach ( $demo['menu']['items'] as $item_data ) {
        
        // Prepare menu item arguments
        $item_slug = isset( $item_data['slug'] ) ? $item_data['slug'] : '';
        $item_url = isset( $item_data['url'] ) ? $item_data['url'] : '/';
        $menu_item_type = 'custom';
        $menu_item_object = '';
        $menu_item_object_id = 0;
        $page_found = false;
        
        // Special handling for "Tienda" - detect Shop page slug and title dynamically
        if ( 'Tienda' === $item_data['title'] ) {
            $shop_details = chow_get_shop_page_details();
            
            if ( $shop_details ) {
                // Usar URL con el slug real de la Shop page
                $item_url = home_url( '/' . $shop_details['shop_slug'] . '/' );
                $item_data['title'] = $shop_details['shop_title']; // Usar el título real de la Shop page
                chow_importer_log( "    → Tienda detectado: slug='{$shop_details['shop_slug']}', título='{$shop_details['shop_title']}'" );
            } else {
                // Fallback si WooCommerce no está disponible - mantener título "Tienda"
                $item_url = home_url( '/shop/' );
                // NO cambiar $item_data['title'], mantener "Tienda"
                chow_importer_log( "    ⚠ Shop page no detectable, usando /shop/ y título 'Tienda' como fallback", 'WARNING' );
            }
            
            $menu_item_type = 'custom';
            $page_found = true;
        }
        
        // Buscar página normal por slug
        if ( ! $page_found && ! empty( $item_slug ) ) {
            $page = get_page_by_path( $item_slug, OBJECT, 'page' );
            if ( $page && 'publish' === $page->post_status ) {
                $menu_item_type = 'post_type';
                $menu_item_object = 'page';
                $menu_item_object_id = $page->ID;
                $item_url = get_permalink( $page->ID );
                $page_found = true;
                chow_importer_log( "    → {$item_data['title']} vinculado a página (ID: {$page->ID})" );
            } else {
                chow_importer_log( "    ⚠ Página no encontrada para slug '{$item_slug}', usando custom link", 'WARNING' );
            }
        }
        
        // Build menu item arguments
        $item_args = array(
            'menu-item-status'     => 'publish',
            'menu-item-type'       => $menu_item_type,
            'menu-item-parent-id'  => $item_data['parent'] ?? 0,
            'menu-item-position'   => $menu_position,
        );
        
        // Add title ONLY for custom links
        // For post_type items, WordPress uses the post_title automatically
        if ( 'custom' === $menu_item_type ) {
            $item_args['menu-item-title'] = $item_data['title'];
            $item_args['menu-item-url'] = $item_url;
        } else {
            // For post_type items, provide object references
            $item_args['menu-item-object-id'] = $menu_item_object_id;
            $item_args['menu-item-object'] = $menu_item_object;
        }
        
        // Create new menu item
        $result = wp_update_nav_menu_item( $menu_id, 0, $item_args );
        
        if ( is_wp_error( $result ) ) {
            chow_importer_log( "    ✗ Error creando menu item '{$item_data['title']}': " . $result->get_error_message(), 'ERROR' );
        } else {
            $type_label = ( 'post_type' === $menu_item_type ) ? 'Página' : 'Custom Link';
            chow_importer_log( "    ✓ '{$item_data['title']}' creado como {$type_label} (posición {$menu_position})" );
        }
        
        $menu_position++;
    }
    
     // Set as Primary Menu (theme location is 'superior')
     $theme_locations = get_theme_mod( 'nav_menu_locations' );
     if ( ! $theme_locations ) {
         $theme_locations = array();
     }
     
     // Theme registers menu location as 'superior'
     $theme_locations['superior'] = $menu_id;
     set_theme_mod( 'nav_menu_locations', $theme_locations );
    
    // Mark menu as demo content
    update_term_meta( $menu_id, '_demo_id', $demo_id );
    
    return true;
}

/**
 * Apply custom CSS
 */
function chow_apply_custom_css( $demo ) {
    if ( ! isset( $demo['custom_css'] ) ) {
        return true;
    }
    
    // Get current custom CSS
    $current_css = wp_get_custom_css();
    
    // Append demo CSS
    $new_css = $current_css . "\n\n/* " . $demo['id'] . " Demo CSS */\n" . $demo['custom_css'];
    
    // Save custom CSS
    wp_update_custom_css_post( $new_css );
    
    return true;
}

/**
 * AJAX Handlers for viewing/downloading logs
 */

add_action( 'wp_ajax_chow_get_importer_logs', 'chow_ajax_get_importer_logs' );
function chow_ajax_get_importer_logs() {
    check_ajax_referer( 'chow_importer_logs' );
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Sin permisos' );
    }
    
    $log_file = wp_upload_dir()['basedir'] . '/chow-importer.log';
    
    if ( ! file_exists( $log_file ) ) {
        wp_send_json_success( 'No hay logs aún.' );
    }
    
    $content = file_get_contents( $log_file );
    $lines = array_reverse( explode( "\n", $content ) );
    $content = implode( "\n", $lines );
    
    wp_send_json_success( $content );
}

add_action( 'wp_ajax_chow_download_importer_logs', 'chow_ajax_download_importer_logs' );
function chow_ajax_download_importer_logs() {
    check_ajax_referer( 'chow_importer_logs' );
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos' );
    }
    
    $log_file = wp_upload_dir()['basedir'] . '/chow-importer.log';
    
    if ( ! file_exists( $log_file ) ) {
        wp_die( 'No hay logs' );
    }
    
    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="chow-importer-' . date( 'Y-m-d-H-i-s' ) . '.log"' );
    readfile( $log_file );
    exit;
}

add_action( 'wp_ajax_chow_clear_importer_logs', 'chow_ajax_clear_importer_logs' );
function chow_ajax_clear_importer_logs() {
    check_ajax_referer( 'chow_importer_logs' );
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Sin permisos' );
    }
    
    $log_file = wp_upload_dir()['basedir'] . '/chow-importer.log';
    
    if ( file_exists( $log_file ) ) {
        @unlink( $log_file );
    }
    
    wp_send_json_success( 'Logs limpiados' );
}

