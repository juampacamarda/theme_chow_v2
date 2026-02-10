<!doctype html>
<html lang="en">

<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<?php wp_head(); ?>
		<title><?php echo get_bloginfo( 'name' ); ?></title>
		<meta name="Title" content="<?php echo get_bloginfo( 'name' ); ?>">
		<?php if ( get_field( 'google_analytics', 'option') ) { ?>
			<?php the_field( 'google_analytics','option' ); ?>
		<?php } ?>
		<?php if ( get_field( 'fb_pix', 'option') ) { ?>
			<?php the_field( 'fb_pix','option' ); ?>
		<?php } ?>
		<?php if ( get_field( 'otro_code', 'option') ) { ?>
			<?php the_field( 'otro_code','option' ); ?>
		<?php } ?>
</head>

<body <?php body_class() ?>>
<div class="site-container">
		<header>
				<div class="container-fluid">
						<div class="topbar d-none d-md-block">
								<div class="row d-flex align-items-center  justify-content-between"> 

										<div class="col-12 col-sm-5 col-md-5 d-flex align-items-center">
												<div class="navbar-brand">
														<?php
																// Display the Custom Logo
																the_custom_logo();

																// No Custom Logo, just display the site's name
																if (!has_custom_logo()) {
																		?>
																<a href="<?php echo get_home_url(); ?>" class="home-link-xs d-block" rel="home">
																		<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo_color.png" alt="Logo" class="img-fluid">
																</a>
														<?php
																}?>
												</div>
												
										</div>

										<div class="col-5 col-sm-5 col-lg-6 justify-content-end datos_right">
												<ul class="nav hidden-xs hidden-sm" id="tabla-navdatos">
														
														<li class="redes border-r padding-datos">
																<!--<<h3 class="title-datos">Nuestras redes</h3>-->
																<ul class="nav data-datos header-redes">
																		<?php if ( get_field( 'instagram_link', 'option') ) { ?>
																		<li>
																				<a href="<?php the_field( 'instagram_link', 'option' ); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
																		</li>
																		<?php } ?>
																		<?php if ( get_field( 'facebook_link', 'option') ) { ?>
																		<li>
																				<a href="<?php the_field( 'facebook_link', 'option' ); ?>" target="_blank"><i class="fab fa-facebook"></i></a>
																		</li>
																		<?php } ?>
																		<?php if ( get_field( 'tel_numero', 'option') ) { ?>
																		<li>
																				<a href="tel:<?php the_field( 'tel_numero', 'option' ); ?>"><i class="fa fa-phone"></i></a>
																		</li>
																		<?php } ?>
																		<?php if ( get_field( 'wsp_link', 'option') ) { ?>
																		<li>
																				<a href="https://wa.me/<?php the_field( 'wsp_link', 'option' ); ?>"><i class="fab fa-whatsapp"></i></a>
																		</li>
																		<?php } ?>
																		<?php if ( get_field( 'mail', 'option') ) { ?>
																		<li>
																				<a href="mailto:<?php the_field( 'mail', 'option' ); ?>"><i class="fa fa-envelope"></i></a>
																		</li>
																		<?php } ?>
																</ul>
														</li>
														<li class="carrito-compras">
																<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
																		$count = WC()->cart->cart_contents_count;
																		?>
																		<a class="chow-cart-contents btn chow-btn" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
																			<i class="fa fa-shopping-basket" aria-hidden="true"></i> Mi Compra
																				<?php if ( $count > 0 ) { ?>
																				<span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
																				<?php } ?>
																		</a>
																
																<?php } ?>
														</li>
												</ul>   
										</div>
										
								</div>
						</div>
				</div>

				<nav id="menu-theme" class="navbar navbar-expand-lg navbar-dark">
								<div class="container-fluid">
										<div class="navbar-brand d-block d-lg-none">
											<?php
													// Display the Custom Logo
													the_custom_logo();

													// No Custom Logo, just display the site's name
													if (!has_custom_logo()) {
															?>
													<a href="<?php echo get_home_url(); ?>" class="home-link-xs d-block" rel="home">
															<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo_light.png" alt="Logo" class="img-fluid">
													</a>
											<?php }?>
										</div>
								
										<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
										aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
												<span class="navbar-toggler-icon"></span>
										</button>
										<div class="collapse navbar-collapse" id="navbarTogglerDemo02">

														<!--menu dinamico-->
														<?php wp_nav_menu(array(
														'theme_location' => 'superior',
														'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
														'container' =>'div',
														'container_class' => 'collapse navbar-collapse',
														'container_id' => 'navbarTogglerDemo02',
														'menu_class' => 'navbar-nav mt-2 mt-lg-0',
														'fallback_cb'  => 'WP_Bootstrap_Navwalker::fallback',
														'walker' =>  new WP_Bootstrap_Navwalker(),
														) ); ?>
														<!--menu dinamico-->
														<?php if ( is_active_sidebar( 'zonabuscador' ) ) : ?>
														<div id="zonabuscador" class="d-block d-md-none wc-search">
																<?php dynamic_sidebar( 'zonabuscador' ); ?>
														</div>
														<?php endif; ?>

												
												
										</div>
										<?php if ( is_active_sidebar( 'zonabuscador' ) ) : ?>
										<div id="zonabuscador" class="d-none d-md-block wc-search">
												<?php dynamic_sidebar( 'zonabuscador' ); ?>
										</div>
										<?php endif; ?>
								</div>
				</nav>
				
		</header>
<!--FIN HEADER EMPIEZA CONTENIDO-->