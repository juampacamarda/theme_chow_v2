<?php
/**
 * Demo Pastelería - "Harina & Miel"
 * Artisanal pastry shop demo with products, gallery and reservation flow
 */

if ( ! function_exists( 'chow_get_demo_pasteleria' ) ) {
    function chow_get_demo_pasteleria() {
        $manifest = chow_demo_pasteleria_get_manifest();
        
        return array(
            'id'          => 'pasteleria',
            'name'        => 'Pastelería Harina & Miel',
            'description' => 'Una pastelería artesanal con 8 productos destacados, galería de tentaciones y sistema de reservas. Perfecto para negocios locales de repostería.',
            'image'       => 'pasteleria-cover.png',
            'version'     => '1.0',
            'card_style'  => 'hover_visual',
            
            'company' => array(
                'color_principal'       => '#d4a574',
                'color_secundario'      => '#f5e6d3',
                'color_texto'           => '#5f4a42',
                'color_fondo'           => '#ffffff',
                'logo_header_desktop'   => 'pasteleria-logo-primary.png',
                'logo_header_mobile'    => 'pasteleria-logo-sticker.png',
                'logo_footer'           => 'pasteleria-logo-sticker.png',
                'direccion'             => 'Av. Corrientes 1234, Buenos Aires, Argentina',
                'telefonos'             => '+54 (11) 4567-8900',
                'mail'                  => 'info@harinaymel.com',
                'facebook_link'         => '#',
                'instagram_link'        => '#',
                'twitter_link'          => '#',
                'wsp_link'              => '5491145678900',
            ),
            
            'categories' => array(
                array(
                    'name'        => 'Tortas',
                    'slug'        => 'tortas',
                    'description' => 'Deliciosas tortas para todas las ocasiones',
                ),
                array(
                    'name'        => 'Pastas',
                    'slug'        => 'pastas',
                    'description' => 'Pastas y postres artesanales',
                ),
            ),
            
            'products' => ! empty( $manifest['products'] ) ? $manifest['products'] : array(),
            
            'pages' => array(
                array(
                    'title'   => 'Inicio',
                    'slug'    => 'home',
                    'content' => '<p>Bienvenido a <strong>Harina & Miel</strong>, tu pastelería artesanal de confianza.</p>',
                    'template' => 'home',
                ),
                array(
                    'title'   => 'Sobre Nosotros',
                    'slug'    => 'sobre-nosotros',
                    'content' => '<p>Desde 1998, <strong>Harina & Miel</strong> se dedica a crear las mejores delicias de repostería con ingredientes premium y recetas tradicionales.</p>
<p><strong>Nuestra Misión:</strong> Llevar alegría a cada celebración con productos artesanales de calidad superior.</p>
<p><strong>¿Por qué elegirnos?</strong></p>
<ul>
<li>Ingredientes frescos y de calidad premium</li>
<li>Recetas tradicionales y creaciones innovadoras</li>
<li>Atención personalizada para eventos especiales</li>
<li>Entregas rápidas y seguras en tu domicilio</li>
<li>Asesoramiento personalizado en tus pedidos</li>
</ul>
<p>Cada producto es hecho con amor y dedicación para que disfrutes de momentos dulces inolvidables.</p>',
                    'template' => 'flexible-page',
                    'imagen_portada' => 'pasteleria-hero-01.png',
                    'activo_encabezado' => true,
                    'pre_txt' => 'NUESTRA HISTORIA',
                    'titulo' => 'Sobre Nosotros',
                    'header_bajada' => 'Más de 25 años de excelencia en repostería artesanal',
                ),
                array(
                    'title'   => 'Preguntas Frecuentes',
                    'slug'    => 'preguntas-frecuentes',
                    'content' => '<p>Resolvemos tus dudas sobre nuestros productos, pedidos y entregas.</p>',
                    'template' => 'flexible-page',
                    'imagen_portada' => 'pasteleria-hero-02.png',
                    'activo_encabezado' => true,
                    'pre_txt' => 'DUDAS',
                    'titulo' => 'Preguntas Frecuentes',
                    'header_bajada' => 'Encuentra respuestas a tus preguntas más comunes',
                    'collapses' => array(
                        array(
                            'titulo_collapse'   => '¿Cuáles son los costos de envío?',
                            'contenido_collapse' => 'El envío es GRATIS para pedidos superiores a $250. Para órdenes menores, el costo es de $40-80 según tu zona. Los envíos se realizan en 24-48 horas hábiles.',
                        ),
                        array(
                            'titulo_collapse'   => '¿Puedo hacer un pedido personalizado?',
                            'contenido_collapse' => 'Sí, hazlo con gusto. Contáctanos con al menos 48 horas de anticipación para tortas decoradas, sin gluten o con requerimientos especiales. Te asesoraremos sin costo adicional.',
                        ),
                        array(
                            'titulo_collapse'   => '¿Tienen opciones sin gluten?',
                            'contenido_collapse' => 'Contamos con una línea de productos sin gluten preparados con dedicación. Consulta disponibilidad y haz tu pedido con 48 horas de anticipación.',
                        ),
                        array(
                            'titulo_collapse'   => '¿Cuál es la política de devoluciones?',
                            'contenido_collapse' => 'Garantizamos la calidad de nuestros productos. Si tienes algún problema, contáctanos dentro de 24 horas y lo resolveremos a tu satisfacción.',
                        ),
                        array(
                            'titulo_collapse'   => '¿Ofrecen servicios para catering?',
                            'contenido_collapse' => 'Sí, realizamos catering para eventos. Contáctanos para presupuestos y opciones de menú personalizados según tu evento.',
                        ),
                        array(
                            'titulo_collapse'   => '¿Cómo puedo contactarlos?',
                            'contenido_collapse' => 'Puedes escribirnos a través de nuestro formulario de contacto, por email a info@harinaymel.com, o llamarnos al +54 (11) 4567-8900. Estamos disponibles de lunes a viernes, 8:00 - 19:00 hs.',
                        ),
                    ),
                ),
                array(
                    'title'   => 'Contacto',
                    'slug'    => 'contacto',
                    'content' => '<p>¿Tienes una ocasión especial? ¿Preguntas sobre nuestros productos? Contáctanos y te ayudaremos.</p>
<p><strong>Información de contacto directo:</strong></p>
<ul>
<li><strong>Email:</strong> info@harinaymel.com</li>
<li><strong>Teléfono:</strong> +54 (11) 4567-8900</li>
<li><strong>WhatsApp:</strong> +54 9 11 4567-8900</li>
<li><strong>Horario:</strong> Lunes a Viernes, 8:00 - 19:00 hs</li>
<li><strong>Dirección:</strong> Av. Corrientes 1234, Buenos Aires, Argentina</li>
</ul>',
                    'template' => 'flexible-page',
                    'imagen_portada' => 'pasteleria-hero-02.png',
                    'activo_encabezado' => true,
                    'pre_txt' => 'CONTACTO',
                    'titulo' => 'Ponte en Contacto',
                    'header_bajada' => 'Estamos aquí para ayudarte con tus pedidos especiales',
                    'codigo_form' => 'Contacto Pastelería',
                ),
            ),
            
            'forms' => array(
                array(
                    'name'     => 'Contacto Pastelería',
                    'form_tag' => '<label>Tu nombre
    [text* nombre autocomplete:name] </label>

<label>Tu correo electrónico
    [email* email autocomplete:email] </label>

<label>¿Cuándo necesitas tu pedido?
    [date* fecha] </label>

<label>Tu mensaje
    [textarea mensaje] </label>

[submit "Enviar Consulta"]',
                ),
            ),
        );
    }
}

function chow_demo_pasteleria_manifest_path() {
    return get_template_directory() . '/demos/pasteleria/manifest.json';
}

function chow_demo_pasteleria_get_manifest() {
    $path = chow_demo_pasteleria_manifest_path();
    if ( ! file_exists( $path ) ) {
        return array();
    }
    $json = file_get_contents( $path );
    return json_decode( $json, true ) ?: array();
}

/**
 * Upload image from filesystem to WP media library
 */
function chow_demo_pasteleria_upload_image( $image_path, $name ) {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
    }

    if ( ! file_exists( $image_path ) ) {
        return false;
    }

    $wp_upload_dir = wp_upload_dir();
    $filename = basename( $image_path );
    $dest_path = $wp_upload_dir['path'] . '/' . $filename;

    if ( ! copy( $image_path, $dest_path ) ) {
        return false;
    }

    $filetype = wp_check_filetype( $dest_path );
    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name( $name ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $dest_path );
    if ( ! $attach_id ) {
        return false;
    }

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $dest_path );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;
}

/**
 * Create WooCommerce products from manifest
 */
function chow_demo_pasteleria_create_products( $manifest ) {
    $product_ids = array();
    $images_dir = get_template_directory() . '/demos/pasteleria/images/';

    if ( empty( $manifest['products'] ) ) {
        return $product_ids;
    }

    foreach ( $manifest['products'] as $idx => $product_data ) {
        $product_name = $product_data['product_name'] ?? 'Producto ' . ( $idx + 1 );
        $image_file = $product_data['file'] ?? null;

        if ( ! $image_file ) {
            continue;
        }

        $image_path = $images_dir . $image_file;
        $attachment_id = chow_demo_pasteleria_upload_image( $image_path, $product_name );
        if ( ! $attachment_id ) {
            continue;
        }

        $post_id = wp_insert_post( array(
            'post_title'   => $product_name,
            'post_content' => '',
            'post_type'    => 'product',
            'post_status'  => 'publish',
            'meta_input'   => array(
                '_visibility'       => 'visible',
                '_stock_status'     => 'instock',
                '_stock'            => 5,
                '_regular_price'    => 15,
                '_product_image_gallery' => '',
            ),
        ) );

        if ( $post_id ) {
            wp_set_post_terms( $post_id, 'simple', 'product_type' );
            set_post_thumbnail( $post_id, $attachment_id );
            $product_ids[] = $post_id;
        }
    }

    return $product_ids;
}

/**
 * Populate "Tentaciones" gallery in ACF option page
 */
function chow_demo_pasteleria_populate_tentaciones( $manifest ) {
    if ( empty( $manifest['tentaciones_gallery'] ) || ! function_exists( 'update_field' ) ) {
        return;
    }

    $images_dir = get_template_directory() . '/demos/pasteleria/images/';
    $tentaciones_data = array();

    foreach ( $manifest['tentaciones_gallery'] as $tent_img ) {
        $image_file = $tent_img['file'] ?? null;
        if ( ! $image_file ) {
            continue;
        }

        $image_path = $images_dir . $image_file;
        $attachment_id = chow_demo_pasteleria_upload_image( $image_path, 'Tentación - ' . $image_file );
        if ( $attachment_id ) {
            $tentaciones_data[] = array(
                'imagen' => $attachment_id,
            );
        }
    }

    if ( ! empty( $tentaciones_data ) ) {
        update_field( 'tentaciones_gallery', $tentaciones_data, 'option' );
    }
}
