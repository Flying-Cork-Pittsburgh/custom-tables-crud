Element.prototype.getOffset = function(relEl, withScroll) {
	var el, offset = { l: 0, t: 0 }
	for(el = this; el && el !== relEl; el = el.offsetParent) {
		offset.l += el.offsetLeft
		offset.t += el.offsetTop
		if(withScroll) {
			if(el.tagName === 'BODY') {
				offset.l -= (
					window.scrollX ||
					window.pageXOffset ||
					document.body.scrollLeft ||
					document.documentElement.scrollLeft ||
					el.scrollLeft ||
					0)
				offset.t -= (
					window.scrollY ||
					window.pageYOffset ||
					document.body.scrollTop ||
					document.documentElement.scrollTop ||
					el.scrollTop ||
					0)
			} else {
				offset.l -= el.scrollLeft
				offset.t -= el.scrollTop
			}
		}
	}
	return offset
}
