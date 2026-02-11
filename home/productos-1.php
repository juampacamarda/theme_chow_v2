<?php
/**
 * Sección de Productos Dinámicos
 * Utiliza un campo repeater en ACF para múltiples bloques de productos
 * Cada bloque puede mostrar productos por categoría, últimos, destacados u ofertas
 * Con opciones de layout: grid de columnas o carrusel Owl Carousel
 */
?>

<section id="productos-dinamicos" class="productos-section">
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
                                'field'    => 'slug',
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
                            // Layout: Carrusel Owl Carousel
                            ?>
                            <div class="productos-carousel owl-carousel owl-theme" data-items="4" data-margin="20" data-responsive='{"0":{"items":1},"576":{"items":2},"768":{"items":3},"992":{"items":4}}'>
                                <?php
                                if ( $products_query->have_posts() ) :
                                    while ( $products_query->have_posts() ) : $products_query->the_post();
                                        global $product;
                                        ?>
                                        <div class="producto-item">
                                            <div class="productocard">
                                                <div class="imagen-prodest" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: get_template_directory_uri() . '/assets/img/default-img.png' ); ?>'); background-size:cover; background-repeat:no-repeat; min-height:200px;"></div>
                                                <div class="dataproducto p-3">
                                                    <h3 class="producto-nombre cursiva"><?php the_title(); ?></h3>
                                                    <h4 class="producto-precio">
                                                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                                    </h4>
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-tienda btn-sm">Ver Más</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                            <?php
                        else :
                            // Layout: Grid de columnas (por defecto)
                            ?>
                            <div class="row">
                                <?php
                                if ( $products_query->have_posts() ) :
                                    while ( $products_query->have_posts() ) : $products_query->the_post();
                                        global $product;
                                        ?>
                                        <div class="<?php echo esc_attr( $columnas ); ?> col-md-6 col-sm-12 mb-4">
                                            <div class="productocard h-100">
                                                <div class="imagen-prodest" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: get_template_directory_uri() . '/assets/img/default-img.png' ); ?>'); background-size:cover; background-repeat:no-repeat; min-height:200px;"></div>
                                                <div class="dataproducto p-3">
                                                    <h3 class="producto-nombre cursiva"><?php the_title(); ?></h3>
                                                    <h4 class="producto-precio">
                                                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                                    </h4>
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-tienda btn-sm">Ver Más</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    ?>
                                    <div class="col-12">
                                        <p class="text-center text-muted">No hay productos disponibles en esta sección.</p>
                                    </div>
                                    <?php
                                endif;
                                ?>
                            </div>
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
                    No hay bloques de productos configurados. Por favor, ve a Apariencia > Opciones de tema y configura los bloques de productos.
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</section>