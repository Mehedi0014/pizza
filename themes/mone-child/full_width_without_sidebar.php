<?php
/**
 * Template Name: Full Width Without Sidebar For Child
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mOne
 */

get_header();
?>

	<main id="myAccount" class="myAccount mt-5 mb-5">

		<?php
		while ( have_posts() ) :
			the_post();
		?>	


			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<?php mone_post_thumbnail(); ?>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'mone' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

				<?php if ( get_edit_post_link() ) : ?>
					<footer class="entry-footer">
						<?php
						edit_post_link(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Edit <span class="screen-reader-text">%s</span>', 'mone' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								wp_kses_post( get_the_title() )
							),
							'<span class="edit-link">',
							'</span>'
						);
						?>
					</footer><!-- .entry-footer -->
				<?php endif; ?>
			</article><!-- #post-<?php the_ID(); ?> -->



		<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
//get_sidebar();
get_footer();
