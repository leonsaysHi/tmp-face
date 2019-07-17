const mix = require('laravel-mix');

if(mix.inProduction()) {
  mix.babel([
    'resources/js/vendor/*.js',
    'resources/js/security/*.js'
  ], 'public/js/security.js');
  mix.version();
  mix
    .js('resources/js/app.js', 'public/js')
    .babel('public/js/app.js', 'public/js/app.js');
} else {
  mix.scripts([
    'resources/js/vendor/*.js',
    'resources/js/security/*.js'
  ], 'public/js/security.js');
  mix.version();
  mix.webpackConfig({
    devtool: 'source-map'
  }).sourceMaps();
  mix
    .js('resources/js/app.js', 'public/js');
}

mix.sass('resources/sass/app.scss', 'public/css', {
  implementation: require('node-sass')
});

mix.copyDirectory('resources/images', 'public/images');
