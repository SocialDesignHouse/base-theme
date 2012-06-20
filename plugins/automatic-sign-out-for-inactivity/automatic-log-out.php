<?php
/*
Plugin Name: Automatic Sign Out For Inactivity
Plugin URI: http://wordpress.org/extend/plugins/automatic-sign-out-for-inactivity
Description: After a time period of inactivity, this plugin will log the inactive user out. You can adjust how long a user is allowed to stay inactive before he is logged out, and you can also set where the user will be redirected to after he is logged out. (To change these settings, simply adjust the two variables in the code to suit your needs)
Version: 1.0
Author: Stuart Baxter
Author URI: http://stuartbloghelpauthor.bestforever.com/
License: GPL2
*/
/*  Copyright 2010  Stuart Baxter  (email : stuart_baxter90@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// -----------------------HOOKS--------------------------------
add_action('wp_login', 'automatic_sign_out_update_last_activity_on_login_event', 1);
add_action('get_header', 'automatic_sign_out_inactivity_event', 1);
add_action('admin_init', 'automatic_sign_out_inactivity_event', 1);

// Stores time of last activity into cookie
define('AUTOMATIC_LOG_OUT_LAST_ACTIVITY_TIME', 'bry_time');
 
//-----Redirect User to this page on Automatic Log Out------------
/*Adjust this variable to change the location to where the logged out user
will be redirected to. If you don't want to redirect the user anywhere,
then leave it as site_url()                                    */
define('AUTOMATIC_SIGN_OUT_REDIRECT_ADDRESS', site_url());

//-----Maximum time allowed before logged out for inactivity---------- 
/*Adjust this variable to change the maximum time. The time unit is
seconds. This means that 60*60*24 is equal to 1 day. Why? because
60 seconds * 60 minutes * 24 hours = 1 day                        */
define('AUTOMATIC_SIGN_OUT_MAXIMUM_INACTIVITIY_TIME', 60*60*24);
//-------------------------------------------------------------------


/* This function logs the user out then immediately redirects them to the specified page. 
   If no page is specified, then they will just be logged out and won't be redirected anywhere */
function automatic_signout_perform_logout_event() {
	wp_logout();
	wp_redirect(AUTOMATIC_SIGN_OUT_REDIRECT_ADDRESS);
}

function automatic_sign_out_activate() {
	automatic_sign_out_update_last_activity_event();
}

function automatic_sign_out_inactivity_event() {
	if (is_user_logged_in()) {
		$last_activity_time = AUTO_SIGN_OUT_GET_TIME_OF_LAST_ACTIVITY();
		if ($last_activity_time + AUTOMATIC_SIGN_OUT_MAXIMUM_INACTIVITIY_TIME < time()) {
			// log out
			automatic_signout_perform_logout_event();
		}else {
			// Stay logged in and update cookie
			automatic_sign_out_update_last_activity_event();
		}
	}
}

// This function gets the last time of activity
 function AUTO_SIGN_OUT_GET_TIME_OF_LAST_ACTIVITY() {
	if (is_user_logged_in()) {
		return (int) get_usermeta(get_current_user_id(), AUTOMATIC_LOG_OUT_LAST_ACTIVITY_TIME);
	}else {
		return 0;
	}
}

// This function sets the last active time on login.
function automatic_sign_out_update_last_activity_on_login_event($username) {
	$now = time();
	try {
		$user_id = get_userdatabylogin($username)->ID;
		if ($user_id != null && $user_id > 0) {
			update_usermeta($user_id, AUTOMATIC_LOG_OUT_LAST_ACTIVITY_TIME, $now);
		}
	}catch (Exception $ex) {
		
	}
}

function automatic_sign_out_update_last_activity_event() {
	if (is_user_logged_in()) {
		$now = time();
		update_usermeta(get_current_user_id(), AUTOMATIC_LOG_OUT_LAST_ACTIVITY_TIME, $now);
	}
}

register_activation_hook( __FILE__, 'automatic_sign_out_activate' );