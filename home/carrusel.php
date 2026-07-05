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
        // Detectar qué demo está activo para agregar clase específica
        $demo_activo = null;
        $demos_files = glob( get_template_directory() . '/demos/demo-*.php' );
        foreach ( $demos_files as $file ) {
            $basename = basename( $file, '.php' );
            if ( preg_match( '/^demo-([a-z0-9_-]+)$/', $basename, $matches ) ) {
                $demo_id = $matches[1];
                if ( get_option( 'chow_demo_' . $demo_id . '_active' ) ) {
                    $demo_activo = $demo_id;
                    break;
                }
            }
        }
        $clase_carrusel = $demo_activo ? 'carrusel-'.$demo_activo : 'carrusel-default';
        $clase_carrusel = apply_filters('chow_slide_prod_classes', $clase_carrusel);
        
        // Acceder directamente al campo repeater
        $carrusel_productos = get_field('carrusel_productos_destacados', 'option') ?: array();
        
        if (!empty($carrusel_productos)) {
        ?>
        <ul id="slide-prod" class="productos owl-carousel owl-theme <?php echo esc_attr($clase_carrusel); ?>">
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