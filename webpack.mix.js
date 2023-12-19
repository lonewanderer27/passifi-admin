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

mix.css('resources/css/app.css', 'public/css')
    .js('resources/js/app.js', 'public/js').react()
    .css('resources/css/dashboard.css', 'public/css')
    .js('resources/js/dashboard/index.js', 'public/js/dashboard')
    .css('resources/css/signup.css', 'public/css')
    .js('resources/js/signup/index.js', 'public/js/signup')
    .css('resources/css/statistics.css', 'public/css')
    .js('resources/js/statistics/index.js', 'public/js/statistics')
    .copyDirectory('resources/images', 'public/images')
    .options({
        hmrOptions: {
            host: 'localhost',
            port: '8079'
        },
    })
    .webpackConfig({
        devServer: {
            port: '8079'
        }
    })
