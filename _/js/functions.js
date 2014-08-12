// remap jQuery to $, make sure window and undefined are defined correctly for this script
(function($, window, undefined){

	//module for site logic
	var site = (function() {
		//initializing public object of site object to return public methods and variables a thte end of the module
		var pub = {};

		//default configuration for module
		var defaultConfig = {};

		//initialize any variables you need here
		//use pub.varname = x; for publicly accessible variables and var varname = x; for private variables

		//any configuration or initialization should go here
		pub.init = function init(cfg) {
			//we want to store our configuration as site.config so that we can access it's properties elsewhere
			site.config = $.$.extend(true, {}, defaultConfig, cfg);

			//config
		};

		//this method should call any code that requires a document.ready() to run
		pub.startUp = function startUp() {
			bindEvents();

			//other startup actions that require the document to be ready
		}

		//this method should call any code that requires a window.load() to run
		pub.loaded = function loaded() {
			//startup actions that require the window to be fully loaded
		}

		//any event binding should happen in this method
		function bindEvents() {
			//events
		}

		//any methods you need can be defined here
		//for public methods use pub.methodName = function methodName() {  };
		//for private methods use function methodName() {  }

		//make our public methods and variables accessibe
		return pub;
	}($, window));

	//object for overriding default configuration options, useful for switching between dev and live environment variables and such
	var siteConfig = {};

	//initialize our module
	site.init(siteConfig);

	$(document).ready(function() {
		site.startUp();
	});

	$(window).load(function() {
		site.loaded();
	});

})(window.jQuery, window);