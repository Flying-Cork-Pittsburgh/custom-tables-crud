/*

	USAGE

	window.addEvent('scroll.HideScroll', throttle(() => {
		this.showItems()
	}, 50));

*/


function throttle(func, limit) {
	let inThrottle
	return function() {
		const args = arguments
		const context = this
		if (!inThrottle) {
			func.apply(context, args)
			inThrottle = true
			setTimeout(() => inThrottle = false, limit)
		}
	}
}

export { throttle };
