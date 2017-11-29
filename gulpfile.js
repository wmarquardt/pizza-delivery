var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var cssmin = require('gulp-clean-css');
var watch = require('gulp-watch');
var ngmin = require('gulp-ngmin');

gulp.task('js', function(){
	return  gulp.src([
		'bower_components/jquery/dist/jquery.min.js',
		'js/kc.fab.js',
		'bower_components/bootstrap/dist/js/bootstrap.min.js',
		'bower_components/bootstrap-material-design/dist/js/material.min.js',
		'js/angular.min.js',
		'js/toastr.min.js',
		'js/ui-bootstrap.js',
		'js/select2.full.js',
		'js/angular-route.min.js',
		'js/angular-cookies.min.js',
		'js/main.js'
	])
	.pipe(concat('main.canvas.min.js'))
	.pipe(gulp.dest('js/dist'));
});
gulp.task('css', function(){
	return gulp.src([
		'bower_components/bootstrap/dist/css/bootstrap.min.css',
		'bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.min.css',
		'css/kc.fab.css',
		'css/select2.css',
		'css/toastr.min.css',
		'css/user_style.css'
	])
	.pipe(concat('main.canvas.min.css'))
	.pipe(cssmin())
	.pipe(gulp.dest('css/dist'));
});
gulp.task('appmin', function () {
	return gulp.src('js/app/app.js')
		.pipe(uglify({
			mangle : false
		}))
		.pipe(gulp.dest('js/dist'));
});


gulp.task('default', ['js', 'css', 'appmin']);
gulp.task('w', function() {
  gulp.watch('js/*.js', ['js']);
  gulp.watch('css/*.css', ['css']);
  gulp.watch('js/app/*.*', ['appmin']);
});

