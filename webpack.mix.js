const mix = require('laravel-mix');

mix.version();
mix.webpackConfig({
  devtool: 'source-map'
}).sourceMaps();
mix
  .js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css', {
  implementation: require('node-sass')
});
mix.copy('resources/index.html', 'public/index.html');
mix.copyDirectory('resources/images', 'public/images');
mix.browserSync('face.test');
