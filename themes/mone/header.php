<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mOne
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<meta property="og:site_name" content="Pizza House" />
	<meta property="og:url" content="<?php echo get_home_url(); ?>" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:title" content="Colosseo Pizzeria" />
	<meta property="og:description" content="The best pizza and Italian food around! Order online for takeout or delivery." />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/img/homePageAfterBanner/banner_1.jpg" />
	<meta property="og:image:secure_url" content="<?php echo get_template_directory_uri(); ?>/assets/img/homePageAfterBanner/banner.jpg" />
	<meta property="og:image:width" content="300" />
	<meta property="og:image:height" content="300" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>



	<section id="mobileMenu" class="d-lg-none position-relative">
		<div class="container-fluid">
			<div class="row">
				<div class="col-3 p-0 bgForHeaderTwo">
					<div class="navLogo">
						<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon.jpg" alt="Logo Icon">
					</div>
				</div>
				<div class="col-9 p-0 bgForHeaderOne d-flex flex-wrap align-content-center justify-content-end">
					<div class="mobileMenuNav w-100">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-3',
									'menu_id'		 => 'Mobile-Menu'
								)
							);
						?>
					</div>
				</div>
			</div>			
		</div>
	</section>





	<header id="masthead" class="mastheadNav site-header w-100 d-none d-lg-block"> <!-- position-absolute -->
		<div class="container-fluid">			
			<div id="navbarPackeg" class="">
				<div class="row">
					<div class="d-flex justify-content-end col-md-5">
						<nav id="site-navigation" class="main-navigation">
							<?php
								wp_nav_menu(
									array(
										'theme_location' => 'menu-1',
										'menu_id'        => 'primary-menu',
									)
								);
							?>
						</nav>
					</div>
					<div class="col-md-2 text-center">
						<div class="navLogo">							
							<a href="<?php echo home_url(); ?>">
								<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon.jpg" alt="Icon">
							</a>
						</div>
					</div>
					<div class="col-md-5">
						<div class="rightMenu d-flex flex-row justify-content-start">
							<?php
								// wp_nav_menu(
								// 	array(
								// 		'theme_location' => 'menu-4',
								// 		'menu_id'		 => 'SecondaryMenuTwo'
								// 	)
								// );
							?>
							<div class="d-flex align-items-center">
								<ul class="list-unstyled list-group list-group-horizontal">
									<li class="px-2">+4962069510554</li>
									<li class="px-2 cartItems">
										<a href="<?php echo wc_get_cart_url(); ?>">
										    <span class="topMenuCartItemsShow">
										    	<?php echo WC()->cart->get_cart_contents_count(); ?>
										    </span>
										    <i class="fa fa-shopping-basket"></i>
										    <!-- <i class="fa fa-shopping-cart"></i> -->
										</a>
									</li>
								</ul>
							</div>
							<?php
								wp_nav_menu(
									array(
										'theme_location' => 'menu-2',
										'menu_id'		 => 'SecondaryMenu'
									)
								);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<div class="headerWrapperBgImg">
		<?php
			if (! is_front_page() || ! is_home() ) :
		?>
			<img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/bannerWithoutHomePage.jpg" alt="Icon">
		<?php			
			endif;
		?>
	</div>
