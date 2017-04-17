// Requirements
require('es6-promise').polyfill();

var gulp          = require('gulp');
var sass          = require('gulp-sass');
var autoprefixer  = require('gulp-autoprefixer');

// SASS task
gulp.task('sass', function(){
  return gulp.src('./sass/**/*.scss')
  .pipe(sass())
  .pipe(autoprefixer())
  .pipe(gulp.dest('./'))
});

gulp.task('watch', function() {
  gulp.watch('./sass/**/*.scss', ['sass']);
});

// default task
gulp.task('default', ['sass', 'watch']);
