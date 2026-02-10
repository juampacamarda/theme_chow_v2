<!-- Archivo de cabecera global de Wordpress -->
<?php get_header(); ?>
 <div class="heading-cat"  style = "background-position: center; background-color:#971623; background-size: cover; height: 250px;">
    
     <h2 class="cursiva" style="font-weight:900; color:#fff"><?php single_cat_title(); ?></h2>
  </div>
  <!-- Título de categoría -->
<section id="categorypost">
 
 
<div class="container pt-4">


<!-- Listado de posts -->
<?php if ( have_posts() ) : ?>
   
    <div class="row">
      <?php while ( have_posts() ) : the_post(); ?>
      <div class="col-12 col-md-4 my-2">
          <a href="<?php the_permalink(); ?>">
              <div <?php post_class('card'); ?>>
                  <?php if ( has_post_thumbnail() ) { ?>
                  <div class="card-img-top ficha-thumbnail" style = "background-position: center; background-image: url(<?php echo get_the_post_thumbnail_url($post_id); ?>); background-size: cover; height: 200px;"></div>
                  <div class="hover card-img-top">
                      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/iso-blanco.png" alt="Logo-Outlet" class="img-fluid d-block">
                      <h3>Leer Más</h3>
                  </div>
                  <?php } ?>
                  <div class="card-body">
                    <h3><?php the_title(); ?></h3>
                    <p><?php the_excerpt(); ?></p>
                  </div>
                  <div class="card-footer">
                      <time datatime="<?php the_time('Y-m-j'); ?>"><?php the_time('j F, Y'); ?></time>
                      <?php the_category (); ?>
                  </div>
              </div>
          </a>

      </div>

    <?php endwhile; ?>
    </div>

    <div class="pagination">
      <span class="in-left"><?php next_posts_link('« Entradas antiguas'); ?></span>
      <span class="in-right"><?php previous_posts_link('Entradas más recientes »'); ?></span>
    </div>
  
<?php else : ?>
  <p><?php _e('Ups!, no hay entradas.'); ?></p>
<?php endif; ?>
</div>
</section>
<!-- Archivo de pié global de Wordpress -->
<?php get_footer(); ?>