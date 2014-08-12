<?php /*------------------------------------------------------------------------------

	Newsletter Form

	This will generate a form based on the few values below the user will need to
	fill in from Campaign Monitor.

------------------------------------------------------------------------------*/

$f_action = 'http://send.socialdesignhouse.com/t/r/s/'; //CM form action here
$e_field = 'cm-'; //CM email field name here
$n_field = 'cm-name'; //CM name field name here
$f_method = 'post'; //CM form method here
$f_class = 'signup-form';
$msg = 'Mailing List'; ?>

<div class="newsletter-signup">

	<?php if($msg) { ?>

		<h3 class='signup-title'><?php echo $msg; ?></h3>

	<?php } ?>

	<form class="<?php echo $f_class; ?>" method="post" action="<?php echo $f_action; ?>">
		<input type="text" class="signup-name" id="<?php echo $n_field; ?>" name="<?php echo $n_field; ?>" value="" />
		<input type="email" class="signup-email" id="<?php echo $e_field; ?>" name="<?php echo $e_field; ?>" value="" />
		<input type="submit" value="Join" id="news-join" class="signup-join" />
	</form>
</div>