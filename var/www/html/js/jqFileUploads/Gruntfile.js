///*
// * jQuery File Upload Gruntfile
// * https://github.com/blueimp/jQuery-File-Upload
// *
// * Copyright 2013, Sebastian Tschan
// * https://blueimp.net
// *
// * Licensed under the MIT license:
// * http://www.opensource.org/licenses/MIT
// */
//
///*global module, require */
//
//module.exports = function (grunt) {
//    'use strict';
//
//    function bowerJson() {
//        require('bower-json').validate(require('./bower.json'));
//    }
//
//    grunt.initConfig({
//        jshint: {
//            options: {
//                jshintrc: '.jshintrc'
//            },
//            all: [
//                'Gruntfile.js',
//                'js/cors/*.js',
//                'js/*.js',
//                'server/node/server.js',
//                'test/test.js'
//            ]
//        }
//    });
//
//    grunt.loadNpmTasks('grunt-contrib-jshint');
//    grunt.loadNpmTasks('grunt-bump-build-git');
//    grunt.registerTask('bower-json', bowerJson);
//    grunt.registerTask('test', ['jshint', 'bower-json']);
//    grunt.registerTask('default', ['test']);
//
//};


module.exports = function (grunt) {
    //var pkg = grunt.file.readJSON('package.json');
    grunt.initConfig({
        //concat: {
        //    files: {
        // 元ファイルの指定
        //src : 'js/*.js',
        // 出力ファイルの指定
        //dest: 'js/Gruntfile/all.js'
        //}
        //},

        //uglify: {
        //    dist: {
        //        files: {
        //            // 出力ファイル: 元ファイル
        //            'js/Gruntfile/*.min.js': 'js/Gruntfile/*.js'
        //        }
        //    }
        //},
        uglify: {
            build: {
                files: [{
                    expand: true,
                    src: 'js/*.js',
                    dest: 'js/build',
                    //cwd: 'js/app/scripts'
                }]
            }
        },

        watch: {
            js: {
                files: 'js/*.js',
                tasks: ['concat', 'uglify']
            }
        }
    });

    // プラグインのロード・デフォルトタスクの登録
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['uglify']);
};