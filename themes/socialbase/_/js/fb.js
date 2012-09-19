/* All Facebook functions should be included in this function, or at least initiated from here */

var app_id = ''; //enter FB app id here to have the script load properly
var url = ''; //enter fully-qualified url to theme's channel.php here

window.fbAsyncInit = function() {
	FB.init({
		appId: app_id,
		status: true,
		cookie: true,
		xfbml: true,
		oauth: true, // enable OAuth 2.0
		channelUrl: url //custom channel
	});
	FB.api('/me', function(response) {
		console.log(response.name);
	});
};
$(function() {
	var e = document.createElement('script'); e.async = true;
	e.src = document.location.protocol +
	'//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
}());