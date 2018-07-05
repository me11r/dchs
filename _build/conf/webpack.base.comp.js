const path = require('path');
const HardcodeSourcesPlugin = require('hard-source-webpack-plugin');

module.exports = {
    resolve: {
        extensions: ['.js', '.ts']
    },
    entry: {
        app: ['./src/index.ts']
    },
    output: {
        path: path.resolve(path.join(__dirname, '..', '..', 'public')),
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: "awesome-typescript-loader",
                    }
                ]
            }
        ]
    },
    plugins: [
        new HardcodeSourcesPlugin(),
    ]
};