const mix = require('laravel-mix');
var path = require('path');

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

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).version();

mix.js('resources/js/affiliate.js', 'public/js/affiliate.js').vue()
    .postCss('resources/css/affiliate.css', 'public/css/affiliate.css', [
        //
    ]).version();

mix.minify('public/scripttag/test.js');

mix.webpackConfig({
    output: {
        chunkFilename: `js/[name].js?id=[chunkhash]`
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.runtime.esm.js',
            '@': path.resolve(__dirname, 'resources/js/src/'),
            'aff': path.resolve(__dirname, 'resources/js/affiliate/')
        }
    }
});