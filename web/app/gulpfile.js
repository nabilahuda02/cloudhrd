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
        .pipe(cssnano())
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


 
// gulp.task("scripts", function() {
//   console.log("-- gulp is running task 'scripts'");
 
//   gulp.src("src/js/main.js")
//     .pipe(include())
//       .on('error', console.log)
//     .pipe(gulp.dest("dist/js"));
// });
 
// gulp.task("default", ["scripts"]);

 
// gulp.task('default', function () {
//     return gulp.src('src/app.js')
//         .pipe(ngAnnotate())
//         .pipe(gulp.dest('dist'));
// });

// var ;
// var;
 
// gulp.task('less', function () {
//   return gulp.src('./less/**/*.less')
//     .pipe(less({
//       paths: [ path.join(__dirname, 'less', 'includes') ]
//     }))
//     .pipe(gulp.dest('./public/css'));
// });

// var ;
 
// gulp.task('compress', function() {
//   gulp.src('lib/*.js')
//     .pipe(minify({
//         exclude: ['tasks'],
//         ignoreFiles: ['.combo.js', '-min.js']
//     }))
//     .pipe(gulp.dest('dist'))
// });

// var gulp = require('gulp');
// var ;
// var ngAnnotate = require('gulp-ng-annotate'); // just as an example 
 
// var SRC = 'src/*.js';
// var DEST = 'dist';
 
// gulp.task('default', function () {
//     return gulp.src(SRC)
//         .pipe(changed(DEST))
//         // ngAnnotate will only get the files that 
//         // changed since the last time it was run 
//         .pipe(ngAnnotate())
//         .pipe(gulp.dest(DEST));
// });

// var ;
 
// gulp.task('compress', function() {
//   gulp.src('lib/*.js')
//     .pipe(minify({
//         exclude: ['tasks'],
//         ignoreFiles: ['.combo.js', '-min.js']
//     }))
//     .pipe(gulp.dest('dist'))
// });

// var gulp = require('gulp'),
//     ;
 
// gulp.task('stream', function () {
//     return gulp.src('css/**/*.css')
//         .pipe(watch('css/**/*.css'))
//         .pipe(gulp.dest('build'));
// });
 
// gulp.task('callback', function (cb) {
//     watch('css/**/*.css', function () {
//         gulp.src('css/**/*.css')
//             .pipe(watch('css/**/*.css'))
//             .on('end', cb);
//     });
// });