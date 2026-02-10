
<section id="destaque" class="d-none d-md-block">
    <!--<div id="iconos">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-2">
                <div class="icono-destacado">
                    
                    <?php if ( get_field( 'iconito-destaque-01' ) ) : ?>
                        <img src="<?php the_field( 'iconito-destaque-01' ); ?>" class="iconito-destaque img-fluid d-block mx-auto" />
                    <?php endif ?>
                    <h4 class="texto-destaque">
                        <?php the_field( 'texto-destaque-01' ); ?>
                    </h4>
                </div>
            </div>  
            <div class="col-12 col-md-2">
                <div class="icono-destacado">
                    
                    <?php if ( get_field( 'iconito-destaque-02' ) ) : ?>
                        <img src="<?php the_field( 'iconito-destaque-02' ); ?>" class="iconito-destaque img-fluid d-block mx-auto" />
                    <?php endif ?>
                    <h4 class="texto-destaque">
                        <?php the_field( 'texto-destaque-02' ); ?>
                    </h4>
                </div>
            </div>      
            <div class="col-12 col-md-2">
                <div class="icono-destacado">
                    
                    <?php if ( get_field( 'iconito-destaque-03' ) ) : ?>
                        <img src="<?php the_field( 'iconito-destaque-03' ); ?>" class="iconito-destaque img-fluid d-block mx-auto" />
                    <?php endif ?>
                    <h4 class="texto-destaque">
                        <?php the_field( 'texto-destaque-03' ); ?>
                    </h4>
                </div>
            </div>  
            <div class="col-12 col-md-2">
                <div class="icono-destacado">
                   
                    <?php if ( get_field( 'iconito-destaque-04' ) ) : ?>
                        <img src="<?php the_field( 'iconito-destaque-04' ); ?>" class="iconito-destaque img-fluid d-block mx-auto" />
                    <?php endif ?>
                    <h4 class="texto-destaque">
                        <?php the_field( 'texto-destaque-04' ); ?>
                    </h4>
                </div>
            </div>    
        </div>
    </div>-->
    <div id="iconos">
        <div class="msjdestacado">
        
            <div class="col-md-4 destimng" 
                style=" <?php if ( get_field( 'imagen_destacada' ) ) {?> 
                background-image:url('<?php the_field( 'imagen_destacada' ); ?>'); 
                <?php }else{ ?>
                background-image:url('<?php echo get_template_directory_uri(); ?>/assets/img/default-img.png');
                <?php } ?> background-size:cover;background-repeat:no-repeat;height: 220px;">
            </div>
        

            
            <div class="col-md-8">
                <h3 class="desttxt">
                    <?php if ( get_field( 'texto-destaque') ) { ?>
                        <?php the_field( 'texto-destaque' ); ?>
                    <?php }else{ ?>
                        Lorem, ipsum dolor sit amet consectetur.
                    <?php }?>
                </h3>
            </div>
        </div>

    </div>
</section>
