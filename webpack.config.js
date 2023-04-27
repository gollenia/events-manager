/**
 * WordPress Dependencies
 */

const TerserPlugin = require( 'terser-webpack-plugin' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		module: {
			...defaultConfig.module,
			rules: [
				...defaultConfig.module.rules,
				{
					test: /\.tsx?$/,
					use: [
						{
							loader: 'ts-loader',
							options: {
								configFile: 'tsconfig.json',
								transpileOnly: true,
							},
						},
					],
				},
			],
		},
		resolve: {
			extensions: [
				'.ts',
				'.tsx',
				...( defaultConfig.resolve ? defaultConfig.resolve.extensions || [ '.js', '.jsx' ] : [] ),
			],
		},
		watchOptions: {
			ignored: /node_modules/,
		},
		optimization: {
			minimizer: [
				new TerserPlugin( {
					terserOptions: {
						output: {
							comments: /translators:/i,
						},
						compress: {
							passes: 2,
							drop_console: true,
						},
						mangle: {
							reserved: [ '__', '_n', '_nx', '_x' ],
						},
					},
				} ),
			],
		},
	},
};
