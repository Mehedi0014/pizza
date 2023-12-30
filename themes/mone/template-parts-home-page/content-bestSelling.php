<section id="bestsellingPizzas">
	<div class="text-center headerHeading">
		<h3 class="h1 mb-5">Unsere Bestseller</h3>
	</div>

	<div class="container-fluid">
		<?php
			$args = array(
			    'post_type' => 'product',
			    'meta_key' => 'total_sales',
			    'orderby' => 'meta_value_num',
			    'posts_per_page' => 8
			);
			$loop = new WP_Query( $args );
		?>
	    <div class="row">

	    <?php
		    while ( $loop->have_posts() ) : $loop->the_post(); 
			global $product;
	    ?>			    	
	    	<div class="col-md-3 ct_mb_5p7">
	    		<div class="imgArea position-relative text-center mb-4">
	    			<a href="<?php the_permalink(); ?>">
		    			<?php
		    				if(has_post_thumbnail($loop->post->ID)){
		    					echo get_the_post_thumbnail( $loop->post->ID, '', array( 'class' => 'img-fluid') );
		    				}else{
		    					echo "No Image Found";
		    				}
		    			?>
	    			</a>
	    			<i class="fa fa-lg fa-heart-o heardShape text-center position-absolute"></i>	
	    		</div>	    		
	    		<div class="titleAndShortDesc text-center">
	    			<h5><?php the_title(); ?></h5>			    			
	    			<article><?php woocommerce_template_single_excerpt(); ?></article>
	    		</div>
	    		<div class="footerLinks text-center">
	    			<div class="footerLinksPrice">
		    			<?php echo $product->get_price_html(); ?>
		    		</div>
		    		<div class="footerLinksAddToCart">
		    			<?php echo mehedi_add_custom_add_to_cart_link(); ?>
		    			<?php //woocommerce_template_loop_add_to_cart(); ?>
		    		</div>
	    		</div>


	    	</div>
	    	<?php 
	    		endwhile;
				wp_reset_query();
			?>
	    	<!-- <div class="col-12 text-center mt-3">
	    		<a href="<?php echo esc_url( get_permalink(7) ); ?>">
	    			<button class="text-uppercase font-weight-bold btn ct_btnOne">View All</button>
	    		</a>
	    	</div> -->
	    </div>
	</div>
</section>