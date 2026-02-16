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

// AJAX handler for importing demo
add_action( 'wp_ajax_chow_import_demo', 'chow_handle_import_ajax' );

function chow_handle_import_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'chow_import_demo' ) ) {
        wp_send_json_error( array( 'message' => 'Verificación de seguridad fallida' ) );
    }
    
    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'No tienes permisos para realizar esta acción' ) );
    }
    
    // Get demo ID
    $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( $_POST['demo_id'] ) : '';
    $action_type = isset( $_POST['action_type'] ) ? sanitize_text_field( $_POST['action_type'] ) : 'import';
    
    if ( empty( $demo_id ) ) {
        wp_send_json_error( array( 'message' => 'Demo ID no válido' ) );
    }
    
    // Call the import function
    $result = chow_do_import( $demo_id, $action_type );
    
    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array( 'message' => $result->get_error_message() ) );
    }
    
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
 * Main import function - orchestrates the complete import process
 * 
 * @param string $demo_id - The demo to import (e.g., 'libreria')
 * @param string $action_type - 'import' or 'overwrite'
 * @return array|WP_Error - Array with success info and skipped plugins, or WP_Error on failure
 */
function chow_do_import( $demo_id, $action_type = 'import' ) {
    // Get demo configuration
    if ( 'libreria' === $demo_id ) {
        $demo = chow_get_demo_libreria();
    } else {
        return new WP_Error( 'invalid_demo', 'Demo no encontrada' );
    }
    
    // Detect missing plugins early
    $missing_plugins = chow_get_missing_plugins();
    
    // Check if demo already exists
    $demo_marker = 'chow_demo_' . $demo_id . '_imported';
    $demo_exists = get_option( $demo_marker );
    
    // If demo exists and action is 'import', check for user content
    if ( $demo_exists && 'import' === $action_type ) {
        // Check if there's user content (products, pages, etc.)
        $has_user_content = chow_has_user_content();
        
        if ( $has_user_content ) {
            return new WP_Error( 'content_exists', 'Ya existe contenido. Por favor, elige sobrescribir.' );
        }
    }
    
    // Clear existing content if overwriting
    if ( 'overwrite' === $action_type ) {
        chow_clear_demo_content( $demo_id );
    }
    
    // Step 1: Import images
    $attachment_ids = chow_import_images( $demo_id );
    if ( is_wp_error( $attachment_ids ) ) {
        return $attachment_ids;
    }
    
    // Step 2: Create Contact Form 7 forms (OPTIONAL - skip if plugin missing)
    $form_ids = array();
    if ( ! isset( $missing_plugins['cf7'] ) ) {
        $form_ids = chow_create_forms( $demo, $attachment_ids );
        if ( is_wp_error( $form_ids ) ) {
            return $form_ids;
        }
    }
    
    // Step 3: Create product categories (OPTIONAL - skip if plugin missing)
    $category_ids = array();
    if ( ! isset( $missing_plugins['woocommerce'] ) ) {
        $category_ids = chow_create_categories( $demo );
        if ( is_wp_error( $category_ids ) ) {
            return $category_ids;
        }
    }
    
    // Step 4: Create products (OPTIONAL - skip if plugin missing)
    if ( ! isset( $missing_plugins['woocommerce'] ) ) {
        $products = chow_create_products( $demo, $attachment_ids, $category_ids );
        if ( is_wp_error( $products ) ) {
            return $products;
        }
    }
    
    // Step 5: Create pages
    $pages = chow_create_pages( $demo, $attachment_ids, $form_ids );
    if ( is_wp_error( $pages ) ) {
        return $pages;
    }
    
    // Step 5.5: Set front page to "Inicio" if it exists
    $inicio_page = get_page_by_title( 'Inicio', OBJECT, 'page' );
    if ( $inicio_page ) {
        update_option( 'page_on_front', $inicio_page->ID );
        update_option( 'show_on_front', 'page' );
    }
    
    // Step 6: Update theme options (OPTIONAL - skip if plugin missing)
    if ( ! isset( $missing_plugins['acf'] ) ) {
        $result = chow_update_theme_options( $demo, $attachment_ids, $form_ids );
        if ( is_wp_error( $result ) ) {
            return $result;
        }
    }
    
    // Step 7: Create/update navigation menu
    $result = chow_update_menu( $demo );
    if ( is_wp_error( $result ) ) {
        return $result;
    }
    
    // Step 8: Apply custom CSS
    $result = chow_apply_custom_css( $demo );
    if ( is_wp_error( $result ) ) {
        return $result;
    }
    
    // Mark demo as imported
    update_option( $demo_marker, time() );
    
    // Store demo info for future reference
    update_option( 'chow_active_demo', $demo_id );
    
    // Return success with information about skipped plugins
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
}

/**
 * Import images from demo folder to WordPress media library
 */
function chow_import_images( $demo_id ) {
    $demo_images_path = get_template_directory() . '/demos/' . $demo_id . '/images/';
    $attachment_ids = array();
    
    // Check if demo images folder exists
    if ( ! is_dir( $demo_images_path ) ) {
        return new WP_Error( 'missing_images', 'Carpeta de imágenes del demo no encontrada' );
    }
    
    // Scan for image files
    $image_files = glob( $demo_images_path . '*.{png,jpg,jpeg,gif}', GLOB_BRACE );
    
    if ( empty( $image_files ) ) {
        return new WP_Error( 'no_images', 'No se encontraron imágenes en la carpeta del demo' );
    }
    
    // Load WordPress media upload functions
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
    foreach ( $image_files as $image_path ) {
        $filename = basename( $image_path );
        
        // Copy image to uploads folder with demo prefix
        $uploads_dir = wp_upload_dir();
        $dest_path = $uploads_dir['path'] . '/' . $demo_id . '-' . $filename;
        
        if ( ! copy( $image_path, $dest_path ) ) {
            continue; // Skip if copy fails
        }
        
        // Create WordPress attachment
        $file_type = wp_check_filetype( $dest_path );
        $attachment = array(
            'post_mime_type' => $file_type['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        
        $attach_id = wp_insert_attachment( $attachment, $dest_path );
        
        if ( ! is_wp_error( $attach_id ) ) {
            // Generate attachment metadata
            $attach_data = wp_generate_attachment_metadata( $attach_id, $dest_path );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            
            // Store the mapping without the extension
            $key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $filename );
            $attachment_ids[ $key ] = $attach_id;
        }
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
    
    foreach ( $demo['products'] as $product_data ) {
        // Check if product already exists
        $existing = get_page_by_title( $product_data['name'], OBJECT, 'product' );
        
        if ( $existing ) {
            continue;
        }
        
        // Get category ID
        $category_name = isset( $product_data['category'] ) ? $product_data['category'] : '';
        $category_id = isset( $category_ids[ $category_name ] ) ? $category_ids[ $category_name ] : 0;
        
        // Create product post
        $product_post = array(
            'post_title'   => $product_data['name'],
            'post_content' => $product_data['description'],
            'post_excerpt' => $product_data['short_description'],
            'post_type'    => 'product',
            'post_status'  => 'publish',
        );
        
        $product_id = wp_insert_post( $product_post );
        
        if ( ! is_wp_error( $product_id ) ) {
            // Create WooCommerce product object
            $product = new WC_Product_Simple( $product_id );
            
            // Set basic product data
            $product->set_price( $product_data['price'] );
            if ( ! empty( $product_data['sale_price'] ) ) {
                $product->set_sale_price( $product_data['sale_price'] );
            }
            
            // Set stock
            $product->set_stock_quantity( $product_data['stock'] );
            $product->set_manage_stock( true );
            $product->set_stock_status( $product_data['stock'] > 0 ? 'instock' : 'outofstock' );
            
            // Set featured
            if ( isset( $product_data['featured'] ) && $product_data['featured'] ) {
                $product->set_featured( true );
            }
            
            // Save product
            $product->save();
            
            // Set product category
            if ( $category_id > 0 ) {
                wp_set_post_terms( $product_id, array( $category_id ), 'product_cat' );
            }
            
            // Set product image
            if ( isset( $product_data['image'] ) ) {
                $image_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $product_data['image'] );
                if ( isset( $attachment_ids[ $image_key ] ) ) {
                    set_post_thumbnail( $product_id, $attachment_ids[ $image_key ] );
                }
            }
            
            // Mark as demo content
            update_post_meta( $product_id, '_demo_id', $demo_id );
            
            // Add bestseller badge if specified
            if ( isset( $product_data['bestseller'] ) && $product_data['bestseller'] ) {
                update_post_meta( $product_id, '_bestseller', 'yes' );
            }
        }
    }
    
    return true;
}

/**
 * Create pages
 */
function chow_create_pages( $demo, $attachment_ids, $form_ids ) {
    $demo_id = isset( $demo['id'] ) ? $demo['id'] : '';
    
    foreach ( $demo['pages'] as $page_data ) {
        // Check if page already exists
        $existing = get_page_by_title( $page_data['title'], OBJECT, 'page' );
        
        if ( $existing ) {
            continue;
        }
        
        // Create page post
        $page_post = array(
            'post_title'  => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_type'   => 'page',
            'post_status' => 'publish',
            'post_name'   => $page_data['slug'],
        );
        
        $page_id = wp_insert_post( $page_post );
        
         if ( ! is_wp_error( $page_id ) ) {
             // Set page template if specified
             if ( isset( $page_data['template'] ) ) {
                 if ( 'flexible-page' === $page_data['template'] ) {
                     update_post_meta( $page_id, '_wp_page_template', 'flexible-page.php' );
                     
                     // Para páginas flexible, guardar contenido en ACF field (no en post_content)
                     if ( isset( $page_data['content'] ) ) {
                         update_field( 'texto_contenido', $page_data['content'], $page_id );
                     }
                     
                     // Guardar collapses si existen
                     if ( isset( $page_data['collapses'] ) ) {
                         update_field( 'collapses', $page_data['collapses'], $page_id );
                     }
                 } elseif ( 'index-plantilla' === $page_data['template'] ) {
                     update_post_meta( $page_id, '_wp_page_template', 'indexplantilla-page.php' );
                 }
             }
             
             // Mark as demo content
             update_post_meta( $page_id, '_demo_id', $demo_id );
         }
    }
    
    return true;
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
        
        // Process logo fields (convert image filenames to IDs)
        $logo_fields = array( 'logo_header_desktop', 'logo_header_mobile', 'logo_footer' );
        foreach ( $logo_fields as $logo_field ) {
            if ( isset( $company[ $logo_field ] ) && ! empty( $company[ $logo_field ] ) ) {
                $logo_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $company[ $logo_field ] );
                $company_data[ $logo_field ] = isset( $attachment_ids[ $logo_key ] ) ? $attachment_ids[ $logo_key ] : 0;
            } else {
                $company_data[ $logo_field ] = 0;
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
        
        // Save as GROUP field
        update_field( 'empresa', $company_data, 'option' );
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
                $image_key_1 = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_1']['imagen'] );
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
                $image_key_2 = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_2']['imagen'] );
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
                $image_key_3 = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_3']['imagen'] );
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
                $image_key_4 = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_4']['imagen'] );
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
                $image_key_5 = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $home_config['slider_5']['imagen'] );
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
             $news_bg_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $newsletter['news_bg'] );
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
        
        // Update redes_seccion (as GROUP field)
        if ( isset( $home_config['redes_seccion'] ) && function_exists( 'update_field' ) ) {
            $redes = $home_config['redes_seccion'];
            $redes_bg_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $redes['fondo_redes'] );
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
                 $prod_image_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $producto['imagen'] );
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
 * Create or update navigation menu
 */
function chow_update_menu( $demo ) {
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
    }
    
    // Add menu items
    foreach ( $demo['menu']['items'] as $item_data ) {
        // Check if item already exists in this menu
        $existing_items = wp_get_nav_menu_items( $menu_id );
        $item_exists = false;
        
        if ( $existing_items ) {
            foreach ( $existing_items as $existing_item ) {
                if ( $existing_item->title === $item_data['title'] ) {
                    $item_exists = true;
                    break;
                }
            }
        }
        
        if ( ! $item_exists ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'      => $item_data['title'],
                'menu-item-url'        => $item_data['url'],
                'menu-item-status'     => 'publish',
                'menu-item-type'       => 'custom',
                'menu-item-parent-id'  => $item_data['parent'] ?? 0,
            ) );
        }
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
