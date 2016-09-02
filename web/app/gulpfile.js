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
    rename     = require('gulp-rename')
    changed    = require('gulp-changed'),
    livereload = require('gulp-livereload'),
    plumber    = require('gulp-plumber');


gulp.task('css', function(){
    return gulp.src(sources.less)
        .pipe(plumber())
        .pipe(changed(destinations.css))
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
        .pipe(gulp.dest(destinations.css))
        .pipe(livereload())
        ;
});

gulp.task('js', function(){
    return gulp.src(sources.js)
        .pipe(plumber())
        .pipe(ngAnnotate())
        .pipe(changed(destinations.js))
        .pipe(include())
        .on('error', console.error)
        .pipe(gulp.dest(destinations.js))
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destinations.js))
        .pipe(livereload())
        ;
});

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(__dirname + '/app/assets/javascripts/**/*.js', ['js']);
    gulp.watch(__dirname + '/app/assets/stylesheets/*.less', ['css']);
});

gulp.task('default', ['watch']);
