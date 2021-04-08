const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'js')
  .vue()
  .postCss("resources/css/app.css", "css", [
    require("tailwindcss")
  ])
  .options({
    terser: {
      extractComments: (astNode, comment) => false,
      terserOptions: {
        format: {
          comments: false
        }
      }
    }
  })