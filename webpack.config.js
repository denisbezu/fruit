const path = require('path');
const webpack = require('webpack');
const FixStyleOnlyEntriesPlugin = require('webpack-fix-style-only-entries');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const getConfig = (env, argv) => {
  const minimizers = [];
  const plugins = [
    new FixStyleOnlyEntriesPlugin(),
    new MiniCssExtractPlugin({
      filename: `[name].css`,
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
  ];

  return {
    entry: {
      'js/admin/fruits/grid': './views/_dev/js/admin/fruits/grid.js',
    },

    output: {
      filename: `[name].js`,
      path: path.resolve(__dirname, './views/')
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env'],
              },
            },
          ],
        },
        {
          test: /\.(s)?css$/,
          use: [
            {loader: MiniCssExtractPlugin.loader},
            {loader: 'css-loader'},
            {loader: 'postcss-loader'},
            {loader: 'sass-loader'},
          ],
        }
      ],
    },

    externals: {
      $: '$',
      jquery: 'jQuery',
    },

    plugins,

    optimization: {
      minimizer: minimizers
    },
    devtool: argv.mode === 'development' ? 'eval' : 'cheap-source-map',
    resolve: {
      extensions: ['.js', '.scss', '.css', '.json'],
      alias: {
        '~': path.resolve(__dirname, './node_modules'),
        '@app': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js/app'),
        '@components': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js/components'),
      },
    },
    stats: {
      children: false,
    },
  };
};

module.exports = (env, argv) => {
  const config = getConfig(env, argv);
  // Production specific settings
  if (argv.mode === 'production') {
    const terserPlugin = new TerserPlugin({
      cache: true,
      sourceMap: true,
      extractComments: /^\**!|@preserve|@license|@cc_on/i, // Remove comments except those containing @preserve|@license|@cc_on
      parallel: true,
      terserOptions: {
        drop_console: true,
      },
    });

    config.optimization.minimizer.push(terserPlugin);
  }

  return config;
};
