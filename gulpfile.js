// Requirements
require('es6-promise').polyfill();

var gulp          = require('gulp');
var sass          = require('gulp-sass');
var autoprefixer  = require('gulp-autoprefixer');
var plumber       = require('gulp-plumber');
var gutil         = require('gulp-util');

var onError = function (err) {
  console.log('An error occurred:', gutil.colors.magenta(err.message));
  gutil.beep();
  this.emit('end');
};

// SASS task
gulp.task('sass', function(){
  return gulp.src('./sass/**/*.scss')
  .pipe(sass())
  .pipe(autoprefixer())
  .pipe(plumber({ errorHandler: onError }))
  .pipe(gulp.dest('./'))
});

gulp.task('watch', function() {
  gulp.watch('./sass/**/*.scss', ['sass']);
});

// default task
gulp.task('default', ['sass', 'watch']);
