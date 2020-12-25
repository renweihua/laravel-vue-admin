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

mix.setPublicPath('public/' + vue_path);

mix.js('app/Modules/Admin/Resources/assets/js/app.js', 'public/' + vue_path + '/js/admin.js')
    .sass('app/Modules/Admin/Resources/assets/sass/app.scss', 'public/' + vue_path + '/css/admin.css');

mix.js('app/Modules/Admin/Resources/' + vue_path + '/main.js','public/' +  vue_path + '/js/main.js');

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'app/Modules/Admin/resources/' + vue_path),
        },
    }
});
