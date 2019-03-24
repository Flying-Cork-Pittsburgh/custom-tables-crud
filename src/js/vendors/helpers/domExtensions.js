/*!
 * helpers based on EasyPure by dobrapyra (v2017-08-17)
 */
(function(){
	'use strict'

	window.scrollLeft = function(scrollVal) {
		if(scrollVal) {
			document.body.scrollLeft = document.documentElement.scrollLeft = scrollVal
		} else {

			return window.scrollX ||
			window.pageXOffset ||
			document.body.scrollLeft ||
			document.documentElement.scrollLeft ||
			0
		}
	}

	window.scrollTop = function(scrollVal) {
		if(scrollVal) {
			document.body.scrollTop = document.documentElement.scrollTop = scrollVal
		} else {

			return window.scrollY ||
			window.pageYOffset ||
			document.body.scrollTop ||
			document.documentElement.scrollTop ||
			0
		}
	}

})()

require('./arrayEach');
require('./elementGetOffset');
require('./elementEvents');
require('./htmlClasses');
