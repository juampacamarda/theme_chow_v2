<?php
/**
 * Tarjeta de Producto - Estilo Hover Visual (02)
 * 
 * Diseño de tarjeta visual donde:
 * - La imagen ocupa el 100% del espacio
 * - Al hacer hover, aparece un overlay con la información del producto
 * - Título, precio y botón se revelan en el hover
 * 
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

// Obtener URL de imagen o placeholder
$image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' ) : wc_placeholder_img_src();

// Clases del botón
$button_class = 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart';
?>

<div class="chow-card-hover-visual" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"> 
    <a href="<?php the_permalink(); ?>" class="chow-card-hover-overlay">
        <div class="chow-card-hover-content">
            <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
            <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
            <button class="<?php echo esc_attr( $button_class ); ?>">Ver Producto</button>
        </div>
    </a>
</div>
