<?php
/**
 * Demo Pastelería - "Harina & Miel"
 * Artisanal pastry shop demo with products, gallery and reservation flow
 */

if ( ! function_exists( 'chow_get_demo_pasteleria' ) ) {
    function chow_get_demo_pasteleria() {
        return array(
            'id'          => 'pasteleria',
            'name'        => 'Pastelería Harina & Miel',
            'description' => 'Una pastelería artesanal con 8 productos destacados, galería de tentaciones y sistema de reservas. Perfecto para negocios locales de repostería.',
            'image'       => 'pasteleria-cover.webp',
            'version'     => '1.0',
            'card_style'  => 'hover_visual',
            
            'company' => array(
                'color_principal'       => '#d4a574',
                'color_secundario'      => '#f5e6d3',
                'color_texto'           => '#5f4a42',
                'color_fondo'           => '#ffffff',
                'logo_header_desktop'   => 'pasteleria-logo-primary.webp',
                'logo_header_mobile'    => 'pasteleria-logo-sticker.webp',
                'logo_footer'           => 'pasteleria-logo-sticker.webp',
                'direccion'             => 'Av. Corrientes 1234, Buenos Aires, Argentina',
                'telefonos'             => '+54 (11) 4567-8900',
                'mail'                  => 'info@harinaymel.com',
                'facebook_link'         => '#',
                'instagram_link'        => '#',
                'twitter_link'          => '#',
                'wsp_link'              => '5491145678900',
                'logos_legales'         => '',
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
            
            'products' => array(
                // Product 1: Croissant
                array(
                    'name'              => 'Croissant de Mantequilla',
                    'slug'              => 'croissant-de-mantequilla',
                    'description'       => 'Croissant auténtico hecho con mantequilla europea de alta calidad. Masa folietada en 7 capas que garantiza ese crujido perfecto en el exterior y suavidad en el interior. Ideal para el desayuno o merienda.',
                    'short_description' => 'Croissant con mantequilla europea',
                    'price'             => '4.50',
                    'sale_price'        => '',
                    'stock'             => 60,
                    'image'             => 'pasteleria-producto-01.webp',
                    'category'          => 'Pastas',
                    'featured'          => true,
                    'on_sale'           => false,
                ),
                
                // Product 2: Lemon Pie
                array(
                    'name'              => 'Lemon Pie Casero',
                    'slug'              => 'lemon-pie-casero',
                    'description'       => 'Nuestro clásico Lemon Pie preparado con limones naturales y meringue casero. Una combinación perfecta de dulce y ácido que seduce al paladar. Base de masa quebrada artesanal.',
                    'short_description' => 'Lemon Pie con meringue casero',
                    'price'             => '18.99',
                    'sale_price'        => '14.99',
                    'stock'             => 25,
                    'image'             => 'pasteleria-producto-02.webp',
                    'category'          => 'Tortas',
                    'featured'          => true,
                    'on_sale'           => true,
                ),
                
                // Product 3: Red Velvet
                array(
                    'name'              => 'Torta Red Velvet Lujo',
                    'slug'              => 'torta-red-velvet-lujo',
                    'description'       => 'Nuestra versión premium de la clásica Red Velvet. Capas de torta roja aterciopelada con relleno y cobertura de queso crema belga. Decorada con detalles en chocolate blanco.',
                    'short_description' => 'Red Velvet con queso crema belga',
                    'price'             => '32.50',
                    'sale_price'        => '',
                    'stock'             => 12,
                    'image'             => 'pasteleria-producto-03.webp',
                    'category'          => 'Tortas',
                    'featured'          => false,
                    'on_sale'           => false,
                ),
                
                // Product 4: Torta Chocolate
                array(
                    'name'              => 'Torta Chocolate Intenso',
                    'slug'              => 'torta-chocolate-intenso',
                    'description'       => 'Para los verdaderos amantes del chocolate. Hecha con chocolate belga 70% cacao, ganache de chocolate oscuro y cobertura espejo. Una experiencia de indulgencia pura en cada bocado.',
                    'short_description' => 'Torta chocolate belga 70% cacao',
                    'price'             => '35.99',
                    'sale_price'        => '24.99',
                    'stock'             => 18,
                    'image'             => 'pasteleria-producto-04.webp',
                    'category'          => 'Tortas',
                    'featured'          => true,
                    'on_sale'           => true,
                ),
                
                // Product 5: Tortitas Mendocinas
                array(
                    'name'              => 'Tortitas Mendocinas Tradicionales',
                    'slug'              => 'tortitas-mendocinas-tradicionales',
                    'description'       => 'Las auténticas tortitas mendocinas rellenas de queso fresco y guayaba. Receta tradicional argentina hecha siguiendo el método ancestral. Perfectas como postre o para llevar una caja como regalo.',
                    'short_description' => 'Tortitas rellenas de queso y guayaba',
                    'price'             => '12.00',
                    'sale_price'        => '',
                    'stock'             => 45,
                    'image'             => 'pasteleria-producto-05.webp',
                    'category'          => 'Pastas',
                    'featured'          => true,
                    'on_sale'           => false,
                    'bestseller'        => true,
                ),
                
                // Product 6: Chipa
                array(
                    'name'              => 'Chipa Paraguaya Receta Original',
                    'slug'              => 'chipa-paraguaya-receta-original',
                    'description'       => 'Chipa auténtica de Asunción hecha con almidón de mandioca, queso paraguayo y anís. Receta original de la abuela que presidenta nuestro horno. De textura esponjosa y sabor inigualable.',
                    'short_description' => 'Chipa paraguaya con queso artesanal',
                    'price'             => '3.50',
                    'sale_price'        => '',
                    'stock'             => 80,
                    'image'             => 'pasteleria-producto-06.webp',
                    'category'          => 'Pastas',
                    'featured'          => true,
                    'on_sale'           => false,
                ),
                
                // Product 7: Sanguchitos de Miga
                array(
                    'name'              => 'Sanguchitos de Miga Surtidos',
                    'slug'              => 'sanguchitos-de-miga-surtidos',
                    'description'       => 'Caja de 12 sanguchitos de miga variados con rellenos gourmet: jamón serrano, queso artesanal, ensalada tropical y combinaciones especiales. Ideales para eventos y celebraciones.',
                    'short_description' => 'Surtido de 12 sanguchitos de miga',
                    'price'             => '22.00',
                    'sale_price'        => '',
                    'stock'             => 30,
                    'image'             => 'pasteleria-producto-07.webp',
                    'category'          => 'Pastas',
                    'featured'          => false,
                    'on_sale'           => false,
                ),
                
                // Product 8: Tarta de Frutas
                array(
                    'name'              => 'Tarta de Frutas de Estación',
                    'slug'              => 'tarta-de-frutas-de-estacion',
                    'description'       => 'Tarta fresca preparada con frutas de estación seleccionadas minuciosamente. Base de masa manteca casera, relleno de crema pastelera italiana y frutas frescas decoradas artísticamente.',
                    'short_description' => 'Tarta con frutas frescas de estación',
                    'price'             => '28.50',
                    'sale_price'        => '',
                    'stock'             => 20,
                    'image'             => 'pasteleria-producto-08.webp',
                    'category'          => 'Tortas',
                    'featured'          => true,
                    'on_sale'           => false,
                ),
            ),
            
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
                    'imagen_portada' => 'pasteleria-hero-01.webp',
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
                    'imagen_portada' => 'pasteleria-hero-02.webp',
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
                    'imagen_portada' => 'pasteleria-hero-02.webp',
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

<label>Asunto
    [text* asunto] </label>

<label>Tu mensaje (opcional)
    [textarea mensaje] </label>

[submit "Enviar"]',
                ),
                array(
                    'name'     => 'Newsletter Pastelería',
                    'form_tag' => '<div id="news-form"><div class="row">
<div class="col-xs-12 col-lg-9"><label> [email* email placeholder"Ingresá tu e-mail"] </label></div>
<div class="col-xs-12 col-lg-3">[submit "ENVIAR"]</div>
</div></div>',
                ),
                array(
                    'name'     => 'Consulta Pedidos Pastelería',
                    'form_tag' => '<div class="prod-form row d-flex justify-content-center">

<div class="col-12 col-md-6">
<label>Nombre (obligatorio)
    [text* nombre] </label>
</div>

<div class="col-12 col-md-6">
<label>Correo electrónico (obligatorio)
    [email* email] </label>
</div>

</div>

<div class="prod-form row d-flex justify-content-center">
<div class="col-12 col-md-6">
<label>Localidad
    [text localidad] </label>
</div>

<div class="col-12 col-md-6">
<label>Teléfono
    [text telefono] </label>
</div>

</div>

<div class="prod-form row d-flex justify-content-center">

<div class="col-12 col-md-12">
<label>Tu consulta
    [textarea mensaje] </label>
</div>

</div>

[submit "Enviar Consulta"]',
                ),
            ),
            
            // Home page configuration (ACF options)
            'home' => array(
                // Sliders for main carousel
                'slider_1' => array(
                    'imagen' => 'pasteleria-hero-01.webp',
                    'texto' => 'Delicias Artesanales',
                    'link' => '/shop',
                ),
                
                'slider_2' => array(
                    'imagen' => 'pasteleria-hero-02.webp',
                    'texto' => 'Tortas Personalizadas',
                    'link' => '/shop',
                ),
                
                'slider_3' => array(
                    'imagen' => 'pasteleria-cover.webp',
                    'texto' => 'Pedidos al Mayor',
                    'link' => '/contacto',
                ),
                
                'slider_4' => array(
                    'imagen' => '',
                    'texto' => '',
                    'link' => '',
                ),
                
                'slider_5' => array(
                    'imagen' => '',
                    'texto' => '',
                    'link' => '',
                ),
                
                // Product blocks
                'product_blocks' => array(
                    array(
                        'titulo'      => 'Productos Destacados',
                        'descripcion' => 'Nuestras creaciones más populares',
                        'tipo'        => 'destacados',
                        'cantidad'    => 4,
                        'layout'      => 'columnas',
                        'columnas'    => 'col-lg-4',
                        'card_style'  => 'hover_visual',
                    ),
                    array(
                        'titulo'      => 'Todas Nuestras Delicias',
                        'descripcion' => 'Explora nuestro catálogo completo',
                        'tipo'        => 'ultimos',
                        'cantidad'    => 6,
                        'layout'      => 'carousel',
                        'columnas'    => 'col-lg-4',
                        'card_style'  => 'hover_visual',
                    ),
                ),
                
                // Newsletter section
                'newsletter' => array(
                    'titulo'           => 'Suscribite a Nuestro Newsletter',
                    'descripcion'      => 'Recibe promociones exclusivas y novedades sobre nuestros productos',
                    'news_bg'          => 'pasteleria-newsbg.webp',
                    'formulario_news'  => 'Newsletter Pastelería',
                ),
                
                // Product inquiry form
                'formulario_producto' => 'Consulta Pedidos Pastelería',
                
                // Social media section
                'redes_seccion' => array(
                    'titulo'      => 'Síguenos en Redes',
                    'descripcion' => 'Conecta con nosotros para ver nuestras últimas creaciones',
                    'fondo_redes' => 'pasteleria-redesbg.webp',
                ),
                
                // Featured carousel title
                'titulo_carrusel_destacados' => 'Nuestras Tentaciones',
                'descripcion_carrusel_destacados' => 'Las creaciones que nuestros clientes más aman',
                
                // Featured products carousel
                'carrusel_productos_destacados' => array(
                    array(
                        'imagen'            => 'pasteleria-tentacion-01.webp',
                        'nombre_del_link'   => 'Croissant de Mantequilla',
                        'link'              => array( 'url' => '/shop', 'title' => 'Croissant de Mantequilla', 'target' => '' ),
                    ),
                    array(
                        'imagen'            => 'pasteleria-tentacion-02.webp',
                        'nombre_del_link'   => 'Lemon Pie Casero',
                        'link'              => array( 'url' => '/shop', 'title' => 'Lemon Pie Casero', 'target' => '' ),
                    ),
                    array(
                        'imagen'            => 'pasteleria-tentacion-03.webp',
                        'nombre_del_link'   => 'Torta Red Velvet',
                        'link'              => array( 'url' => '/shop', 'title' => 'Torta Red Velvet', 'target' => '' ),
                    ),
                    array(
                        'imagen'            => 'pasteleria-tentacion-04.webp',
                        'nombre_del_link'   => 'Torta Chocolate',
                        'link'              => array( 'url' => '/shop', 'title' => 'Torta Chocolate', 'target' => '' ),
                    ),
                    array(
                        'imagen'            => 'pasteleria-tentacion-05.webp',
                        'nombre_del_link'   => 'Chipa Paraguaya',
                        'link'              => array( 'url' => '/shop', 'title' => 'Chipa Paraguaya', 'target' => '' ),
                    ),
                    array(
                        'imagen'            => 'pasteleria-tentacion-06.webp',
                        'nombre_del_link'   => 'Tarta de Frutas',
                        'link'              => array( 'url' => '/shop', 'title' => 'Tarta de Frutas', 'target' => '' ),
                    ),
                ),
                
                // Sections visibility
                'sections' => array(
                    'slide'              => true,
                    'productos-1'        => true,
                    'productos-carrusel' => true,
                    'news'               => true,
                    'redes'              => true,
                    'clientes'           => false,
                ),
            ),
            
            'custom_css' => <<<CSS
/* Pastelería Demo - Estilos personalizados */
#clientes-botonera { 
    display: none !important; 
}

.main-slider-class {
    height: 600px !important;
}
.main-slider-class .slick-list,
.main-slider-class .slick-track,
.main-slider-class .slick-slide img {
    height: 100% !important;
    object-fit: cover !important;
}

.carousel-item > div {
    height: 650px !important;
}
CSS,
            
            // Menu for Pastelería demo
            'menu' => array(
                'name'  => 'Menú Pastelería',
                'items' => array(
                    array( 'title' => 'Inicio', 'url' => '/', 'parent' => null ),
                    array( 'title' => 'Tienda', 'url' => '/shop', 'parent' => null ),
                    array( 'title' => 'Sobre Nosotros', 'url' => '/sobre-nosotros', 'parent' => null ),
                    array( 'title' => 'Preguntas Frecuentes', 'url' => '/preguntas-frecuentes', 'parent' => null ),
                    array( 'title' => 'Contacto', 'url' => '/contacto', 'parent' => null ),
                    array( 'title' => 'Blog', 'url' => '/blog', 'parent' => null ),
                ),
            ),
        );
    }
}
