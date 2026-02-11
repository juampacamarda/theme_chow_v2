<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     9.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$defaults = array(
	'quantity'   => 1,
	'class'      => implode(
		' ',
		array_filter(
			array(
				'button',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
			)
		)
	),
	'attributes' => array(),
);

$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

$id = $product->get_id();
$link_id = 'product-' . $id . '-add-to-cart-description';

// Set up additional attributes.
$args['attributes']['data-product_id']  = $id;
$args['attributes']['data-product_sku'] = $product->get_sku();
$args['attributes']['aria-describedby'] = $link_id;

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s<span id="%s" hidden>%s</span></a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() ),
		$link_id,
		wp_kses_post( $product->add_to_cart_description() )
	),
	$product,
	$args
);