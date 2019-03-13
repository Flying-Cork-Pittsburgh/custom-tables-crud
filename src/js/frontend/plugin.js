/*
 * @preserve: Custom JavaScript Logic - Frontend
 */

;var CTCRUD_NS = CTCRUD_NS || {};

(function($, undefined) {

  CTCRUD_NS.Site = {

    sampleFunction: function( name ) {

      name = name || 'world';
      console.log( 'Hello ' + name + '!' )

    }

  }

  // Write a message to the debugger console
  //CTCRUD_NS.Site.sampleFunction( 'Darlene' );

})( window.jQuery );
