const mix = require('laravel-mix')
require('mix-tailwindcss')

mix.js('resources/js/app.js', 'js')
  .vue()
  .postCss('resources/css/app.css', 'public/css')
  .tailwind()
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