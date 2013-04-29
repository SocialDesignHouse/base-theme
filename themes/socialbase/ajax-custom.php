<?php

//mimic the actuall admin-ajax
define('DOING_AJAX', true);

if (!isset($_POST['action'])) {
	die('-1');
} else {
	ini_set('html_errors', 0);
	//define('SHORTINIT', true);

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

	//Typical headers
	header('Content-Type: text/html');
	send_nosniff_header();

	//Disable caching
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	//call action and exit
	$action = trim($_POST['action']);
	if(is_user_logged_in()) {
		do_action('wp_custom_ajax_' . $action);
	} else {
		do_action ('wp_custom_ajax_nopriv_' . $action);
	}

	die(0);
}