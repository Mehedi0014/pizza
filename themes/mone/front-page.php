<?php
/**
 * Template Name: Home Page / Front Page
 * The main template file
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package mOne
 */

get_header();
?>
	<main id="primaryFrontPage" class="site-main">
		<?php 
			get_template_part( 'template-parts-home-page/content', 'carousel' );			
			get_template_part( 'template-parts-home-page/content', 'thirtyMinuteDelevary' );
			get_template_part( 'template-parts-home-page/content', 'homePageAfterBannerCarousel' );		
			get_template_part( 'template-parts-home-page/content', 'specialOffer' );
			//get_template_part( 'template-parts-home-page/content', 'rewarded' );
			get_template_part( 'template-parts-home-page/content', 'bestSelling' );
			//get_template_part( 'template-parts-home-page/content', 'threeBanner' );
			//get_template_part( 'template-parts-home-page/content', 'subscribeSection' ); 
		?>
	</main>

<?php
get_footer();

