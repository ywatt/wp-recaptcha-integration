var gulp = require('gulp');
var concat = require('gulp-concat');  
var uglify = require('gulp-uglify');  
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');


gulp.task('styles:dev', function() {
	// dev
});

gulp.task('styles:admin:settings:dev', function() {
	// dev
	// dev
    return gulp.src('scss/admin/settings.scss')
		.pipe(sourcemaps.init())
        .pipe( sass( { outputStyle: 'expanded' } ).on('error', sass.logError) )
        .pipe( sourcemaps.write() )
		.pipe(rename('settings-recaptcha.css'))
        .pipe( gulp.dest('./css/admin/'));
});


gulp.task('default', function() {
	// place code for your default task here
	gulp.watch('scss/**/*.scss',['styles:admin:settings:dev']);
//	gulp.watch('js/src/*.js',['scripts']);
});