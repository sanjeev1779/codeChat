var gulp = require('gulp');
var sass = require('gulp-sass');
var clean = require('gulp-clean');
var browserSync = require('browser-sync').create();

var paths = {
        'scss' : ['scss/*.scss']
};
var dist = {
        'scss': 'css'
};

gulp.task('clean', function() {
    gulp.src(dist.scss)
        .pipe(clean({force: true}));
});

gulp.task('serve', function() {
    browserSync.init({
        server: "./",
        notify: false
    });
    gulp.watch(['scss/*.scss'], ['clean', 'sass']);
    gulp.watch(["*.html", "js/*.js"]).on('change', browserSync.reload);
});

gulp.task('sass', function() {
    gulp.src(paths.scss)
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest(dist.scss))
    .pipe(browserSync.stream());
});

gulp.task('watch', ['serve']);
