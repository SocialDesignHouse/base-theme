<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head profile="https://gmpg.org/xfn/11">
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<?php //set title separator here
	$title_sep = ' | '; ?>

	<?php if (is_search()) { ?>

		<meta name="robots" content="noindex, nofollow" />

	<?php } ?>

	<meta name="title" content="

		<?php //format title
		if (function_exists('is_tag') && is_tag()) {
			single_tag_title("Tag Archive for &quot;"); echo '&quot;' . $title_sep;
		} elseif (is_archive()) {
			wp_title(''); echo ' Archive' . $title_sep;
		} elseif (is_search()) {
			echo 'Search for &quot;'.wp_specialchars($s).'&quot;' . $title_sep;
		} elseif (!(is_404()) && (is_single()) || (is_page())) {
			wp_title(''); echo $title_sep;
		} elseif (is_404()) {
			echo 'Not Found' . $title_sep;
		} elseif (is_home()) {
			bloginfo('name'); echo $title_sep; bloginfo('description');
		} else {
			bloginfo('name');
		}

		if ($paged>1) {
			echo $tile_sep . 'page '. $paged;
		} ?>

	">
	<!-- Use Yoast SEO plug-in to generate description meta -->
	<meta name="author" content="[Client Name]">
	<meta name="Copyright" content="Copyright [Client Name] [Year]. All Rights Reserved.">
	<title>

		<?php if (function_exists('is_tag') && is_tag()) {
			single_tag_title("Tag Archive for &quot;"); echo '&quot;' . $title_sep;
		} elseif (is_archive()) {
			wp_title(''); echo ' Archive' . $title_sep;
		} elseif (is_search()) {
			echo 'Search for &quot;'.wp_specialchars($s).'&quot;' . $title_sep;
		} elseif (!(is_404()) && (is_single()) || (is_page()) && !is_page('Home')) {
			wp_title(''); echo $title_sep;
		} elseif (is_404()) {
			echo 'Not Found' . $title_sep;
		} elseif (is_front_page()) {
			bloginfo('name'); echo $title_sep; bloginfo('description');
		} else {
			bloginfo('name');
		}

		if ($paged > 1) {
			echo $title_sep . 'page '. $paged;
		} ?>

	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- TypeKit load | REMOVE if not needed
	<script type="text/javascript">
		(function() {
			var config = {
				//change the kitId below to the correct typekit id
				kitId : '',
				scriptTimeout : 3000
			};
			var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
		})();
	</script>
	-->

	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/_/img/favicon.ico">
	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/_/img/apple-touch-icon.png" />

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

			<?php //build nav menu
			$defaults = array(
				'theme_location' => 'primary',
				'container' => 'false',
				'fallback_cb' => 'wp_page_menu',
				'items_wrap' => "\n".'<ul>'."\n".'%3$s</ul>'."\n",
				'depth' => 0
			);

			wp_nav_menu($defaults); ?>

		</nav>
	</header>