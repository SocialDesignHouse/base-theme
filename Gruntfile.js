module.exports = function(grunt) {
	require('load-grunt-tasks')(grunt);

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
				tasks: ['newer:uglify', 'notify:scripts'],
				options: {
					spawn: false
				}
			},
			css: {
				files: ['_/css/scss/**/*.scss'],
				tasks: ['sass', 'newer:cssmin', 'notify:sass'],
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
					'owl.carousel.js': 'owl-carousel/owl-carousel/owl.carousel.js'
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
		},

		notify: {
			sass: {
				options: {
					title: 'Grunt',  // optional
					message: 'SASS compiled' //required
				}
			},
			scripts: {
				options: {
					title: 'Grunt',  // optional
					message: 'JavaScript concatenated and minified'
				}
			}
		}
	});

	grunt.registerTask('default', ['modernizr', 'bowercopy']);

	grunt.registerTask('setup', ['modernizr', 'bowercopy']);

	grunt.event.on('watch', function(action, filepath, target) {
		grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
	});
};