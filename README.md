base-theme
==========

The Base WordPress theme we start our projects with. It is a modified version of the HTML5 Reset theme.

Instructions
=======

1. Run `npm install grunt --save-dev`
2. Run `npm install grunt-modernizr --save-dev`
3. Run `grunt modernizr` to get your packaged Modernizr build

Notes
===

When you need to perform ajax calls that will need to be fast and may transfer a large amount of data, utilize the ajax-custom.php file as your AJAX URL instead of the admin-ajax.php file.