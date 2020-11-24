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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/partials/profile.js', 'public/js/partials')
    .js('resources/js/partials/autocomplete.js', 'public/js/partials')
    .js('resources/js/partials/create.js', 'public/js/partials')
    .js('resources/js/partials/map.js', 'public/js/partials')
    .js('resources/js/partials/search.js', 'public/js/partials')
    .js('resources/js/partials/chart.js', 'public/js/partials')
    .js('resources/js/partials/header.js', 'public/js/partials')
    .js('resources/js/partials/sponsorship.js', 'public/js/partials')
    .js('resources/js/partials/messages.js', 'public/js/partials')
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('resources/img', 'public/img');
    
