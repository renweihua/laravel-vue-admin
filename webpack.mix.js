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

let vue_path = 'vue-element-admin';

mix.js('resources/js/app.js', 'public/' + vue_path)
    .postCss('resources/css/app.css', 'public/' + vue_path, [
        //
    ]);


mix.js('resources/' + vue_path + '/main.js', 'public/' + vue_path)

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/' + vue_path),
    },
  },
})