const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();
mix.browserSync("127.0.0.1:8000");

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/styles')

mix.sass('resources/components/sidebar/sidebar.scss', 'public/components/sidebar')
    .js('resources/components/sidebar/sidebar.js', 'public/components/sidebar')
