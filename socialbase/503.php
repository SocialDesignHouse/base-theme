<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<title><?php bloginfo('name'); ?> &raquo; <?php echo $this->g_opt['mamo_pagetitle']; ?></title>
	
	<style type="text/css">
		<!--
		* { margin: 0; 	padding: 0; }
		body { font-family: Arial, Helvetica, sans-serif; font-size: 65.5%; background: #ccc; }
		#header { color: #333; padding: 1.5em; text-align: center; font-size: 1.2em; border-bottom: 1px solid #08658F; }
		#content { font-size: 150%; width:60%; margin:10% auto; color: #999; padding: 5% 0; text-align: center; background: #fff; border: 5px solid #666; }
		#content p { font-size: 1em; padding: .8em 0; }
		h2 { color: #333; }
		-->
	</style>
	
</head>

<body>
	<div id="content">
		<div id="logo">
			<img src="http://dev.snagmetalsmith.org/wp-content/themes/snagmetalsmith/_/images/logo.png" width="229" height="95" />
		</div>
		<br /><br />
		<h2>We are currently down for scheduled maintenance</h2>
		<br /><br />
		<p><?php echo $this->mamo_template_tag_message(); ?></p>
		<p>Sorry for any inconvenience.</p>
	</div>
</body>
</html>