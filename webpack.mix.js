const mix = require('laravel-mix')
require('mix-tailwindcss')

mix.js('resources/js/app.js', 'js')
  .vue()
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