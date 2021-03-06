base-theme v2.2.1
==========

The Base WordPress theme we start our projects with. It is a modified version of the HTML5 Reset theme.

Instructions
=======

We recommend using something like [YeoPress](https://github.com/wesleytodd/YeoPress) for starting your project.

These instructions will follow the general way that we set up our project and will assume you have node.js, npm, git, yeoman, generator-wordpress, bundler, and grunt installed and configured.

1. Run `yo wordpress`
2. Type the URL of the site when prompted and hit Enter
3. Go to [MakeMyPassword](http://www.makemypassword.com) and generate a 5 character alphanumeric string for the table prefix.
4. Type or paste the 5 character string from MakeMyPassword into the terminal and add an underscore (`_`) to the end of it
5. Follow the prompts for entering the rest of your database configuration
6. Choose Y to initialize git
7. Choose n to install WordPress as a submodule
8. Choose n to use a custom directory structure
9. Choose Y to install a theme
10. The directory for the theme will be `socialbase`
10. Choose git as the theme type
11. The username: `SocialDesignHouse`
12. The repo name: `base-theme`
13. The branch: master
14. If everything looks okay choose Y
15. If asked about overwriting, just choose Y
16. cd into `wp-content/themes/socialbase/`
17. Run `npm install`
18. Run `bundle install`
19. Run `grunt watch` and start editing

Notes
===

When YeoPress installs the theme it will run the `setup` task in grunt. Right now this task uses `grunt-modernizr` to build a production ready version of Modernizr with the basics for you and downloads a few basic modules from bower and moves the distribution files to te correct diretories. You can customize the settings for these in `Gruntfile.js` and run `grunt modernizr` at any time to create a new customized version of Modernizr for your project.

The project is set up with grunt-notify so you can receive notifications when SASS, uglify, and/or cssmin are done running.

When you need to perform ajax calls that will need to be fast and may transfer a large amount of data, utilize the ajax-custom.php file as your AJAX URL instead of the admin-ajax.php file.
