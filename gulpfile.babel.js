/**
 * Initial gulpfile based on this css-tricks article:
 * https://css-tricks.com/gulp-for-wordpress-initial-setup/
 * https://css-tricks.com/gulp-for-wordpress-creating-the-tasks/
 */

import { src, dest, watch } from "gulp";
import yargs from "yargs";
import sass from "gulp-sass";
import postcss from "gulp-postcss";
import sourcemaps from "gulp-sourcemaps";
import cleanCss from "gulp-clean-css";
import gulpif from "gulp-if";
import purgecss from "gulp-purgecss";

const PROD = yargs.argv.prod;

export const styles = () => {
	return src("src/scss/style.scss")
		.pipe(gulpif(!PROD, sourcemaps.init()))
		.pipe(sass().on("error", sass.logError))
		.pipe(postcss([require("tailwindcss")]))
		.pipe(gulpif(PROD, postcss([require("autoprefixer")])))
		.pipe(gulpif(PROD, purgecss({ content: ["./**/*.php"] })))
		.pipe(gulpif(PROD, cleanCss({ compatibility: "ie8" })))
		.pipe(gulpif(!PROD, sourcemaps.write()))
		.pipe(dest("public/css"));
};

export const dev = () => {
	watch("src/scss/**/*.scss", styles);
};

export default { dev, styles };
