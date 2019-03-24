Element.prototype.addClass = function(className) {
	var el = this, tmpArr

	if(el.classList) {
		el.classList.add(className)
	} else {
		if(el.hasClass(className)) return

		tmpArr = el.className.split(' ')
		tmpArr.push(className)
		el.className = tmpArr.join(' ')
	}

	return el
}
NodeList.prototype.addClass = function(className) {
	this.each(function(el) {
		el.addClass(className)
	})
	return this
}
HTMLCollection.prototype.addClass = NodeList.prototype.addClass




Element.prototype.hasClass = function(className) {
	var el = this

	if(el.classList) {
		return el.classList.contains(className)
	} else {
		return ((el.className.split(' ')).indexOf(className) >= 0)
	}
}



Element.prototype.removeClass = function(className) {
	var el = this, tmpArr

	if(el.classList) {
		el.classList.remove(className)
	} else {
		if(!el.hasClass(className)) return

		tmpArr = el.className.split(' ')
		tmpArr.splice(tmpArr.indexOf(className))
		el.className = tmpArr.join(' ')
	}

	return el
}
NodeList.prototype.removeClass = function(className) {
	this.each(function(el) {
		el.removeClass(className)
	})

	return this
}
HTMLCollection.prototype.removeClass = NodeList.prototype.removeClass


/*  later

Element.prototype.toggleClass = function(className) {
	var el = this

	if (el.classList.contains(className)) {
		el.classList.removeClass(className)
	} else {
		el.classList.removeClass(className)
	}
} */
