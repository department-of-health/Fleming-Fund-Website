const path = require('path');

const CleanWebpackPlugin = require('clean-webpack-plugin')

const CopyWebpackPlugin = require('copy-webpack-plugin')

const ExtractTextPlugin = require("extract-text-webpack-plugin");


module.exports = {
    entry: [ path.join(__dirname, 'src/fleming-theme/templates/fleming.scss') ],
    output: {
        filename: 'dist/temp/bundle.js'
    },
    module: {
        rules: [
            { // sass / scss loader for webpack
                test: /\.(sass|scss)$/,
                loader: ExtractTextPlugin.extract(['css-loader', 'sass-loader'])
            }
        ]
    },
    plugins: [
        new CleanWebpackPlugin(
            ['dist/wordpress/wp-content/themes/fleming-theme'],
            { watch: true }
        ),
        new ExtractTextPlugin({
            filename: 'dist/wordpress/wp-content/themes/fleming-theme/fleming-[contenthash].css',
            allChunks: true,
        }),
        new CopyWebpackPlugin([
            {
                from: path.join(__dirname, "src/fleming-theme"),
                to: path.join(__dirname, "./dist/wordpress/wp-content/themes/fleming-theme"),
                ignore: [ '*.scss' ] 
            }
        ],
        { copyUnmodified: true }),
        new CopyWebpackPlugin([
            {
                from: path.join(__dirname, "src/wordpress"),
                to: path.join(__dirname, "./dist/wordpress/")
            }
        ],
        { copyUnmodified: true })
    ],
    watch: true
}

