const path = require("path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
	...defaultConfig,
	externals: {
		"react": "React",
		"react-dom": "ReactDOM"
	},
	entry: "./src/index.tsx",
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.tsx?$/,
				use: "ts-loader",
				exclude: /node_modules/,
			},	
		],
	},

	resolve: {
		...defaultConfig.resolve,
		extensions: [".tsx", ".ts", "js", "jsx"],
		alias: {
			util: path.resolve(__dirname, "src/util/")
		}
	},

	output: {
		...defaultConfig.output,
		filename: "index.js",
		path: path.resolve(__dirname, "build"),
	}
};
