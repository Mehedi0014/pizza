<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div id="shopWrapper">
	<div class="container-fluid">


			<?php
			/**
			 * Hook: woocommerce_before_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 * @hooked WC_Structured_Data::generate_website_data() - 30
			 */
			do_action( 'woocommerce_before_main_content' );

			?>

			<div class="row">
				<div class="col-12">
					<header class="woocommerce-products-header">
						<?php if ( apply_filters( 'woocommerce_show_page_title', false ) ) : ?>
							<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
						<?php endif; ?>

						<?php
						/**
						 * Hook: woocommerce_archive_description.
						 *
						 * @hooked woocommerce_taxonomy_archive_description - 10
						 * @hooked woocommerce_product_archive_description - 10
						 */
						do_action( 'woocommerce_archive_description' );
						?>
					</header>
				</div>
			</div>


			<div class="row">
				<?php if(is_product_category() ) : ?>					
					<div class="col-12 categoryTitle text-center">
						<h2 class="text-uppercase d-table m-auto font-weight-bold ct-colorTwo">
							<?php
								single_cat_title( __( '', 'textdomain' ) );
							?>
						</h2>
					</div>
				<?php endif; ?>
			</div>

		
			<div class="row">
				<div class="col-12">	
					<?php
						//if ( woocommerce_product_loop() ) {
						//uporer line ami copy kore nia gachi. kono somossa hole ai line uncomment kore nicher theke er copy remove kore dite hobe

							/**
							 * Hook: woocommerce_before_shop_loop.
							 *
							 * @hooked woocommerce_output_all_notices - 10
							 * @hooked woocommerce_result_count - 20
							 * @hooked woocommerce_catalog_ordering - 30
							 */
						//do_action( 'woocommerce_before_shop_loop' ); 
						// uporer hook k comment kore niche alada alada vabe show kora holo.
					?>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<?php echo woocommerce_output_all_notices(); ?>
				</div>
			</div>
			

			<div class="row">
				<div class="col-12">
					<div class="showResultAndProductOrder px-3">
						<div class="row">
							<div class="showingResultNumber col-md-4 text-center text-md-left">
								<?php echo woocommerce_result_count(); ?>
							</div>
							<div class="customOrdering col-md-4 offset-md-4 d-flex justify-content-center justify-content-md-end">
								<div class="sortBy mr-2">Sort By :</div>
								<?php echo woocommerce_catalog_ordering(); ?>					
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12 mt-3">
					<?php
						if ( woocommerce_product_loop() ) { //ai line ami upor theke copy kore nia aschi. kono somossa hole ai line remove kore uporer line uncommnt kore dite hobe.
							woocommerce_product_loop_start();

							if ( wc_get_loop_prop( 'total' ) ) {
								while ( have_posts() ) {
									the_post();

									/**
									 * Hook: woocommerce_shop_loop.
									 */
									do_action( 'woocommerce_shop_loop' );

									wc_get_template_part( 'content', 'product' );
								}
							}

							woocommerce_product_loop_end();

							/**
							 * Hook: woocommerce_after_shop_loop.
							 *
							 * @hooked woocommerce_pagination - 10
							 */
							do_action( 'woocommerce_after_shop_loop' );
						} else {
							/**
							 * Hook: woocommerce_no_products_found.
							 *
							 * @hooked wc_no_products_found - 10
							 */
							do_action( 'woocommerce_no_products_found' );
						}
					?>
				</div>
			</div>
		
			
			<?php
				do_action( 'init_pagination' );
				/**
				 * Hook: woocommerce_after_main_content.
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );

				/**
				 * Hook: woocommerce_sidebar.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				// do_action( 'woocommerce_sidebar' );
			?>
	</div>
</div>


<?php
get_footer( 'shop' );


