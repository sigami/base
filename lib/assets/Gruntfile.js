'use strict';
module.exports = function (grunt) {

    grunt.initConfig({
        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            all: [
                'vendor/bootstrap/js/*.js',
                'js/*.js'
            ]
        },
        less: {
            dist: {
                files: {
                    'dist/jutzu.css': [
                        'less/jutzu.less'
                    ]
                },
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapFilename: 'dist/jutzu.css.map',
                    sourceMapRootpath: '../'
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'dist/jutzu.js': [
                        'vendor/bootstrap/dist/js/bootstrap.js',
                        'js/*.js'
                    ]
                },
                options: {
                    sourceMap: true
                }
            }
        },
        watch: {
            less: {
                files: [
                    'vendor/bootstrap/less/*.less',
                    'vendor/font-awesome/less/*.less',
                    'less/*.less'
                ],
                tasks: ['less']
            },
            js: {
                files: [
                    '<%= jshint.all %>'
                ],
                tasks: ['uglify']
            },
            livereload: {
                options: {
                    livereload: true
                },
                files: [
                    'dist/jutzu.css',
                    'js/*',
                    '../../*.php',
                    '../classes/*.php'
                ]
            }
        },
        clean: {
            dist: [
                'dist',
            ]
        }
    });

    // Load tasks
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');

    // Register tasks
    grunt.registerTask('default', [
        'clean',
        'less',
        'uglify'
    ]);

    grunt.registerTask('build', [
        'clean:dist',
        'less',
        'uglify'
    ]);

    grunt.registerTask('dev', [
        'watch'
    ]);

};
