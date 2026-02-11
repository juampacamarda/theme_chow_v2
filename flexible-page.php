<?php
/**
 * Template Name: Página Flexible
 * Description: Template flexible para todas las páginas de contenido
 */
get_header();
?>

<main class="site-content">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- SECCIÓN DE ENCABEZADO -->
    <?php if (get_field('activo_encabezado') && get_field('imagen_portada')) : ?>
    <section class="hero-section" style="background-image: url('<?php echo esc_url(get_field('imagen_portada')); ?>'); background-size: cover; background-position: center; padding: 80px 20px; position: relative;">
        <div class="hero-overlay" style="background: rgba(0,0,0,0.4); position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
        <div class="container" style="position: relative; z-index: 2; color: white; text-align: center;">
            <?php if (get_field('pre_txt')) : ?>
            <p class="hero-pretxt" style="font-size: 18px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px;">
                <?php echo esc_html(get_field('pre_txt')); ?>
            </p>
            <?php endif; ?>
            
            <?php if (get_field('titulo')) : ?>
            <h1 class="hero-title" style="font-size: 48px; font-weight: 700; margin-bottom: 15px;">
                <?php echo esc_html(get_field('titulo')); ?>
            </h1>
            <?php endif; ?>
            
            <?php if (get_field('header_bajada')) : ?>
            <p class="hero-description" style="font-size: 20px; max-width: 600px; margin: 0 auto;">
                <?php echo esc_html(get_field('header_bajada')); ?>
            </p>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- SECCIÓN DE CONTENIDO PRINCIPAL -->
    <div class="page-content-wrapper" style="display: flex; flex-direction: column; gap: 40px; padding: 60px 20px;">
        <div class="container">
            
            <!-- CONTENIDO DE TEXTO -->
            <?php if (get_field('texto_contenido')) : ?>
            <section class="content-text" style="order: <?php echo esc_attr(get_field('orden_contenido') ?: '1'); ?>;">
                <div class="texto-post">
                    <?php echo wp_kses_post(get_field('texto_contenido')); ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SECCIÓN DE COLLAPSES/FAQ -->
            <?php 
            $collapses = get_field('collapses');
            if (get_field('activo_collapses') && $collapses && is_array($collapses)) :
            ?>
            <section class="collapses-section" style="order: <?php echo esc_attr(get_field('orden_collapses') ?: '2'); ?>;">
                <h2 style="margin-bottom: 30px; font-size: 32px; font-weight: 700;">Preguntas Frecuentes</h2>
                <div class="accordion" id="faqAccordion">
                    <?php 
                    $collapse_index = 0;
                    foreach ($collapses as $collapse) :
                        $collapse_id = 'collapse-' . $collapse_index;
                    ?>
                    <div class="accordion-item" style="border: 1px solid #e0e0e0; margin-bottom: 15px; border-radius: 5px;">
                        <h2 class="accordion-header" id="heading<?php echo $collapse_index; ?>">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="false" aria-controls="<?php echo $collapse_id; ?>" style="background: #f9f9f9; font-weight: 600; color: #333;">
                                <?php echo esc_html($collapse['titulo_collapse']); ?>
                            </button>
                        </h2>
                        <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $collapse_index; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="padding: 20px; background: #fff;">
                                <?php echo wp_kses_post($collapse['contenido_collapse']); ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $collapse_index++;
                    endforeach; 
                    ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SECCIÓN DE LOGOS/CLIENTES -->
            <?php 
            $clientes = get_field('clientes_logos');
            if (get_field('activo_clientes') && $clientes && is_array($clientes)) :
            ?>
            <section class="clientes-section" style="order: <?php echo esc_attr(get_field('orden_clientes') ?: '3'); ?>; padding: 40px 0;">
                <h2 style="text-align: center; margin-bottom: 40px; font-size: 32px; font-weight: 700;">Nuestros Clientes</h2>
                <div class="row align-items-center" style="gap: 20px;">
                    <?php foreach ($clientes as $cliente) : ?>
                    <div class="col-md-3 col-sm-6 text-center">
                        <?php if ($cliente['logo_cliente']) : ?>
                        <img src="<?php echo esc_url($cliente['logo_cliente']); ?>" alt="<?php echo esc_attr($cliente['nombre_cliente'] ?: 'Cliente'); ?>" style="max-height: 80px; width: auto; object-fit: contain;">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SECCIÓN DE FORMULARIO -->
            <?php if (get_field('activo_form') && get_field('codigo_form')) : ?>
            <section class="form-section" style="order: <?php echo esc_attr(get_field('orden_form') ?: '4'); ?>; background: #f9f9f9; padding: 40px; border-radius: 10px;">
                <div class="form texto-post">
                    <?php echo do_shortcode(get_field('codigo_form')); ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- SECCIÓN DE BANNER -->
            <?php if (get_field('activo_banner') && get_field('imagen_banner')) : ?>
            <section class="banner-section" style="order: <?php echo esc_attr(get_field('orden_banner') ?: '5'); ?>; margin-top: 40px;">
                <div class="banner-wrapper" style="position: relative; border-radius: 10px; overflow: hidden;">
                    <?php 
                    $banner_link = get_field('link_banner');
                    $banner_link_url = is_array($banner_link) ? $banner_link['url'] : $banner_link;
                    $banner_link_target = is_array($banner_link) ? ($banner_link['target'] ?: '_self') : '_self';
                    ?>
                    <a href="<?php echo esc_url($banner_link_url ?: '#'); ?>" target="<?php echo esc_attr($banner_link_target); ?>" style="text-decoration: none; display: block;">
                        <img src="<?php echo esc_url(get_field('imagen_banner')); ?>" alt="Banner" style="width: 100%; height: auto; display: block;">
                    </a>
                </div>
            </section>
            <?php endif; ?>

        </div>
    </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?> 