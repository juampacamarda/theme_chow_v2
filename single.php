<?php get_header();?>
<main class="site-content">
    <?php if ( have_posts() ) : the_post(); ?>
  <section>
            <?php if ( has_post_thumbnail() ) { ?>
                   
                <div class="headdingpost" style = "background-position: center; background-image: url(<?php echo get_the_post_thumbnail_url($post_id); ?>); background-size: cover; height: 350px;">
                  <h1 class="cursiva" style="font-weight:900">
                    <?php the_title(); ?></h1>
                  <small>
                       <?php the_category (); ?>
                  </small>
                </div>
                   
                  <?php } else { ?>
                  
                  <div class="headdingpost-b" style = "background-position: center; background-color:#971623; background-size: cover; height: 350px;">
                    <h1 class="cursiva" style="font-weight:900"><?php the_title(); ?></h1>
                    <small>
                       <?php the_category (); ?>
                    </small>
                   
                  </div>

                 <?php ; } ?>
      <div class="container">

        
            
            <?php the_content(); ?>
            <div class="post-foot">
              <time datatime="<?php the_time('Y-m-j'); ?>"><i class="fa fa-calendar"></i> <?php the_time('j F, Y'); ?></time>
              <address><i class="fa fa-user"></i> <?php the_author_posts_link() ?></address>
            </div>
            <!-- Previous and Next Post -->
            <?php if(is_single()) : ?>
            <!-- Para pantallas grandes -->
                <div class="navpost d-flex justify-content-between align-items-center" id="nextpreviouslinks">
                  <?php if (get_previous_post_link()){ ?>
                  <div class="prev-link">
                     <i class="fa fa-angle-left"></i> <?php previous_post_link( '%link', '%title'); ?>
                  </div>
                  <?php } else {?>
                    <div class="empty-link">
                     
                    </div>
                  <?php }?>
                   
                  <div class="home-link">
                     <a href="<?php bloginfo('url') ?>"><i class="fa fa-th" aria-hidden="true"></i></a>
                  </div>
                  <?php if (get_next_post_link()){ ?>
                  <div class="next-link">
                    <?php next_post_link( '%link', '%title' ); ?> <i class="fa fa-angle-right"></i> 
                  </div>
                  <?php } else {?>
                    <div class="empty-link">
                     
                    </div>
                  <?php }?>
                  
                </div>
                <?php endif; ?>
            <!-- /Previous and Next Post -->
            <!-- Comentarios -->
            <?php //comments_template(); ?>
          
      </div>
  </section>
  <?php else : ?>
    <p><?php _e('Ups!, esta entrada no existe.'); ?></p>
  <?php endif; ?>

</main> <!--fin main-->
<?php get_footer();?> 