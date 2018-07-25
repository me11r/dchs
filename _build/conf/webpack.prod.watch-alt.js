const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CssMiniPlugin = require('mini-css-extract-plugin');
const webpackProd = require('./webpack.prod.app');
const path = require('path');

module.exports = {
    ...webpackProd,
    plugins: [
        new UglifyJsPlugin({
            cache: path.resolve(path.join(__dirname, 'cache')),
            parallel: true,
            extractComments: false,
            sourceMap: true,
            uglifyOptions: {
                compress: false,
                comments: false,
                mangle: false,
                warnings: false
            }
        }),
        new CopyWebpackPlugin([
            {
                from: path.resolve(path.join(__dirname, '..', '..', 'resources', 'static')),
                to: '',
                ignore: ['.*']
            }
        ]),
        new CssMiniPlugin({
            filename: '[name].[contenthash:7].min.css'
        }),
        new OptimizeCssAssetsPlugin({
            assetNameRegExp: /\.min\.css$/g,
            cssProcessorOptions: {safe: true, discardComments: {removeAll: true}}
        }),
        new ManifestPlugin({
            basePath: '/assets/'
        })
    ],
    performance: {
        hints: false
    }
};
