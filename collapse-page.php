<?php
/*
Template Name: page-collapse
*/
?>
<?php get_header();?>
<main class="site-content">
   
    <?php if ( scf_get_field( 'imagen_portada') ) { ?>
        <div class="heading d-flex align-items-center" style="background-position: center;background-image:url('<?php scf_the_field( 'imagen_portada' ); ?>'); background-size:cover; background-repeat:no-repeat">
            <div class="container">
                <h2 class="tittle cursiva" data-aos="fade-up"> <?php scf_the_field( 'titulo' ); ?> </h2>
            </div>
        </div>
    <?php } ?>
        <div class="container">
          <?php if( scf_have_rows('collapses') ): ?>
            <div class="content-page">
              <div class="texto-post" data-aos="fade-up">
            <?php while( scf_have_rows('collapses') ): scf_the_row(); ?>
              <div class="pregunta mb-3">
                <h3>
                  <a class="collapse-link d-flex justify-content-between" data-bs-toggle="collapse" href="#pregunta-<?php scf_the_sub_field('numero'); ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <?php scf_the_sub_field('pregunta'); ?> <i class="fa fa-angle-down"></i>
                  </a>
                </h3>
                <div class="collapse" id="pregunta-<?php scf_the_sub_field('numero'); ?>">
                  <div class="card card-body">
                    <?php scf_the_sub_field('respuesta'); ?>
                    <?php $button = scf_get_field( 'button' ); ?>
                    <?php if ( $button ) : ?>
                          <a class="btn btn-contacto mt-auto ml-auto" href="<?php echo esc_url( $button) ; ?>">¡Comunícate con nosotros!</a>
                      <?php endif; ?>
                  </div>
                </div>
              </div>
                
            <?php endwhile; ?>
              </div>
            </div>
          <?php endif; ?>
           
        </div>

</main> <!--fin main-->
<?php get_footer();?> 