let mix = require('laravel-mix');

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

mix.disableSuccessNotifications();

mix.js('resources/assets/js/app.js', 'public/js')
    .copy('node_modules/autosize/dist/autosize.min.js', 'public/js')
    .copy('node_modules/clipboard/dist/clipboard.min.js', 'public/js')
    .copy('node_modules/bootstrap-maxlength/bootstrap-maxlength.min.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');
