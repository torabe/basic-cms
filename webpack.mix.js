const mix = require('laravel-mix');

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

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

mix
  .browserSync({
    proxy: 'localhost:8000',
    open: true,
  })
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .js('resources/js/admin/app.js', 'js/admin')
  // .sass('resources/sass/app.scss', 'css')
  .options({
    autoprefixer: {
      options: {
        grid: 'autoplace',
        overrideBrowserslist: [
          'last 5 version',
          'ie >= 9',
          'iOS >= 8.1',
          'Android >= 4.4',
          '>= 2%',
          'Firefox >= 20',
          'safari >= 8',
        ],
      },
    },
    postCss: [require('autoprefixer')()],
  })
  .sourceMaps(!mix.inProduction())
  .webpackConfig({
    devtool: mix.inProduction() ? 'none' : 'source-map',
    plugins: [new VuetifyLoaderPlugin()],
  })
  .version();
