const path = require('path');
const defaults = require("@wordpress/scripts/config/webpack.config");

module.exports = {
    ...defaults,
    externals: {
        "react": "React",
        "react-dom": "ReactDOM"
    },
    resolve: {
        alias: {
            Spinner: path.resolve(__dirname, 'classes/Modules/Spinner/src'),
            Notify: path.resolve(__dirname, 'classes/Modules/Notify/src'),
            Blocks: path.resolve(__dirname, 'classes/Modules/Blocks/src'),
            TrackLogins: path.resolve(__dirname, 'classes/Modules/TrackLogins/src'),
        }
    }

};