/**
 * WordPress Dependencies
 */

const TerserPlugin = require('terser-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

module.exports = {
	...defaultConfig,
	...{
		module: {
			...defaultConfig.module,
			rules: [
				{
					test: /\.tsx?$/,
					use: "ts-loader",
					exclude: /node_modules/,
				},
				...defaultConfig.module.rules,
			],
		},
		optimization: {
			minimizer: [
				new TerserPlugin({
					terserOptions: {
						output: {
							comments: /translators:/i,
						},
						compress: {
							passes: 2,
							drop_console: true,
						},
						mangle: {
							reserved: ['__', '_n', '_nx', '_x'],
						},
					},
				}),
			],
		},
	},
};
