<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mOne
 */
?>


<footer id="mainFooter">    
	<div class="container">
	    <div class="row MainPart">	    	
	        <div class="col-md-3">
	            <div class="OfficeAddress pb-4">
	                <div class="footerWidget">
	                    <h5 class="">Kontaktieren Sie uns</h5>
	                </div>
	                <div>
	                    <p>
	                        <span class="h2">+49 6206 951 05 54</span> <br>
	                        <a href="mailto:colosseo@pizzeriacolosseo.de">colosseo@pizzeriacolosseo.de</a>
	                    </p>
	                </div>
	                <div>
	                	<a href="<?php echo get_permalink(635); ?>">AGB</a>
	                	<br>
	                	<a href="<?php echo get_permalink(3); ?>">Datenschutzbestimmungen</a>
	                </div>
	            </div>
	        </div>
	        <div class="col-md-6">
	            <div class="OfficeAddress text-center">
	                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/assets/img/footerLogo.png" alt="Logo Icon">
	                <div class="text-center mt-2">
	                	Straße : Römerstr. 160 <br> 
	                	Stadt : Lampertheim <br> 
	                	PLZ: 68623 <br>
					</div>
	            </div>
	        </div>
	        <div class="col-md-3">
	            <div class="QuickLink">
	                <div class="footerWidget">
	                    <h5 class="">Öffnungszeiten</h5>
	                </div>
	                <div>
	                    <ul class="listDecoration">
	                        <li>Montag - Donnerstag</li>
	                        <li>11:00 – 22:30</li>
	                        <li>Freitag - Samstag</li>
	                        <li>11:00 - 23:00</li>
	                        <li>Sonntag </li>
	                        <li>11:00 - 22:30</li>
	                    </ul>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="row subFooter">
	        <div class="col-12">
	            <p>All Right Reserve &copy; <?php echo date('Y')?> - CLS Soft </p>
	        </div>
	    </div>
	</div>
</footer>



<section id="googleMap">
	<div class="buttonPart d-flex justify-content-center">
		<button class="btn googleMapToggleBtn ct-bgColorTwo d-flex align-items-center"><i class="fa fa-2x fa-map-marker mr-3"></i><span>Anfahrt</span></button>
	</div>
	<div id="google_map">
	    <div class="container-fluid hide_overflow">
	        <div class="row">
	            <div class="col-12">
					<iframe id="g-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2586.127503812982!2d8.46130241587304!3d49.595344956486954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4797d3a8b66cf139%3A0xdfbbf0943ded6468!2sPizzeria%20Colosseo!5e0!3m2!1sde!2sde!4v1616000656344!5m2!1sde!2sde" width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
	            </div>
	        </div>
	    </div>
	</div>	
</section>










<?php wp_footer(); ?>

</body>
</html>
