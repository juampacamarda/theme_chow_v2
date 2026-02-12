<?php
/**
 * The template for displaying product content within loops
 *
 * This version has been customized to use the Chow theme modular card system
 * following WooCommerce 9.4.0 structure.
 *
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility
if ( ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
	return;
}

$card_style = chow_get_card_style();
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php chow_load_product_card( $product, $card_style ); ?>
</li>
