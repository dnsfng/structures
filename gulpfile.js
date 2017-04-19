// Requirements
require('es6-promise').polyfill();

var gulp          = require('gulp');
var sass          = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer  = require('gulp-autoprefixer');
var plumber       = require('gulp-plumber');
var gutil         = require('gulp-util');
var browserSync   = require('browser-sync').create();
var reload        = browserSync.reload;

var onError = function (err) {
  console.log('An error occurred:', gutil.colors.magenta(err.message));
  gutil.beep();
  this.emit('end');
};

// SASS task
gulp.task('sass', function(){
  return gulp
  .src('./sass/**/*.scss')
  .pipe(sourcemaps.init())
  .pipe(sass())
  .pipe(plumber({ errorHandler: onError }))
  .pipe(sourcemaps.write())
  .pipe(autoprefixer())
  .pipe(gulp.dest('./'))
});

gulp.task('watch', function() {
  browserSync.init({
    files: ['./**/*.php'],
    proxy: 'http://localhost:8888/wordpress/',
  });
  gulp.watch('./sass/**/*.scss', ['sass', reload]);
});

// default task
gulp.task('default', ['sass', 'watch']);
