const merge = require('webpack-merge');
const webpack = require('webpack');
const serve = require('webpack-serve');
const path = require('path');
const args = require('yargs').argv;
const proxy = require('http-proxy-middleware');

const confType = args.config || 'app';

const baseConfig = require('./conf/webpack.base.' + confType);
const devConfig = require('./conf/webpack.dev.' + confType);

const serveConfig = {
    serve: {
        stats: 'minimal',
        host: 'localhost',
        port: 8099,
        clipboard: false,
        content: [path.resolve(path.join(__dirname, '..', 'public'))],
        hot: {
            port: 9099
        }
    }
};

const webpackConfig = merge(baseConfig, devConfig);
const config = merge(webpackConfig, serveConfig);

serve({config});
