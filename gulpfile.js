var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

	mix.copy('node_modules/sweetalert/dist/sweetalert.min.js', 'resources/assets/js/libs')
     .copy('node_modules/sweetalert/dist/sweetalert.css', 'resources/assets/css/libs');

  mix.copy('node_modules/jsonlylightbox/js/lightbox.js', 'resources/assets/js/libs')
     .copy('node_modules/jsonlylightbox/css/lightbox.css', 'resources/assets/css/libs');

  mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts/bootstrap');
  mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'resources/assets/js/libs');

  mix.copy('node_modules/jquery/dist/jquery.min.js', 'resources/assets/js/libs');

    mix.sass('app.scss', 'public/css/app.css')
       .phpUnit()
       .styles([
	      'libs/sweetalert.css',
	      'libs/dropzone.css',
        //'libs/lity.css',
        'libs/lightbox.css'
	   ], 'public/css/libs.css')
       .scripts([
        'libs/jquery.min.js',
        'libs/bootstrap.min.js',
    	  'libs/sweetalert.min.js',
        'libs/dropzone.js',
        //'libs/lity.js',
        'libs/lightbox.js'
       ], 'public/js/libs.js');
});
