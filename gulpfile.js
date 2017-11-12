// Requirements
require('es6-promise').polyfill();

var gulp          = require('gulp');
var sass          = require('gulp-sass');
var sourcemaps    = require('gulp-sourcemaps');
var autoprefixer  = require('gulp-autoprefixer');
var plumber       = require('gulp-plumber');
var gutil         = require('gulp-util');
var browserSync   = require('browser-sync').create();
var reload        = browserSync.reload;
var newer         = require('gulp-newer');
var rename        = require('gulp-rename');
var cleanCSS      = require('gulp-clean-css');
var htmlreplace   = require('gulp-html-replace');
var objectFit     = require('object-fit-images');


var onError = function (err) {
  console.log('An error occurred:', gutil.colors.magenta(err.message));
  gutil.beep();
  this.emit('end');
};

// set the theme files
var paths = {
  all: ['./**/*.*'],
  details: [
    '*.php',
    '*.md',
    '*.txt',
    '*.png',
    '*.css',
    './js/**/*.*',
    './inc/**/*.*',
    './languages/**/*.*',
    './template-parts/**/*.*',
    '!./style.original.css'
  ]
};

// SASS
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

// Watch
gulp.task('watch', function() {
  browserSync.init({
    files: ['./**/*.php'],
    proxy: 'http://localhost:8888/wordpress/',
  });
  gulp.watch('./sass/**/*.scss', ['sass', reload]);
});

// Init—JS
gulp.task('objectFit', function(){
  gulp.src('./node_modules/object-fit-images/dist/ofi.min.js')
  .pipe(gulp.dest('./js/'))
  gulp.src('./node_modules/object-fit-images/preprocessors/mixin.scss')
  .pipe(rename('/sass/mixins/_object-fit.scss'))
  .pipe(gulp.dest('./'))
});

// copy over all but the assets folder
gulp.task('copy', [], function(){
  return gulp.src(paths.details, {base: './'})
  	.pipe(newer('build'))
    .pipe(gulp.dest('build'));
});

gulp.task('mincss', function(){
  return gulp.src('style.css')
    .pipe(cleanCSS())
    // .pipe(cleanCSS({level: 2}))
    .pipe(rename({suffix: ".min"}))
    .pipe(gulp.dest('build'));
});


// Global commands
gulp.task('default', ['sass', 'watch']);
gulp.task('init', ['sass', 'objectFit']);
gulp.task('build', ['copy', 'mincss']);
