const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');
const LiveReloadPlugin = require('webpack-livereload-plugin');

module.exports = (env, _argv) => {
  const config = env;

  return {
    mode: config.debug ? 'development' : 'production',
    devtool: 'source-map',
    target: 'web',
    entry: {
      app: path.resolve(__dirname, 'src/App/index.tsx'),
    },
    output: {
      filename: 'js/[name].js',
      chunkFilename: '[name].bundle.js',
      path: path.resolve(__dirname, 'webroot'),
      assetModuleFilename: './font/[name][ext]',
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: 'css/[name].css',
        chunkFilename: '[id].css',
      }),
      new HtmlWebpackPlugin({
        filename: path.resolve(__dirname, 'templates/layout/default.php'),
        template: path.resolve(
          __dirname,
          'templates/layout/default.ejs',
        ),
        minify: false,
        hash: true,
        inject: false,
      }),
      new CleanWebpackPlugin({
        cleanStaleWebpackAssets: true,
        cleanOnceBeforeBuildPatterns: ['**/css/*', '**/fonts/*', '**/js/*'],
        verbose: true,
      }),
      new LiveReloadPlugin({
        useSourceSize: true,
      }),
    ],
    resolve: {
      extensions: ['.scss', '.js', '.jsx', '.tsx', '.ts'],
      alias: {
        Api: path.resolve(__dirname, 'src/App/Api/'),
        Component: path.resolve(__dirname, 'src/App/Component/'),
        Config: path.resolve(__dirname, 'src/App/Config/'),
        Hook: path.resolve(__dirname, 'src/App/Hook/'),
        Interface: path.resolve(__dirname, 'src/App/Interface/'),
        Layout: path.resolve(__dirname, 'src/App/Layout/'),
        Policy: path.resolve(__dirname, 'src/App/Policy/'),
        Reducer: path.resolve(__dirname, 'src/App/Reducer/'),
        Screen: path.resolve(__dirname, 'src/App/Screen/'),
        Store: path.resolve(__dirname, 'src/App/Store/'),
        Type: path.resolve(__dirname, 'src/App/Type/'),
        Utility: path.resolve(__dirname, 'src/App/Utility/'),
      },
    },
    module: {
      rules: [
        {
          test: /\.(js|ts)x?$/i,
          exclude: /[\\/]node_modules[\\/]/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react',
                  '@babel/preset-typescript',
                ],
              },
            },
            { loader: 'source-map-loader', options: {} },
          ],
        },
        {
          test: /\.(sa|sc|c)ss?$/i,
          use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
        },
      ],
    },
    optimization: {
      runtimeChunk: 'single',
      splitChunks: {
        chunks: 'all',
        maxSize: 500000,
        hidePathInfo: true,
        cacheGroups: {
          bundle: {
            name: 'bundle',
            test: /[\\/]node_modules[\\/]/,
            priority: -10,
            reuseExistingChunk: true,
          },
          default: {
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
          },
        },
      },
    },
  };
};
