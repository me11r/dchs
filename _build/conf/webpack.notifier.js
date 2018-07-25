const path = require('path');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');

module.exports = {
    plugins: [
        new WebpackBuildNotifierPlugin({
            title: 'Webpack builder',
            logo: path.resolve('./img/favicon.png'),
            suppressSuccess: false
        })
    ]
};
