<?php
/*
Template Name: page-contacto
*/
?>
<?php get_header();?>
<main class="site-content">
   
        <?php if ( get_field( 'imagen_portada') ) { ?>
            <div class="heading d-flex align-items-center" style="background-image:url('<?php the_field( 'imagen_portada' ); ?>'); background-size:cover; background-repeat:no-repeat; background-position:center">
                <div class="container">
                    <h2 class="tittle cursiva"> <?php the_field( 'titulo' ); ?> </h2>
                </div>
            </div>
        <?php } ?>        
    
    <div class="container">
        <div class="content-page">
            <div class="row d-flex justify-content-center">
                <?php if ( get_field( 'imagen' ) ) : ?>
                <div class="col-12 col-md-6" data-aos="fade-up">
                    <div class="my-4">
                        <img src="<?php the_field( 'imagen' ); ?>" class="img-fluid d-block"/>
                    </div>
                    
                </div>
                <?php endif ?> 
                <div class="col-12 col-md-6" data-aos="fade-up">
                    <div class="texto-post my-4">
                        <?php the_field( 'texto_contenido' ); ?>
                        <?php if ( get_field( 'texto_contacto' ) ) : ?>
                            <div class="my-4">
                                <?php the_field( 'texto_contacto' ); ?>
                            </div>
                        <?php endif ?> 
                    </div>
                </div>
                
                    <?php if (get_field( 'mapa' )){ ?>
                        <div class="col-12">
                            <?php the_field( 'mapa' ); ?>
                        </div>
                        <?php }?>
                
            </div>
            


        </div>
    </div>
</main> <!--fin main-->
<?php get_footer();?> 