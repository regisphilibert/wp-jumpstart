// For performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// If you want to recursively match all sub-folders, use:
// 'test/spec/**/*.js'

'use strict';

module.exports = function (grunt) {

  var autoprefixer = require('autoprefixer')({
      browsers: [
        'Chrome >= 35',
        'Firefox >= 31',
        'Edge >= 12',
        'Explorer >= 9',
        'iOS >= 8',
        'Safari >= 8',
        'Android 2.3',
        'Android >= 4',
        'Opera >= 12'
      ]
    }),
    cssnano = require('cssnano')();

  grunt.initConfig({

    //-----------------------------------------------------------------------------------------------IMPORT-package.json


    pkg: grunt.file.readJSON('package.json'),


    //-----------------------------------------------------------------------------------------------------CONFIG-OBJECT


    config: {
      src: {
        root: 'src',
        fonts: '<%= config.src.root %>/fonts/{,*/}*.{woff,woff2,ttf,eot,svg}',
        html: '<%= config.src.root %>/pug',
        images: '<%= config.src.root %>/images/',
        scripts: '<%= config.src.root %>/scripts/{,*/}*.js',
        sass: '<%= config.src.root %>/sass',
        less: '<%= config.src.root %>/less',
        api:'./api/'
      },
      dist: {
        root: 'dist',
        fonts: '<%= config.dist.root %>/fonts',
        html: '<%= config.dist.root %>/',
        images: '<%= config.dist.root %>/img',
        scripts: '<%= config.dist.root %>/js',
        styles: '<%= config.dist.root %>/css',
        assets: '<%= config.dist.root %>/assets',
        lang: 'languages'
      }
    },

  //--------------------------------------------------------------------------------------------------------POT-FILE
    makepot: {
        target: {
            options: {
                processPot: function( pot ) {
                  var translation,
                      excluded_meta = [
                          'Theme Name of the plugin/theme',
                          'Theme URI of the plugin/theme',
                          'Description of the plugin/theme',
                          'Template Name of the plugin/theme',
                          'Author of the plugin/theme',
                          'Author URI of the plugin/theme'
                      ];
                  for ( translation in pot.translations[''] ) {
                      if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
                          if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
                              console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
                              delete pot.translations[''][ translation ];
                          }
                      }
                  }
                  return pot
                },
                type: 'wp-theme',
                domainPath:'<%= config.dist.lang %>',
                mainFile: 'functions.php'
            }
        }
    },

    potomo: {
      dist: {
        options: {
          poDel: false
        },
        files: [{
          expand: true,
          cwd: '<%= config.dist.lang %>',
          src: ['*.po'],
          dest: '<%= config.dist.lang %>',
          ext: '.mo',
          nonull: true
        }]
      }
    },

    //--------------------------------------------------------------------------------------------------------CLEAN-DIST


    clean: {
      dist: ['<%= config.dist.root %>']
    },


    //---------------------------------------------------------------------------------------------------------SASS/SCSS

    // grunt-contrib-sass (need to be installed)
    sass: {
      development: {
        options: {
          sourcemap: 'none',
          style: 'compact', // nested, compact, compressed, expanded
          quiet: true,
        },
        files: [
	        {
	          expand: true,
	          cwd: '<%= config.src.sass %>',
	          src: ['{,*!/}*.{scss,sass}'],
	          dest: '<%= config.dist.styles %>',
	          ext: '.css'
	        },
	        {
	          expand: true,
	          cwd: '<%= config.src.api %>/css',
	          src: ['{,*!/}*.{scss,sass}'],
	          dest: '<%= config.src.api %>/css',
	          ext: '.css'
	        }
        ]
      }
    },

    // grunt-contrib-less (need to be installed)
    /*
    less: {
        development: {
          options: {
            paths: ['<%= config.src.less %>'],
            modifyVars: {
              configEnv: 'development',
            }
          },
          files: {
            '<%= config.dist.styles %>/main.css': '<%= config.src.less %>/main.less'
          }
        },
        stage: {
          options: {
            paths: ['<%= config.src.less %>'],
            modifyVars: {
              configEnv: 'stage',
            }
          },
          files: {
            '<%= config.dist.styles %>/main.css': '<%= config.src.less %>/main.less'
          }
        }
    },
    */
    //---------------------------------------------------------------------------------------------------------UGLIFY-JS


    uglify: {
      options: {sourceMap: true},
      dist: {
        files: [
        	{
	          expand: true,
	          cwd: '<%= config.src.root %>/scripts/',
	          src: ['{,*/}*.js', '!{,*/}*.min.js'],
	          dest: '<%= config.dist.scripts %>',
	          ext: '.min.js'
        	},
        	{
	          expand: true,
	          cwd: '<%= config.src.api %>/js',
	          src: ['{,*/}*.js', '!{,*/}*.min.js'],
	          dest: '<%= config.src.api %>/js',
	          ext: '.min.js'
        	}
        ]
      }
    },


    //----------------------------------------------------------------------------------------------------------JADE/PUG


    pug: {
      dist: {
        options: {
          pretty: '    ',
          //data: {debug: false, timestamp: '<%= grunt.template.today("yyyy-mm-dd hh:mm:ss") %>'},
          data: function(dest, src) {
            // Return an object of data to pass to templates
            return require('./data/globals.json');
          }
        },
        files: [{
          expand: true,
          cwd: '<%= config.src.html %>',
          dest: '<%= config.dist.html %>',
          src: ['{,*/}*.{pug,jade,htm,html,php}', '!{,*/}/_*.{pug,jade,htm,html,php}'],
          ext: '.html'
        }]
      }
    },

    //--------------------------------------------------------------------------------------------------------------COPY

    copy: {
      main: {
        expand: true,
        cwd: '<%= config.src.root %>/assets',
        src: '**',
        dest: '<%= config.dist.root %>/',
      },
    },

    //-------------------------------------------------------------------------------------------------------------WATCH


    watch: {
      sass: {
        files: ['<%= config.src.sass %>/**/*.scss', '<%= config.src.api %>/**/*.scss'],
        tasks: ['sass:development'],
        options: {
          spawn: false,
        },
      },
      less: {
        files: '<%= config.src.less %>/**/*.less',
        tasks: ['less:development'],
        options: {
          spawn: false,
        },
      },
      js: {
        files: ['<%= config.src.scripts %>', '<%= config.src.api %>/**/*.js'],
        tasks: ['uglify'],
        options: {
          spawn: false,
        },
      },
      html: {
        files: '<%= config.src.html %>/**/*.{pug,jade,htm,html,php,json}',
        tasks: ['pug:dist'],
        options: {
          spawn: false,
        },
      },
    },

  });


  //------------------------------------------------------------------------------------------------------LOAD-NPM-TASKS


  // load npm tasks, these plugins provide necessary tasks.
  require('load-grunt-tasks')(grunt, {
    scope: 'devDependencies',
    pattern: ['grunt-*']
  });


  //------------------------------------------------------------------------------------------------------REGISTER-TASKS

  grunt.registerTask('pot', [
    'makepot'
  ])

  grunt.registerTask('mo', [
    'potomo:dist'
  ])

  // Default task
  grunt.registerTask('default', [
    'clean', 'sass:development', 'uglify', 'copy:main', 'pug', 'watch'
  ]);
  // Stage task for stage environement
  grunt.registerTask('stage', [
    'clean', 'sass:stage', 'uglify', 'copy:main', 'pug'
  ]);

};