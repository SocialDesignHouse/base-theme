<?php

///////////////////////////////////
// Translations can be filed in the /languages/ directory
///////////////////////////////////

load_theme_textdomain( 'socialbase', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);

///////////////////////////////////
// Add RSS links to <head> section
///////////////////////////////////

automatic_feed_links();

///////////////////////////////////
// Load jQuery (must be /_/js/jquery.min.js)
///////////////////////////////////

if ( !function_exists(core_mods) ) {
	function core_mods() {
		if ( !is_admin() ) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', (get_stylesheet_directory_uri() . "/_/js/jquery.min.js"), false);
			wp_enqueue_script('jquery');
		}
	}
	core_mods();
}

///////////////////////////////////
// Hide Admin Bar
///////////////////////////////////

if (!current_user_can('edit_posts')) {
	add_filter('show_admin_bar', '__return_false');
}


///////////////////////////////////
// Add featured image support
///////////////////////////////////

//add post-thumbnails to the system
add_theme_support('post-thumbnails');


///////////////////////////////////
// Adds featured images to RSS feed
///////////////////////////////////

function featuredtoRSS($content) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ){
		$content = '<div>' . get_the_post_thumbnail( $post->ID, 'full', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
	}
	return $content;
}

add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

///////////////////////////////////
// Clean Up Head
///////////////////////////////////

function removeHeadLinks() {
	//remove feeds from header
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action( 'wp_head', 'rel_canonical');
	remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
   }
   add_action('init', 'removeHeadLinks');
   remove_action('wp_head', 'wp_generator');
   
///////////////////////////////////
// Register Sidebars
///////////////////////////////////

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => __('Sidebar Widgets','socialbase' ),
		'id'   => 'sidebar-widgets',
		'description'   => __( 'These are widgets for the sidebar.','socialbase' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>'
	));
}

///////////////////////////////////
// Remove Wordpress Version for Security
///////////////////////////////////

function complete_version_removal() {
    return '';
}
add_filter('the_generator', 'complete_version_removal');

if (!is_admin()) {
  wp_deregister_script('l10n');
}
   
///////////////////////////////////
// Add Post Formats
///////////////////////////////////

   add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video')); // Add 3.1 post format theme support.

///////////////////////////////////
// Add page name to body's class attribute
///////////////////////////////////

function add_body_class($classes) {
	global $pagename;
	$classes[] = $pagename;
	return $classes;
}

add_filter('body_class','add_body_class');

///////////////////////////////////
// Add page id as nav html IDs
///////////////////////////////////

function nav_class_filter( $var ) {
	return is_array($var) ? array_intersect($var, array('current-menu-ancestor','current-menu-parent','current-page-parent','current-menu-item','current-page-ancestor','current-page-item')) : '';
}

add_filter('nav_menu_css_class','nav_class_filter',100, 1);

function nav_id_filter($id, $item) {
	return 'nav-' . $item->object_id;
}

add_filter('nav_menu_item_id','nav_id_filter',10,2 );

///////////////////////////////////
// Enqueue Register Script
///////////////////////////////////

wp_register_script('facebook',get_stylesheet_directory_uri() . "/_/js/fb.js",'','',true);
wp_enqueue_script('facebook');

?>