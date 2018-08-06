const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    devtool: 'source-map',
    resolve: {
        extensions: ['.js', '.scss', '.vue', '.json'],
        alias: {
            '@': 'resources/assets/js',
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    entry: {
        app: ['./resources/assets/js/app.js']
    },
    output: {
        path: path.resolve(path.join(__dirname, '..', '..', 'public', 'assets')),
        publicPath: '/assets/'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.js$/,
                use: {
                    loader: 'babel-loader'
                },
                exclude: file => (
                    /node_modules/.test(file) &&
                    !/\.vue\.js/.test(file)
                )
            },
            {
                test: /\.(png|jpe?g|gif)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        limit: 4096,
                        name: '[name].[hash:8].[ext]',
                        outputPath: 'images/'
                    }
                }

            },
            {
                test: /\.(woff2?|ttf|eot|svg)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        limit: 4096,
                        name: '[name].[hash:8].[ext]',
                        outputPath: 'fonts/'
                    }
                },
                exclude: /node_modules/
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin()
    ]
};
