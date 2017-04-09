// Requis
var gulp = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // tous les plugins de package.json

// Variables de chemins
var source = './application/public'; // dossier de travail
var destination = './application/public'; // dossier à livrer

gulp.task('css', function() {
    return gulp.src(source + "/css/*.scss")
        .pipe(plugins.sass())
        .pipe(plugins.csscomb())
        .pipe(plugins.cssbeautify({indent: '  '}))
        .pipe(plugins.autoprefixer())
        .pipe(gulp.dest(destination + "/css/"));
});

gulp.task('minify', function() {
    return gulp.src(destination + "/css/*.css")
        .pipe(plugins.csso())
        .pipe(plugins.rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destination + "/css/"));
});