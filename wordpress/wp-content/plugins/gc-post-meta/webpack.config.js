const TerserPlugin = require('terser-webpack-plugin');
const I18nLoaderWebpackPlugin = require('@automattic/i18n-loader-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
module.exports = {
    mode: "production",
    entry: './resources/js/sidebar.js',
    output: {
        path: __dirname + "/resources/js/build/",
        filename: 'sidebar.js',
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {}
                }
            }
        ]
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    parse: {
                        ecma: 8
                    },
                    compress: {
                        ecma: 5,
                        warnings: false,
                        comparisons: false,
                        inline: 2
                    },
                    mangle: {
                        safari10: true,
                        reserved: [
                            '__',
                            '_n',
                            '_nx',
                            '_x'
                        ]
                    },
                    keep_classnames: false,
                    keep_fnames: false,
                    output: {
                        ecma: 5,
                        comments: false,
                        ascii_only: true
                    }
                }
            })
        ]
    },
    plugins: [
        new I18nLoaderWebpackPlugin({
            textdomain: 'gc-post-meta'
        }),
        new DependencyExtractionWebpackPlugin(),
    ],
};