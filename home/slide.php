<?php if ( get_field( 'imagen_01', 'option') ) { ?>
<section id="home-carousel" class="chow-home-carousel ">      
	<div id="slide-home" class="carousel slide" data-bs-ride="carousel">
		<ol class="carousel-indicators d-none">
			<li data-bs-target="#slide-home" data-bs-slide-to="0" class="active"></li>
			<li data-bs-target="#slide-home" data-bs-slide-to="1"></li>
			<li data-bs-target="#slide-home" data-bs-slide-to="2"></li>
						
				</ol>
				<div class="carousel-inner">
					<?php $link_slide_1 = get_field( 'link_slide_1', 'option' ); ?>  
							<a class="carousel-item active" href="<?php echo esc_url( $link_slide_1) ; ?>">
									<div  style="background-image:url('<?php the_field( 'imagen_01', 'option' ); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
											
											<div class="content-slide">
												<h1 class="animated bounceInUp slow">
													<?php the_field( 'texto_slide_01', 'option' ); ?>
													</h1>
												</div>
												
												<div class="overlay d-none"></div>
										</div>
								</a>
					
					<?php if ( get_field( 'imagen_2', 'option') ) { ?>
							<?php $link_slide_2 = get_field( 'link_slide_2', 'option' ); ?>  
									<a class="carousel-item" href="<?php echo esc_url( $link_slide_2) ; ?>">

										<div  style="background-image:url('<?php the_field( 'imagen_2', 'option' ); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
											<div class="content-slide">
										<h1 class="animated bounceInUp slow">
											<?php the_field( 'texto_slide_2', 'option' ); ?>
													</h1>
												</div>
														<div class="overlay d-none"></div>
												</div>

										</a>
						<?php } ?>
					<?php if ( get_field( 'imagen_3', 'option') ) { ?>
							<?php $link_slide_3 = get_field( 'link_slide_3', 'option' ); ?>  
									
									<a class="carousel-item" href="<?php echo esc_url( $link_slide_3) ; ?>">

										<div  style="background-image:url('<?php the_field( 'imagen_3', 'option' ); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
											<div class="content-slide">
												<h1 class="animated bounceInUp slow">
													<?php the_field( 'texto_slide_3', 'option' ); ?>
															</h1>
														</div>
														<div class="overlay d-none"></div>
												</div>

										</a>

						<?php } ?>
					<?php if ( get_field( 'imagen_4', 'option') ) { ?>
					<?php $link_slide_4 = get_field( 'link_slide_4', 'option' ); ?>  
			 						
									<a class="carousel-item" href="<?php echo esc_url( $link_slide_4) ; ?>">
											<div  style="background-image:url('<?php the_field( 'imagen_4', 'option' ); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
													<div class="content-slide">
														<h1 class="animated bounceInUp slow">
															<?php the_field( 'texto_slide_4', 'option' ); ?>
															</h1>
														</div>
														<div class="overlay d-none"></div>
												</div>
										</a>

						<?php } ?>
					<?php if ( get_field( 'imagen_5', 'option') ) { ?>
					<?php $link_slide_5 = get_field( 'link_slide_5', 'option' ); ?>  
			 						
							<a class="carousel-item" href="<?php echo esc_url( $link_slide_5) ; ?>">
									<div  style="background-image:url('<?php the_field( 'imagen_5' , 'option'); ?>'); background-size:cover;background-repeat:no-repeat;height: 450px;">
											<div class="content-slide">
												<h1 class="animated bounceInUp slow">
													<?php the_field( 'texto_slide_5' , 'option'); ?>
													</h1>
												</div>
												<div class="overlay d-none"></div>
										</div>
								</a>
						<?php } ?>
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