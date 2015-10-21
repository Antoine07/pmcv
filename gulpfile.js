'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    minify = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify');

var path = {
    'resources': {
        'sass': './resources/assets/sass',
        'js': './resources/assets/js',
        'lang': './resources/lang/',
        'images': './resources/assets/images/**/*',
        'skeleton': './resources/vendor/bower_components/skeleton'
    },
    'public': {
        'css': './public/assets/css',
        'js': './public/assets/js',
        'images': './public/assets/images',
        'vendor': './public/assets/vendor'
    },
    'sass': './resources/assets/sass/**/*.scss',
    'js': './resources/assets/js/**/*.js'
};

gulp.task('sass', function () {

    return gulp.src(path.resources.sass + '/app.scss')
        .pipe(sass({
            onError: console.error.bind(console, 'SASS ERROR')
        }))
        .pipe(minify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.public.css))

});

gulp.task('js', function () {
    return gulp.src([
        path.resources.js + '/app.js'
    ])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.public.js));
});

gulp.task('lang', function () {
    return gulp.src([
        path.resources.lang + '/en_js.js'
    ])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.public.js));
});

// install bootstrap.min css and JS
gulp.task('skeleton-normalize', function () {
    return gulp.src([
        path.resources.skeleton + '/css/normalize.css'
    ])
        .pipe(minify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.public.css))
});

gulp.task('skeleton-css', function () {
    return gulp.src([
        path.resources.skeleton + '/css/skeleton.css'
    ])
        .pipe(minify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.public.css))
});

gulp.task('images', function () {
    return gulp.src([
        path.resources.images
    ])
        .pipe(gulp.dest(path.public.images))
});

gulp.task('deploy', ['skeleton-normalize', 'skeleton-css', 'images']);

gulp.task('watch', function () {
    gulp.watch(path.sass, ['sass']);
    gulp.watch(path.js, ['js']);
    gulp.watch(path.js, ['lang']);
});

gulp.task('default', ['watch']);