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

    mix.sass('app.scss', 'public/css/app.css')
       .styles([
	      'libs/sweetalert.css',
	   ], 'public/css/libs.css')
       .scripts([
    	  'libs/sweetalert.min.js'
       ], 'public/js/libs.js');
});
