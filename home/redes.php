<section id="seccion-redes"class="d-flex align-items-center" <?php if ( scf_get_field( 'fondo_redes','option' ) ) { ?> style="background-image:url('<?php scf_the_field( 'fondo_redes','option' ); ?>'); background-size:cover;background-repeat:no-repeat" <?php } ?> >

    <div class="container">
        <h2>Seguinos también en nuestras Redes Sociales !</h2>
        <p class="text-center">  Ofertas y productos exclusivos.</p>
        <ul class="redes nav d-flex justify-content-center">
            <?php if ( scf_get_field( 'facebook_link', 'option') ) { ?>
                <li>
                    <?php $facebook_link = scf_get_field( 'facebook_link', 'option' ); ?>
                    <?php if ( $facebook_link ) { ?>
                        <a href="<?php echo $facebook_link; ?>" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
            <?php if ( scf_get_field( 'twitter_link', 'option') ) { ?>
                <li>
                    <?php $twitter_link = scf_get_field( 'twitter_link', 'option' ); ?>
                    <?php if ( $twitter_link ) { ?>
                        <a href="<?php echo $twitter_link; ?>" target="_blank">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
            <?php if ( scf_get_field( 'instagram_link', 'option') ) { ?>
                <li>
                    <?php $instagram_link= scf_get_field( 'instagram_link', 'option' ); ?>
                    <?php if ( $instagram_link ) { ?>
                        <a href="<?php echo $instagram_link; ?>">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
            <?php if ( scf_get_field( 'wsp_link', 'option') ) { ?>
                <li>
                    <?php $wsp_link= scf_get_field( 'wsp_link', 'option' ); ?>
                    <?php if ( $wsp_link ) { ?>
                        <a href="<?php echo $wsp_link; ?>">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>

        </ul>
    </div>
</section>