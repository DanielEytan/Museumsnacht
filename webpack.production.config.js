var webpack = require('webpack')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
  entry: [
    './src/js/main.js'
  ],
  output: {
    filename: "main.js"
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        // excluding some local linked packages.
        // for normal use cases only node_modules is needed.
        exclude: /node_modules|vue\/src|vue-router\//,
        loader: 'babel-loader'
      },
      {
        test: /\.scss$/,
        loaders: ['style', 'css', 'sass']
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      }
    ]
  },
  // /app/js/webpack.config.js
  plugins: [
      
    // Fixes warning in moment-with-locales.min.js 
    //   Module not found: Error: Can't resolve './locale' in ...
    new webpack.IgnorePlugin(/\.\/locale$/),

    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('production')
    }),
    // minify with dead-code elimination
    new webpack.optimize.UglifyJsPlugin()
  ],
  resolve: {
    modulesDirectories: ['./src/js', 'node_modules'],
    alias: {
      'vue$': 'vue/dist/vue.min.js'
    }
  }
}
