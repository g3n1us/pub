require('es6-promise').polyfill();
var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var fs = require('fs');
const path = require('path');
const gulpCopy = require('gulp-copy');
const destination = '../../dist/theme';

gulp.task('default', ['watch']);

gulp.task('watch', function() {
	var watcher = gulp.watch(['./sass/*'], ['process']);
	watcher.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
	
});





gulp.task('copy_assets', function(){

    return gulp.src('./images/**').pipe(gulp.dest(destination + '/images')); 

});


//// ***************************************  ////


gulp.task('process', function() {    	
	scssfiles = [
		'./css/*',
		'./theme.scss',
	];

	return gulp.src(scssfiles)
	  .pipe(sass().on('error', sass.logError))
	  .pipe(autoprefixer({
		  browsers: ['last 4 versions'],
	  }))
	  .pipe(sourcemaps.init())
	  .pipe(concat('theme.css'))
	  .pipe(gulp.dest(destination + '/css'))
	  .pipe(cssnano())
	  .pipe(concat('theme.min.css'))	  
	  .pipe(sourcemaps.write('.'))
	  .pipe(gulp.dest(destination + '/css'));
	  
});
