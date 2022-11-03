var gulp = require('gulp');
var minify = require('gulp-minify');
var concat = require('gulp-concat');

// gulp.task('min-js', function() {
//     return gulp.src(['public/frontend/js/*.js', 'public/frontend/js/vendors/*.js'])
//         .pipe(concat('all.js'))
//         .pipe(minify())
//         .pipe(gulp.dest('public/frontend/js/single'))
// });

gulp.task('min-css', function() {
    return gulp.src(['public/frontend/css/*.css'])
        .pipe(minify())
        .pipe(gulp.dest('public/frontend/css/new'))
});

// gulp.task('watch', function(){
//   gulp.watch('public/frontend/js/*.js', ['min-js']); 
//   // Other watchers
// });

// gulp.task('default', ['min-js', 'watch']);