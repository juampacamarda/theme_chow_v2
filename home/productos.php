<section id="prod-botonera">
    <div class="container-fluid">
        <div class="tittle-botonera">
            <h3 class="text-center cursiva" style="font-weight:900">Nuestros Productos</h3>
        </div>
        <?php if( have_rows('botonera_productos') ): ?>
        <ul id="slide-prod" class="productos owl-carousel owl-theme">
            <?php while( have_rows('botonera_productos') ): the_row(); 
                $imagen = get_sub_field('imagen');
                $link = get_sub_field('link');
                $nombre = get_sub_field('nombre_del_link')
            ?>
                <li data-aos="fade-up">
                    <?php if ( $imagen ) { ?>
                        <img src="<?php echo $imagen;?>" alt="" class="d-block img-fluid">
                        
                        
                        
                        <?php if ( $link ) : ?>
                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>">
                                <?php echo $nombre; ?>
                            </a>
                        <?php endif; ?>
                    <?php } ?>          
                </li>
            <?php endwhile; ?>
        </ul>
     <?php endif; ?>   
    </div>
</section>