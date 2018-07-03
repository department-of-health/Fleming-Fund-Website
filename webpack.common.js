const path = require('path');

const CleanWebpackPlugin = require('clean-webpack-plugin')

const CopyWebpackPlugin = require('copy-webpack-plugin')

const ExtractTextPlugin = require("extract-text-webpack-plugin");

const scriptEntry = path.join(__dirname, 'src/fleming-theme/templates/fleming.js');

module.exports = {
    entry: [
        path.join(__dirname, 'src/fleming-theme/templates/fleming.scss'),
        scriptEntry,
    ],
    output: {
        filename: 'dist/wordpress/wp-content/themes/fleming-theme/fleming-[chunkhash].js',
        libraryTarget: 'var',
        library: 'Fleming',
    },
    module: {
        rules: [
            { // sass / scss loader for webpack
                test: /\.(sass|scss)$/,
                loader: ExtractTextPlugin.extract(['css-loader', 'sass-loader'])
            },
            { // js loader for webpack
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    plugins: [
        new CleanWebpackPlugin(
            ['dist/wordpress/wp-content/themes/fleming-theme'],
            { watch: true } // This "watch" is not activated unless the global "watch" is also activated (i.e. only in webpack.dev.js)
        ),
        new ExtractTextPlugin({
            filename: 'dist/wordpress/wp-content/themes/fleming-theme/fleming-[contenthash].css',
            allChunks: true,
        }),
        new CopyWebpackPlugin([
                {
                    from: path.join(__dirname, "src/fleming-theme"),
                    to: path.join(__dirname, "./dist/wordpress/wp-content/themes/fleming-theme"),
                    ignore: [ '*.scss', '*.js' ]
                }
            ],
            { copyUnmodified: true }),
        new CopyWebpackPlugin([
                {
                    from: path.join(__dirname, "src/fleming-theme/static"),
                    to: path.join(__dirname, "./dist/wordpress/wp-content/themes/fleming-theme/static"),
                }
            ],
            { copyUnmodified: true }),
        new CopyWebpackPlugin([
            {
                from: path.join(__dirname, "src/wordpress"),
                to: path.join(__dirname, "./dist/wordpress/")
            }
        ],
        { copyUnmodified: true }),
        // new UglifyJsPlugin({
        //     include: /\.js$/,
        //     minimize: true
        // }),
    ],
}
