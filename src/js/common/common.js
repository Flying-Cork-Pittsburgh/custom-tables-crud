/*
 * @preserve: Custom JavaScript Logic - Frontend & Backend
 */

;var CTCRUD_NS = CTCRUD_NS || {};

(function($, undefined) {

  CTCRUD_NS.Common = {

    clearObjectCache: function() {

      $.ajax({
		      type: 'GET',
		      url: ctcrud_ajax_filter_params.ajax_url,
					dataType: 'json',
		      data: {
	          action: 'clear_object_cache_ajax'
		      },
		      success: function(result)
		      {
            alert( result.success ? _ctcrud_plugin_settings['admin_bar_add_clear_cache_success'] : 'Error: ' + result.message );
		      }
		  });

    }

  }

  // Bind event to clear theme cache Admin Bar link
  if( typeof _ctcrud_plugin_settings !== 'undefined' && _ctcrud_plugin_settings['show_clear_cache_link'] && _ctcrud_plugin_settings['admin_bar_add_clear_cache'] ) {
    $( '#wpadminbar' ).waitUntilExists(function() {
      $('#wp-admin-bar-clear_object_cache').on( 'click', function( event ) {
    		event.preventDefault();
    		CTCRUD_NS.Common.clearObjectCache();
    		return false;
    	});
    });
  }

})( window.jQuery );
