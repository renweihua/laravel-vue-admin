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

// svg dir
const svgDirPath  = path.resolve(__dirname, 'app/Modules/Admin/resources/' + vue_path + 'icons/svg')

Mix.listen('configReady', (webpackConfig) => {
    // Exclude 'svg' folder from font loader
    let fontLoaderConfig = webpackConfig.module.rules.find(rule => String(rule.test) === String(/(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/));
    fontLoaderConfig.exclude = [svgDirPath];
});

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'app/Modules/Admin/resources/' + vue_path),
        },
    },
    module: {
        rules: [
            {
                test: /\.svg$/,
                loader: 'svg-sprite-loader',
                include: [path.resolve(__dirname, 'app/Modules/Admin/resources/' + vue_path + 'icons/svg')],
                options: {
                    symbolId: 'icon-[name]'
                }
            }
        ],
    },
}).babelConfig({
    plugins: ['dynamic-import-node']
});

mix.js('app/Modules/Admin/resources/assets/js/app.js', 'public/js')
    .sass('app/Modules/Admin/resources/assets/sass/app.scss', 'public/css');

mix.js('app/Modules/Admin/resources/' + vue_path + '/main.js', 'public/js').extract(['vue', 'axios']);

if (mix.inProduction()) {
    mix.version();
}
