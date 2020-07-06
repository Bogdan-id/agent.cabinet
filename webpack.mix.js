const mix = require('laravel-mix')
const CKEditorWebpackPlugin = require( '@ckeditor/ckeditor5-dev-webpack-plugin' )
const CKEStyles = require('@ckeditor/ckeditor5-dev-utils').styles
const CKERegex = {
    svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
    css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
}
require('laravel-mix-polyfill')
const TargetsPlugin = require('targets-webpack-plugin')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
  // .polyfill({
  //   enabled: true,
  //   useBuiltIns: "usage",
  //   targets: {"ie": 11},
  //   // debug: true,
  //   corejs: 3,
  // })
  .webpackConfig({
    plugins: [
      new CKEditorWebpackPlugin({
        language: 'uk',
        addMainLanguageTranslationsToAllAssets: true
      }),
      new TargetsPlugin({
        browsers: ['last 2 versions', 'chrome >= 41', 'IE 11'],
      }),
    ],
    module: {
      rules: [
        // {
        //   test: /ckeditor5-[^\/\\]+[\/\\].+\.js$/,
        //   use: [
        //     {
        //       loader: 'babel-loader',
        //       options: Config.babel()
        //       // options: {
        //       //     presets: [ require( '@babel/preset-env' ) ]
        //       // }
        //     }
        //   ]
        // },
        {
          test: CKERegex.svg,
          use: [ 'raw-loader' ]
        },
        {
          test: CKERegex.css,
          use: [
            {
              loader: 'style-loader',
            },
            {
              loader: 'postcss-loader',
              options: CKEStyles.getPostCssConfig({
                themeImporter: {
                    themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
                },
                minify: true
              })
            },
          ]
        },
      ]
    }
  })

// eslint-disable-next-line
Mix.listen('configReady', webpackConfig => {
    const rules = webpackConfig.module.rules
    const targetSVG = /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/
    const targetCSS = /\.css$/

    for (let rule of rules) {
      if (rule.test.toString() === targetSVG.toString()) {
        rule.exclude = CKERegex.svg
      }
      else if (rule.test.toString() === targetCSS.toString()) {
        rule.exclude = CKERegex.css
      }
    }
})

