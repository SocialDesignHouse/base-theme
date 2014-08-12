// remap jQuery to $
(function($){

	//module for site logic
	var site = (function() {
		//initializing public object of site object to return public methods and variables a thte end of the module
		var pub = {};

		//initialize any variables you need here
		//use pub.varname = x; for publicly accessible variables and var varname = x; for private variables

		//any configuration or initialization should go here
		pub.init = function init() {
			bindEvents();
		};

		//any event binding should happen in this method
		function bindEvents() {
			$(document).ready(function() {
				startUp();
			});

			/* $(window).load(function() {

			});

			$(window).resize(function() {

			}); */
		}

		//this method should call any code that requires a document.ready() to run
		function startUp() {

		}

		//any methods you need can be defined here
		//for public methods use pub.methodName = function methodName() {  };
		//for private methods use function methodName() {  }

		//make our public methods and variables accessibe
		return pub;
	}());

	//initialize our module
	site.init();

})(window.jQuery);