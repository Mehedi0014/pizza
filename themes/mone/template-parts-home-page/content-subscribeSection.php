<section id="subscribeSection" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/subscribeFormBg.jpg);">
	<div class="container h-100">
		<div class="row h-100">
			<div class="col-md-8 offset-md-2 h-100 d-flex align-items-center">
				<div class="caption w-100 text-center">
					<h3 class="title ct-colorTwo">Abonnieren sie unseren Newsletter</h3>
					<div class="marketingText">Hiermit erhalten jede Woche sie unsere leckeren Wochenangebote</div>
						<?php
							echo do_shortcode( '[gctlSubscribeNewsletter]' );
						?>
				</div>
			</div>
		</div>
	</div>			
</section>

