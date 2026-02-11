<?php
// Check if slider has data - look for the first slider group
$has_slider_data = false;
for($i = 1; $i <= 5; $i++) {
    if (get_field('slider_' . $i, 'option')) {
        $has_slider_data = true;
        break;
    }
}

if ($has_slider_data) { 
?>
<section id="home-carousel" class="chow-home-carousel ">      
	<div id="slide-home" class="carousel slide" data-bs-ride="carousel">
		<ol class="carousel-indicators d-none">
			<li data-bs-target="#slide-home" data-bs-slide-to="0" class="active"></li>
			<li data-bs-target="#slide-home" data-bs-slide-to="1"></li>
			<li data-bs-target="#slide-home" data-bs-slide-to="2"></li>
		</ol>
		<div class="carousel-inner">
			<?php for($i = 1; $i <= 5; $i++) {
				$slide = get_field('slider_' . $i, 'option');
				
				if ($slide) {
					$imagen = isset($slide['imagen']) ? $slide['imagen'] : '';
					$texto = isset($slide['texto']) ? $slide['texto'] : '';
					$link = isset($slide['link']) ? $slide['link'] : '#';
					$active_class = ($i === 1) ? 'active' : '';
					
					if ($imagen) {
						?>
						<a class="carousel-item <?php echo $active_class; ?>" href="<?php echo esc_url($link); ?>">
							<div style="background-image:url('<?php echo esc_url($imagen); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
								<div class="content-slide">
									<h1 class="animated bounceInUp slow">
										<?php echo esc_html($texto); ?>
									</h1>
								</div>
								<div class="overlay d-none"></div>
							</div>
						</a>
						<?php
					}
				}
			} ?>
		</div>
		<a class="carousel-control-prev" href="#slide-home" data-bs-slide="prev">
			<span aria-hidden="true">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide/control-prev.png" alt="">
			</span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#slide-home" data-bs-slide="next">
			<span aria-hidden="true">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/slide/control-next.png" alt="">
			</span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</section>
<?php } ?>

<!--fin Carousel-->