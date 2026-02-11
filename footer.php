<!--FIN CONTENIDO EMPIEZA FOOTER-->
<footer>
		
		<div class="container-fluid">
			
				<div id="datafooter" class="row">
						
								

								<div class="col-12 col-md-4">
										<div class="logo-ftr mb-4">
													<?php
															// Logo Footer - Get from ACF or use fallback
															$logo_footer = get_field('logo_footer', 'option');
															$logo_url = $logo_footer ? $logo_footer : get_template_directory_uri() . '/assets/img/logo_light.png';
													?>
													<a href="<?php echo get_home_url(); ?>" class="home-link-xs d-block" rel="home">
															<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo get_bloginfo('name'); ?>" class="img-fluid">
													</a>
										</div>
										<div class="data-cliente">
												
												<?php if ( get_field( 'direccion','option' ) ) {?>
														<p>
															<i class="fas fa-map-marker-alt me-2"></i> <?php the_field( 'direccion','option' ); ?>
														</p>
												<?php } ?> 
												<?php if ( get_field( 'telefonos','option' ) ) {?>
													<p>
														<i class="fas fa-phone me-2"></i> <?php the_field( 'telefonos','option' ); ?>
													</p>
												<?php } ?>
												<?php if ( get_field( 'mail','option' ) ) {?>
														<p>
															<i class="fas fa-envelope me-2"></i>	<?php the_field( 'mail', 'option' ); ?>
														</p>
												<?php }else{ ?>
														E-mail: xxxxx@xxxxx - xxxxx@xxxxx
												<?php } ?>
										</div>
										
								</div>

								<div class="col-xs-12 col-md-4 d-flex flex-column justify-content-center ">
										<div id="mennu-footer">
												<nav class="navbar">
														<!--menu dinamico-->
														<?php  wp_nav_menu(array(
														'theme_location' => 'footer',
														'depth'           => 1, // 1 = no dropdowns, 2 = with dropdowns.
														'container' =>'div',
														'container_class' => 'menu',
														'container_id' => 'footermenu',
														'menu_class' => 'navbar-nav flex-row mt-lg-0',
														'fallback_cb'  => 'WP_Bootstrap_Navwalker::fallback',
														'walker' =>  new WP_Bootstrap_Navwalker(),
														) ); ?>
														<!--menu dinamico-->
												</nav>
										</div>
								</div>

								<div class="col-12 col-md-4 align-items-md-center text-center text-md-left">
									<?php if ( have_rows( 'logos_legales', 'options' ) ):?>

									<div class="logos-footer">
									<?php while ( have_rows( 'logos_legales', 'options' ) ) : the_row();
										
									?>
										<img src="<?php the_sub_field( 'logo' ) ;?>" alt="">
									<?php endwhile ;?>
									</div>

									<?php endif ;?>
										
								</div>

				
		</div>
		
		</footer>
		<div class="footer-2">
				<div class="container-fluid wrapper_consultar">
						<div class="container d-flex justify-content-end">
								<div class="col-sm-4">
										<div class="consultar"> 
												<a href="#" title="Chow" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-container="body"> <small>powered by Chow</small> </a>
										</div>
								</div>
						</div>
				</div>
		</div>
		<?php wp_footer(); ?>
</div>
