const path = require('path');
const gulp = require('gulp');
const livereload = require('gulp-livereload');
const less = require('gulp-less');
const cssmin = require('gulp-cssmin');
const rename = require('gulp-rename');
const imagemin = require('gulp-imagemin');

gulp.task('less', function() {
    return gulp.src('./styles/source/*.less')
        .pipe(less({
            paths: [path.join(__dirname, 'bower_components')]
        }))
        .pipe(gulp.dest('./styles'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./styles'))
        .pipe(livereload())
        ;
});

gulp.task('html', function() {
    return gulp.src('index.html')
        .pipe(livereload())
        ;
});

gulp.task('images', function () {
    gulp.src('images/src/*')
        .pipe(imagemin())
        .pipe(gulp.dest('images'))
        ;
});

gulp.task('watch', function () {
	livereload.listen();
    gulp.watch('./styles/source/*.less', ['less']);
    gulp.watch('index.html', ['html']);
});


gulp.task('default', ['watch']);