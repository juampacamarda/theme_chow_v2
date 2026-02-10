<?php
/*
Template Name: blog-plantilla
*/
?>
<?php get_header();?>
<main class="site-content">
<?php if ( get_field( 'imagen_portada') ) { ?>
    <div class="heading d-flex align-items-center" style="background-position: center;background-image:url('<?php the_field( 'imagen_portada' ); ?>'); background-size:cover; background-repeat:no-repeat">
        <div class="container">
            <h2 class="tittle cursiva" data-aos="fade-up" > <?php the_title(); ?> </h2>
        </div>
    </div>
<?php } ?>
<section id="posts-home">
    <div class="container">
         <div class="row">
            <?php
            $args = array(
                'numberposts' => -1, // number of posts to return
                'post_type' => 'post' // change this to the post type you want to retrieve
            );

            $posts = get_posts( $args );

            if ( $posts ) : 
            foreach ( $posts as $post ) : setup_postdata( $post );  ?>
                <div class="col-12 col-md-4 my-2">
                   
                        <div <?php post_class('card'); ?>>
                         <a href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) { ?>
                                <div class="card-img-top ficha-thumbnail" style = "background-position: center; background-image: url(<?php echo get_the_post_thumbnail_url($post_id); ?>); background-size: cover; height: 200px;"></div>
                                <div class="hover card-img-top">
                                    <h3>Leer Más</h3>
                                </div>
                            <?php } ?>
                            <div class="card-body">
                                <h3><?php the_title(); ?></h3>
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="card-footer">
                                <time datatime="<?php the_time('Y-m-j'); ?>"><i class="fa fa-calendar"></i><?php the_time('j F, Y'); ?></time>
                                <?php the_category (); ?>
                            </div>
                            </a>
                        </div>
                    

                </div>


                <?php endforeach; ?>
                <?php wp_reset_postdata(); ?>

            <?php endif; ?>
        </div>
    </div>
   
</section>
</main> <!--fin main-->
<?php get_footer();?> 