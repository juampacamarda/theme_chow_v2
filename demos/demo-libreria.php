<?php
/**
 * Demo Librería - "Páginas de Tinta"
 * Bookstore demo with complete configuration for Chow Theme
 * 
 * This file contains all data needed to import the Librería demo:
 * - 8 products with metadata
 * - 3 product categories
 * - 4 pages (Institucional, FAQ, Contacto, Home)
 * - 3 Contact Form 7 forms
 * - Theme options and styling
 * - Navigation menu structure
 */

if ( ! function_exists( 'chow_get_demo_libreria' ) ) {
    function chow_get_demo_libreria() {
        return array(
            'id'          => 'libreria',
            'name'        => 'Librería "Páginas de Tinta"',
            'description' => 'Un sitio elegante para una tienda de libros con catálogo de novelas, poesía y autoayuda. Incluye 8 productos destacados, página de preguntas frecuentes y formularios de contacto.',
            'image'       => 'libreria-cover.png', // Demo card image
            'version'     => '1.0',
            'card_style'  => 'hover_visual', // Estilo de tarjeta por defecto: 'classic' o 'hover_visual'
            
            // Company Configuration
            'company' => array(
                'color_principal'       => '#2c3e50',
                'color_secundario'      => '#fdf5e6',
                'color_texto'           => '#5f5f5f',
                'color_fondo'           => '#ffffff',
                'logo_header_desktop'   => 'libreria-logo-color.png',
                'logo_header_mobile'    => 'libreria-logo-blanco.png',
                'logo_footer'           => 'libreria-logo-blanco.png',
                'direccion'             => 'Av. Corrientes 1234, Buenos Aires, Argentina',
                'telefonos'             => '+54 (11) 4567-8900',
                'mail'                  => 'info@paginasdetinta.com',
                'facebook_link'         => '#',
                'instagram_link'        => '#',
                'twitter_link'          => '#',
                'wsp_link'              => '5491145678900',
                'logos_legales'         => '',
            ),
            
            // Product Categories
            'categories' => array(
                array(
                    'name'        => 'Novelas',
                    'slug'        => 'novelas',
                    'description' => 'Novelas de ficción, misterio y aventura',
                ),
                array(
                    'name'        => 'Poesía',
                    'slug'        => 'poesia',
                    'description' => 'Colecciones de poesía clásica y contemporánea',
                ),
                array(
                    'name'        => 'Autoayuda',
                    'slug'        => 'autoayuda',
                    'description' => 'Libros de desarrollo personal y autoconocimiento',
                ),
            ),
            
            // Products (8 items)
            'products' => array(
                // Product 1: El Arte de la Lectura Crítica
                array(
                    'name'              => 'El Arte de la Lectura Crítica',
                    'slug'              => 'el-arte-de-la-lectura-critica',
                    'description'       => 'Una guía completa para desarrollar habilidades de análisis literario profundo. Ideal para estudiantes y amantes de la literatura que desean comprender mejor las obras clásicas y contemporáneas.',
                    'short_description' => 'Guía para análisis literario profundo',
                    'price'             => '34.99',
                    'sale_price'        => '',
                    'stock'             => 50,
                    'image'             => 'libreria-producto01.png',
                    'category'          => 'Autoayuda',
                    'featured'          => true,
                    'on_sale'           => false,
                ),
                
                // Product 2: Viajes por la Filosofía Oriental
                array(
                    'name'              => 'Viajes por la Filosofía Oriental',
                    'slug'              => 'viajes-por-la-filosofia-oriental',
                    'description'       => 'Un viaje fascinante a través de las principales corrientes filosóficas de Oriente. Descubre las enseñanzas del budismo, taoísmo y otras tradiciones que han moldeado civilizaciones.',
                    'short_description' => 'Exploración de filosofías orientales',
                    'price'             => '49.99',
                    'sale_price'        => '34.99',
                    'stock'             => 35,
                    'image'             => 'libreria-producto02.png',
                    'category'          => 'Autoayuda',
                    'featured'          => true,
                    'on_sale'           => true,
                ),
                
                // Product 3: Edición Limitada: Cartas
                array(
                    'name'              => 'Edición Limitada: Cartas',
                    'slug'              => 'edicion-limitada-cartas',
                    'description'       => 'Una colección única de cartas históricas nunca antes publicadas en español. Edición limitada de solo 500 copias. Incluye estuche de lujo y certificado de autenticidad.',
                    'short_description' => 'Edición limitada de cartas históricas',
                    'price'             => '89.99',
                    'sale_price'        => '',
                    'stock'             => 0,
                    'image'             => 'libreria-producto03.png',
                    'category'          => 'Novelas',
                    'featured'          => false,
                    'on_sale'           => false,
                ),
                
                // Product 4: Pack Escritura Creativa
                array(
                    'name'              => 'Pack Escritura Creativa',
                    'slug'              => 'pack-escritura-creativa',
                    'description'       => 'Set de 3 libros especialmente seleccionados para escritores en desarrollo. Incluye técnicas narrativas, análisis de estructura y ejercicios prácticos para potenciar tu creatividad.',
                    'short_description' => 'Set de 3 libros para escritores',
                    'price'             => '129.99',
                    'sale_price'        => '79.99',
                    'stock'             => 28,
                    'image'             => 'libreria-producto04.png',
                    'category'          => 'Autoayuda',
                    'featured'          => true,
                    'on_sale'           => true,
                ),
                
                // Product 5: El Enigma de las Mareas Negras
                array(
                    'name'              => 'El Enigma de las Mareas Negras',
                    'slug'              => 'el-enigma-de-las-mareas-negras',
                    'description'       => 'Un thriller psicológico cautivador que te mantendrá en vilo hasta la última página. Un detective investiga desapariciones misteriosas en un pueblo costero con secretos oscuros.',
                    'short_description' => 'Thriller psicológico adictivo',
                    'price'             => '24.99',
                    'sale_price'        => '',
                    'stock'             => 120,
                    'image'             => 'libreria-producto05.png',
                    'category'          => 'Novelas',
                    'featured'          => true,
                    'on_sale'           => false,
                    'bestseller'        => true,
                ),
                
                // Product 6: Domina Python
                array(
                    'name'              => 'Domina Python',
                    'slug'              => 'domina-python',
                    'description'       => 'Del principiante al experto en Python. Incluye ejercicios prácticos, proyectos reales y una comunidad online para resolver dudas. Más de 500 páginas de contenido actualizado.',
                    'short_description' => 'Aprende Python desde cero',
                    'price'             => '59.99',
                    'sale_price'        => '',
                    'stock'             => 75,
                    'image'             => 'libreria-producto06.png',
                    'category'          => 'Autoayuda',
                    'featured'          => true,
                    'on_sale'           => false,
                ),
                
                // Product 7: Versos Nocturnos
                array(
                    'name'              => 'Versos Nocturnos',
                    'slug'              => 'versos-nocturnos',
                    'description'       => 'Colección de poesía contemporánea que explora los temas del amor, la soledad y la esperanza. Con ilustraciones originales de artistas nacionales. Premio Nacional de Poesía 2024.',
                    'short_description' => 'Poesía contemporánea premiada',
                    'price'             => '39.99',
                    'sale_price'        => '27.99',
                    'stock'             => 42,
                    'image'             => 'libreria-producto07.png',
                    'category'          => 'Poesía',
                    'featured'          => false,
                    'on_sale'           => true,
                ),
                
                // Product 8: Manuscritos Recuperados
                array(
                    'name'              => 'Manuscritos Recuperados',
                    'slug'              => 'manuscritos-recuperados',
                    'description'       => 'Una colección exclusiva de obras inéditas de autores clásicos, restauradas y publicadas por primera vez en la era moderna. Encuadernación de lujo con páginas de papel de algodón.',
                    'short_description' => 'Obras inéditas de autores clásicos',
                    'price'             => '199.99',
                    'sale_price'        => '149.99',
                    'stock'             => 15,
                    'image'             => 'libreria-producto08.png',
                    'category'          => 'Novelas',
                    'featured'          => true,
                    'on_sale'           => true,
                ),
            ),
            
            // Pages
            'pages' => array(
                // Page 0: Inicio (Home page)
                array(
                    'title'   => 'Inicio',
                    'slug'    => 'inicio',
                    'content' => '',
                    'template' => 'index-plantilla',
                ),
                
                // Page 1: Sobre Nosotros
                array(
                    'title'   => 'Sobre Nosotros',
                    'slug'    => 'sobre-nosotros',
                    'content' => '<p>Bienvenido a <strong>Páginas de Tinta</strong>, tu espacio dedicado a la magia de los libros.</p>
<p>Desde 1995, nos comprometemos a llevar las mejores obras literarias a tus manos. Nuestra selección cuidadosa de títulos abarca desde clásicos atemporales hasta los bestsellers más contemporáneos.</p>
<p><strong>Nuestra Misión:</strong> Promover la lectura como herramienta fundamental para el conocimiento, entretenimiento y transformación personal.</p>
<p><strong>¿Por qué elegirnos?</strong></p>
<ul>
<li>Selección curada por expertos literarios</li>
<li>Envíos rápidos y seguros a todo el país</li>
<li>Atención personalizada a cada cliente</li>
<li>Garantía de satisfacción en todas nuestras compras</li>
<li>Comunidad activa de lectores y clubes de lectura</li>
</ul>
<p>Creemos que cada libro es una puerta a nuevos mundos, y estamos aquí para ayudarte a encontrar tu próxima lectura favorita.</p>',
                    'template' => 'default',
                ),
                
                // Page 2: Preguntas Frecuentes
                array(
                    'title'   => 'Preguntas Frecuentes',
                    'slug'    => 'preguntas-frecuentes',
                    'content' => '<p>Encuentra respuestas a las preguntas más comunes de nuestros clientes.</p>',
                    'template' => 'flexible-page',
                    'flexible_content' => array(
                        array(
                            'acf_fc_layout' => 'collapse_accordion',
                            'items' => array(
                                array(
                                    'title'   => '¿Cuáles son los costos de envío?',
                                    'content' => 'El envío es GRATIS para compras superiores a $500. Para órdenes menores, el costo es de $50-150 según tu localidad. Los envíos se realizan en 3-5 días hábiles.',
                                ),
                                array(
                                    'title'   => '¿Puedo devolver un libro?',
                                    'content' => 'Sí, tenemos una política de devolución de 30 días sin preguntas. El libro debe estar en perfectas condiciones y con su empaque original. Los costos de envío de devolución corren por cuenta del cliente.',
                                ),
                                array(
                                    'title'   => '¿Ofrecen envío internacional?',
                                    'content' => 'Actualmente solo enviamos dentro del país. Estamos trabajando en ampliar nuestras opciones de envío internacional. Contáctanos si tienes una solicitud especial.',
                                ),
                                array(
                                    'title'   => '¿Tienen libros en otros idiomas?',
                                    'content' => 'Sí, contamos con una sección de libros en inglés, portugués y francés. Puedes filtrar por idioma en nuestro catálogo. También aceptamos pedidos especiales.',
                                ),
                                array(
                                    'title'   => '¿Cómo puedo contactarlos?',
                                    'content' => 'Puedes escribirnos a través de nuestro formulario de contacto, por email a info@paginasdetinta.com, o llamarnos al +54 (11) 4567-8900. Respondemos en máximo 24 horas.',
                                ),
                                array(
                                    'title'   => '¿Tienen programas de fidelización?',
                                    'content' => 'Sí, al suscribirse a nuestro newsletter reciben descuentos exclusivos, promociones anticipadas y acceso a eventos especiales. Además, por cada compra acumulan puntos canjeables.',
                                ),
                            ),
                        ),
                    ),
                ),
                
                // Page 3: Contacto
                array(
                    'title'   => 'Contacto',
                    'slug'    => 'contacto',
                    'content' => '<p>¿Tienes preguntas o comentarios? Nos encantaría escucharte. Completa el formulario a continuación y nos pondremos en contacto lo antes posible.</p>
<p><strong>Información de contacto directo:</strong></p>
<ul>
<li><strong>Email:</strong> info@paginasdetinta.com</li>
<li><strong>Teléfono:</strong> +54 (11) 4567-8900</li>
<li><strong>Horario de atención:</strong> Lunes a Viernes, 9:00 - 18:00 hs</li>
<li><strong>Dirección:</strong> Av. Corrientes 1234, Buenos Aires, Argentina</li>
</ul>',
                    'template' => 'default',
                ),
            ),
            
            // Contact Form 7 Forms
            'forms' => array(
                array(
                    'name'     => 'Contacto Librería',
                    'form_tag' => '[text* nombre placeholder "Tu nombre"][email* email placeholder "Tu email"][text* asunto placeholder "Asunto"][textarea mensaje placeholder "Tu mensaje"]',
                ),
                array(
                    'name'     => 'Newsletter Librería',
                    'form_tag' => '[email* your-email placeholder "Ingresá tu e-mail"][submit "ENVIAR"]',
                ),
                array(
                    'name'     => 'Contacto Productos Librería',
                    'form_tag' => '[text* nombre placeholder "Tu nombre"][email* email placeholder "Tu email"][text localidad placeholder "Localidad"][tel telefono placeholder "Teléfono"][textarea mensaje placeholder "Tu mensaje"]',
                ),
            ),
            
            // Home Configuration
            'home' => array(
                // Slider 1 - Individual slider fields (SCF expects slider_1, slider_2, etc.)
                'slider_1' => array(
                    'imagen' => 'libreria-slide01.png',
                    'texto' => 'Descubre Nuevos Mundos',
                    'link' => '/tienda',
                ),
                
                'slider_2' => array(
                    'imagen' => 'libreria-slide02.png',
                    'texto' => 'Literatura Premium',
                    'link' => '/tienda',
                ),
                
                'slider_3' => array(
                    'imagen' => 'libreria-slide03.png',
                    'texto' => 'Ediciones Limitadas',
                    'link' => '/tienda',
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
                        'titulo'      => 'Novedades',
                        'descripcion' => 'Las últimas incorporaciones a nuestro catálogo',
                        'tipo'        => 'ultimos',
                        'cantidad'    => 4,
                        'layout'      => 'columnas',
                        'columnas'    => 'col-lg-3',
                        'card_style'  => 'hover_visual',
                    ),
                    array(
                        'titulo'      => 'Recomendados',
                        'descripcion' => 'Nuestras mejores selecciones',
                        'tipo'        => 'destacados',
                        'cantidad'    => 6,
                        'layout'      => 'carousel',
                        'columnas'    => 'col-lg-3',
                        'card_style'  => 'hover_visual',
                    ),
                ),
                
                // Newsletter section (GROUP field)
                'newsletter' => array(
                    'titulo'       => 'Suscribite a Nuestro Newsletter',
                    'descripcion'  => 'Recibe ofertas exclusivas y novedades literarias directamente en tu inbox',
                    'fondo'        => 'fondo-news.png',
                    'form_id'      => 'Newsletter Librería', // Will be replaced with actual form ID
                ),
                
                // Redes (social) section (GROUP field)
                'redes_seccion' => array(
                    'titulo'      => 'Síguenos en Redes',
                    'descripcion' => 'Conecta con nosotros en nuestras redes sociales',
                    'fondo_redes' => 'fondo-redes.png',
                ),
                
                // Productos destacados (REPEATER field - 6 productos)
                'carrusel_productos_destacados' => array(
                    array(
                        'nombre'      => 'El Quijote de la Mancha',
                        'descripcion' => 'La novela clásica de Miguel de Cervantes',
                        'imagen'      => 'libreria-producto01.png',
                        'link'        => '/?p=1',
                        'precio'      => '34.99',
                    ),
                    array(
                        'nombre'      => 'Orgullo y Prejuicio',
                        'descripcion' => 'Novela romántica de Jane Austen',
                        'imagen'      => 'libreria-producto02.png',
                        'link'        => '/?p=2',
                        'precio'      => '29.99',
                    ),
                    array(
                        'nombre'      => 'Cien Años de Soledad',
                        'descripcion' => 'Obra maestra de García Márquez',
                        'imagen'      => 'libreria-producto03.png',
                        'link'        => '/?p=3',
                        'precio'      => '35.99',
                    ),
                    array(
                        'nombre'      => 'La Metamorfosis',
                        'descripcion' => 'Novela de Franz Kafka',
                        'imagen'      => 'libreria-producto04.png',
                        'link'        => '/?p=4',
                        'precio'      => '18.99',
                    ),
                    array(
                        'nombre'      => 'El Gran Gatsby',
                        'descripcion' => 'Novela de F. Scott Fitzgerald',
                        'imagen'      => 'libreria-producto05.png',
                        'link'        => '/?p=5',
                        'precio'      => '22.99',
                    ),
                    array(
                        'nombre'      => '1984',
                        'descripcion' => 'Novela distópica de George Orwell',
                        'imagen'      => 'libreria-producto06.png',
                        'link'        => '/?p=6',
                        'precio'      => '25.99',
                    ),
                ),
                
                // Sections visibility
                'sections' => array(
                    'slide'              => true,
                    'productos-1'        => true,
                    'productos-carrusel' => true,
                    'news'               => true,
                    'redes'              => true,
                    'clientes'           => false, // Hidden for librería
                ),
            ),
            
            // Theme Options
            'theme_options' => array(
                'color_principal'  => '#2c3e50',
                'color_secundario' => '#fdf5e6',
                'color_texto'      => '#5f5f5f',
                'color_fondo'      => '#ffffff',
            ),
            
            // Custom CSS
            'custom_css' => '/* Librería Demo - Estilos personalizados */
#clientes-botonera { 
    display: none !important; 
}',
            
            // Menu
            'menu' => array(
                'name'  => 'Menú Librería',
                'items' => array(
                    array( 'title' => 'Inicio', 'url' => '/', 'parent' => null ),
                    array( 'title' => 'Tienda', 'url' => '/tienda', 'parent' => null ),
                    array( 'title' => 'Sobre Nosotros', 'url' => '/sobre-nosotros', 'parent' => null ),
                    array( 'title' => 'Preguntas Frecuentes', 'url' => '/preguntas-frecuentes', 'parent' => null ),
                    array( 'title' => 'Contacto', 'url' => '/contacto', 'parent' => null ),
                    array( 'title' => 'Blog', 'url' => '/blog', 'parent' => null ),
                ),
            ),
        );
    }
}
