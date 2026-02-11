<?php
// Include modular files
require_once get_template_directory() . '/inc/acf-config.php';

/*Este código muestra los estilos que aplicamos en el archivo custom-login-styles.css dentro de la carpeta login*/
function custom_login() {
  wp_enqueue_style( 'custom-login-css', get_stylesheet_directory_uri() . '/assets/css/chow-base-style.css', array(), '1.0' );
}
add_action( 'login_head', 'custom_login' );
function custom_url_login() {
	return '#'; // Ponemos la web que queramos.
}
add_filter( 'login_headerurl', 'custom_url_login' );

//colores

function chow_theme_customize_register($wp_customize) {
    // Color principal
    $wp_customize->add_setting('chow_ppal', array(
        'default' => '#D60B52',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'chow_ppal', array(
        'label' => __('Color Principal', 'chow-theme01'),
        'section' => 'colors',
        'settings' => 'chow_ppal',
    )));
    
    // Color secundario
    $wp_customize->add_setting('chow_secundario', array(
        'default' => '#1d71b8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'chow_secundario', array(
        'label' => __('Color Secundario', 'chow-theme01'),
        'section' => 'colors',
        'settings' => 'chow_secundario',
    )));

    // Color del texto
    $wp_customize->add_setting('chow_txt', array(
        'default' => '#5f5f5f',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'chow_txt', array(
        'label' => __('Color del Texto', 'chow-theme01'),
        'section' => 'colors',
        'settings' => 'chow_txt',
    )));

    // Color de fondo
    $wp_customize->add_setting('chow_blanco', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'chow_blanco', array(
        'label' => __('Color de Fondo', 'chow-theme01'),
        'section' => 'colors',
        'settings' => 'chow_blanco',
    )));
}
add_action('customize_register', 'chow_theme_customize_register');

function chow_theme_customize_css() {
    ?>
    <style type="text/css">
        :root {
            --chow_ppal: <?php echo get_theme_mod('chow_ppal', '#D60B52'); ?>;
            --chow_secundario: <?php echo get_theme_mod('chow_secundario', '#1d71b8'); ?>;
            --chow_txt: <?php echo get_theme_mod('chow_txt', '#5f5f5f'); ?>;
            --chow_blanco: <?php echo get_theme_mod('chow_blanco', '#ffffff'); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'chow_theme_customize_css');



//abajo carga estilos

if (!function_exists('theme_scripts')) {
    function theme_scripts() {
        // Estilos
        wp_enqueue_style('theme-style', get_template_directory_uri() . '/assets/css/chow-base-style.css');
        wp_enqueue_style('theme-wc', get_template_directory_uri() . '/assets/css/chow-wc.css');
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
        wp_enqueue_style('owl', get_template_directory_uri() . '/assets/owlcarousel/owl.carousel.min.css');
        wp_enqueue_style('owltheme', get_template_directory_uri() . '/assets/owlcarousel/owl.carousel.min.css');
        wp_enqueue_style('animatecss', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');
        wp_enqueue_style('aoscss', 'https://unpkg.com/aos@next/dist/aos.css');
        
        // Scripts
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js');
        wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js');
        wp_enqueue_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js');
        wp_enqueue_script('prefix-font-awesome', 'https://kit.fontawesome.com/ef7428ba99.js');
        wp_enqueue_script('theme-js', get_template_directory_uri() . '/assets/js/theme-chow.js');
        wp_enqueue_script('owljs', get_template_directory_uri() . '/assets/owlcarousel/owl.carousel.min.js');
        wp_enqueue_script('aosjs', 'https://unpkg.com/aos@next/dist/aos.js');
    }
    add_action('wp_enqueue_scripts', 'theme_scripts');
}


//agregar custom logo
function mytheme_setup() {
    add_theme_support('custom-logo', array(
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );}
add_action('after_setup_theme', 'mytheme_setup');

//cambiar la clase del logo
add_filter( 'get_custom_logo', 'change_logo_class' );

function change_logo_class( $html ) {

    $html = str_replace( 'custom-logo-link', 'home-link-lg d-none d-lg-block', $html );
	$html = str_replace( 'custom-logo', 'img-fluid',  $html );

    return $html;
}
add_filter( 'get_custom_logo', function( $html, $blog_id ) {
    $html = str_replace( 'itemprop="logo"', 'itemprop="logo" title=""', $html );
    return $html;
 }, 10, 2 );

//nav menu and thumbnails

if (function_exists('register_nav_menus')){
	register_nav_menus (array('superior'=>'Menu Principal Superior'));
	register_nav_menus (array('wootienda'=>'menu tienda'));
	register_nav_menus (array('footer'=>'Menu footer b'));

}
function add_link_atts($atts) {
$atts['class'] = "nav-link";
return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_link_atts');
//navlink


if ( function_exists( 'add_theme_support' ) ) { 
		add_theme_support( 'post-thumbnails' );
}

//mostrar barra admin
if (is_user_logged_in()) {
    show_admin_bar(true);
}#end if

//abajo carga shortcodes
add_filter('the_excerpt', 'do_shortcode');

// Procesar shortcodes en campos ACF (textarea y text)
add_filter('acf/format_value/type=textarea', function( $value ) {
    if ( is_string( $value ) ) {
        return do_shortcode( $value );
    }
    return $value;
});

add_filter('acf/format_value/type=text', function( $value ) {
    if ( is_string( $value ) ) {
        return do_shortcode( $value );
    }
    return $value;
});

// Procesar shortcodes en todos los campos ACF (fallback seguro)
add_filter('acf/format_value', function( $value ) {
    if ( is_string( $value ) ) {
        return do_shortcode( $value );
    }
    return $value;
});

// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');


//subir svg
function custom_mimes( $mimes = array() ) {
	// New allowed mime types.
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'custom_mimes' );
//fin subir svg

//woocomerce
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
    echo '<main class="site-content"><div class="container py-3">';
}

function my_theme_wrapper_end() {
    echo '</div></main>';
}

function plantilla_add_woocommerce_support() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => '',
        'single_image_width'    => '',

        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );

    // Single Product Gallery Addons
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );  
    
}
add_action( 'after_setup_theme', 'plantilla_add_woocommerce_support');

function zona_widgets_woo() {

    register_sidebar( array(
        'name'          => 'widget-woocommerce',
        'id'            => 'id-nueva-zona',
        'description'   => 'Descripción de la nueva Zona de Widgets',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    
    }
add_action( 'widgets_init', 'zona_widgets_woo' );

function relacionados(){
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products' );
 }
 add_action( 'woocommerce_before_main_content', 'relacionados' );


/* widget buscador header */
function widget_buscador() {
    register_sidebar( array(
    'name' => 'Nueva Zona Widget',
    'id' => 'zonabuscador',
    'description' => 'Descripción de la nueva Zona de Widgets',
    'before_widget' => '<div class="widget-class">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    ) );
    }
	add_action( 'widgets_init', 'widget_buscador' );
	
/*
 * Register Custom Navigation Walker
 */
function register_navwalker(){
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
add_action( 'after_setup_theme', 'register_navwalker' );


//agregar iconito carro



add_action( 'woocommerce_before_shop_loop_item_title', function() {
   global $product;
   if ( !$product->is_in_stock() ) {
       echo '<span class="now_sold">AGOTADO</span>';
   }
});

/*solo productos en la busqueda*/

function chow_search_filter_pages($query) {
    if ($query->is_search) {
        $query->set('post_type', 'product');
        $query->set( 'wc_query', 'product_query' );
    }
    return $query;
}
add_filter('pre_get_posts','chow_search_filter_pages');

/* funcion formulario de productos */
function chow_formulario_productos(){
    echo get_template_part( './contactoproductos' );
}

/* funcion slide shop */
function chow_slide_shop(){
    echo get_template_part( './home/slide' );
}

add_action( 'woocommerce_before_main_content', 'chow_slide_shop', 5 );

/*function aviso_tienda(){
    echo get_template_part( './woocommerce/aviso-tienda' );
}*/

/* formulario de productos */

/* agregar formulario de productos */
add_action( 'woocommerce_after_single_product_summary', 'chow_formulario_productos', 16 );

//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

//remove_action ( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

//add_action( 'woocommerce_before_cart_totals', 'aviso_tienda' );

/* Modifica Listado de provincias

add_filter('woocommerce_states', 'lista_provincias');

function lista_provincias( $provincias ) {

$provincias ['AR'] = array(

'C' => 'Ciudad Autónoma de Buenos Aires',

); //CODIGO Y NOMBRES DE PROVINCIAS DISPONIBLES

return $provincias;

}
*/

/*ocultar add to cart del loop*/ 
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');

/**

 * Cambiar el número de columnas

 */

add_filter('loop_shop_columns', 'loop_columns', 999);

if (!function_exists('loop_columns')) {

 function loop_columns() {

 return 4; //4 productos por columna

 }

}

/* Filtro para Bootstrap 5 data attributes en navwalker */
add_filter( 'nav_menu_link_attributes', 'chow_bs5_dropdown_data_attribute', 20, 3 );
function chow_bs5_dropdown_data_attribute( $atts, $item, $args ) {
    if ( is_a( $args->walker, 'WP_Bootstrap_Navwalker' ) ) {
        if ( array_key_exists( 'data-toggle', $atts ) ) {
            unset( $atts['data-toggle'] );
            $atts['data-bs-toggle'] = 'dropdown';
        }
    }
    return $atts;
}