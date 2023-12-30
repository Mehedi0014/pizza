<?php
/**
 * Template Name: Contact Us
 * The template for displaying Contact Us page
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package mOne
 */

get_header();
?>

<main id="contactUsPageWrapper">
	<section id="textInfoPart" class="py-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 mb-5">
					<div class="title">Rufen sie uns an oder besuchen sie uns</div>
					<p class="textArea">Sie sind jederzeit in unserem Restaurant willkommen</p>
				</div>
				<div class="col-md-3 text-center text-md-left mb-5 mb-md-0">
					<div class="icon"><i class="fa fa-mobile"></i></div>
					<div class="heading">Telefon</div>
					<div class="content">+49 06206 951 05 54</div>
				</div>
				<div class="col-md-3 text-center text-md-left mb-5 mb-md-0">
					<div class="icon"><i class="fa fa-map-marker"></i></div>
					<div class="heading">Adresse</div>
					<div class="content">Straße : "Römerstr. 160" <br> Stadt : "Lampertheim" <br> PLZ "68623</div>
				</div>
				<div class="col-md-3 text-center text-md-left mb-5 mb-md-0">
					<div class="icon"><i class="fa fa-envelope-o"></i></div>
					<div class="heading">Email:</div>
					<div class="content">colosseo@pizzeriacolosseo.de</div>
					
				</div>
				<div class="col-md-3 mb-5 mb-md-0">
					<div class="icon text-center text-md-left"><i class="fa fa-clock-o"></i></div>
					<div class="heading text-center text-md-left">Öffnungszeiten</div>
					<ul class="list-unstyled content">
						<li class="clearfix">
							<span class="day">Montag</span>
							<span class="pull-right flip hours">11:00 bis 22:30 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Dienstag</span>
							<span class="pull-right flip hours">11:00 bis 22:30 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Mittwoch</span>
							<span class="pull-right flip hours">11:00 bis 22:30 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Donnerstag</span>
							<span class="pull-right flip hours">11:00 bis 22:30 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Freitag</span>
							<span class="pull-right flip hours">11:00 bis 23:00 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Samstag</span>
							<span class="pull-right flip hours">11:00 bis 23:00 Uhr</span>
						</li>
						<li class="clearfix">
							<span class="day">Sonntag und Feiertags</span>
							<span class="pull-right flip hours">11:00 bis 22:30 Uhr</span>
						</li>
					</ul>

				</div>
			</div>
		</div>
	</section>


	<section id="mapAndForm" class="mb-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 mb-5 mb-md-0">
					<iframe width="100%" height="550" id="gmap_canvas" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2586.1275015056412!2d8.4613024!3d49.595345!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4797d3a8b66cf139%3A0xdfbbf0943ded6468!2sPizzeria%20Colosseo!5e0!3m2!1sde!2sde!4v1616064406527!5m2!1sde!2sde" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
				</div>
				<div id="contact_form" class="col-md-6 custom_form px-4">
					<div class="title">Senden sie uns eine Nachricht</div>
					<p class="textArea mb-5">Ihr Datenschutz ist uns wichtig!</p>

					<div class="custom_form border p-4">            
							<?php
								echo do_shortcode( '[contact-form-7 id="235" title="Contact form contact us page"]' );
							?>
	                </div>

				</div>
			</div>
		</div>
	</section>
</main>

<?php

get_footer();
