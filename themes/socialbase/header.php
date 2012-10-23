<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head profile="https://gmpg.org/xfn/11">
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<?php if (is_search()) { ?>
		
		<meta name="robots" content="noindex, nofollow" />

	<?php } ?>

	<meta name="title" content="
		
		<?php //format title
		if (function_exists('is_tag') && is_tag()) {
			single_tag_title("Tag Archive for &quot;"); echo '&quot; - ';
		} elseif (is_archive()) {
			wp_title(''); echo ' Archive - ';
		} elseif (is_search()) {
			echo 'Search for &quot;'.wp_specialchars($s).'&quot; - ';
		} elseif (!(is_404()) && (is_single()) || (is_page())) {
			wp_title(''); echo ' - ';
		} elseif (is_404()) {
			echo 'Not Found - ';
		} if (is_home()) {
			bloginfo('name'); echo ' - '; bloginfo('description'); 
		} else {
			bloginfo('name'); 
		}
		if ($paged>1) {
			echo ' - page '. $paged;
		} ?>
	
	">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="author" content="Ryan Quincy">
	<meta name="Copyright" content="Copyright Ryan Quincy <?php echo date('Y'); ?>. All Rights Reserved.">
	<title>

		<?php if (function_exists('is_tag') && is_tag()) {
			single_tag_title("Tag Archive for &quot;"); echo '&quot; - ';
		} elseif (is_archive()) {
			wp_title(''); echo ' Archive - ';
		} elseif (is_search()) {
			echo 'Search for &quot;'.wp_specialchars($s).'&quot; - ';
		} elseif (!(is_404()) && (is_single()) || (is_page()) && !is_page('Home')) {
			wp_title(''); echo ' - ';
		} elseif (is_404()) {
			echo 'Not Found - '; 
		}
		if (is_front_page()) {
			bloginfo('name'); echo ' - '; bloginfo('description'); 
		} else {
			bloginfo('name'); 
		} if ($paged > 1) {
			echo ' - page '. $paged;
		} ?>

	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_/css/socialbase.css" />
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/_/img/favicon.ico">
	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/_/img/apple-touch-icon.png" />
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_/js/modernizr.min.js"></script>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<header id="header">
		<h1>
			<a href="<?php echo get_option('home'); ?>/">

				<?php bloginfo('name'); ?>

			</a>
		</h1>
		<nav class="nav-menu">

			<?php //check transient cache for menu first
			$local_nav = get_transient('primary_nav');
			//if we didn't find it
			if(false === ($local_nav)) {
				$defaults = array(
					'theme_location' => 'primary',
					'container' => 'false',
					'fallback_cb' => 'wp_page_menu',
					'items_wrap' => "\n".'<ul>'."\n".'%3$s</ul>'."\n",
					'depth' => 0,
					'echo' => 0
				);
				$local_nav = wp_nav_menu($defaults);
				//set the transient
				set_transient('primary_nav',$local_nav,3600*6);
			}
			echo $local_nav; ?>

		</nav>
	</header>