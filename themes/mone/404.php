<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package mOne
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<section id="wrongPage">
			<div class="wrongPage">
				<div class="wrongPage-404">
					<h1 style="background-image:url(<?php bloginfo('template_directory') ?>/assets/img/404_bg.jpg)"><?php esc_html_e( 'Oops!', 'demo_one' ); ?></h1>
				</div>
				<h2><?php esc_html_e( '404 - Page not found.', 'demo_one' ); ?></h2>
				<p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
				<a href="<?php echo home_url(); ?>">Go To Homepage</a>
			</div>
		</section>
	</main>
</div>

<?php
get_footer();
