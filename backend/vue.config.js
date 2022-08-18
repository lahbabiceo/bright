const { defineConfig } = require('@vue/cli-service')
const CopyPlugin = require("copy-webpack-plugin");

module.exports = defineConfig({
  transpileDependencies: [
    'vuetify'
  ],
  outputDir: './public/',
  configureWebpack: {
   //Change only this line
    plugins: [
      new CopyPlugin({
        patterns: [
          { from: "./index.php", to: "./index.php" },
          { from: "./.htaccess", to: "./" },
          { from: "./storefront/", to: "./" }
          
        ],
      }),
    ]
  },
  chainWebpack: (config) => {
    if (process.env.NODE_ENV === 'production') {
        config.plugin('html').tap((opts) => {
            opts[0].filename = './dashboard.html';
            return opts;
        });
    }
 }
  
})
