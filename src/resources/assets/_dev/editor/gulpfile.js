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
const destination = '../../dist/editor';



gulp.task('default', ['watch']);


//// ***************************************  ////

var acepath = path.dirname(require.resolve('ace-builds/package.json'));
var handlebarsfile = path.dirname(require.resolve('handlebars/package.json')) + '/dist/handlebars.min.js';
// console.log(require.resolve('emmet'));
// return;
gulp.task('copy_assets', function(){
	var editorjsfiles = [
        require.resolve('dropzone'),
        './js/vendor/ckeditor_4.6/ckeditor.js',
        './js/vendor/ckeditor_4.6/adapters/jquery.js',
//         require.resolve('emmet'),
        
        acepath+'/src-min-noconflict/ace.js',
        acepath+'/src-min-noconflict/ext-language_tools.js',
        acepath+'/src-min-noconflict/ext-emmet.js',
        require.resolve('sortablejs'),
        handlebarsfile,
        './js/vendor/holder.min.js',
        './js/vendor/tether.min.js',
        '../BootstrapVersions/bootstrap-4.0.0-alpha.6/dist/js/bootstrap.min.js',
        './js/vendor/hammer.min.js',
        './js/vendor/hammer.jquery.js',
        './js/vendor/FileSaver.js',
        './js/vendor/g3n1us_helpers.js',
        './js/vendor/vue.js',
        handlebarsfile,
       //  require.resolve('fastclick'),
        require.resolve('mousetrap'),
        require.resolve('jquery-smooth-scroll'),
        './js/logic.js',
        './js/includes/handlers.js',
        './js/handlebars_fetcher.js',
        './js/includes/leprechaun.js',
        './js/includes/Caret.js-master/dist/jquery.caret.min.js',
        './js/includes/At.js-master/dist/js/jquery.atwho.min.js',
        './js/random.js',
        
    ];
	gulp.src(editorjsfiles)
	  .pipe(concat('pub-editor.js'))
	  .pipe(gulp.dest(destination + '/js'))
	  .pipe(concat('pub-editor.min.js'))
	  .pipe(uglify())
	  .pipe(gulp.dest(destination + '/js'));
	// Copy the required files
    gulp.src(acepath+'/src-min-noconflict/**').pipe(gulp.dest(destination + '/js/ace/src-min-noconflict')); 
    gulp.src('./js/vendor/ckeditor_4.6/**').pipe(gulp.dest(destination + '/js/ckeditor')); 
    gulp.src('./js/ckeditor_templates.js').pipe(gulp.dest(destination + '/js')); 
    gulp.src('./js/ckeditor_plugins/**').pipe(gulp.dest(destination + '/js/ckeditor_plugins')); 
    gulp.src('./css/ckeditor.css').pipe(gulp.dest(destination + '/css')); 
	  
	var fontawesomedir = path.dirname(require.resolve('font-awesome/package.json'));
	var fontawesomefile = fontawesomedir + '/scss/font-awesome.scss';
	// Copy the required files
    gulp.src(fontawesomedir + '/fonts/*').pipe(gulp.dest(destination + '/fonts')); 
    return gulp.src('./images/**').pipe(gulp.dest(destination + '/images')); 
});




//// ***************************************  ////


gulp.task('editor', function() {
	var fontawesomefile = path.dirname(require.resolve('font-awesome/package.json')) + '/scss/font-awesome.scss';
	var cssfiles = [
		path.dirname(require.resolve('dropzone')) + '/dropzone.css',
		'./js/includes/At.js-master/dist/css/jquery.atwho.min.css',
		'./sass/editor.scss',		
		fontawesomefile,		
	];
	gulp.src(cssfiles)
	  .pipe(sass().on('error', sass.logError))
	  .pipe(autoprefixer({
		  browsers: ['last 4 versions'],
	  }))
	  .pipe(sourcemaps.init())
	  .pipe(concat('pub-editor.css'))
	  .pipe(gulp.dest(destination + '/css'))	
	  .pipe(cssnano())
	  .pipe(concat('pub-editor.min.css'))	  
	  .pipe(sourcemaps.write('.'))
	  .pipe(gulp.dest(destination + '/css'));
});


//// ***************************************  ////



gulp.task('watch', function() {
	var watcher = gulp.watch(['./sass/*'], ['editor']);
	watcher.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
	
});



