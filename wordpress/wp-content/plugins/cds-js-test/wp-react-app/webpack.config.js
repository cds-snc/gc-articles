const path = require("path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
	...defaultConfig,
	externals: {
		"react": "React",
		"react-dom": "ReactDOM"
	},
	entry: "./src/index.js",
	output: {
		...defaultConfig.output,
		filename: "index.js",
		path: path.resolve(__dirname, "build"),
	}
};
