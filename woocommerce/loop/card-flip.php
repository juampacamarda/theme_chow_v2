<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$image_url = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
if ( ! $image_url ) {
    $image_url = wc_placeholder_img_src( 'medium' );
}
?>

<div class="chow-product-card-03 chow-card-flip" tabindex="0" role="group" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
  <div class="chow-card-flip-inner">
    <!-- Front: Imagen y Título -->
    <div class="chow-card-front" style="background-image: url('<?php echo esc_url( $image_url ); ?>')">
      <div class="chow-card-front-title">
        <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
      </div>
    </div>

    <!-- Back: Información completa -->
    <div class="chow-card-back">
      <div class="chow-card-back-content">
        <h3 class="woocommerce-loop-product__title"><?php echo wp_kses_post( $product->get_name() ); ?></h3>

        <div class="chow-card-category">
          <?php echo wc_get_product_category_list( $product->get_id(), ', ' ); ?>
        </div>

        <div class="chow-card-excerpt">
          <?php
          $short = $product->get_short_description();
          if ( ! $short ) {
              $short = wp_trim_words( $product->get_description(), 18, '...' );
          }
          echo wp_kses_post( wp_strip_all_tags( $short ) );
          ?>
        </div>

        <div class="chow-card-price">
          <?php echo $product->get_price_html(); ?>
        </div>

        <div class="chow-card-actions">
          <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="button chow-btn-view">
            <?php esc_html_e( 'Ver producto', 'chow' ); ?>
          </a>
          <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
