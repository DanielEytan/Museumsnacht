var webpack = require('webpack')

module.exports = {
  entry: [
    './src/js/main.js'
  ],
  output: {
    filename: "main.js"
  },
  watch: true,
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
  babel: {
    presets: ['es2015'],
    plugins: ['transform-runtime']
  },
  // /app/js/webpack.config.js
  plugins: [
      
      // Fixes warning in moment-with-locales.min.js 
      //   Module not found: Error: Can't resolve './locale' in ...
      new webpack.IgnorePlugin(/\.\/locale$/)
  ],
  resolve: {
    modulesDirectories: ['./src/js', 'node_modules'],
    alias: {
      'vue$': 'vue/dist/vue.common.js'
    }
  }
}
