=== Automatic Sign Out For Inactivity ===
Contributors: 2k8bomb
Donate link: http://stuartbloghelpauthor.bestforever.com/
Tags: automatic, logout, log, out, inactive, inactivity, plugin, buddypress, bp, widget, post, signout, buddy, sign, press, auto
Requires at least: 2.7
Tested up to: 3.3.1
Stable tag: 1.0

This plugin helps to minimize some security concerns. It will automatically sign an inactive user out after a specified time period of inactivity. 

== Description ==
This plugin helps to minimize certain security risks involved with session stealing. It allows you to automatically sign a user out after he has been inactive for a specified period of time. This plugin also works with BuddyPress. The default time period is set to one day. This means that if a user was logged into your site  and did not come back after 24 hours, he would be logged out due to inactivity. However, if he performed any sort of activity on your site (e.g. browsed a new page) between his latest activity and 24 hours after that, then he would not be logged out. You can change this time period to whatever suits your needs. Additionally, you can specify a page where the user will be redirected to when he gets logged out (e.g. a landing page to notify the user he has been logged out for inactivity). However, this is optional and you can make the user not be redirected when he gets automatically logged out. No redirection is the default setting.

== Installation ==

1. Upload the *.zip copy of this plugin into your WordPress through your Plugin admin page.
2. Activate the plugin through the Plugins menu in WordPress
3. Adjust the time period and redirection site if needed. (See below to learn how to configure these settings)
4. If you want to test the plugin to see if it's working, then change the time period of inactivity to a few seconds. If you
get logged out after those few seconds of inactivity, then it works!

== Frequently Asked Questions ==

= How can I set up the time period of inactivity correctly? =

You need to change the value of the constant AUTOMATIC_SIGN_OUT_MAXIMUM_TIME to the time you specify. The time is measured in seconds, so 60 = 1 minute, 60 * 60 = 1 hour, 60 * 60 * 24 = 1 day.
Here are some examples:

define('AUTOMATIC_SIGN_OUT_MAXIMUM_INACTIVITIY_TIME', 60 * 60 * 24);  // 1 day 

define('AUTOMATIC_SIGN_OUT_MAXIMUM_INACTIVITIY_TIME', 60 * 60); // 1 hour

define('AUTOMATIC_SIGN_OUT_MAXIMUM_INACTIVITIY_TIME', 60); // 1 minute

= What if I don't specify a redirection address? =

If you don't specify a redirection address and leave it as the default site_url() then the user will not be redirected anywhere when he is automatically logged out.
However, if you want to change it to an actual address, then replace site_url() with "http://www.yourwebsite.com/". (Remember to include the quotations.)

== Screenshots ==

1. Automatic Sign out plugin in the administration plugins page.

== Changelog ==

= 1.0 =
*Initial release

== Upgrade Notice ==

= 1.0 =
*Initial release
