var elixir = require('laravel-elixir');

require('elixir-coffeeify');
require('laravel-elixir-vueify');

elixir(function(mix) {
    mix.sass('app.scss');
    mix.sass('front.scss');
    mix.sass('admin.scss');
    mix.coffeeify('app.coffee');
    mix.browserSync({
        proxy: 'camp.dev'
    });
});
