<section id="suscripcion-news" class="d-none d-lg-flex align-items-center"<?php 
    $newsletter = get_field('newsletter', 'option');
    if ($newsletter && isset($newsletter['news_bg']) && $newsletter['news_bg']) { 
        echo 'style="background-image:url(\'' . esc_url($newsletter['news_bg']) . '\'); background-size:cover; background-position:center"'; 
    } 
?>>
    <div class="container">
        <div class="row d-flex justify-content-end">
            <div class="col-lg-7">
                <div class="newcontenedor" data-aos="fade-up" data-aos-duration="3000">
                    <h3 class="text-center"><span class="cursiva">Descubrinos ! No te pierdas nuestras propuestas...</span><br/><br>Suscribite</h3>
                    <?php 
                    if ($newsletter && isset($newsletter['formulario_news']) && $newsletter['formulario_news']) {
                        echo do_shortcode($newsletter['formulario_news']);
                    }
                    ?>
                </div>
            </div>
                
        </div>
    </div>
</section><!--fin newsletter-->