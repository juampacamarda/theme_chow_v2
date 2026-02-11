<?php
/**
 * Sección de Productos Dinámicos
 * Utiliza un campo repeater en ACF para múltiples bloques de productos
 * Cada bloque puede mostrar productos por categoría, últimos, destacados u ofertas
 * Con opciones de layout: grid de columnas o carrusel Owl Carousel
 * 
 * Estructura sincronizada con los estilos de WooCommerce estándar
 */
?>

<section id="productos-dinamicos" class="productos-section woocommerce">
    <div class="container-fluid">
        <?php
        if ( have_rows( 'bloques_productos', 'option' ) ) :
            while ( have_rows( 'bloques_productos', 'option' ) ) : the_row();
                
                // Obtener valores del sub-campo
                $titulo = get_sub_field( 'titulo' );
                $descripcion = get_sub_field( 'descripcion' );
                $tipo = get_sub_field( 'tipo' );
                $cantidad = get_sub_field( 'cantidad' ) ? get_sub_field( 'cantidad' ) : 8;
                $layout = get_sub_field( 'layout' );
                $columnas = get_sub_field( 'columnas' ) ? get_sub_field( 'columnas' ) : 'col-lg-3';
                
                // Construcción del array de argumentos para WP_Query
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => $cantidad,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                
                // Aplicar filtros según el tipo seleccionado
                if ( 'categoria' === $tipo ) {
                    $categoria = get_sub_field( 'categoria' );
                    if ( $categoria ) {
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'id',
                                'terms'    => $categoria,
                            ),
                        );
                    }
                } elseif ( 'destacados' === $tipo ) {
                    $args['meta_query'] = array(
                        array(
                            'key'   => '_featured',
                            'value' => 'yes',
                        ),
                    );
                } elseif ( 'ofertas' === $tipo ) {
                    $args['meta_query'] = array(
                        array(
                            'key'     => '_sale_price',
                            'value'   => 0,
                            'compare' => '>',
                            'type'    => 'NUMERIC',
                        ),
                    );
                }
                // Si tipo es 'ultimos', usa los argumentos por defecto
                
                // Ejecutar la consulta
                $products_query = new WP_Query( $args );
                
                ?>
                <div class="bloque-productos mb-5">
                    <div class="container">
                        <?php if ( $titulo ) : ?>
                            <div class="bloque-header mb-4">
                                <h2 class="titulo cursiva"><?php echo esc_html( $titulo ); ?></h2>
                                <?php if ( $descripcion ) : ?>
                                    <p class="descripcion"><?php echo wp_kses_post( $descripcion ); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        if ( 'carousel' === $layout ) :
                            // Layout: Carrusel Owl Carousel usando estructura WooCommerce
                            if ( $products_query->have_posts() ) :
                                ?>
                                <ul class="products productos-carousel owl-carousel owl-theme">
                                    <?php
                                    while ( $products_query->have_posts() ) : $products_query->the_post();
                                        global $product;
                                        
                                        // Obtener clases dinámicas como WooCommerce
                                        $product_class = 'product type-product post-' . get_the_ID() . ' status-publish';
                                        $product_class .= ' ' . ( $product->is_in_stock() ? 'instock' : 'outofstock' );
                                        if ( has_post_thumbnail() ) {
                                            $product_class .= ' has-post-thumbnail';
                                        }
                                        $product_class .= ' shipping-taxable purchasable product-type-' . $product->get_type();
                                        
                                        // Obtener categorías de forma correcta
                                        $terms = get_the_terms( get_the_ID(), 'product_cat' );
                                        if ( $terms && ! is_wp_error( $terms ) ) {
                                            foreach ( $terms as $term ) {
                                                $product_class .= ' product_cat-' . esc_attr( $term->slug );
                                            }
                                        }
                                        
                                        // Clases del botón
                                        $button_class = 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart';
                                        ?>
                                        <li class="<?php echo esc_attr( $product_class ); ?>">
                                            
                                                <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <?php echo get_the_post_thumbnail( get_the_ID(), 'woocommerce_thumbnail', array( 'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail' ) ); ?>
                                                    <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                                                    <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                                                </a>
                                                <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id() ) ); ?>" data-quantity="1" class="<?php echo esc_attr( $button_class ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="Add to cart: &quot;<?php the_title(); ?>&quot;" rel="nofollow"><?php esc_html_e( 'Agregar al carrito', 'woocommerce' ); ?></a>
                                            
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <?php
                                wp_reset_postdata();
                            else :
                                ?>
                                <p class="text-center text-muted">No hay productos disponibles en esta sección.</p>
                                <?php
                            endif;
                            ?>
                            <?php
                        else :
                            // Layout: Grid de columnas (por defecto) usando estructura WooCommerce
                            if ( $products_query->have_posts() ) :
                                ?>
                                <div class="products d-flex flex-wrap">
                                    <?php
                                    while ( $products_query->have_posts() ) : $products_query->the_post();
                                        global $product;
                                        
                                        // Obtener clases dinámicas como WooCommerce
                                        $product_class = 'product type-product post-' . get_the_ID() . ' status-publish';
                                        $product_class .= ' ' . ( $product->is_in_stock() ? 'instock' : 'outofstock' );
                                        if ( has_post_thumbnail() ) {
                                            $product_class .= ' has-post-thumbnail';
                                        }
                                        $product_class .= ' shipping-taxable purchasable product-type-' . $product->get_type();
                                        
                                        // Obtener categorías de forma correcta
                                        $terms = get_the_terms( get_the_ID(), 'product_cat' );
                                        if ( $terms && ! is_wp_error( $terms ) ) {
                                            foreach ( $terms as $term ) {
                                                $product_class .= ' product_cat-' . esc_attr( $term->slug );
                                            }
                                        }
                                        
                                        $product_class .= ' ' . esc_attr( $columnas ) . ' col-md-6 col-sm-12';
                                        
                                        // Clases del botón
                                        $button_class = 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart';
                                        ?>
                                        <div class="<?php echo esc_attr( $product_class ); ?>">
                                            <div class="wrap">
                                                <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <?php echo get_the_post_thumbnail( get_the_ID(), 'woocommerce_thumbnail', array( 'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail' ) ); ?>
                                                    <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                                                    <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                                                </a>
                                                <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id() ) ); ?>" data-quantity="1" class="<?php echo esc_attr( $button_class ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="Add to cart: &quot;<?php the_title(); ?>&quot;" rel="nofollow"><?php esc_html_e( 'Agregar al carrito', 'woocommerce' ); ?></a>
                                            </div>
                                        </div>
                                        <?php
                                    endwhile;
                                    ?>
                                </div>
                                <?php
                                wp_reset_postdata();
                            else :
                                ?>
                                <p class="text-center text-muted">No hay productos disponibles en esta sección.</p>
                                <?php
                            endif;
                            ?>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
                <?php
            endwhile;
        else :
            ?>
            <!-- Sin bloques de productos configurados -->
            <div class="container">
                <div class="alert alert-info" role="alert">
                    No hay bloques de productos configurados. Por favor, ve a Apariencia > Chow theme > Bloques de Productos y configura los bloques.
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</section>