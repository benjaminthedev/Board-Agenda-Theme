'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var gulpif = require('gulp-if');
var sprity = require('sprity');
var documentation = require( 'gulp-documentation' );
var sourcemaps = require('gulp-sourcemaps');
var coffee = require('gulp-coffee');
var pump = require('pump');
var shell = require( 'gulp-shell' );
var concat = require('gulp-concat');
//var imagemin = require('gulp-imagemin');

//Used for jasmine/testing testing
var coffee = require( 'gulp-coffee' );

gulp.task('sass', function () {
  return gulp.src('sass/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./'));
});

gulp.task('uglify', function (cb) {
  pump([
        gulp.src('js/src/*.js'),
        uglify(),
        gulp.dest('js/min/')
    ],
    cb
  );
});

gulp.task('concat', ['uglify'], function() {
  return gulp.src('js/min/*.js')
    .pipe(concat('script.min.js'))
    .pipe(gulp.dest('js'));
});

gulp.task('concat-js', function() {
  return gulp.src('js/src/*.js')
    .pipe(concat('script.js'))
    .pipe(gulp.dest('js'));
});

gulp.task('documentation', ['php-doc', 'js-doc']);

gulp.task('watch', function () {
  gulp.watch('js/src/*.js', ['concat', 'concat-js']);
  gulp.watch('sass/**/*.scss', ['sass']);
  // gulp.watch('assets/img/sprites/*.{png,jpg}', ['sprites', 'sass']);
});
