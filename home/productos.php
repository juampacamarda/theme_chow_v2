<section id="prod-botonera">
    <div class="container-fluid">
        <div class="tittle-botonera">
            <h3 class="text-center cursiva" style="font-weight:900">Nuestros Productos</h3>
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