<section id="posts-dest">
    <div class="container">
         <div class="row d-flex justify-content-center">
            <?php
            $args = array(
                'numberposts' => 1, // number of posts to return
                'post_type' => 'post', // change this to the post type you want to retrieve
                'category_name' => 'destaque'
            );

            $posts = get_posts( $args );

            if ( $posts ) : 
            foreach ( $posts as $post ) : setup_postdata( $post );  ?>
                <div class="col-12 col-md-10 my-2">
                   
                        <div <?php post_class('card'); ?>>
                            <div class="row no-gutters">
                                
                                <div class="col-md-7">
                                <?php if ( has_post_thumbnail() ) { ?>
                                    <a href="<?php the_permalink(); ?>">
                                    <div class="card-img-top ficha-thumbnail" style = "background-position: center; background-image: url(<?php echo get_the_post_thumbnail_url($post_id); ?>); background-size: cover; height: 100%;"></div>
                                    <div class="hover card-img-top">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/iso-blanco.png" alt="Logo-Outlet" class="img-fluid d-block">
                                        <h3>Leer Más</h3>
                                    </div>
                                    </a>
                                <?php } ?>
                                </div>
                                <div class="col-md-5">
                                <div class="card-body">
                                    <a href="<?php the_permalink(); ?>">
                                        <h3><?php the_title(); ?></h3>
                                        <p><?php the_excerpt(); ?></p>
                                        <div class="card-footer">
                                            <time datatime="<?php the_time('Y-m-j'); ?>"><i class="fa fa-calendar"></i><?php the_time('j F, Y'); ?></time>
                                        </div>
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                    
                </div>


                <?php endforeach; ?>
                <?php wp_reset_postdata(); ?>

            <?php endif; ?>
        </div>
    </div>
   
</section>
    


