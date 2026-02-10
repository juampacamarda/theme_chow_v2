<section id="productos-destacados">
    <div class="container">
        <div class="row">
            <div id="tituloseccion" class="col-12 col-md-4">
                <h2 class="titulo cursiva">
                    <?php if ( get_field( 'titulo') ) { ?>
                        <?php the_field( 'titulo' ); ?>
                        <?php }else{ ?>
                        Lorem, ipsum dolor sit amet consectetur.
                    <?php }?>
                </h2>
                <p>
                    <?php if ( get_field( 'mensaje') ) { ?>
                        <?php the_field( 'mensaje' ); ?>
                        <?php }else{ ?>
                        Lorem, ipsum dolor sit amet consectetur.
                    <?php }?>
                </p>
                <a href="<?php echo wc_get_page_permalink( 'shop' ); ?>" class="btn btn-tienda">Ver Nuestros Productos</a>
            </div>
            <!--<div class="col-12 col-md-8 mb-2">
                <div id="slide-proddest" class="productocard carousel slide carousel-fade" data-ride="carousel">
        
                        <div class="carousel-inner">
                            <div class="carousel-item active" style="<?php //if ( get_field( 'imagen_de_producto_1' ) ) { ?> 
                                background-image:url('<?php //the_field( 'imagen_de_producto_1' ); ?>'); 
                                <?php //}else{ ?>
                                background-image:url('<?php //echo get_template_directory_uri(); ?>/assets/img/default-img.png');
                                <?php //} ?> background-size:cover;background-repeat:no-repeat;height: 400px;" data-interval="5000">
                                
                                <div class="dataproducto">
                                        <h3 class="producto-nombre cursiva">
                                            <?php //if ( get_field( 'nombre_de_producto_1') ) { ?>
                                                <?php //the_field( 'nombre_de_producto_1' ); ?>
                                                <?php //}else{ ?>
                                                Lorem, ipsum dolor sit amet consectetur.
                                            <?php// }?>
                                        </h3>
                                        <h4 class="producto-precio">
                                            $ <?php// if ( get_field( 'precio_producto_1') ) { ?>
                                                <?php// the_field( 'precio_producto_1' ); ?>
                                                <?php// }else{ ?>
                                                XXX.XXX
                                            <?php// }?>
                                        </h4>
                                        <?php //$link_producto_1 = get_field( 'link_producto_1' ); ?>
                                        <?php //if ( $link_producto_1 ) { ?>
                                            <a href="<?php// echo esc_url( $link_producto_1) ; ?>" class="btn btn-tienda">Ver Más</a>
                                        <?php// }else{ ?>
                                            <a href="" class="btn btn-tienda">Ver Más</a>
                                        <?php// } ?>
                                    </div>
                                <div class="overlay"></div>
                            </div>
                        
                            <div class="carousel-item" style="<?php //if ( get_field( 'imagen_de_producto_2' ) ) { ?> 
                                background-image:url('<?php //the_field( 'imagen_de_producto_2' ); ?>'); 
                                <?php //}else{ ?>
                                background-image:url('<?php //echo get_template_directory_uri(); ?>/assets/img/default-img.png');
                                <?php //} ?> background-size:cover;background-repeat:no-repeat;height: 400px;" data-interval="5000">
                                
                                <div class="dataproducto">
                                        <h3 class="producto-nombre cursiva">
                                            <?php //if ( get_field( 'nombre_de_producto_2') ) { ?>
                                                <?php //the_field( 'nombre_de_producto_2' ); ?>
                                                <?php //}else{ ?>
                                                Lorem, ipsum dolor sit amet consectetur.
                                            <?php //}?>
                                        </h3>
                                        <h4 class="producto-precio">
                                            $ <?php //if ( get_field( 'precio_producto_2') ) { ?>
                                                <?php //the_field( 'precio_producto_2' ); ?>
                                                <?php// }else{ ?>
                                                XXX.XXX
                                            <?php// }?>
                                        </h4>
                                        <?php //$link_producto_2 = get_field( 'link_producto_2' ); ?>
                                        <?php //if ( $link_producto_2 ) { ?>
                                            <a href="<?php// echo esc_url( $link_producto_2) ; ?>" class="btn btn-tienda">Ver Más</a>
                                        <?php //}else{ ?>
                                            <a href="" class="btn btn-tienda">Ver Más</a>
                                        <?php //} ?>
                                    </div>
                                <div class="overlay"></div>
                            </div>
                        </div>
                </div>
            </div>-->
            <div class="col-12 col-md-4 mb-2">
                <div class="productocard">
                    <?php if ( get_field( 'imagen_de_producto_1' ) ) { ?>
                        <div class="imagen-prodest" style="background-image:url('<?php the_field( 'imagen_de_producto_1' ); ?>'); background-size:cover;background-repeat:no-repeat;height:150px"></div>
                        <?php }else{ ?>
                         <div class="imagen-prodest" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/img/default-img.png'); background-size:cover;background-repeat:no-repeat;height:150px"></div>
                    <?php } ?>
                    <div class="dataproducto">
                        <h3 class="producto-nombre cursiva">
                            <?php if ( get_field( 'nombre_de_producto_1') ) { ?>
                                <?php the_field( 'nombre_de_producto_1' ); ?>
                                <?php }else{ ?>
                                Lorem, ipsum dolor sit amet consectetur.
                            <?php }?>
                        </h3>
                        <h4 class="producto-precio">
                            $ <?php if ( get_field( 'precio_producto_1') ) { ?>
                                <?php the_field( 'precio_producto_1' ); ?>
                                <?php }else{ ?>
                                XXX.XXX
                            <?php }?>
                        </h4>
                        <?php $link_producto_1 = get_field( 'link_producto_1' ); ?>
                        <?php if ( $link_producto_1 ) { ?>
                            <a href="<?php echo esc_url( $link_producto_1) ; ?>" class="btn btn-tienda">Ver Más</a>
                        <?php }else{ ?>
                            <a href="" class="btn btn-tienda">Ver Más</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="productocard">
                    <?php if ( get_field( 'imagen_de_producto_2' ) ) { ?>
                        <div class="imagen-prodest" style="background-image:url('<?php the_field( 'imagen_de_producto_2' ); ?>'); background-size:cover;background-repeat:no-repeat;height:150px"></div>
                        <?php }else{ ?>
                         <div class="imagen-prodest" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/img/default-img.png'); background-size:cover;background-repeat:no-repeat;height:150px"></div>
                    <?php } ?>
                    <h3 class="producto-nombre cursiva">
                         <?php if ( get_field( 'nombre_de_producto_2') ) { ?>
                            <?php the_field( 'nombre_de_producto_2' ); ?>
                            <?php }else{ ?>
                            Lorem, ipsum dolor sit amet consectetur.
                        <?php }?>
                    </h3>
                    <h4 class="producto-precio">
                        $ <?php if ( get_field( 'precio_producto_2') ) { ?>
                            <?php the_field( 'precio_producto_2' ); ?>
                            <?php }else{ ?>
                            XXX.XXX
                        <?php }?>
                    </h4>
                    <?php $link_producto_1 = get_field( 'link_producto_1' ); ?>
                    <?php if ( $link_producto_1 ) { ?>
                        <a href="<?php echo esc_url( $link_producto_1) ; ?>" class="btn btn-tienda">Ver Más</a>
                    <?php }else{ ?>
                        <a href="" class="btn btn-tienda">Ver Más</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        
    </div>
</section>