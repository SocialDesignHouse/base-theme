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

add_theme_support('automatic-feed-links');

//////////////////////////////////
// Add custom nav menu location
//////////////////////////////////

//set nav menu
function register_my_menus() {
	register_nav_menus(
		array(
			'primary' => __('Primary Nav')
		)
	);
}
//run this on init action
add_action('init','register_my_menus');

///////////////////////////////////
// Load jQuery (must be /_/js/jquery.min.js)
///////////////////////////////////

if ( !is_admin() ) {
	/*---------------------------------------------------------------------------------------------
	
		This snippet loads jQuery via Google, but falls back to a locally hosted copy
		if necessary. 

		It was modified from:

		http://wpshock.com/wordpress-load-jquery-from-google-libraries-cdn-with-local-jquery-fallback/

		There have been adjustments to make the code better suited for our purposes and also some
		formatting adjustments to make the code follow our style guidelines.
	
	---------------------------------------------------------------------------------------------*/
			
	// jQuery via Google CDN With Local Fall Back
	$v = '1.8.1'; //you must update this to the most recent version of jQuery
	// the URL to check against
	$url = 'http://ajax.googleapis.com/ajax/libs/jquery/' . $v . '/jquery.min.js';
	// test parameters
	$test_url = @fopen($url,'r');
	// test if the URL exists
	if($test_url !== false) {
		// deregisters the default WordPress jQuery
		wp_deregister_script( 'jquery' );
		// register the external file
		wp_register_script('jquery', $url, '', $v, true);
		// enqueue the external file
		wp_enqueue_script('jquery');
	} else {
		// enqueue the local file
		wp_deregister_script("jquery");
		// register the external file
		wp_register_script("jquery",get_template_directory() . '/_/js/jquery.min.js', '', $v, true);
		// enqueue the external file
		wp_enqueue_script('jquery');
	}
	// END wpshock.com modified code
}

///////////////////////////////////
// Hide Admin Bar
///////////////////////////////////

/*---------------------------------------------------------------------------------------------
	
	This snippet hides the admin bar entirely and removes the 28px of space at the top of 
	the screen when the admin bar is missing

	It was modified from:

	http://wp.tutsplus.com/tutorials/how-to-disable-the-admin-bar-in-wordpress-3-3/

	There have been adjustments to format the code to follow our style guidelines.
	
---------------------------------------------------------------------------------------------*/

if(!function_exists('disableAdminBar')) {
	function disableAdminBar() {
		// for the admin page
		remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );
		// for the front end 
		remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); 

		// css override for the admin page
		function remove_admin_bar_style_backend() {
			echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
		}
		add_filter('admin_head', 'remove_admin_bar_style_backend');

		// css override for the frontend
		function remove_admin_bar_style_frontend() {
			echo '<style type="text/css" media="screen">html { margin-top: 0px !important; } * html body { margin-top: 0px !important; } </style>';
		}
		add_filter('wp_head', 'remove_admin_bar_style_frontend', 99);
	}
}

add_action('init', 'disableAdminBar');

///////////////////////////////////
// Add featured image support
///////////////////////////////////

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
// Remove Dashboard Widgets
///////////////////////////////////

function disable_default_dashboard_widgets() {
	// disable default dashboard widgets
	remove_meta_box('dashboard_right_now', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	//remove_meta_box('meandmymac_rss_widget', 'dashboard', 'normal'); //AdRotate
}
add_action('admin_menu', 'disable_default_dashboard_widgets');
	
function hide_welcome_screen() {
	//hide the welcome panel
	$user_id = get_current_user_id();
	if ( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) )
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
}
add_action( 'load-index.php', 'hide_welcome_screen' );

///////////////////////////////////
// Muffle Update Notices
///////////////////////////////////

function run_chk_usr_lvl($matches) {
	global $userdata;
	if (!current_user_can('update_plugins')) { 
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('admin_init', 'run_chk_usr_lvl');

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
// Check user entered URLs
///////////////////////////////////

/*
used to create appropriate links from user-entered urls

parameters:
	$link = link the user entered (will be set to javascript:void(0); if left empty)
	$text = text to show up as a link (required)
	$title = optional title attribute for link (will be set to text if left empty)
	$options = associative array of extra attributes (will be set to an empty array if no array is included as a parameter)
		example:
			$opt_array = array(
				'target' => '_blank',
				'class' => 'portfolio-link',
			);
	
use this function like this:  $link = social_anchor($link,$text,$title,$options);
*/

function social_anchor($link = '', $text, $title = '', $options = array()) {
	//set array for commonly-used TLD's, add more as they come up
	$tld = array('.com','.org','.net','.us','.co.uk','.biz','.mobi','.me','.info','.edu','.gov','.mil');
	//set extra attributes to null
	$add_atts = '';
	//loop through options array and add them to the extra attributes string
	foreach($options as $att => $val) { 
		$add_atts .= ' ' . $att . '="' . $val . '"';
	}
	//if there is no title, the link text becomes the title
	if(!$title) { $title = $text; }
	//check for http://, https://, ftp://, and /
	if(substr($link,0,7) == "http://" || substr($link,0,8) == "https://" || substr($link,0,1) == "/" || substr($link,0,6) == "ftp://") {	
		$href = 'href="' . $link . '"';
	//if it starts with www. or ends with one of the pre-defined TLDs we set up earlier
	} elseif(substr($link,0,4) == "www." || in_array(substr($link,-4),$tld)) {
		$href = 'href="http://' . $link . '"';
	//if it has no http:// or www. or TLD it is probably internal, so let's add a slash and call it a day
	} elseif($link != '') {
		$href = 'href="/' . $link . '"';
	//if the link is null, set href to javascript:void(0);
	} else {
		$href = 'href="javascript:void(0);"';
	}
	$link = '<a' . $add_atts . ' ' . $href . '" title="' . $title . '">' . $text . '</a>';
	//return the formatted link
	return $link;
}

///////////////////////////////////
// Enqueue Register Script
///////////////////////////////////

//wp_register_script('facebook',get_stylesheet_directory_uri() . "/_/js/fb.js",'','',true);
//wp_enqueue_script('facebook');
//wp_register_script('jq-def-text', get_bloginfo('template_directory') . "/_/js/jquery.def-text/jquery.def-text-1.3.min.js", array('jquery'), '1.3', true);
//wp_enqueue_script('jq-def-text');