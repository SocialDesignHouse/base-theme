/*

TO DO

1) Reduce CSS duplication
   - Ideally just a single build - global.scss turns into /build/global.css
   - Can Autoprefixer output minified?
   - If it can, is it as good as cssmin?
   - Could Sass be used again to minify instead?
   - If it can, is it as good as cssmin?

2) Better JS dependency management
   - Require js?
   - Can it be like the Asset Pipeline where you just do //= require "whatever.js"

3) Is HTML minification worth it?

4) Set up a Jasmine test just to try it.

5) Can this Gruntfile.js be abstracted into smaller parts?
   - https://github.com/cowboy/wesbos/commit/5a2980a7818957cbaeedcd7552af9ce54e05e3fb

*/

module.exports = function(grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		uglify: {
			build: {
				src: ['_/js/*.js', '_/js/functions.js', '!_/js/modernizr.min.js', '!_/js/dev/modernizr.dev.js'],
				dest: '_/js/theme.min.js'
			}
		},

		sass: {
			dist: {
				options: {
					style: 'expanded',
					sourcemap: true,
					compass: true
				},
				files: {
					'_/css/theme.css' : '_/css/scss/theme.scss'
				}
			}
		},

		modernizr: {
			dist: {
				"devFile" : "_/js/dev/modernizr.dev.js",
				"outputFile" : "_/js/modernizr.min.js",
				"extra" : {
					"shiv" : true,
					"printshiv" : false,
					"load" : true,
					"mq" : false,
					"cssclasses" : true
				},
				"extensibility" : {
					"addtest" : false,
					"prefixed" : false,
					"teststyles" : false,
					"testprops" : false,
					"testallprops" : false,
					"hasevents" : false,
					"prefixes" : false,
					"domprefixes" : false
				},
				"uglify" : true,
				"tests" : [],
				"parseFiles" : true,
				"matchCommunityTests" : false,
				"customTests" : []
			}
		},

		watch: {
			scripts: {
				files: ['_/js/**/*.js'],
				tasks: ['uglify'],
				options: {
					spawn: false
				}
			},
			css: {
				files: ['_/css/scss/**/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					spawn: false
				}
			}
		},

		cssmin: {
			minify: {
				expand: true,
				cwd: '_/css/',
				src: ['*.css', '!*.min.css'],
				dest: '_/css/',
				ext: '.min.css'
			}
		},

		bowercopy: {
			options: {
				clean: true
			},
			libs: {
				options: {
					destPrefix: '_/js'
				},
				files: {
					'opt/jquery.fancyBox.js': 'fancybox/source/jquery.fancybox.js',
					'respond.js': 'respond/src/respond.js',
					'owl.carousel.js': 'owl-carousel/own-carousel/owl.carousel.js'
				}
			},
			styles: {
				options: {
					destPrefix: '_/css'
				},
				files: {
					'opt/jquery.fancyBox.css': 'fancybox/source/jquery.fancybox.css',
					'owl.carousel.css': 'owl-carousel/owl-carousel/owl.carousel.css',
					'owl.transitions.css': 'owl-carousel/owl-carousel/owl.transitions.css'
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.loadNpmTasks('grunt-modernizr');
	grunt.loadNpmTasks('grunt-bowercopy');

	grunt.registerTask('default', ['modernizr', 'bowercopy']);

	grunt.registerTask('setup', ['modernizr', 'bowercopy']);

	grunt.event.on('watch', function(action, filepath, target) {
		grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
	});
};