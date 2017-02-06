var gulp = require('gulp'),
    gulpIf = require('gulp-if'),
    minimist = require('minimist'),
    cssmin = require('gulp-cssmin'),
    less = require('gulp-less'),
    concat = require('gulp-concat'),
    gulpUtil = require('gulp-util'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    gulpCopy = require('gulp-file-copy'),
    fs = require('fs'),
    clean = require('gulp-clean'),
    requirejsOptimize = require('gulp-requirejs-optimize');
var options = {
  string: 'env',
  default: { env: process.env.NODE_ENV || '' }
};
options = minimist(process.argv.slice(2), options);

var _Config_TXT = {
    error_pre : 'need arguments!',
    line_pre  : '| --',
    arg_src   : 'src',
    arg_dest  : 'dest',
    arg_name  : 'name',
    wrap      : '\r\n'
}

var _C_T = _Config_TXT;

var checkExist = function(file) {

    if(!file) return gulpUtil.log(gulpUtil.colors.red(_C_T.wrap + _C_T.error_pre));

    if(fs.existsSync(file)) return gulpUtil.log(gulpUtil.colors.red('file  ' + file + '  exists'));

    return 'ok';
}

//文件清理
gulp.task('clean', function() {
    if(!options.name) {
        return console.log(gulpUtil.colors.red(_C_T.error_pre +  _C_T.wrap + _C_T.line_pre + _C_T.arg_name + ' use , split'));
    }
    var _name = options.name.split(',');
    return gulp.src(_name, {read: false})
        .pipe(clean());
});
//css
// gulp.task('cssmin', function() {
//     if(!options.src || !options.dest) {
//         return console.log(gulpUtil.colors.red(_C_T.error_pre + _C_T.wrap + _C_T.line_pre + _C_T.arg_src + ' source files, use , split' +  _C_T.wrap + _C_T.line_pre + _C_T.arg_dest + 'build dir' +  _Config_TXT.wrap + _C_T.line_pre + _C_T.arg_name + ' out name, auto add .min, default: style.min.css'));
//     }
//     var _name = options.name || 'style.css'
//     var _src = options.src.split(',');
//     return gulp.src(_src)
//         .pipe(concat(_name))
//         .pipe(cssmin())
//         .pipe(rename({suffix: '.min'}))
//         .pipe(gulp.dest(options.dest));
//
// });

//less build
// gulp.task('lessmin', function() {
//     if(!options.src || !options.dest) {
//         return console.log(gulpUtil.colors.red(_C_T.error_pre + _C_T.wrap + _C_T.line_pre + _C_T.arg_src + ' source files, use , split' +  _C_T.wrap + _C_T.line_pre + _C_T.arg_dest + ' out folder'));
//     }
//     var _src = options.src.split(',');
//     return gulp.src(_src)
//         .pipe(less())
//         .pipe(cssmin())
//         .pipe(rename({suffix: '.min'}))
//         .pipe(gulp.dest(options.dest));
// });

gulp.task('rjs', function(){
    if(!options.src || !options.dest) {
        return console.log(gulpUtil.colors.red(_Config_TXT.error_pre + _C_T.wrap + _C_T.line_pre + _C_T.arg_src + ' source main export files, use , split' + _C_T.wrap + _C_T.line_pre + _C_T.arg_dest + ' out folder'));
    }
    var _src = options.src.split(',');
    return gulp.src(_src)
        .pipe(requirejsOptimize(function(file) {
                return {
                    baseUrl : file.base,
                    optimize: 'uglify'
                }
            }))
        .pipe(gulp.dest(options.dest));
});

gulp.task('rjs-all', function(){

    var _src = ['./App/Public/js/5.5/index.js', './App/Public/js/5.5/review.js','./App/Public/js/5.5/dishesList.js', './App/Public/js/5.5/ucenter.js','./App/Public/js/5.5/comment.js','./App/Public/js/5.5/addComment.js'],
        _dest = './App/Public/js/release/5.5';
    rjsConfig();
    return gulp.src(_src)
        .pipe(requirejsOptimize(function(file) {
                return {
                    baseUrl : file.base,
                    optimize: 'uglify'
                }
            }))
        .pipe(gulp.dest(_dest));
});

gulp.task('jsmin', function() {
    if(!options.src || !options.dest) {
        return console.log(gulpUtil.colors.red(_Config_TXT.error_pre + _C_T.wrap + _C_T.line_pre + _C_T.arg_src + ' source main export files, use , split' + _C_T.wrap + _C_T.line_pre + _C_T.arg_dest + ' out folder'));
    }
    var _name = options.name || 'build-' + new Date().getTime() + '.js';
    var _src = options.src.split(',');
    return gulp.src(_src)
        .pipe(concat(_name))
        //.pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest(options.dest));
});



gulp.task('lessc', function() {
    var _src = ["./App/Public/css/5.5/style.less"],
        _dest = "./App/Public/css/5.5"
    return gulp.src(_src)
        .pipe(less())
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(_dest));
});
//监控文件变化自执行
// gulp.task('watch', function () {
//    gulp.watch(["./App/Public/css/5.5/**/*.less"],function(){
//      gulp.run('lessc','watch');
//    });
// });

//开发环境切换
function switchTo(t){
    var _type = t || 'develop',
        _static_html = '<link rel="stylesheet" type="text/css" href="http://static.daojia.com.cn/dj-lib/css/h5/reset.min.css"/><link rel="stylesheet/less" type="text/css" href="/App/Public/css/5.5/style.less"/><script>less = {env: "development",async: true, fileAsync: true,};</script><script src="http://static.daojia.com.cn/less.js/2.7.1/less.min.js"></script>';
        _static_js_config = "var _url = '/Home/Index', _public = '/App/Public', _imgDomain = 'http://img2.daojia.com.cn';require.config({baseUrl : '/App/Public/js/5.5',});"
    if(options && options.t) {
        _type = options.t;
    }
    if(_type == 'release') {
        _static_html = '<link rel="stylesheet" type="text/css" href="/App/Public/css/5.5/style.min.css"/>';
        _static_js_config = "var _url = '/Home/Index', _public = '/App/Public', _imgDomain = 'http://img2.daojia.com.cn';require.config({baseUrl : '/App/Public/js/release/5.5',});"
    }
    //切换css环境
    (function(){
        var fileName = './App/Public/include/static.php'
        fs.writeFile(fileName, _static_html, 'utf8');
    })();
    //切换JS环境
    (function(){
        var requirejs = './App/Public/js/vendors/require.min.js',
          fileName = './App/Public/js/require.customer.js';
          fs.readFile(requirejs, 'utf8', function(err, data) {
            if (err) return console.log(err);
            fs.writeFile(fileName, data + _static_js_config, 'utf8');
          });
    })()
}
gulp.task('switch', function () {
    switchTo();
});



var rjsConfig = function(){
  var fileName = './App/Public/js/require.customer.js'
    fs.readFile(fileName,  'utf8', function(err, data) {
      if (err) return console.log(err);
      var data = data.replace(/var release = \'\/\';/g, 'var release = \'release\/\';');
      fs.writeFile(fileName, data, 'utf8');
      gulpUtil.log(gulpUtil.colors.green('修改配置文件'));
    });
}

gulp.task('build', ['lessc', 'rjs-all'], function() {
    switchTo('release');
});
