const CopyWebpackPlugin = require('copy-webpack-plugin');
const CssMiniPlugin = require('mini-css-extract-plugin');
const path = require('path');
const BundleAnalyzerWebpackPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
    mode: 'production',
    devtool: 'source-map',
    output: {
        filename: "[name].[contenthash:7].min.js"
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendors: {
                    name: 'vendor',
                    test: /[\\\/]node_modules[\\\/]/,
                    priority: -10,
                    chunks: 'initial'
                },
                common: {
                    name: 'common',
                    minChunks: 2,
                    priority: -20,
                    chunks: 'initial',
                    reuseExistingChunk: true
                }
            }
        }
    },
    module: {
        noParse: /^(vue|vue-router|vuex|vuex-router-sync)$/,
        rules: [
            {
                test: /\.scss$/,
                use:
                    [
                        CssMiniPlugin.loader,
                        {
                            loader: "css-loader",
                            options: {
                                importLoaders: 1,
                            }
                        },
                        "postcss-loader",
                        "sass-loader"
                    ],
                exclude: /node_modules/
            },
            {
                test: /\.css$/,
                use:
                    [
                        CssMiniPlugin.loader,
                        {
                            loader: "css-loader",
                            options: {
                                importLoaders: 1
                            }
                        },
                        "postcss-loader",

                    ],
                exclude: /node_modules/
            },

        ]
    },
    plugins: [
        new CopyWebpackPlugin([
            {
                from: path.resolve(path.join(__dirname, '..', '..', 'resources', 'static')),
                to: '',
                ignore: ['.*'],
            }
        ]),
        new UglifyJsPlugin({
            cache: true,
            parallel: true,
            extractComments: true,
            sourceMap: true
        }),
        new CssMiniPlugin({
            filename: "[name].[contenthash:7].min.css"
        }),
        new OptimizeCssAssetsPlugin({
            assetNameRegExp: /\.min\.css$/g,
            cssProcessorOptions: {safe: true, discardComments: {removeAll: true}},
        }),
        new ManifestPlugin({
            basePath: 'assets/'
        })
    ]
};