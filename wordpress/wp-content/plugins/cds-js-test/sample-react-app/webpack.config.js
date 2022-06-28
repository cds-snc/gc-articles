const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const I18nLoaderWebpackPlugin = require("@automattic/i18n-loader-webpack-plugin");
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

module.exports = {
  externals: {
    "react": "React",
    "react-dom": "ReactDOM",
  },
  optimization: {
    minimize: false,
    concatenateModules: false,
    
  },
  output: {
    path: path.join(__dirname, "/dist"), // the bundle output path
    filename: "bundle.js", // the name of the bundle
    publicPath: 'auto',
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: "src/index.html", // to import index.html file inside index.js
    }),
    new I18nLoaderWebpackPlugin({
      textdomain: 'cds-js-test',
    }),
    new DependencyExtractionWebpackPlugin() 
  ],
  devServer: {
    port: 3030, // you can change the port
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/, // .js and .jsx files
        exclude: /node_modules/, // excluding the node_modules folder
        use: {
          loader: "babel-loader",
        },
      }
    ],
  },
};