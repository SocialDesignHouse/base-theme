//DefaultText plug-in for jQuery
//	usage:
//		$(selector).defaultText({
//			def_text: 'Default Text'
//		});
		
(function( $ ) {
	$.fn.defaultText = function(options) {
		return this.each(function() {
			var settings = $.extend({
				def_text: ""
			}, options);
			$(this).val(settings.def_text);
			$(this).focus(function() {
				if($(this).val() == settings.def_text) {
					$(this).val("");
				}
			});
			$(this).blur(function() {
				if($(this).val() == "") {
					$(this).val(settings.def_text);
				}
			});
		});
	}
})( jQuery )

// remap jQuery to $
(function($){})(window.jQuery);

/* trigger when page is ready */
$(document).ready(function (){

	// your functions go here

});


/* optional triggers

$(window).load(function() {
	
});

$(window).resize(function() {
	
});

*/