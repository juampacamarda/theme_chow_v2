<?php get_header();?>
<main id="busqueda" class="site-content">
<div class="container">
<div class="row my-5">
     <div class="page-header">
        <h2 class="page-title"><?php printf( __( 'Resultados de búsqueda: %s', 'shape' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
    </div><!-- .page-header -->
    <!--<div class="col-12 col-md-3">
        <h4 class="tienda-tittle">BUSCADOR DE PRODUCTOS</h4>
            <?php //if ( is_active_sidebar( 'zonabuscador' ) ) : ?>
            <div id="zonabuscador" class="wc-search">
                <?php// dynamic_sidebar( 'zonabuscador' ); ?>
            </div>
            <?php //endif; ?>
            <!--menu dinamico
            <?php /* wp_nav_menu(array(
                'theme_location' => 'wootienda',
                'container' =>'div',
                'container_class' => 'tienda-sidebar',
                'container_id' => 'menu-hdr',
                'items_wrap' => '<ul id="botonera-tienda" class="navbar-nav p-2 mt-lg-0">%3$s</ul>',
                'items_class' => 'nav-item',
            ) )*/; ?>
            <!--menu dinamico
    </div>-->
    <div class="col-md-12">
        <div class="row d-flex">
            <?php if ( have_posts() ) : while ( have_posts() ) :   the_post(); ?>

            <div class="col-12 col-md-3 my-3">
                <a href="<?php the_permalink() ?>">
                    <div class="card">
                        <div class="img-loop-wrap card-img-top">
                            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                                <img src="<?php echo $image[0]; ?>" alt="" class="img-fluid d-block mx-auto">
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-tittle">
                            <h5>    
                                <?php the_title(); ?>
                                <br>
                                <?php global $woocommerce;
									$product = new WC_Product(get_the_ID()); 
									echo $product->get_price_html(); //Shows the <price></price>
								?>
                            </h5>
                        </div>
                        <!--
                        <div class="boton">
                            <p class="btn btn-vita">Ver Más</p>
                        </div>
                        -->       

                    </div>
                </a>
            </div>
                
            <?php endwhile; else: ?>
                
            <div class="container">
                <div class="content-page">
                    <div class="heading" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/img/404.jpg'); background-size:cover; background-repeat:no-repeat">
                    </div>
                    
                    <p class="texto-post">Disculpas! No Encontramos la Web que buscabas</p>
                </div>
            </div>

            <?php endif; ?>
        </div>

    </div>

    
</div>

</main> <!--fin main-->
<?php get_footer();?> 
