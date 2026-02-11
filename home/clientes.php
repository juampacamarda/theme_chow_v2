<section id="clientes-botonera">
    <div class="container-fluid">
        <div class="tittle-botonera">
            <h3 class="text-center cursiva" style="font-weight:900">Nuestras Marcas</h3>
        </div>
        
        <?php 
        $contenido_home = get_field('contenido_home', 'option');
        $carrusel_logos = get_field('carrusel_logos', 'option') ?: array();
        
        if (!empty($carrusel_logos)) {
        ?>
        <ul id="slide-clientes" class="productos owl-carousel owl-theme">
            <?php foreach ($carrusel_logos as $logo) {
                $imagen = isset($logo['imagen']) ? $logo['imagen'] : '';
                $link = isset($logo['link']) ? $logo['link'] : '';
                $nombre = isset($logo['nombre_del_link']) ? $logo['nombre_del_link'] : ''
            ?>
                <li data-aos="fade-up">
                    <?php if ($imagen) { ?>
                        <img src="<?php echo esc_url($imagen);?>" alt="<?php echo esc_attr($nombre); ?>" class="d-block img-fluid">
                        
                        <?php if ($link) : 
                            $link_url = is_array($link) ? (isset($link['url']) ? $link['url'] : '') : $link;
                            $link_target = is_array($link) && isset($link['target']) ? $link['target'] : '';
                        ?>
                            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
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
