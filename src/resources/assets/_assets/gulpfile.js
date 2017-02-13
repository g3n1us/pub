require('es6-promise').polyfill();
var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var awspublish = require('gulp-awspublish');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var fs = require('fs');
const path = require('path');
const gulpCopy = require('gulp-copy');
const destination = '../dist';



gulp.task('default', ['watch']);
gulp.task('sparse', ['process']);
gulp.task('full', ['copy_assets', 'process', 'editor', 'publish']);


//// ***************************************  ////


gulp.task('copy_assets', function(){
	var jsfiles = [
        './quicksand_js/dropzone.js',
        './js/vendor/ckeditor_4.6/ckeditor.js',
        './js/vendor/ckeditor_4.6/adapters/jquery.js',
        './quicksand_js/ace/src-min-noconflict/ace.js',
        './quicksand_js/ace/src-min-noconflict/ext-language_tools.js',
        './quicksand_js/ace/src-min-noconflict/ext-emmet.js',
        './quicksand_js/emmet-core/emmet.js',
        './quicksand_js/Sortable-master/Sortable.min.js',
    ];
	gulp.src(jsfiles)
	  .pipe(concat('private-compiled.js'))
	  .pipe(gulp.dest(destination + '/js'))
	  .pipe(concat('private-compiled.min.js'))
	  .pipe(uglify())
	  .pipe(gulp.dest(destination + '/js'));
	// Copy the required files
    gulp.src('./quicksand_js/ace/src-min-noconflict/**').pipe(gulp.dest(destination + '/js/ace/src-min-noconflict')); 
    gulp.src('./js/vendor/ckeditor_4.6/**').pipe(gulp.dest(destination + '/js/ckeditor')); 
    gulp.src('./quicksand_js/ckeditor_templates.js').pipe(gulp.dest(destination + '/js')); 
		
	
	var handlebarsdir = path.dirname(require.resolve('handlebars/package.json'));
	var handlebarsfile = handlebarsdir + '/dist/handlebars.min.js';
	
	var jsfiles = [
        './js/vendor/holder.min.js',
        './js/vendor/tether.min.js',
//        './js/vendor/bootstrap.min.js',
        './BootstrapVersions/bootstrap-4.0.0-alpha.6/dist/js/bootstrap.min.js',
        './js/vendor/hammer.min.js',
        './js/vendor/hammer.jquery.js',
        './js/vendor/FileSaver.js',
// 	        './js/vendor/combined.js',
        './js/vendor/g3n1us_helpers.js',
        './js/vendor/vue.js',
        handlebarsfile,
        require.resolve('fastclick'),
        require.resolve('mousetrap'),
        require.resolve('jquery-smooth-scroll'),
	    ];
	gulp.src(jsfiles)
	  .pipe(concat('public-compiled.js'))
	  .pipe(gulp.dest(destination + '/js'))
	  .pipe(concat('public-compiled.min.js'))
	  .pipe(uglify())
	  .pipe(gulp.dest(destination + '/js'));
	  
	var fontawesomedir = path.dirname(require.resolve('font-awesome/package.json'));
	var fontawesomefile = fontawesomedir + '/scss/font-awesome.scss';
	// Copy the required files
    gulp.src(fontawesomedir + '/fonts/*').pipe(gulp.dest(destination + '/fonts')); 
    return gulp.src('./images/**').pipe(gulp.dest(destination + '/images')); 
});


//// ***************************************  ////


gulp.task('process', function() {    
	var fontawesomedir = path.dirname(require.resolve('font-awesome/package.json'));
	var fontawesomefile = fontawesomedir + '/scss/font-awesome.scss';
	
	bothcssfiles = [
		fontawesomefile, 
	];
	
	gulp.src(bothcssfiles)
	  .pipe(sass().on('error', sass.logError))
	  .pipe(autoprefixer({
		  browsers: ['last 4 versions'],
	  }))
	  .pipe(concat('both-compiled.css'))
 	  .pipe(gulp.dest(destination + '/css'))
	  .pipe(cssnano())
	  .pipe(concat('both-compiled.min.css'))	
	  .pipe(gulp.dest(destination + '/css'));	     
	  
	
	cssfiles = [
		'./css/*.css', 
		'./sass/washington-examiner-2016.scss',
	];
	
	outputfilename = 'public-compiled.css';
	minified_outputfilename = 'public-compiled.min.css';

	return gulp.src(cssfiles)
	  .pipe(sass().on('error', sass.logError))
	  .pipe(autoprefixer({
		  browsers: ['last 4 versions'],
	  }))
	  .pipe(sourcemaps.init())
	  .pipe(concat(outputfilename))
	  .pipe(gulp.dest(destination + '/css'))
	  .pipe(cssnano())
	  .pipe(concat(minified_outputfilename))	  
	  .pipe(sourcemaps.write('.'))
	  .pipe(gulp.dest(destination + '/css'));
	  
});


//// ***************************************  ////


gulp.task('editor', function() {
	
	var cssfiles = [
		'./quicksand_js/dropzone-4.3.0/dist/dropzone.css',
		'./sass/editor.scss',		
		'./sass/ccm.app.scss',		
	];
	gulp.src(cssfiles)
	  .pipe(sass().on('error', sass.logError))
	  .pipe(autoprefixer({
		  browsers: ['last 4 versions'],
	  }))
	  .pipe(sourcemaps.init())
	  .pipe(concat('private-compiled.css'))
	  .pipe(gulp.dest(destination + '/css'))	
	  .pipe(cssnano())
	  .pipe(concat('private-compiled.min.css'))	  
	  .pipe(sourcemaps.write('.'))
	  .pipe(gulp.dest(destination + '/css'));
});


//// ***************************************  ////


gulp.task('watch', function() {
	var watcher = gulp.watch(['./sass/*'], ['process']);
	watcher.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
	
});

gulp.task('watch-editor', function() {
	var watcher = gulp.watch(['./sass/*'], ['editor']);
	watcher.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
	
});


//// ***************************************  ////


gulp.task('publish', function() {
 
	// create a new publisher using S3 options 
	// http://docs.aws.amazon.com/AWSJavaScriptSDK/latest/AWS/S3.html#constructor-property 
	var publisher = awspublish.create({
		region: 'us-east-1',
		params: {
		  Bucket: 'mediadc-file-uploads'
		}
	}, {
		//cacheFileName: 'your-cache-location'
	});
	
	// define custom headers 
	var headers = {
		'Cache-Control': 'max-age=315360000, no-transform, public',
	};
 
 
	return gulp.src('../dist/**')
		.pipe(rename(function (path) {
			path.dirname = 'dist/' + path.dirname;
		}))
	    .pipe(awspublish.gzip())
		
	    // publisher will add Content-Length, Content-Type and headers specified above 
	    // If not specified it will set x-amz-acl to public-read by default 
	    .pipe(publisher.publish(headers))
	 
	    // create a cache file to speed up consecutive uploads 
	    .pipe(publisher.cache())
	 
	     // print upload updates to console 
	    .pipe(awspublish.reporter());
});













/*
gulp.src('../fonts/*')
	.pipe(rename(function (path) {
		path.dirname = '2016/fonts/' + path.dirname;
	}))
    .pipe(publisher.publish(headers));
//   ['../fonts/*','../images/*','../img/*']
gulp.src('../img/*')
	.pipe(rename(function (path) {
		path.dirname = '2016/img/' + path.dirname;
	}))
    .pipe(publisher.publish(headers));

gulp.src('../images/*')
	.pipe(rename(function (path) {
		path.dirname = '2016/images/' + path.dirname;
	}))
    .pipe(publisher.publish(headers));
 
  return gulp.src('../dist/*')
	.pipe(rename(function (path) {
		path.dirname = '2016/' + path.dirname;
	}))
*/
