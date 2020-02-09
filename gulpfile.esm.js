import config from './config/assets';
import { src, dest, series } from 'gulp';
import plugins from 'gulp-load-plugins';

const plugin = plugins();

function styles(cb) {
    src(config.vendor.styles)
        .pipe(plugin.sass({outputStyle: 'compressed'}))
        .pipe(plugin.concat(config.vendor.css))
        .pipe(dest(config.out + '/css'));

    src(config.app.styles)
        .pipe(plugin.sass({outputStyle: 'compressed'}))
        .pipe(plugin.concat(config.app.css))
        .pipe(dest(config.out + '/css'));

    cb();
}

function scripts(cb) {
    
    src(config.vendor.scripts)
        .pipe(plugin.sourcemaps.init())
        .pipe(plugin.concat(config.vendor.js))
        .pipe(plugin.sourcemaps.write('./'))
        .pipe(dest(config.out + '/lib'));

    src(config.app.scripts)
        .pipe(plugin.rename(config.app.js))
        .pipe(plugin.sourcemaps.init())
        .pipe(plugin.uglifyEs.default())
        .pipe(plugin.sourcemaps.write('./'))
        .pipe(dest(config.out + '/lib'));

    src(config.editor.scripts)
        .pipe(plugin.sourcemaps.init())
        .pipe(plugin.concat('editor.min.js'))
        .pipe(plugin.sourcemaps.write('./'))
        .pipe(dest(config.out + '/lib'));

    cb();
}

function copy(cb)
{
    src('./resources/assets/lib/codemirror.js')
        .pipe(dest('./public/lib/'));

    cb();
}

exports.copy    = copy;
exports.styles  = styles;
exports.scripts = scripts;
exports.build   = series(copy, styles, scripts);