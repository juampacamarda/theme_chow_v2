<section id="suscripcion-news" class="d-none d-lg-flex align-items-center"<?php if ( get_field( 'news_bg' ) ) : ?>style="background-image:url('<?php the_field( 'news_bg' ); ?>'); background-size:cover; background-position:center"<?php endif ?>>
    <div class="container">
        <div class="row d-flex justify-content-end">
            <div class="col-lg-7">
                <div class="newcontenedor" data-aos="fade-up" data-aos-duration="3000">
                    <h3 class="text-center"><span class="cursiva">Descubrinos ! No te pierdas nuestras propuestas...</span><br/><br>Suscribite</h3>
                    <?php the_field( 'formulario_news' ); ?></div>
                </div>
                
        </div>
    </div>
</section><!--fin newsletter-->