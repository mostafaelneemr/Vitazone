const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.scripts([
    'public/frontend/js/vendor/jquery.min.js',
    'public/frontend/js/vendor/popper.min.js',
    'public/frontend/js/vendor/bootstrap.min.js',
    'public/frontend/js/jquery.countdown.min.js',
   'public/frontend/js/select2.min.js',
   'public/frontend/js/nouislider.min.js',
   'public/frontend/js/sweetalert2.min.js',
   'public/frontend/js/slick.min.js',
   'public/frontend/js/jssocials.min.js',
   'public/frontend/js/bootstrap-tagsinput.min.js',
   'public/frontend/js/jodit.min.js',
   'public/frontend/js/xzoom.min.js',
   'public/frontend/js/fb-script.js',
   'public/frontend/js/lazysizes.min.js',
   'public/frontend/js/intlTelInput.min.js',
   'public/frontend/js/active-shop.js',
   'public/frontend/js/main.js',
], 'public/js/all.js');


 mix.styles([
    'public/frontend/css/active-shop.css',
    'public/frontend/css/bootstrap.min.css',
    'public/frontend/css/bootstrap.tagsinput.css',
    'public/frontend/css/custom-style.css',
    'public/frontend/css/fb-style.css',
    'public/frontend/css/intlTelInput.min.css',
    'public/frontend/css/jodit.min.css',
    'public/frontend/css/jquery.share.css',
    'public/frontend/css/jssocials-theme-flat.css',
    'public/frontend/css/jssocials.css',
    'public/frontend/css/main.css',
    'public/frontend/css/slick.css',
    'public/frontend/css/sweetalert2.min.css',
    'public/frontend/css/xzoom.css',
    'public/frontend/css/colors/1.css',
    'public/frontend/css/colors/2.css',
    'public/frontend/css/colors/3.css',
    'public/frontend/css/colors/4.css',
    'public/frontend/css/colors/5.css',
    'public/frontend/css/colors/6.css',
    'public/frontend/css/colors/7.css',
    'public/frontend/css/colors/default.css',
    ], 'public/css/all.css');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
