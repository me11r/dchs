const path = require('path');

module.exports = {
    resolve: {
        extensions: ['.js', '.scss']
    },
    entry: {
        app: ['./resources/assets/js/app.js']
    },
    output: {
        path: path.resolve(path.join(__dirname, '..', '..', 'public', 'assets')),
        publicPath: '/assets/',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: "babel-loader",
                },
                exclude: /node_modules/
            },
        ]
    },
    plugins: []
};