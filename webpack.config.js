let path = require('path'),
    glob = require("glob"),
    _ = require('lodash');

const JS_DIST_DIR = path.resolve(__dirname, 'web/js/dist/routes'),
    JS_SOURCE_DIR = path.resolve(__dirname, 'web/js/app/routes');

module.exports = {
    // change to production in feature
    mode: 'development',
    entry: Object.assign({},
        _.reduce(glob.sync(JS_SOURCE_DIR + '/*.js'),
            (obj, val) => {
                const filenameRegex = /[\w-]+(?:\.\w+)*$/i;
                obj[val.match(filenameRegex)[0]] = val;
                return obj;
            },
            {}),
        {}),
    output: {
        publicPath: '/',
        filename: '[name]',
        path: JS_DIST_DIR
    }
};