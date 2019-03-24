/*

	USAGE

	window.addEvent('scroll.HideScroll', debounce(() => {
		this.showItems()
	}, 50));

*/

function debounce(func, delay) {
	let inDebounce
	return function() {
		const context = this
		const args = arguments
		clearTimeout(inDebounce)
		inDebounce = setTimeout(() => func.apply(context, args), delay)
	}
}

export { debounce };
