<?php
/**
 * Tarjeta de Producto - Estilo Clásico (01)
 * 
 * Muestra la información del producto de forma estándar:
 * Imagen + Título + Precio + Botón Agregar al Carrito
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

// Clases del botón
$button_class = 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart';
?>

<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
    <?php echo get_the_post_thumbnail( get_the_ID(), 'woocommerce_thumbnail', array( 'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail' ) ); ?>
    <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
    <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
</a>
<a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id() ) ); ?>" 
   data-quantity="1" 
   class="<?php echo esc_attr( $button_class ); ?>" 
   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" 
   aria-label="Add to cart: &quot;<?php the_title(); ?>&quot;" 
   rel="nofollow">
    <?php esc_html_e( 'Agregar al carrito', 'woocommerce' ); ?>
</a>
