<?php
/*
Template Name: page-plantilla
*/
?>
<?php get_header();?>
<main class="site-content">
   
        <?php if ( get_field( 'imagen_portada') ) { ?>
            <div class="heading d-flex align-items-center" style="background-image:url('<?php the_field( 'imagen_portada' ); ?>'); background-size:cover; background-repeat:no-repeat">
                <div class="container">
                    <h4 class="pre-txt"><?php the_field( 'pre_txt' ); ?></h4>
                    <h2 class="tittle cursiva"> <?php the_title(); ?> </h2>
                    <p class="header_bajada"><?php the_field( 'header_bajada' ); ?></p>
                </div>
            </div>
        <?php } ?>        
    
        <div class="content-page d-flex flex-column">

            <?php if(get_field('texto_contenido') ){ ?>
                <div class="texto-post textppal" style="order:<?php the_field( 'orden_de_bloque_texto' ); ?>;"> 
                    <div class="container">
                        <?php the_field( 'texto_contenido' ); ?>
                    </div>
                </div>
            <?php } ?>

            <?php if( have_rows('collapses') ): ?>
            
                <div class="colapsable texto-post" style="order:<?php the_field( 'orden_de_bloque_collapse' ); ?>;">
                    <div class="container">
                        <?php while( have_rows('collapses') ): the_row(); ?>
                            <h3 class="d-flex justify-content-between">
                                <a class="collapse-link" data-bs-toggle="collapse" href="#pregunta-<?php the_sub_field('numero'); ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <?php the_sub_field('pregunta'); ?>
                                </a>
                                <i class="fa fa-angle-down"></i>
                            </h3>
                            <div class="collapse" id="pregunta-<?php the_sub_field('numero'); ?>">
                            <div class="card card-body">
                                <?php the_sub_field('respuesta'); ?>
                            </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
          
            <?php endif; ?>

            <?php if( have_rows('clientes_logos') ): ?>
                <div class="lista_logos" style="order:<?php the_field( 'orden_de_bloque_logo' ); ?>;"> 
                    <div class="container">
                        <ul class="Logos productos nav nav-bar">

                                <?php while( have_rows('clientes_logos') ): the_row(); 
                                // vars
                                $imagen = get_sub_field('imagenes');
                                ?>
                                    <li class="nav-item">
                                        <?php if( the_sub_field('imagenes') ){ ;?>
                                            <img src="<?php the_sub_field('imagenes') ?>" alt="" class="d-block img-fluid">
                                        <?php } ;?>
                                        <?php if( the_sub_field('info') ){ ;?>
                                            <p><?php the_sub_field('info') ?></p>
                                        <?php } ;?>
                                    </li> 
                                    
                                <?php endwhile; ?>
                            
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
			<?php if(get_field('codigo_form') ){ ?>
                <div  class="form texto-post" 
										style=" <?php if(get_field('orden_form') ){ ?>
										order:<?php the_field( 'orden_form' ); ?><?php } ?> ;
										 <?php if ( get_field( 'fondo_form' ) ) { ?> 
										 background-image:url(<?php the_field( 'fondo_form' ); ?>);<?php }?>"> 
                    <div class="container">
												<?php the_field( 'codigo_form' ); ?>
                    </div>
                </div>
            <?php } ?>

			<?php if(get_field('banner-titulo') ){ ?>
                <div  class="banner texto-post" 
										style=" <?php if(get_field('orden_banner') ){ ?>
										order:<?php the_field( 'orden_banner' ); ?><?php } ?>;
										<?php if ( get_field( 'imagen_banner' ) ) { ?> 
										background-image:url(<?php the_field( 'imagen_banner' ); ?>);<?php }?>" >
                    <div class="container">
										<h3><?php the_field( 'banner-titulo' ); ?></h3>
										<h5 class="pretxt"><?php the_field( 'banner-pretxt' ); ?></h5>
										<?php $link = get_field( 'link' ); ?>
										<?php if ( $link ) : ?>
										<a href="<?php the_field( 'link' ); ?>" target="blank" class="btn btn-landing">¡Contactanos! </a>
                                        <?php endif; ?> 

                    </div>
                </div>
            <?php } ?>


        </div>
</main> <!--fin main-->
<?php get_footer();?> 