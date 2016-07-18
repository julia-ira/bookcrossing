var gulp = require('gulp'),
    del = require('del'),

    /* CSS */
    sourcemaps = require('gulp-sourcemaps'),
    sass = require('gulp-sass'),

    /* Javascript and Typescript */
    jsuglify = require('gulp-uglify'),
    typescript = require('gulp-typescript'),

    /* Configuration files */
    tsconfig = require('./tsconfig.json');


gulp.task('clean', function() {
    return del('app/dist/**/*');
});

gulp.task('build-css', ['clean'], function() {
    return gulp.src('app/style/*')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('app/dist/style'));
});

gulp.task('compile', ['build-css'], function() {
    return gulp
        .src(['app/js/*.ts', 'app/js/**/*.ts'])
        .pipe(sourcemaps.init())
        .pipe(typescript(tsconfig.compilerOptions))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('app/dist/js'));
});

gulp.task('build', ['compile']);
gulp.task('default', ['build']);

// TODO: use plugins & write additional tasks, more recepies here: https://github.com/gulpjs/gulp/tree/master/docs/recipes
