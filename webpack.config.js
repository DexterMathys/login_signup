const path = require('path');

// Webpack Plugins
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
	entry: {
		base: './public/assets/js/base.jsx',
		login: './public/assets/js/login.jsx',
		index: './public/assets/js/index.jsx',
		passwordRecovery: './public/assets/js/passwordRecovery.jsx',
	},
	output: {
		// Save compiled files into build folder with the entry name
		path: path.resolve(__dirname, 'public/build'),
		filename: '[name].js'
	},
	// Plugins
	plugins: [
		new CleanWebpackPlugin(),
		new MiniCssExtractPlugin()
	],
	module: {
		rules: [
			// Rules for MiniCssExtractPlugin
            {
				test: /\.css$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							// you can specify a publicPath here
							// by default it use publicPath in webpackOptions.output
							publicPath: '../assets/'
						}
					},
					'css-loader'
				]
			},
            {
                test: /\.(woff|woff2|ttf|eot|svg|jpg)($|\?)/,
                use: "url-loader"
            },
			// Rules for React
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader'
				}
			},
		]
	}
};
