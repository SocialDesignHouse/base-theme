<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
<?php if (is_search()) { ?><meta name="robots" content="noindex, nofollow" /><?php } ?>
<title><?php
if (function_exists('is_tag') && is_tag()) {
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
} if ($paged>1) {
	echo ' - page '. $paged;
} ?></title>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" >
<link href="<?php bloginfo('template_directory'); ?>/_/img/apple-touch-icon.png" rel="apple-touch-icon" >
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_/js/modernizr-1.7.min.js"></script>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header id="header">
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<?php $defaults = array(
		'container'       => 'nav',
		'container_class' => 'nav-main', 
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'items_wrap'      => "\n".'<ul>'."\n".'%3$s</ul>'."\n",
		'depth'           => 0);
		?>
		<?php wp_nav_menu( $defaults ); ?>
	</header>