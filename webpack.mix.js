const mix = require('laravel-mix');

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
    .js('resources/js/delete-modal.js', 'public/js')
    .js('resources/js/add-work-times-modal', 'public/js')
    .js('resources/js/work-times/add-modal.js', 'public/js/work-time')
    .js('resources/js/work-times/edit-modal.js', 'public/js/work-time')
    .js('resources/js/calendar/add-modal.js', 'public/js/calendar')
    .js('resources/js/calendar/show-modal.js', 'public/js/calendar')
    .js('resources/js/calendar/edit-modal.js', 'public/js/calendar')
    .js('resources/js/project-element-component-versions/tasks/add-modal.js', 'public/js/tasks')
    .js('resources/js/project-element-component-versions/tasks/edit-modal.js', 'public/js/tasks')
    .js('resources/js/project-element-component-versions/upload-video.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/font-awasome.scss', 'public/css')
    .vue()
    .sourceMaps();
