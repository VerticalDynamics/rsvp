'use strict';

const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sass = require('gulp-sass');
const cssnano = require('gulp-cssnano');
const autoprefixer = require('gulp-autoprefixer');
const connect = require('gulp-connect-php');
const browserSync = require('browser-sync').create();

const tasks = [
  'normalize',
  'skeleton-css',
  'sass'
];

gulp.task('sass', () => {
  gulp.src('sass/style.scss')
    .pipe(plumber())
    .pipe(sass({
      outputStyle: 'compressed'
    }))
    .pipe(autoprefixer({
      browsers: ['last 3 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('static/css'))
    .pipe(browserSync.stream());
});

gulp.task('normalize', () => {
  gulp.src('node_modules/skeleton-css/css/normalize.css')
    .pipe(cssnano())
    .pipe(gulp.dest('static/css'));
});

gulp.task('skeleton-css', () => {
  gulp.src('node_modules/skeleton-css/css/skeleton.css')
    .pipe(cssnano())
    .pipe(gulp.dest('static/css'));
});

gulp.task('default', tasks);

gulp.task('watch', tasks, () => {
  connect.server({
    port: '8000'
  }, () => {
    browserSync.init({
      proxy: '127.0.0.1:8000',
      port: 1337
    });
  });

  gulp.watch('sass/**/*.scss', ['sass']);
  gulp.watch(['**/*.php', '**/*.js']).on('change', browserSync.reload)
});
