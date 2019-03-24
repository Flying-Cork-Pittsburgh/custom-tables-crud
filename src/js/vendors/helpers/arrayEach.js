/*!
 * DOMextensions based on EasyPure by dobrapyra (v2017-08-17)
 */

Array.prototype.each = function(fn) {
	if(typeof fn !== 'function') return
	var arr = this, i, l = arr.length, result
	for(i = 0; i < l; i++) {
		result = fn(arr[i], i)
		if(result === true) continue
		if(result === false) break
	}
}

NodeList.prototype.each = Array.prototype.each
HTMLCollection.prototype.each = Array.prototype.each
