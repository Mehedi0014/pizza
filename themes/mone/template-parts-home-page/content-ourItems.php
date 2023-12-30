<?php
      $pizza =          get_category_link( (int)(int)16 ); 
      $pasta =          get_category_link( (int)21 ); 
      $salat =          get_category_link( (int)41 ); 
      $desserts =       get_category_link( (int)43 ); 
      $burger =         get_category_link( (int)18 );
      $ben =            get_category_link( (int)42 ); 
      $fingerfood =     get_category_link( (int)17 ); 
      $putenfleisch =   get_category_link( (int)19 ); 
      $sandwiches =     get_category_link( (int)20 ); 
?>


<section id="ourItems" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/homePageRewarded/rewarded.jpg);">
	<div class="container">
		<div class="heading text-center mb-5">
			<h1>Our Items</h1>
		</div>
		<div class="owl-carousel ourItems">
                  <div class="product-box">
                  	<a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $pizza ); ?>">Pizza</a>
                        <a href="<?php echo esc_url( $pizza ); ?>">
                  	     <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/PIZZA.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                  	<a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $pasta ); ?>">Pasta</a>
                        <a href="<?php echo esc_url( $pasta ); ?>">
                  	     <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/PASTA.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                  	<a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $salat ); ?>">Salat</a>
                        <a href="<?php echo esc_url( $salat ); ?>">
                  	     <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/SALAT.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                  	<a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $desserts ); ?>">Desserty</a>
                        <a href="<?php echo esc_url( $desserts ); ?>">
                  	     <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/DESSERTS.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                  	<a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $burger ); ?>">Burger</a>
                        <a href="<?php echo esc_url( $burger ); ?>">
                  	     <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/BURGER.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                        <a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $ben ); ?>">Ben-&-Jerry's</a>
                        <a href="<?php echo esc_url( $ben ); ?>">
                              <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/BEN-JERRYS-SHORTIES.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                        <a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $fingerfood ); ?>">Fingerfood</a>
                        <a href="<?php echo esc_url( $fingerfood ); ?>">
                              <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/FINGERFOOD.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                        <a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $putenfleisch ); ?>">Putenfleisch</a>
                        <a href="<?php echo esc_url( $putenfleisch ); ?>">
                              <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/PUTENFLEISCH.jpg" alt="Our Special Category">
                        </a>
                  </div>
                  <div class="product-box">
                        <a class="portuct-title text-center m-auto font-weight-bold d-block text-uppercase" href="<?php echo esc_url( $sandwiches ); ?>">Sandwiches XL</a>
                        <a href="<?php echo esc_url( $sandwiches ); ?>">
                              <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/homePageOurItems/Sandwiches.jpg" alt="Our Special Category">
                        </a>
                  </div>
		</div>
	</div>				
</section>