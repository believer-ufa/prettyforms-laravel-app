module.exports = function(grunt) {

  grunt.initConfig({

    concat: {
      js: {
        src: [
          './bower_components/jquery/dist/jquery.js',
          './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js',
          './bower_components/underscore/underscore.js',
          './bower_components/backbone/backbone.js',
          './public/assets/js.src/*.js',
          './public/assets/js.src/classes/*.js'
        ],
        dest: './public/assets/application.js'
      }
    },
    uglify: {
      options: {
        mangle: false
      },
      js: {
        files: {
          './public/assets/application.js': './public/assets/application.js'
        }
      }
    },
    sass: {
      development: {
        files: {
          "./public/assets/application.css":"./app/assets/application.sass"
        }
      }
    },
    watch: {
      js: {
        files: [
          './bower_components/jquery/dist/jquery.js',
          './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js',
          './public/assets/js.src/*.js',
          './public/assets/js.src/classes/*.js'
          ],
        tasks: ['concat:js', 'uglify:js']
      },
      sass: {
        files: ['./app/assets/stylesheets/*.sass'],
        tasks: ['sass']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['watch']);
};