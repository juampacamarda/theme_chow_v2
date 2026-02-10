<?php if (get_field('formulario_producto', 'option')) : ;?>
<section id="contacto-productos" class="d-flex flex-column justify-content-center">
    
    <h2>Consultar por este producto</h2>
    <div class="formulario-productos-consulta">
        <?php the_field('formulario_producto', 'option') ; ?>
    </div>

</section>
<?php endif; ?>