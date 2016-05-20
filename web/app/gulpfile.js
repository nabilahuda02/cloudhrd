// https://www.npmjs.com/package/gulp-include
// https://www.npmjs.com/package/gulp-less
// https://www.npmjs.com/package/gulp-cssnano
// https://www.npmjs.com/package/gulp-minify
// https://www.npmjs.com/package/gulp-changed
// https://www.npmjs.com/package/gulp-notify
// https://www.npmjs.com/package/gulp-watch
// https://www.npmjs.com/package/gulp-ng-annotate

var sources = {
    less: __dirname + '/app/assets/stylesheets/*.less',
    js: __dirname + '/app/assets/javascripts/*.js',
}

var destinations = {
    css: __dirname + '/public/assets/css',
    js: __dirname + '/public/assets/js',
}

var gulp       = require("gulp"),
    include    = require("gulp-include"),
    ngAnnotate = require('gulp-ng-annotate'),
    less       = require('gulp-less'),
    cssnano    = require('gulp-cssnano'),
    path       = require('path'),
    uglify     = require('gulp-uglify'),
    rename     = require('gulp-rename');


gulp.task('css', function(){
    gulp.src(sources.less)
        .pipe(include())
        .on('error', console.error)
        .pipe(less({
            paths: [__dirname + '/app/assets/stylesheets']
        }))
        .pipe(gulp.dest(destinations.css))
        .pipe(cssnano({discardUnused: false}))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destinations.css));
        ;
});

gulp.task('js', function(){
    gulp.src(sources.js)
        .pipe(include())
        .on('error', console.error)
        .pipe(gulp.dest(destinations.js))
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destinations.js));
        ;
});

gulp.task('watch', function() {
  gulp.watch(sources.js, ['js']);
  gulp.watch(sources.less, ['css']);
});

gulp.task('default', ['watch']);
