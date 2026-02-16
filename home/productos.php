<section id="prod-botonera">
    <div class="container-fluid">
        <?php 
        // Título y descripción personalizables de la sección (opciones del theme)
        $titulo_seccion = get_field('titulo_carrusel_destacados', 'option');
        $descripcion_seccion = get_field('descripcion_carrusel_destacados', 'option');

        // Fallback si no hay título configurado
        if ( ! $titulo_seccion ) {
            $titulo_seccion = 'Nuestros Productos';
        }
        ?>
        <div class="tittle-botonera">
            <h3 class="text-center cursiva" style="font-weight:900"><?php echo esc_html( $titulo_seccion ); ?></h3>
            <?php if ( $descripcion_seccion ) : ?>
                <p class="text-center mb-0 descripcion-seccion">
                    <?php echo wp_kses_post( $descripcion_seccion ); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php 
        // Acceder directamente al campo repeater
        $carrusel_productos = get_field('carrusel_productos_destacados', 'option') ?: array();
        
        if (!empty($carrusel_productos)) {
        ?>
        <ul id="slide-prod" class="productos owl-carousel owl-theme">
            <?php foreach ($carrusel_productos as $producto) {
                $imagen = isset($producto['imagen']) ? $producto['imagen'] : '';
                $link = isset($producto['link']) ? $producto['link'] : array();
                $nombre = isset($producto['nombre_del_link']) ? $producto['nombre_del_link'] : '';
            ?>
                <li data-aos="fade-up">
                    <?php if ($imagen) { ?>
                        <img src="<?php echo esc_url($imagen);?>" alt="<?php echo esc_attr($nombre); ?>" class="d-block img-fluid">
                        
                        <?php if ($link && !empty($link)) : 
                            $link_url = isset($link['url']) ? $link['url'] : $link;
                            $link_target = isset($link['target']) ? $link['target'] : '';
                            $link_title = isset($link['title']) ? $link['title'] : $nombre;
                        ?>
                            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" title="<?php echo esc_attr($link_title); ?>">
                                <?php echo esc_html($nombre); ?>
                            </a>
                        <?php endif; ?>
                    <?php } ?>          
                </li>
            <?php } ?>
        </ul>
     <?php } ?>   
    </div>
</section>