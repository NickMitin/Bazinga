var gulp = require('gulp'),
	compass = require('gulp-compass'),
	minifyCSS = require('gulp-minify-css'),
	watch = require('gulp-watch'),
	uglify = require('gulp-uglifyjs'),
	sourcemaps = require('gulp-sourcemaps'),
	plumber = require('gulp-plumber');


var conf = require('./conf/gulp.json');

gulp.task('style:build', function () {
	gulp.src(conf.sassPath + conf.sassMainFileName)
		.pipe(plumber())
		.pipe(compass({
			css: conf.cssPath,
			sass: conf.sassPath,
			image: conf.imagesPath,
			style: 'expanded',
			sourcemap: true
		}));

});

gulp.task('js:build', function () {
	gulp.src([conf.jsInitialSourcePath + '**/*.js', conf.jsSourcePath + '**/*.js'])
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(uglify(conf.jsMainFileName + '.js'))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(conf.jsPath));
});


gulp.task('build', [
	'js:build',
	'style:build',
]);

gulp.task('watch', function () {
	watch([conf.sassPath + '**/*.scss'], function (event, cb) {
		gulp.start('style:build');
	});
	watch([conf.jsSourcePath + '**/*.js'], function (event, cb) {
		gulp.start('js:build');
	});
});

gulp.task('default', ['build', 'watch']);