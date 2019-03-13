/*
 * @preserve: Custom JavaScript Logic - WP Admin
 */

;var CTCRUD_NS = CTCRUD_NS || {};

(function($, undefined) {

  CTCRUD_NS.Admin = {

    exampleFunction: function( name ) {

      name = name || 'world';
      console.log( 'Hello ' + name );

    }

  }

  // Write a message to the debugger console
  CTCRUD_NS.Admin.exampleFunction( 'James' );

})( window.jQuery );
