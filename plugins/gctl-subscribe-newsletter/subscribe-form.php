<?php
//$siteUrl = get_site_url( null, '/subscribe-to-newsletter/', null );
$siteRootUrl = get_site_url( null, '/', null );
?>
	<form action="<?php $siteRootUrl; ?>" method="POST">
		<div class="newsletter-form position-relative">
			<input class="gctlSubscribeInput " type="email" name="emailAddress" placeholder="Type here your email address to receive our newsletter">
			<button class="btn ct_btnTwo gctlSubscribebutton" type="submit" name="submitForm">Sign Up</button>
		</div>
	</form>
	<div class="notice text-center">
		<p><?php echo $this->errorMsg; ?></p>
	</div>