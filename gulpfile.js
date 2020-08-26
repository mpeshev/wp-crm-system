"use strict";

let gulp = require("gulp"),
  autoprefixer = require("gulp-autoprefixer"),
  livereload = require("gulp-livereload"),
  sass = require("gulp-sass"),
  notify = require("gulp-notify"),
  uglify = require("gulp-uglify"),
  concat = require("gulp-concat"),
  cleanCSS = require("gulp-clean-css"),
  rename = require("gulp-rename"),
  imagemin = require("gulp-imagemin"),
  del = require("del"),
  moment = require("moment"),
  sassLint = require("gulp-sass-lint"),
  newer = require("gulp-newer"),
  sourcemaps = require("gulp-sourcemaps"),
  babel = require("gulp-babel"),
  plumber = require("gulp-plumber"),
  cp = require("child_process");

/**
 * Unify all scripts to work with source and destination paths.
 * For more custom paths, please add them in this object
 */
const paths = {
  source: {
    scripts: "assets/src/scripts/",
    sass: "assets/src/sass/",
    images: "assets/src/images/",
    fonts: "assets/src/fonts/",
  },
  destination: {
    scripts: "assets/dist/scripts/",
    css: "assets/dist/css/",
    images: "assets/dist/images/",
    fonts: "assets/dist/fonts/",
  },
};

gulp.task("sass", function () {
  return gulp
    .src(paths.source.sass + "**/*.scss")
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest(paths.destination.css))
    .pipe(
      notify({
        onLast: true,
        title: "Sass compiled successfully.",
        message: getFormatDate(),
      })
    );
});

// cssmin
gulp.task("cssmin", () => {
  return gulp
    .src(paths.destination.css + "*.css")
    .pipe(newer(paths.destination.css))
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(cleanCSS({ compatibility: "ie8" }))
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest(paths.destination.css))
    .pipe(
      notify({
        onLast: true,
        title: "CSS minified successfully.",
        message: getFormatDate(),
      })
    );
});

// The files to be watched for minifying. If more dev js files are added this
// will have to be updated.
gulp.task("watch", function () {
  livereload.listen();

  gulp.watch(paths.source.sass + "**/*.scss", gulp.series("sass"));
  gulp.watch(paths.source.scripts + "**/*.js", gulp.series("compress"));
  gulp.watch(paths.source.images + "*", gulp.series("optimizeImages"));

  // Once the CSS file is build, minify it.
  gulp.watch(paths.destination.css + "master.css", gulp.series("cssmin"));
});

gulp.task("compress", (done) => {
  gulp
    .src(paths.source.scripts + "*.js")
    .pipe(uglify())
    .pipe(gulp.dest(paths.destination.scripts));
  done();
});

gulp.task("optimizeImages", (done) => {
  gulp
    .src(paths.source.images + "*")
    .pipe(imagemin())
    .pipe(gulp.dest(paths.destination.images));
  done();
});

gulp.task("optimizeFonts", function () {
  gulp.src(paths.source.fonts + "*").pipe(gulp.dest(paths.destination.fonts));
});

// What will be run with simply writing "$ gulp"
gulp.task(
  "default",
  gulp.series(
    "sass",
    gulp.parallel("compress", "cssmin", "optimizeImages"),
    "watch"
  )
);

// Print the current date formatted. Used for the script compile notify messages.
function getFormatDate() {
  return moment().format("LTS");
}
