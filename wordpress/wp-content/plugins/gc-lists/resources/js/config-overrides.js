// https://developer.wordpress.com/author/a8cuser/

const TerserPlugin = require("terser-webpack-plugin");
const I18nLoaderWebpackPlugin = require("@automattic/i18n-loader-webpack-plugin");
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');


module.exports = {
  webpack: function (config, env) {
    //
    const isEnvProduction = true;
    const isEnvProductionProfile = true;

    config.optimization = {
      minimize: isEnvProduction,
      minimizer: [
        // This is only used in production mode
        new TerserPlugin({
          terserOptions: {
            parse: {
              // We want terser to parse ecma 8 code. However, we don't want it
              // to apply any minification steps that turns valid ecma 5 code
              // into invalid ecma 5 code. This is why the 'compress' and 'output'
              // sections only apply transformations that are ecma 5 safe
              // https://github.com/facebook/create-react-app/pull/4234
              ecma: 8,
            },
            compress: {
              ecma: 5,
              warnings: false,
              // Disabled because of an issue with Uglify breaking seemingly valid code:
              // https://github.com/facebook/create-react-app/issues/2376
              // Pending further investigation:
              // https://github.com/mishoo/UglifyJS2/issues/2011
              comparisons: false,
              // Disabled because of an issue with Terser breaking valid code:
              // https://github.com/facebook/create-react-app/issues/5250
              // Pending further investigation:
              // https://github.com/terser-js/terser/issues/120
              inline: 2,
            },
            mangle: {
              safari10: true,
              reserved: [
                "__",
                "_n",
                "_nx",
                "_x"
              ]
            },
            // Added for profiling in devtools
            keep_classnames: isEnvProductionProfile,
            keep_fnames: isEnvProductionProfile,
            output: {
              ecma: 5,
              comments: false,
              // Turned on because emoji and regex is not minified properly using default
              // https://github.com/facebook/create-react-app/issues/2488
              ascii_only: true,
            },
          },
        }),
      ],
    };

    config.plugins.push(new I18nLoaderWebpackPlugin({
      textdomain: 'gc-lists',
    }));

    config.plugins.push(new DependencyExtractionWebpackPlugin());



    return config;
  },
};