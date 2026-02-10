<?php
/*
Template Name: index-plantilla
*/
?>
<?php get_header();?>
<main class="site-content">
<?php get_template_part( 'home/slide' ); ?>
<?php get_template_part( 'home/news' ); ?>
<?php get_template_part( 'home/productos' ); ?>
<!--</*?php get_template_part( 'home/formas-pago' ); ?*/>-->
<?php get_template_part( 'home/clientes' ); ?>
</main> <!--fin main-->
<?php get_footer();?> 