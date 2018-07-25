const merge = require('webpack-merge');
const webpack = require('webpack');
const chalk = require('chalk');
const rm = require('rimraf');
const path = require('path');
const args = require('yargs').argv;

const confType = args.config || 'app';
const watch = args.watch || false;

const baseWebpack = require('./conf/webpack.base.' + confType);
const prodWebpack = require('./conf/webpack.prod.' + confType);
const webpackNotifier = require('./conf/webpack.notifier');

let webpackConfig = merge(baseWebpack, prodWebpack, {watch: watch}, webpackNotifier);

rm(path.join(__dirname, '..', 'public', 'assets', '**/*'), err => {
    if (err) throw  err;
    webpack(webpackConfig, (err, stats) => {
        if (err) throw err;
        process.stdout.write(stats.toString({
            colors: true,
            modules: false,
            children: false,
            chunks: false,
            chunkModules: false
        }) + '\n\n');

        if (stats.hasErrors()) {
            console.log(chalk.red('  Build failed with errors.\n'));
            process.exit(1);
        }

        console.log(chalk.cyan('  Build complete.\n'));
        console.log(chalk.yellow(
            '  Tip: built files are meant to be served over an HTTP server.\n' +
            '  Opening index.html over file:// won\'t work.\n'
        ))
    });
});
