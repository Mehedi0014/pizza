<?php
/**
 * Template Name: Full Width Without Sidebar For Parent
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mOne
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="container-fluid my-5">

					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'full_width_without_sidebar_parent_theme' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>

		</div>
	</main><!-- #main -->

<?php
get_footer();
