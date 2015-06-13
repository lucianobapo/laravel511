var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //mix.less('app.less');

    mix.less('app.less', 'resources/css');
    //mix.sass('app.scss', 'resources/css');

    mix.styles([
        'app.css',
        //'libs/select2.min.css'
    ], 'public/css/app.compiled.css');

    mix.scripts([
        'libs/jquery.min.js',
        'libs/bootstrap.min.js',
        //'libs/select2.min.js'
    ], 'public/js/app.compiled.js');

    mix.version(['public/css/app.compiled.css','public/js/app.compiled.js']);
    //mix.version('public/js/app.compiled.js');

    //mix.phpUnit().phpSpec();
});
