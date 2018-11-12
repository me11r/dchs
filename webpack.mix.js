let mix = require('laravel-mix');

mix
    .sass('resources/assets/sass/app.scss', 'public/assets/app.css')
    .js('resources/assets/js/app.js', 'public/assets/app.js')
    .copy('resources/static', 'public/assets');

mix.webpackConfig({
    resolve: {
        unsafeCache: true
    },
    module: {
        rules: [{
            test: /\.js?$/,
            use: [{
                loader: 'babel-loader',
                options: mix.config.babel()
            }]
        }]
    }
});

if (mix.inProduction()) {
    mix.version();
    mix.options({
        uglify: {
            parallel: true,
            cache: true,
            uglifyOptions: {
                compress: true,
                comments: true,
                mangle: true,
                drop_console: true
            }
        },
        processCssUrls: false,
        extractVueStyles: true
    });
} else {
    mix.options({
        uglify: {
            sourceMap: true,
            parallel: true,
            cache: true,
            uglifyOptions: {
                compress: false,
                comments: false,
                mangle: true,
                cache: true
            }
        },
        processCssUrls: false,
        extractVueStyles: true
    });
}
