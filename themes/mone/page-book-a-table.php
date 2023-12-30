<?php
/**
 * Template Name: Book A Table
 * The template for displaying Contact Us page
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package mOne
 */

get_header();
?>

<main id="bookATableWrapper">
	<div class="container">
		<div class="row">
			<div class="offset-md-2 col-md-8">
				<div class="my-5">
					<?php
						echo do_shortcode( '[contact-form-7 id="236" title="Book A Table"]' );
					?>
				</div>

			</div>
		</div>





















			

	</div>
</main>

<?php

get_footer();
