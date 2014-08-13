<?php

//Theme Setup (based on twentythirteen: http://make.wordpress.org/core/tag/twentythirteen/)
function html5reset_setup() {
	//Translations can be filed in the /languages/ directory
	load_theme_textdomain('html5reset', get_template_directory() . '/languages');

	add_theme_support('automatic-feed-links');
	add_theme_support('structured-post-formats', array('link', 'video'));
	add_theme_support('post-formats', array('aside', 'audio', 'chat', 'gallery', 'image', 'quote', 'status'));
	add_theme_support('post-thumbnails');

	register_my_menus();
}

add_action('after_setup_theme', 'html5reset_setup');


//Add custom nav menu location
function register_my_menus() {
	register_nav_menus(
		array(
			'primary' => __('Primary Nav')
			//add other nav menu locations as needed
		)
	);
}

//Remove Attachment link
function remove_media_link( $form_fields, $post ) {
	unset( $form_fields['url'] );
	return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'remove_media_link', 10, 2 );

//Adds featured images to RSS feed
function featuredtoRSS($content) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ){
		$content = '<div>' . get_the_post_thumbnail( $post->ID, 'full', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
	}
	return $content;
}

add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

//Clean Up Head
function removeHeadLinks() {
	//remove feeds from header
	remove_action( 'wp_head', 'feed_links_extra', 3 ); //Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); //Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); //Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); //Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); //index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); //prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); //start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); //Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action( 'wp_head', 'rel_canonical');
	remove_action( 'wp_head', 'wp_generator' ); //Display the XHTML generator that is generated on the wp_head hook, WP version
}

add_action('init', 'removeHeadLinks');

remove_action('wp_head', 'wp_generator');

//Remove Dashboard Widgets
function disable_default_dashboard_widgets() {
	//disable default dashboard widgets
	remove_meta_box('dashboard_right_now', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	//remove_meta_box('meandmymac_rss_widget', 'dashboard', 'normal'); //AdRotate RSS | uncomment if using AdRotate
}

add_action('admin_menu', 'disable_default_dashboard_widgets');

//Hide the welcome panel
function hide_welcome_screen() {
	$user_id = get_current_user_id();
	if ( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) )
		update_user_meta( $user_id, 'show_welcome_panel', 0 );
}

add_action( 'load-index.php', 'hide_welcome_screen' );

//Muffle Update Notices
function run_chk_usr_lvl($matches) {
	global $userdata;
	if (!current_user_can('update_plugins')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}

add_action('admin_init', 'run_chk_usr_lvl');

//Register Sidebars
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => __('Sidebar Widgets', 'socialbase'),
		'id' => 'sidebar-widgets',
		'description' => __( 'These are widgets for the sidebar.', 'socialbase' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
}

//Remove Wordpress Version
function complete_version_removal() {
	return '';
}

add_filter('the_generator', 'complete_version_removal');

if (!is_admin()) {
	wp_deregister_script('l10n');
}

//Add page name to body's class attribute
function add_body_class($classes) {
	global $pagename;
	$classes[] = $pagename;
	return $classes;
}

add_filter('body_class','add_body_class');

//Add page id as nav html IDs
function nav_id_filter($id, $item) {
	return 'nav-' . $item->object_id;
}

add_filter('nav_menu_item_id','nav_id_filter',10,2 );

//Filter out unwanted current-menu states
function nav_class_filter( $var ) {
	return is_array($var) ? array_intersect($var, array('current-menu-ancestor','current-menu-parent','current-page-parent','current-menu-item','current-page-ancestor','current-page-item')) : '';
}

add_filter('nav_menu_css_class','nav_class_filter',100, 1);

//Enqueue/Register Scripts & Styles
function social_enqueue_scripts_styles() {
	//JavaScript
	wp_register_script('modernizr', get_stylesheet_directory_uri() . '/_/js/modernizr.min.js', array(), '2.8.3', false); //check version #
	wp_register_script('functions', get_stylesheet_directory_uri() . '/_/js/functions.min.js', array('jquery'), '0.0.0', true); //add version #
	//wp_register_script('facebook',get_stylesheet_directory_uri() . '/_/js/fb.min.js','','',true); //if you need facebook async

	wp_enqueue_script('modernizr');
	wp_enqueue_script('functions');
	//wp_enqueue_script('facebook');

	//CSS
	//put any links to external CSS files for typefaces here as well, be sure to add them to the dependency array for the theme stylesheet
	wp_register_style('theme', get_stylesheet_directory_uri() . '/_/css/theme.css', array(), '0.0.0', 'all'); //add version #

	wp_enqueue_style('theme');
}

add_action('wp_enqueue_scripts', 'social_enqueue_scripts_styles');


/* ----- Extra functionality ----- */


/*--------------------------------------------------------------------------------------------------------------
	truncate_copy() - Intelligent Text Truncation

	$content (str) [required] - The content to be truncated
	$cut_length (int) [optional] default: 140 - The number of characters to truncate to
	$filter (bool) [optional] default: true - Should the_content filter be applied to this copy?

	This function takes the above parameters and attempts to truncate the text around the character limit intelligently.

	This means that it tries to find a line break prior to the $cut_length value and will truncate the text there,
	and if no linebreak is found, it will find the end of the nearest word to the $cut_length character and truncate there.

	If non-linebreak truncation occurs, an ellipsis is appened to the text.

	TODO:  Increase intelligence to look for the end of a sentence near the $cut_length character within a threshold of characters
	before or after the $cut_length character.

--------------------------------------------------------------------------------------------------------------*/

function truncate_copy($content, $cut_length = 140, $filter = true) {
	if($filter) {
		$post_content = apply_filters('the_content', $content);
	}

	//find the position to cut our content at
	if(strlen($post_content) > $cut_length) {
		$space_pos = strpos($post_content, ' ', $cut_length);

		$new_line_pos = strpos($post_content, "\n");

		if($space_pos) {
			if($space_pos > $new_line_pos) {
				$cut_pos = $new_line_pos;
				$ellipsis = '';
			} else {
				$cut_pos = $space_pos;
				$ellipsis = '...';
			}
		} else {
			$cut_pos = strlen($post_content);
			$ellipsis = '';
		}
	} else {
		$cut_pos = strlen($post_content);
		$ellipsis = '';
	}

	return substr($post_content, 0, $cut_pos) . $ellipsis;
}