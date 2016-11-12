module.exports = function (grunt) {

    grunt.initConfig({

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