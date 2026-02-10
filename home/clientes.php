
<section id="clientes-botonera">
    <div class="container-fluid">
        <div class="tittle-botonera">
            <h3 class="text-center cursiva" style="font-weight:900">Nuestras Marcas</h3>
        </div>
        <img src="https://sobolwines.com/wp-content/uploads/Logo-Don-Cristobal.png" alt="cliente" style="width:200px;height:auto;display:block;margin:20px auto;">
        <?php if( have_rows('botonera_clientes') ): ?>
        <ul id="slide-clientes" class="productos owl-carousel owl-theme">
            <?php while( have_rows('botonera_clientes') ): the_row(); 
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
