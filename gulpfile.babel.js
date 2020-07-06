import gulp from "gulp";
import yargs from "yargs";
import sass from "gulp-sass";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import sourcemaps from "gulp-sourcemaps";
import cleanCss from "gulp-clean-css";
import gulpif from "gulp-if";
import imagemin from "gulp-imagemin";
import del from "del";
import webpack from "webpack-stream";
import uglify from "gulp-uglify";
import named from "vinyl-named";
import browserSync from "browser-sync";
import zip from "gulp-zip";

let server = browserSync.create();
const PROD = yargs.argv.prod;

let paths = {
	styles: {
		src: ["src/scss/style.scss" /* "src/scss/admin.scss" */],
		dest: "public/css",
	},
	images: {
		src: "src/img/**/*.{jpg,jpeg,png,svg,gif}",
		dest: "public/img",
	},
	scripts: {
		src: ["src/js/index.js" /* "src/js/admin.js" */],
		dest: "public/js",
	},
	other: {
		src: ["src/**/*", "!src/{img,js,scss}", "!src/{img,js,scss}/**/*"],
		dest: "public",
	},
	package: {
		src: [
			"**/*",
			"!node_modules{,/**}",
			"!package{,/**}",
			"!src{,/**}",
			"!.*",
			"!*.md",
			"!gulpfile.babel.js",
			"!package.json",
			"!package-lock.json",
			"!phpcs.xml.dist",
			"!composer.json",
		],
		dest: "package",
	},
};

export let serve = (done) => {
	server.init({
		proxy: "http://paulgarciaco.local/",
	});
	done();
};

export let reload = (done) => {
	server.reload();
	done();
};

export let clean = () => del(["public", "package"]);

export let styles = () => {
	return gulp
		.src(paths.styles.src)
		.pipe(gulpif(!PROD, sourcemaps.init()))
		.pipe(sass().on("error", sass.logError))
		.pipe(gulpif(PROD, postcss([autoprefixer()])))
		.pipe(gulpif(PROD, cleanCss({ compatibility: "ie8" })))
		.pipe(gulpif(!PROD, sourcemaps.write()))
		.pipe(gulp.dest(paths.styles.dest))
		.pipe(server.stream());
};

export let images = () => {
	return gulp
		.src(paths.images.src)
		.pipe(gulpif(PROD, imagemin()))
		.pipe(gulp.dest(paths.images.dest));
};

export let copy = () => {
	return gulp.src(paths.other.src).pipe(gulp.dest(paths.other.dest));
};

export let watch = () => {
	gulp.watch("src/scss/**/*.scss", styles);
	gulp.watch("src/js/**/*.js", gulp.series(scripts, reload));
	gulp.watch("**/*.php", reload);
	gulp.watch(paths.images.src, gulp.series(images, reload));
	gulp.watch(paths.other.src, gulp.series(copy, reload));
};

export let scripts = () => {
	return gulp
		.src(paths.scripts.src)
		.pipe(named())
		.pipe(
			webpack({
				module: {
					rules: [
						{
							test: /\.js$/,
							use: {
								loader: "babel-loader",
								options: {
									presets: ["@babel/preset-env"],
								},
							},
						},
					],
				},
				output: {
					filename: "[name].js",
				},
				devtool: !PROD ? "inline-source-map" : false,
			})
		)
		.pipe(gulpif(PROD, uglify()))
		.pipe(gulp.dest(paths.scripts.dest));
};

export let compress = () => {
	return gulp
		.src(paths.package.src)
		.pipe(zip("paul-garcia-co.zip"))
		.pipe(gulp.dest(paths.package.dest));
};

export let dev = gulp.series(
	clean,
	gulp.parallel(styles, scripts, images, copy),
	serve,
	watch
);

export let build = gulp.series(
	clean,
	gulp.parallel(styles, scripts, images, copy)
);

export let bundle = gulp.series(build, compress);

export default dev;
