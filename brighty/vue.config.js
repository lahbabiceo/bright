const { defineConfig } = require('@vue/cli-service')
const CopyPlugin = require("copy-webpack-plugin");

module.exports = defineConfig({
  transpileDependencies: [
    'vuetify'
  ],
  outputDir: './public/',
  configureWebpack: {
    plugins: [
      new CopyPlugin({
        patterns: [
          { from: "./index.php", to: "./index.php" },
          { from: "./.htaccess", to: "./" }
          
        ],
      }),
    ]
  }
  
})
