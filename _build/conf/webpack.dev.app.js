module.exports = {
    mode: "development",
    module: {
        rules: [{
            test: /\.scss$/,
            use:
                [
                    {
                        loader: "style-loader",
                    },
                    {
                        loader: "css-loader",
                    },
                    {
                        loader: "sass-loader"
                    }
                ],
            exclude: /node_modules/
        },
        ]
    },
    plugins: []
}
;