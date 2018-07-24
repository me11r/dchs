const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const webpackProd = require('./webpack.prod.app');

module.exports = {
    mode: 'dev',
    plugins: [
        new UglifyJsPlugin({
            cache: true,
            parallel: true,
            extractComments: false,
            sourceMap: false,
            uglifyOptions: {
                compress: false,
                comments: false,
                mangle: false,
                warnings: false
            }
        })
    ],
    performance: {
        hints: false
    },
    ...webpackProd
};
