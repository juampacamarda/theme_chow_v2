<?php
/*
Template Name: clientes-plantilla
*/
?>
<?php get_header();?>
<main class="site-content">
 <div class="heading d-flex align-items-center" style="background-position: center; background-image:url('<?php echo get_template_directory_uri(); ?>/assets/img/bg-quienes-somos.jpg'); background-size:cover;background-repeat:no-repeat">
    <div class="container">
        <h2 class="tittle cursiva" style="margin:0"> <?php the_title(); ?> </h2>
    </div>
</div>
<?php get_template_part( 'home/partners' ); ?>
<!--</*?php get_template_part( 'home/formas-pago' ); ?*/>-->

</main> <!--fin main-->
<?php get_footer();?> 