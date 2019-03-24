Element.prototype.addEvent = function(name, fn, capture) {
	if(typeof fn !== 'function') return

	var el = this, eventObj, nameArr, eventName, eventId

	el._event = el._event || {}

	nameArr = name.split('.'); eventName = nameArr[0] || '_'; eventId = nameArr[1]
	if(eventId) el.removeEvent(name, capture)

	capture = !!capture

	eventObj = { id: eventId, fn: fn.bind(el), capture: capture }

	el._event[eventName] = el._event[eventName] || []
	el._event[eventName].push(eventObj)

	el.addEventListener(eventName, eventObj.fn, capture)
}
document.addEvent = Element.prototype.addEvent.bind(document)
window.addEvent = Element.prototype.addEvent.bind(window)


Element.prototype.removeEvent = function(name, capture) {
	var el = this, eventObj, nameArr, eventName, eventId, i, l, toRemove = []
	if(!el._event) return

	nameArr = name.split('.'); eventName = nameArr[0] || '_'; eventId = nameArr[1]
	if(!el._event[eventName]) return

	capture = !!capture

	l = el._event[eventName].length
	if(!l) return

	for(i = 0; i < l; i++) {
		eventObj = el._event[eventName][i]
		if(eventObj.capture === capture && (!eventId || eventObj.id === eventId)) toRemove.push(eventObj)
	}

	l = toRemove.length
	for(i = 0; i < l; i++) {
		eventObj = toRemove[i]

		el.removeEventListener(eventName, eventObj.fn, eventObj.capture)

		el._event[eventName].splice(el._event[eventName].indexOf(eventObj), 1)
	}
}
document.removeEvent = Element.prototype.removeEvent.bind(document)
window.removeEvent = Element.prototype.removeEvent.bind(window)



Element.prototype.trigger = function(name, capture) {
	var el = this, eventObj, nameArr, eventName, eventId, i, l
	if(!el._event) return

	nameArr = name.split('.'); eventName = nameArr[0] || '_'; eventId = nameArr[1]
	if(!el._event[eventName]) return

	capture = !!capture

	l = el._event[eventName].length
	if(!l) return

	for(i = 0; i < l; i++) {
		eventObj = el._event[eventName][i]
		if(eventObj.capture === capture && (!eventId || eventObj.id === eventId))
			eventObj.fn({
				currentTarget: el
			})
	}
}
document.trigger = Element.prototype.trigger.bind(document)
window.trigger = Element.prototype.trigger.bind(window)


// Element.prototype.bindEvent = function(name, args)
// {
// 	if (!this.events || !this.events[name])
// 	return;

// 	args = args || ''
// 	this.events[name](args)
// }
