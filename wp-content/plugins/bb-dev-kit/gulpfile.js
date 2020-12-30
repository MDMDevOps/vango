const gulp         = require('gulp');
const sass         = require('gulp-sass');
const postcss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano      = require('cssnano');
const gulpWebpack  = require('webpack-stream');
const uglify       = require("gulp-uglify");
const sourcemaps   = require('gulp-sourcemaps');
const rename       = require('gulp-rename');
const path         = require('path');
const glob         = require("glob")

/**
 * Helper function to glob all .js files in src directory
 */
var webpack_entries = glob.sync( 'src/scripts/*.js' ).reduce( ( files, file ) => {

	let filename =  path.basename( file );

	let name = path.parse( filename ).name;

	files[name] = './src/scripts/' + filename;

	return files;

}, {} );
/**
 * Helper function to retrieve wepack config
 */
const _getWebpackConfig = ( mode = 'production' ) => {

	let config = {
		// entry: [],
		entry : webpack_entries,
		output : {
			filename: '[name].js',
			// path: path.resolve( __dirname, 'assets/lasjd' )
		},
		mode: mode,
		externals: {
		  'jquery': 'jQuery'
		},
		module : {
			rules : [
				{
					test: /.js$/,
					exclude: /(node_modules)/,
					use : {
						loader : 'babel-loader',
						options: {
							presets: [ "@wordpress/babel-preset-default" ]
						}
					}
				}
			]
		}
	}

	return config
}

gulp.task( 'buildjs', function() {

	return gulp.src( 'src/scripts/*.js' )
	/**
	 * Build for development
	 *
	 * Build with webpack, with mode set to development and save unminified file w/sourcemap
	 */
	.pipe( gulpWebpack( { config : _getWebpackConfig( 'development' ) }, require( 'webpack' ) ) )
	// Recover from errors
	.on( 'error', function() {
		this.emit( 'end' );
	})
	// Send to destination
	.pipe( gulp.dest( 'assets/js' ) )
	/**
	 * Build for production
	 *
	 * Build with webpack, with mode set to production, minify, rename, and do not produce a sourcemap
	 */
	.pipe( gulpWebpack( { config : _getWebpackConfig( 'production' ) }, require( 'webpack' ) ) )
	// Recover from errors
	.on( 'error', function() {
		this.emit( 'end' );
	})
	// Create a min version
	.pipe( rename( { suffix: '.min' } ) )
	// Uglify it
	.pipe( uglify() )
	// Save production version
	.pipe( gulp.dest( 'assets/js' ) )
} ) ;

gulp.task( 'modulescss', function(){
	return gulp.src( './src/styles/modules/**/*.scss' )
	.pipe( sass().on( 'error', sass.logError ) )
	.pipe(rename(function (path) {
		path.dirname  = "modules/" + path.basename + '/css';
		path.basename = 'frontend';
	}))
	.pipe( gulp.dest( './' ) )
} );

gulp.task( 'modulecss', gulp.series( 'modulescss', function () {
	return gulp.src( './modules/**/frontend.css' )
	.pipe( postcss( [
		autoprefixer( { grid : 'autoplace' } ),
		cssnano()
	] ) )
	.pipe( rename( function( path ) {
		path.dirname = 'modules/' + path.dirname;
	}))
	.pipe( gulp.dest( './' ) );
} ) );

gulp.task( 'globalsass', function(){
	return gulp.src( './src/styles/global/**/*.scss' )
	.pipe( sass().on( 'error', sass.logError ) )
	.pipe( gulp.dest( 'assets/css' ) )
} );

gulp.task( 'globalcss', gulp.series( 'globalsass', function () {
	return gulp.src( './assets/css/*.css' )
	.pipe( postcss( [
		autoprefixer( { grid : 'autoplace' } ),
		cssnano()
	] ) )
	.pipe( gulp.dest( './assets/css' ) );
} ) );

gulp.task( 'watch', function(){
	gulp.watch( 'src/styles/modules/**/*.scss', gulp.series( 'modulecss' ) );
	gulp.watch( 'src/styles/global/**/*.scss', gulp.series( 'globalcss' ) );
	gulp.watch( 'src/scripts/**/*.js', gulp.series( 'buildjs' ) );
});

gulp.task('default', gulp.series( [ 'watch' ] ));

/**
 * Set the build path
 */
// gulp.task( 'build', gulp.series( [ 'css' ] ) );